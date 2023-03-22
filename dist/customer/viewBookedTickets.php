<?php
include('../database/db.php');
include('servercustomer.php') ?>
<?php
if (session_id() == '') {
    session_start();
}

if (!isset($_SESSION['customername'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: loginCustomer.php');
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['customername']);
    header("location: loginCustomer.php");
}
if (isset($_GET['unsetsession'])) {
    unset($_SESSION['selectedorigin']);
    unset($_SESSION['selecteddestination']);
    unset($_SESSION['selecteddate']);
    unset($_SESSION['selectedday']);
    header("location: registeredHome.php");
}

$name = $_SESSION['customername'];
$cid = $_SESSION['customerid'];
$bookingid = $_GET['bid'];
$_SESSION['booking_id'] = $bookingid;

if ($cid != "demo_account") {
    $result223 = $conn->query("SELECT a.id,a.companyID,a.terminalID,a.bustripID,a.origin,a.destination,a.duration,a.departure_time,a.number_of_seats,a.fare_amount,a.total_amount,a.payment_status,a.payer_email,a.payment_used,a.booked_at,a.booking_status,a.reference_id,b.trip_date,b.tripID,b.busID FROM booking_tbl a RIGHT OUTER JOIN bus_trip b ON(a.bustripID = b.id) WHERE a.id='$bookingid' AND a.customerID = '$cid' ");
    $bookings = $result223->fetch_all(MYSQLI_ASSOC);
}
$result224 = $conn->query("SELECT * FROM customer_booking_details WHERE bookingID = '$bookingid'");
$passengers = $result224->fetch_all(MYSQLI_ASSOC);



?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <?php include 'css/style.php'; ?>

</head>

<body>

    <div class="wrapper">
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light " style="background-color: #3b55d9;">
                <div class="container-fluid">
                    <a class="navbar-brand text-light" href="registeredBookings.php"><i class="fas fa-arrow-left mr-2 ml-2"></i></a><b class="text-light mr-5">Booking Details</b>
                    <div></div>
                </div>

            </nav>

            <?php
            if ($cid == "demo_account") {
                include 'extensions/viewBookedDemoTickets.php';
            } else {
            ?>
                <div class="container container-small  vertical-center px-4 mt-4 py-1 mb-4">
                    <?php foreach ($bookings as $booking) { ?>
                        <div class="container py-2 rounded shadow" style="background-color: #D2D5FA;">
                            <div class="text-center mb-2">
                                <small class="text-muted">Reference no: </small>
                                <h5><?php echo $booking['reference_id'] ?> </h5>
                            </div>
                            <p class="text-center text-dark mt-2">
                                <i class="fas fa-map-marker-alt mr-1" aria-hidden="true"></i><span class="mr-3"><?php echo $booking['origin'] ?></span> <i class="fas fa-long-arrow-alt-right"></i>
                                <i class="fas fa-location-arrow ml-3 mr-1" aria-hidden="true"></i><span class=""><?php echo $booking['destination'] ?></span><br>
                            <div class="mt-1 text-center text-light">
                                <span><small class="text-secondary">Trip Date:</small><small style="font-weight: 600;" class="text-dark"> <?php echo $booking['trip_date'] ?></small></span><br>
                                <span class="text-dark"><small class="text-secondary">Departure time: </small><small style="font-weight: 600;"><?php echo date('h:i A', strtotime($booking['departure_time'])) ?></small></span>
                            </div>
                            </p>
                        </div>
                        <div class="container shadow py-3 bg-light rounded">
                            <div class="row px-3 pt-2">
                                <?php
                                $count11 = $conn->query("SELECT companyName,email FROM user_partner_admin WHERE id = '" . $booking['companyID'] . "' limit 1") or die($mysql->connect_error);
                                $companyName = array_values($count11->fetch_assoc());
                                $count12 = $conn->query("SELECT terminal_name,city,province FROM terminal WHERE id = '" . $booking['terminalID'] . "' limit 1") or die($mysql->connect_error);
                                $terminalName = array_values($count12->fetch_assoc());
                                $count13 = $conn->query("SELECT seat_type,bus_model,busNumber FROM buses WHERE id = '" . $booking['busID'] . "' limit 1") or die($mysql->connect_error);
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
                                <?php $totalFare = $booking['fare_amount'] * $booking['number_of_seats'];
                                $tripFare = $booking['fare_amount']; ?>
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
                            $result2 = $conn->query("SELECT * FROM customer_booking_details WHERE bookingID = '" . $booking['id'] . "'");
                            $bdetails = $result2->fetch_all(MYSQLI_ASSOC);
                            foreach ($bdetails as $bdetail) : ?>
                                <div class=" px-3 pb-1">
                                    <small class="font-weight-bold text-capitalize">Seat Number: </small>
                                    <small class="font-weight-bold text-capitalize"><?php echo sprintf("%02d", $bdetail['seat_number']) ?></small>
                                </div>
                                <div class="row px-3 mt-0 mb-2">
                                    <div class="col ">
                                        <small class="text-muted">Passenger</small><br>
                                        <small class="font-weight-bold text-capitalize"><?php echo $bdetail['last_name'] . ', ' . $bdetail['first_name'] ?></small>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted">Gender</small><br>
                                        <small class="font-weight-bold text-capitalize"><?php echo $bdetail['gender'] ?></small>
                                    </div>
                                    <div class="col-2">
                                        <small class="text-muted">Age</small><br>
                                        <small class="font-weight-bold text-capitalize"><?php echo $bdetail['age'] ?></small>
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
                                            <small class="font-weight-bold text-capitalize"><?php echo $bdetail['valid_ID'] ?></small>
                                        </div>
                                        <div class="col-5">
                                            <?php if ($bdetail['file_a_status'] == "pending") { ?>
                                                <small class="font-weight-bold ">: </small><small class="text-warning font-weight-bold text-capitalize">Pending</small>
                                                <a href="registeredBookings.php#modaleditvalidid" class="btn btn-link btn-sm text-warning my-0 py-0 mr-2" data-bs-toggle="modal" data-userid="<?php echo $bdetail['id']; ?>"><i class="fas fa-edit"></i></a>
                                            <?php } else if ($bdetail['file_a_status'] == "invalid") { ?>
                                                <small class="font-weight-bold ">: </small><small class="text-danger font-weight-bold text-capitalize">Invalid</small>
                                                <a href="registeredBookings.php#modaleditvalidid" class="btn btn-link btn-sm text-danger my-0 py-0 mr-2" data-bs-toggle="modal" data-userid="<?php echo $bdetail['id']; ?>"><i class="fas fa-edit"></i></a>
                                            <?php } else if ($bdetail['file_a_status'] == "valid") { ?>
                                                <small class="font-weight-bold ">: </small><small class="text-success font-weight-bold text-capitalize">Valid</small>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row mt-2 g-0">
                                        <div class="col">
                                            <small class="text-muted">Vaccination Card</small>
                                            <?php if ($bdetail['file_b_status'] == "invalid" || $bdetail['file_b_status'] == "pending") { ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row g-0">
                                        <div class="col">
                                            <small class="font-weight-bold text-capitalize"><?php echo $bdetail['vaccination_card'] ?></small>
                                        </div>
                                        <div class="col-5">
                                            <?php if ($bdetail['file_b_status'] == "pending") { ?>
                                                <small class="font-weight-bold ">: </small><small class="text-warning font-weight-bold text-capitalize">Pending</small>
                                                <a href="registeredBookings.php#modaleditvaccard" class="btn btn-link btn-sm text-warning my-0 py-0 mr-2" data-bs-toggle="modal" data-userid="<?php echo $bdetail['id']; ?>"><i class="fas fa-edit"></i></a>
                                            <?php } else if ($bdetail['file_b_status'] == "invalid") { ?>
                                                <small class="font-weight-bold ">: </small><small class="text-danger font-weight-bold text-capitalize">Invalid</small>
                                                <a href="registeredBookings.php#modaleditvaccard" class="btn btn-link btn-sm text-danger my-0 py-0 mr-2" data-bs-toggle="modal" data-userid="<?php echo $bdetail['id']; ?>"><i class="fas fa-edit"></i></a>
                                            <?php } else if ($bdetail['file_b_status'] == "valid") { ?>
                                                <small class="font-weight-bold ">: </small><small class="text-success font-weight-bold text-capitalize">Valid</small>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <!-- <hr> -->
                                    <div class="row mt-2 g-0">
                                        <div class="col">
                                            <small class="text-muted">Travel Coordiation Permit (s-pass)</small>
                                            <?php if ($bdetail['file_c_status'] == "invalid" || $bdetail['file_c_status'] == "pending") { ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row g-0">
                                        <div class="col">
                                            <small class="font-weight-bold text-capitalize"><?php echo $bdetail['s_pass'] ?></small>
                                        </div>
                                        <div class="col-5">
                                            <?php if ($bdetail['file_c_status'] == "pending") { ?>
                                                <small class="font-weight-bold ">: </small><small class="text-warning font-weight-bold text-capitalize">Pending</small>
                                                <a href="registeredBookings.php#modaleditspass" class="btn btn-link btn-sm text-warning py-0 my-0 mr-2" data-bs-toggle="modal" data-userid="<?php echo $bdetail['id']; ?>"><i class="fas fa-edit"></i></a>
                                            <?php } else if ($bdetail['file_c_status'] == "invalid") { ?>
                                                <small class="font-weight-bold ">: </small><small class="text-danger font-weight-bold text-capitalize">Invalid</small>
                                                <a href="registeredBookings.php#modaleditspass" class="btn btn-link btn-sm text-danger py-0 my-0 mr-2" data-bs-toggle="modal" data-userid="<?php echo $bdetail['id']; ?>"><i class="fas fa-edit"></i></a>
                                            <?php } else if ($bdetail['file_c_status'] == "valid") { ?>
                                                <small class="font-weight-bold ">: </small><small class="text-success font-weight-bold text-capitalize">Valid</small>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                if ($bdetail === end($bdetails)) {
                                    echo '<br>';
                                } else {
                                    echo '<hr>';
                                }
                                ?>
                            <?php endforeach; ?>

                        </div>
                    <?php } ?>
                </div>



                <div class="container container-small  vertical-center px-4 mt-2 mb-4 py-1 ">
                    <?php foreach ($bookings as $booking) { ?>
                        <div class="container shadow py-3 px-4 bg-light ">
                            <small class='mt-1 text-muted'><b><?php echo 'Php ' . $booking['fare_amount'] . ' * ' . $booking['number_of_seats'] . ' (passengers)' ?></b></small><br>
                            <b class="" style="color: #3b55d9;">Total: Php <?php echo $totalFare ?></b>
                            <div class="row mt-2">
                                <div class="col pt-2">
                                    <?php if ($cid == 'demo_booking') { ?>
                                        <b class=" text-muted">Status:</b>
                                    <?php } else { ?>
                                        <b class=" text-muted">PayPal:</b>
                                    <?php } ?>
                                </div>
                                <div class="col">
                                    <?php if ($booking['payment_status'] == 'not paid' && $booking['booking_status'] == "confirmed") { ?>
                                        <?php
                                        $compid = $booking['companyID'];
                                        $count333 = $conn->query("SELECT paypal_email FROM payment_bussiness_info WHERE `companyID`='$compid'  limit 1") or die($mysql->connect_error);
                                        if ($count333) {
                                            $paypal_email = array_values($count333->fetch_assoc());
                                        }
                                        ?>
                                        <?php if ($count333) { ?>
                                            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                                                <input type="hidden" name="cmd" value="_xclick">
                                                <input type="hidden" name="business" value="<?php echo $paypal_email[0]; ?>">
                                                <input type="hidden" name="lc" value="PH">
                                                <input type="hidden" name="item_name" value="Booking Ticket">
                                                <input type="hidden" name="item_number" value="bookingticket">
                                                <input type="hidden" name="amount" value="<?php echo $totalFare ?>.00">
                                                <input type="hidden" name="currency_code" value="PHP">
                                                <input type="hidden" name="button_subtype" value="services">
                                                <input type="hidden" name="no_note" value="1">
                                                <input type="hidden" name="no_shipping" value="1">
                                                <input type="hidden" name="rm" value="1">
                                                <input type="hidden" name="return" value="https://omnibus-ph.000webhostapp.com/customer/success_payment.php">
                                                <input type="hidden" name="cancel_return" value="https://omnibus-ph.000webhostapp.com/customer/registeredHome.php">
                                                <input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynowCC_LG.gif:NonHosted">
                                                <input type="hidden" name="notify_url" value="https://omnibus-ph.000webhostapp.com/customer/handler.php?booking_id=<?php echo $bookingid ?>">
                                                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                                                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                                            </form>
                                        <?php } else { ?>
                                            <button class="btn btn-secondary py-2 px-5 rounded-pill float-right mr-4 mt-1" value="" disabled>No online payment</button>
                                        <?php } ?>
                                    <?php } else if ($booking['payment_status'] == 'not paid' && $booking['booking_status'] == "cancelled") { ?>
                                        <button class="btn btn-danger py-2 px-5 rounded-pill float-right mr-4 mt-1" name="viewbooking" value="" disabled>Cancelled</button>
                                    <?php } else if ($booking['payment_status'] == 'not paid' && $booking['booking_status'] == "requested for cancellation") { ?>
                                        <button class="btn btn-danger py-2 px-5 rounded-pill float-right mr-4 mt-1" name="viewbooking" value="" disabled>Not Paid</button>
                                    <?php } else if ($booking['payment_status'] == 'paid' && $booking['booking_status'] == "cancelled") { ?>
                                        <a href="viewBookedTickets.php#modalrefundbooking" class="btn btn-warning py-2 px-5 shadow rounded-pill float-right mr-4 mt-1" data-bs-toggle="modal" data-userid="<?php echo $bookingid; ?>">Refund</a>
                                    <?php } else if ($booking['payment_status'] == 'paid' && $booking['booking_status'] == "requested for cancellation") { ?>
                                        <a href="viewBookedTickets.php#modalrefundbooking" class="btn btn-warning py-2 px-5 shadow rounded-pill float-right mr-4 mt-1" data-bs-toggle="modal" data-userid="<?php echo $bookingid; ?>">Processing Refund</a>
                                    <?php } else if ($booking['payment_status'] == 'refunded') { ?>
                                        <button class="btn btn-success py-2 px-5 rounded-pill float-right mr-4 mt-1" name="viewbooking" value="" disabled>Refunded</button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>


            <?php if ($cid == "demo_account") { ?>
                <div class="fixed-bottom mb-3">
                    <div class="col-md-12 text-center">
                        <div class="alert alert-warning alert-dismissible fade show shadow" role="alert">
                            <strong>Note *</strong> Files cannot be viewed and change using demo account.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="modal" id="modaleditspass">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="container text-center">
                                <p>Travel Coordiation Permit (s-pass)</p>
                            </div>
                            <div class="">
                                <?php  ?>
                                <form class="form" id="changespass" action="" method="Post" enctype="multipart/form-data">
                                    <input type="hidden" class="form-control" name="passengerid" value="">
                                    <input type="hidden" class="form-control" name="bookingid" value="<?php echo $bookingid; ?>">
                                    <div class="form-group px-3">
                                        <span class="h6 " style="color: #999;"><small>(* Allows images files and pdf)</small></span>
                                        <input type="file" name="spass" class="form-control name_list my-2" accept="image/*,.pdf" required>
                                    </div>
                                    <div class="d-flex justify-content-center mt-4">
                                        <button type="button" class="btn btn-outline-secondary rounded-pill mr-3" data-bs-dismiss="modal">Back</button>
                                        <button type="submit" name="changespass" form="changespass" class="btn btn-danger rounded-pill ml-3">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal" id="modaleditvaccard">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="container text-center">
                                <p>Vaccination Card</p>
                            </div>
                            <div class="">
                                <?php  ?>
                                <form class="form" id="changevaccard" action="" method="Post" enctype="multipart/form-data">
                                    <input type="hidden" class="form-control" name="passengerids" value="">
                                    <input type="hidden" class="form-control" name="bookingid" value="<?php echo $bookingid; ?>">
                                    <div class="form-group px-3">
                                        <span class="h6 " style="color: #999;"><small>(* Allows images files and pdf)</small></span>
                                        <input type="file" name="vaccard" class="form-control name_list my-2" accept="image/*,.pdf" required>
                                    </div>
                                    <div class="d-flex justify-content-center mt-4">
                                        <button type="button" class="btn btn-outline-secondary rounded-pill mr-3" data-bs-dismiss="modal">Back</button>
                                        <button type="submit" name="changevaccard" form="changevaccard" class="btn btn-danger rounded-pill ml-3">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal" id="modaleditvalidid">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="container text-center">
                                <p>Valid ID</p>
                            </div>
                            <div class="">
                                <?php  ?>
                                <form class="form" id="changesvalidid" action="" method="Post" enctype="multipart/form-data">
                                    <?php foreach ($bookings as $booking) { ?>
                                        <input type="hidden" class="form-control" name="passengeridss" value="">
                                        <input type="hidden" class="form-control" name="bookingid" value="<?php echo $bookingid; ?>">
                                    <?php } ?>
                                    <div class="form-group px-3">
                                        <span class="h6 " style="color: #999;" small>(* Allows images files and pdf)</small></span>
                                        <input type="file" name="validid" class="form-control name_list my-2" accept="image/*,.pdf" required>
                                    </div>
                                    <div class="d-flex justify-content-center mt-4">
                                        <button type="button" class="btn btn-outline-secondary rounded-pill mr-3" data-bs-dismiss="modal">Back</button>
                                        <button type="submit" name="changesvalidid" form="changesvalidid" class="btn btn-danger rounded-pill ml-3">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal" id="modalrefundbooking">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="container text-center">
                                <p>Refund request will be sent through email</p>
                            </div>
                            <div class="">
                                <?php  ?>
                                <form class="form" id="refundBooking" action="" method="Post">
                                    <input type="hidden" class="form-control" name="bookingid" value="<?php echo $bookingid; ?>">
                                    <input type="hidden" class="form-control" name="reference" value="<?php echo $booking['reference_id'] ?>">
                                    <input type="hidden" class="form-control" name="companyname" value="<?php echo $companyName[0]; ?>">
                                    <input type="hidden" class="form-control" name="companyemail" value="<?php echo $companyName[1]; ?>">
                                    <div class="d-flex justify-content-center mt-4">
                                        <button type="button" class="btn btn-outline-secondary rounded-pill mr-3" data-bs-dismiss="modal">Back</button>
                                        <button type="submit" name="refundBooking" form="refundBooking" class="btn btn-danger rounded-pill ml-3">Send Request</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="overlay"></div>

            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <!-- Popper.JS -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
            <!-- Bootstrap JS -->
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
            <!-- jQuery Custom Scroller CDN -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>

            <script type="text/javascript">
                $('#origin').on('change', function() {
                    var optionInSelect2 = $('#destination').find('option[value="' + $(this).val() + '"]');
                    if (optionInSelect2.length) {
                        optionInSelect2.attr('disabled', 'disabled');
                    }
                });

                $("button").click(function() {
                    var id = $(this).val();
                    var btn = document.getElementById("idm" + id);
                    var input = document.getElementById('seatID');
                    var val = document.getElementById('seatID').value;
                    if (val != 0) {
                        if (val != id) {
                            var obtn = document.getElementById("idm" + val);
                            obtn.style.backgroundColor = "white";
                        }
                    }
                    btn.style.backgroundColor = "green";
                    input.value = id;
                });

                $('#modaleditspass').on('show.bs.modal', function(e) {
                    var userID = $(e.relatedTarget).data('userid');
                    var id = userID;
                    $(e.currentTarget).find('input[name="passengerid"]').val(userID);

                });
                $('#modaleditvaccard').on('show.bs.modal', function(e) {
                    var userID = $(e.relatedTarget).data('userid');
                    var id = userID;
                    $(e.currentTarget).find('input[name="passengerids"]').val(userID);

                });
                $('#modaleditvalidid').on('show.bs.modal', function(e) {
                    var userID = $(e.relatedTarget).data('userid');
                    var id = userID;
                    $(e.currentTarget).find('input[name="passengeridss"]').val(userID);

                });

                $(document).ready(function() {
                    $("#sidebar").mCustomScrollbar({
                        theme: "minimal"
                    });

                    $('#dismiss, .overlay').on('click', function() {
                        $('#sidebar').removeClass('active');
                        $('.overlay').removeClass('active');
                    });

                    $('#sidebarCollapse').on('click', function() {
                        $('#sidebar').addClass('active');
                        $('.overlay').addClass('active');
                        $('.collapse.in').toggleClass('in');
                        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
                    });
                });
            </script>



</body>

</html>