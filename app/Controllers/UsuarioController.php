<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Validator;
use App\Models\Usuario;

final class UsuarioController extends Controller
{
    private const ETIQUETAS = ['nombre' => 'El nombre', 'usuario' => 'El usuario', 'rol' => 'El rol', 'clave' => 'La contraseña'];

    public function index(): void
    {
        $this->vista('usuarios/index', ['titulo' => 'Usuarios', 'usuarios' => Usuario::listar()]);
    }

    public function crear(): void
    {
        $this->vista('usuarios/form', ['titulo' => 'Nuevo usuario', 'usuario' => ['activo' => true, 'rol' => 'recepcion'], 'accion' => '/usuarios']);
    }

    public function guardar(): void
    {
        $d = $this->datos();
        $clave = $this->clave();
        $errores = Validator::validar($d + ['clave' => $clave], [
            'nombre'  => 'required|max:100',
            'usuario' => 'required|max:50',
            'rol'     => 'required|in:admin,recepcion',
            'clave'   => 'required',
        ], self::ETIQUETAS);
        if (!isset($errores['usuario']) && !preg_match('/^[A-Za-z0-9._-]+$/', (string) $d['usuario'])) {
            $errores['usuario'] = 'El usuario solo admite letras, números, punto, guion y guion bajo.';
        }
        if (!isset($errores['usuario']) && Usuario::usuarioEnUso((string) $d['usuario'])) {
            $errores['usuario'] = 'Ese nombre de usuario ya existe.';
        }
        if (!isset($errores['clave']) && mb_strlen($clave) < 8) {
            $errores['clave'] = 'La contraseña debe tener al menos 8 caracteres.';
        }
        if ($errores) {
            $this->volverConErrores('/usuarios/nuevo', $errores, $this->old($d));
        }
        Usuario::crear($d, $clave);
        $this->redirigir('/usuarios', 'success', 'Usuario creado.');
    }

    public function editar(int $id): void
    {
        $u = Usuario::encontrar($id) ?? $this->abortar(404, 'El usuario no existe.');
        $this->vista('usuarios/form', ['titulo' => 'Editar usuario', 'usuario' => $u, 'accion' => "/usuarios/$id"]);
    }

    public function actualizar(int $id): void
    {
        $actual = Usuario::encontrar($id) ?? $this->abortar(404, 'El usuario no existe.');
        $d = $this->datos();
        $clave = $this->clave();
        $errores = Validator::validar($d, ['nombre' => 'required|max:100', 'rol' => 'required|in:admin,recepcion'], self::ETIQUETAS);
        if ($clave !== '' && mb_strlen($clave) < 8) {
            $errores['clave'] = 'La contraseña debe tener al menos 8 caracteres.';
        }
        // Nadie puede quedarse sin administradores activos
        $dejaDeSerAdmin = $actual['rol'] === 'admin' && $actual['activo'] && ($d['rol'] !== 'admin' || !$d['activo']);
        if ($dejaDeSerAdmin && Usuario::contarAdminsActivos() <= 1) {
            $errores['rol'] = 'Debe quedar al menos un administrador activo.';
        }
        if ($errores) {
            $this->volverConErrores("/usuarios/$id/editar", $errores, $this->old($d));
        }
        Usuario::actualizar($id, $d);
        if ($clave !== '') {
            Usuario::cambiarClave($id, $clave);
        }
        if ($id === Auth::id()) {
            $_SESSION['usuario']['nombre'] = $d['nombre'];
            $_SESSION['usuario']['rol'] = $d['rol'];
        }
        $this->redirigir('/usuarios', 'success', 'Usuario actualizado.');
    }

    private function datos(): array
    {
        return $this->entrada(['nombre', 'usuario', 'rol']) + ['activo' => $this->check('activo')];
    }

    private function clave(): string
    {
        return is_string($_POST['clave'] ?? null) ? $_POST['clave'] : '';
    }

    private function old(array $d): array
    {
        return array_merge($d, ['activo' => $d['activo'] ? '1' : null]);
    }
}
