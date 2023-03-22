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
    unset($_SESSION['selectedtripid']);
    unset($_SESSION['selectedbusid']);
    unset($_SESSION['selectedcompid']);
    unset($_SESSION['selectedtsid']);
    unset($_SESSION['selectedfare']);
    unset($_SESSION['selectedterminalid']);
    unset($_SESSION['selectedduration']);
    unset($_SESSION['selecteddeparture_time']);
    header("location: availableTrips.php");
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
$numofpassenger = $_SESSION['selectednumofpassenger'];

$result = $conn->query("SELECT * FROM guidelines WHERE companyID = '$scompid'");
$guides = $result->fetch_all(MYSQLI_ASSOC);
$result1 = $conn->query("SELECT companyName, contactNumber, email, companyAddress FROM user_partner_admin WHERE id = '$scompid'");
$compDetails = $result1->fetch_all(MYSQLI_ASSOC);
$count = $conn->query("SELECT seat_type,bus_model,busNumber FROM buses WHERE `id`='$sbusid' AND `companyID`='$scompid' limit 1") or die($mysql->connect_error);
$busNumber = array_values($count->fetch_assoc());
$ree = $conn->query("SELECT * FROM bus_trip WHERE companyID = '$scompid' AND id = '$stripid'");
$scheds = $ree->fetch_all(MYSQLI_ASSOC);


$result222 = $conn->query("SELECT a.origin, a.destination, a.duration, a.departure_time, a.routeID,b.id,b.companyID, b.trip_date, b.fare, b.total_seat, b.taken_seat, b.tripID, b.busID, b.terminalID FROM trip a RIGHT OUTER JOIN bus_trip b ON(a.id = b.tripID) WHERE b.id = '$stripid' AND b.companyID = '$scompid'");
$tripss = $result222->fetch_all(MYSQLI_ASSOC);


$numItems = count($guides);
$ni = 0;

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
        <!-- Page Content  -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light " style="background-color: #3b55d9;">
                <div class="container-fluid">
                    <form class="px-0" method="GET">
                        <button type="submit" name="unsetsession" class="btn btn-link navbar-brand text-light px-0"><i class="fas fa-arrow-left mr-2 ml-2"></i> <b> Go back</b></button>
                    </form>
                </div>
            </nav>
            <div class="container container-small">
                <div class="vertical-center px-2 py-2 mt-2 rounded-top shadow" style="background-color: #7980E9;">
                    <?php foreach ($compDetails as $cdet) { ?>
                        <span class="text-light mx-2" style="font-size: 20px; font-weight: 600;"><?php echo $cdet['companyName'] ?></span>
                    <?php } ?>
                </div>
                <div class="bg-light rounded-bottom shadow py-2">
                    <div class="text-center px-3" style="color: #3b55d9;">

                        <div class="d-flex justify-content-between mt-3">
                            <div>
                                <i class="fas fa-map-marker-alt" aria-hidden="true"></i><span class="mr-3"><?php echo $sorigin ?></span>
                            </div>
                            <div>
                                <i class="fas mx-3 fa-long-arrow-alt-right"></i>
                            </div>
                            <div>
                                <i class="fas fa-location-arrow" aria-hidden="true"></i><span class=""><?php echo $sdest ?></span>
                            </div>
                        </div>

                        <?php foreach ($tripss as $trip) { ?>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <small class="text-secondary"><?php echo date('h:i A', strtotime($trip["departure_time"])) ?></small>
                                </div>
                                <div>
                                    <?php
                                    $pieces = explode(":", $trip['duration']);
                                    $add = '+' . $pieces[0] . ' hour +' . $pieces[1] . ' minutes';
                                    ?>
                                    <small class="mr-2 ml-2 text-secondary"><?php echo $pieces[0]; ?>h <?php echo $pieces[1]; ?>m</small>
                                </div>
                                <div>
                                    <small class="text-secondary"><?php echo date('h:i A', strtotime($add, strtotime($trip["departure_time"]))); ?></small>
                                </div>
                            </div>

                        <?php } ?>

                        <div class="mt-3 text-center text-secondary">
                            <span><small style="font-weight: 600;color: #3b55d9;"><i class="fas fa-calendar-alt mr-1"></i> <?php echo $sdate ?>, <?php echo $sday ?></small></span>
                        </div>
                    </div>
                    <div class="container">
                        <hr>
                    </div>
                    <div class="row mx-2">
                        <p class="t mt-2">
                            <small class="" style="font-weight: 600;"><span class="" style="color: #545454;">Bus #: </span><?php echo $busNumber[2] ?></small><br>
                            <?php foreach ($tripss as $sched) { ?>
                                <small class="" style="font-weight: 600;"><span class="" style="color: #545454;">Available Seat: </span><?php echo $sched['total_seat'] - $sched['taken_seat']; ?></small>
                            <?php }
                            echo '<small><b> | ' . $busNumber[0] . ' seat config</b></small>' ?><br>
                            <small class="" style="font-weight: 600;"><span class="" style="color: #545454;">Model: </span><?php echo $busNumber[1] ?></small><br>
                            <?php foreach ($tripss as $trip) : ?>
                                <small class="" style="font-weight: 600;"><span class="" style="color: #545454;">Fare: </span>Php <?php echo $trip['fare'] ?></small>
                            <?php endforeach; ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="container container-small px-4 py-4 mb-5">
                <div class="px-4 py-2 rounded-top bg-warning">
                    <span class="text-light font-weight-bold">Guidelines</span>
                </div>
                <div class="container border border-warning bg-light pt-3 pb-1 rounded-bottom shadow mb-5" style="border:2px solid #7980E9;">
                    <?php foreach ($guides as $guide) {  ?>
                        <ul>
                            <li class="font-weight-bold text-secondary " style="font-size: 15px;"><?php echo $guide['guideline']; ?></li>
                        </ul>
                        <?php
                        if (++$ni != $numItems) { ?>
                            <hr style="background-color: #7390d5;">
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>

            <div class="container container-small p-0 fixed-bottom">
                <div class="row bg-light shadow py-3">
                    <div class="col">
                        <?php
                        if ($numofpassenger == 1) {
                            echo "<small class='mt-1 ml-3 text-muted' >Select seat for " . $numofpassenger . " passenger</small>";
                        } else {
                            echo "<small class='mt-1 ml-3 text-muted'><b>Select seats for " . $numofpassenger . " passenger</b></small>";
                        }

                        foreach ($tripss as $trip) {
                            echo '<br><b class="ml-3"style="color: #3b55d9;">Total: Php ' . $trip['fare'] * $numofpassenger . '</b>';
                        }
                        ?>

                    </div>
                    <div class="col-1">
                        <a href="selectSeat.php" class="btn btn-lg float-right shadow rounded-pill text-light mr-3" style="background-color: #3b55d9;">Select a Seat</a>
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