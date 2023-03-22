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
    header("location: registeredHome.php");
}

$name = $_SESSION['customername'];
$cid = $_SESSION['customerid'];
$sorigin = $_SESSION['selectedorigin'];
$sdest = $_SESSION['selecteddestination'];
$sdate = $_SESSION['selecteddate'];
$sday = $_SESSION['selectedday'];
$numofpassenger = $_SESSION['selectednumofpassenger'];

$result222 = $conn->query("SELECT a.origin, a.destination, a.duration, a.departure_time, a.routeID,b.id,b.companyID, b.trip_date, b.fare, b.total_seat, b.taken_seat, b.tripID, b.busID, b.terminalID FROM trip a RIGHT OUTER JOIN bus_trip b ON(a.id = b.tripID) WHERE a.origin = '$sorigin' AND a.destination = '$sdest' AND b.trip_date = '$sdate' AND b.driverID IS NOT NULL AND b.conductorID IS NOT NULL AND b.busID IS NOT NULL ORDER BY a.departure_time DESC ");
$tripss = $result222->fetch_all(MYSQLI_ASSOC);
$result2222 = $conn->query("SELECT DISTINCT a.origin, a.destination, a.duration, a.departure_time, a.routeID,b.id,b.companyID, b.trip_date, b.fare, b.total_seat, b.taken_seat, b.tripID, b.busID, b.terminalID FROM trip a RIGHT OUTER JOIN bus_trip b ON(a.id = b.tripID) WHERE a.origin = '$sorigin' AND b.trip_date = '$sdate' AND b.driverID IS NOT NULL AND b.conductorID IS NOT NULL AND b.busID IS NOT NULL ORDER BY a.departure_time DESC ");
$tripsss = $result2222->fetch_all(MYSQLI_ASSOC);



$result = $conn->query("SELECT * FROM trip WHERE origin = '$sorigin' AND destination = '$sdest'");
$trips = $result->fetch_all(MYSQLI_ASSOC);
$result2 = $conn->query("SELECT * FROM bus_trip WHERE trip_date= '$sdate'");
$date = $result2->fetch_all(MYSQLI_ASSOC);
$result3 = $conn->query("SELECT * FROM buses");
$buses = $result3->fetch_all(MYSQLI_ASSOC);
$result4 = $conn->query("SELECT id,companyName FROM user_partner_admin");
$companies = $result4->fetch_all(MYSQLI_ASSOC);

$result1 = $conn->query("SELECT COUNT(id) FROM trip WHERE origin = '$sorigin' AND destination = '$sdest'");
$arrtrips = array_values($result1->fetch_assoc());
// $result3 = $conn->query("SELECT FROM user_partner_admin");
// $compdetails = array_values($result3->fetch_assoc());
// $result4 = $conn->query("SELECT * FROM bus");
// $busdetails = array_values($result4->fetch_assoc());
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
                    <form class="" method="GET">
                        <button type="submit" name="unsetsession" class="btn btn-link navbar-brand text-light"><i class="fas fa-arrow-left mr-2 ml-2"></i></button>
                    </form>
                    <b class="text-light mr-5">Available Trips</b>
                    <div class="mr-5"></div>
                </div>
            </nav>
            <div class="container container-small vertical-center px-4 py-3 shadow" style="background-color: #7390d5;">
                <div class="text-center text-light mt-4" style="font-weight: 600;">
                    <div class="d-flex justify-content-center  fs-4">
                        <div>
                            <i class="fas fa-map-marker-alt mr-1" aria-hidden="true"></i>
                            <span class="mr-3"> <?php echo $sorigin ?> </span>
                        </div>
                        <div>
                            <i class="fas fa-long-arrow-alt-right"></i>
                        </div>
                        <div>
                            <i class="fas fa-location-arrow ml-3 mr-1" aria-hidden="true"></i>
                            <span class=""> <?php echo $sdest ?></span>
                        </div>
                    </div>
                    <div class="mt-1 text-center text-light">
                        <span><small><i class="fas fa-calendar-alt mr-1"></i> <?php echo $sdate ?>, <?php echo $sday ?></small></span>
                    </div>
                </div>
                <div class="row mt-4 mb-2">
                    <div class="col mt-2 px-1">
                        <select class="select form-select text-muted py-1" name="filter_company" id="filter_company" onchange="FetchBusFilter(this.value)" aria-label="Default select example">
                            <option value="" class="text-muted" selected>All Company</option>
                            <?php foreach ($companies as $company) : ?>
                                <option value="<?php echo $company['id'] ?>"><?php echo $company['companyName'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col mt-2 px-1">
                        <select class="select form-select text-muted py-1" name="filter_bustype" id="filter_bustype" aria-label="Default select example">
                            <option value="" class="text-muted" selected>Bus type</option>
                            <?php foreach ($buses as $bus) : ?>
                                <option value="<?php echo $bus['id'] ?>"><?php echo $bus['bus_model'] . ' (' . $bus['total_seat'] . ' seats)' ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col mt-2 px-1">
                        <button type="button" name="filter" id="filter" class="btn btn-info text-light py-1 w-100"><i class="fas fa-filter"></i> Filter</button>
                    </div>
                </div>
            </div>

            <div class="container container-small" id="trips">
                <?php if (!empty($tripss)) { ?>
                    <?php
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

                                if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                    for ($a = 0; $a < count($_SESSION['demo_booking_table']); $a++) {
                                        if ($_SESSION['demo_booking_table'][$a]['companyID'] == $comid) {
                                            $remainingSeat = $remainingSeat - $_SESSION['demo_booking_table'][$a]['number_of_seats'];
                                        }
                                    }
                                }

                                $result12 = $conn->query("SELECT companyName FROM user_partner_admin WHERE id = '$comid'");
                                $compName = array_values($result12->fetch_assoc());
                                $result133 = $conn->query("SELECT seat_type,bus_model FROM buses WHERE id = '$busid'");
                                $compBus = array_values($result133->fetch_assoc());
                    ?>
                                <div class="trps position-relative mt-3 px-3 py-2 shadow rounded border-left border-success bg-white" style="border-width: 4px !important; ">
                                    <div class="row no-gutters">
                                        <div class="col pl-2">

                                            <span class="" style="font-size: 1.2em; font-weight: 300;  color: #7E7E7E"><?php echo $compName[0];; ?></span>

                                        </div>
                                        <div class="col pr-3 ">
                                            <div class="float-right">
                                                <i class="fa fa-check-circle mr-1 text-success" aria-hidden="true" style="font-size: .7em ;"></i>
                                                <span class="text-success" style="font-size: .8em ;line-height: 2.3em; ">
                                                    <?php
                                                    echo $remainingSeat . " available seat";
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-muted"><small style="font-size: .9em; color: #A5A5A5">Departure time: <b><?php echo date('h:i A', strtotime($trip['departure_time'])) ?></b></small></span><br>
                                    <span class="text-muted">
                                        <small style="font-size: .9em; color: #A5A5A5">Bus details: <br>
                                            <b><?php echo $compBus[0] . ' seat - ' . $compBus[1] ?>
                                            </b>
                                        </small>
                                    </span>
                                    <br><br>

                                    <div class="d-flex flex-row">
                                        <span class="" style="font-size: 1.1em; font-weight: 300; line-height: 1.7em; color: #949494">Php <?php echo $trip['fare']; ?></span>
                                        <form action="" method="Post">
                                            <input type="hidden" class="form-control" name="tripsid" value="<?php echo $trip['id']; ?>" readonly>
                                            <input type="hidden" class="form-control" name="tripfare" value="<?php echo $trip['fare']; ?>" readonly>
                                            <input type="hidden" class="form-control" name="busid" value="<?php echo $trip['busID']; ?>" readonly>
                                            <input type="hidden" class="form-control" name="compid" value="<?php echo $trip['companyID']; ?>" readonly>
                                            <input type="hidden" class="form-control" name="tripschedid" value="<?php echo $trip['tripID']; ?>" readonly>
                                            <input type="hidden" class="form-control" name="terminalid" value="<?php echo $trip['terminalID']; ?>" readonly>
                                            <input type="hidden" class="form-control" name="duration" value="<?php echo $trip['duration']; ?>" readonly>
                                            <input type="hidden" class="form-control" name="departure_time" value="<?php echo $trip['departure_time']; ?>" readonly>
                                            <button class="btn btn-link stretched-link" type="submit" name="confirmTrip"></button>
                                        </form>
                                    </div>

                                </div>
                            <?php } else {
                                $comid = $trip['companyID'];
                                $busid = $trip['busID'];

                                if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                    for ($a = 0; $a < count($_SESSION['demo_booking_table']); $a++) {
                                        if ($_SESSION['demo_booking_table'][$a]['companyID'] == $comid) {
                                            $remainingSeat = $remainingSeat - $_SESSION['demo_booking_table'][$a]['number_of_seats'];
                                        }
                                    }
                                }

                                $result12 = $conn->query("SELECT companyName FROM user_partner_admin WHERE id = '$comid'");
                                $compName = array_values($result12->fetch_assoc());
                                $result133 = $conn->query("SELECT seat_type,bus_model FROM buses WHERE id = '$busid'");
                                $compBus = array_values($result133->fetch_assoc());
                            ?>
                                <div class="bg-light position-relative mt-3 px-3 py-2 shadow rounded border-left border-secondary" style="border-width: 4px !important;">
                                    <div class="row no-gutters">
                                        <div class="col pl-2">

                                            <span class="" style="font-size: 1.2em; font-weight: 300;  color: #7E7E7E"><?php echo $compName[0];; ?></span>

                                        </div>
                                        <div class="col pr-3 ">
                                            <div class="float-right">
                                                <i class="fa fa-check-circle mr-1 text-success" aria-hidden="true" style="font-size: .7em ;"></i>
                                                <span class="text-success" style="font-size: .8em ;line-height: 2.3em; ">
                                                    <?php
                                                    echo $remainingSeat . " available seat";
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-muted"><small style="font-size: .9em; color: #A5A5A5">Departure time: <b><?php echo date('h:i A', strtotime($trip['departure_time'])) ?></b></small></span><br>
                                    <span class="text-muted">
                                        <small style="font-size: .9em; color: #A5A5A5">Bus details: <br>
                                            <b><?php echo $compBus[0] . ' seat - ' . $compBus[1] ?>
                                            </b>
                                        </small>
                                    </span>
                                    <br><br>

                                </div>
                            <?php }
                        } else {
                            if ($trip['taken_seat'] != $trip['total_seat'] && $remainingSeat >= $numofpassenger) {
                                $comid = $trip['companyID'];
                                $busid = $trip['busID'];

                                if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                    for ($a = 0; $a < count($_SESSION['demo_booking_table']); $a++) {
                                        if ($_SESSION['demo_booking_table'][$a]['companyID'] == $comid) {
                                            $remainingSeat = $remainingSeat - $_SESSION['demo_booking_table'][$a]['number_of_seats'];
                                        }
                                    }
                                }

                                $result12 = $conn->query("SELECT companyName FROM user_partner_admin WHERE id = '$comid'");
                                $compName = array_values($result12->fetch_assoc());
                                $result133 = $conn->query("SELECT seat_type,bus_model FROM buses WHERE id = '$busid'");
                                $compBus = array_values($result133->fetch_assoc());
                            ?>
                                <div class="trps position-relative mt-3 px-3 py-2 shadow rounded border-left border-success bg-white" style="border-width: 4px !important; ">
                                    <div class="row no-gutters">
                                        <div class="col pl-2">

                                            <span class="" style="font-size: 1.2em; font-weight: 300;  color: #7E7E7E"><?php echo $compName[0]; ?></span>

                                        </div>
                                        <div class="col pr-3 ">
                                            <div class="float-right">
                                                <i class="fa fa-check-circle mr-1 text-success" aria-hidden="true" style="font-size: .7em ;"></i>
                                                <span class="text-success" style="font-size: .8em ;line-height: 2.3em; ">
                                                    <?php
                                                    echo $remainingSeat . " avalable seat";
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-muted"><small style="font-size: .9em; color: #A5A5A5">Departure time: <b><?php echo date('h:i A', strtotime($trip['departure_time'])) ?></b></small></span><br>
                                    <span class="text-muted">
                                        <small style="font-size: .9em; color: #A5A5A5">Bus details: <br>
                                            <b><?php echo $compBus[0] . ' seat - ' . $compBus[1] ?>
                                            </b>
                                        </small>
                                    </span>
                                    <br><br>

                                    <!-- <span class="" style="font-size: .9em; color: #A5A5A5"><?php echo $trip['origin']; ?> - <?php echo $trip['destination'] ?></span><br><br> -->

                                    <div class="d-flex flex-row">
                                        <span class="" style="font-size: 1.1em; font-weight: 300; line-height: 1.7em; color: #949494">Php <?php echo $trip['fare']; ?></span>
                                        <form action="" method="Post">
                                            <input type="hidden" class="form-control" name="tripsid" value="<?php echo $trip['id']; ?>" readonly>
                                            <input type="hidden" class="form-control" name="tripfare" value="<?php echo $trip['fare']; ?>" readonly>
                                            <input type="hidden" class="form-control" name="busid" value="<?php echo $trip['busID']; ?>" readonly>
                                            <input type="hidden" class="form-control" name="compid" value="<?php echo $trip['companyID']; ?>" readonly>
                                            <input type="hidden" class="form-control" name="tripschedid" value="<?php echo $trip['tripID']; ?>" readonly>
                                            <input type="hidden" class="form-control" name="terminalid" value="<?php echo $trip['terminalID']; ?>" readonly>
                                            <input type="hidden" class="form-control" name="duration" value="<?php echo $trip['duration']; ?>" readonly>
                                            <input type="hidden" class="form-control" name="departure_time" value="<?php echo $trip['departure_time']; ?>" readonly>
                                            <button class="btn btn-link stretched-link" type="submit" name="confirmTrip"></button>
                                        </form>
                                    </div>

                                </div>
                            <?php } else {
                                $comid = $trip['companyID'];
                                $busid = $trip['busID'];

                                if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                    for ($a = 0; $a < count($_SESSION['demo_booking_table']); $a++) {
                                        if ($_SESSION['demo_booking_table'][$a]['companyID'] == $comid) {
                                            $remainingSeat = $remainingSeat - $_SESSION['demo_booking_table'][$a]['number_of_seats'];
                                        }
                                    }
                                }

                                $result12 = $conn->query("SELECT companyName FROM user_partner_admin WHERE id = '$comid'");
                                $compName = array_values($result12->fetch_assoc());
                                $result133 = $conn->query("SELECT seat_type,bus_model FROM buses WHERE id = '$busid'");
                                $compBus = array_values($result133->fetch_assoc());
                            ?>
                                <div class="bg-light position-relative mt-3 mx-3 px-3 py-2 shadow rounded border-left border-secondary" style="border-width: 4px !important;">
                                    <div class="row no-gutters">
                                        <div class="col pl-2">

                                            <span class="" style="font-size: 1.2em; font-weight: 300;  color: #7E7E7E"><?php echo $compName[0];; ?></span>

                                        </div>
                                        <div class="col pr-3 ">
                                            <div class="float-right">
                                                <i class="fa fa-check-circle mr-1 text-success" aria-hidden="true" style="font-size: .7em ;"></i>
                                                <span class="text-success" style="font-size: .8em ;line-height: 2.3em; ">
                                                    <?php
                                                    echo $remainingSeat . " avalable seat";
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-muted"><small style="font-size: .9em; color: #A5A5A5">Departure time: <b><?php echo date('h:i A', strtotime($trip['departure_time'])) ?></b></small></span><br>
                                    <span class="text-muted">
                                        <small style="font-size: .9em; color: #A5A5A5">Bus details: <br>
                                            <b><?php echo $compBus[0] . ' seat - ' . $compBus[1] ?>
                                            </b>
                                        </small>
                                    </span>
                                    <br><br>

                                </div>
                    <?php }
                        }
                    }
                } else { ?>
                    <div class="no-gutters position-relative mt-3 shadow rounded" style="background-color:#F44E4E;">
                        <div class="text-center text-light p-3">
                            <?php echo "No Available Trips"; ?>
                        </div>
                    </div>
                    <h5 class="mt-3">Suggested</h5>
                    <div class="">
                        <?php
                        if (!empty($tripsss)) {
                            foreach ($tripsss as $tripi) {
                                echo '<li>' . $tripi['origin'] . ' - ' . $tripi['destination'] . '</li>';
                            }
                        } else { ?>
                        <?php
                            $result226 = $conn->query("SELECT DISTINCT in_between_address FROM in_between WHERE `origin` = '$sorigin' LIMIT 6");
                            $in_between_addressess = $result226->fetch_all(MYSQLI_ASSOC);
                            foreach ($in_between_addressess as $in_betweens) {
                                echo '<li>' . $sorigin . ' - ' . $in_betweens['in_between_address'] . '</li>';
                            }
                        }
                        ?>
                    </div>
                <?php } ?>
            </div>

        </div>
    </div>

    <div class="overlay"></div>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <!-- jQuery Custom Scroller CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>

    <script type="text/javascript">
        $('#mymodalconfirmtrip').on('show.bs.modal', function(e) {
            var hid = $(e.relatedTarget).data('userid');
            var id = hid;
            var tripid = $('#tripid' + id).text();
            var compid = $('#compid' + id).text();
            $(e.currentTarget).find('input[name="compid"]').val(compid);
            $(e.currentTarget).find('input[name="tripid"]').val(tripid);
            // $('#pTest').text(fn);
            // $('#busidtext').text(busid);
        });

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

<script>
    function FetchBusFilter(id) {
        $('#filter_bustype').html('');
        if (id == '') {
            // alert(id);
            $.ajax({
                type: 'post',
                url: 'extensions/fetchbusfilter.php',
                data: {
                    'id': id
                },
                success: function(data) {
                    $('#filter_bustype').html(data);
                }
            })
        } else {
            $.ajax({
                type: 'post',
                url: 'extensions/fetchbusfilter.php',
                data: {
                    'id': id
                },
                success: function(data) {
                    $('#filter_bustype').html(data);
                }
            })
        }
    }

    $('#filter').click(function() {
        $('#trips').html('');
        var filter_companyid = $("#filter_company :selected").val();
        var filter_busid = $("#filter_bustype :selected").val();
        // var filter_date = $("#filter_date").val();
        if (filter_companyid != '' && filter_busid == '') {
            $.ajax({
                type: 'post',
                url: 'extensions/fetch_filtered_trips.php',
                data: {
                    companyid: filter_companyid,
                    busid: filter_busid
                },
                success: function(data) {
                    $('#trips').html(data);
                }
            })
        } else if (filter_companyid == '' && filter_busid != '') {
            // alert("hello");
            $.ajax({
                type: 'post',
                url: 'extensions/fetch_filtered_trips.php',
                data: {
                    companyid: filter_companyid,
                    busid: filter_busid
                },
                success: function(data) {
                    $('#trips').html(data);
                }
            })
        } else if (filter_companyid != '' && filter_busid != '') {
            $.ajax({
                type: 'post',
                url: 'extensions/fetch_filtered_trips.php',
                data: {
                    companyid: filter_companyid,
                    busid: filter_busid
                },
                success: function(data) {
                    $('#trips').html(data);
                }
            })
        } else if (filter_companyid == '' && filter_busid == '') {
            $.ajax({
                type: 'post',
                url: 'extensions/fetch_filtered_trips.php',
                data: {
                    companyid: filter_companyid,
                    busid: filter_busid
                },
                success: function(data) {
                    $('#trips').html(data);
                }
            })
        }
    });
</script>

<!-- <?php $busIDD = "<script>document.write(busid)</script>" ?>   -->