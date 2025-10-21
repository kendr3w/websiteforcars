<?php 

$apiUrl = "http://localhost/autot_api/autot_api.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $ch = curl_init("$apiUrl?id=$id");

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

    $response = curl_exec($ch);

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if($httpCode === 200 || $httpCode === 204) {
        header("Location: index.php?status=deleted");
    } else {
        header("Location: index.php?status=error");
    }

    exit;
}