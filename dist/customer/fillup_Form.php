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
$numofpassenger = $_SESSION['selectednumofpassenger'];


$result = $conn->query("SELECT * FROM bus_trip WHERE id = '$sstid'");
$trips = $result->fetch_all(MYSQLI_ASSOC);
// $count1 = $conn->query("SELECT fare FROM bus_trip WHERE `id`='$sstid' limit 1") or die($mysql->connect_error);
// $price = array_values($count1->fetch_assoc());
$count2 = $conn->query("SELECT companyName FROM user_partner_admin WHERE `id`='$scompid' limit 1") or die($mysql->connect_error);
$compName = array_values($count2->fetch_assoc());
$count3 = $conn->query("SELECT busNumber FROM buses WHERE `id`='$sbusid' AND companyID = '$scompid' limit 1") or die($mysql->connect_error);
$busNumber = array_values($count3->fetch_assoc());
// $count4 = $conn->query("SELECT connumber FROM user_customer WHERE `id`='$cid' AND fullname = '$name' limit 1") or die($mysql->connect_error);
// $connumber = array_values($count4->fetch_assoc());
// $result1 = $conn->query("SELECT connumber,email FROM user_customer WHERE `id` = '$cid' AND fullname = '$name'");
// $cons = $result1->fetch_all(MYSQLI_ASSOC);


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
                    <a class="navbar-brand text-light" href="selectSeat.php"><i class="fas fa-arrow-left mr-2 ml-2"></i></a><b class="text-light mr-5">Fill up form</b>
                    <div></div>
                </div>

            </nav>
            <div class="container container-small  vertical-center px-4 mt-4 py-1 ">
                <?php include('errors.php'); ?>
                <?php $pieces = explode(",", $sseat); ?>

                <form action="" id="skipPassengerDetails" method="post" enctype="multipart/form-data">
                    <?php for ($i = 0; $i < $numofpassenger; $i++) { ?>
                        <input type="hidden" class="form-control" name="busnumbers<?php echo $i ?>" value="<?php echo $pieces[$i] ?>" required>
                    <?php } ?>
                </form>
                <form action="" id="confirmPassengerDetails" method="post" enctype="multipart/form-data">
                    <?php for ($i = 0; $i < $numofpassenger; $i++) { ?>
                        <div class="container container-small rounded shadow bg-light mb-3 py-3">
                            <h5 class="" style="color: #3b55d9;">Passenger details <span class="float-right mr-2"><small class="text-muted">Seat: </small><?php echo $pieces[$i] ?></span></h5>
                            <div class="mx-2">
                                <input type="hidden" class="form-control" name="busnumber<?php echo $i ?>" value="<?php echo $pieces[$i] ?>" required>
                                <div class="form-group md-6">
                                    <label class="h6 text-muted"><small><b class="text-muted">First Name</b></small></label>
                                    <input type="text" class="form-control" name="fname<?php echo $i ?>" value="" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)" required>
                                </div>
                                <div class="form-group md-6">
                                    <label class="h6 text-muted"><small><b>Last Name</b></small></label>
                                    <input type="text" class="form-control" name="lname<?php echo $i ?>" value="" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode==32)" required>
                                </div>
                                <div class="row">
                                    <div class="col form-group">
                                        <label class="h6 text-muted"><small><b>Gender</b></small></label>
                                        <select class="select form-select text-muted " name="gender<?php echo $i ?>" id="gender" aria-label="Default select example" required>
                                            <option value="" class="text-muted" selected>Gender</option>
                                            <option value="Male" class="text-muted">Male</option>
                                            <option value="Female" class="text-muted">Female</option>
                                        </select>
                                    </div>
                                    <div class="col-4 form-group md-6">
                                        <label class="h6 text-muted"><small><b>Age</b></small></label>
                                        <input type="number" class="form-control" name="age<?php echo $i ?>" min="0" value="" required>
                                    </div>
                                </div>

                                <div class="form-group md-6" id="dynamic_field">
                                    <label class="h6 text-muted"><small><b>Documents</b></small></label><small class="text-muted"> (* Allows images files and pdf)</small>

                                    <br>
                                    <span class="h6 text-muted"><small>Valid ID</small></span>
                                    <input type="file" name="files1<?php echo $i ?>" class="form-control name_list my-2" accept="image/*,.pdf" required>
                                    <span class="h6 text-muted"><small>Vaccination Card</small></span>
                                    <input type="file" name="files2<?php echo $i ?>" class="form-control name_list my-2" accept="image/*,.pdf" required>
                                    <span class="h6 text-muted"><small>Travel Coordiation Permit (s-pass)</small></span>
                                    <input type="file" name="files3<?php echo $i ?>" class="form-control name_list my-2" accept="image/*,.pdf" required>

                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-md-12 text-center mt-2 mb-4">
                        <button class="btn btn-lg btn-success rounded px-5 mt-2" type="submit" id="confirmPassengerDetails" form="confirmPassengerDetails" name="confirmPassengerDetails">Submit Form</button>
                        <?php if ($cid == "demo_account") { ?>
                            <button class="btn btn-lg btn-outline-secondary rounded px-5 mt-2" type="submit" id="skipPassengerDetails" form="skipPassengerDetails" name="skipPassengerDetails">Skip</button>
                        <?php } ?>
                    </div>
                </form>
                <!-- <form action=""  id="lol" method="post"><button class="btn btn-lg btn-success rounded-0 px-5  m-0" type="submit" id="lol" form="lol" name="lol">Confirm</button></form> -->

            </div>
            <?php if ($cid == "demo_account") { ?>
                <div class="fixed-bottom mb-3">
                    <div class="col-md-12 text-center">
                        <div class="alert alert-warning alert-dismissible fade show shadow" role="alert">
                            <strong>Note*</strong> For the demo users, you can either fill up the form or skip it.
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