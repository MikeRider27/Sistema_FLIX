<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Pago extends Model
{
    public static function listar(string $desde, string $hasta): array
    {
        return self::todos(
            "SELECT pg.*, s.nombre || ' ' || s.apellido AS socio, s.ci, p.nombre AS plan, u.nombre AS cajero
             FROM pagos pg
             JOIN membresias m ON m.id_membresia = pg.id_membresia
             JOIN socios s ON s.id_socio = m.id_socio
             JOIN planes p ON p.id_plan = m.id_plan
             JOIN usuarios u ON u.id_usuario = pg.id_usuario
             WHERE pg.fecha_pago::date BETWEEN :d::date AND :h::date
             ORDER BY pg.fecha_pago DESC",
            ['d' => $desde, 'h' => $hasta]
        );
    }

    public static function porMembresia(int $idMembresia): array
    {
        return self::todos(
            'SELECT pg.*, u.nombre AS cajero FROM pagos pg JOIN usuarios u ON u.id_usuario = pg.id_usuario
             WHERE pg.id_membresia = :id ORDER BY pg.fecha_pago DESC',
            ['id' => $idMembresia]
        );
    }

    public static function porSocio(int $idSocio): array
    {
        return self::todos(
            'SELECT pg.*, p.nombre AS plan FROM pagos pg
             JOIN membresias m ON m.id_membresia = pg.id_membresia
             JOIN planes p ON p.id_plan = m.id_plan
             WHERE m.id_socio = :id ORDER BY pg.fecha_pago DESC',
            ['id' => $idSocio]
        );
    }

    /**
     * Registra un pago validando el saldo con la fila bloqueada, para que dos pagos
     * simultáneos no superen el precio de la membresía.
     * @return string|null mensaje de error, o null si se registró
     */
    public static function registrar(int $idMembresia, string $monto, string $metodo, ?string $referencia, int $idUsuario): ?string
    {
        $db = self::db();
        $db->beginTransaction();
        try {
            $m = self::uno("SELECT precio, estado FROM membresias WHERE id_membresia = :id FOR UPDATE", ['id' => $idMembresia]);
            if ($m === null) {
                $db->rollBack();
                return 'La membresía no existe.';
            }
            if ($m['estado'] === 'cancelada') {
                $db->rollBack();
                return 'No se pueden registrar pagos en una membresía cancelada.';
            }
            $pagado = (float) self::valor('SELECT COALESCE(SUM(monto), 0) FROM pagos WHERE id_membresia = :id', ['id' => $idMembresia]);
            $saldo = (float) $m['precio'] - $pagado;
            if ((float) $monto > $saldo + 0.001) {
                $db->rollBack();
                return 'El monto supera el saldo pendiente (' . dinero($saldo) . ').';
            }
            self::ejecutar(
                'INSERT INTO pagos (id_membresia, monto, metodo, referencia, id_usuario) VALUES (:m, :monto, :metodo, :ref, :u)',
                ['m' => $idMembresia, 'monto' => $monto, 'metodo' => $metodo, 'ref' => $referencia, 'u' => $idUsuario]
            );
            $db->commit();
            return null;
        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            throw $e;
        }
    }

    public static function ingresosMes(): float
    {
        return (float) self::valor("SELECT COALESCE(SUM(monto), 0) FROM pagos WHERE date_trunc('month', fecha_pago) = date_trunc('month', now())");
    }
}
