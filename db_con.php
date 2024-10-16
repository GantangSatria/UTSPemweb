<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phones_store";
$port = 3307;

$db_con = new mysqli($servername, $username, $password, $dbname, $port);

// Memeriksa koneksi
if ($db_con->connect_error) {
    die("Koneksi gagal: " . $db_con->connect_error);
}
?>