<?php

$servername = "localhost";
$username = "";
$password = "";
$dbname = '';


$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");
if ($conn->connect_error) {
    die("Yhteys epäonnistui: " . $conn->connect_error);
}
