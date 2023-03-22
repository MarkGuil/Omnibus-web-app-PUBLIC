<div class="container container-small  vertical-center px-4 mt-4 py-1 mb-4">
    <?php
    $result223 = $conn->query(" SELECT `trip_date`,`tripID`,`busID` FROM `bus_trip` WHERE `id` = '" . $_SESSION['demo_booking_table'][$bookingid]['bustripID'] . "' ");
    $bookings = array_values($result223->fetch_assoc());
    ?>
    <div class="container py-2 rounded shadow" style="background-color: #D2D5FA;">
        <div class="text-center mb-2">
            <small class="text-muted">Reference no: </small>
            <h5><?php echo $_SESSION['demo_booking_table'][$bookingid]['reference_id'] ?> </h5>
        </div>
        <p class="text-center text-dark mt-2">
            <i class="fas fa-map-marker-alt mr-1" aria-hidden="true"></i><span class="mr-3"><?php echo $_SESSION['demo_booking_table'][$bookingid]['origin'] ?></span> <i class="fas fa-long-arrow-alt-right"></i>
            <i class="fas fa-location-arrow ml-3 mr-1" aria-hidden="true"></i><span class=""><?php echo $_SESSION['demo_booking_table'][$bookingid]['destination'] ?></span><br>
        <div class="mt-1 text-center text-light">
            <span><small class="text-secondary">Trip Date:</small><small style="font-weight: 600;" class="text-dark"> <?php echo $bookings[0] ?></small></span><br>
            <span class="text-dark"><small class="text-secondary">Departure time: </small><small style="font-weight: 600;"><?php echo date('h:i A', strtotime($_SESSION['demo_booking_table'][$bookingid]['departure_time'])) ?></small></span>
        </div>
        </p>
    </div>
    <div class="container shadow py-3 bg-light rounded">
        <div class="row px-3 pt-2">
            <?php
            $count11 = $conn->query("SELECT companyName,email FROM user_partner_admin WHERE id = '" . $_SESSION['demo_booking_table'][$bookingid]['companyID'] . "' limit 1") or die($mysql->connect_error);
            $companyName = array_values($count11->fetch_assoc());
            $count12 = $conn->query("SELECT terminal_name,city,province FROM terminal WHERE id = '" . $_SESSION['demo_booking_table'][$bookingid]['terminalID'] . "' limit 1") or die($mysql->connect_error);
            $terminalName = array_values($count12->fetch_assoc());
            $count13 = $conn->query("SELECT seat_type,bus_model,busNumber FROM buses WHERE id = '" . $bookings[2] . "' limit 1") or die($mysql->connect_error);
            $busDetails = array_values($count13->fetch_assoc());
            ?>

            <div class="col ">
                <h6 class="font-weight-bold" style="color: #3b55d9;"><?php echo $companyName[0]; ?></h6>
                <div class="row">
                    <div class="col">
                        <span class="ml-1 text-muted"><small>Terminal:</small></span><br>
                        <span class="ml-1 "><small><b><?php echo $terminalName[0] ?></b></small></span>
                    </div>
                    <div class="col">
                        <span class="ml-1 text-muted"><small>Address:</small></span><br>
                        <span class="ml-1 "><small><b><?php echo $terminalName[1] . ', ' . $terminalName[2] ?></b></small></span>
                    </div>
                </div>
            </div>
            <?php $totalFare = $_SESSION['demo_booking_table'][$bookingid]['fare_amount'] * $_SESSION['demo_booking_table'][$bookingid]['number_of_seats'];
            $tripFare = $_SESSION['demo_booking_table'][$bookingid]['fare_amount']; ?>
        </div>
        <hr>
        <div class="row px-3">
            <div class="col">
                <small class="text-muted">Bus Number</small><br>
                <small class="font-weight-bold"><?php echo $busDetails[2]; ?></small>
            </div>
            <div class="col">
                <small class="text-muted">Bus Model</small><br>
                <small class="font-weight-bold"><?php echo $busDetails[1]; ?></small>
            </div>
            <div class="col">
                <small class="text-muted">Seat Type</small><br>
                <small class="font-weight-bold"><?php echo $busDetails[0]; ?></small>
            </div>
        </div>
        <p class="text-center  mt-4" style="line-height: normal; color: #3b55d9;">Passenger Detail</p>


        <?php
        for ($a = 0; $a < count($_SESSION['demo_booking_detail']); $a++) {
            if ($_SESSION['demo_booking_detail'][$a]["bookingID"] == $bookingid) {
        ?>
                <div class=" px-3 pb-1">
                    <small class="font-weight-bold text-capitalize">Seat Number: </small>
                    <small class="font-weight-bold text-capitalize"><?php echo sprintf("%02d", $_SESSION['demo_booking_detail'][$a]['seat_number']) ?></small>
                </div>
                <div class="row px-3 mt-0 mb-2">
                    <div class="col ">
                        <small class="text-muted">Passenger</small><br>
                        <small class="font-weight-bold text-capitalize"><?php echo $_SESSION['demo_booking_detail'][$a]['last_name'] . ', ' . $_SESSION['demo_booking_detail'][$a]['first_name'] ?></small>
                    </div>
                    <div class="col-3">
                        <small class="text-muted">Gender</small><br>
                        <small class="font-weight-bold text-capitalize"><?php echo $_SESSION['demo_booking_detail'][$a]['gender'] ?></small>
                    </div>
                    <div class="col-3 px-2">
                        <small class="text-muted">Age</small><br>
                        <small class="font-weight-bold text-capitalize"><?php echo $_SESSION['demo_booking_detail'][$a]['age'] ?></small>
                    </div>
                </div>
                <div class="px-3 mt-2 mb-2">
                    <div class="row g-0">
                        <div class="col">
                            <small class="text-muted">Valid ID</small>
                        </div>
                    </div>
                    <div class="row g-0">
                        <div class="col">
                            <small class="font-weight-bold text-capitalize"><?php echo $_SESSION['demo_booking_detail'][$a]['valid_ID'] ?></small>
                        </div>
                        <div class="col-5">
                            <?php if ($_SESSION['demo_booking_detail'][$a]['valid_ID'] == "pending") { ?>
                                <small class="font-weight-bold ">: </small><small class="text-warning font-weight-bold text-capitalize">Pending</small>
                                <a href="registeredBookings.php#modaleditvalidid" class="btn btn-link btn-sm text-warning my-0 py-0 mr-2" data-bs-toggle="modal" data-userid="<?php echo $a; ?>"><i class="fas fa-edit"></i></a>
                            <?php } else if ($_SESSION['demo_booking_detail'][$a]['valid_ID'] == "invalid") { ?>
                                <small class="font-weight-bold ">: </small><small class="text-danger font-weight-bold text-capitalize">Invalid</small>
                                <a href="registeredBookings.php#modaleditvalidid" class="btn btn-link btn-sm text-danger my-0 py-0 mr-2" data-bs-toggle="modal" data-userid="<?php echo $a; ?>"><i class="fas fa-edit"></i></a>
                            <?php } else if ($_SESSION['demo_booking_detail'][$a]['valid_ID'] == "valid") { ?>
                                <small class="font-weight-bold ">: </small><small class="text-success font-weight-bold text-capitalize">Valid</small>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row mt-2 g-0">
                        <div class="col">
                            <small class="text-muted">Vaccination Card</small>
                            <?php if ($_SESSION['demo_booking_detail'][$a]['vaccination_card'] == "invalid" || $_SESSION['demo_booking_detail'][$a]['vaccination_card'] == "pending") { ?>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row g-0">
                        <div class="col">
                            <small class="font-weight-bold text-capitalize"><?php echo $_SESSION['demo_booking_detail'][$a]['vaccination_card'] ?></small>
                        </div>
                        <div class="col-5">
                            <?php if ($_SESSION['demo_booking_detail'][$a]['vaccination_card'] == "pending") { ?>
                                <small class="font-weight-bold ">: </small><small class="text-warning font-weight-bold text-capitalize">Pending</small>
                                <a href="registeredBookings.php#modaleditvaccard" class="btn btn-link btn-sm text-warning my-0 py-0 mr-2" data-bs-toggle="modal" data-userid="<?php echo $a; ?>"><i class="fas fa-edit"></i></a>
                            <?php } else if ($_SESSION['demo_booking_detail'][$a]['vaccination_card'] == "invalid") { ?>
                                <small class="font-weight-bold ">: </small><small class="text-danger font-weight-bold text-capitalize">Invalid</small>
                                <a href="registeredBookings.php#modaleditvaccard" class="btn btn-link btn-sm text-danger my-0 py-0 mr-2" data-bs-toggle="modal" data-userid="<?php echo $a; ?>"><i class="fas fa-edit"></i></a>
                            <?php } else if ($_SESSION['demo_booking_detail'][$a]['vaccination_card'] == "valid") { ?>
                                <small class="font-weight-bold ">: </small><small class="text-success font-weight-bold text-capitalize">Valid</small>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- <hr> -->
                    <div class="row mt-2 g-0">
                        <div class="col">
                            <small class="text-muted">Travel Coordiation Permit (s-pass)</small>
                            <?php if ($_SESSION['demo_booking_detail'][$a]['s_pass'] == "invalid" || $_SESSION['demo_booking_detail'][$a]['s_pass'] == "pending") { ?>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row g-0">
                        <div class="col">
                            <small class="font-weight-bold text-capitalize"><?php echo $_SESSION['demo_booking_detail'][$a]['s_pass'] ?></small>
                        </div>
                        <div class="col-5">
                            <?php if ($_SESSION['demo_booking_detail'][$a]['s_pass'] == "pending") { ?>
                                <small class="font-weight-bold ">: </small><small class="text-warning font-weight-bold text-capitalize">Pending</small>
                                <a href="registeredBookings.php#modaleditspass" class="btn btn-link btn-sm text-warning py-0 my-0 mr-2" data-bs-toggle="modal" data-userid="<?php echo $a; ?>"><i class="fas fa-edit"></i></a>
                            <?php } else if ($_SESSION['demo_booking_detail'][$a]['s_pass'] == "invalid") { ?>
                                <small class="font-weight-bold ">: </small><small class="text-danger font-weight-bold text-capitalize">Invalid</small>
                                <a href="registeredBookings.php#modaleditspass" class="btn btn-link btn-sm text-danger py-0 my-0 mr-2" data-bs-toggle="modal" data-userid="<?php echo $a; ?>"><i class="fas fa-edit"></i></a>
                            <?php } else if ($_SESSION['demo_booking_detail'][$a]['s_pass'] == "valid") { ?>
                                <small class="font-weight-bold ">: </small><small class="text-success font-weight-bold text-capitalize">Valid</small>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php
                if ($_SESSION['demo_booking_detail'][$a] === end($_SESSION['demo_booking_detail'])) {
                    echo '<br>';
                } else {
                    echo '<hr>';
                }
                ?>
        <?php
            }
        }
        ?>

    </div>
</div>



<div class="container container-small  vertical-center px-4 mt-2 mb-4 py-1 ">
    <div class="container shadow py-3 px-4 bg-light ">
        <small class='mt-1 text-muted'><b><?php echo 'Php ' . $_SESSION['demo_booking_table'][$bookingid]['fare_amount'] . ' * ' . $_SESSION['demo_booking_table'][$bookingid]['number_of_seats'] . ' (passengers)' ?></b></small><br>
        <b class="" style="color: #3b55d9;">Total: Php <?php echo $totalFare ?></b>
        <div class="row mt-2">
            <div class="col">
                <?php if ($_SESSION['demo_booking_table'][$bookingid]['payment_status'] == 'not paid' && $_SESSION['demo_booking_table'][$bookingid]['booking_status'] == "confirmed") { ?>
                    <button class="btn btn-secondary py-2 px-5 rounded-pill mr-4 mt-1 w-100" value="" disabled>Payment disabled</button>
                <?php } else if ($_SESSION['demo_booking_table'][$bookingid]['payment_status'] == 'not paid' && $_SESSION['demo_booking_table'][$bookingid]['booking_status'] == "cancelled") { ?>
                    <button class="btn btn-danger py-2 px-5 rounded-pill mr-4 mt-1 w-100" name="viewbooking" value="" disabled>Cancelled</button>
                <?php } else if ($_SESSION['demo_booking_table'][$bookingid]['payment_status'] == 'not paid' && $_SESSION['demo_booking_table'][$bookingid]['booking_status'] == "requested for cancellation") { ?>
                    <button class="btn btn-danger py-2 px-5 rounded-pill  mr-4 mt-1 w-100" name="viewbooking" value="" disabled>Not Paid</button>
                <?php } else if ($_SESSION['demo_booking_table'][$bookingid]['payment_status'] == 'paid' && $_SESSION['demo_booking_table'][$bookingid]['booking_status'] == "cancelled") { ?>
                    <a href="viewBookedTickets.php#modalrefundbooking" class="btn btn-warning py-2 px-5 shadow rounded-pill mr-4 mt-1 w-100" data-bs-toggle="modal" data-userid="<?php echo $bookingid; ?>">Refund</a>
                <?php } else if ($_SESSION['demo_booking_table'][$bookingid]['payment_status'] == 'paid' && $_SESSION['demo_booking_table'][$bookingid]['booking_status'] == "requested for cancellation") { ?>
                    <a href="viewBookedTickets.php#modalrefundbooking" class="btn btn-warning py-2 px-5 shadow rounded-pill mr-4 mt-1 w-100" data-bs-toggle="modal" data-userid="<?php echo $bookingid; ?>">Processing Refund</a>
                <?php } else if ($_SESSION['demo_booking_table'][$bookingid]['payment_status'] == 'refunded') { ?>
                    <button class="btn btn-success py-2 px-5 rounded-pill mr-4 mt-1 w-100" name="viewbooking" value="" disabled>Refunded</button>
                <?php } ?>
            </div>
        </div>
    </div>
</div>