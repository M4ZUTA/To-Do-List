<?php
$databaseHost = 'localhost';
$databaseName = 'todolist'; // nama database
$databaseUsername = 'root'; // username
$databasePassword = ''; // password

// Ini buat connect ke database
$conn = new mysqli($databaseHost, $databaseUsername, $databasePassword, $databaseName);

// Nek ini buat cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>