<?php
require_once("Twitter.class.php");
require_once("Weather.class.php");

// Main random weather forecast
do {
    $zip = mt_rand(10000, 99999);
    $weather = new Weather($zip);
} while ($weather->getLocation() == "Error");

Twitter::post($weather->generateReport() . "\n#randomforecast", $weather->getImage());

// Personal replies
Twitter::personalReplies();
?>
