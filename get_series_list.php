<?php

require_once 'http.php';

$json = [];
$matches = [];

$chars = str_split('abcdefghijklmnopqrstuvwxyz');
$chars[] = 'numeric';
foreach ($chars as $char) {
    echo $char;
    $listSource = sendRequest('https://www.crunchyroll.com/videos/anime/alpha?group=' . $char);

    preg_match('/id="main_content"(.*?)id="sidebar"/s', $listSource, $matches);
    $listSource = $matches[1];

    preg_match_all('/itemprop="url" href="([^"]+)".*?photo" alt="([^"]+)" src="([^"]+)".*?short-desc">([^<]+)</s', $listSource, $matches,
        PREG_SET_ORDER);

    foreach ($matches as $match) {
        $json[] = [
            'title' => $match[2],
            'description' => trim($match[4]),
            'image' => str_replace('small', 'full', $match[3]),
            'url' => $match[1]
        ];
    }
}

file_put_contents('series.json', json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));