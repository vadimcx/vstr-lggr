<?php

function getUserIP(): string {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } else {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}

function getUserAgent(): string {
    return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
}

function getVisitorGeoData(string $ip): ?array {
    $url = "https://ipwho.is/" . urlencode($ip);
    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FAILONERROR => true,
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        return null;
    }

    $data = json_decode($response, true);
    return is_array($data) ? $data : null;
}

function buildVisitorData(): ?array {

    $ip = getUserIP();
    $agent = getUserAgent();
    $geoData = getVisitorGeoData($ip);

    if (!$geoData || !$geoData['success']) {
        return null; // IP lookup failed
    }

    return [
        'ip' => $ip,
        'continent' => $geoData['continent'] ?? 'Unknown',
        'country' => $geoData['country'] ?? 'Unknown',
        'flag' => $geoData['flag']['emoji_unicode'] ?? '',
        'isp' => $geoData['connection']['isp'] ?? 'Unknown',
        'agent' => $agent,
        'called_url' => $called_url
    ];
    
}

?>
