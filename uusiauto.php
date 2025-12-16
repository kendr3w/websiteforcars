<?php
$apiUrl = "https://tvt-linux.tvtedu.fi/~213582/autot_api.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        "merkki" => $_POST['merkki'],
        "malli" => $_POST['malli'],
        "vuosimalli" => (int)$_POST['vuosimalli']
    ];

    $ch = curl_init($apiUrl);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json']
    ]);
    curl_exec($ch);
    curl_close($ch);

    header("Location: index.php?status=added");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
<h1>Add a new car</h1>

<form method="post">
<input name="merkki" placeholder="Character" required>
<input name="malli" placeholder="Model" required>
<input type="number" name="vuosimalli" placeholder="Model year" required>
<button>Add</button>
</form>

<a class="btn-secondary" href="index.php">Back</a>
</div>
</body>
</html>
