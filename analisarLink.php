<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = filter_var($_POST['link'] ?? '', FILTER_VALIDATE_URL);

    if (!$url) {
        echo json_encode(['error' => 'Link inválido.']);
        exit;
    }

    // Obter redirecionamento final
    stream_context_set_default(['http' => ['method' => 'HEAD', 'follow_location' => 1]]);
    $headers = @get_headers($url, 1);
    $finalUrl = is_array($headers["Location"] ?? null) ? end($headers["Location"]) : ($headers["Location"] ?? $url);

    $host = parse_url($finalUrl, PHP_URL_HOST) ?? 'desconhecido';
    $ip = gethostbyname($host);

    // API de geolocalização
    $geo = @json_decode(file_get_contents("http://ip-api.com/json/{$ip}"), true);
    $country = $geo['country'] ?? 'Desconhecido';
    $isp = $geo['isp'] ?? 'Desconhecido';

    // Verifica SSL
    $sslValid = str_starts_with($finalUrl, 'https://') ? 'Sim' : 'Não';

    // Checa similaridade com domínios famosos
    $famosos = ['google.com', 'facebook.com', 'instagram.com', 'whatsapp.com', 'hotmail.com'];
    $suspiciousScore = 0;
    foreach ($famosos as $famoso) {
        similar_text($host, $famoso, $percent);
        if ($percent > 70) {
            $suspiciousScore = max($suspiciousScore, $percent);
        }
    }

    echo json_encode([
        'urlFinal' => $finalUrl,
        'ip' => $ip,
        'pais' => $country,
        'isp' => $isp,
        'ssl' => $sslValid,
        'similaridade' => $suspiciousScore,
    ]);
}
