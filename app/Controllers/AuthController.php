<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Flash;
use App\Core\View;

final class AuthController extends Controller
{
    public function formulario(): void
    {
        if (Auth::check()) {
            $this->redirigir('/');
        }
        View::render('auth/login', ['titulo' => 'Iniciar sesión'], 'simple');
    }

    public function login(): void
    {
        $d = $this->entrada(['usuario', 'clave']);
        // La clave no se recorta ni se normaliza: se toma tal cual
        $clave = is_string($_POST['clave'] ?? null) ? $_POST['clave'] : '';

        if (empty($d['usuario']) || $clave === '' || !Auth::intentar($d['usuario'], $clave)) {
            $this->volverConErrores('/login', ['general' => 'Usuario o contraseña incorrectos.'], ['usuario' => $d['usuario']]);
        }
        $this->redirigir(Flash::tomarDestino());
    }

    public function logout(): void
    {
        Auth::salir();
        $this->redirigir('/login');
    }
}
