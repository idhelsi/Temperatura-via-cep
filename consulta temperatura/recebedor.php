<?php
require_once 'longlat.php'; 
require_once 'temp.php';

// Sanitiza e valida os dados de entrada
$nome = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$cep = filter_input(INPUT_POST, 'cep', FILTER_SANITIZE_STRING);

if (!$cep) {
    header("Location: index.html");
    exit;
}

// Obtém as coordenadas usando o CEP
$coordinates = getCoordinates($cep);
if (!$coordinates) {
    echo 'Não foi possível obter as coordenadas.';
    exit;
}

// Obtém os dados do tempo usando as coordenadas
$data = getWeatherData($coordinates['latitude'], $coordinates['longitude']);
if (!$data || !isset($data['current_weather']['temperature'])) {
    echo 'Não foi possível obter a temperatura.';
    exit;
}

// Exibe os dados do usuário e a temperatura atual
$temperature = $data['current_weather']['temperature'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dados do Usuário</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .user-data {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        .user-data h2 {
            color: #4682b4;
        }

        .user-data p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="user-data">
        <h2>Dados Requisitados</h2>
        <!-- <p><strong>Nome:</strong> <?php echo htmlspecialchars($nome); ?></p>
        <p><strong>E-mail:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>CEP:</strong> <?php echo htmlspecialchars($cep); ?></p> -->
        <p><strong>A temperatura atual é:</strong> <?php echo htmlspecialchars($temperature); ?>°C</p>
    </div>
</body>
</html>
