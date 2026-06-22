<?php
$ch = curl_init("https://www.mpvnet.cz/ZLIN/tab?stopNum=42028&departures=true");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    'Accept-Language: cs,en;q=0.5',
    'Referer: https://www.mpvnet.cz/ZLIN/map',
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP: $httpCode | Délka: " . strlen($response) . " znaků\n\n";
echo "=== CELÁ ODPOVĚĎ ===\n";
echo $response;
