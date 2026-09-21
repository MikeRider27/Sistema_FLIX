<?php
declare(strict_types=1);

namespace App\Core;

final class HttpException extends \RuntimeException
{
    public function __construct(string $mensaje, int $codigo)
    {
        parent::__construct($mensaje, $codigo);
    }
}
