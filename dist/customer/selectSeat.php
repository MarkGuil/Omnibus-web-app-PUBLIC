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
if (!isset($_SESSION['selectedorigin']) || !isset($_SESSION['selecteddestination']) || !isset($_SESSION['selecteddate']) || !isset($_SESSION['selectedday'])) {
    header('location: registeredHome.php');
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
$sorigin = $_SESSION['selectedorigin'];
$sdest = $_SESSION['selecteddestination'];
$sdate = $_SESSION['selecteddate'];
$sday = $_SESSION['selectedday'];
$scompid = $_SESSION['selectedcompid'];
$sbusid = $_SESSION['selectedbusid'];
$sstid = $_SESSION['selectedtsid'];
$numofpassenger = $_SESSION['selectednumofpassenger'];
$sbustripid = $_SESSION['selectedtripid'];

$result = $conn->query("SELECT * FROM guidelines WHERE companyID = '$scompid'");
$guides = $result->fetch_all(MYSQLI_ASSOC);
$result1 = $conn->query("SELECT total_seat FROM bus_trip WHERE id='$sbustripid' limit 1") or die($mysql->connect_error);
$totalSeats = array_values($result1->fetch_assoc());
$count = $conn->query("SELECT protocol FROM buses WHERE `id`='$sbusid' AND `companyID`='$scompid' limit 1") or die($mysql->connect_error);
$protocol = array_values($count->fetch_assoc());

$result222 = $conn->query("SELECT a.id,a.companyID, b.seat_number FROM booking_tbl a RIGHT OUTER JOIN customer_booking_details b ON(a.id = b.bookingID) WHERE a.companyID = '$scompid' AND a.bustripID = '$sbustripid' AND a.booking_status <> 'cancelled'");
$seatss = $result222->fetch_all(MYSQLI_ASSOC);

$taken_seat_number = array();
$demo_bustrip_ID = array();
$demo_number_of_seats = 0;
if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
    for ($a = 0; $a < count($_SESSION['demo_booking_table']); $a++) {
        if ($_SESSION['demo_booking_table'][$a]['bustripID'] == $sbustripid && $_SESSION['demo_booking_table'][$a]['booking_status'] != 'cancelled') {
            array_push($demo_bustrip_ID, $a);
            $demo_number_of_seats = $_SESSION['demo_booking_table'][$a]['number_of_seats'];
        }
    }
    for ($a = 0; $a < count($_SESSION['demo_booking_detail']); $a++) {
        foreach ($demo_bustrip_ID as $dbID) {
            if ($dbID == $_SESSION['demo_booking_detail'][$a]['bookingID']) {
                array_push($taken_seat_number, $_SESSION['demo_booking_detail'][$a]['seat_number']);
            }
        }
    }
}

$continue4 = $totalSeats[0] - 6;
$continue = $totalSeats[0] - 5;
$continue2 = $totalSeats[0] - 4;
$continue3 = $totalSeats[0] - 3;
$unavailable = 2;
$unavailable2 = 1;
$unavailable3 = 6;
$otherend = 8;
$space = 2;
$end = 4;
$start = 1;
$start2 = 4;
$space2 = 3;
$end2 = 5;
$space3 = 2;
$end3 = 3;



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
                    <a class="navbar-brand text-light" href="confirmTrip.php"><i class="fas fa-arrow-left mr-2 ml-2"></i></a><b class="text-light mr-5">Select Seat</b>
                    <div></div>
                </div>
            </nav>

            <div class="container vertical-center px-5 py-1 shadow" style="background-color: #7390d5;">
                <p class="text-center text-dark pt-2">
                    <span class="text-light">Legends</span>
                <div class="row text-center">
                    <div class="col">
                        <i class="fa fa-square fa-2x text-light" aria-hidden="true"></i>
                        <p class="text-light" style="font-size: 16px; font-weight: bold;">Available</p>
                    </div>
                    <div class="col">
                        <i class="fa fa-square fa-2x text-danger" aria-hidden="true"></i>
                        <p class="text-light" style="font-size: 16px; font-weight: bold;">Booked</p>
                    </div>
                    <div class="col">
                        <i class="fa fa-square fa-2x text-success" aria-hidden="true"></i>
                        <p class="text-light" style="font-size: 16px; font-weight: bold;">Selected</p>
                    </div>
                </div>
                </p>
            </div>

            <div class="container container-small px-3 pb-2 mb-0 ">
                <?php
                if ($totalSeats[0] == 29) {
                    if ($protocol[0] == "On") {
                ?>
                        <div class="container bg-light py-4 shadow border border-secondary rounded-bottom">
                            <img src="image/whl.png" alt="" class="ml-3 mb-4" style="width: 43px; height: 43px;">
                            <div class="row text-center">
                                <?php
                                for ($i = 1; $i <= ($totalSeats[0] - 4); $i++) {
                                ?>
                                    <?php
                                    if ($i != $end3) {
                                        if ($i == 16) {
                                            if ($i == $unavailable2) {
                                                echo '<button class="btn btn-lg btn-outline-dark bg-dark py-3" id="idm' . $i . '" value="' . $i . '" style="background-color: white;" disabled></button></div><div class="col"><b>Room</b></div></div> <div class="row text-center mt-4">';
                                                $unavailable2 = $unavailable2 + 1;
                                            } else {
                                                $counter = 0;
                                                foreach ($seatss as $seat) {
                                                    if ($i == $seat['seat_number']) {
                                                        $counter++;
                                                    }
                                                }
                                                if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                    foreach ($taken_seat_number as $seat_n) {
                                                        if ($i == $seat_n) {
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                if ($counter > 0) {
                                                    echo '<button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1" id="idm' . $i . '" value="' . $i . '" style="background-color: white;" disabled>' . sprintf("%02d", $i) . '</button></div><div class="col"><b>Room</b></div></div> <div class="row text-center mt-4">';
                                                } else {
                                                    echo '<button class="btn btn-lg btn-outline-dark py-0 px-1" id="idm' . $i . '" value="' . $i . '" style="background-color: white;">' . sprintf("%02d", $i) . '</button></div><div class="col"><b>Room</b></div></div> <div class="row text-center mt-4">';
                                                }
                                            }
                                            $space3 = $space3 + 1;
                                            $end3 = $end3 + 1;
                                        } else if ($i == $space3) {
                                            if ($i == $unavailable2) {
                                                echo '<button class="btn btn-lg bg-dark btn-outline-dark  py-3" id="idm' . $i . '"  value="' . $i . '" style="background-color: white;" disabled></button></div>';
                                                $unavailable2 = $unavailable2 + 2;
                                            } else {
                                                $counter = 0;
                                                foreach ($seatss as $seat) {
                                                    if ($i == $seat['seat_number']) {
                                                        $counter++;
                                                    }
                                                }
                                                if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                    foreach ($taken_seat_number as $seat_n) {
                                                        if ($i == $seat_n) {
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                if ($counter > 0) {
                                                    echo '<button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1" id="idm' . $i . '"  value="' . $i . '" style="background-color: white;" disabled>' . sprintf("%02d", $i) . '</button></div>';
                                                } else {
                                                    echo '<button class="btn btn-lg btn-outline-dark py-0 px-1" id="idm' . $i . '"  value="' . $i . '" style="background-color: white;">' . sprintf("%02d", $i) . '</button></div>';
                                                }
                                            }
                                            $space3 = $space3 + 3;
                                        } else {
                                            if ($i == $unavailable2) {
                                                echo '<div class="col"><button class="btn btn-lg btn-outline-dark bg-dark py-3 mr-3" id="idm' . $i . '"  value="' . $i . '" style="background-color: white;" disabled></button>';
                                                $unavailable2 = $unavailable2 + 2;
                                            } else {
                                                $counter = 0;
                                                foreach ($seatss as $seat) {
                                                    if ($i == $seat['seat_number']) {
                                                        $counter++;
                                                    }
                                                }
                                                if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                    foreach ($taken_seat_number as $seat_n) {
                                                        if ($i == $seat_n) {
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                if ($counter > 0) {
                                                    echo '<div class="col"><button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1 mr-3" id="idm' . $i . '"  value="' . $i . '" style="background-color: white;" disabled>' . sprintf("%02d", $i) . '</button>';
                                                } else {
                                                    echo '<div class="col"><button class="btn btn-lg btn-outline-dark py-0 px-1 mr-3" id="idm' . $i . '"  value="' . $i . '" style="background-color: white;">' . sprintf("%02d", $i) . '</button>';
                                                }
                                            }
                                        }
                                    } else {
                                        if ($i == 15) {
                                            if ($i == $unavailable2) {
                                                echo '<div class="col px-2"><b>Comfort</b></div></div> <div class="row text-center mt-4"><div class="col"><button class="btn btn-lg btn-outline-dark  py-0 px-1 mr-3" id="idm' . $i . '" value="' . $i . '" style="background-color: white;">' . sprintf("%02d", $i) . '</button>';
                                                $unavailable2 = $unavailable2 + 1;
                                            } else {
                                                $counter = 0;
                                                foreach ($seatss as $seat) {
                                                    if ($i == $seat['seat_number']) {
                                                        $counter++;
                                                    }
                                                }
                                                if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                    foreach ($taken_seat_number as $seat_n) {
                                                        if ($i == $seat_n) {
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                if ($counter > 0) {
                                                    echo '<div class="col px-2"><b>Comfort</b></div></div> <div class="row text-center mt-4"><div class="col"><button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1 mr-3" id="idm' . $i . '" value="' . $i . '" style="background-color: white;" disabled>' . sprintf("%02d", $i) . '</button>';
                                                } else {
                                                    echo '<div class="col px-2"><b>Comfort</b></div></div> <div class="row text-center mt-4"><div class="col"><button class="btn btn-lg btn-outline-dark py-0 px-1 mr-3" id="idm' . $i . '" value="' . $i . '" style="background-color: white;">' . sprintf("%02d", $i) . '</button>';
                                                }
                                            }
                                        } else {
                                            if ($i == $unavailable2) {
                                                echo '<div class="col"><button class="btn btn-lg btn-outline-dark bg-dark py-3" id="idm' . $i . '" value="' . $i . '" style="background-color: white;" disabled></button></div></div> <div class="row text-center mt-4">';
                                                $unavailable2 = $unavailable2 + 2;
                                            } else {
                                                $counter = 0;
                                                foreach ($seatss as $seat) {
                                                    if ($i == $seat['seat_number']) {
                                                        $counter++;
                                                    }
                                                }
                                                if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                    foreach ($taken_seat_number as $seat_n) {
                                                        if ($i == $seat_n) {
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                if ($counter > 0) {
                                                    echo '<div class="col"><button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1" id="idm' . $i . '" value="' . $i . '" style="background-color: white;" disabled>' . sprintf("%02d", $i) . '</button></div></div> <div class="row text-center mt-4">';
                                                } else {
                                                    echo '<div class="col"><button class="btn btn-lg btn-outline-dark py-0 px-1" id="idm' . $i . '" value="' . $i . '" style="background-color: white;">' . sprintf("%02d", $i) . '</button></div></div> <div class="row text-center mt-4">';
                                                }
                                            }
                                        }
                                        $end3 = $end3 + 3;
                                    }
                                    ?>
                                <?php } ?>
                                <div class="row p-0">
                                    <div class="col mt-4">
                                        <?php for ($i = $continue3; $i <= ($continue3 + 2); $i++) {
                                            if ($i == $unavailable2) {
                                        ?>
                                                <button class="btn btn-lg btn-outline-dark bg-dark py-3 ml-3" id="<?php echo 'idm' . $i; ?>" value="<?php echo $i; ?>" style="background-color: white;" disabled></button>
                                                <?php $unavailable2 = $unavailable2 + 1;
                                            } else {
                                                $counter = 0;
                                                foreach ($seatss as $seat) {
                                                    if ($i == $seat['seat_number']) {
                                                        $counter++;
                                                    }
                                                }
                                                if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                    foreach ($taken_seat_number as $seat_n) {
                                                        if ($i == $seat_n) {
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                if ($counter > 0) { ?>
                                                    <button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1 ml-3" id="<?php echo 'idm' . $i; ?>" value="<?php echo $i; ?>" style="background-color: white;" disabled><?php echo sprintf("%02d", $i) ?></button>
                                                <?php } else { ?>
                                                    <button class="btn btn-lg btn-outline-dark py-0 px-1 ml-3" id="<?php echo 'idm' . $i; ?>" value="<?php echo $i; ?>" style="background-color: white;"><?php echo sprintf("%02d", $i) ?></button>
                                        <?php }
                                            }
                                        } ?>
                                        <?php $counter = 0;
                                        foreach ($seatss as $seat) {
                                            if ($totalSeats[0] == $seat['seat_number']) {
                                                $counter++;
                                            }
                                        }
                                        if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                            foreach ($taken_seat_number as $seat_n) {
                                                if ($i == $seat_n) {
                                                    $counter++;
                                                }
                                            }
                                        }
                                        if ($counter > 0) { ?>
                                            <button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1 ml-3" id="<?php echo 'idm' . $totalSeats[0]; ?>" value="<?php echo $totalSeats[0]; ?>" style="background-color: white;" disabled><?php echo sprintf("%02d", $totalSeats[0]) ?></button>
                                        <?php } else { ?>
                                            <button class="btn btn-lg btn-outline-dark py-0 px-1 ml-3" id="<?php echo 'idm' . $totalSeats[0]; ?>" value="<?php echo $totalSeats[0]; ?>" style="background-color: white;"><?php echo sprintf("%02d", $totalSeats[0]) ?></button>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                    if ($protocol[0] == "Off") {  ?>
                        <div class="container bg-light py-4 shadow border border-secondary rounded-bottom">
                            <img src="image/whl.png" alt="" class="ml-3 mb-4" style="width: 43px; height: 43px;">
                            <div class="row text-center">
                                <?php
                                for ($i = 1; $i <= ($totalSeats[0] - 4); $i++) {
                                ?>
                                    <?php
                                    if ($i != $end3) {
                                        if ($i == 16) {
                                            $counter = 0;
                                            foreach ($seatss as $seat) {
                                                if ($i == $seat['seat_number']) {
                                                    $counter++;
                                                }
                                            }
                                            if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                foreach ($taken_seat_number as $seat_n) {
                                                    if ($i == $seat_n) {
                                                        $counter++;
                                                    }
                                                }
                                            }
                                            if ($counter > 0) {
                                                echo '<button class="btn btn-lg btn-outline-dark bg-danger  py-0 px-1" id="idm' . $i . '" value="' . $i . '" style="background-color: white;" disabled>' . sprintf("%02d", $i) . '</button></div><div class="col"><b>Room</b></div></div> <div class="row text-center mt-4">';
                                            } else {
                                                echo '<button class="btn btn-lg btn-outline-dark  py-0 px-1" id="idm' . $i . '" value="' . $i . '" style="background-color: white;">' . sprintf("%02d", $i) . '</button></div><div class="col"><b>Room</b></div></div> <div class="row text-center mt-4">';
                                            }
                                            $space3 = $space3 + 1;
                                            $end3 = $end3 + 1;
                                        } else if ($i == $space3) {
                                            $counter = 0;
                                            foreach ($seatss as $seat) {
                                                if ($i == $seat['seat_number']) {
                                                    $counter++;
                                                }
                                            }
                                            if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                foreach ($taken_seat_number as $seat_n) {
                                                    if ($i == $seat_n) {
                                                        $counter++;
                                                    }
                                                }
                                            }
                                            if ($counter > 0) {
                                                echo '<button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1" id="idm' . $i . '"  value="' . $i . '" style="background-color: white;" disabled>' . sprintf("%02d", $i) . '</button></div>';
                                            } else {
                                                echo '<button class="btn btn-lg btn-outline-dark  py-0 px-1" id="idm' . $i . '"  value="' . $i . '" style="background-color: white;">' . sprintf("%02d", $i) . '</button></div>';
                                            }
                                            $space3 = $space3 + 3;
                                        } else {
                                            $counter = 0;
                                            foreach ($seatss as $seat) {
                                                if ($i == $seat['seat_number']) {
                                                    $counter++;
                                                }
                                            }
                                            if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                foreach ($taken_seat_number as $seat_n) {
                                                    if ($i == $seat_n) {
                                                        $counter++;
                                                    }
                                                }
                                            }
                                            if ($counter > 0) {
                                                echo '<div class="col"><button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1 mr-3" id="idm' . $i . '"  value="' . $i . '" style="background-color: white;" disabled>' . sprintf("%02d", $i) . '</button>';
                                            } else {
                                                echo '<div class="col"><button class="btn btn-lg btn-outline-dark  py-0 px-1 mr-3" id="idm' . $i . '"  value="' . $i . '" style="background-color: white;">' . sprintf("%02d", $i) . '</button>';
                                            }
                                        }
                                    } else {
                                        if ($i == 15) {
                                            $counter = 0;
                                            foreach ($seatss as $seat) {
                                                if ($i == $seat['seat_number']) {
                                                    $counter++;
                                                }
                                            }
                                            if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                foreach ($taken_seat_number as $seat_n) {
                                                    if ($i == $seat_n) {
                                                        $counter++;
                                                    }
                                                }
                                            }
                                            if ($counter > 0) {
                                                echo '<div class="col px-2"><b>Comfort</b></div></div> <div class="row text-center mt-4"><div class="col"><button class="btn btn-lg bg-danger btn-outline-dark py-0 px-1 mr-3" id="idm' . $i . '" value="' . $i . '" style="background-color: white;" disabled>' . sprintf("%02d", $i) . '</button>';
                                            } else {
                                                echo '<div class="col px-2"><b>Comfort</b></div></div> <div class="row text-center mt-4"><div class="col"><button class="btn btn-lg btn-outline-dark py-0 px-1 mr-3" id="idm' . $i . '" value="' . $i . '" style="background-color: white;">' . sprintf("%02d", $i) . '</button>';
                                            }
                                            // $space3 = $space3 -1;
                                        } else {
                                            $counter = 0;
                                            foreach ($seatss as $seat) {
                                                if ($i == $seat['seat_number']) {
                                                    $counter++;
                                                }
                                            }
                                            if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                foreach ($taken_seat_number as $seat_n) {
                                                    if ($i == $seat_n) {
                                                        $counter++;
                                                    }
                                                }
                                            }
                                            if ($counter > 0) {
                                                echo '<div class="col"><button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1" id="idm' . $i . '" value="' . $i . '" style="background-color: white;" disabled>' . sprintf("%02d", $i) . '</button></div></div> <div class="row text-center mt-4">';
                                            } else {
                                                echo '<div class="col"><button class="btn btn-lg btn-outline-dark py-0 px-1" id="idm' . $i . '" value="' . $i . '" style="background-color: white;">' . sprintf("%02d", $i) . '</button></div></div> <div class="row text-center mt-4">';
                                            }
                                        }
                                        $end3 = $end3 + 3;
                                    }
                                    ?>
                                <?php } ?>
                                <div class="row p-0">
                                    <div class="col mt-4">
                                        <?php for ($i = $continue3; $i <= ($continue3 + 3); $i++) {
                                            $counter = 0;
                                            foreach ($seatss as $seat) {
                                                if ($i == $seat['seat_number']) {
                                                    $counter++;
                                                }
                                            }
                                            if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                foreach ($taken_seat_number as $seat_n) {
                                                    if ($i == $seat_n) {
                                                        $counter++;
                                                    }
                                                }
                                            }
                                            if ($counter > 0) {
                                        ?>
                                                <button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1 ml-3" id="<?php echo 'idm' . $i; ?>" value="<?php echo $i; ?>" style="background-color: white;" disabled><?php echo sprintf("%02d", $i) ?></button>
                                            <?php } else { ?>
                                                <button class="btn btn-lg btn-outline-dark  py-0 px-1 ml-3" id="<?php echo 'idm' . $i; ?>" value="<?php echo $i; ?>" style="background-color: white;"><?php echo sprintf("%02d", $i) ?></button>
                                        <?php }
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                }
                if ($totalSeats[0] == 45 || $totalSeats[0] == 49) {
                    if ($protocol[0] == "On") {
                    ?>
                        <div class="container bg-light py-4 shadow border border-secondary rounded-bottom">
                            <img src="image/whl.png" alt="" class="ml-3 mb-4" style="width: 43px; height: 43px;">
                            <div class="row text-center">
                                <?php
                                for ($i = 1; $i <= ($totalSeats[0] - 5); $i++) {
                                ?>
                                    <?php
                                    if ($i != $end) {
                                        if ($i == $space) {
                                            if ($i == $unavailable2) {
                                                echo '<button class="btn btn-lg bg-dark btn-outline-dark  py-3" id="idm' . $i . '"  value="' . $i . '" style="background-color: white;" disabled></button></div>';
                                                $unavailable2 = $unavailable2 + 2;
                                            } else {
                                                $counter = 0;
                                                foreach ($seatss as $seat) {
                                                    if ($i == $seat['seat_number']) {
                                                        $counter++;
                                                    }
                                                }
                                                if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                    foreach ($taken_seat_number as $seat_n) {
                                                        if ($i == $seat_n) {
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                if ($counter > 0) {
                                                    echo '<button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1" id="idm' . $i . '"  value="' . $i . '" style="background-color: white;">' . sprintf("%02d", $i) . '</button></div>';
                                                } else {
                                                    echo '<button class="btn btn-lg btn-outline-dark py-0 px-1" id="idm' . $i . '"  value="' . $i . '" style="background-color: white;" >' . sprintf("%02d", $i) . '</button></div>';
                                                }
                                            }
                                            $space = $space + 4;
                                            //    $unavailable2=$unavailable2+1;
                                        } else {
                                            if ($i == $unavailable2) {
                                                echo '<div class="col"><button class="btn btn-lg bg-dark btn-outline-dark  py-3 mr-3" id="idm' . $i . '"  value="' . $i . '" style="background-color: white;" disabled></button>';
                                                $unavailable2 = $unavailable2 + 2;
                                            } else {
                                                $counter = 0;
                                                foreach ($seatss as $seat) {
                                                    if ($i == $seat['seat_number']) {
                                                        $counter++;
                                                    }
                                                }
                                                if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                    foreach ($taken_seat_number as $seat_n) {
                                                        if ($i == $seat_n) {
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                if ($counter > 0) {
                                                    echo '<div class="col"><button class="btn btn-lg bg-danger btn-outline-dark py-0 px-1 mr-3" id="idm' . $i . '"  value="' . $i . '" style="background-color: white;" disabled>' . sprintf("%02d", $i) . '</button>';
                                                } else {
                                                    echo '<div class="col"><button class="btn btn-lg btn-outline-dark py-0 px-1 mr-3" id="idm' . $i . '"  value="' . $i . '" style="background-color: white;">' . sprintf("%02d", $i) . '</button>';
                                                }
                                            }
                                        }
                                    } else {
                                        if ($i == $unavailable2) {
                                            echo '<button class="btn btn-lg bg-dark btn-outline-dark  py-3" id="idm' . $i . '" value="' . $i . '" style="background-color: white;" disabled></button></div></div> <div class="row text-center mt-4">';
                                            $unavailable2 = $unavailable2 + 2;
                                        } else {
                                            $counter = 0;
                                            foreach ($seatss as $seat) {
                                                if ($i == $seat['seat_number']) {
                                                    $counter++;
                                                }
                                            }
                                            if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                foreach ($taken_seat_number as $seat_n) {
                                                    if ($i == $seat_n) {
                                                        $counter++;
                                                    }
                                                }
                                            }
                                            if ($counter > 0) {
                                                echo '<button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1" id="idm' . $i . '" value="' . $i . '" style="background-color: white;" disabled>' . sprintf("%02d", $i) . '</button></div></div> <div class="row text-center mt-4">';
                                            } else {
                                                echo '<button class="btn btn-lg btn-outline-dark py-0 px-1" id="idm' . $i . '" value="' . $i . '" style="background-color: white;">' . sprintf("%02d", $i) . '</button></div></div> <div class="row text-center mt-4">';
                                            }
                                        }
                                        $end = $end + 4;
                                        if ($i == $otherend) {
                                            $unavailable2 = $unavailable2 - 1;
                                            $otherend = $otherend + 8;
                                        } else {
                                            $unavailable2 = $unavailable2 + 1;
                                        }
                                    }
                                    ?>
                                <?php } ?>
                                <div class="row p-0">
                                    <div class="col">
                                        <?php for ($i = $continue + 1; $i <= ($continue + 5); $i++) {
                                            if ($i == $unavailable2) { ?>
                                                <button class="btn btn-lg btn-outline-dark bg-dark py-3 ml-1" id="<?php echo 'idm' . $i; ?>" value="<?php echo $i; ?>" style="background-color: white;" disabled></button>
                                                <?php $unavailable2 = $unavailable2 + 2;
                                            } else {
                                                $counter = 0;
                                                foreach ($seatss as $seat) {
                                                    if ($i == $seat['seat_number']) {
                                                        $counter++;
                                                    }
                                                }
                                                if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                    foreach ($taken_seat_number as $seat_n) {
                                                        if ($i == $seat_n) {
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                if ($counter > 0) {
                                                ?>
                                                    <button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1 ml-1" id="<?php echo 'idm' . $i; ?>" value="<?php echo $i; ?>" style="background-color: white;" disabled><?php echo sprintf("%02d", $i) ?></button>
                                                <?php } else { ?>
                                                    <button class="btn btn-lg btn-outline-dark  py-0 px-1 ml-1" id="<?php echo 'idm' . $i; ?>" value="<?php echo $i; ?>" style="background-color: white;"><?php echo sprintf("%02d", $i) ?></button>
                                        <?php }
                                            }
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                    if ($protocol[0] == "Off") { ?>
                        <div class="container bg-light py-4 shadow border border-secondary rounded-bottom">
                            <img src="image/whl.png" alt="" class="ml-3 mb-4" style="width: 43px; height: 43px;">
                            <div class="row text-center">
                                <?php
                                for ($i = 1; $i <= ($totalSeats[0] - 5); $i++) {
                                ?>
                                    <?php
                                    if ($i != $end) {
                                        if ($i == $space) {
                                            $counter = 0;
                                            foreach ($seatss as $seat) {
                                                if ($i == $seat['seat_number']) {
                                                    $counter++;
                                                }
                                            }
                                            if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                foreach ($taken_seat_number as $seat_n) {
                                                    if ($i == $seat_n) {
                                                        $counter++;
                                                    }
                                                }
                                            }
                                            if ($counter > 0) {
                                                echo '<button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1" id="idm' . $i . '"  value="' . $i . '" style="background-color: white;" disabled>' . sprintf("%02d", $i) . '</button></div>';
                                            } else {
                                                echo '<button class="btn btn-lg btn-outline-dark py-0 px-1" id="idm' . $i . '"  value="' . $i . '" style="background-color: white;">' . sprintf("%02d", $i) . '</button></div>';
                                            }
                                            $space = $space + 4;
                                        } else {
                                            $counter = 0;
                                            foreach ($seatss as $seat) {
                                                if ($i == $seat['seat_number']) {
                                                    $counter++;
                                                }
                                            }
                                            if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                foreach ($taken_seat_number as $seat_n) {
                                                    if ($i == $seat_n) {
                                                        $counter++;
                                                    }
                                                }
                                            }
                                            if ($counter > 0) {
                                                echo '<div class="col"><button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1 mr-3" id="idm' . $i . '"  value="' . $i . '" style="background-color: white;" disabled>' . sprintf("%02d", $i) . '</button>';
                                            } else {
                                                echo '<div class="col"><button class="btn btn-lg btn-outline-dark py-0 px-1 mr-3" id="idm' . $i . '"  value="' . $i . '" style="background-color: white;">' . sprintf("%02d", $i) . '</button>';
                                            }
                                        }
                                    } else {
                                        $counter = 0;
                                        foreach ($seatss as $seat) {
                                            if ($i == $seat['seat_number']) {
                                                $counter++;
                                            }
                                        }
                                        if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                            foreach ($taken_seat_number as $seat_n) {
                                                if ($i == $seat_n) {
                                                    $counter++;
                                                }
                                            }
                                        }
                                        if ($counter > 0) {
                                            echo '<button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1" id="idm' . $i . '" value="' . $i . '" style="background-color: white;" disabled>' . sprintf("%02d", $i) . '</button></div></div> <div class="row text-center mt-4">';
                                        } else {
                                            echo '<button class="btn btn-lg btn-outline-dark py-0 px-1" id="idm' . $i . '" value="' . $i . '" style="background-color: white;">' . sprintf("%02d", $i) . '</button></div></div> <div class="row text-center mt-4">';
                                        }
                                        $end = $end + 4;
                                    }
                                    ?>
                                <?php } ?>
                                <div class="row p-0">
                                    <div class="col">
                                        <?php for ($i = $continue + 1; $i <= ($continue + 5); $i++) {
                                            $counter = 0;
                                            foreach ($seatss as $seat) {
                                                if ($i == $seat['seat_number']) {
                                                    $counter++;
                                                }
                                            }
                                            if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                foreach ($taken_seat_number as $seat_n) {
                                                    if ($i == $seat_n) {
                                                        $counter++;
                                                    }
                                                }
                                            }
                                            if ($counter > 0) {
                                        ?>
                                                <button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1 ml-1" id="<?php echo 'idm' . $i; ?>" value="<?php echo $i; ?>" style="background-color: white;" disabled><?php echo sprintf("%02d", $i) ?></button>
                                            <?php } else { ?>
                                                <button class="btn btn-lg btn-outline-dark  py-0 px-1 ml-1" id="<?php echo 'idm' . $i; ?>" value="<?php echo $i; ?>" style="background-color: white;"><?php echo sprintf("%02d", $i) ?></button>
                                        <?php }
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                }
                if ($totalSeats[0] == 60) {
                    if ($protocol[0] == "On") {
                    ?>
                        <div class="container bg-light py-4 shadow border border-secondary rounded-bottom">
                            <img src="image/whl.png" alt="" class="ml-3 mb-4" style="width: 43px; height: 43px;">
                            <div class="row text-center">
                                <?php
                                for ($c = 1; $c <= ($totalSeats[0] - 5); $c++) {
                                ?>
                                    <?php
                                    if ($c != $end2) {
                                        if ($c == $space2) {
                                            if ($c == $unavailable) {
                                                echo '<button class="btn bg-dark btn-lg btn-outline-dark  py-3" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;" disabled></button></div>';
                                                $unavailable = $unavailable + 2;
                                            } else {
                                                $counter = 0;
                                                foreach ($seatss as $seat) {
                                                    if ($c == $seat['seat_number']) {
                                                        $counter++;
                                                    }
                                                }
                                                if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                    foreach ($taken_seat_number as $seat_n) {
                                                        if ($i == $seat_n) {
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                if ($counter > 0) {
                                                    echo '<button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;" disabled>' . sprintf("%02d", $c) . '</button></div>';
                                                } else {
                                                    echo '<button class="btn btn-lg btn-outline-dark  py-0 px-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;">' . sprintf("%02d", $c) . '</button></div>';
                                                }
                                            }
                                            $space2 = $space2 + 5;
                                        } else if ($c == $start2) {
                                            if ($c == $unavailable) {
                                                echo '<div class="col"><button class="btn bg-dark btn-lg btn-outline-dark  py-3 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;" disabled></button>';
                                                $unavailable = $unavailable + 2;
                                            } else {
                                                $counter = 0;
                                                foreach ($seatss as $seat) {
                                                    if ($c == $seat['seat_number']) {
                                                        $counter++;
                                                    }
                                                }
                                                if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                    foreach ($taken_seat_number as $seat_n) {
                                                        if ($i == $seat_n) {
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                if ($counter > 0) {
                                                    echo '<div class="col"><button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;" disabled>' . sprintf("%02d", $c) . '</button>';
                                                } else {
                                                    echo '<div class="col"><button class="btn btn-lg btn-outline-dark  py-0 px-1 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;">' . sprintf("%02d", $c) . '</button>';
                                                }
                                            }
                                            $start2 = $start2 + 5;
                                        } else if ($c == $start) {
                                            if ($c == $unavailable) {
                                                echo '<div class="col ml-2"><button class="btn bg-dark btn-lg btn-outline-dark  py-3 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;" disabled></button>';
                                                $unavailable = $unavailable + 2;
                                            } else {
                                                $counter = 0;
                                                foreach ($seatss as $seat) {
                                                    if ($c == $seat['seat_number']) {
                                                        $counter++;
                                                    }
                                                }
                                                if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                    foreach ($taken_seat_number as $seat_n) {
                                                        if ($i == $seat_n) {
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                if ($counter > 0) {
                                                    echo '<div class="col ml-2"><button class="btn btn-lg bg-danger btn-outline-dark  py-0 px-1 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;" disabled>' . sprintf("%02d", $c) . '</button>';
                                                } else {
                                                    echo '<div class="col ml-2"><button class="btn btn-lg btn-outline-dark  py-0 px-1 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;">' . sprintf("%02d", $c) . '</button>';
                                                }
                                            }
                                            $start = $start + 5;
                                        } else {
                                            if ($c == $unavailable) {
                                                echo '<button class="btn bg-dark btn-lg btn-outline-dark py-3 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;" disabled></button>';
                                                $unavailable = $unavailable + 2;
                                            } else {
                                                $counter = 0;
                                                foreach ($seatss as $seat) {
                                                    if ($c == $seat['seat_number']) {
                                                        $counter++;
                                                    }
                                                }
                                                if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                    foreach ($taken_seat_number as $seat_n) {
                                                        if ($i == $seat_n) {
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                if ($counter > 0) {
                                                    echo '<button class="btn btn-lg bg-danger btn-outline-dark py-0 px-1 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;" disabled>' . sprintf("%02d", $c) . '</button>';
                                                } else {
                                                    echo '<button class="btn btn-lg btn-outline-dark py-0 px-1 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;">' . sprintf("%02d", $c) . '</button>';
                                                }
                                            }
                                        }
                                    } else {
                                        if ($c == $unavailable) {
                                            echo '<button class="btn bg-dark btn-lg btn-outline-dark  py-3" id="idm' . $c . '" value="' . $c . '" style="background-color: white;" disabled></button></div></div> <div class="row text-center mt-4">';
                                            $unavailable = $unavailable + 2;
                                        } else {
                                            $counter = 0;
                                            foreach ($seatss as $seat) {
                                                if ($c == $seat['seat_number']) {
                                                    $counter++;
                                                }
                                            }
                                            if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                foreach ($taken_seat_number as $seat_n) {
                                                    if ($i == $seat_n) {
                                                        $counter++;
                                                    }
                                                }
                                            }
                                            if ($counter > 0) {
                                                echo '<button class="btn btn-lg bg-danger btn-outline-dark  py-0 px-1" id="idm' . $c . '" value="' . $c . '" style="background-color: white;" disabled>' . sprintf("%02d", $c) . '</button></div></div> <div class="row text-center mt-4">';
                                            } else {
                                                echo '<button class="btn btn-lg btn-outline-dark  py-0 px-1" id="idm' . $c . '" value="' . $c . '" style="background-color: white;">' . sprintf("%02d", $c) . '</button></div></div> <div class="row text-center mt-4">';
                                            }
                                        }
                                        $end2 = $end2 + 5;
                                    }
                                    ?>
                                <?php } ?>
                                <div class="row p-0">
                                    <div class="col">
                                        <?php
                                        for ($i = $continue + 1; $i <= ($continue + 5); $i++) {
                                            if ($i == $unavailable) { ?>
                                                <button class="btn bg-dark btn-lg btn-outline-dark  py-3 ml-2" id="<?php echo 'idm' . $i; ?>" value="<?php echo $i; ?>" style="background-color: white;" disabled></button>
                                                <?php
                                                $unavailable = $unavailable + 2;
                                            } else {
                                                $counter = 0;
                                                foreach ($seatss as $seat) {
                                                    if ($i == $seat['seat_number']) {
                                                        $counter++;
                                                    }
                                                }
                                                if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                    foreach ($taken_seat_number as $seat_n) {
                                                        if ($i == $seat_n) {
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                if ($counter > 0) {
                                                ?>
                                                    <button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1 ml-2" id="<?php echo 'idm' . $i; ?>" value="<?php echo $i; ?>" style="background-color: white;" disabled><?php echo sprintf("%02d", $i) ?></button>
                                                <?php } else { ?>
                                                    <button class="btn btn-lg btn-outline-dark  py-0 px-1 ml-2" id="<?php echo 'idm' . $i; ?>" value="<?php echo $i; ?>" style="background-color: white;"><?php echo sprintf("%02d", $i) ?></button>
                                        <?php }
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($protocol[0] == "Off") { ?>
                        <div class="container bg-light py-4 shadow border border-secondary rounded-bottom">
                            <img src="image/whl.png" alt="" class="ml-3 mb-4" style="width: 43px; height: 43px;">
                            <div class="row text-center">
                                <?php
                                for ($c = 1; $c <= ($totalSeats[0] - 5); $c++) {
                                ?>
                                    <?php
                                    if ($c != $end2) {
                                        if ($c == $space2) {
                                            $counter = 0;
                                            foreach ($seatss as $seat) {
                                                if ($c == $seat['seat_number']) {
                                                    $counter++;
                                                }
                                            }
                                            if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                foreach ($taken_seat_number as $seat_n) {
                                                    if ($i == $seat_n) {
                                                        $counter++;
                                                    }
                                                }
                                            }
                                            if ($counter > 0) {
                                                echo '<button class="btn btn-lg bg-danger btn-outline-dark  py-0 px-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;" disabled>' . sprintf("%02d", $c) . '</button></div>';
                                            } else {
                                                echo '<button class="btn btn-lg btn-outline-dark  py-0 px-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;">' . sprintf("%02d", $c) . '</button></div>';
                                            }
                                            $space2 = $space2 + 5;
                                        } else if ($c == $start2) {
                                            $counter = 0;
                                            foreach ($seatss as $seat) {
                                                if ($c == $seat['seat_number']) {
                                                    $counter++;
                                                }
                                            }
                                            if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                foreach ($taken_seat_number as $seat_n) {
                                                    if ($i == $seat_n) {
                                                        $counter++;
                                                    }
                                                }
                                            }
                                            if ($counter > 0) {
                                                echo '<div class="col"><button class="btn btn-lg bg-danger btn-outline-dark  py-0 px-1 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;" disabled>' . sprintf("%02d", $c) . '</button>';
                                            } else {
                                                echo '<div class="col"><button class="btn btn-lg btn-outline-dark  py-0 px-1 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;">' . sprintf("%02d", $c) . '</button>';
                                            }
                                            $start2 = $start2 + 5;
                                        } else if ($c == $start) {
                                            $counter = 0;
                                            foreach ($seatss as $seat) {
                                                if ($c == $seat['seat_number']) {
                                                    $counter++;
                                                }
                                            }
                                            if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                foreach ($taken_seat_number as $seat_n) {
                                                    if ($i == $seat_n) {
                                                        $counter++;
                                                    }
                                                }
                                            }
                                            if ($counter > 0) {
                                                echo '<div class="col ml-2"><button class="btn btn-lg bg-danger btn-outline-dark  py-0 px-1 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;" disabled>' . sprintf("%02d", $c) . '</button>';
                                            } else {
                                                echo '<div class="col ml-2"><button class="btn btn-lg btn-outline-dark  py-0 px-1 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;">' . sprintf("%02d", $c) . '</button>';
                                            }
                                            $start = $start + 5;
                                        } else {
                                            $counter = 0;
                                            foreach ($seatss as $seat) {
                                                if ($c == $seat['seat_number']) {
                                                    $counter++;
                                                }
                                            }
                                            if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                foreach ($taken_seat_number as $seat_n) {
                                                    if ($i == $seat_n) {
                                                        $counter++;
                                                    }
                                                }
                                            }
                                            if ($counter > 0) {
                                                echo '<button class="btn btn-lg bg-danger btn-outline-dark py-0 px-1 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;" disabled>' . sprintf("%02d", $c) . '</button>';
                                            } else {
                                                echo '<button class="btn btn-lg btn-outline-dark py-0 px-1 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;">' . sprintf("%02d", $c) . '</button>';
                                            }
                                        }
                                    } else {
                                        $counter = 0;
                                        foreach ($seatss as $seat) {
                                            if ($c == $seat['seat_number']) {
                                                $counter++;
                                            }
                                        }
                                        if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                            foreach ($taken_seat_number as $seat_n) {
                                                if ($i == $seat_n) {
                                                    $counter++;
                                                }
                                            }
                                        }
                                        if ($counter > 0) {
                                            echo '<button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1"  id="idm' . $c . '" value="' . $c . '" style="background-color: white;" disabled>' . sprintf("%02d", $c) . '</button></div></div> <div class="row text-center mt-4">';
                                        } else {
                                            echo '<button class="btn btn-lg btn-outline-dark  py-0 px-1"  id="idm' . $c . '" value="' . $c . '" style="background-color: white;">' . sprintf("%02d", $c) . '</button></div></div> <div class="row text-center mt-4">';
                                        }
                                        $end2 = $end2 + 5;
                                    }
                                    ?>
                                <?php } ?>
                                <div class="row p-0">
                                    <div class="col">
                                        <?php for ($i = $continue + 1; $i <= ($continue + 5); $i++) {
                                            $counter = 0;
                                            foreach ($seatss as $seat) {
                                                if ($i == $seat['seat_number']) {
                                                    $counter++;
                                                }
                                            }
                                            if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                foreach ($taken_seat_number as $seat_n) {
                                                    if ($i == $seat_n) {
                                                        $counter++;
                                                    }
                                                }
                                            }
                                            if ($counter > 0) { ?>
                                                <button class="btn  btn-lg btn-outline-dark bg-danger py-0 px-1 ml-2" id="<?php echo 'idm' . $i; ?>" value="<?php echo $i; ?>" style="background-color: white;" disabled><?php echo sprintf("%02d", $i) ?></button>
                                            <?php } else { ?>
                                                <button class="btn  btn-lg btn-outline-dark  py-0 px-1 ml-2" id="<?php echo 'idm' . $i; ?>" value="<?php echo $i; ?>" style="background-color: white;"><?php echo sprintf("%02d", $i) ?></button>
                                        <?php }
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                }
                if ($totalSeats[0] == 61) {
                    if ($protocol[0] == "On") {
                    ?>
                        <div class="container bg-light py-4 shadow border border-secondary rounded-bottom">
                            <img src="image/whl.png" alt="" class="ml-3 mb-4" style="width: 43px; height: 43px;">
                            <div class="row text-center">
                                <?php
                                for ($c = 1; $c <= ($totalSeats[0] - 6); $c++) {
                                ?>
                                    <?php
                                    if ($c != $end2) {
                                        if ($c == $space2) {
                                            if ($c == $unavailable) {
                                                echo '<button class="btn bg-dark btn-lg btn-outline-dark  py-3" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;" disabled></button></div>';
                                                $unavailable = $unavailable + 2;
                                            } else {
                                                $counter = 0;
                                                foreach ($seatss as $seat) {
                                                    if ($c == $seat['seat_number']) {
                                                        $counter++;
                                                    }
                                                }
                                                if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                    foreach ($taken_seat_number as $seat_n) {
                                                        if ($i == $seat_n) {
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                if ($counter > 0) {
                                                    echo '<button class="btn btn-lg bg-danger btn-outline-dark py-0 px-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;" disabled>' . sprintf("%02d", $c) . '</button></div>';
                                                } else {
                                                    echo '<button class="btn btn-lg btn-outline-dark py-0 px-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;">' . sprintf("%02d", $c) . '</button></div>';
                                                }
                                            }
                                            $space2 = $space2 + 5;
                                        } else if ($c == $start2) {
                                            if ($c == $unavailable) {
                                                echo '<div class="col"><button class="btn bg-dark btn-lg btn-outline-dark  py-3 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;" disabled></button>';
                                                $unavailable = $unavailable + 2;
                                            } else {
                                                $counter = 0;
                                                foreach ($seatss as $seat) {
                                                    if ($c == $seat['seat_number']) {
                                                        $counter++;
                                                    }
                                                }
                                                if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                    foreach ($taken_seat_number as $seat_n) {
                                                        if ($i == $seat_n) {
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                if ($counter > 0) {
                                                    echo '<div class="col"><button class="btn btn-lg bg-danger btn-outline-dark py-0 px-1 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;" disabled>' . sprintf("%02d", $c) . '</button>';
                                                } else {
                                                    echo '<div class="col"><button class="btn btn-lg btn-outline-dark py-0 px-1 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;">' . sprintf("%02d", $c) . '</button>';
                                                }
                                            }
                                            $start2 = $start2 + 5;
                                        } else if ($c == $start) {
                                            if ($c == $unavailable) {
                                                echo '<div class="col ml-2"><button class="btn bg-dark btn-lg btn-outline-dark  py-3 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;" disabled></button>';
                                                $unavailable = $unavailable + 2;
                                            } else {
                                                $counter = 0;
                                                foreach ($seatss as $seat) {
                                                    if ($c == $seat['seat_number']) {
                                                        $counter++;
                                                    }
                                                }
                                                if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                    foreach ($taken_seat_number as $seat_n) {
                                                        if ($i == $seat_n) {
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                if ($counter > 0) {
                                                    echo '<div class="col ml-2"><button class="btn btn-lg bg-danger btn-outline-dark py-0 px-1 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;" disabled>' . sprintf("%02d", $c) . '</button>';
                                                } else {
                                                    echo '<div class="col ml-2"><button class="btn btn-lg btn-outline-dark py-0 px-1 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;">' . sprintf("%02d", $c) . '</button>';
                                                }
                                            }
                                            $start = $start + 5;
                                        } else {
                                            if ($c == $unavailable) {
                                                echo '<button class="btn bg-dark btn-lg btn-outline-dark py-3 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;" disabled></button>';
                                                $unavailable = $unavailable + 2;
                                            } else {
                                                $counter = 0;
                                                foreach ($seatss as $seat) {
                                                    if ($c == $seat['seat_number']) {
                                                        $counter++;
                                                    }
                                                }
                                                if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                    foreach ($taken_seat_number as $seat_n) {
                                                        if ($i == $seat_n) {
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                if ($counter > 0) {
                                                    echo '<button class="btn btn-lg bg-danger btn-outline-dark py-0 px-1 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;" disabled>' . sprintf("%02d", $c) . '</button>';
                                                } else {
                                                    echo '<button class="btn btn-lg btn-outline-dark py-0 px-1 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;">' . sprintf("%02d", $c) . '</button>';
                                                }
                                            }
                                        }
                                    } else {
                                        if ($c == $unavailable) {
                                            echo '<button class="btn bg-dark btn-lg btn-outline-dark  py-3" id="idm' . $c . '" value="' . $c . '" style="background-color: white;" disabled></button></div></div> <div class="row text-center mt-4">';
                                            $unavailable = $unavailable + 2;
                                        } else {
                                            $counter = 0;
                                            foreach ($seatss as $seat) {
                                                if ($c == $seat['seat_number']) {
                                                    $counter++;
                                                }
                                            }
                                            if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                foreach ($taken_seat_number as $seat_n) {
                                                    if ($i == $seat_n) {
                                                        $counter++;
                                                    }
                                                }
                                            }
                                            if ($counter > 0) {
                                                echo '<button class="btn btn-lg bg-danger btn-outline-dark py-0 px-1" id="idm' . $c . '" value="' . $c . '" style="background-color: white;" disabled>' . sprintf("%02d", $c) . '</button></div></div> <div class="row text-center mt-4">';
                                            } else {
                                                echo '<button class="btn btn-lg btn-outline-dark py-0 px-1" id="idm' . $c . '" value="' . $c . '" style="background-color: white;">' . sprintf("%02d", $c) . '</button></div></div> <div class="row text-center mt-4">';
                                            }
                                        }
                                        $end2 = $end2 + 5;
                                    }
                                    ?>
                                <?php } ?>
                                <div class="row p-0">
                                    <div class="col">
                                        <?php
                                        $unavailable = $unavailable + 1;
                                        for ($i = $continue4 + 1; $i <= ($continue4 + 2); $i++) {
                                            if ($i == $unavailable) {
                                        ?>
                                                <button class="btn bg-dark btn-lg btn-outline-dark  py-3 ml-2" id="<?php echo 'idm' . $i; ?>" value="<?php echo $i; ?>" style="background-color: white;" disabled></button>
                                                <?php
                                                $unavailable = $unavailable + 1;
                                            } else {
                                                $counter = 0;
                                                foreach ($seatss as $seat) {
                                                    if ($c == $seat['seat_number']) {
                                                        $counter++;
                                                    }
                                                }
                                                if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                    foreach ($taken_seat_number as $seat_n) {
                                                        if ($i == $seat_n) {
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                if ($counter > 0) {
                                                ?>
                                                    <button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1 ml-2" id="<?php echo 'idm' . $i; ?>" value="<?php echo $i; ?>" style="background-color: white;" disabled><?php echo sprintf("%02d", $i) ?></button>
                                                <?php } else { ?>
                                                    <button class="btn btn-lg btn-outline-dark py-0 px-1 ml-2" id="<?php echo 'idm' . $i; ?>" value="<?php echo $i; ?>" style="background-color: white;"><?php echo sprintf("%02d", $i) ?></button>
                                        <?php }
                                            }
                                        } ?>
                                        <?php
                                        for ($i = $continue4 + 3; $i <= ($continue4 + 6); $i++) {
                                            if ($i == $unavailable) {
                                        ?>
                                                <button class="btn bg-dark btn-lg btn-outline-dark  py-3 ml-2" id="<?php echo 'idm' . $i; ?>" value="<?php echo $i; ?>" style="background-color: white;" disabled></button>
                                                <?php $unavailable = $unavailable + 2;
                                            } else {
                                                $counter = 0;
                                                foreach ($seatss as $seat) {
                                                    if ($i == $seat['seat_number']) {
                                                        $counter++;
                                                    }
                                                }
                                                if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                    foreach ($taken_seat_number as $seat_n) {
                                                        if ($i == $seat_n) {
                                                            $counter++;
                                                        }
                                                    }
                                                }
                                                if ($counter > 0) {
                                                ?>
                                                    <button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1 ml-2" id="<?php echo 'idm' . $i; ?>" value="<?php echo $i; ?>" style="background-color: white;" disabled><?php echo sprintf("%02d", $i) ?></button>
                                                <?php
                                                } else { ?>
                                                    <button class="btn btn-lg btn-outline-dark  py-0 px-1 ml-2" id="<?php echo 'idm' . $i; ?>" value="<?php echo $i; ?>" style="background-color: white;"><?php echo sprintf("%02d", $i) ?></button>
                                        <?php
                                                }
                                            }
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($protocol[0] == "Off") { ?>
                        <div class="container bg-light py-4 shadow border border-secondary rounded-bottom">
                            <img src="image/whl.png" alt="" class="ml-3 mb-4" style="width: 43px; height: 43px;">
                            <div class="row text-center">
                                <?php
                                for ($c = 1; $c <= ($totalSeats[0] - 6); $c++) {
                                ?>
                                    <?php
                                    if ($c != $end2) {
                                        if ($c == $space2) {
                                            $counter = 0;
                                            foreach ($seatss as $seat) {
                                                if ($c == $seat['seat_number']) {
                                                    $counter++;
                                                }
                                            }
                                            if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                foreach ($taken_seat_number as $seat_n) {
                                                    if ($i == $seat_n) {
                                                        $counter++;
                                                    }
                                                }
                                            }
                                            if ($counter > 0) {
                                                echo '<button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;" disabled>' . sprintf("%02d", $c) . '</button></div>';
                                            } else {
                                                echo '<button class="btn btn-lg btn-outline-dark py-0 px-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;">' . sprintf("%02d", $c) . '</button></div>';
                                            }
                                            $space2 = $space2 + 5;
                                        } else if ($c == $start2) {
                                            $counter = 0;
                                            foreach ($seatss as $seat) {
                                                if ($c == $seat['seat_number']) {
                                                    $counter++;
                                                }
                                            }
                                            if ($cid == "demo_account") {
                                                foreach ($taken_seat_number as $seat_n) {
                                                    if ($i == $seat_n) {
                                                        $counter++;
                                                    }
                                                }
                                            }
                                            if ($counter > 0) {
                                                echo '<div class="col"><button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;" disabled>' . sprintf("%02d", $c) . '</button>';
                                            } else {
                                                echo '<div class="col"><button class="btn btn-lg btn-outline-dark py-0 px-1 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;">' . sprintf("%02d", $c) . '</button>';
                                            }
                                            $start2 = $start2 + 5;
                                        } else if ($c == $start) {
                                            $counter = 0;
                                            foreach ($seatss as $seat) {
                                                if ($c == $seat['seat_number']) {
                                                    $counter++;
                                                }
                                            }
                                            if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                foreach ($taken_seat_number as $seat_n) {
                                                    if ($i == $seat_n) {
                                                        $counter++;
                                                    }
                                                }
                                            }
                                            if ($counter > 0) {
                                                echo '<div class="col ml-2"><button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;" disabled>' . sprintf("%02d", $c) . '</button>';
                                            } else {
                                                echo '<div class="col ml-2"><button class="btn btn-lg btn-outline-dark py-0 px-1 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;">' . sprintf("%02d", $c) . '</button>';
                                            }
                                            $start = $start + 5;
                                        } else {
                                            $counter = 0;
                                            foreach ($seatss as $seat) {
                                                if ($c == $seat['seat_number']) {
                                                    $counter++;
                                                }
                                            }
                                            if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                foreach ($taken_seat_number as $seat_n) {
                                                    if ($i == $seat_n) {
                                                        $counter++;
                                                    }
                                                }
                                            }
                                            if ($counter > 0) {
                                                echo '<button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;" disabled>' . sprintf("%02d", $c) . '</button>';
                                            } else {
                                                echo '<button class="btn btn-lg btn-outline-dark py-0 px-1 mx-1" id="idm' . $c . '"  value="' . $c . '" style="background-color: white;">' . sprintf("%02d", $c) . '</button>';
                                            }
                                        }
                                    } else {
                                        $counter = 0;
                                        foreach ($seatss as $seat) {
                                            if ($c == $seat['seat_number']) {
                                                $counter++;
                                            }
                                        }
                                        if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                            foreach ($taken_seat_number as $seat_n) {
                                                if ($i == $seat_n) {
                                                    $counter++;
                                                }
                                            }
                                        }
                                        if ($counter > 0) {
                                            echo '<button class="btn btn-lg btn-outline-dark bg-danger py-0 px-1" id="idm' . $c . '" value="' . $c . '" style="background-color: white;" disabled>' . sprintf("%02d", $c) . '</button></div></div> <div class="row text-center mt-4">';
                                        } else {
                                            echo '<button class="btn btn-lg btn-outline-dark  py-0 px-1" id="idm' . $c . '" value="' . $c . '" style="background-color: white;">' . sprintf("%02d", $c) . '</button></div></div> <div class="row text-center mt-4">';
                                        }
                                        $end2 = $end2 + 5;
                                    }
                                    ?>
                                <?php } ?>
                                <div class="row p-0">
                                    <div class="col">
                                        <?php for ($i = $continue4 + 1; $i <= ($continue4 + 6); $i++) {
                                            $counter = 0;
                                            foreach ($seatss as $seat) {
                                                if ($i == $seat['seat_number']) {
                                                    $counter++;
                                                }
                                            }
                                            if ($cid == "demo_account" && isset($_SESSION['demo_booking_table'])) {
                                                foreach ($taken_seat_number as $seat_n) {
                                                    if ($i == $seat_n) {
                                                        $counter++;
                                                    }
                                                }
                                            }
                                            if ($counter > 0) {
                                        ?>
                                                <button class="btn  btn-lg btn-outline-dark bg-danger py-0 px-1 ml-2" id="<?php echo 'idm' . $i; ?>" value="<?php echo $i; ?>" style="background-color: white;" disabled><?php echo sprintf("%02d", $i) ?></button>
                                            <?php } else { ?>
                                                <button class="btn  btn-lg btn-outline-dark  py-0 px-1 ml-2" id="<?php echo 'idm' . $i; ?>" value="<?php echo $i; ?>" style="background-color: white;"><?php echo sprintf("%02d", $i) ?></button>
                                        <?php }
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php }
                } ?>
            </div>



            <div class="container container-small mt-4 mb-0">
                <div class=" text-center">
                    <?php include('errors.php'); ?>
                    <form action="" method="Post">
                        <div class="d-flex justify-content-center my-4 ">
                            <p class="mt-2 mr-3 " style="line-height: 1em;">Seat Number </p>
                            <input type="text" class="form-control text-muted text-center w-25 ali" name="seatID" id="seatID" readonly="">

                            <!-- <p class="mt-2 mr-3 " style="line-height: 1em;">Seat Number </p> -->
                            <input type="hidden" class="form-control  text-center w-25 ali" name="maxxs" id="maxxs" value="<?php echo $numofpassenger ?>" readonly="">
                        </div>
                        <button class="btn btn-lg btn-danger rounded-0 px-5" id="resetb">Reset</button>
                        <button class="btn btn-lg btn-success rounded-0 px-5  m-0" type="submit" id="confirmSeat" name="confirmSeat" disabled>Confirm</button>
                    </form>
                    <!-- <a href="selectSeat.php" class="btn btn-lg btn-primary shadow px-5 rounded-pill">Confirm</a> -->
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

            var i = 1;
            $("button").click(function() {
                var id = $(this).val();
                var max = document.getElementById("maxxs").value;
                var btn = document.getElementById("idm" + id);
                var btnvalue = document.getElementById("idm" + id).value;
                var input = document.getElementById('seatID');
                var inputvalue = document.getElementById('seatID').value;
                var val = document.getElementById('seatID').value;
                var counter = 0;
                if (inputvalue != '') {
                    var idArr = inputvalue.split(',');
                    for (var x = 0; x < idArr.length; x++) {
                        // alert(idArr[x]+" "+id);
                        if (id == idArr[x]) {
                            counter++;
                        }
                    }
                }
                if (i <= max && counter == 0) {
                    btn.style.backgroundColor = "green";
                    btn.style.color = "white";
                    if (i == 1) {
                        input.value = id;
                    } else {
                        input.value = input.value + "," + id;
                    }

                    if (i == max) {
                        // $(":submit").removeAttr("disabled");
                        $("#confirmSeat").removeAttr('disabled');
                    }
                    i++;
                }
            });


            $('#resetb').click(function() {
                location.reload();
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