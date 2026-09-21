<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Validator;
use App\Models\Entrenador;

final class EntrenadorController extends Controller
{
    private const REGLAS = [
        'ci'           => 'required|max:20',
        'nombre'       => 'required|max:80',
        'apellido'     => 'required|max:80',
        'telefono'     => 'max:30',
        'email'        => 'email|max:120',
        'especialidad' => 'max:100',
    ];
    private const ETIQUETAS = ['ci' => 'La cédula', 'nombre' => 'El nombre', 'apellido' => 'El apellido', 'email' => 'El correo', 'especialidad' => 'La especialidad'];

    public function index(): void
    {
        $this->vista('entrenadores/index', ['titulo' => 'Entrenadores', 'entrenadores' => Entrenador::listar()]);
    }

    public function crear(): void
    {
        $this->vista('entrenadores/form', ['titulo' => 'Nuevo entrenador', 'entrenador' => ['activo' => true], 'accion' => '/entrenadores']);
    }

    public function guardar(): void
    {
        $d = $this->datos();
        $errores = $this->validar($d, null);
        if ($errores) {
            $this->volverConErrores('/entrenadores/nuevo', $errores, $this->old($d));
        }
        Entrenador::crear($d);
        $this->redirigir('/entrenadores', 'success', 'Entrenador registrado.');
    }

    public function editar(int $id): void
    {
        $e = Entrenador::encontrar($id) ?? $this->abortar(404, 'El entrenador no existe.');
        $this->vista('entrenadores/form', ['titulo' => 'Editar entrenador', 'entrenador' => $e, 'accion' => "/entrenadores/$id"]);
    }

    public function actualizar(int $id): void
    {
        Entrenador::encontrar($id) ?? $this->abortar(404, 'El entrenador no existe.');
        $d = $this->datos();
        $errores = $this->validar($d, $id);
        if ($errores) {
            $this->volverConErrores("/entrenadores/$id/editar", $errores, $this->old($d));
        }
        Entrenador::actualizar($id, $d);
        $this->redirigir('/entrenadores', 'success', 'Entrenador actualizado.');
    }

    public function eliminar(int $id): void
    {
        Entrenador::encontrar($id) ?? $this->abortar(404, 'El entrenador no existe.');
        try {
            Entrenador::eliminar($id);
        } catch (\PDOException $e) {
            if ($e->getCode() === '23503') {
                $this->redirigir('/entrenadores', 'danger', 'No se puede eliminar: tiene membresías asignadas. Desactivalo en su lugar.');
            }
            throw $e;
        }
        $this->redirigir('/entrenadores', 'success', 'Entrenador eliminado.');
    }

    private function datos(): array
    {
        return $this->entrada(['ci', 'nombre', 'apellido', 'telefono', 'email', 'especialidad']) + ['activo' => $this->check('activo')];
    }

    private function old(array $d): array
    {
        return array_merge($d, ['activo' => $d['activo'] ? '1' : null]);
    }

    private function validar(array $d, ?int $id): array
    {
        $errores = Validator::validar($d, self::REGLAS, self::ETIQUETAS);
        if (!isset($errores['ci']) && Entrenador::ciEnUso((string) $d['ci'], $id)) {
            $errores['ci'] = 'Ya existe un entrenador con esa cédula.';
        }
        return $errores;
    }
}
