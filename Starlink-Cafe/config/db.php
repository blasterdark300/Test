<?php
$conn = mysqli_connect("localhost", "root", "", "db_starlink");
if (!$conn) { die("Koneksi Error: " . mysqli_connect_error()); }
?>