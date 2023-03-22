<?php
include('../database/db.php');
include('server_conductor.php'); ?>
<?php
if (session_id() == '') {
    session_start();
}

if (!isset($_SESSION['conductorid'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: loginConductor.php');
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['conductorid']);
    unset($_SESSION['conductorname']);
    header("location: loginConductor.php");
}

if (isset($_GET['unsetsession'])) {
    unset($_SESSION['selectedbtripid']);
    unset($_SESSION['selectedstatus']);
    unset($_SESSION['selectedcompid']);
    unset($_SESSION['selectedbookid']);
    unset($_SESSION['selectedcustomerid']);
    unset($_SESSION['selectedreference']);
    unset($_SESSION['selectedconductor']);
    unset($_SESSION['selectedseat']);
    header("location: homeConductor.php");
}
//   if (isset($_GET['unsetsession'])) {
//     unset($_SESSION['selectedorigin']);
//     unset($_SESSION['selecteddestination']);
//     unset($_SESSION['selecteddate']);
//     unset($_SESSION['selectedday']);
//     unset($_SESSION['selectedcompid']);
//     unset($_SESSION['selectedtripid']);
//     unset($_SESSION['selectedtsid']);
//     header("location: registeredHome.php");
//   }


$name = $_SESSION['conductorname'];
$id = $_SESSION['selectedconductor'];
$sstatus = $_SESSION['selectedstatus'];
$sbookid = $_SESSION['selectedbookid'];
$srefid = $_SESSION['selectedreference'];
$seatnumber = $_SESSION['selectedseat'];

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
        <!-- Page Content  -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light " style="background-color: #535DE3;">
                <div class="container-fluid">
                    <a class="navbar-brand text-light" href="bookingList.php"><i class="fas fa-arrow-left mr-2 ml-2"></i> <b> Go back</b></a>
                </div>
            </nav>
            <?php if ($sstatus == "confirmed") { ?>
                <div class="container">

                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <!-- <div class="modal-header bg-dark"> -->
                            <!-- <h6 class="text-light pt-2">Cancel Bus</h6> -->
                            <!-- <div class="close text-white" data-bs-dismiss="modal">&times;</div> -->
                            <!-- </div> -->
                            <div class="modal-body">
                                <div class="container text-center">
                                    <p>Update status of seat#<?php echo $seatnumber ?> as Occupied?</p>
                                </div>
                                <div class="">
                                    <?php  ?>
                                    <form class="form" id="setoccupied" action="" method="Post">
                                        <input type="hidden" class="form-control" name="bookingid" value="<?php echo $sbookid; ?>">
                                        <input type="hidden" class="form-control" name="companyname" value="">
                                        <input type="hidden" class="form-control" name="companyemail" value="">
                                        <input type="hidden" class="form-control" name="reference" value="<?php echo $srefid; ?>">

                                        <div class="d-flex justify-content-center mt-4">
                                            <!-- <div class="col text-center"> -->
                                            <!-- <button type="button" class="btn btn-outline-secondary rounded-pill mr-3" data-bs-dismiss="modal">Back</button> -->
                                            <!-- </div>
                                                <div class="col text-center"> -->
                                            <button type="submit" name="setoccupied" form="setoccupied" class="btn btn-danger rounded-pill ml-3">Set Occupied</button>
                                            <!-- </div> -->
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else if ($sstatus == "travelled") {
            ?>
                <div class="container">

                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <!-- <div class="modal-header bg-dark"> -->
                            <!-- <h6 class="text-light pt-2">Cancel Bus</h6> -->
                            <!-- <div class="close text-white" data-bs-dismiss="modal">&times;</div> -->
                            <!-- </div> -->
                            <div class="modal-body">
                                <div class="container text-center">
                                    <p>Update status of seat# <?php echo $seatnumber ?> as unoccupied?</p>
                                </div>
                                <div class="">
                                    <?php  ?>
                                    <form class="form" id="setunoccupied" action="" method="Post">
                                        <input type="hidden" class="form-control" name="bookingid" value="<?php echo $sbookid; ?>">
                                        <input type="hidden" class="form-control" name="companyname" value="">
                                        <input type="hidden" class="form-control" name="companyemail" value="">
                                        <input type="hidden" class="form-control" name="reference" value="<?php echo $srefid; ?>">

                                        <div class="d-flex justify-content-center mt-4">
                                            <!-- <div class="col text-center"> -->
                                            <!-- <button type="button" class="btn btn-outline-secondary rounded-pill mr-3" data-bs-dismiss="modal">Back</button> -->
                                            <!-- </div>
                                            <div class="col text-center"> -->
                                            <button type="submit" name="setunoccupied" form="setunoccupied" class="btn btn-danger rounded-pill ml-3">Set Unoccupied</button>
                                            <!-- </div> -->
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            ?>
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