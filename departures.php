<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$stopNum = isset($_GET['stops']) ? intval($_GET['stops']) : 0;

if (!$stopNum) {
    echo json_encode(['error' => 'Chybí parametr stops']);
    exit;
}

// Zavolej mpvnet.cz API
$ch = curl_init('https://www.mpvnet.cz/zlin/mapapi/departures');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'stopNum' => $stopNum,
    'departures' => true
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json; charset=UTF-8',
    'Accept: */*',
    'Origin: https://www.mpvnet.cz',
    'Referer: https://www.mpvnet.cz/zlin/map/showStation/' . $stopNum,
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
    'X-Requested-With: XMLHttpRequest',
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$html = curl_exec($ch);
curl_close($ch);

// Parsuj HTML tabulku
$departures = [];
preg_match_all('/<tr>\s*<td[^>]*>(.*?)<\/td>\s*<td[^>]*title="([^"]*)"[^>]*>.*?<\/td>\s*<td[^>]*>(.*?)<\/td>\s*<td[^>]*>(.*?)<\/td>\s*<td[^>]*>(.*?)<\/td>\s*<\/tr>/s', $html, $matches, PREG_SET_ORDER);

foreach ($matches as $row) {
    $departures[] = [
        'linka'     => trim(strip_tags($row[1])),
        'smer'      => html_entity_decode(trim($row[2]), ENT_HTML5, 'UTF-8'),
        'stanoviste'=> trim(strip_tags($row[3])),
        'cas'       => trim(strip_tags($row[4])),
        'zpozdeni'  => intval(trim(strip_tags($row[5]))),
    ];
}

echo json_encode($departures, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
