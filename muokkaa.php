<?php 
$apiUrl = "http://localhost/autot_api.php";

$auto = null;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $response = @file_get_contents("$apiUrl?id=$id");
    $auto = json_decode($response, true);
}

if (isset($_POST['update'])) {
    $data = [
        "ID" => intval($_POST['id']),
        "merkki" => $_POST['merkki'],
        "malli" => $_POST["malli"],
        "vuosimalli" => intval($_POST['vuosimalli'])
    ];

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 || $httpCode === 204) {
        header("Location: index.php?status=updated");
    } else {
        header("Location: index.php?status=update_error");
    }
    exit;
}
?>

<h2>Muokkaa autoa</h2>
<form method="post">
    <input type="hidden" name="id" value="<?= htmlspecialchars($auto['ID']) ?>">
    <input type="text" name="merkki" value="<?= htmlspecialchars($auto['merkki']) ?>"placeholder="Merkki">
    <input type="text" name="malli" value="<?= htmlspecialchars($auto['malli']) ?>"placeholder="Malli">
    <input type="number" name="vuosimalli" value="<?= htmlspecialchars($auto['vuosimalli']) ?>"placeholder="Vuosimalli">
    <button type="submit" name="update">Tallenna muutokset</button>
</form>

