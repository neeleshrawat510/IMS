<?php

require_once "vendor/autoload.php";
require_once "includes/HubSpotService.php";

$hubspot = new HubSpotService();

$result = $hubspot->getDealPipelines();

echo "<pre>";
print_r($result);
echo "</pre>";