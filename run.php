<?php
require_once("Twitter.class.php");
require_once("Weather.class.php");

// Main random weather forecast
$random = json_decode(file_get_contents("https://randomapi.com/api/?key=" . Config::API_KEY . "&id=" . Config::API_ID));
$weather = new Weather($random->results[0]->{Config::OBJECT_NAME}->{Config::OBJECT_FIELD});

Twitter::post($weather->generateReport() . "\nPowered by:randomapi.com\n#randomforecast", $weather->getImage());

// Personal replies
Twitter::personalReplies();
?>
