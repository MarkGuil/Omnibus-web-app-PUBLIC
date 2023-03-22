<?php
include('../database/db.php');
include('servercustomer.php'); ?>
<?php
if (session_id() == '') {
    session_start();
}

if (!isset($_SESSION['customername'])) {
    $_SESSION['msg'] = "You must log in first";
    header("location: ../validation/loginMobile.php");
}
if (isset($_GET['desktop_logout'])) {
    session_destroy();
    unset($_SESSION['customername']);
    header("location: ../validation/loginb.php");
}
if (isset($_GET['mobile_logout'])) {
    session_destroy();
    unset($_SESSION['customername']);
    header("location: ../validation/loginMobile.php");
}

$name = $_SESSION['customername'];
$cid = $_SESSION['customerid'];
if ($cid != "demo_account") {
    $result223 = $conn->query("SELECT a.id,a.companyID,a.terminalID,a.bustripID,a.origin,a.destination,a.duration,a.departure_time,a.number_of_seats,a.fare_amount,a.total_amount,a.payment_status,a.payer_email,a.payment_used,a.booked_at,a.booking_status,a.reference_id,b.trip_date,b.tripID,b.busID FROM booking_tbl a RIGHT OUTER JOIN bus_trip b ON(a.bustripID = b.id) WHERE a.customerID = '$cid' ");
    $bookings = $result223->fetch_all(MYSQLI_ASSOC);
}

$datetime = new DateTime();
$timezone = new DateTimeZone('Asia/Manila');
$datetime->setTimezone($timezone);
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
        <nav id="sidebar">
            <div id="dismiss">
                <i class="fas fa-arrow-left"></i>
            </div>

            <div class="sidebar-header">
                <h3 class="m-0">Omnibus</h3>
            </div>

            <ul class="list-unstyled components ml-2">
                <p class="text-center text-break text-capitalize">
                    <?php if (isset($_SESSION['customername'])) : ?>
                        Welcome <strong class="text-capitalize"><?php echo $_SESSION['customername']; ?></strong>
                    <?php endif ?>
                </p>
                <li class="nactive">
                    <a href="registeredHome.php" class="text-white"><i class="fas fa-home mr-2"></i> Home</a>
                </li>
                <li class="active">
                    <a href="#" data-toggle="collapse" aria-expanded="false" class="text-white"><i class="fas fa-calendar-check mr-2"></i> Bookings</a>
                </li>
                <li class="nactive">
                    <a href="registeredProfile.php" class="text-white"><i class="fas fa-user mr-2"></i> Account</a>
                </li>
            </ul>

            <ul class="list-unstyled CTAs">
                <li>

                </li>
                <form class="form-inline my-2 my-lg-0">
                    <?php if (isset($_SESSION['customername']) && isset($_SESSION['isMobile']) && $_SESSION['isMobile'] == false) { ?>
                        <a class="btn btn-danger p-2 w-100" href="registeredBookings.php?desktop_logout='1'">Logout</a>
                    <?php } else if (isset($_SESSION['customername']) && isset($_SESSION['isMobile']) && $_SESSION['isMobile'] == true) { ?>
                        <a class="btn btn-danger p-2 w-100" href="registeredBookings.php?mobile_logout='1'">Logout</a>
                    <?php }  ?>
                </form>

            </ul>
        </nav>

        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light " style="background-color: #3b55d9;">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="btn btn-light">
                        <i class="fas fa-align-left" style="color: #3b55d9;"></i>
                    </button>
                    <span class="ml-3 fs-4 mt-1 navbar-brand text-light">Onmibus</span>
                    <div class="d-inline-block d-lg-none ml-auto"></div>

                </div>
            </nav>

            <div class="container container-small vertical-center shadow" style="background-color: #7390d5;">

                <form class action="" method="GET">
                    <div class="row px-0">
                        <?php
                        if (isset($_GET['ongoing']) || !isset($_GET['ongoing']) && !isset($_GET['cancelled']) && !isset($_GET['history'])) {
                        ?>
                            <div class="col text-center px-0" style="background-color: #5c66f7;">
                                <button class="btn text-light w-100" name="ongoing">On going</button>
                            </div>
                            <div class="col text-center px-0" style="border-left: 1px solid #5c66f7;border-right: 1px solid #5c66f7">
                                <button class="btn text-light w-100" name="cancelled">Cancelled</button>
                            </div>
                            <div class="col text-center px-0">
                                <button class="btn text-light w-100" name="history">History</button>
                            </div>
                        <?php }
                        if (isset($_GET['cancelled'])) { ?>
                            <div class="col text-center px-0">
                                <button class="btn text-light w-100" name="ongoing">On going</button>
                            </div>
                            <div class="col text-center px-0" style="background-color: #5c66f7;border-left: 1px solid #5c66f7;border-right: 1px solid #5c66f7">
                                <button class="btn text-light w-100" name="cancelled">Cancelled</button>
                            </div>
                            <div class="col text-center px-0">
                                <button class="btn text-light w-100" name="history">History</button>
                            </div>
                        <?php }
                        if (isset($_GET['history'])) { ?>
                            <div class="col text-center px-0">
                                <button class="btn text-light w-100" name="ongoing">On going</button>
                            </div>
                            <div class="col text-center px-0" style="border-left: 1px solid #5c66f7;border-right: 1px solid #5c66f7">
                                <button class="btn text-light w-100" name="cancelled">Cancelled</button>
                            </div>
                            <div class="col text-center px-0" style="background-color: #5c66f7">
                                <button class="btn text-light w-100" name="history">History</button>
                            </div>
                        <?php } ?>
                    </div>
                </form>

            </div>

            <div class="container container-small vertical-center px-3 mt-3 ">
                <?php
                include('errors.php');

                if (isset($_GET['ongoing']) || !isset($_GET['ongoing']) && !isset($_GET['cancelled']) && !isset($_GET['history'])) {

                    if ($cid == "demo_account") {
                        include 'extensions/bookings_demo.php';
                    } else {
                ?>
                        <h5>On going trips</h5>
                        Trips Today<br>
                        <?php
                        $counter = 0;
                        foreach ($bookings as $booking) {
                            if ($booking["booking_status"] == 'confirmed') {
                                if (date("Y/m/d", strtotime($booking["trip_date"])) == $datetime->format('Y/m/d')) {
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

                                            <!-- <button class="btn btn-outline-primary py-0 rounded-pill  mr-2" name="viewbooking" value="" >View</button> -->
                                            <a href="viewBookedTickets.php?bid=<?php echo $booking['id']; ?>" class="btn btn-outline-primary py-0 rounded-pill mr-2">View</a>
                                        </div>
                                    </div>
                            <?php  }
                            }
                        }
                        if ($counter == 0) { ?>
                            <div class="container container-small bg-secondary shadow rounded py-2 px-3 my-2 text-center">
                                No schedules yet
                            </div>
                        <?php } ?>
                        <br>Upcoming Trips<br>
                        <?php
                        $counter = 0;
                        foreach ($bookings as $booking) {
                            if ($booking["booking_status"] == 'confirmed') {
                                if (date("Y/m/d", strtotime($booking["trip_date"])) > $datetime->format('Y/m/d')) {
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
                                                    echo '<small id="paystatus' . $booking['id'] . '" class="font-weight-bold text-danger">' . $booking['payment_status'] . '</small>';
                                                } else {
                                                    echo '<small id="paystatus' . $booking['id'] . '" class="font-weight-bold text-success">' . $booking['payment_status'] . '</small>';
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

                                            <!-- <button class="btn btn-outline-primary py-0 rounded-pill  mr-2" name="viewbooking" value="" >View</button> -->
                                            <a href="viewBookedTickets.php?bid=<?php echo $booking['id']; ?>" class="btn btn-outline-primary py-0 rounded-pill mr-2">View</a>
                                        </div>
                                    </div>
                            <?php  }
                            }
                        };
                        if ($counter == 0) { ?>
                            <div class="container container-small bg-secondary shadow rounded py-2 px-3 my-2 text-center">
                                No schedules yet
                            </div>
                    <?php }
                    }
                }
                if (isset($_GET['cancelled'])) { ?>
                    <?php
                    if ($cid == "demo_account") {
                        include 'extensions/bookings_demo_cancel.php';
                    } else {
                        include 'cancelledBooking.php';
                    }
                    ?>
                <?php }
                if (isset($_GET['history'])) { ?>
                    <?php if ($cid == "demo_account") {
                        include 'extensions/bookings_demo_history.php';
                    } else {
                        include 'historyBooking.php';
                    }
                    ?>
                <?php } ?>
            </div>

            <div class="modal" id="modalcancelbooking">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="container text-center">
                                <p>This action cannot be undone. Are you sure want to continue?</p>
                                <?php if ($cid == "demo_account") { ?>
                                    <div class="">
                                        <div class="col-md-12 text-center">
                                            <div class="alert alert-warning alert-dismissible fade show shadow" role="alert">
                                                <strong>Note *</strong> Cancellation of booking will automatically be cancelled for demo users.
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="">
                                <?php  ?>
                                <form class="form" id="cancelBooking" action="" method="Post">
                                    <input type="hidden" class="form-control" name="bookingid" value="">
                                    <input type="hidden" class="form-control" name="companyname" value="">
                                    <input type="hidden" class="form-control" name="companyemail" value="">
                                    <input type="hidden" class="form-control" name="reference" value="">
                                    <input type="hidden" class="form-control" name="tripid" value="">
                                    <div class="form-group px-3">
                                        <label for="exampleFormControlTextarea1"><small class="text-muted">Reason for cancellation</small></label>
                                        <textarea class="form-control" name="reason" id="exampleFormControlTextarea1" rows="3"></textarea>
                                    </div>
                                    <div class="d-flex justify-content-center mt-4">
                                        <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Back</button>

                                        <?php if ($cid == "demo_account") { ?>
                                            <button type="button" name="" form="" class="btn btn-danger rounded-pill ml-3" disabled>Send Request</button>
                                            <button type="submit" name="cancelBooking" form="cancelBooking" class="btn btn-danger rounded-pill ml-3">Continue</button>
                                        <?php } else { ?>
                                            <button type="submit" name="cancelBooking" form="cancelBooking" class="btn btn-danger rounded-pill ml-3">Send Request</button>
                                        <?php } ?>
                                    </div>
                                </form>
                            </div>
                        </div>
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

        $('#modalcancelbooking').on('show.bs.modal', function(e) {
            var userID = $(e.relatedTarget).data('userid');
            var id = userID;
            var companyname = $('#companyname' + id).text();
            var companyemail = $('#companyemail' + id).text();
            var reference = $('#reference' + id).text();
            var paystatus = $('#paystatus' + id).text();
            var tripid = $('#tripID' + id).text();
            $(e.currentTarget).find('input[name="bookingid"]').val(userID);
            $(e.currentTarget).find('input[name="companyname"]').val(companyname);
            $(e.currentTarget).find('input[name="companyemail"]').val(companyemail);
            $(e.currentTarget).find('input[name="reference"]').val(reference);
            $(e.currentTarget).find('input[name="tripid"]').val(tripid);
            // $(e.currentTarget).find('input[name="paystatus"]').val(paystatus);
            // if(paystatus == 'not paid'){
            //     document.getElementById('payeremail').style.display = "block";
            // }

            // document.getElementById("nameee").innerText = fn;
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