<?php
// include('server_comp_admin.php');
session_start();
include('../database/db.php');

$id = $_SESSION['compadminID']; 

$column = array('#','Bus trip ID','Reference no','Routes','Customer','Number of Seats','Total Amount','Date','Departure Time','Booking Status','Action');
$query = "SELECT a.id,a.companyID,a.terminalID,a.bustripID,a.customerID,a.origin,a.destination,a.duration,a.departure_time,a.number_of_seats,a.fare_amount,a.total_amount,a.payment_status,a.booked_at,a.booking_status,a.reference_id,b.trip_date,b.taken_seat,b.tripID,b.busID FROM booking_tbl a RIGHT OUTER JOIN bus_trip b ON(a.bustripID = b.id) WHERE a.companyID = '$id' ";

// if(isset($_POST['filter_termid']) && $_POST['filter_termid'] != '' && isset($_POST['filter_route']) && $_POST['filter_route'] != '' && isset($_POST['filter_date']) && $_POST['filter_date'] != '')
// {
//     $query .= 'AND trip_date = "'.$_POST['filter_date'].'" AND routeID = '.$_POST['filter_route'].' AND terminalID = '.$_POST['filter_termid'].' ';
// }
// else if(isset($_POST['filter_termid']) && $_POST['filter_termid'] != '' && isset($_POST['filter_route']) && $_POST['filter_route'] != '' && isset($_POST['filter_date']) && $_POST['filter_date'] == '')
// {
//     $query .= 'AND routeID = '.$_POST['filter_route'].' AND terminalID = '.$_POST['filter_termid'].' ';
// }

if(isset($_POST['order']))
{
    $query .= 'ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
else
{
    $query .= 'ORDER BY id ASC ';
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
    if($row["booking_status"] == 'cancelled'){
        $sub_array[] = $x++;
        $sub_array[] = $row['bustripID'];
        $sub_array[] = $row['reference_id'];
        $sub_array[] = $row['origin'].' - '.$row['destination'];
        
        $userid= $row["customerID"]; 
        $resultc = $conn->query("SELECT fullname FROM user_customer WHERE id = '$userid'");
        $customer = array_values($resultc->fetch_assoc());
        $sub_array[] = $customer[0];
        
        $sub_array[] = $row['number_of_seats'];
        $sub_array[] = 'Php'.$row['total_amount'];
        $sub_array[] = $row['trip_date'];
        $sub_array[] = date('h:i A', strtotime($row['departure_time']));
        
        $status = $row["booking_status"];
        switch ($status) {
            case 'confirmed': 
                $sub_array[] = '<div class="">
                    <span>Confirmed</span>
                </div>';
                break;
            case 'requested for cancellation':
                
                $sub_array[] = '<div class="dropdown px-0">
                <button class="btn btn-link dropdown-toggle px-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Requested for cancellation
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <form method="post" >
                    <input type="hidden" name="bustripID" value="'.$row["bustripID"].'">
                    <input type="hidden" name="taken_seat" value="'.$row["taken_seat"].'">
                    <input type="hidden" name="totalSeats" value="'.$row["number_of_seats"].'">
                    <button class="dropdown-item" type="submit" name="canbook" value="'.$row['id'].'">Cancel Booking</button>
                </form>
                </div>
                </div>';
                break;
            case 'travelled':
                
                $sub_array[] = '<div class="">
                    <span>Travelled</span>
                </div>';
                break;
            case 'cancelled':
                
                $sub_array[] = '<div class="">
                    <span>Cancelled</span>
                </div>';
                break;
        }
        
        $sub_array[] = '<a href="customer_booking_details.php?i='.$row['id'].'" class="edit" data-userid="'.$row['id'].'"><i class="fas fa-file-alt text-success"></i></a>
            ';
    }
    $data[] = $sub_array;
}

function count_all_data($connect,$id)
{
    // $id = $_SESSION['compadminID']; 
    $query = "SELECT a.id,a.companyID,a.terminalID,a.bustripID,a.customerID,a.origin,a.destination,a.duration,a.departure_time,a.number_of_seats,a.fare_amount,a.total_amount,a.payment_status,a.booked_at,a.booking_status,a.reference_id,b.trip_date,b.taken_seat,b.tripID,b.busID FROM booking_tbl a RIGHT OUTER JOIN bus_trip b ON(a.bustripID = b.id) WHERE a.companyID = '$id' ";
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
