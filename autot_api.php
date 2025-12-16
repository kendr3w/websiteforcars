<?php
header("Content-Type: application/json; charset=UTF-8");
require "db_config.php";

$action = $_GET['action'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if ($action === 'delete' && isset($_GET['id'])) {
        deleteAuto((int)$_GET['id']);
        exit;
    }

    if (isset($_GET['id'])) {
        getAuto((int)$_GET['id']);
    } else {
        getAllAutot();
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);

    if ($action === 'update') {
        updateAuto($data);
        exit;
    }

    addAuto($data);
    exit;
}

/* ===== FUNKTIOT ===== */

function getAuto($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM autot WHERE ID=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    echo json_encode($res->fetch_assoc() ?: []);
}

function getAllAutot() {
    global $conn;
    $res = $conn->query("SELECT * FROM autot");
    echo json_encode($res->fetch_all(MYSQLI_ASSOC));
}

function addAuto($data) {
    global $conn;
    if (empty($data['merkki']) || empty($data['malli']) || empty($data['vuosimalli'])) {
        http_response_code(400);
        echo json_encode(["message" => "Tietoja puuttuu"]);
        return;
    }

    $stmt = $conn->prepare("INSERT INTO autot (merkki, malli, vuosimalli) VALUES (?,?,?)");
    $stmt->bind_param("ssi", $data['merkki'], $data['malli'], $data['vuosimalli']);
    $stmt->execute();
    http_response_code(201);
}

function updateAuto($data) {
    global $conn;
    $stmt = $conn->prepare(
        "UPDATE autot SET merkki=?, malli=?, vuosimalli=? WHERE ID=?"
    );
    $stmt->bind_param(
        "ssii",
        $data['merkki'],
        $data['malli'],
        $data['vuosimalli'],
        $data['ID']
    );
    $stmt->execute();
    http_response_code(200);
}

function deleteAuto($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM autot WHERE ID=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    http_response_code(200);
}
