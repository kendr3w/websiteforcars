<?php
if (!isset($_GET['id'])) {
    header("Location: index.php?status=error");
    exit;
}

$id = (int)$_GET['id'];

$apiUrl = "https://tvt-linux.tvtedu.fi/~213582/autot_api.php?action=delete&id=$id";

$response = file_get_contents($apiUrl);

header("Location: index.php?status=deleted");
exit;
