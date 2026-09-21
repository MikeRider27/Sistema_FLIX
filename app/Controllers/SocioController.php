<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Validator;
use App\Models\Asistencia;
use App\Models\Membresia;
use App\Models\Pago;
use App\Models\Socio;

final class SocioController extends Controller
{
    private const CAMPOS = ['ci', 'nombre', 'apellido', 'fecha_nacimiento', 'sexo', 'telefono', 'email', 'direccion', 'contacto_emergencia', 'notas', 'estado'];

    private const REGLAS = [
        'ci'                  => 'required|max:20',
        'nombre'              => 'required|max:80',
        'apellido'            => 'required|max:80',
        'fecha_nacimiento'    => 'date',
        'sexo'                => 'in:M,F,O',
        'telefono'            => 'max:30',
        'email'               => 'email|max:120',
        'direccion'           => 'max:200',
        'contacto_emergencia' => 'max:150',
        'estado'              => 'required|in:activo,inactivo',
    ];

    private const ETIQUETAS = ['ci' => 'La cédula', 'nombre' => 'El nombre', 'apellido' => 'El apellido', 'fecha_nacimiento' => 'La fecha de nacimiento', 'email' => 'El correo', 'estado' => 'El estado'];

    public function index(): void
    {
        $q = trim((string) ($_GET['q'] ?? ''));
        $estado = (string) ($_GET['estado'] ?? '');
        $this->vista('socios/index', [
            'titulo' => 'Socios',
            'socios' => Socio::listar($q, $estado),
            'q'      => $q,
            'estado' => $estado,
        ]);
    }

    public function crear(): void
    {
        $this->vista('socios/form', ['titulo' => 'Nuevo socio', 'socio' => ['estado' => 'activo'], 'accion' => '/socios']);
    }

    public function guardar(): void
    {
        $d = $this->entrada(self::CAMPOS);
        $errores = $this->validar($d, null);
        if ($errores) {
            $this->volverConErrores('/socios/nuevo', $errores, $d);
        }
        $id = Socio::crear($d);
        $this->redirigir("/socios/$id", 'success', 'Socio registrado.');
    }

    public function ver(int $id): void
    {
        $socio = Socio::encontrar($id) ?? $this->abortar(404, 'El socio no existe.');
        $this->vista('socios/ver', [
            'titulo'      => $socio['nombre'] . ' ' . $socio['apellido'],
            'socio'       => $socio,
            'membresias'  => Membresia::porSocio($id),
            'pagos'       => Pago::porSocio($id),
            'asistencias' => Asistencia::porSocio($id),
        ]);
    }

    public function editar(int $id): void
    {
        $socio = Socio::encontrar($id) ?? $this->abortar(404, 'El socio no existe.');
        $this->vista('socios/form', ['titulo' => 'Editar socio', 'socio' => $socio, 'accion' => "/socios/$id"]);
    }

    public function actualizar(int $id): void
    {
        Socio::encontrar($id) ?? $this->abortar(404, 'El socio no existe.');
        $d = $this->entrada(self::CAMPOS);
        $errores = $this->validar($d, $id);
        if ($errores) {
            $this->volverConErrores("/socios/$id/editar", $errores, $d);
        }
        Socio::actualizar($id, $d);
        $this->redirigir("/socios/$id", 'success', 'Datos del socio actualizados.');
    }

    public function eliminar(int $id): void
    {
        Socio::encontrar($id) ?? $this->abortar(404, 'El socio no existe.');
        try {
            Socio::eliminar($id);
        } catch (\PDOException $e) {
            if ($e->getCode() === '23503') { // foreign_key_violation
                $this->redirigir("/socios/$id", 'danger', 'No se puede eliminar: tiene membresías o asistencias registradas. Marcalo como inactivo.');
            }
            throw $e;
        }
        $this->redirigir('/socios', 'success', 'Socio eliminado.');
    }

    private function validar(array $d, ?int $id): array
    {
        $errores = Validator::validar($d, self::REGLAS, self::ETIQUETAS);
        if (!isset($errores['ci']) && Socio::ciEnUso((string) $d['ci'], $id)) {
            $errores['ci'] = 'Ya existe un socio con esa cédula.';
        }
        return $errores;
    }
}
