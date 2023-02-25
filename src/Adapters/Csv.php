<?php

namespace App\Adapters;

use App\Adapters\Interfaces\AdapterInterface;

class Csv implements AdapterInterface
{
    private string $accept = "text/plain";

    public function accept(string $type): bool
    {
        return $this->accept === $type;
    }

    public function translate(string $filename): ?array
    {
        if ($file = fopen($filename, "r+")) {
            $content = [];
            while (($data = fgetcsv($file, 512)) !== false) {
                $filtered = array_filter($data);
                if (count($filtered)) {
                    $content[] = $data;
                }
            }
            return $content;
        }
        return null;
    }
}
