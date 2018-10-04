<?php

require_once 'http.php';

$url = 'https://www.crunchyroll.com' . $_GET['url'];
$source = sendRequest($url);
$matches = [];

preg_match('/vilos.config.media = ([^;]+);/s', $source, $matches);

header('Content-Type: application/json');

echo $matches[1];