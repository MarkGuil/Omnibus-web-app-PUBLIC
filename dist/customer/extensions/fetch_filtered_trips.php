<?php

session_start();
$name = $_SESSION['customername'];
$sorigin = $_SESSION['selectedorigin'];
$sdest = $_SESSION['selecteddestination'];
$sdate = $_SESSION['selecteddate'];
$sday = $_SESSION['selectedday'];
$numofpassenger = $_SESSION['selectednumofpassenger'];
include('../../database/db.php');

if (isset($_POST['companyid']) && $_POST['companyid'] != '' && isset($_POST['busid']) && $_POST['busid'] == '') {
    $cmpID = $_POST['companyid'];
    $result1 = $conn->query("SELECT COUNT(id) FROM trip WHERE origin = '$sorigin' AND destination = '$sdest'");
    $arrtrips = array_values($result1->fetch_assoc());

    $result222 = $conn->query("SELECT a.origin, a.destination, a.duration, a.departure_time, a.routeID,b.id,b.companyID, b.trip_date, b.fare, b.total_seat, b.taken_seat, b.tripID, b.busID, b.terminalID FROM trip a RIGHT OUTER JOIN bus_trip b ON(a.id = b.tripID) WHERE a.origin = '$sorigin' AND a.destination = '$sdest' AND b.companyID = '$cmpID' AND b.trip_date = '$sdate' AND b.driverID IS NOT NULL AND b.conductorID IS NOT NULL AND b.busID IS NOT NULL ORDER BY a.departure_time DESC ");

    // $result222 = $conn->query("SELECT a.origin, a.destination, a.departure_time, a.routeID,b.id,b.companyID, b.trip_date, b.fare, b.total_seat, b.taken_seat, b.tripID, b.busID, b.terminalID FROM trip a RIGHT OUTER JOIN bus_trip b ON(a.id = b.tripID) WHERE  a.origin = '$sorigin' AND a.destination = '$sdest' AND b.companyID = '$cmpID' AND b.trip_date = '$sdate'");
    $tripss = $result222->fetch_all(MYSQLI_ASSOC);

    if (!empty($tripss)) {

        foreach ($tripss as $trip) {
            $trpid = $trip['id'];
            $trptime = $trip['departure_time'];
            $remainingSeat = $trip['total_seat'] - $trip['taken_seat'];
            $datetime = new DateTime();
            $timezone = new DateTimeZone('Asia/Manila');
            $datetime->setTimezone($timezone);
            if (date('Y/m/d', strtotime($trip['trip_date'])) == $datetime->format('Y/m/d') && date('h:i A', strtotime($trip['departure_time'])) > $datetime->format('h:i A')) {
                if ($trip['taken_seat'] != $trip['total_seat'] && $remainingSeat >= $numofpassenger) {
                    $comid = $trip['companyID'];
                    $busid = $trip['busID'];

                    // echo ;
                    $result12 = $conn->query("SELECT companyName FROM user_partner_admin WHERE id = '$comid'");
                    $compName = array_values($result12->fetch_assoc());
                    $result133 = $conn->query("SELECT seat_type,bus_model FROM buses WHERE id = '$busid'");
                    $compBus = array_values($result133->fetch_assoc());

                    echo '<div class="trps position-relative mt-3 mx-3 px-3 py-2 shadow rounded border-left border-success bg-white" style="border-width: 4px !important;">
                        <div class="row no-gutters">
                            <div class="col pl-2">
                            
                                <span class="" style="font-size: 1.2em; font-weight: 300;  color: #7E7E7E">' . $compName[0] . '</span>
                            
                            </div>
                            <div class="col pxr3 ">
                                <div class="float-right">
                                <i class="fa fa-check-circle mr-1 text-success" aria-hidden="true" style="font-size: .7em ;"></i>
                                <span class="text-success" style="font-size: .8em ;line-height: 2.3em; ">
                                    ' . $remainingSeat . ' remaining seat
                                </span>
                                </div>
                            </div>
                        </div>
                        <span class="text-muted"><small style="font-size: .9em; color: #A5A5A5">Departure time: </small>' . date('h:i A', strtotime($trip['departure_time'])) . '</span><br>
                                    <span class="text-muted">
                                        <small style="font-size: .9em; color: #A5A5A5">Bus details: <br>
                                            <b>' . $compBus[0] . ' seat - ' . $compBus[1] . '
                                            </b>
                                        </small>
                                    </span>
                                    <br><br>
                        

                        <div class="d-flex flex-row">
                            <span class="" style="font-size: 1.1em; font-weight: 300; line-height: 1.7em; color: #949494">Php ' . $trip['fare'] . '</span>
                            <form action="" method="Post">
                                <input type="hidden" class="form-control" name="tripsid" value="' . $trip['id'] . '" readonly>
                                <input type="hidden" class="form-control" name="tripfare" value="' . $trip['fare'] . '" readonly>
                                <input type="hidden" class="form-control" name="busid" value="' . $trip['busID'] . '" readonly>
                                <input type="hidden" class="form-control" name="compid" value="' . $trip['companyID'] . '" readonly>
                                <input type="hidden" class="form-control" name="tripschedid" value="' . $trip['tripID'] . '" readonly>
                                <input type="hidden" class="form-control" name="terminalid" value="' . $trip['terminalID'] . '" readonly>
                                <input type="hidden" class="form-control" name="duration" value="' . $trip['duration'] . '" readonly>
                                <input type="hidden" class="form-control" name="departure_time" value="' . $trip['departure_time'] . '" readonly>
                                <button class="btn stretched-link" type="submit" name="confirmTrip"></button>
                            </form>
                        </div>
                    </div>';
                } else {
                    $comid = $trip['companyID'];
                    $busid = $trip['busID'];

                    // echo ;
                    $result12 = $conn->query("SELECT companyName FROM user_partner_admin WHERE id = '$comid'");
                    $compName = array_values($result12->fetch_assoc());
                    $result133 = $conn->query("SELECT seat_type,bus_model FROM buses WHERE id = '$busid'");
                    $compBus = array_values($result133->fetch_assoc());

                    echo '<div class="bg-light position-relative mt-3 mx-3 px-3 py-2 shadow rounded border-left border-secondary" style="border-width: 4px !important;">
                        <div class="row no-gutters">
                            <div class="col pl-2">
                                <span class="" style="font-size: 1.2em; font-weight: 300;  color: #7E7E7E">' . $compName[0] . '</span>
                            </div>
                            <div class="col pr-3 ">
                                <div class="float-right">
                                <i class="fa fa-minus-circle mr-1 text-secondary" aria-hidden="true" style="font-size: .7em ;"></i>
                                <span class="text-secondary" style="font-size: .8em ;line-height: 2.3em; ">Full</span>
                                </div>
                            </div>
                        </div>
                            <span class="" style="font-size: .9em; color: #A5A5A5">' . $trip['departure_time'] . '</span><br>
                                    <span class="text-muted">
                                        <small style="font-size: .9em; color: #A5A5A5">Bus details: <br>
                                            <b>' . $compBus[0] . ' seat - ' . $compBus[1] . '
                                            </b>
                                        </small>
                                    </span>
                                    <br><br>
                        
                        <span class="" style="font-size: 1.1em; font-weight: 300; line-height: 1.7em; color: #949494">Php ' . $trip['fare'] . '</span>
                        <!-- <div class=""> -->
                            <!-- <a href="confirmTrip.php" class="stretched-link"></a> -->
                        <!-- </div> -->
                    </div>';
                }
            } else {
                if ($trip['taken_seat'] != $trip['total_seat'] && $remainingSeat >= $numofpassenger) {
                    $comid = $trip['companyID'];
                    $busid = $trip['busID'];

                    // echo ;
                    $result12 = $conn->query("SELECT companyName FROM user_partner_admin WHERE id = '$comid'");
                    $compName = array_values($result12->fetch_assoc());
                    $result133 = $conn->query("SELECT seat_type,bus_model FROM buses WHERE id = '$busid'");
                    $compBus = array_values($result133->fetch_assoc());

                    echo '<div class="trps position-relative mt-3 mx-3 px-3 py-2 shadow rounded border-left border-success bg-white" style="border-width: 4px !important;">
                        <div class="row no-gutters">
                            <div class="col pl-2">
                            
                                <span class="" style="font-size: 1.2em; font-weight: 300;  color: #7E7E7E">' . $compName[0] . '</span>
                            
                            </div>
                            <div class="col pr-3 ">
                                <div class="float-right">
                                <i class="fa fa-check-circle mr-1 text-success" aria-hidden="true" style="font-size: .7em ;"></i>
                                <span class="text-success" style="font-size: .8em ;line-height: 2.3em; ">
                                
                                    ' . $remainingSeat . ' remaining seat
                                
                                </span>
                                </div>
                            </div>
                        </div>
                        <span class="text-muted"><small style="font-size: .9em; color: #A5A5A5">Departure time: </small>' . date('h:i A', strtotime($trip['departure_time'])) . '</span><br>
                                    <span class="text-muted">
                                        <small style="font-size: .9em; color: #A5A5A5">Bus details: <br>
                                            <b>' . $compBus[0] . ' seat - ' . $compBus[1] . '
                                            </b>
                                        </small>
                                    </span>
                                    <br><br>    
                        
                        

                        <div class="d-flex flex-row">
                            <span class="" style="font-size: 1.1em; font-weight: 300; line-height: 1.7em; color: #949494">Php ' . $trip['fare'] . '</span>
                            <form action="" method="Post">
                                <input type="hidden" class="form-control" name="tripsid" value="' . $trip['id'] . '" readonly>
                                <input type="hidden" class="form-control" name="tripfare" value="' . $trip['fare'] . '" readonly>
                                <input type="hidden" class="form-control" name="busid" value="' . $trip['busID'] . '" readonly>
                                <input type="hidden" class="form-control" name="compid" value="' . $trip['companyID'] . '" readonly>
                                <input type="hidden" class="form-control" name="tripschedid" value="' . $trip['tripID'] . '" readonly>
                                <input type="hidden" class="form-control" name="terminalid" value="' . $trip['terminalID'] . '" readonly>
                                <input type="hidden" class="form-control" name="duration" value="' . $trip['duration'] . '" readonly>
                                <input type="hidden" class="form-control" name="departure_time" value="' . $trip['departure_time'] . '" readonly>
                                <button class="btn stretched-link" type="submit" name="confirmTrip"></button>
                            </form>
                        </div>
                    </div>';
                } else {
                    $comid = $trip['companyID'];
                    $busid = $trip['busID'];

                    // echo ;
                    $result12 = $conn->query("SELECT companyName FROM user_partner_admin WHERE id = '$comid'");
                    $compName = array_values($result12->fetch_assoc());
                    $result133 = $conn->query("SELECT seat_type,bus_model FROM buses WHERE id = '$busid'");
                    $compBus = array_values($result133->fetch_assoc());
                    echo '<div class="bg-light position-relative mt-3 mx-3 px-3 py-2 shadow rounded border-left border-secondary" style="border-width: 4px !important;">
                        <div class="row no-gutters">
                            <div class="col pl-2">
                                <span class="" style="font-size: 1.2em; font-weight: 300;  color: #7E7E7E">
                                ' . $compName[0] . '
                            </span>
                            </div>
                            <div class="col pr-3 ">
                                <div class="float-right">
                                <i class="fa fa-minus-circle mr-1 text-secondary" aria-hidden="true" style="font-size: .7em ;"></i>
                                <span class="text-secondary" style="font-size: .8em ;line-height: 2.3em; ">Full</span>
                                </div>
                            </div>
                        </div>
                            <span class="" style="font-size: .9em; color: #A5A5A5">' . $trip['departure_time'] . '</span><br>
                                    <span class="text-muted">
                                        <small style="font-size: .9em; color: #A5A5A5">Bus details: <br>
                                            <b>' . $compBus[0] . ' seat - ' . $compBus[1] . '
                                            </b>
                                        </small>
                                    </span>
                                    <br><br>
                            <span class="" style="font-size: 1.1em; font-weight: 300; line-height: 1.7em; color: #949494">Php ' . $tripdate['fare'] . '</span>
                        
                        
                        <!-- <div class=""> -->
                            <!-- <a href="confirmTrip.php" class="stretched-link"></a> -->
                        <!-- </div> -->
                    </div>';
                }
            }
        }
    } else {
        echo '<div class="no-gutters position-relative mt-3 mx-3 shadow rounded" style="background-color:#F44E4E;">
            <div class="text-center text-light p-3">
                No Data Found
            </div>
        </div>';
    }
} else if (isset($_POST['companyid']) && $_POST['companyid'] != '' && isset($_POST['busid']) && $_POST['busid'] != '') {
    $cmpID = $_POST['companyid'];
    $bsID = $_POST['busid'];
    $result1 = $conn->query("SELECT COUNT(id) FROM trip WHERE origin = '$sorigin' AND destination = '$sdest'");
    $arrtrips = array_values($result1->fetch_assoc());


    $result222 = $conn->query("SELECT a.origin, a.destination, a.duration, a.departure_time, a.routeID,b.id,b.companyID, b.trip_date, b.fare, b.total_seat, b.taken_seat, b.tripID, b.busID, b.terminalID FROM trip a RIGHT OUTER JOIN bus_trip b ON(a.id = b.tripID) WHERE a.origin = '$sorigin' AND a.destination = '$sdest' AND b.companyID = '$cmpID' AND b.trip_date = '$sdate' AND b.driverID IS NOT NULL AND b.conductorID IS NOT NULL AND b.busID IS NOT NULL AND b.busID = '$bsID' ORDER BY a.departure_time DESC ");

    // $result222 = $conn->query("SELECT a.origin, a.destination, a.departure_time, a.routeID,b.id,b.companyID, b.trip_date, b.fare, b.total_seat, b.taken_seat, b.tripID, b.busID, b.terminalID FROM trip a RIGHT OUTER JOIN bus_trip b ON(a.id = b.tripID) WHERE  a.origin = '$sorigin' AND a.destination = '$sdest' AND b.companyID = '$cmpID' AND b.trip_date = '$sdate' AND b.busID = '$bsID'");
    $tripss = $result222->fetch_all(MYSQLI_ASSOC);

    if (!empty($tripss)) {

        foreach ($tripss as $trip) {
            $trpid = $trip['id'];
            $trptime = $trip['departure_time'];
            $remainingSeat = $trip['total_seat'] - $trip['taken_seat'];
            $datetime = new DateTime();
            $timezone = new DateTimeZone('Asia/Manila');
            $datetime->setTimezone($timezone);
            if (date('Y/m/d', strtotime($trip['trip_date'])) == $datetime->format('Y/m/d') && date('h:i A', strtotime($trip['departure_time'])) > $datetime->format('h:i A')) {
                if ($trip['taken_seat'] != $trip['total_seat'] && $remainingSeat >= $numofpassenger) {
                    $comid = $trip['companyID'];
                    $busid = $trip['busID'];

                    // echo ;
                    $result12 = $conn->query("SELECT companyName FROM user_partner_admin WHERE id = '$comid'");
                    $compName = array_values($result12->fetch_assoc());
                    $result133 = $conn->query("SELECT seat_type,bus_model FROM buses WHERE id = '$busid'");
                    $compBus = array_values($result133->fetch_assoc());


                    echo '<div class="trps position-relative mt-3 mx-3 px-3 py-2 shadow rounded border-left border-success bg-white" style="border-width: 4px !important;">
                        <div class="row no-gutters">
                            <div class="col pl-2">
                            
                                <span class="" style="font-size: 1.2em; font-weight: 300;  color: #7E7E7E">' . $compName[0] . '</span>
                            
                            </div>
                            <div class="col pr-3 ">
                                <div class="float-right">
                                <i class="fa fa-check-circle mr-1 text-success" aria-hidden="true" style="font-size: .7em ;"></i>
                                <span class="text-success" style="font-size: .8em ;line-height: 2.3em; ">
                                
                                    ' . $remainingSeat . ' remaining seat
                                
                                </span>
                                </div>
                            </div>
                        </div>
                        <span class="text-muted"><small style="font-size: .9em; color: #A5A5A5">Departure time: </small>' . date('h:i A', strtotime($trip['departure_time'])) . '</span><br>
                                    <span class="text-muted">
                                        <small style="font-size: .9em; color: #A5A5A5">Bus details: <br>
                                            <b>' . $compBus[0] . ' seat - ' . $compBus[1] . '
                                            </b>
                                        </small>
                                    </span>
                                    <br><br>
                        

                        <div class="d-flex flex-row">
                            <span class="" style="font-size: 1.1em; font-weight: 300; line-height: 1.7em; color: #949494">Php ' . $trip['fare'] . '</span>
                            <form action="" method="Post">
                                <input type="hidden" class="form-control" name="tripsid" value="' . $trip['id'] . '" readonly>
                                <input type="hidden" class="form-control" name="tripfare" value="' . $trip['fare'] . '" readonly>
                                <input type="hidden" class="form-control" name="busid" value="' . $trip['busID'] . '" readonly>
                                <input type="hidden" class="form-control" name="compid" value="' . $trip['companyID'] . '" readonly>
                                <input type="hidden" class="form-control" name="tripschedid" value="' . $trip['tripID'] . '" readonly>
                                <input type="hidden" class="form-control" name="terminalid" value="' . $trip['terminalID'] . '" readonly>
                                <input type="hidden" class="form-control" name="duration" value="' . $trip['duration'] . '" readonly>
                                <input type="hidden" class="form-control" name="departure_time" value="' . $trip['departure_time'] . '" readonly>
                                <button class="btn stretched-link" type="submit" name="confirmTrip"></button>
                            </form>
                        </div>
                    </div>';
                } else {
                    $comid = $trip['companyID'];
                    $busid = $trip['busID'];

                    // echo ;
                    $result12 = $conn->query("SELECT companyName FROM user_partner_admin WHERE id = '$comid'");
                    $compName = array_values($result12->fetch_assoc());
                    $result133 = $conn->query("SELECT seat_type,bus_model FROM buses WHERE id = '$busid'");
                    $compBus = array_values($result133->fetch_assoc());
                    echo '<div class="bg-light position-relative mt-3 mx-3 px-3 py-2 shadow rounded border-left border-secondary" style="border-width: 4px !important;">
                        <div class="row no-gutters">
                            <div class="col pl-2">
                                <span class="" style="font-size: 1.2em; font-weight: 300;  color: #7E7E7E">
                                ' . $compName[0] . '
                            </span>
                            </div>
                            <div class="col pr-3 ">
                                <div class="float-right">
                                <i class="fa fa-minus-circle mr-1 text-secondary" aria-hidden="true" style="font-size: .7em ;"></i>
                                <span class="text-secondary" style="font-size: .8em ;line-height: 2.3em; ">Full</span>
                                </div>
                            </div>
                        </div>
                            <span class="" style="font-size: .9em; color: #A5A5A5">' . $trip['departure_time'] . '</span><br>
                                    <span class="text-muted">
                                        <small style="font-size: .9em; color: #A5A5A5">Bus details: <br>
                                            <b>' . $compBus[0] . ' seat - ' . $compBus[1] . '
                                            </b>
                                        </small>
                                    </span>
                                    <br><br>
                            <span class="" style="font-size: 1.1em; font-weight: 300; line-height: 1.7em; color: #949494">Php ' . $tripdate['fare'] . '</span>
                        
                        
                        <!-- <div class=""> -->
                            <!-- <a href="confirmTrip.php" class="stretched-link"></a> -->
                        <!-- </div> -->
                    </div>';
                }
            } else {
                if ($trip['taken_seat'] != $trip['total_seat'] && $remainingSeat >= $numofpassenger) {
                    $comid = $trip['companyID'];
                    $busid = $trip['busID'];

                    // echo ;
                    $result12 = $conn->query("SELECT companyName FROM user_partner_admin WHERE id = '$comid'");
                    $compName = array_values($result12->fetch_assoc());
                    $result133 = $conn->query("SELECT seat_type,bus_model FROM buses WHERE id = '$busid'");
                    $compBus = array_values($result133->fetch_assoc());

                    echo '<div class="trps position-relative mt-3 mx-3 px-3 py-2 shadow rounded border-left border-success bg-white" style="border-width: 4px !important;">
                        <div class="row no-gutters">
                            <div class="col pl-2">
                            
                                <span class="" style="font-size: 1.2em; font-weight: 300;  color: #7E7E7E">' . $compName[0] . '</span>
                            
                            </div>
                            <div class="col pr-3 ">
                                <div class="float-right">
                                <i class="fa fa-check-circle mr-1 text-success" aria-hidden="true" style="font-size: .7em ;"></i>
                                <span class="text-success" style="font-size: .8em ;line-height: 2.3em; ">
                                
                                    ' . $remainingSeat . ' remaining seat
                                
                                </span>
                                </div>
                            </div>
                        </div>
                        <span class="text-muted"><small style="font-size: .9em; color: #A5A5A5">Departure time: </small>' . date('h:i A', strtotime($trip['departure_time'])) . '</span><br>
                                    <span class="text-muted">
                                        <small style="font-size: .9em; color: #A5A5A5">Bus details: <br>
                                            <b>' . $compBus[0] . ' seat - ' . $compBus[1] . '
                                            </b>
                                        </small>
                                    </span>
                                    <br><br>

                        <div class="d-flex flex-row">
                            <span class="" style="font-size: 1.1em; font-weight: 300; line-height: 1.7em; color: #949494">Php ' . $trip['fare'] . '</span>
                            <form action="" method="Post">
                                <input type="hidden" class="form-control" name="tripsid" value="' . $trip['id'] . '" readonly>
                                <input type="hidden" class="form-control" name="tripfare" value="' . $trip['fare'] . '" readonly>
                                <input type="hidden" class="form-control" name="busid" value="' . $trip['busID'] . '" readonly>
                                <input type="hidden" class="form-control" name="compid" value="' . $trip['companyID'] . '" readonly>
                                <input type="hidden" class="form-control" name="tripschedid" value="' . $trip['tripID'] . '" readonly>
                                <input type="hidden" class="form-control" name="terminalid" value="' . $trip['terminalID'] . '" readonly>
                                <input type="hidden" class="form-control" name="duration" value="' . $trip['duration'] . '" readonly>
                                <input type="hidden" class="form-control" name="departure_time" value="' . $trip['departure_time'] . '" readonly>
                                <button class="btn stretched-link" type="submit" name="confirmTrip"></button>
                            </form>
                        </div>
                    </div>';
                } else {
                    $comid = $trip['companyID'];
                    $busid = $trip['busID'];

                    // echo ;
                    $result12 = $conn->query("SELECT companyName FROM user_partner_admin WHERE id = '$comid'");
                    $compName = array_values($result12->fetch_assoc());
                    $result133 = $conn->query("SELECT seat_type,bus_model FROM buses WHERE id = '$busid'");
                    $compBus = array_values($result133->fetch_assoc());
                    echo '<div class="bg-light position-relative mt-3 mx-3 px-3 py-2 shadow rounded border-left border-secondary" style="border-width: 4px !important;">
                        <div class="row no-gutters">
                            <div class="col pl-2">
                                <span class="" style="font-size: 1.2em; font-weight: 300;  color: #7E7E7E">
                                ' . $compName[0] . '
                            </span>
                            </div>
                            <div class="col pr-3 ">
                                <div class="float-right">
                                <i class="fa fa-minus-circle mr-1 text-secondary" aria-hidden="true" style="font-size: .7em ;"></i>
                                <span class="text-secondary" style="font-size: .8em ;line-height: 2.3em; ">Full</span>
                                </div>
                            </div>
                        </div>
                            <span class="" style="font-size: .9em; color: #A5A5A5">' . $trip['departure_time'] . '</span><br>
                                    <span class="text-muted">
                                        <small style="font-size: .9em; color: #A5A5A5">Bus details: <br>
                                            <b>' . $compBus[0] . ' seat - ' . $compBus[1] . '
                                            </b>
                                        </small>
                                    </span>
                                    <br><br>
                            <span class="" style="font-size: 1.1em; font-weight: 300; line-height: 1.7em; color: #949494">Php ' . $tripdate['fare'] . '</span>
                        
                        
                        <!-- <div class=""> -->
                            <!-- <a href="confirmTrip.php" class="stretched-link"></a> -->
                        <!-- </div> -->
                    </div>';
                }
            }
        }
    } else {
        echo '<div class="no-gutters position-relative mt-3 mx-3 shadow rounded" style="background-color:#F44E4E;">
            <div class="text-center text-light p-3">
                No Data Found
            </div>
        </div>';
    }
} else if (isset($_POST['companyid']) && $_POST['companyid'] == '' && isset($_POST['busid']) && $_POST['busid'] != '') {
    $bsID = $_POST['busid'];
    $result1 = $conn->query("SELECT COUNT(id) FROM trip WHERE origin = '$sorigin' AND destination = '$sdest'");
    $arrtrips = array_values($result1->fetch_assoc());

    $result222 = $conn->query("SELECT a.origin, a.destination, a.duration, a.departure_time, a.routeID,b.id,b.companyID, b.trip_date, b.fare, b.total_seat, b.taken_seat, b.tripID, b.busID, b.terminalID FROM trip a RIGHT OUTER JOIN bus_trip b ON(a.id = b.tripID) WHERE a.origin = '$sorigin' AND a.destination = '$sdest' AND b.trip_date = '$sdate' AND b.driverID IS NOT NULL AND b.conductorID IS NOT NULL AND b.busID IS NOT NULL AND b.busID = '$bsID' ORDER BY a.departure_time DESC ");

    // $result222 = $conn->query("SELECT a.origin, a.destination, a.departure_time, a.routeID,b.id,b.companyID, b.trip_date, b.fare, b.total_seat, b.taken_seat, b.tripID, b.busID, b.terminalID FROM trip a RIGHT OUTER JOIN bus_trip b ON(a.id = b.tripID) WHERE  a.origin = '$sorigin' AND a.destination = '$sdest' AND b.trip_date = '$sdate' AND b.busID = '$bsID'");
    $tripss = $result222->fetch_all(MYSQLI_ASSOC);

    if (!empty($tripss)) {

        foreach ($tripss as $trip) {
            $trpid = $trip['id'];
            $trptime = $trip['departure_time'];
            $remainingSeat = $trip['total_seat'] - $trip['taken_seat'];
            $datetime = new DateTime();
            $timezone = new DateTimeZone('Asia/Manila');
            $datetime->setTimezone($timezone);
            if (date('Y/m/d', strtotime($trip['trip_date'])) == $datetime->format('Y/m/d') && date('h:i A', strtotime($trip['departure_time'])) > $datetime->format('h:i A')) {
                if ($trip['taken_seat'] != $trip['total_seat'] && $remainingSeat >= $numofpassenger) {
                    $comid = $trip['companyID'];
                    $busid = $trip['busID'];

                    // echo ;
                    $result12 = $conn->query("SELECT companyName FROM user_partner_admin WHERE id = '$comid'");
                    $compName = array_values($result12->fetch_assoc());
                    $result133 = $conn->query("SELECT seat_type,bus_model FROM buses WHERE id = '$busid'");
                    $compBus = array_values($result133->fetch_assoc());

                    echo '<div class="trps position-relative mt-3 mx-3 px-3 py-2 shadow rounded border-left border-success bg-white" style="border-width: 4px !important;">
                        <div class="row no-gutters">
                            <div class="col pl-2">
                            
                                <span class="" style="font-size: 1.2em; font-weight: 300;  color: #7E7E7E">' . $compName[0] . '</span>
                            
                            </div>
                            <div class="col pr-3 ">
                                <div class="float-right">
                                <i class="fa fa-check-circle mr-1 text-success" aria-hidden="true" style="font-size: .7em ;"></i>
                                <span class="text-success" style="font-size: .8em ;line-height: 2.3em; ">
                                
                                    ' . $remainingSeat . ' remaining seat
                                
                                </span>
                                </div>
                            </div>
                        </div>
                        <span class="text-muted"><small style="font-size: .9em; color: #A5A5A5">Departure time: </small>' . date('h:i A', strtotime($trip['departure_time'])) . '</span><br>
                                    <span class="text-muted">
                                        <small style="font-size: .9em; color: #A5A5A5">Bus details: <br>
                                            <b>' . $compBus[0] . ' seat - ' . $compBus[1] . '
                                            </b>
                                        </small>
                                    </span>
                                    <br><br>

                        <div class="d-flex flex-row">
                            <span class="" style="font-size: 1.1em; font-weight: 300; line-height: 1.7em; color: #949494">Php ' . $trip['fare'] . '</span>
                            <form action="" method="Post">
                                <input type="hidden" class="form-control" name="tripsid" value="' . $trip['id'] . '" readonly>
                                <input type="hidden" class="form-control" name="tripfare" value="' . $trip['fare'] . '" readonly>
                                <input type="hidden" class="form-control" name="busid" value="' . $trip['busID'] . '" readonly>
                                <input type="hidden" class="form-control" name="compid" value="' . $trip['companyID'] . '" readonly>
                                <input type="hidden" class="form-control" name="tripschedid" value="' . $trip['tripID'] . '" readonly>
                                <input type="hidden" class="form-control" name="terminalid" value="' . $trip['terminalID'] . '" readonly>
                                <input type="hidden" class="form-control" name="duration" value="' . $trip['duration'] . '" readonly>
                                <input type="hidden" class="form-control" name="departure_time" value="' . $trip['departure_time'] . '" readonly>
                                <button class="btn stretched-link" type="submit" name="confirmTrip"></button>
                            </form>
                        </div>
                    </div>';
                } else {
                    $comid = $trip['companyID'];
                    $busid = $trip['busID'];

                    // echo ;
                    $result12 = $conn->query("SELECT companyName FROM user_partner_admin WHERE id = '$comid'");
                    $compName = array_values($result12->fetch_assoc());
                    $result133 = $conn->query("SELECT seat_type,bus_model FROM buses WHERE id = '$busid'");
                    $compBus = array_values($result133->fetch_assoc());
                    echo '<div class="bg-light position-relative mt-3 mx-3 px-3 py-2 shadow rounded border-left border-secondary" style="border-width: 4px !important;">
                        <div class="row no-gutters">
                            <div class="col pl-2">
                                <span class="" style="font-size: 1.2em; font-weight: 300;  color: #7E7E7E">
                                ' . $compName[0] . '
                            </span>
                            </div>
                            <div class="col pr-3 ">
                                <div class="float-right">
                                <i class="fa fa-minus-circle mr-1 text-secondary" aria-hidden="true" style="font-size: .7em ;"></i>
                                <span class="text-secondary" style="font-size: .8em ;line-height: 2.3em; ">Full</span>
                                </div>
                            </div>
                        </div>
                            <span class="" style="font-size: .9em; color: #A5A5A5">' . $trip['departure_time'] . '</span><br>
                                    <span class="text-muted">
                                        <small style="font-size: .9em; color: #A5A5A5">Bus details: <br>
                                            <b>' . $compBus[0] . ' seat - ' . $compBus[1] . '
                                            </b>
                                        </small>
                                    </span>
                                    <br><br>
                            <span class="" style="font-size: 1.1em; font-weight: 300; line-height: 1.7em; color: #949494">Php ' . $tripdate['fare'] . '</span>
                        
                        
                        <!-- <div class=""> -->
                            <!-- <a href="confirmTrip.php" class="stretched-link"></a> -->
                        <!-- </div> -->
                    </div>';
                }
            } else {
                if ($trip['taken_seat'] != $trip['total_seat'] && $remainingSeat >= $numofpassenger) {
                    $comid = $trip['companyID'];
                    $busid = $trip['busID'];

                    // echo ;
                    $result12 = $conn->query("SELECT companyName FROM user_partner_admin WHERE id = '$comid'");
                    $compName = array_values($result12->fetch_assoc());
                    $result133 = $conn->query("SELECT seat_type,bus_model FROM buses WHERE id = '$busid'");
                    $compBus = array_values($result133->fetch_assoc());

                    echo '<div class="trps position-relative mt-3 mx-3 px-3 py-2 shadow rounded border-left border-success bg-white" style="border-width: 4px !important;">
                        <div class="row no-gutters">
                            <div class="col pl-2">
                            
                                <span class="" style="font-size: 1.2em; font-weight: 300;  color: #7E7E7E">' . $compName[0] . '</span>
                            
                            </div>
                            <div class="col pr-3 ">
                                <div class="float-right">
                                <i class="fa fa-check-circle mr-1 text-success" aria-hidden="true" style="font-size: .7em ;"></i>
                                <span class="text-success" style="font-size: .8em ;line-height: 2.3em; ">
                                
                                    ' . $remainingSeat . ' remaining seat
                                
                                </span>
                                </div>
                            </div>
                        </div>
                        <span class="text-muted"><small style="font-size: .9em; color: #A5A5A5">Departure time: </small>' . date('h:i A', strtotime($trip['departure_time'])) . '</span><br>
                                    <span class="text-muted">
                                        <small style="font-size: .9em; color: #A5A5A5">Bus details: <br>
                                            <b>' . $compBus[0] . ' seat - ' . $compBus[1] . '
                                            </b>
                                        </small>
                                    </span>
                                    <br><br>
                        

                        <div class="d-flex flex-row">
                            <span class="" style="font-size: 1.1em; font-weight: 300; line-height: 1.7em; color: #949494">Php ' . $trip['fare'] . '</span>
                            <form action="" method="Post">
                                <input type="hidden" class="form-control" name="tripsid" value="' . $trip['id'] . '" readonly>
                                <input type="hidden" class="form-control" name="tripfare" value="' . $trip['fare'] . '" readonly>
                                <input type="hidden" class="form-control" name="busid" value="' . $trip['busID'] . '" readonly>
                                <input type="hidden" class="form-control" name="compid" value="' . $trip['companyID'] . '" readonly>
                                <input type="hidden" class="form-control" name="tripschedid" value="' . $trip['tripID'] . '" readonly>
                                <input type="hidden" class="form-control" name="terminalid" value="' . $trip['terminalID'] . '" readonly>
                                <input type="hidden" class="form-control" name="duration" value="' . $trip['duration'] . '" readonly>
                                <input type="hidden" class="form-control" name="departure_time" value="' . $trip['departure_time'] . '" readonly>
                                <button class="btn stretched-link" type="submit" name="confirmTrip"></button>
                            </form>
                        </div>
                    </div>';
                } else {
                    $comid = $trip['companyID'];
                    $busid = $trip['busID'];

                    // echo ;
                    $result12 = $conn->query("SELECT companyName FROM user_partner_admin WHERE id = '$comid'");
                    $compName = array_values($result12->fetch_assoc());
                    $result133 = $conn->query("SELECT seat_type,bus_model FROM buses WHERE id = '$busid'");
                    $compBus = array_values($result133->fetch_assoc());
                    echo '<div class="bg-light position-relative mt-3 mx-3 px-3 py-2 shadow rounded border-left border-secondary" style="border-width: 4px !important;">
                        <div class="row no-gutters">
                            <div class="col pl-2">
                                <span class="" style="font-size: 1.2em; font-weight: 300;  color: #7E7E7E">
                                ' . $compName[0] . '
                            </span>
                            </div>
                            <div class="col pr-3 ">
                                <div class="float-right">
                                <i class="fa fa-minus-circle mr-1 text-secondary" aria-hidden="true" style="font-size: .7em ;"></i>
                                <span class="text-secondary" style="font-size: .8em ;line-height: 2.3em; ">Full</span>
                                </div>
                            </div>
                        </div>
                            <span class="" style="font-size: .9em; color: #A5A5A5">' . $trip['departure_time'] . '</span><br>
                                    <span class="text-muted">
                                        <small style="font-size: .9em; color: #A5A5A5">Bus details: <br>
                                            <b>' . $compBus[0] . ' seat - ' . $compBus[1] . '
                                            </b>
                                        </small>
                                    </span>
                                    <br><br>
                            <span class="" style="font-size: 1.1em; font-weight: 300; line-height: 1.7em; color: #949494">Php ' . $tripdate['fare'] . '</span>
                        
                        
                        <!-- <div class=""> -->
                            <!-- <a href="confirmTrip.php" class="stretched-link"></a> -->
                        <!-- </div> -->
                    </div>';
                }
            }
        }
    } else {
        echo '<div class="no-gutters position-relative mt-3 mx-3 shadow rounded" style="background-color:#F44E4E;">
            <div class="text-center text-light p-3">
                No Data Found
            </div>
        </div>';
    }
} else if (isset($_POST['companyid']) && $_POST['companyid'] == '' && isset($_POST['busid']) && $_POST['busid'] == '') {
    $result1 = $conn->query("SELECT COUNT(id) FROM trip WHERE origin = '$sorigin' AND destination = '$sdest'");
    $arrtrips = array_values($result1->fetch_assoc());


    $result222 = $conn->query("SELECT a.origin, a.destination, a.duration, a.departure_time, a.routeID,b.id,b.companyID, b.trip_date, b.fare, b.total_seat, b.taken_seat, b.tripID, b.busID, b.terminalID FROM trip a RIGHT OUTER JOIN bus_trip b ON(a.id = b.tripID) WHERE a.origin = '$sorigin' AND a.destination = '$sdest' AND b.trip_date = '$sdate' AND b.driverID IS NOT NULL AND b.conductorID IS NOT NULL AND b.busID IS NOT NULL ORDER BY a.departure_time DESC ");
    // $result222 = $conn->query("SELECT a.origin, a.destination, a.departure_time, a.routeID,b.id,b.companyID, b.trip_date, b.fare, b.total_seat, b.taken_seat, b.tripID, b.busID, b.terminalID FROM trip a RIGHT OUTER JOIN bus_trip b ON(a.id = b.tripID) WHERE  a.origin = '$sorigin' AND a.destination = '$sdest' AND b.trip_date = '$sdate'");
    $tripss = $result222->fetch_all(MYSQLI_ASSOC);

    if (!empty($tripss)) {

        foreach ($tripss as $trip) {
            $trpid = $trip['id'];
            $trptime = $trip['departure_time'];
            $remainingSeat = $trip['total_seat'] - $trip['taken_seat'];
            $datetime = new DateTime();
            $datetime = new DateTime();
            $timezone = new DateTimeZone('Asia/Manila');
            $datetime->setTimezone($timezone);
            if (date('Y/m/d', strtotime($trip['trip_date'])) == $datetime->format('Y/m/d') && date('h:i A', strtotime($trip['departure_time'])) > $datetime->format('h:i A')) {
                if ($trip['taken_seat'] != $trip['total_seat'] && $remainingSeat >= $numofpassenger) {
                    $comid = $trip['companyID'];
                    $busid = $trip['busID'];

                    // echo ;
                    $result12 = $conn->query("SELECT companyName FROM user_partner_admin WHERE id = '$comid'");
                    $compName = array_values($result12->fetch_assoc());
                    $result133 = $conn->query("SELECT seat_type,bus_model FROM buses WHERE id = '$busid'");
                    $compBus = array_values($result133->fetch_assoc());

                    echo '<div class="trps position-relative mt-3 mx-3 px-3 py-2 shadow rounded border-left border-success bg-white" style="border-width: 4px !important;">
                        <div class="row no-gutters">
                            <div class="col pl-2">
                            
                                <span class="" style="font-size: 1.2em; font-weight: 300;  color: #7E7E7E">' . $compName[0] . '</span>
                            
                            </div>
                            <div class="col pr-3 ">
                                <div class="float-right">
                                <i class="fa fa-check-circle mr-1 text-success" aria-hidden="true" style="font-size: .7em ;"></i>
                                <span class="text-success" style="font-size: .8em ;line-height: 2.3em; ">
                                
                                    ' . $remainingSeat . ' remaining seat
                                
                                </span>
                                </div>
                            </div>
                        </div>
                        <span class="text-muted"><small style="font-size: .9em; color: #A5A5A5">Departure time: </small>' . date('h:i A', strtotime($trip['departure_time'])) . '</span><br>
                                    <span class="text-muted">
                                        <small style="font-size: .9em; color: #A5A5A5">Bus details: <br>
                                            <b>' . $compBus[0] . ' seat - ' . $compBus[1] . '
                                            </b>
                                        </small>
                                    </span>
                                    <br><br>
                        

                        <div class="d-flex flex-row">
                            <span class="" style="font-size: 1.1em; font-weight: 300; line-height: 1.7em; color: #949494">Php ' . $trip['fare'] . '</span>
                            <form action="" method="Post">
                                <input type="hidden" class="form-control" name="tripsid" value="' . $trip['id'] . '" readonly>
                                <input type="hidden" class="form-control" name="tripfare" value="' . $trip['fare'] . '" readonly>
                                <input type="hidden" class="form-control" name="busid" value="' . $trip['busID'] . '" readonly>
                                <input type="hidden" class="form-control" name="compid" value="' . $trip['companyID'] . '" readonly>
                                <input type="hidden" class="form-control" name="tripschedid" value="' . $trip['tripID'] . '" readonly>
                                <input type="hidden" class="form-control" name="terminalid" value="' . $trip['terminalID'] . '" readonly>
                                <input type="hidden" class="form-control" name="duration" value="' . $trip['duration'] . '" readonly>
                                <input type="hidden" class="form-control" name="departure_time" value="' . $trip['departure_time'] . '" readonly>
                                <button class="btn stretched-link" type="submit" name="confirmTrip"></button>
                            </form>
                        </div>
                    </div>';
                } else {
                    $comid = $trip['companyID'];
                    $busid = $trip['busID'];

                    // echo ;
                    $result12 = $conn->query("SELECT companyName FROM user_partner_admin WHERE id = '$comid'");
                    $compName = array_values($result12->fetch_assoc());
                    $result133 = $conn->query("SELECT seat_type,bus_model FROM buses WHERE id = '$busid'");
                    $compBus = array_values($result133->fetch_assoc());
                    echo '<div class="bg-light position-relative mt-3 mx-3 px-3 py-2 shadow rounded border-left border-secondary" style="border-width: 4px !important;">
                        <div class="row no-gutters">
                            <div class="col pl-2">
                                <span class="" style="font-size: 1.2em; font-weight: 300;  color: #7E7E7E">
                                ' . $compName[0] . '
                            </span>
                            </div>
                            <div class="col pr-3 ">
                                <div class="float-right">
                                <i class="fa fa-minus-circle mr-1 text-secondary" aria-hidden="true" style="font-size: .7em ;"></i>
                                <span class="text-secondary" style="font-size: .8em ;line-height: 2.3em; ">Full</span>
                                </div>
                            </div>
                        </div>
                            <span class="" style="font-size: .9em; color: #A5A5A5">' . $trip['departure_time'] . '</span><br>
                                    <span class="text-muted">
                                        <small style="font-size: .9em; color: #A5A5A5">Bus details: <br>
                                            <b>' . $compBus[0] . ' seat - ' . $compBus[1] . '
                                            </b>
                                        </small>
                                    </span>
                                    <br><br>
                            <span class="" style="font-size: 1.1em; font-weight: 300; line-height: 1.7em; color: #949494">Php ' . $tripdate['fare'] . '</span>
                        
                        
                        <!-- <div class=""> -->
                            <!-- <a href="confirmTrip.php" class="stretched-link"></a> -->
                        <!-- </div> -->
                    </div>';
                }
            } else {
                if ($trip['taken_seat'] != $trip['total_seat'] && $remainingSeat >= $numofpassenger) {
                    $comid = $trip['companyID'];
                    $busid = $trip['busID'];

                    // echo ;
                    $result12 = $conn->query("SELECT companyName FROM user_partner_admin WHERE id = '$comid'");
                    $compName = array_values($result12->fetch_assoc());
                    $result133 = $conn->query("SELECT seat_type,bus_model FROM buses WHERE id = '$busid'");
                    $compBus = array_values($result133->fetch_assoc());

                    echo '<div class="trps position-relative mt-3 mx-3 px-3 py-2 shadow rounded border-left border-success bg-white" style="border-width: 4px !important;">
                        <div class="row no-gutters">
                            <div class="col pl-2">
                            
                                <span class="" style="font-size: 1.2em; font-weight: 300;  color: #7E7E7E">' . $compName[0] . '</span>
                            
                            </div>
                            <div class="col pr-3 ">
                                <div class="float-right">
                                <i class="fa fa-check-circle mr-1 text-success" aria-hidden="true" style="font-size: .7em ;"></i>
                                <span class="text-success" style="font-size: .8em ;line-height: 2.3em; ">
                                
                                    ' . $remainingSeat . ' remaining seat
                                
                                </span>
                                </div>
                            </div>
                        </div>
                        <span class="text-muted"><small style="font-size: .9em; color: #A5A5A5">Departure time: </small>' . date('h:i A', strtotime($trip['departure_time'])) . '</span><br>
                                    <span class="text-muted">
                                        <small style="font-size: .9em; color: #A5A5A5">Bus details: <br>
                                            <b>' . $compBus[0] . ' seat - ' . $compBus[1] . '
                                            </b>
                                        </small>
                                    </span>
                                    <br><br>
                        

                        <div class="d-flex flex-row">
                            <span class="" style="font-size: 1.1em; font-weight: 300; line-height: 1.7em; color: #949494">Php ' . $trip['fare'] . '</span>
                            <form action="" method="Post">
                                <input type="hidden" class="form-control" name="tripsid" value="' . $trip['id'] . '" readonly>
                                <input type="hidden" class="form-control" name="tripfare" value="' . $trip['fare'] . '" readonly>
                                <input type="hidden" class="form-control" name="busid" value="' . $trip['busID'] . '" readonly>
                                <input type="hidden" class="form-control" name="compid" value="' . $trip['companyID'] . '" readonly>
                                <input type="hidden" class="form-control" name="tripschedid" value="' . $trip['tripID'] . '" readonly>
                                <input type="hidden" class="form-control" name="terminalid" value="' . $trip['terminalID'] . '" readonly>
                                <input type="hidden" class="form-control" name="duration" value="' . $trip['duration'] . '" readonly>
                                <input type="hidden" class="form-control" name="departure_time" value="' . $trip['departure_time'] . '" readonly>
                                <button class="btn stretched-link" type="submit" name="confirmTrip"></button>
                            </form>
                        </div>
                    </div>';
                } else {
                    $comid = $trip['companyID'];
                    $busid = $trip['busID'];

                    // echo ;
                    $result12 = $conn->query("SELECT companyName FROM user_partner_admin WHERE id = '$comid'");
                    $compName = array_values($result12->fetch_assoc());
                    $result133 = $conn->query("SELECT seat_type,bus_model FROM buses WHERE id = '$busid'");
                    $compBus = array_values($result133->fetch_assoc());
                    echo '<div class="bg-light position-relative mt-3 mx-3 px-3 py-2 shadow rounded border-left border-secondary" style="border-width: 4px !important;">
                        <div class="row no-gutters">
                            <div class="col pl-2">
                                <span class="" style="font-size: 1.2em; font-weight: 300;  color: #7E7E7E">
                                ' . $compName[0] . '
                            </span>
                            </div>
                            <div class="col pr-3 ">
                                <div class="float-right">
                                <i class="fa fa-minus-circle mr-1 text-secondary" aria-hidden="true" style="font-size: .7em ;"></i>
                                <span class="text-secondary" style="font-size: .8em ;line-height: 2.3em; ">Full</span>
                                </div>
                            </div>
                        </div>
                            <span class="" style="font-size: .9em; color: #A5A5A5">' . $trip['departure_time'] . '</span><br>
                                    <span class="text-muted">
                                        <small style="font-size: .9em; color: #A5A5A5">Bus details: <br>
                                            <b>' . $compBus[0] . ' seat - ' . $compBus[1] . '
                                            </b>
                                        </small>
                                    </span>
                                    <br><br>
                            <span class="" style="font-size: 1.1em; font-weight: 300; line-height: 1.7em; color: #949494">Php ' . $tripdate['fare'] . '</span>
                        
                        
                        <!-- <div class=""> -->
                            <!-- <a href="confirmTrip.php" class="stretched-link"></a> -->
                        <!-- </div> -->
                    </div>';
                }
            }
        }
    } else {
        echo '<div class="no-gutters position-relative mt-3 mx-3 shadow rounded" style="background-color:#F44E4E;">
            <div class="text-center text-light p-3">
                No Data Found
            </div>
        </div>';
    }
}
