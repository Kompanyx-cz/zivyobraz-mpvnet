<?php
// Test 1: cURL
echo "=== cURL ===\n";
echo function_exists('curl_init') ? "OK - cURL je dostupný\n" : "CHYBA - cURL chybí\n";

// Test 2: Cache složka
echo "\n=== Cache ===\n";
$cacheDir = __DIR__ . '/cache';
echo "Cesta: $cacheDir\n";
echo file_exists($cacheDir) ? "Složka existuje\n" : "CHYBA - složka neexistuje\n";
echo is_writable($cacheDir) ? "OK - složka je zapisovatelná\n" : "CHYBA - složka není zapisovatelná\n";

// Test 3: Připojení na mpvnet.cz
echo "\n=== Připojení na mpvnet.cz ===\n";
$ch = curl_init("https://www.mpvnet.cz/ZLIN/tab?stopNum=42028&departures=true");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP status: $httpCode\n";
echo $error ? "CHYBA: $error\n" : "Připojení OK\n";
echo "Délka odpovědi: " . strlen($response) . " znaků\n";
if (strlen($response) > 0) {
    echo "Prvních 500 znaků:\n" . substr($response, 0, 500) . "\n";
}
