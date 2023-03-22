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
    unset($_SESSION['booking_id']);
    header("location: registeredHome.php");
}
if (isset($_SESSION['booking_id'])) {
    $bookingID = $_SESSION['booking_id'];
    // echo "<br><br><br><br><br>".$bookingID;
    updatePaymentStatus($bookingID);
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
                <div class="container-fluid py-2">
                    <div></div>
                    <b class="text-light ">Payment Status</b>
                    <div class="mr-3"></div>
                    <!-- <a class="navbar-brand text-light" href="registeredHome.php"><i class="fas fa-arrow-left mr-2 ml-2"></i></a><b class="text-light mr-5">Booking Details</b><div></div> -->
                </div>
            </nav>

            <div class="container mt-5 py-5">
                <div class="container container-small bg-light rounded shadow">
                    <div class="row px-3 py-5">
                        <div class="col text-center">
                            <h1 class="display-1 text-success" style="font-size:10em;"><i class="far fa-check-circle"></i></h1>
                            <h1 class="font-weight-bold" style="color: #3b55d9;"> Payment</h1>
                            <h1 class="font-weight-bold" style="color: #3b55d9;"> Succesfully!</h1>
                            <form class="px-0" method="GET">
                                <button type="submit" name="unsetsession" class="btn btn-outline-success mt-5 px-3"><i class="fas fa-home"></i> Home</button>
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