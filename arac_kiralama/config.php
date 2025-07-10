<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "arac_kiralama";

// Bağlantı
$conn = new mysqli($servername, $username, $password, $dbname);

// Hata kontrolü
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

if (!$conn) {
    die("Veritabanı bağlantı hatası: " . mysqli_connect_error());
}

?>
