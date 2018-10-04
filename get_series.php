<?php

require 'http.php';

$url = 'https://www.crunchyroll.com' . $_GET['url'];
$seriesSource = sendRequest($url);
$matches = [];

preg_match_all('/<a href="([^"]+)"[^>]*?episode">\s*<img[^>]*"(http[^"]+)".*?short-desc[^>]*>([^<]+)<.*?"description":"([^"]+)"/s', $seriesSource, $matches, PREG_SET_ORDER);

$episodes = [];
foreach ($matches as $match) {
    $episodes[] = [
        'title' => $match[3],
        'description' => json_decode('"' . $match[4] . '"'),
        'image' => str_replace('wide', 'full', $match[2]),
        'url' => $match[1]
    ];
}
$json['episodes'] = array_reverse($episodes);

header('Content-Type: application/json');

echo json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);