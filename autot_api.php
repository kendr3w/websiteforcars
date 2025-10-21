<?php

header("Content-Type: application/json; charset=UTF-8");
include 'db_config.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if(isset($_GET['id']) && is_numeric($_GET['id'])) {
            getAuto(intval($_GET['id']));
        } else {
            getAllAutot();
        }
        break;

    case 'POST':
        if (strpos($_SERVER["CONTENT_TYPE"], "application/json") === 0) {
            addAuto();
        } else {
            http_response_code(415);
            echo json_encode(["message" => "Sisältötyyppi ei tuettu"]);
        }
        break;

    case 'PUT':
        if (strpos($_SERVER["CONTENT_TYPE"], "application/json") === 0) {
            $data = json_decode(file_get_contents("php://input"), true);
        } else {
            http_response_code(415);
            echo json_encode(["message" => "Sisältötyyppi ei tuettu"]);
        }
        break;
    
    case 'DELETE':
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            deleteAuto(intval($_GET['id']));
        } else {
            http_response_code(400);
            echo json_encode(["message" => "ID puuttuu tai ei ole kelvollinen"]);
        }
        break;
    
    default:
        http_response_code(405);
        echo json_encode(["message" => "Metodi ei sallittu"]);
        break;
}

function getAuto($id) {
    global $conn;
    $stmt = $conn->prepare('SELECT ID, merkki, malli, vuosimalli FROM autot WHERE ID = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1) {
        echo json_encode($result->fetch_assoc());
    } else {
        http_response_code(404);
        echo json_encode(['message' => "Autoa ei löytyny tällä ID:llä $id"]);
    }
    $stmt->close();
}

function getAllAutot() {
    global $conn;
    $stmt = $conn->prepare("SELECT ID, merkki, malli, vuosimalli FROM autot");
    $stmt->execute();
    $result = $stmt->get_result();
    $autot = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($autot);
    $stmt->close();
}

function addAuto() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!empty($data['merkki']) && !empty($data['malli']) && !empty($data['vuosimalli'])) {
        $merkki = $data['merkki'];
        $malli = $data['malli'];
        $vuosimalli = intval($data['vuosimalli']);

        $stmt = $conn->prepare("INSERT INTO autot (merkki, malli, vuosimalli) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $merkki, $malli, $vuosimalli);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(['message' => 'Auto lisätty onnistuneesti']);
        } else {
            http_response_code(503);
            echo json_encode(['message' => 'Auton lisääminen epäonnistui']);
        }
        $stmt->close();
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Tietoja puuttuu"]);
    }
}

function updateAuto($data) {
    global $conn;
    if (!empty($data['ID']) && !empty($data['merkki']) && !empty($data['malli']) && !empty($data['vuosimalli'])) {
        $id = intval($data['ID']);
        $merkki = $data['merkki'];
        $malli = $data['malli'];
        $vuosimalli = intval($data['vuosimalli']);

        $stmt = $conn->prepare("UPDATE autot SET merkki = ?, malli = ?, vuosimalli = ? WHERE ID = ?");
        $stmt->bind_param('ssii', $merkki, $malli, $vuosimalli, $id);

        if($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(['message' => 'Auto päivitetty onnistuneesti']);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Autoa ei löytynyt ID:llä $id"]);
            }
        } else {
            http_response_code(503);
            echo json_encode(['message' => 'Auton päivittäminen epäonnistui']);
        }
        $stmt->close();
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'Tietoja puuttuu']);
    }
}

function deleteAuto($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM autot WHERE ID = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if($stmt->affected_rows > 0) {
            echo json_encode(['message' => 'Auto poistettu onnistuneesti']);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Autoa ei löytynyt ID:llä $id"]);
        }
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Auton poistaminen epäonnistui"]);
    }
    $stmt->close();
}
$conn->close();
?>