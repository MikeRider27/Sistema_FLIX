<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Membresia extends Model
{
    public static function listar(?string $estado = null): array
    {
        $sql = 'SELECT * FROM v_membresias';
        $p = [];
        if ($estado !== null && $estado !== '') {
            $sql .= ' WHERE estado_real = :e';
            $p['e'] = $estado;
        }
        return self::todos($sql . ' ORDER BY fecha_fin DESC, id_membresia DESC', $p);
    }

    public static function encontrar(int $id): ?array
    {
        return self::uno('SELECT * FROM v_membresias WHERE id_membresia = :id', ['id' => $id]);
    }

    public static function porSocio(int $idSocio): array
    {
        return self::todos('SELECT * FROM v_membresias WHERE id_socio = :id ORDER BY fecha_inicio DESC', ['id' => $idSocio]);
    }

    /** Membresía vigente hoy del socio, si la hay. */
    public static function vigenteDe(int $idSocio): ?array
    {
        return self::uno("SELECT * FROM v_membresias WHERE id_socio = :id AND estado_real = 'vigente' ORDER BY fecha_fin DESC LIMIT 1", ['id' => $idSocio]);
    }

    /** Última fecha de fin entre las membresías activas del socio (para sugerir el inicio de una renovación). */
    public static function ultimoFin(int $idSocio): ?string
    {
        $v = self::valor("SELECT max(fecha_fin) FROM membresias WHERE id_socio = :id AND estado = 'activa'", ['id' => $idSocio]);
        return $v ?: null;
    }

    public static function haySolapamiento(int $idSocio, string $inicio, string $fin): bool
    {
        return (bool) self::valor(
            "SELECT 1 FROM membresias
             WHERE id_socio = :s AND estado = 'activa' AND daterange(fecha_inicio, fecha_fin, '[]') && daterange(:i::date, :f::date, '[]')",
            ['s' => $idSocio, 'i' => $inicio, 'f' => $fin]
        );
    }

    public static function crear(int $idSocio, array $plan, ?int $idEntrenador, string $inicio): int
    {
        return self::insertar(
            "INSERT INTO membresias (id_socio, id_plan, id_entrenador, fecha_inicio, fecha_fin, precio)
             VALUES (:s, :p, :e, :i::date, :i::date + (:dias::int - 1), :precio) RETURNING id_membresia",
            [
                's'      => $idSocio,
                'p'      => $plan['id_plan'],
                'e'      => $idEntrenador,
                'i'      => $inicio,
                'dias'   => $plan['duracion_dias'],
                'precio' => $plan['precio'],
            ]
        );
    }

    public static function cancelar(int $id): void
    {
        self::ejecutar("UPDATE membresias SET estado = 'cancelada' WHERE id_membresia = :id", ['id' => $id]);
    }

    public static function porVencer(int $dias = 7): array
    {
        return self::todos(
            "SELECT * FROM v_membresias
             WHERE estado_real = 'vigente' AND fecha_fin <= CURRENT_DATE + :d::int
             ORDER BY fecha_fin",
            ['d' => $dias]
        );
    }

    public static function conSaldo(): array
    {
        return self::todos("SELECT * FROM v_membresias WHERE estado <> 'cancelada' AND saldo > 0 ORDER BY fecha_fin");
    }

    public static function contarVigentes(): int
    {
        return (int) self::valor("SELECT count(*) FROM v_membresias WHERE estado_real = 'vigente'");
    }
}
