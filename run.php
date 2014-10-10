<?php
require_once("Twitter.class.php");
require_once("Weather.class.php");

$zip = mt_rand(10000, 99999);
$weather = new Weather($zip);
Twitter::post($weather->generateReport() . "\n#randomforecast", $weather->getImage());
?>
