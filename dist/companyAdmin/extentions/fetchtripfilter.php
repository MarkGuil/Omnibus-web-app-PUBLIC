<?php

session_start();
include('../../database/db.php');
$id = $_SESSION['compadminID'];

if ($_POST['terminalid']) {
    $query = "SELECT DISTINCT origin,destination,routeID FROM `trip` WHERE `companyID` = $id AND `terminalID` = " . $_POST['terminalid'];
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        // $trips = $result->fetch_all(MYSQLI_ASSOC);
        echo '<option value="">Select route</option>';
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['routeID'] . '">' . $row['origin'] . ' - ' . $row['destination'] . '</option>';
        }
    } else {
        echo '<option>no data found</option>';
    }
}
// else{
//     $query = "SELECT DISTINCT origin,destination,routeID FROM trip WHERE companyID = $id ";
//     $result = $conn->query($query);
//     if($result->num_rows > 0){
//         // $trips = $result->fetch_all(MYSQLI_ASSOC);
//         echo '<option value="">Select route</option>';
//         while($row = $result->fetch_assoc()){
//             echo '<option value="'.$row['routeID'].'">'.$row['origin'].' - '.$row['destination'].'</option>';
//         }
//     } else {
//         echo '<option>no data found</option>';
//     }
// }
