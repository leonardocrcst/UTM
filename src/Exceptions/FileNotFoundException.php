<?php

namespace App\Exceptions;

class FileNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct("File not found");
    }
}
