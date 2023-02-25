<?php

namespace App\Adapters;

use App\Adapters\Interfaces\AdapterInterface;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Xlsx implements AdapterInterface
{
    private string $accept = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";

    /**
     * @throws Exception
     */
    public function translate(string $filename): ?array
    {
        $file = IOFactory::load($filename);
        $worksheet = $file->getSheet($file->getFirstSheetIndex());
        $current = 1;
        $lines = $worksheet->rangeToArray("A$current:AZ$current");
        do {
            $current++;
            $line = $worksheet->rangeToArray("A$current:AZ$current");
            if (isset($line[0]) && isset($line[0][0]) && $line[0][0]) {
                $lines[] = $line[0];
            }
        } while (isset($line[0]) && isset($line[0][0]) && $line[0][0]);
        return $lines;
    }

    public function accept(string $type): bool
    {
        return $type === $this->accept;
    }
}
