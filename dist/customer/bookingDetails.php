<?php
include('../database/db.php');
include('servercustomer.php'); ?>
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
if (!isset($_SESSION['selectedorigin']) || !isset($_SESSION['selecteddestination']) || !isset($_SESSION['selecteddate']) || !isset($_SESSION['selectedday'])) {
    header('location: registeredHome.php');
}
if (isset($_GET['unsetsession'])) {
    unset($_SESSION['selectedorigin']);
    unset($_SESSION['selecteddestination']);
    unset($_SESSION['selecteddate']);
    unset($_SESSION['selectedday']);
    unset($_SESSION['selectednumofpassenger']);
    unset($_SESSION['selectedtripid']);
    unset($_SESSION['selectedbusid']);
    unset($_SESSION['selectedcompid']);
    unset($_SESSION['selectedtsid']);
    unset($_SESSION['selectedfare']);
    unset($_SESSION['selectedterminalid']);
    unset($_SESSION['selectedduration']);
    unset($_SESSION['selecteddeparture_time']);
    header("location: registeredHome.php");
}

$name = $_SESSION['customername'];
$cid = $_SESSION['customerid'];
$sorigin = $_SESSION['selectedorigin'];
$sdest = $_SESSION['selecteddestination'];
$sdate = $_SESSION['selecteddate'];
$sday = $_SESSION['selectedday'];
$scompid = $_SESSION['selectedcompid'];
$sbusid = $_SESSION['selectedbusid'];
$stripid = $_SESSION['selectedtripid'];
$sstid = $_SESSION['selectedtsid'];
$sseat = $_SESSION['selectedseatid'];
$bookingIDs = $_SESSION['bookingID'];
$terminalid = $_SESSION['selectedterminalid'];
$numofpassenger = $_SESSION['selectednumofpassenger'];
$result = $conn->query("SELECT * FROM bus_trip WHERE id = '$sstid'");
$trips = $result->fetch_all(MYSQLI_ASSOC);
$totalFare = 0;
$tripFare = 0;

$count2 = $conn->query("SELECT companyName FROM user_partner_admin WHERE `id`='$scompid' limit 1") or die($mysql->connect_error);
$compName = array_values($count2->fetch_assoc());
$count4 = $conn->query("SELECT terminal_name,city,province FROM terminal WHERE `id`='$terminalid' AND `companyID`='$scompid' limit 1") or die($mysql->connect_error);
$terminalName = array_values($count4->fetch_assoc());
$count3 = $conn->query("SELECT seat_type,bus_model,busNumber FROM buses WHERE `id`='$sbusid' AND `companyID`='$scompid' limit 1") or die($mysql->connect_error);
$busNumber = array_values($count3->fetch_assoc());
// $count4 = $conn->query("SELECT connumber FROM user_customer WHERE `id`='$cid' AND fullname = '$name' limit 1") or die($mysql->connect_error);
// $connumber = array_values($count4->fetch_assoc());
// $result1 = $conn->query("SELECT connumber,email FROM user_customer WHERE `id` = '$cid' AND fullname = '$name'");
// $cons = $result1->fetch_all(MYSQLI_ASSOC);
$result222 = $conn->query("SELECT a.origin, a.destination, a.duration, a.departure_time, a.routeID,b.id,b.companyID, b.trip_date, b.fare, b.total_seat, b.taken_seat, b.tripID, b.busID, b.terminalID FROM trip a RIGHT OUTER JOIN bus_trip b ON(a.id = b.tripID) WHERE b.id = '$stripid' AND b.companyID = '$scompid'");
$tripss = $result222->fetch_all(MYSQLI_ASSOC);
if ($cid != "demo_account") {
    $count333 = $conn->query("SELECT paypal_email FROM payment_bussiness_info WHERE `companyID`='$scompid'  limit 1") or die($mysql->connect_error);
    if ($count333) {
        $paypal_email = array_values($count333->fetch_assoc());
    }
}



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
                    <form class="px-0" method="GET">
                        <button type="submit" name="unsetsession" class="btn btn-link navbar-brand text-light px-0"><i class="fas fa-arrow-left mr-2 ml-2"></i></button>
                    </form>
                    <b class="text-light mr-5">Booking Details</b>
                    <div class="mr-5"></div>
                    <!-- <a class="navbar-brand text-light" href="registeredHome.php"><i class="fas fa-arrow-left mr-2 ml-2"></i></a><b class="text-light mr-5">Booking Details</b><div></div> -->
                </div>
            </nav>

            <div class="container container-small  vertical-center px-4 mt-4 py-1 ">
                <?php foreach ($tripss as $trip) { ?>
                    <div class="container py-2 rounded shadow" style="background-color: #D2D5FA;">
                        <p class="text-center text-dark mt-2">
                            <i class="fas fa-map-marker-alt mr-1" aria-hidden="true"></i><span class="mr-3"><?php echo $sorigin ?></span> <i class="fas fa-long-arrow-alt-right"></i>
                            <i class="fas fa-location-arrow ml-3 mr-1" aria-hidden="true"></i><span class=""><?php echo $sdest ?></span><br>
                        <div class="mt-1 text-center text-light">
                            <span><small class="text-secondary">Trip Date:</small><small style="font-weight: 600;" class="text-dark"> <?php echo $sdate ?></small></span><br>
                            <span class="text-dark"><small class="text-secondary">Departure time: </small><small style="font-weight: 600;"><?php echo date('h:i A', strtotime($trip['departure_time'])) ?></small></span>
                        </div>
                        </p>
                    </div>
                    <div class="container shadow py-3 bg-light rounded">
                        <div class="row px-3 pt-2">
                            <div class="col ">
                                <h6 class="font-weight-bold" style="color: #3b55d9;"><?php echo $compName[0]; ?></h6>
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
                            <?php $totalFare = $trip['fare'] * $numofpassenger;
                            $tripFare = $trip['fare']; ?>
                        </div>
                        <hr>
                        <div class="row px-3">
                            <div class="col">
                                <small class="text-muted">Bus Number</small><br>
                                <small class="font-weight-bold"><?php echo $busNumber[2]; ?></small>
                            </div>
                            <div class="col">
                                <small class="text-muted">Bus Model</small><br>
                                <small class="font-weight-bold"><?php echo $busNumber[1]; ?></small>
                            </div>
                            <div class="col">
                                <small class="text-muted">Seat Type</small><br>
                                <small class="font-weight-bold"><?php echo $busNumber[0]; ?></small>
                            </div>
                        </div>
                        <p class="text-center  mt-4" style="line-height: normal; color: #3b55d9;">Passenger Detail</p>
                        <?php if ($cid == "demo_account") {
                            for ($a = 0; $a < count($_SESSION['demo_booking_detail']); $a++) {
                                if ($_SESSION['demo_booking_detail'][$a]['bookingID'] == $bookingIDs) {
                        ?>
                                    <div class=" px-3 pb-1">
                                        <small class="font-weight-bold text-capitalize">Seat Number: </small>
                                        <small class="font-weight-bold text-capitalize"><?php echo sprintf("%02d", $_SESSION['demo_booking_detail'][$a]["seat_number"]); ?></small>
                                    </div>
                                    <div class="row px-3 mt-0 mb-2">
                                        <div class="col ">
                                            <small class="text-muted">Passenger</small><br>
                                            <small class="font-weight-bold text-capitalize"><?php echo $_SESSION['demo_booking_detail'][$a]["last_name"] . ', ' . $_SESSION['demo_booking_detail'][$a]["first_name"] ?></small>
                                        </div>
                                        <div class="col-4">
                                            <small class="text-muted">Gender</small><br>
                                            <small class="font-weight-bold text-capitalize"><?php echo $_SESSION['demo_booking_detail'][$a]['gender'] ?></small>
                                        </div>
                                        <div class="col-2">
                                            <small class="text-muted">Age</small><br>
                                            <small class="font-weight-bold text-capitalize"><?php echo $_SESSION['demo_booking_detail'][$a]['age'] ?></small>
                                        </div>
                                    </div>
                                <?php
                                }
                            }
                        } else {
                            $result2 = $conn->query("SELECT * FROM customer_booking_details WHERE bookingID = '$bookingIDs'");
                            $bookings = $result2->fetch_all(MYSQLI_ASSOC);
                            foreach ($bookings as $booking) { ?>
                                <div class=" px-3 pb-1">
                                    <small class="font-weight-bold text-capitalize">Seat Number: </small>
                                    <small class="font-weight-bold text-capitalize"><?php echo sprintf("%02d", $booking['seat_number']) ?></small>
                                </div>
                                <div class="row px-3 mt-0 mb-2">
                                    <div class="col ">
                                        <small class="text-muted">Passenger</small><br>
                                        <small class="font-weight-bold text-capitalize"><?php echo $booking['last_name'] . ', ' . $booking['first_name'] ?></small>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted">Gender</small><br>
                                        <small class="font-weight-bold text-capitalize"><?php echo $booking['gender'] ?></small>
                                    </div>
                                    <div class="col-2">
                                        <small class="text-muted">Age</small><br>
                                        <small class="font-weight-bold text-capitalize"><?php echo $booking['age'] ?></small>
                                    </div>
                                </div>

                                <?php
                                if ($booking === end($bookings)) {
                                    echo '<br>';
                                } else {
                                    echo '<hr>';
                                }
                                ?>

                        <?php }
                        } ?>


                    </div>
                <?php } ?>
            </div>
            <div class="container container-small  vertical-center px-4 mt-4 py-1 ">
                <div class="container  py-3 bg-light rounded">
                    <small class='mt-1 ml-2 text-muted'><b><?php echo 'Php ' . $tripFare . ' * ' . $numofpassenger . ' (passengers)' ?></b></small><br>
                    <b class="ml-2" style="color: #3b55d9;">Total: Php <?php echo $totalFare ?></b>
                </div>
                <div class="container pb-3 px-4 bg-light rounded">
                    <?php
                    if ($cid == "demo_account") { ?>
                        <form class="px-0" method="GET">
                            <button class="btn btn-lg btn-success rounded mt-2 w-100" type="submit" name="unsetsession">done</button>
                        </form>
                    <?php } else { ?>
                        <div class="row  ">
                            <div class="col pt-2">
                                <b class="" style="color: #3b55d9;">PayPal:</b>
                            </div>
                            <div class="col">
                                <?php
                                if ($count333) {
                                ?>
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
                                        <input type="hidden" name="notify_url" value="https://omnibus-ph.000webhostapp.com/customer/handler.php?booking_id=<?php echo $bookingIDs ?>">
                                        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                                        <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                                    </form>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php if ($cid == "demo_account") { ?>
                <div class="fixed-bottom mb-3">
                    <div class="col-md-12 text-center">
                        <div class="alert alert-warning alert-dismissible fade show shadow" role="alert">
                            <strong>Note *</strong> Payment is turned off for demo account. Bookings can be found on the top left menu. please press done to continue
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            <?php } ?>


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