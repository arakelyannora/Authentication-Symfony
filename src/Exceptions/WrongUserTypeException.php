<?php

namespace App\Exceptions;

use JetBrains\PhpStorm\Pure;

class WrongUserTypeException extends GeneralException
{
    #[Pure] public static function create(): self
    {
        return new self(
            "Something wrong with user type"
        );
    }
}