<?php
include('../database/db.php');
include('server_conductor.php'); ?>
<?php
if (session_id() == '') {
    session_start();
}

if (!isset($_SESSION['conductorname'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: loginCustomer.php');
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['conductorname']);
    unset($_SESSION['conductorid']);
    header("location: loginConductor.php");
}

$name = $_SESSION['conductorname'];
$custID = $_SESSION['conductorid'];
$result = $conn->query("SELECT * FROM employees WHERE id = '$custID' ");
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

            <ul class="list-unstyled components ml-1">
                <p class="text-center text-break text-capitalize">
                    <?php if (isset($_SESSION['conductorname'])) : ?>
                        Welcome <strong class="text-capitalize"><?php echo $_SESSION['conductorname']; ?></strong>
                    <?php endif ?>
                </p>
                <li class="nactive">
                    <a href="#registeredHome.php" data-toggle="collapse" aria-expanded="false" class="text-white"><i class="fas fa-home mr-2"></i> Home</a>
                <li class="active">
                    <a href="#" class="text-white"><i class="fas fa-user mr-2"></i> Account</a>
                </li>
            </ul>

            <ul class="list-unstyled CTAs">
                <li>

                </li>
                <form class="form-inline my-2 my-lg-0">
                    <?php if (isset($_SESSION['customername'])) : ?>
                        <a class="btn btn-danger p-2 w-100" href="editProfile.php?logout='1'">Logout</a>
                    <?php endif ?>
                </form>

            </ul>
        </nav>
        <!-- End of Sidebar -->

        <!-- Page Content  -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light " style="background-color: #535DE3;">
                <div class="container-fluid">
                    <a class="navbar-brand text-light" href="registeredProfile.php"><i class="fas fa-arrow-left mr-2 ml-2"></i></a><b class="text-light mr-5">Edit Profile</b>
                    <div></div>
                </div>

            </nav>

            <div class="container vertical-center px-5 pt-4 pb-4 shadow" style="background-color:#7B84FA">
                <?php foreach ($profileDetails as $detail) { ?>
                    <div class="d-flex justify-content-center ">

                        <?php if ($detail['status'] == 0) { ?>
                            <div class="border rounded-circle text-center py-1 px-2 bg-light shadow">
                                <h1 class="display-1 px-3 pt-2 text-capitalize font-weight-bold" style="font-size: 4.5em;color: #7B84FA"><?php echo substr($name, 0, 1); ?></h1>
                            </div>
                        <?php } else {
                        } ?>
                    </div>
                <?php } ?>
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-light shadow py-1"><i class="fas fa-edit mr-1"></i><small>Change Photo</small></button>
                </div>
            </div>
            <div class="container px-3 mt-3">
                <div class="container container-small vertical-center bg-white px-4 py-4 shadow">
                    <!-- <div class="h1 text-center text-primary mb-4 mt-4">Omnibus</div>  -->
                    <h5 class="mb-3 text-primary">Personal Details</h5>
                    <form class="mx-3 mb-3" action="" method="POST">
                        <?php include('errors.php'); ?>
                        <?php foreach ($profileDetails as $detail) { ?>
                            <div class="form-group md-6 mx-1"><?php //echo $detail['id']; 
                                                                ?>
                                <label class="h6 text-muted font-weight-bold">Fullname</label>
                                <input type="text" class="form-control" name="fullname" value="<?php echo $name; ?>" placeholder="Fullname" pattern="[a-zA-Z0-9\s]+" required>
                                <input type="hidden" class="form-control" name="cid" value="<?php echo $detail['id']; ?>" required>
                            </div>
                            <input type="hidden" class="form-control" name="usertype" value="1">
                            <div class="form-group md-6 mx-1">
                                <label class="h6 text-muted font-weight-bold">Address</label>
                                <input type="text" class="form-control" name="address" value="<?php echo $detail['address']; ?>" placeholder="Address" required>
                            </div>
                        <?php } ?>
                        <button type="submit" class="btn btn-primary mt-3 px-4" name="editprofile"><b>Save</b></button>
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