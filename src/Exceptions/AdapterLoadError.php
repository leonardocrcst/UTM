<?php

namespace App\Exceptions;

class AdapterLoadError extends \Exception
{
    public function __construct(string $name)
    {
        parent::__construct("$name: error on load adapter.");
    }
}
