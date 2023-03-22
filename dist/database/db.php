<?php

$servername = "";
$username = "";
$password = "";
$dbname = "";

$db = mysqli_connect($servername, $username, $password, $dbname);
$conn = new mysqli($servername, $username, $password, $dbname);
$connect = new PDO("mysql:host=$servername;dbname=$dbname", "$username", "$password");

function connect()
{
    $mysql = new mysqli('', '', '', '');
    return $mysql;
}

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
