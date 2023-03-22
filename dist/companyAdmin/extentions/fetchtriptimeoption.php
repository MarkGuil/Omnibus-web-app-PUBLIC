<?php

session_start();
include('../../database/db.php');
$id = $_SESSION['compadminID'];

if ($_POST['routeid']) {
    $query = "SELECT * FROM `trip` WHERE `companyID` = $id AND `terminalID` = " . $_POST['terminalid'] . " AND `routeID` = " . $_POST['routeid'];
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        // $trips = $result->fetch_all(MYSQLI_ASSOC);
        echo '<option value="">Select time</option>';
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['id'] . '">' . date('h:i A', strtotime($row['departure_time'])) . '</option>';
        }
    } else {
        echo '<option>no data found</option>';
    }
}
