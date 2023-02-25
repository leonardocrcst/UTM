<?php

namespace App;

class Report
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function saleByPlatform(): ?string
    {
        $sales = [];
        if (count($this->data)) {
            foreach ($this->data as $line) {
                $platform = !empty($line['source']) ? $line['source'] : 'Unknown';
                if (!isset($sales[$platform])) {
                    $sales[$platform] = [
                        'quantity' => 0,
                        'amount' => 0
                    ];
                }
                $sales[$platform]['quantity']++;
                $sales[$platform]['amount'] += number_format(
                    floatval($line['comission']),
                    2,
                    '.',
                    ''
                );
            }
        }
        return json_encode($sales, JSON_PRETTY_PRINT & JSON_NUMERIC_CHECK);
    }
}
