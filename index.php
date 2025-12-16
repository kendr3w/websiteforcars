<?php
$apiUrl = "https://tvt-linux.tvtedu.fi/~213582/autot_api.php";
$autot = [];

if (isset($_GET['getAll'])) {
    $autot = json_decode(file_get_contents($apiUrl), true) ?? [];
}

if (isset($_GET['getID']) && is_numeric($_GET['getID'])) {
    $auto = json_decode(file_get_contents("$apiUrl?id=".(int)$_GET['getID']), true);
    if (!empty($auto['ID'])) $autot[] = $auto;
}
?>
<!DOCTYPE html>
<html lang="fi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Autotietokanta</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">

<h1>Car database</h1>

<?php if (isset($_GET['status'])): ?>
<div class="status <?= $_GET['status']==='error'?'error':'success' ?>">
    <?= htmlspecialchars($_GET['status']) ?>
</div>
<?php endif; ?>

<form method="get">
    <button name="getAll">Get all the cars</button>
</form>

<form method="get">
    <input type="number" name="getID" placeholder="Search car with ID" required>
    <button>Find</button>
</form>

<p>
    <a class="btn" href="uusiauto.php">Add a new car</a>
</p>

<?php if ($autot): ?>
<table>
<thead>
<tr>
<th>ID</th><th>Character</th><th>Model</th><th>Model year</th><th>Operations</th>
</tr>
</thead>
<tbody>
<?php foreach ($autot as $a): ?>
<tr>
<td data-label="ID"><?= $a['ID'] ?></td>
<td data-label="Merkki"><?= htmlspecialchars($a['merkki']) ?></td>
<td data-label="Malli"><?= htmlspecialchars($a['malli']) ?></td>
<td data-label="Vuosimalli"><?= htmlspecialchars($a['vuosimalli']) ?></td>
<td data-label="Toiminnot" class="actions">
    <a href="muokkaa.php?id=<?= $a['ID'] ?>">Edit</a>
    <a class="btn-danger" href="poista.php?id=<?= $a['ID'] ?>"
       onclick="return confirm('Are you sure we're removing the car?')">Remove</a>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php endif; ?>

</div>
</body>
</html>
