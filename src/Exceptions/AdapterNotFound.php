<?php

namespace App\Exceptions;

class AdapterNotFound extends \Exception
{
    public function __construct()
    {
        parent::__construct("Adapter not found");
    }
}
