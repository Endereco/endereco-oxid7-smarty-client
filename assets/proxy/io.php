<?php

if ('GET' === $_SERVER['REQUEST_METHOD']) {
    die('We expect a POST request here.');
}

include("../../metadata.php");
$pluginVersion = $aModule['version'] ?? '0.0.0';
$agent_info  = "Endereco Oxid7 Client v" . $pluginVersion;
$post_data   = json_decode(file_get_contents('php://input'), true);
$api_key     = trim($_SERVER['HTTP_X_AUTH_KEY']);
$data_string = json_encode($post_data);
$ch          = curl_init(trim($_SERVER['HTTP_X_REMOTE_API_URL']));

if ($_SERVER['HTTP_X_TRANSACTION_ID']) {
    $tid = $_SERVER['HTTP_X_TRANSACTION_ID'];
} else {
    $tid = 'not_set';
}

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt(
    $ch,
    CURLOPT_HTTPHEADER,
    [
        'Content-Type: application/json',
        'X-Auth-Key: ' . $api_key,
        'X-Transaction-Id: ' . $tid,
        'X-Agent: ' . $agent_info,
        'X-Transaction-Referer: ' . $_SERVER['HTTP_X_TRANSACTION_REFERER'],
        'Content-Length: ' . strlen($data_string)]
);

$result = curl_exec($ch);

header('Content-Type: application/json');
echo $result;
die();
