<?php

session_start();
include('../../database/db.php');

$id = $_SESSION['compadminNameID'];
$termID = $_SESSION['terminalID'];

if (isset($_POST['busid']) && $_POST['busid'] != '' && isset($_POST['starttime']) && $_POST['starttime'] == '' && isset($_POST['endtime']) && $_POST['endtime'] == '') {
    $busid = $_POST['busid'];
    $result222 = $conn->query("SELECT a.id, a.origin, a.destination, a.departure_time, a.routeID, b.trip_date, b.tripID, b.busID FROM trip a RIGHT OUTER JOIN bus_trip b ON(a.id = b.tripID) WHERE a.companyID = $id AND b.busID = $busid AND b.terminalID = $termID");
    $tripss = $result222->fetch_all(MYSQLI_ASSOC);
    $times = "11:00 PM";
    $times2 = "11:59 PM";
    for ($x = 0; $x <= 23; $x++) {
        $timestamp = strtotime($times) + 60 * 60;
        $timestamp2 = strtotime($times2) + 60 * 60;
        $times = date('h:i A', $timestamp);
        $times2 = date('h:i A', $timestamp2);

        echo "<tr>";
        echo '<th class="p-3" width="13%">' . $times;
        '.</th>';

        $dt_min = new DateTime("last saturday");
        $dt_min->modify('+1 day');
        $dt_max = clone ($dt_min);

        for ($y = 0; $y <= 6; $y++) {
            $curDate = date_format($dt_max, 'Y-m-d');
            echo "<td class='p-0'>";
            foreach ($tripss as $trp) {

                $depttime = date('h:i A', strtotime($trp['departure_time']));
                if ($trp['trip_date'] == $curDate) {

                    if (strtotime($depttime) >= strtotime($times) && strtotime($depttime) <= strtotime($times2)) {
                        echo "<div class='py-2 px-3' style='background-color:#e4ffde;'><h6>" . $trp['origin'] . " - " . $trp['destination'] . "</h6>";
                        echo "<small>" . date('h:i A', strtotime($trp['departure_time'])) . "</small><br>";
                        $nbusid = $trp['busID'];
                        $resBus = $conn->query("SELECT * FROM buses WHERE id = '$nbusid' AND companyID='$id'");
                        $busDets = $resBus->fetch_all(MYSQLI_ASSOC);

                        foreach ($busDets as $busDet) {
                            echo "<small>Bus #: " . $busDet['busNumber'] . "</small>";
                        }

                        echo "</div>";
                    }
                }
            }
            echo "</td>";
            $dt_max->modify('+1 days');
        }

        echo "</tr>";
    }
} else if (isset($_POST['busid']) && $_POST['busid'] != '' && isset($_POST['starttime']) && $_POST['starttime'] != '' && isset($_POST['endtime']) && $_POST['endtime'] != '') {
    // $termid = $_POST['termid'];
    $busid = $_POST['busid'];
    $start = $_POST['starttime'];
    $start2 = $_POST['starttime'];
    $timestamp2 = strtotime($start2) + 60 * 59;
    $start3 = date('h:i A', $timestamp2);
    $end = $_POST['endtime'];

    $difference = round(abs(strtotime($_POST['starttime']) - strtotime($_POST['endtime'])) / 3600, 2);
    // echo $difference;
    $result222 = $conn->query("SELECT a.id, a.origin, a.destination, a.departure_time, a.routeID, b.trip_date, b.tripID, b.busID FROM trip a RIGHT OUTER JOIN bus_trip b ON(a.id = b.tripID) WHERE a.companyID = $id AND b.busID = $busid AND b.terminalID = $termID");
    $tripss = $result222->fetch_all(MYSQLI_ASSOC);
    for ($x = 0; $x <= $difference; $x++) {
        echo "<tr>";
        echo '<th class="p-3" width="13%">' . $start;
        '.</th>';
        $timestamp = strtotime($start) + 60 * 60;
        $start = date('h:i A', $timestamp);
        $timestamp3 = strtotime($start3) + 60 * 60;
        $start3 = date('h:i A', $timestamp3);

        $dt_min = new DateTime("last saturday");
        $dt_min->modify('+1 day');
        $dt_max = clone ($dt_min);

        for ($y = 0; $y <= 6; $y++) {
            $curDate = date_format($dt_max, 'Y-m-d');
            echo "<td class='p-0'>";
            foreach ($tripss as $trp) {

                $depttime = date('h:i A', strtotime($trp['departure_time']));
                if ($trp['trip_date'] == $curDate) {

                    if (strtotime($depttime) >= strtotime($start) && strtotime($depttime) <= strtotime($start3)) {
                        echo "<div class='py-2 px-3' style='background-color:#e4ffde;'><h6>" . $trp['origin'] . " - " . $trp['destination'] . "</h6>";
                        echo "<small>" . date('h:i A', strtotime($trp['departure_time'])) . "</small><br>";
                        $nbusid = $trp['busID'];
                        $resBus = $conn->query("SELECT * FROM buses WHERE id = '$nbusid' AND companyID='$id'");
                        $busDets = $resBus->fetch_all(MYSQLI_ASSOC);

                        foreach ($busDets as $busDet) {
                            echo "<small>Bus #: " . $busDet['busNumber'] . "</small>";
                        }

                        echo "</div>";
                    }
                }
            }
            echo "</td>";
            $dt_max->modify('+1 days');
        }

        echo "</tr>";
    }
}
