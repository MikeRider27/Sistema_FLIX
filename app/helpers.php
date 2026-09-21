<?php
declare(strict_types=1);

use App\Core\Auth;
use App\Core\Config;
use App\Core\Csrf;
use App\Core\Flash;

function e(mixed $v): string
{
    return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function url(string $ruta = ''): string
{
    return '/' . ltrim($ruta, '/');
}

function asset(string $ruta): string
{
    return '/assets/' . ltrim($ruta, '/');
}

function csrf(): string
{
    return Csrf::campo();
}

function dinero(float|int|string|null $v): string
{
    return Config::get('app.moneda') . ' ' . number_format((float) $v, 0, ',', '.');
}

function fecha(?string $v, string $formato = 'd/m/Y'): string
{
    return $v ? (new DateTimeImmutable($v))->format($formato) : '—';
}

function fecha_hora(?string $v): string
{
    return fecha($v, 'd/m/Y H:i');
}

function es_admin(): bool
{
    return Auth::tieneRol('admin');
}

function menu_activo(string $prefijo): string
{
    $ruta = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $activo = $prefijo === '/' ? $ruta === '/' : str_starts_with($ruta, $prefijo);
    return $activo ? 'active' : '';
}

/** Clase de etiqueta Bootstrap según el estado de una membresía. */
function badge_estado(string $estado): string
{
    $clase = match ($estado) {
        'vigente', 'activo', 'activa' => 'label-success',
        'pendiente'                   => 'label-info',
        'vencida'                     => 'label-warning',
        'cancelada', 'inactivo'       => 'label-danger',
        default                       => 'label-default',
    };
    return '<span class="label ' . $clase . '">' . e(ucfirst($estado)) . '</span>';
}

/**
 * Helpers de formulario: repueblan con lo enviado (old) o con $opts['valor'] y muestran errores.
 * Opciones: tipo, valor, requerido, col (ancho bootstrap), placeholder, step, min, max, extra (atributos crudos)
 */
function campo(string $name, string $label, array $opts = []): string
{
    $tipo = $opts['tipo'] ?? 'text';
    $valor = Flash::old($name, $opts['valor'] ?? '');
    $error = Flash::error($name);
    $attrs = '';
    foreach (['placeholder', 'step', 'min', 'max', 'maxlength'] as $a) {
        if (isset($opts[$a])) {
            $attrs .= ' ' . $a . '="' . e($opts[$a]) . '"';
        }
    }
    if (!empty($opts['requerido'])) {
        $attrs .= ' required';
    }
    $attrs .= ' ' . ($opts['extra'] ?? '');

    return '<div class="col-md-' . (int) ($opts['col'] ?? 6) . '"><div class="form-group' . ($error ? ' has-error' : '') . '">'
        . '<label for="f_' . e($name) . '">' . e($label) . (!empty($opts['requerido']) ? ' *' : '') . '</label>'
        . '<input class="form-control" id="f_' . e($name) . '" name="' . e($name) . '" type="' . e($tipo) . '" value="' . e($valor) . '"' . $attrs . '>'
        . ($error ? '<span class="help-block">' . e($error) . '</span>' : '')
        . '</div></div>';
}

/** @param array<string|int,string> $opciones valor => texto */
function selector(string $name, string $label, array $opciones, array $opts = []): string
{
    $sel = (string) Flash::old($name, $opts['valor'] ?? '');
    $error = Flash::error($name);
    $html = '<div class="col-md-' . (int) ($opts['col'] ?? 6) . '"><div class="form-group' . ($error ? ' has-error' : '') . '">'
        . '<label for="f_' . e($name) . '">' . e($label) . (!empty($opts['requerido']) ? ' *' : '') . '</label>'
        . '<select class="form-control" id="f_' . e($name) . '" name="' . e($name) . '"' . (!empty($opts['requerido']) ? ' required' : '') . ' ' . ($opts['extra'] ?? '') . '>';
    if (!empty($opts['vacio'])) {
        $html .= '<option value="">' . e($opts['vacio']) . '</option>';
    }
    foreach ($opciones as $v => $texto) {
        $html .= '<option value="' . e($v) . '"' . ((string) $v === $sel ? ' selected' : '') . '>' . e($texto) . '</option>';
    }
    return $html . '</select>' . ($error ? '<span class="help-block">' . e($error) . '</span>' : '') . '</div></div>';
}

function area(string $name, string $label, array $opts = []): string
{
    $valor = Flash::old($name, $opts['valor'] ?? '');
    $error = Flash::error($name);
    return '<div class="col-md-' . (int) ($opts['col'] ?? 12) . '"><div class="form-group' . ($error ? ' has-error' : '') . '">'
        . '<label for="f_' . e($name) . '">' . e($label) . '</label>'
        . '<textarea class="form-control" rows="3" id="f_' . e($name) . '" name="' . e($name) . '">' . e($valor) . '</textarea>'
        . ($error ? '<span class="help-block">' . e($error) . '</span>' : '')
        . '</div></div>';
}

function casilla(string $name, string $label, bool $marcado): string
{
    $hay = Flash::hayErrores() || Flash::old($name) !== null;
    $chk = $hay ? Flash::old($name) === '1' : $marcado;
    return '<div class="col-md-12"><div class="checkbox"><label>'
        . '<input type="checkbox" name="' . e($name) . '" value="1"' . ($chk ? ' checked' : '') . '> ' . e($label)
        . '</label></div></div>';
}
