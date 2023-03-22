<h5>Travel History</h5>
Missed trips<br>
<?php
$counter = 0;
foreach ($bookings as $booking) :
    if (date("Y/m/d", strtotime($booking["trip_date"])) < date("Y/m/d") && $booking["booking_status"] == 'confirmed') {
        $counter++;
?>
        <div class="container container-small bg-light shadow rounded py-2 px-2 my-3">
            <?php
            $count11 = $conn->query("SELECT companyName,email FROM user_partner_admin WHERE id = '" . $booking['companyID'] . "' limit 1") or die($mysql->connect_error);
            $companyName = array_values($count11->fetch_assoc());
            $count12 = $conn->query("SELECT terminal_name FROM terminal WHERE id = '" . $booking['terminalID'] . "' limit 1") or die($mysql->connect_error);
            $terminalName = array_values($count12->fetch_assoc());
            $count13 = $conn->query("SELECT seat_type,bus_model,busNumber FROM buses WHERE id = '" . $booking['busID'] . "' limit 1") or die($mysql->connect_error);
            $busDetails = array_values($count13->fetch_assoc());


            $pieces = explode(":", $booking['duration']);
            $add = '+' . $pieces[0] . ' hour +' . $pieces[1] . ' minutes';
            // $add = $pieces[0].' hour +'.$pieces[1].' minutes';
            ?>
            <div class="text-center">
                <small class="text-muted">Reference no: </small>
                <h5><?php echo $booking['reference_id'] ?> </h5>
            </div>
            <div class="row">
                <div class="col">
                    <b style="color: #3b55d9;"><span id="companyname<?php echo $booking['id'] ?>"><?php echo $companyName[0] ?></span><?php echo ' (' . $terminalName[0] . ')' ?></b>
                </div>
                <span id="companyemail<?php echo $booking['id'] ?>" style="display:none;"><?php echo $companyName[1] ?></span>
                <span id="reference<?php echo $booking['id'] ?>" style="display:none;"><?php echo $booking['reference_id'] ?></span>
                <span id="tripID<?php echo $booking['id'] ?>" style="display:none;"><?php echo $booking['bustripID'] ?></span>
            </div>
            <div class="row">
                <div class="col">
                    <?php echo $booking['origin'] . ' - ' . $booking['destination'] ?>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col">
                    <small class="text-muted">Departs</small><br>
                    <small class="font-weight-bold"><?php echo date('h:i A', strtotime($booking['departure_time'])) ?></small>
                </div>
                <div class="col">
                    <small class="text-muted">Arrives</small><br>
                    <small class="font-weight-bold"><?php echo date('h:i A', strtotime($add, strtotime($booking["departure_time"]))); ?></small>
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
                    <small class="font-weight-bold"><?php echo $booking['number_of_seats'] ?></small>
                </div>
            </div>
            <hr>
            <div class="">
                <small class="text-muted">Departs on</small><br>
                <?php
                $bookdate = new DateTime($booking['trip_date']);
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
                    <small class="font-weight-bold"><?php echo $booking['trip_date'] ?></small>
                </div>
                <div class="col">
                    <small class="text-muted">Amount</small><br>
                    <small class="font-weight-bold"><?php echo 'Php' . $booking['total_amount'] ?></small>
                </div>
                <div class="col">
                    <small class="text-muted">Booking status</small><br>

                    <?php
                    if ($booking['payment_status'] == 'not paid') {
                        echo '<small class="font-weight-bold text-danger">' . $booking['payment_status'] . '</small>';
                    } else {
                        echo '<small class="font-weight-bold text-success">' . $booking['payment_status'] . '</small>';
                    }
                    ?>
                </div>
            </div>
            <div class="mt-3 mb-2 d-flex justify-content-end">
                <?php if ($booking['booking_status'] == 'requested for cancellation') { ?>
                    <button class="btn btn-outline-danger py-0 rounded-pill mr-2" name="viewbooking" value="submit" disabled>Request Sent</button>
                <?php } else if ($booking['booking_status'] == 'cancelled') { ?>
                    <button class="btn btn-outline-danger py-0 rounded-pill mr-2" name="viewbooking" value="submit" disabled>Cancelled</button>
                <?php } else { ?>
                    <a href="registeredBookings.php#modalcancelbooking" class="btn btn-outline-danger py-0 rounded-pill mr-2" data-bs-toggle="modal" data-userid="<?php echo $booking['id']; ?>">Cancel</a>
                <?php } ?>

                <a href="viewBookedTickets.php?bid=<?php echo $booking['id']; ?>" class="btn btn-outline-primary py-0 rounded-pill mr-2">View</a>
            </div>
        </div>
<?php  }
endforeach; ?>
<?php if ($counter == 0) { ?>
    <div class="container container-small bg-secondary shadow rounded py-2 px-3 my-2 text-center">
        No schedules yet
    </div>
<?php } ?>
<br>Travelled trips<br>
<?php
$counter = 0;
foreach ($bookings as $booking) :
    if ($booking["booking_status"] == 'travelled') {
        $counter++;
?>
        <div class="container container-small bg-light shadow rounded py-2 px-2 my-3">
            <?php
            $count11 = $conn->query("SELECT companyName,email FROM user_partner_admin WHERE id = '" . $booking['companyID'] . "' limit 1") or die($mysql->connect_error);
            $companyName = array_values($count11->fetch_assoc());
            $count12 = $conn->query("SELECT terminal_name FROM terminal WHERE id = '" . $booking['terminalID'] . "' limit 1") or die($mysql->connect_error);
            $terminalName = array_values($count12->fetch_assoc());
            $count13 = $conn->query("SELECT seat_type,bus_model,busNumber FROM buses WHERE id = '" . $booking['busID'] . "' limit 1") or die($mysql->connect_error);
            $busDetails = array_values($count13->fetch_assoc());


            $pieces = explode(":", $booking['duration']);
            $add = '+' . $pieces[0] . ' hour +' . $pieces[1] . ' minutes';
            // $add = $pieces[0].' hour +'.$pieces[1].' minutes';
            ?>
            <div class="text-center">
                <small class="text-muted">Reference no: </small>
                <h5><?php echo $booking['reference_id'] ?> </h5>
            </div>
            <div class="row">
                <div class="col">
                    <b style="color: #3b55d9;"><span id="companyname<?php echo $booking['id'] ?>"><?php echo $companyName[0] ?></span><?php echo ' (' . $terminalName[0] . ')' ?></b>
                </div>
                <span id="companyemail<?php echo $booking['id'] ?>" style="display:none;"><?php echo $companyName[1] ?></span>
                <span id="reference<?php echo $booking['id'] ?>" style="display:none;"><?php echo $booking['reference_id'] ?></span>
                <span id="tripID<?php echo $booking['id'] ?>" style="display:none;"><?php echo $booking['bustripID'] ?></span>
            </div>
            <div class="row">
                <div class="col">
                    <?php echo $booking['origin'] . ' - ' . $booking['destination'] ?>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col">
                    <small class="text-muted">Departs</small><br>
                    <small class="font-weight-bold"><?php echo date('h:i A', strtotime($booking['departure_time'])) ?></small>
                </div>
                <div class="col">
                    <small class="text-muted">Arrives</small><br>
                    <small class="font-weight-bold"><?php echo date('h:i A', strtotime($add, strtotime($booking["departure_time"]))); ?></small>
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
                    <small class="font-weight-bold"><?php echo $booking['number_of_seats'] ?></small>
                </div>
            </div>
            <hr>
            <div class="">
                <small class="text-muted">Departs on</small><br>
                <?php
                $bookdate = new DateTime($booking['trip_date']);
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
                    <small class="font-weight-bold"><?php echo $booking['trip_date'] ?></small>
                </div>
                <div class="col">
                    <small class="text-muted">Amount</small><br>
                    <small class="font-weight-bold"><?php echo 'Php' . $booking['total_amount'] ?></small>
                </div>
                <div class="col">
                    <small class="text-muted">Booking status</small><br>

                    <?php
                    if ($booking['payment_status'] == 'not paid') {
                        echo '<small class="font-weight-bold text-danger">' . $booking['payment_status'] . '</small>';
                    } else {
                        echo '<small class="font-weight-bold text-success">' . $booking['payment_status'] . '</small>';
                    }
                    ?>
                </div>
            </div>
            <div class="mt-3 mb-2 d-flex justify-content-end">
                <?php if ($booking['booking_status'] == 'requested for cancellation') { ?>
                    <button class="btn btn-outline-danger py-0 rounded-pill mr-2" name="viewbooking" value="submit" disabled>Request Sent</button>
                <?php } else if ($booking['booking_status'] == 'cancelled') { ?>
                    <button class="btn btn-outline-danger py-0 rounded-pill mr-2" name="viewbooking" value="submit" disabled>Cancelled</button>
                <?php } else { ?>
                    <a href="registeredBookings.php#modalcancelbooking" class="btn btn-outline-danger py-0 rounded-pill mr-2" data-bs-toggle="modal" data-userid="<?php echo $booking['id']; ?>">Cancel</a>
                <?php } ?>

                <a href="viewBookedTickets.php?bid=<?php echo $booking['id']; ?>" class="btn btn-outline-primary py-0 rounded-pill mr-2">View</a>
            </div>
        </div>
<?php  }
endforeach; ?>
<?php if ($counter == 0) { ?>
    <div class="container container-small bg-secondary shadow rounded py-2 px-3 my-2 text-center">
        No schedules yet
    </div>
<?php } ?>