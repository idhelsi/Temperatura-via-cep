<?php
// Função para obter dados do tempo usando latitude e longitude
function getWeatherData($latitude, $longitude) {
    $url = sprintf('https://api.open-meteo.com/v1/forecast?latitude=%s&longitude=%s&current_weather=true', $latitude, $longitude);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);

    if ($response === false) {
        echo 'Erro na requisição: '. curl_error($ch);
        curl_close($ch);
        return null;
    }

    curl_close($ch);
    return json_decode($response, true);
}
?>
