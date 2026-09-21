<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Reglas separadas por "|": required, max:N, min:N, email, date, int, numeric, in:a,b, digits.
 * Devuelve un array campo => mensaje con el primer error de cada campo.
 */
final class Validator
{
    public static function validar(array $datos, array $reglas, array $etiquetas = []): array
    {
        $errores = [];
        foreach ($reglas as $campo => $lista) {
            $etq = $etiquetas[$campo] ?? ucfirst($campo);
            $valor = $datos[$campo] ?? null;
            $vacio = $valor === null || $valor === '';

            foreach (explode('|', $lista) as $regla) {
                [$nombre, $arg] = array_pad(explode(':', $regla, 2), 2, null);

                if ($nombre === 'required') {
                    if ($vacio) {
                        $errores[$campo] = "$etq es obligatorio.";
                        break;
                    }
                    continue;
                }
                if ($vacio) {
                    continue;
                }

                $msg = match ($nombre) {
                    'max'     => mb_strlen((string) $valor) > (int) $arg ? "$etq no puede superar $arg caracteres." : null,
                    'min'     => (float) $valor < (float) $arg ? "$etq debe ser al menos $arg." : null,
                    'email'   => filter_var($valor, FILTER_VALIDATE_EMAIL) ? null : "$etq no es un correo válido.",
                    'date'    => self::fechaValida((string) $valor) ? null : "$etq no es una fecha válida.",
                    'int'     => filter_var($valor, FILTER_VALIDATE_INT) !== false ? null : "$etq debe ser un número entero.",
                    'numeric' => is_numeric($valor) ? null : "$etq debe ser numérico.",
                    'digits'  => ctype_digit((string) $valor) ? null : "$etq solo admite dígitos.",
                    'in'      => in_array((string) $valor, explode(',', (string) $arg), true) ? null : "$etq tiene un valor no permitido.",
                    default   => null,
                };
                if ($msg !== null) {
                    $errores[$campo] = $msg;
                    break;
                }
            }
        }
        return $errores;
    }

    private static function fechaValida(string $v): bool
    {
        $d = \DateTimeImmutable::createFromFormat('!Y-m-d', $v);
        return $d !== false && $d->format('Y-m-d') === $v;
    }
}
