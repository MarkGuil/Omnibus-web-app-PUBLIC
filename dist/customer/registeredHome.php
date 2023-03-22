<?php
include('../database/db.php');
include('servercustomer.php') ?>
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
$custID = $_SESSION['customerid'];

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
        <!-- Sidebar  -->
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
                <li class="active">
                    <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="text-white"><i class="fas fa-home mr-2"></i> Home</a>
                </li>
                <li class="nactive">
                    <a href="registeredBookings.php" class="text-white"><i class="fas fa-calendar-check mr-2"></i> Bookings</a>
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
        <!-- End of Sidebar -->

        <!-- Page Content  -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light " style="background-color: #3b55d9;">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="btn btn-light">
                        <i class="fas fa-align-left" style="color: #3b55d9;"></i>
                    </button>
                    <span class="ml-3 fs-4 mt-1 navbar-brand text-light">Omnibus</span>
                    <div class="d-inline-block d-lg-none ml-auto"></div>

                </div>
            </nav>
            <div class="container container-small vertical-center px-3 py-3 shadow rounded-bottom-4" style="background-color: #7390d5; ">
                <form action="" method="POST" oninput='pointB.setCustomValidity(pointB.value == pointA.value ? "Origin and destination cannot be the same" : "")'>
                    <div class="form-group md-6">
                        <label class="h6 text-light py-1 mb-0" style="border-top-left-radius: 10px; border-top-right-radius: 30px; background-color: #3b55d9;">
                            <i class="fas fa-map-marker-alt ml-2 mr-1" aria-hidden="true"></i>
                            <span class="me-3">Origin</span>
                        </label>
                        <!-- <input class="form-control" type="text" name="pointA" id="pointA" required> -->

                        <input list="browsers" class="form-control" name="pointA" id="pointA" required>
                        <datalist class="overflow-y-auto overflow-x-hidden" id="browsers" style="max-height: 20em;">
                            <?php
                            $sql_items = "SELECT DISTINCT `origin` FROM `trip`";
                            $sql_items_run = mysqli_query($db, $sql_items);
                            while ($items = $sql_items_run->fetch_assoc()) {
                            ?>
                                <option value="<?= $items['origin'] ?>">

                                <?php
                            }
                                ?>
                        </datalist>
                    </div>
                    <div class="form-group md-6">
                        <label class="h6 text-light py-1 mb-0" style="border-top-left-radius: 10px; border-top-right-radius: 30px; background-color: #3b55d9; ">
                            <i class="fas fa-location-arrow ml-2 mr-1" aria-hidden="true"></i>
                            <span class="me-3">Destination</span>
                        </label>
                        <!-- <input class="form-control" type="text" name="pointB" id="pointB" required> -->

                        <input list="browsers2" class="form-control" name="pointB" id="pointB" required>
                        <datalist class="overflow-y-auto overflow-x-hidden" id="browsers2" style="max-height: 20em;">
                            <?php
                            $sql_items = "SELECT DISTINCT `destination` FROM `trip`";
                            $sql_items_run = mysqli_query($db, $sql_items);
                            while ($items = $sql_items_run->fetch_assoc()) {
                            ?>
                                <option value="<?= $items['destination'] ?>">

                                <?php
                            }
                                ?>
                        </datalist>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group md-6 mb-3">
                                <label class="h6 text-light py-1 mb-0" style="border-top-left-radius: 10px; border-top-right-radius: 30px; background-color: #3b55d9;">
                                    <i class="fas fa-calendar-alt ml-2 mr-1" aria-hidden="true"></i>
                                    <span class="me-3">Date</span>
                                </label>
                                <input class="form-control shadow-sm" name="date" type="date" id="demo" min="<?php echo date("Y-m-d"); ?>" max="<?php echo date('Y-m-d', strtotime('+1 months')); ?>" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group md-6 mb-3">
                                <label class="h6 text-light py-1 mb-0" style="border-top-left-radius: 10px; border-top-right-radius: 30px; background-color: #3b55d9; ">
                                    <i class="fas fa-user-friends ml-2 mr-1" aria-hidden="true"></i>
                                    <span class="me-3">Passenger</span>
                                </label>
                                <input class="form-control shadow-sm" name="numberofpassenger" type="number" id="numberofpassenger" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-blue rounded-pill mt-3 h6 shadow px-4 w-100" name="searchBusTrip"><b>Search Bus </b><i class="fas fa-search ml-2 "></i></button>
                    </div>
                </form>
            </div>

            <!-- <?php
                    foreach ($origins as $origin) {
                        echo "<br>\n" . $origin['id'];
                    }
                    ?> -->



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