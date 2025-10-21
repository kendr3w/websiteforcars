<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = '610';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Yhteys epäonnistui: " . $conn->connect_error);
}