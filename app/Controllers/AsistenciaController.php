<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Asistencia;
use App\Models\Membresia;
use App\Models\Socio;

final class AsistenciaController extends Controller
{
    public function index(): void
    {
        $v = (string) ($_GET['fecha'] ?? '');
        $f = \DateTimeImmutable::createFromFormat('!Y-m-d', $v);
        $fecha = $f && $f->format('Y-m-d') === $v ? $v : date('Y-m-d');

        $this->vista('asistencias/index', [
            'titulo'      => 'Asistencias',
            'fecha'       => $fecha,
            'asistencias' => Asistencia::delDia($fecha),
        ]);
    }

    /** Check-in por cédula: solo socios activos con membresía vigente y una entrada por día. */
    public function registrar(): void
    {
        $ci = trim((string) ($_POST['ci'] ?? ''));
        if ($ci === '') {
            $this->redirigir('/asistencias', 'danger', 'Ingresá la cédula del socio.');
        }
        $socio = Socio::porCi($ci);
        if ($socio === null) {
            $this->redirigir('/asistencias', 'danger', "No existe un socio con la cédula $ci.");
        }
        $nombre = $socio['nombre'] . ' ' . $socio['apellido'];
        if ($socio['estado'] !== 'activo') {
            $this->redirigir('/asistencias', 'danger', "$nombre está inactivo.");
        }
        $id = (int) $socio['id_socio'];
        $m = Membresia::vigenteDe($id);
        if ($m === null) {
            $this->redirigir('/asistencias', 'danger', "$nombre no tiene una membresía vigente.");
        }
        if (Asistencia::yaIngresoHoy($id)) {
            $this->redirigir('/asistencias', 'warning', "$nombre ya registró su ingreso hoy.");
        }

        Asistencia::registrar($id, Auth::id());
        $aviso = $m['saldo'] > 0 ? ' Atención: tiene un saldo pendiente de ' . dinero($m['saldo']) . '.' : '';
        $this->redirigir('/asistencias', 'success', "Ingreso registrado: $nombre (vence " . fecha($m['fecha_fin']) . ").$aviso");
    }
}
