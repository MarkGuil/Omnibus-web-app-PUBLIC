<?php
// include('server_comp_admin.php');
session_start();
include('../database/db.php');

$id = $_SESSION['compadminID']; 

$column = array('id','origin','destination','departure_time','terminalID');
$query = "SELECT * FROM `trip` WHERE `companyID` = $id ";

// if(isset($_POST['filter_termid']) && $_POST['filter_termid'] != '')
// {
//     $query .= 'AND terminalID = '.$_POST['filter_termid'].' ';
// }
// if(isset($_POST['filter_route']) && $_POST['filter_route'] != '')
// {
//     $query .= 'AND routeID = '.$_POST['filter_route'].' ';
// }
if(isset($_POST['filter_termid']) && $_POST['filter_termid'] != '' && isset($_POST['filter_route']) && $_POST['filter_route'] != '')
{
    $query .= 'AND `terminalID` = '.$_POST['filter_termid'].' AND `routeID` = '.$_POST['filter_route'].' ';
}
else if(isset($_POST['filter_termid']) && $_POST['filter_termid'] != '' && isset($_POST['filter_route']) && $_POST['filter_route'] == '')
{
    $query .= 'AND `terminalID` = '.$_POST['filter_termid'].' ';
}

if(isset($_POST['order']))
{
    $query .= 'ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
else
{
    $query .= 'ORDER BY `origin` ASC ';
}

$query1 = '';

if($_POST['length'] != -1)
{
    $query1 = 'LIMIT '.$_POST['start'].', '.$_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$number_filter_row =  $statement->rowCount();

$statement = $connect->prepare($query . $query1);

$statement->execute();

$result = $statement->fetchAll();

$data = array();

foreach($result as $row)
{
    $sub_array = array();
    $sub_array[] = $row['id'];
    $sub_array[] = '<span id="origin'.$row['id'].'">'.$row['origin'].'</span>';
    $sub_array[] = '<span id="destination'.$row['id'].'">'.$row['destination'].'</span>';
    $sub_array[] = '<span id="duration'.$row['id'].'">'.date('h:i A', strtotime($row['departure_time'])).'</span>';
    $termid = $row['terminalID'];
    $ress = $conn->query("SELECT `terminal_name` FROM `terminal` WHERE `id` = '$termid' AND `companyID`='$id'");
    $terminalID = array_values($ress->fetch_assoc());
    $d = $row['duration'];
    if($d != ''){
    $pieces = explode(":", $d);
    $sub_array[] = '<span id="hour'.$row['id'].'">'.$pieces[0].'</span> hrs : <span id="mins'.$row['id'].'">'.$pieces[1].'</span> mins';
    }
    else{
        $sub_array[]='Undefined';
    }
    $sub_array[] = '<td>
    <a href="home_admin_trips.php#editTripModal" class="edit" data-bs-toggle="modal" data-userid="'.$row['id'].'"><i class="material-icons text-warning" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
    <a href="home_admin_trips.php#mymodaldeleteTrips" class="delete" data-bs-toggle="modal" data-userid="'.$row['id'].'"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
</td>';
    $data[] = $sub_array;
}

function count_all_data($connect,$id)
{
    // $id = $_SESSION['compadminID']; 
    $query = "SELECT * FROM `trip` WHERE `companyID` = $id";
    $statement = $connect->prepare($query);
    $statement->execute();
    return $statement->rowCount();
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($connect,$id),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);
