<?php
include('../database/db.php');
include('server_conductor.php'); ?>
<?php
if (session_id() == '') {
    session_start();
}

if (!isset($_SESSION['conductorid'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../validation/loginMobile.php');
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['conductorid']);
    unset($_SESSION['conductorname']);
    header("location: ../validation/loginMobile.php");
}

$name = $_SESSION['conductorname'];
$id = $_SESSION['conductorid'];
$results = $conn->query("SELECT * FROM bus_trip WHERE  conductorID = '$id'");
$btdetails = $results->fetch_all(MYSQLI_ASSOC);
$result = $conn->query("SELECT role FROM employees WHERE  id = '$id'");
$role = $result->fetch_all(MYSQLI_ASSOC);
$result1 = $conn->query("SELECT COUNT(tripID) FROM bus_trip WHERE conductorID = '$id'");
$arrtrips = array_values($result1->fetch_assoc());

// $result = $conn->query("SELECT * FROM origin ");
// $origins = $result->fetch_all(MYSQLI_ASSOC);
// $result2 = $conn->query("SELECT DISTINCT address FROM destination ");
// $destinations = $result2->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">


    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <!-- Our Custom CSS -->
    <!-- <link rel="stylesheet" href="style3.css"> -->

    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>

    <title>Omnibus</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script> -->

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css" />

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>

    <!-- <link rel="stylesheet" type="text/css" href="css/style.css"> -->
    <style>
        img[alt="www.000webhost.com"] {
            display: none;
        }
    </style>
    <style>
        @media (min-width: 768px) {
            .container-small {
                width: 500px;
            }

            .container-large {
                width: 970px;
            }
        }

        @media (min-width: 992px) {
            .container-small {
                width: 500px;
            }

            .container-large {
                width: 1170px;
            }
        }

        @media (min-width: 1200px) {
            .container-small {
                width: 650px;
            }

            .container-large {
                width: 1500px;
            }
        }

        .container-small,
        .container-large {
            max-width: 100%;
        }

        @import "https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700";

        body {
            font-family: 'Poppins', sans-serif;
            background: #DBE6F3;
        }

        p {
            font-family: 'Poppins', sans-serif;
            font-size: 1.1em;
            font-weight: 300;
            line-height: 1.7em;
            color: #999;
        }

        a,
        a:hover,
        a:focus {
            color: inherit;
            text-decoration: none;
            transition: all 0.3s;
        }

        .navbar {
            padding: 12px 10px;
            background: #fff;
            border: none;
            border-radius: 0;
            box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
        }

        .navbar-btn {
            box-shadow: none;
            outline: none !important;
            border: none;
        }

        .line {
            width: 100%;
            height: 1px;
            border-bottom: 1px dashed #ddd;
            margin: 40px 0;
        }

        /* ---------------------------------------------------
    SIDEBAR STYLE
----------------------------------------------------- */

        #sidebar {
            width: 280px;
            position: fixed;
            top: 0;
            left: -280px;
            height: 100vh;
            z-index: 999;
            background: #7386D5;
            color: #fff;
            transition: all 0.3s;
            overflow-y: scroll;
            box-shadow: 3px 3px 3px rgba(0, 0, 0, 0.2);
        }

        #sidebar.active {
            left: 0;
        }

        #dismiss {
            width: 35px;
            height: 35px;
            line-height: 35px;
            text-align: center;
            background: #7386D5;
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            -webkit-transition: all 0.3s;
            -o-transition: all 0.3s;
            transition: all 0.3s;
        }

        #dismiss:hover {
            background: #fff;
            color: #7386D5;
        }

        .overlay {
            display: none;
            position: fixed;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.7);
            z-index: 998;
            opacity: 0;
            transition: all 0.5s ease-in-out;
        }

        .overlay.active {
            display: block;
            opacity: 1;
        }

        #sidebar .sidebar-header {
            padding: 20px;
            background: #535DE3;
        }

        #sidebar ul.components {
            padding: 20px 0;
            border-bottom: 1px solid #FEFEFE;
        }

        #sidebar ul p {
            color: #fff;
            padding: 10px;
        }

        #sidebar ul li a {
            padding: 10px;
            font-size: 1.1em;
            display: block;
        }

        #sidebar ul li.nactive a:hover {
            color: #7386D5 !important;
            background: #fff;
        }

        #sidebar ul li.active>a,
        a[aria-expanded="true"] {
            color: #fff;
            background: #6d7fcc;
        }

        a[data-toggle="collapse"] {
            position: relative;
        }

        .dropdown-toggle::after {
            display: block;
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
        }

        ul ul a {
            font-size: 0.9em !important;
            padding-left: 30px !important;
            background: #6d7fcc;
        }

        ul.CTAs {
            padding: 20px;
        }

        ul.CTAs a {
            text-align: center;
            font-size: 0.9em !important;
            display: block;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        a.download {
            background: #fff;
            color: #7386D5;
        }

        a.article,
        a.article:hover {
            background: #6d7fcc !important;
            color: #fff !important;
        }

        /* ---------------------------------------------------
    CONTENT STYLE
----------------------------------------------------- */

        #content {
            width: 100%;
            /*padding: 20px;*/
            min-height: 100vh;
            transition: all 0.3s;
            position: absolute;
            top: 0;
            right: 0;
        }

        .btn-blue {
            background-color: #535DE3;
            color: #F2F2F2;
            border-color: #535DE3;
        }

        .btn-blue:hover,
        .btn-blue:focus,
        .btn-blue:active,
        .btn-blue.active,
        .open .dropdown-toggle.btn-blue {
            background-color: #F2F2F2;
            color: #535DE3;
            border-color: #535DE3;
            transition: .2s;
        }
    </style>

</head>

<body>
    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div id="dismiss">
                <i class="fas fa-arrow-left"></i>
            </div>

            <div class="sidebar-header">
                <h3>Omnibus</h3>
            </div>

            <ul class="list-unstyled components ml-2">
                <p class="text-center text-break text-capitalize">
                    <?php if (isset($_SESSION['conductorname'])) : ?>
                        Welcome <strong class="text-capitalize"><?php echo $_SESSION['conductorname']; ?></strong>
                    <?php endif ?>
                </p>
                <li class="active">
                    <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="text-white"><i class="fas fa-home mr-2"></i> Home</a>
                </li>
                <!-- <li class="nactive">
                    <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="text-white">Pages</a>
                    <ul class="collapse list-unstyled" id="pageSubmenu">
                        <li>
                            <a href="#" class="text-white">Page 1</a>
                        </li>
                        <li>
                            <a href="#" class="text-white">Page 2</a>
                        </li>
                        <li>
                            <a href="#" class="text-white">Page 3</a>
                        </li>
                    </ul>
                </li> -->
                <!-- <li class="nactive">
                    <a href="#" class="text-white"><i class="fas fa-calendar-check mr-2"></i> Bookings</a>
                </li> -->
                <li class="nactive">
                    <a href="registeredProfile.php" class="text-white"><i class="fas fa-user mr-2"></i> Account</a>
                </li>
            </ul>

            <ul class="list-unstyled CTAs">
                <li>

                </li>
                <form class="form-inline my-2 my-lg-0">
                    <?php if (isset($_SESSION['conductorid'])) : ?>
                        <a class="btn btn-danger p-2 w-100" href="homeConductor.php?logout='1'">Logout</a>
                    <?php endif ?>
                </form>

            </ul>
        </nav>
        <!-- End of Sidebar -->

        <!-- Page Content  -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light " style="background-color: #535DE3;">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="btn btn-light">
                        <i class="fas fa-align-left" style="color: #535DE3;"></i>
                    </button>
                    <span class="ml-3 fs-4 mt-1 navbar-brand text-light">Ommibus</span>
                    <!-- <a class="navbar-brand text-primary" href="registeredHome.php"><b>Omnibus</b></a> -->
                    <div class="d-inline-block d-lg-none ml-auto"></div>

                    <!-- <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item active">
                                <a class="nav-link" href="#">Page</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Page</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Page</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Page</a>
                            </li>
                        </ul>
                    </div> -->
                </div>
            </nav>


            <div class="container">
                <!-- <?php echo $id; ?> -->
                <!-- <?php foreach ($btdetails as $trip) { ?>
                    <?php
                            $tripid = $trip['tripID'];
                            $compid = $trip['companyID'];
                            $routeid = $trip['routeID'];
                            $trmid = $trip['terminalID'];
                            $date = $trip['trip_date'];
                            $busid = $trip['busID'];
                            $bustripid = $trip['id'];
                            echo "Tripid: " . $tripid;
                            echo "<br>";
                            echo "Trip date: " . $date;
                            echo "<br>";
                            $res1 = $conn->query("SELECT departure_time FROM trip WHERE id = '$tripid' AND routeID = '$routeid'");
                            $time = array_values($res1->fetch_assoc());
                            $res2 = $conn->query("SELECT origin FROM trip WHERE id = '$tripid' AND routeID = '$routeid'");
                            $origin = array_values($res2->fetch_assoc());
                            $res3 = $conn->query("SELECT destination FROM trip WHERE id = '$tripid' AND routeID = '$routeid'");
                            $destination = array_values($res3->fetch_assoc());
                            //$recurs = $res1->fetch_all(MYSQLI_ASSOC);
                            echo $origin[0] . " - ";
                            echo $destination[0];
                            echo "<br>";
                            echo $time[0];
                            echo "<br><br>";
                    ?>
                <?php } ?> -->
            </div>


            <!--try -->
            <div class="container">

                <?php
                if ($role[0] = "Conductor") {
                    if (!empty($btdetails)) {
                        foreach ($btdetails as $sched) {
                            $tripid = $sched['tripID'];
                            $compid = $sched['companyID'];
                            $routeid = $sched['routeID'];
                            $trmid = $sched['terminalID'];
                            $date = $sched['trip_date'];
                            $busid = $sched['busID'];
                            $bustripid = $sched['id'];
                            $res1 = $conn->query("SELECT departure_time FROM trip WHERE id = '$tripid' AND routeID = '$routeid'");
                            $time = array_values($res1->fetch_assoc());
                            $res2 = $conn->query("SELECT origin FROM trip WHERE id = '$tripid' AND routeID = '$routeid'");
                            $origin = array_values($res2->fetch_assoc());
                            $res3 = $conn->query("SELECT destination FROM trip WHERE id = '$tripid' AND routeID = '$routeid'");
                            $destination = array_values($res3->fetch_assoc());

                            if (date("Y/m/d", strtotime($sched["trip_date"])) >= date("Y/m/d")) {
                ?>
                                <div class="position-relative mt-3 mx-3 px-3 py-2 shadow rounded border-left border-success" style="border-width: 4px !important; background-color: #E9FFED;">
                                    <div class="row no-gutters">
                                        <div class="col-8 px-2">

                                            <span class="" style="font-size: 1.2em; font-weight: 300;  color: #7E7E7E"><?php echo $origin[0] . " - ";
                                                                                                                        echo $destination[0]; ?></span>

                                        </div>
                                        <div class="col-8 px-2">
                                            <span class="" style="font-size: 1.1em; font-weight: 300; line-height: 1.7em; color: #949494">
                                                <hr>Trip Date: <?php echo $date; ?>
                                            </span>
                                            <span class="text-success" style="font-size: .8em ;line-height: 2.3em; "></span>
                                            <span class="" style="font-size: 1.1em; font-weight: 300; line-height: 1.7em; color: #949494"><br>Time: <?php echo  date('h:i A', strtotime($time[0])); ?></span><br>
                                            <?php
                                            $res4 = $conn->query("SELECT busNumber FROM buses WHERE id = '$busid'");
                                            $busnumber = array_values($res4->fetch_assoc());
                                            ?>
                                            <span class="" style="font-size: 1.1em; font-weight: 300; line-height: 1.7em; color: #949494">Bus Number: <?php echo $busnumber[0]; ?></span>

                                        </div>
                                        <div class="col px-3 ">
                                            <div class="float-right">
                                                <i class="fa fa-check-circle mr-1 text-success" aria-hidden="true" style="font-size: .7em ;"></i>

                                            </div>
                                        </div>
                                    </div>


                                    <div class="">
                                        <form action="" method="Post">
                                            <input type="hidden" class="form-control" name="tripsid" value="<?php echo $tripid; ?>" readonly>
                                            <input type="hidden" class="form-control" name="busid" value="<?php echo $busid; ?>" readonly>
                                            <input type="hidden" class="form-control" name="compid" value="<?php echo $compid; ?>" readonly>
                                            <input type="hidden" class="form-control" name="routeid" value="<?php echo $routeid; ?>" readonly>
                                            <input type="hidden" class="form-control" name="trmid" value="<?php echo $trmid; ?>" readonly>
                                            <input type="hidden" class="form-control" name="conductorid" value="<?php echo $id; ?>" readonly>
                                            <input type="hidden" class="form-control" name="tripdate" value="<?php echo $date; ?>" readonly>
                                            <input type="hidden" class="form-control" name="departtime" value="<?php echo $time[0]; ?>" readonly>
                                            <input type="hidden" class="form-control" name="bustripid" value="<?php echo $bustripid; ?>" readonly>
                                            <button class="btn btn-success stretched-link" type="submit" name="openList">View Seat Bookings</button>
                                        </form>
                                    </div>
                                </div>

                <?php }
                        }
                    } else {
                        echo '
                        <div class="position-relative mt-3 mx-3 px-3 py-2 shadow bg-danger rounded border-left border-danger text-light" style="border-width: 4px !important;"> No trip available</div>';
                    }
                } ?>

            </div>
            <!-- <footer class="text-center text-lg-start ">
                <div class="text-center text-light p-3" style="background-color: #212529">
                    © 2021 Copyright:
                    <a class="text-light" href="">Omnibus</a>
                </div>
            </footer> -->
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