<?php
// Função para buscar JSON de uma URL
function fetchJsonFromUrl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

// Função para obter coordenadas usando o CEP
function getCoordinates($cep) {
    $viacepUrl = 'https://viacep.com.br/ws/'. urlencode($cep). '/json/';
    $viacepData = fetchJsonFromUrl($viacepUrl);

    if (!isset($viacepData['logradouro'], $viacepData['localidade'], $viacepData['uf'])) {
        error_log('Endereço não encontrado no ViaCEP');
        return null;
    }

    $enderecoCompleto = "{$viacepData['logradouro']}, {$viacepData['localidade']}, {$viacepData['uf']}";
    $apiKey = 'c193dba917c1424b9f636d73b732f356';
    $geoapifyUrl = "https://api.geoapify.com/v1/geocode/search?text=". urlencode($enderecoCompleto). "&lang=pt&limit=1&apiKey=$apiKey";
    $geoapifyData = fetchJsonFromUrl($geoapifyUrl);

    if (!isset($geoapifyData['features'][0]['geometry']['coordinates'])) {
        error_log('Dados de coordenadas não encontrados na Geoapify');
        return null;
    }

    $coordinates = $geoapifyData['features'][0]['geometry']['coordinates'];
    return ['latitude' => $coordinates[1], 'longitude' => $coordinates[0]];
}
?>
