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

$name = $_SESSION['customername'];
$custID = $_SESSION['customerid'];
$result = $conn->query("SELECT * FROM user_customer WHERE id = '$custID' ");
$profileDetails = $result->fetch_all(MYSQLI_ASSOC);
// $result2 = $conn->query("SELECT DISTINCT address FROM destination ");
// $destinations = $result2->fetch_all(MYSQLI_ASSOC);

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
                <h3>Omnibus</h3>
            </div>

            <ul class="list-unstyled components ml-1">
                <p class="text-center text-break text-capitalize">
                    <?php if (isset($_SESSION['customername'])) : ?>
                        Welcome <strong class="text-capitalize"><?php echo $_SESSION['customername']; ?></strong>
                    <?php endif ?>
                </p>
                <li class="nactive">
                    <a href="#registeredHome.php" data-toggle="collapse" aria-expanded="false" class="text-white"><i class="fas fa-home mr-2"></i> Home</a>
                </li>
                <li class="nactive">
                    <a href="#" class="text-white"><i class="fas fa-calendar-check mr-2"></i> Bookings</a>
                </li>
                <li class="active">
                    <a href="#" class="text-white"><i class="fas fa-user mr-2"></i> Account</a>
                </li>
            </ul>

            <ul class="list-unstyled CTAs">
                <li>

                </li>
                <form class="form-inline my-2 my-lg-0">
                    <?php if (isset($_SESSION['customername'])) : ?>
                        <a class="btn btn-danger p-2 w-100" href="registeredHome.php?logout='1'">Logout</a>
                    <?php endif ?>
                </form>

            </ul>
        </nav>
        <!-- End of Sidebar -->

        <!-- Page Content  -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light " style="background-color: #3b55d9;">
                <div class="container-fluid">
                    <a class="navbar-brand text-light" href="registeredProfile.php"><i class="fas fa-arrow-left mr-2 ml-2"></i></a><b class="text-light mr-5">Phone Number Confirmation</b>
                    <div></div>
                </div>

            </nav>

            <div class="container px-3 mt-3">
                <div class="container container-small vertical-center bg-white px-4 py-4 shadow">
                    <form class="mx-3 mb-3" action="" method="POST">
                        <?php include('errors.php'); ?>

                        <?php if ($custID == 'demo_account') { ?>
                            <div class="md-6 form-group ">
                                <label class="h6 text-muted">Contact Number</label>
                                <input type="text" class="form-control" name="" value="" placeholder="Contact Number" maxlength="11" size="11" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                            </div>
                            <button type="button" class="btn btn-primary mt-1 px-4 w-100" name=""><b>Continue</b></button>
                        <?php } else { ?>
                            <?php foreach ($profileDetails as $detail) { ?>
                                <div class="md-6 form-group ">
                                    <label class="h6 text-muted">Contact Number</label>
                                    <input type="text" class="form-control" name="cnumber" value="<?php echo $detail['connumber']; ?>" placeholder="Contact Number" maxlength="11" size="11" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                                </div>
                            <?php } ?>
                            <button type="submit" class="btn btn-primary mt-1 px-4 w-100" name="changeContactNumber"><b>Continue</b></button>
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