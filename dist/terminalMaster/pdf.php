<?php 

// require 'vendor/autoload.php';
// require 'vendor/autoload.php';
require_once 'dompdf/autoload.inc.php';
// use Dompdf\Dompdf;
// $dompdf = new Dompdf();
use Dompdf\Dompdf;

include('../database/db.php');
include('server_terminal_master.php');

  $id = $_SESSION['compadminNameID'];
//   $termID = $_SESSION['terminalID'];
//   $email = $_SESSION['compTerMasemail'];
//   $id = $_SESSION['compTerMasID'];
$bustrpid = $_GET['id'];

$result223 = $conn->query("SELECT a.id,a.companyID,a.terminalID,a.bustripID,a.customerID,a.origin,a.destination,a.duration,a.departure_time,a.number_of_seats,a.fare_amount,a.total_amount,a.payment_status,a.booked_at,a.booking_status,a.reference_id,b.trip_date,b.taken_seat,b.tripID,b.busID FROM booking_tbl a RIGHT OUTER JOIN bus_trip b ON(a.bustripID = b.id) WHERE a.companyID = '$id' AND a.bustripID = '$bustrpid' ");
$details = $result223->fetch_all(MYSQLI_ASSOC);
$result1 = $conn->query("SELECT * FROM bus_trip WHERE id='$bustrpid' AND companyID = '$id' ");
$bustrips = $result1->fetch_all(MYSQLI_ASSOC);
$x=1;
$html = '<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>OmnibusAdmin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css"/>
 
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
<script src="https://kit.fontawesome.com/9bfa39220a.js" crossorigin="anonymous"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
    <style>
        body {
            background-color: #F4F4F4;
        }

        @media (min-width: 768px) {
            .container-small {
                width: 500px;
            }

            .container-medium {
                width: 850px;
            }

            .container-large {
                width: 970px;
            }
        }

        @media (min-width: 992px) {
            .container-small {
                width: 500px;
            }

            .container-medium {
                width: 1000px;
            }

            .container-large {
                width: 1170px;
            }
        }

        @media (min-width: 1200px) {
            .container-small {
                width: 650px;
            }

            .container-medium {
                width: 1350px;
            }

            .container-large {
                width: 1500px;
            }
        }

        .container-small,
        .container-medium,
        .container-large {
            max-width: 100%;
        }

        .table-stripeds>tbody>tr:nth-child(even)>td,
        .table-stripeds>tbody>tr:nth-child(even)>th {
            background-color: #f8f9fa;
        }

        .table-stripeds>tbody>tr:nth-child(odd)>td,
        .table-stripeds>tbody>tr:nth-child(odd)>th {
            background-color: #ffffff;
        }
        th, td {
            padding: 5px 10px 5px 2px;
            text-align: left;
            border-bottom: 1px solid #ddd;
          }
          table{
              border: 1px solid black;
          }
    </style>

</head>

<body>
    <div class="container container-large">
        <div class="text-center mt-3 mb-3">
            <h2 class="text-primary">Omnibus</h2>
            <h5 class="text-muted">Bus trip report</h5>';

    foreach($bustrips as $bustrip) {
        $tripID = $bustrip['tripID'];
        $resultT = $conn->query("SELECT origin,destination,departure_time FROM trip WHERE id = '$tripID' AND companyID='$id'");
        $oridest = array_values($resultT->fetch_assoc());
        $terminalID = $bustrip['terminalID'];
        $resultT = $conn->query("SELECT terminal_name FROM terminal WHERE id = '$terminalID' AND companyID='$id'");
        $terms = array_values($resultT->fetch_assoc());
        $html .= '<h6 class="text-muted">'. $terms[0].' ('.$oridest[0].' - '.$oridest[1].')' .'</h6>
        ';
    }

    $html .= '</div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Bus trip id</th>
                <th scope="col">Date</th>
                <th scope="col">Time</th>
                <th scope="col">Fare</th>
                <th scope="col">Bus no.</th>
                <th scope="col">Driver</th>
                <th scope="col">Operator</th>
                <th scope="col">Assigned By</th>
            </tr>
        </thead>
        <tbody>';

    foreach($bustrips as $bustrip){
        $html .= '<tr>';
            $driverID = $bustrip['driverID'];
            if($driverID == null){
            } else {
                $resultD = $conn->query("SELECT fullname FROM employees WHERE id = '$driverID' AND companyID='$id'");
                $driverName = array_values($resultD->fetch_assoc());
            }
            $conductorID = $bustrip['conductorID'];
            if($conductorID == null){
            } else {
                $resultC = $conn->query("SELECT fullname FROM employees WHERE id = '$conductorID' AND companyID='$id'");
                $conductorName = array_values($resultC->fetch_assoc());
            }
        $html .= '<th>'. $bustrip['id'] .'</th>
        <td>'. $bustrip['trip_date'] .'</td>
        <td>'. $oridest[2] .'</td>
        <td>'. $bustrip['fare'] .'</td>
        <td>'. $bustrip['busID'] .'</td>
        <td>'. $driverName[0] .'</td>
        <td>'. $conductorName[0] .'</td>
        <td>'. $bustrip['assigned_by'] .'</td>
        </tr>';
    }

    $html .= '</tbody>
    </table>
    <div class="text-center mt-3 mb-3">
        <h5>Passengers</h5>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Age</th>
                <th scope="col">Gender</th>
                <th scope="col">Seat Number</th>
                <th scope="col">Booking Status</th>
            </tr>
        </thead>
        <tbody>';

    foreach($details as $detail){
        $datetime = new DateTime();
        $timezone = new DateTimeZone('Asia/Manila');
        $bookingID = $detail['id'];
        $bookdetatils = $conn->query("SELECT * FROM customer_booking_details WHERE bookingID = '$bookingID'");
        $bookcusdetatils = $bookdetatils->fetch_all(MYSQLI_ASSOC);
        foreach($bookcusdetatils as $bookcusdetatil) {
            $html .= '<tr>
            <td>'. $x++ .'</th>
            <td>'. $bookcusdetatil['first_name'] .'</th>
            <td>'. $bookcusdetatil['last_name'] .'</td>
            <td>'. $bookcusdetatil['age'] .'</td>
            <td>'. $bookcusdetatil['gender'] .'</td>
            <td>'. $bookcusdetatil['seat_number'] .'</td>
            <td>';
            if($detail['booking_status'] == 'confirmed'){
                if(date('Y/m/d', strtotime($detail['trip_date'])) >= $datetime->format('Y/m/d') && date('h:i A', strtotime($detail['departure_time'])) > $datetime->format('h:i A')){
                    $html .= "confirmed";
                } 
                else {
                    $html .= "missed";
                }
            } else{
                $html .= $detail['booking_status'];
            }
            $html .= '</td>
            </tr>';
        }
    }
    $html .= '</tbody>
    </table>
</div>

</body>

</html>';


$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Bus Trip Report.pdf", array("Attachment" => false));
exit(0);
