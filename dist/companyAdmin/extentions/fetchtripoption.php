<?php

session_start();
include('../../database/db.php');
$id = $_SESSION['compadminID'];

if ($_POST['terminalid']) {
    $query = "SELECT * FROM `routes` WHERE `companyID` = '$id' AND `pointA_terminalID` = " . $_POST['terminalid'];
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        // $trips = $result->fetch_all(MYSQLI_ASSOC);
        echo '<option value="">Select route</option>';
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['id'] . '">' . $row['pointA'] . ' - ' . $row['pointB'] . '</option>';
        }
    } else {
        echo '<option value="">no data found</option>';
    }
}
