<?php
$apiUrl = "https://tvt-linux.tvtedu.fi/~213582/autot_api.php";

$auto = json_decode(
    file_get_contents("$apiUrl?id=" . (int)$_GET['id']),
    true
);

if (isset($_POST['update'])) {

    $data = [
        "ID" => (int)$_POST['id'],
        "merkki" => $_POST['merkki'],
        "malli" => $_POST['malli'],
        "vuosimalli" => (int)$_POST['vuosimalli']
    ];

    $ch = curl_init("$apiUrl?action=update");
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json']
    ]);
    curl_exec($ch);
    curl_close($ch);

    header("Location: index.php?status=updated");
    exit;
}
?>
<!DOCTYPE html>
<html>
<body>

<h2>Edit the car</h2>

<form method="post">
<input type="hidden" name="id" value="<?= $auto['ID'] ?>">
<input name="merkki" value="<?= htmlspecialchars($auto['merkki']) ?>" required>
<input name="malli" value="<?= htmlspecialchars($auto['malli']) ?>" required>
<input type="number" name="vuosimalli" value="<?= $auto['vuosimalli'] ?>" required>
<button name="update">Save</button>
</form>

</body>
</html>
