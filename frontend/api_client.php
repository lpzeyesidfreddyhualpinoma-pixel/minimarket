<?php
define('API_BASE_URL', 'http://localhost:8000/api/productos.php');

/**
 * Realiza peticiones HTTP a la API REST utilizando funciones nativas de PHP
 */
function callAPI($method, $url, $data = null) {
    $options = [
        'http' => [
            'method'  => strtoupper($method),
            'header'  => "Content-Type: application/json\r\n" .
                         "Accept: application/json\r\n",
            'ignore_errors' => true
        ]
    ];

    if ($data !== null && in_array(strtoupper($method), ['POST', 'PUT', 'DELETE'])) {
        $options['http']['content'] = json_encode($data);
    } elseif ($data !== null && strtoupper($method) === 'GET' && (is_array($data) || is_object($data))) {
        $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    $context  = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    if ($result === FALSE) {
        return ["message" => "Error al conectar con la API."];
    }

    return json_decode($result, true);
}
?>