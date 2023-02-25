<?php

namespace App\Adapters\Interfaces;

interface AdapterInterface
{
    public function translate(string $filename): ?array;
    public function accept(string $type): bool;
}
