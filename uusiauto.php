<?php
$apiUrl = "http:/localhost/autot_api/autot_api.php";

if(isset($_POST['add'])) {
    $data = [
        "merkki" => $_POST['merkki'],
        "malli" => $_POST['malli'],
        "vuosimalli" => intval($_POST['vuosimalli'])
    ];

    $ch = curl_init($apiUrl);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    curl_setopt($ch, CURLOPT_POST, true);

    $response = curl_exec($ch);

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if($httpCode === 201 || $httpCode === 200) {
        header("Location: index.php?status=added");
    } else {
        header("Location: index.php?status=add_error");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Lisää uusi auto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h2>Lisää uusi auto</h2>
    <form method = "post">
        <input type="text" name="merkki" placeholder="Merkki">
        <input type="text" name="malli" placeholder="Malli">
        <input type="number" name="vuosimalli" placeholder="Vuosimalli">
        <button type="submit" name="add">Lisää</button>
    </form>    
</body>
</html>