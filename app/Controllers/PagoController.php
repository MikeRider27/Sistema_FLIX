<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Validator;
use App\Models\Membresia;
use App\Models\Pago;

final class PagoController extends Controller
{
    public function index(): void
    {
        $hoy = date('Y-m-d');
        $desde = $this->fechaGet('desde', date('Y-m-01'));
        $hasta = $this->fechaGet('hasta', $hoy);
        $pagos = Pago::listar($desde, $hasta);
        $this->vista('pagos/index', [
            'titulo' => 'Pagos',
            'pagos'  => $pagos,
            'desde'  => $desde,
            'hasta'  => $hasta,
            'total'  => array_sum(array_column($pagos, 'monto')),
        ]);
    }

    public function crear(int $idMembresia): void
    {
        $m = Membresia::encontrar($idMembresia) ?? $this->abortar(404, 'La membresía no existe.');
        $this->vista('pagos/form', ['titulo' => 'Registrar pago', 'm' => $m]);
    }

    public function guardar(int $idMembresia): void
    {
        Membresia::encontrar($idMembresia) ?? $this->abortar(404, 'La membresía no existe.');
        $d = $this->entrada(['monto', 'metodo', 'referencia']);
        $errores = Validator::validar($d, [
            'monto'      => 'required|numeric|min:0.01',
            'metodo'     => 'required|in:efectivo,tarjeta,transferencia',
            'referencia' => 'max:100',
        ], ['monto' => 'El monto', 'metodo' => 'El método']);

        if (!$errores) {
            $error = Pago::registrar($idMembresia, $d['monto'], $d['metodo'], $d['referencia'], (int) Auth::id());
            if ($error !== null) {
                $errores['monto'] = $error;
            }
        }
        if ($errores) {
            $this->volverConErrores("/membresias/$idMembresia/pagar", $errores, $d);
        }
        $this->redirigir("/membresias/$idMembresia", 'success', 'Pago registrado.');
    }

    private function fechaGet(string $clave, string $def): string
    {
        $v = (string) ($_GET[$clave] ?? '');
        $f = \DateTimeImmutable::createFromFormat('!Y-m-d', $v);
        return $f && $f->format('Y-m-d') === $v ? $v : $def;
    }
}
