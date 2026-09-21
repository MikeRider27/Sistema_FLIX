<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Asistencia;
use App\Models\Membresia;
use App\Models\Pago;
use App\Models\Socio;

final class DashboardController extends Controller
{
    public function index(): void
    {
        $this->vista('dashboard/index', [
            'titulo'        => 'Panel',
            'sociosActivos' => Socio::contarActivos(),
            'vigentes'      => Membresia::contarVigentes(),
            'asistenciasHoy'=> Asistencia::contarHoy(),
            'ingresosMes'   => Pago::ingresosMes(),
            'porVencer'     => Membresia::porVencer(7),
            'conSaldo'      => Membresia::conSaldo(),
            'ultimas'       => Asistencia::ultimas(8),
        ]);
    }
}
