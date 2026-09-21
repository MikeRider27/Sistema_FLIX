<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Validator;
use App\Models\Entrenador;
use App\Models\Membresia;
use App\Models\Pago;
use App\Models\Plan;
use App\Models\Socio;

final class MembresiaController extends Controller
{
    private const ESTADOS = ['vigente', 'pendiente', 'vencida', 'cancelada'];

    public function index(): void
    {
        $estado = (string) ($_GET['estado'] ?? '');
        $this->vista('membresias/index', [
            'titulo'     => 'Membresías',
            'membresias' => Membresia::listar(in_array($estado, self::ESTADOS, true) ? $estado : null),
            'estado'     => $estado,
            'estados'    => self::ESTADOS,
        ]);
    }

    public function crear(): void
    {
        $idSocio = (int) ($_GET['socio'] ?? 0);
        $inicio = date('Y-m-d');
        if ($idSocio) {
            // Renovación: arranca al día siguiente del último vencimiento si todavía está vigente
            $fin = Membresia::ultimoFin($idSocio);
            if ($fin !== null && $fin >= $inicio) {
                $inicio = (new \DateTimeImmutable($fin))->modify('+1 day')->format('Y-m-d');
            }
        }
        $this->vista('membresias/form', [
            'titulo'        => 'Nueva membresía',
            'valores'       => ['id_socio' => $idSocio ?: '', 'fecha_inicio' => $inicio, 'metodo' => 'efectivo'],
            'socios'        => Socio::opciones(),
            'planes'        => Plan::opciones(),
            'entrenadores'  => Entrenador::opciones(),
        ]);
    }

    public function guardar(): void
    {
        $d = $this->entrada(['id_socio', 'id_plan', 'id_entrenador', 'fecha_inicio', 'monto', 'metodo', 'referencia']);
        $errores = Validator::validar($d, [
            'id_socio'      => 'required|int',
            'id_plan'       => 'required|int',
            'id_entrenador' => 'int',
            'fecha_inicio'  => 'required|date',
            'monto'         => 'numeric|min:0',
            'metodo'        => 'in:efectivo,tarjeta,transferencia',
            'referencia'    => 'max:100',
        ], ['id_socio' => 'El socio', 'id_plan' => 'El plan', 'fecha_inicio' => 'La fecha de inicio', 'monto' => 'El pago inicial']);

        $socio = isset($errores['id_socio']) ? null : Socio::encontrar((int) $d['id_socio']);
        $plan = isset($errores['id_plan']) ? null : Plan::encontrar((int) $d['id_plan']);
        if (!isset($errores['id_socio']) && ($socio === null || $socio['estado'] !== 'activo')) {
            $errores['id_socio'] = 'Elegí un socio activo.';
        }
        if (!isset($errores['id_plan']) && ($plan === null || !$plan['activo'])) {
            $errores['id_plan'] = 'Elegí un plan activo.';
        }
        if (!isset($errores['id_entrenador']) && $d['id_entrenador'] !== null && Entrenador::encontrar((int) $d['id_entrenador']) === null) {
            $errores['id_entrenador'] = 'El entrenador no existe.';
        }
        if (!$errores) {
            $fin = (new \DateTimeImmutable($d['fecha_inicio']))->modify('+' . ((int) $plan['duracion_dias'] - 1) . ' days')->format('Y-m-d');
            if (Membresia::haySolapamiento((int) $socio['id_socio'], $d['fecha_inicio'], $fin)) {
                $errores['fecha_inicio'] = 'El socio ya tiene una membresía activa que se superpone con ese período.';
            }
            if ($d['monto'] !== null && (float) $d['monto'] > (float) $plan['precio']) {
                $errores['monto'] = 'El pago inicial no puede superar el precio del plan (' . dinero($plan['precio']) . ').';
            }
        }
        if ($errores) {
            $this->volverConErrores('/membresias/nueva', $errores, $d);
        }

        try {
            $id = Membresia::crear((int) $socio['id_socio'], $plan, $d['id_entrenador'] !== null ? (int) $d['id_entrenador'] : null, $d['fecha_inicio']);
        } catch (\PDOException $e) {
            if ($e->getCode() === '23P01') { // exclusion_violation: otra carga simultánea
                $this->volverConErrores('/membresias/nueva', ['fecha_inicio' => 'El socio ya tiene una membresía activa en ese período.'], $d);
            }
            throw $e;
        }

        if ($d['monto'] !== null && (float) $d['monto'] > 0) {
            Pago::registrar($id, $d['monto'], $d['metodo'] ?? 'efectivo', $d['referencia'], (int) Auth::id());
        }
        $this->redirigir("/membresias/$id", 'success', 'Membresía registrada.');
    }

    public function ver(int $id): void
    {
        $m = Membresia::encontrar($id) ?? $this->abortar(404, 'La membresía no existe.');
        $this->vista('membresias/ver', ['titulo' => 'Membresía #' . $id, 'm' => $m, 'pagos' => Pago::porMembresia($id)]);
    }

    public function cancelar(int $id): void
    {
        $m = Membresia::encontrar($id) ?? $this->abortar(404, 'La membresía no existe.');
        if ($m['estado'] === 'cancelada') {
            $this->redirigir("/membresias/$id", 'warning', 'La membresía ya estaba cancelada.');
        }
        Membresia::cancelar($id);
        $this->redirigir("/membresias/$id", 'success', 'Membresía cancelada.');
    }
}
