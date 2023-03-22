<?php

session_start();
include('../../database/db.php');
if ($_POST['id']) {
    $query = "SELECT * FROM buses WHERE companyID = " . $_POST['id'];
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        echo '<option value="">Bus type</option>';
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['id'] . '">' . $row['bus_model'] . ' (' . $row['total_seat'] . ' seats)</option>';
        }
    } else {
        echo '<option>no data found</option>';
    }
} else {
    $query = "SELECT * FROM buses";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        echo '<option value="">Bus type</option>';
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['id'] . '">' . $row['bus_model'] . ' (' . $row['total_seat'] . ' seats)</option>';
        }
    } else {
        echo '<option>no data found</option>';
    }
}
