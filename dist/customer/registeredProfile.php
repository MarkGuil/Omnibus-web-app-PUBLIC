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
$result = $conn->query("SELECT * FROM user_customer WHERE id = '$custID' ");
$profileDetails = $result->fetch_all(MYSQLI_ASSOC);

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

            <ul class="list-unstyled components ml-1">
                <p class="text-center text-break text-capitalize">
                    <?php if (isset($_SESSION['customername'])) : ?>
                        Welcome <strong class="text-capitalize"><?php echo $_SESSION['customername']; ?></strong>
                    <?php endif ?>
                </p>
                <li class="nactive">
                    <a href="registeredHome.php" class="text-white"><i class="fas fa-home mr-2"></i> Home</a>
                </li>
                <li class="nactive">
                    <a href="registeredBookings.php" class="text-white"><i class="fas fa-calendar-check mr-2"></i> Bookings</a>
                </li>
                <li class="active">
                    <a href="#" data-toggle="collapse" aria-expanded="false" class="text-white"><i class="fas fa-user mr-2"></i> Account</a>
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
                    <?php } ?>
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
                    <span class="ml-3 fs-4 mt-1 navbar-brand text-light">Onmibus</span>
                    <div class="d-inline-block d-lg-none ml-auto"></div>
                </div>
            </nav>

            <div class="container vertical-center px-5 pt-4 pb-2 shadow" style="background-color:#7390d5">
                <?php
                if ($custID == 'demo_account') { ?>
                    <div class="d-flex justify-content-center ">
                        <div class="border rounded-circle text-center py-1 px-2 bg-light shadow">
                            <h1 class="display-1 px-3 pt-2 text-capitalize font-weight-bold" style="font-size: 4.5em;color: #7390d5"><?php echo substr($name, 0, 1); ?></h1>
                        </div>
                    </div>
                    <h3 class="text-center text-light mt-3">
                        <span class="text-capitalize"><?php echo $name ?></span>
                    </h3>
                    <?php } else {
                    foreach ($profileDetails as $detail) { ?>
                        <div class="d-flex justify-content-center ">
                            <div class="border rounded-circle text-center py-1 px-2 bg-light shadow">
                                <h1 class="display-1 px-3 pt-2 text-capitalize font-weight-bold" style="font-size: 4.5em;color: #7390d5"><?php echo substr($name, 0, 1); ?></h1>
                            </div>
                        </div>
                        <p class="text-center text-light mt-3">
                            <span class="text-capitalize"><?php echo $name ?></span>
                        <div class="mt-1 text-center text-light">
                            <small><i class="fas fa-phone mr-1"></i> <?php echo $detail['connumber'] ?></small><br>
                            <small><i class="fas fa-envelope mr-1"></i> <?php echo $detail['email'] ?></small>
                        </div>
                        </p>
                <?php }
                } ?>
            </div>
            <div class="container px-4 py-2 mb-3 mt-3">
                <div class="rounded-top bg-light pt-4 pb-4 shadow">
                    <span class="text-muted font-weight-bold mx-4 ">Profile</span>

                    <div class="row px-4 mt-3 position-relative">
                        <div class="col d-flex pt-1 ml-1">
                            <div class="rounded-circle text-center py-2 px-2 bg-warning"><i class="fas fa-user-cog text-light mt-1 ml-1"></i></div>
                        </div>
                        <div class="col-7 pt-3"><span class="font-weight-bold">Edit Profile</span></div>

                        <div class="col pt-3 position-static"><a href="editProfile.php" class="stretched-link float-right mr-3"><i class="fas fa-angle-right"></i></a></div>
                    </div>
                    <div class="row px-4 mt-2 mb-4 position-relative">
                        <div class="col d-flex pt-1 ml-1">
                            <div class="rounded-circle text-center py-2 px-2 bg-primary"><i class="fas fa-key text-light mt-1 mx-1"></i></div>
                        </div>
                        <div class="col-7 pt-3"><span class="font-weight-bold">Change Password</span></div>
                        <div class="col pt-3 position-static"><a href="changePassword.php" class="stretched-link float-right mr-3"><i class="fas fa-angle-right"></i></a></div>
                    </div>

                    <span class="text-muted font-weight-bold mx-4 ">Contact Info</span>

                    <div class="row px-4 mt-3 position-relative">
                        <div class="col d-flex pt-1 ml-1">
                            <div class="rounded-circle text-center py-2 px-2 bg-success"><i class="fas fa-phone text-light mt-1 mx-1"></i></div>
                        </div>
                        <div class="col-7 pt-3"><span class="font-weight-bold">Phone Number</span></div>

                        <div class="col pt-3 position-static"><a href="changeMNumber.php" class="stretched-link float-right mr-3"><i class="fas fa-angle-right"></i></a></div>
                    </div>
                    <div class="row px-4 mt-2 mb-4 position-relative">
                        <div class="col d-flex pt-1 ml-1">
                            <div class="rounded-circle text-center py-2 px-2 bg-info"><i class="fas fa-envelope text-light mt-1 mx-1"></i></div>
                        </div>
                        <div class="col-7 pt-3"><span class="font-weight-bold">Email Address</span></div>
                        <div class="col pt-3 position-static"><a href="changeEmail.php" class="stretched-link float-right mr-3"><i class="fas fa-angle-right"></i></a></div>
                    </div>

                    <form class="my-2 mt-5 my-lg-0 text-center">
                        <?php if (isset($_SESSION['customername']) && isset($_SESSION['isMobile']) && $_SESSION['isMobile'] == false) { ?>
                            <a class="btn btn-danger p-2 w-75" href="registeredBookings.php?desktop_logout='1'">Logout</a>
                        <?php } else if (isset($_SESSION['customername']) && isset($_SESSION['isMobile']) && $_SESSION['isMobile'] == true) { ?>
                            <a class="btn btn-danger p-2 w-75" href="registeredBookings.php?mobile_logout='1'">Logout</a>
                        <?php } ?>
                    </form>
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