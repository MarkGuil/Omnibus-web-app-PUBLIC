<h5>Cancelled trips</h5>
Cancellation Request/s<br>
<?php
$counter = 0;

if (isset($_SESSION['demo_booking_table'])) {
    for ($a = 0; $a < count($_SESSION['demo_booking_table']); $a++) {
        $result223 = $conn->query(" SELECT `trip_date`,`tripID`,`busID` FROM `bus_trip` WHERE `id` = '" . $_SESSION['demo_booking_table'][$a]['bustripID'] . "' ");
        $bookings = array_values($result223->fetch_assoc());
        if ($_SESSION['demo_booking_table'][$a]["booking_status"] == 'requested for cancellation') {
            $counter++;
?>
            <div class="container container-small bg-light shadow rounded py-2 px-2 my-3">
                <?php
                $count11 = $conn->query("SELECT companyName,email FROM user_partner_admin WHERE id = '" . $_SESSION['demo_booking_table'][$a]['companyID'] . "' limit 1") or die($mysql->connect_error);
                $companyName = array_values($count11->fetch_assoc());
                $count12 = $conn->query("SELECT terminal_name FROM terminal WHERE id = '" . $_SESSION['demo_booking_table'][$a]['terminalID'] . "' limit 1") or die($mysql->connect_error);
                $terminalName = array_values($count12->fetch_assoc());
                $count13 = $conn->query("SELECT seat_type,bus_model,busNumber FROM buses WHERE id = '" . $bookings[2] . "' limit 1") or die($mysql->connect_error);
                $busDetails = array_values($count13->fetch_assoc());


                $pieces = explode(":", $_SESSION['demo_booking_table'][$a]['duration']);
                $add = '+' . $pieces[0] . ' hour +' . $pieces[1] . ' minutes';
                // $add = $pieces[0].' hour +'.$pieces[1].' minutes';
                ?>
                <div class="text-center">
                    <small class="text-muted">Reference no: </small>
                    <h5><?php echo $_SESSION['demo_booking_table'][$a]['reference_id'] ?> </h5>
                </div>
                <div class="row">
                    <div class="col">
                        <b style="color: #3b55d9;"><span id="companyname<?php echo $a ?>"><?php echo $companyName[0] ?></span><?php echo ' (' . $terminalName[0] . ')' ?></b>
                    </div>
                    <span id="companyemail<?php echo $a ?>" style="display:none;"><?php echo $companyName[1] ?></span>
                    <span id="reference<?php echo $a ?>" style="display:none;"><?php echo $_SESSION['demo_booking_table'][$a]['reference_id'] ?></span>
                    <span id="tripID<?php echo $a ?>" style="display:none;"><?php echo $_SESSION['demo_booking_table'][$a]['bustripID'] ?></span>
                </div>
                <div class="row">
                    <div class="col">
                        <?php echo $_SESSION['demo_booking_table'][$a]['origin'] . ' - ' . $_SESSION['demo_booking_table'][$a]['destination'] ?>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <small class="text-muted">Departs</small><br>
                        <small class="font-weight-bold"><?php echo date('h:i A', strtotime($_SESSION['demo_booking_table'][$a]['departure_time'])) ?></small>
                    </div>
                    <div class="col">
                        <small class="text-muted">Arrives</small><br>
                        <small class="font-weight-bold"><?php echo date('h:i A', strtotime($add, strtotime($_SESSION['demo_booking_table'][$a]["departure_time"]))); ?></small>
                    </div>
                    <div class="col">
                        <small class="text-muted">Duration</small><br>
                        <small class="font-weight-bold"><?php echo $pieces[0] . 'h :' . $pieces[1] . 'm' ?></small>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <small class="text-muted">Bus no.</small><br>
                        <small class="font-weight-bold"><?php echo $busDetails[2] ?></small>
                    </div>
                    <div class="col">
                        <small class="text-muted">Bus model</small><br>
                        <small class="font-weight-bold"><?php echo $busDetails[1] ?></small>
                    </div>
                    <div class="col">
                        <small class="text-muted">Passengers</small><br>
                        <small class="font-weight-bold"><?php echo $_SESSION['demo_booking_table'][$a]['number_of_seats'] ?></small>
                    </div>
                </div>
                <hr>
                <div class="">
                    <small class="text-muted">Departs on</small><br>
                    <?php
                    $bookdate = new DateTime($bookings[0]);
                    $dt_min = new DateTime("last saturday");
                    $dt_min->modify('+1 day');
                    $dt_max = clone ($dt_min);
                    for ($x = 0; $x <= 6; $x++) {
                        if ($bookdate->format('D') == $dt_max->format('D')) {
                            echo '<small class="text-primary font-weight-bold mr-2" style="font-size:10px">' . $dt_max->format('l') . '</small>';
                        } else {
                            echo '<small class="font-weight-bold mr-2" style="font-size:10px">' . $dt_max->format('l') . '</small>';
                        }
                        $dt_max->modify('+1 days');
                    }
                    ?>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <small class="text-muted">Travel date</small><br>
                        <small class="font-weight-bold"><?php echo $bookings[0] ?></small>
                    </div>
                    <div class="col">
                        <small class="text-muted">Amount</small><br>
                        <small class="font-weight-bold"><?php echo 'Php' . $_SESSION['demo_booking_table'][$a]['total_amount'] ?></small>
                    </div>
                    <div class="col">
                        <small class="text-muted">Booking status</small><br>

                        <?php
                        if ($_SESSION['demo_booking_table'][$a]['payment_status'] == 'not paid') {
                            echo '<small class="font-weight-bold text-danger">' . $_SESSION['demo_booking_table'][$a]['payment_status'] . '</small>';
                        } else {
                            echo '<small class="font-weight-bold text-success">' . $_SESSION['demo_booking_table'][$a]['payment_status'] . '</small>';
                        }
                        ?>
                    </div>
                </div>
                <div class="mt-3 mb-2 d-flex justify-content-end">
                    <?php if ($_SESSION['demo_booking_table'][$a]['booking_status'] == 'requested for cancellation') { ?>
                        <button class="btn btn-outline-danger py-0 rounded-pill mr-2" name="viewbooking" value="submit" disabled>Request Sent</button>
                    <?php } else if ($_SESSION['demo_booking_table'][$a]['booking_status'] == 'cancelled') { ?>
                        <button class="btn btn-outline-danger py-0 rounded-pill mr-2" name="viewbooking" value="submit" disabled>Cancelled</button>
                    <?php } else { ?>
                        <a href="registeredBookings.php#modalcancelbooking" class="btn btn-outline-danger py-0 rounded-pill mr-2" data-bs-toggle="modal" data-userid="<?php echo $a ?>">Cancel</a>
                    <?php } ?>

                    <a href="viewBookedTickets.php?bid=<?php echo $a ?>" class="btn btn-outline-primary py-0 rounded-pill mr-2">View</a>
                </div>
            </div>
<?php  }
    }
} ?>
<?php if ($counter == 0) { ?>
    <div class="container container-small bg-secondary shadow rounded py-2 px-3 my-2 text-center">
        No Data Found
    </div>
<?php } ?>
<br>Cancelled trips<br>
<?php
$counter = 0;
if (isset($_SESSION['demo_booking_table'])) {
    for ($a = 0; $a < count($_SESSION['demo_booking_table']); $a++) {
        $result223 = $conn->query(" SELECT `trip_date`,`tripID`,`busID` FROM `bus_trip` WHERE `id` = '" . $_SESSION['demo_booking_table'][$a]['bustripID'] . "' ");
        $bookings = array_values($result223->fetch_assoc());
        if ($_SESSION['demo_booking_table'][$a]["booking_status"] == 'cancelled') {
            $counter++;
?>
            <div class="container container-small bg-light shadow rounded py-2 px-2 my-3">
                <?php
                $count11 = $conn->query("SELECT companyName,email FROM user_partner_admin WHERE id = '" . $_SESSION['demo_booking_table'][$a]['companyID'] . "' limit 1") or die($mysql->connect_error);
                $companyName = array_values($count11->fetch_assoc());
                $count12 = $conn->query("SELECT terminal_name FROM terminal WHERE id = '" . $_SESSION['demo_booking_table'][$a]['terminalID'] . "' limit 1") or die($mysql->connect_error);
                $terminalName = array_values($count12->fetch_assoc());
                $count13 = $conn->query("SELECT seat_type,bus_model,busNumber FROM buses WHERE id = '" . $bookings[2] . "' limit 1") or die($mysql->connect_error);
                $busDetails = array_values($count13->fetch_assoc());


                $pieces = explode(":", $_SESSION['demo_booking_table'][$a]['duration']);
                $add = '+' . $pieces[0] . ' hour +' . $pieces[1] . ' minutes';
                // $add = $pieces[0].' hour +'.$pieces[1].' minutes';
                ?>
                <div class="text-center">
                    <small class="text-muted">Reference no: </small>
                    <h5><?php echo $_SESSION['demo_booking_table'][$a]['reference_id'] ?> </h5>
                </div>
                <div class="row">
                    <div class="col">
                        <b style="color: #3b55d9;"><span id="companyname<?php echo $a ?>"><?php echo $companyName[0] ?></span><?php echo ' (' . $terminalName[0] . ')' ?></b>
                    </div>
                    <span id="companyemail<?php echo $a ?>" style="display:none;"><?php echo $companyName[1] ?></span>
                    <span id="reference<?php echo $a ?>" style="display:none;"><?php echo $_SESSION['demo_booking_table'][$a]['reference_id'] ?></span>
                    <span id="tripID<?php echo $a ?>" style="display:none;"><?php echo $_SESSION['demo_booking_table'][$a]['bustripID'] ?></span>
                </div>
                <div class="row">
                    <div class="col">
                        <?php echo $_SESSION['demo_booking_table'][$a]['origin'] . ' - ' . $_SESSION['demo_booking_table'][$a]['destination'] ?>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <small class="text-muted">Departs</small><br>
                        <small class="font-weight-bold"><?php echo date('h:i A', strtotime($_SESSION['demo_booking_table'][$a]['departure_time'])) ?></small>
                    </div>
                    <div class="col">
                        <small class="text-muted">Arrives</small><br>
                        <small class="font-weight-bold"><?php echo date('h:i A', strtotime($add, strtotime($_SESSION['demo_booking_table'][$a]["departure_time"]))); ?></small>
                    </div>
                    <div class="col">
                        <small class="text-muted">Duration</small><br>
                        <small class="font-weight-bold"><?php echo $pieces[0] . 'h :' . $pieces[1] . 'm' ?></small>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <small class="text-muted">Bus no.</small><br>
                        <small class="font-weight-bold"><?php echo $busDetails[2] ?></small>
                    </div>
                    <div class="col">
                        <small class="text-muted">Bus model</small><br>
                        <small class="font-weight-bold"><?php echo $busDetails[1] ?></small>
                    </div>
                    <div class="col">
                        <small class="text-muted">Passengers</small><br>
                        <small class="font-weight-bold"><?php echo $_SESSION['demo_booking_table'][$a]['number_of_seats'] ?></small>
                    </div>
                </div>
                <hr>
                <div class="">
                    <small class="text-muted">Departs on</small><br>
                    <?php
                    $bookdate = new DateTime($bookings[0]);
                    $dt_min = new DateTime("last saturday");
                    $dt_min->modify('+1 day');
                    $dt_max = clone ($dt_min);
                    for ($x = 0; $x <= 6; $x++) {
                        if ($bookdate->format('D') == $dt_max->format('D')) {
                            echo '<small class="text-primary font-weight-bold mr-2" style="font-size:10px">' . $dt_max->format('l') . '</small>';
                        } else {
                            echo '<small class="font-weight-bold mr-2" style="font-size:10px">' . $dt_max->format('l') . '</small>';
                        }
                        $dt_max->modify('+1 days');
                    }
                    ?>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <small class="text-muted">Travel date</small><br>
                        <small class="font-weight-bold"><?php echo $bookings[0] ?></small>
                    </div>
                    <div class="col">
                        <small class="text-muted">Amount</small><br>
                        <small class="font-weight-bold"><?php echo 'Php' . $_SESSION['demo_booking_table'][$a]['total_amount'] ?></small>
                    </div>
                    <div class="col">
                        <small class="text-muted">Booking status</small><br>

                        <?php
                        if ($_SESSION['demo_booking_table'][$a]['payment_status'] == 'not paid') {
                            echo '<small class="font-weight-bold text-danger">' . $_SESSION['demo_booking_table'][$a]['payment_status'] . '</small>';
                        } else {
                            echo '<small class="font-weight-bold text-success">' . $_SESSION['demo_booking_table'][$a]['payment_status'] . '</small>';
                        }
                        ?>
                    </div>
                </div>
                <div class="mt-3 mb-2 d-flex justify-content-end">
                    <?php if ($_SESSION['demo_booking_table'][$a]['booking_status'] == 'requested for cancellation') { ?>
                        <button class="btn btn-outline-danger py-0 rounded-pill mr-2" name="viewbooking" value="submit" disabled>Request Sent</button>
                    <?php } else if ($_SESSION['demo_booking_table'][$a]['booking_status'] == 'cancelled') { ?>
                        <button class="btn btn-outline-danger py-0 rounded-pill mr-2" name="viewbooking" value="submit" disabled>Cancelled</button>
                    <?php } else { ?>
                        <a href="registeredBookings.php#modalcancelbooking" class="btn btn-outline-danger py-0 rounded-pill mr-2" data-bs-toggle="modal" data-userid="<?php echo $a ?>">Cancel</a>
                    <?php } ?>

                    <a href="viewBookedTickets.php?bid=<?php echo $a ?>" class="btn btn-outline-primary py-0 rounded-pill mr-2">View</a>
                </div>
            </div>
<?php  }
    }
} ?>
<?php if ($counter == 0) { ?>
    <div class="container container-small bg-secondary shadow rounded py-2 px-3 my-2 text-center">
        No Data Found
    </div>
<?php } ?>