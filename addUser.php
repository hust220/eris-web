<?php

$servername = "localhost";
$username = "other_svc";
$password = "final-S3cr3t";
$dbname = "ddg";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully<br>";

$username = 'jianopt';
$password = md5('Kang1994$');
$email = 'jianopt@ad.unc.edu';
$level = 100;
$approved = 1;
$emailConfirmed = 1;
$sql = "insert into users3(username, password, email, level, approved, emailConfirmed) values('$username', '$password', '$email', $level, $approved, $emailConfirmed)";
$conn->query($sql);

$conn->close();

