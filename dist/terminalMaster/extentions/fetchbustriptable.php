<?php
session_start();
include('../../database/db.php');

$id = $_SESSION['compadminNameID'];
$terminal_id = $_SESSION['terminalID'];

$column = array('id', 'date', 'time', 'fare', 'bus', 'driver', 'conductor', 'assigned by', 'action');
$query = "SELECT * FROM bus_trip WHERE companyID = $id ";

if (isset($_POST['filter_route']) && $_POST['filter_route'] != '' && isset($_POST['filter_date']) && $_POST['filter_date'] != '') {
    $query .= 'AND trip_date = "' . $_POST['filter_date'] . '" AND routeID = ' . $_POST['filter_route'] . ' AND terminalID = ' . $terminal_id . ' ';
} else if (isset($_POST['filter_route']) && $_POST['filter_route'] != '' && isset($_POST['filter_date']) && $_POST['filter_date'] == '') {
    $query .= 'AND routeID = ' . $_POST['filter_route'] . ' AND terminalID = ' . $terminal_id . ' ';
}

if (isset($_POST['order'])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= 'ORDER BY id ASC ';
}

$query1 = '';

if ($_POST['length'] != -1) {
    $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$number_filter_row =  $statement->rowCount();

$statement = $connect->prepare($query . $query1);

$statement->execute();

$result = $statement->fetchAll();

$data = array();

foreach ($result as $row) {
    $sub_array = array();
    $sub_array[] = $row['id'];
    $tripID = $row['tripID'];
    $resultT = $conn->query("SELECT origin,destination,departure_time FROM trip WHERE id = '$tripID' AND companyID='$id'");
    $oridest = array_values($resultT->fetch_assoc());
    // $sub_array[] = $oridest[0].' - '.$oridest[1];
    $sub_array[] = $row['trip_date'];
    $sub_array[] = date('h:i A', strtotime($oridest[2]));
    $sub_array[] = 'Php <span id="fare' . $row['id'] . '">' . $row['fare'] . '</span>';

    $busID = $row['busID'];
    $resultB = $conn->query("SELECT busNUmber FROM buses WHERE id = '$busID' AND companyID='$id'");
    $busName = array_values($resultB->fetch_assoc());
    $sub_array[] = '<span style="display:none" id="busID' . $row['id'] . '">' . $row['busID'] . '</span> <span id="busName' . $row['id'] . '">' . $busName[0] . '</span>';

    $driverID = $row['driverID'];
    $resultD = $conn->query("SELECT fullname FROM employees WHERE id = '$driverID' AND companyID='$id'");
    $driverName = array_values($resultD->fetch_assoc());
    $sub_array[] = $driverName[0];
    $conductorID = $row['conductorID'];
    $resultC = $conn->query("SELECT fullname FROM employees WHERE id = '$conductorID' AND companyID='$id'");
    $conductorName = array_values($resultC->fetch_assoc());
    $sub_array[] = $conductorName[0];
    $sub_array[] = $row['assigned_by'];
    $sub_array[] = '<a href="pdf.php?id=' . $row['id'] . '" class="dropdown-item" target="_blank">Generate Report</a>';
    // $sub_array[] = '
    // <div class="dropdown">
    //     <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    //         Action
    //     </button>
    //     <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    //         <a href="home_admin_trips.php#editBusTripModal" class="dropdown-item" data-bs-toggle="modal" data-userid="'.$row['id'].'">Change Bus</a>
    //         <a href="home_admin_trips.php#editBusTripModaldc" class="dropdown-item" data-bs-toggle="modal" data-userid="'.$row['id'].'">Change Driver & Conductor</a>
    //         <div class="dropdown-divider"></div>
    //         <a href="bus_trip_view.php#mymodaldeleteBusTrip" class="dropdown-item delete" data-bs-toggle="modal" data-userid="'.$row['id'].'">Delete</a>
    //     </div>
    // </div>';
    $data[] = $sub_array;
}

function count_all_data($connect, $id)
{
    // $id = $_SESSION['compadminID']; 
    $query = "SELECT * FROM bus_trip WHERE companyID = $id";
    $statement = $connect->prepare($query);
    $statement->execute();
    return $statement->rowCount();
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($connect, $id),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);
