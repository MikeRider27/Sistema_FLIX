<?php
declare(strict_types=1);

use App\Controllers\{AsistenciaController, AuthController, DashboardController, EntrenadorController,
    MembresiaController, PagoController, PlanController, SocioController, UsuarioController};
use App\Core\Router;

$r = new Router();
$admin = ['admin'];

// Acceso
$r->get('/login', [AuthController::class, 'formulario'], 'publico');
$r->post('/login', [AuthController::class, 'login'], 'publico');
$r->post('/logout', [AuthController::class, 'logout']);

$r->get('/', [DashboardController::class, 'index']);

// Socios
$r->get('/socios', [SocioController::class, 'index']);
$r->get('/socios/nuevo', [SocioController::class, 'crear']);
$r->post('/socios', [SocioController::class, 'guardar']);
$r->get('/socios/{id}', [SocioController::class, 'ver']);
$r->get('/socios/{id}/editar', [SocioController::class, 'editar']);
$r->post('/socios/{id}', [SocioController::class, 'actualizar']);
$r->post('/socios/{id}/eliminar', [SocioController::class, 'eliminar'], $admin);

// Membresías y pagos
$r->get('/membresias', [MembresiaController::class, 'index']);
$r->get('/membresias/nueva', [MembresiaController::class, 'crear']);
$r->post('/membresias', [MembresiaController::class, 'guardar']);
$r->get('/membresias/{id}', [MembresiaController::class, 'ver']);
$r->post('/membresias/{id}/cancelar', [MembresiaController::class, 'cancelar'], $admin);
$r->get('/membresias/{id}/pagar', [PagoController::class, 'crear']);
$r->post('/membresias/{id}/pagar', [PagoController::class, 'guardar']);
$r->get('/pagos', [PagoController::class, 'index']);

// Asistencias
$r->get('/asistencias', [AsistenciaController::class, 'index']);
$r->post('/asistencias', [AsistenciaController::class, 'registrar']);

// Administración
$r->get('/planes', [PlanController::class, 'index'], $admin);
$r->get('/planes/nuevo', [PlanController::class, 'crear'], $admin);
$r->post('/planes', [PlanController::class, 'guardar'], $admin);
$r->get('/planes/{id}/editar', [PlanController::class, 'editar'], $admin);
$r->post('/planes/{id}', [PlanController::class, 'actualizar'], $admin);
$r->post('/planes/{id}/eliminar', [PlanController::class, 'eliminar'], $admin);

$r->get('/entrenadores', [EntrenadorController::class, 'index'], $admin);
$r->get('/entrenadores/nuevo', [EntrenadorController::class, 'crear'], $admin);
$r->post('/entrenadores', [EntrenadorController::class, 'guardar'], $admin);
$r->get('/entrenadores/{id}/editar', [EntrenadorController::class, 'editar'], $admin);
$r->post('/entrenadores/{id}', [EntrenadorController::class, 'actualizar'], $admin);
$r->post('/entrenadores/{id}/eliminar', [EntrenadorController::class, 'eliminar'], $admin);

$r->get('/usuarios', [UsuarioController::class, 'index'], $admin);
$r->get('/usuarios/nuevo', [UsuarioController::class, 'crear'], $admin);
$r->post('/usuarios', [UsuarioController::class, 'guardar'], $admin);
$r->get('/usuarios/{id}/editar', [UsuarioController::class, 'editar'], $admin);
$r->post('/usuarios/{id}', [UsuarioController::class, 'actualizar'], $admin);

return $r;
