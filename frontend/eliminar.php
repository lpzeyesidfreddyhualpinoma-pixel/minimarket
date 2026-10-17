<?php
require_once 'api_client.php';
$id = $_GET['id'] ?? null;

if ($id) {
    callAPI('DELETE', API_BASE_URL . '?id=' . $id);
}
header('Location: index.php');
exit();
?>