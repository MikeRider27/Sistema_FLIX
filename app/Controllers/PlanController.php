<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Validator;
use App\Models\Plan;

final class PlanController extends Controller
{
    private const REGLAS = [
        'nombre'        => 'required|max:80',
        'descripcion'   => 'max:250',
        'duracion_dias' => 'required|int|min:1',
        'precio'        => 'required|numeric|min:0',
    ];
    private const ETIQUETAS = ['nombre' => 'El nombre', 'descripcion' => 'La descripción', 'duracion_dias' => 'La duración', 'precio' => 'El precio'];

    public function index(): void
    {
        $this->vista('planes/index', ['titulo' => 'Planes', 'planes' => Plan::listar()]);
    }

    public function crear(): void
    {
        $this->vista('planes/form', ['titulo' => 'Nuevo plan', 'plan' => ['activo' => true], 'accion' => '/planes']);
    }

    public function guardar(): void
    {
        $d = $this->datos();
        $errores = $this->validar($d, null);
        if ($errores) {
            $this->volverConErrores('/planes/nuevo', $errores, $this->old($d));
        }
        Plan::crear($d);
        $this->redirigir('/planes', 'success', 'Plan creado.');
    }

    public function editar(int $id): void
    {
        $plan = Plan::encontrar($id) ?? $this->abortar(404, 'El plan no existe.');
        $this->vista('planes/form', ['titulo' => 'Editar plan', 'plan' => $plan, 'accion' => "/planes/$id"]);
    }

    public function actualizar(int $id): void
    {
        Plan::encontrar($id) ?? $this->abortar(404, 'El plan no existe.');
        $d = $this->datos();
        $errores = $this->validar($d, $id);
        if ($errores) {
            $this->volverConErrores("/planes/$id/editar", $errores, $this->old($d));
        }
        Plan::actualizar($id, $d);
        $this->redirigir('/planes', 'success', 'Plan actualizado.');
    }

    public function eliminar(int $id): void
    {
        Plan::encontrar($id) ?? $this->abortar(404, 'El plan no existe.');
        try {
            Plan::eliminar($id);
        } catch (\PDOException $e) {
            if ($e->getCode() === '23503') {
                $this->redirigir('/planes', 'danger', 'No se puede eliminar: hay membresías con este plan. Desactivalo en su lugar.');
            }
            throw $e;
        }
        $this->redirigir('/planes', 'success', 'Plan eliminado.');
    }

    private function datos(): array
    {
        return $this->entrada(['nombre', 'descripcion', 'duracion_dias', 'precio']) + ['activo' => $this->check('activo')];
    }

    private function old(array $d): array
    {
        return array_merge($d, ['activo' => $d['activo'] ? '1' : null]);
    }

    private function validar(array $d, ?int $id): array
    {
        $errores = Validator::validar($d, self::REGLAS, self::ETIQUETAS);
        if (!isset($errores['nombre']) && Plan::nombreEnUso((string) $d['nombre'], $id)) {
            $errores['nombre'] = 'Ya existe un plan con ese nombre.';
        }
        return $errores;
    }
}
