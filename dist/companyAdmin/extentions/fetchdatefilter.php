<?php

session_start();
include('../../database/db.php');
$id = $_SESSION['compadminID'];

if ($_POST['routeid']) {
    $query = "SELECT * FROM `bus_trip` WHERE `companyID` = $id AND `terminalID` = " . $_POST['terminalid'] . " AND " . $_POST['routeid'];
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        // $trips = $result->fetch_all(MYSQLI_ASSOC);
        echo '<option value="">Select date</option>';
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['trip_date'] . '">' . $row['trip_date'] . '</option>';
        }
    } else {
        echo '<option>no data found</option>';
    }
}
