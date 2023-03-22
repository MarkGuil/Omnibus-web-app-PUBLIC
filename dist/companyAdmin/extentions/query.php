<?php
$id = $_SESSION['compadminID'];

include('../database/db.php');
// if ($_POST['point']) {
//     // Be sure to set up your SQL $conn variable here
//     $terminalID = $_POST['point'];
//     $conn = new mysqli('localhost', 'root','','omnibus_db');;
//     $sql = "SELECT city FROM terminal WHERE id = '$terminalID' AND companyID = '$id'";
//     $result = mysqli_query($conn, $sql);
//     $data = []; // Save the data into an arbitrary array.
//     while ($row = mysqli_fetch_assoc($result)) {
//         $data[] = $row;
//     }
//     echo json_encode($data); // This will encode the data into a variable that JavaScript can decode.
// }
// $host = "localhost";
//   $user = "root";
//   $pass = "";

//   $databaseName = "omnibus_db";
//   $tableName = "terminal";

//   $con = mysql_connect($host,$user,$pass);
//   $dbs = mysql_select_db($databaseName, $con);

//   $result = mysql_query("SELECT * FROM $tableName");
//   $array = mysql_fetch_row($result);

//   echo json_encode($array);