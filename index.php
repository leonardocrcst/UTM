<?php
header("Content-Type: application/json");

use App\Report;
use App\Tracking;
use App\Types\TranslatorType;

require_once __DIR__ . "/vendor/autoload.php";

$doppus = new TranslatorType();
$doppus->fee = "doppus_fee";
$doppus->comission = "commission_value";
$doppus->price = "sale_value";
$doppus->source = "utm_source";
$doppus->date = "registration_date";

$kiwify = new TranslatorType();
$kiwify->fee = "Kiwify Fee";
$kiwify->comission = "Your Commision Amount";
$kiwify->price = "Product Base Price";
$kiwify->source = "Tracking utm_source";
$kiwify->date = "Creation Date";

try {
    $files = [
        "uploaded/Kiwify.xlsx" => $kiwify,
        "uploaded/Doppus.csv" => $doppus,
    ];
    $data = Tracking::sheets($files);
    $report = new Report($data);

    echo $report->saleByPlatform();
} catch (Throwable $e) {
    echo $e->getMessage();
    exit();
}
