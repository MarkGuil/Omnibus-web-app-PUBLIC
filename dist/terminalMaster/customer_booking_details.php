<?php
include('../database/db.php');
include('server_terminal_master.php') ?>
<?php
if (session_id() == '') {
    session_start();
}

if (!isset($_SESSION['compadminName'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login_page.php');
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['compTerMasemail']);
    unset($_SESSION['compadminName']);
    unset($_SESSION['compadminNameID']);
    unset($_SESSION['compTerMasID']);
    header("location: login_page.php");
}

$x = 1;
$compID = $_SESSION['compadminName'];
$email = $_SESSION['compTerMasemail'];
$id = $_SESSION['compTerMasID'];
$bookID = $_GET['i'];
$result = $conn->query("SELECT * FROM customer_booking_details WHERE bookingID = '$bookID'");
$details = $result->fetch_all(MYSQLI_ASSOC);

if (isset($_POST['viewFilePDF'])) {
    $file = $_POST['viewFilePDF'];
    header("content-type: application/pdf");
    readfile("../customer/uploads/" . $file);
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>OmnibusTerminal</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <?php include 'extentions/bootstrap.php' ?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <style>
        body {
            background-color: #F4F4F4;
        }

        @media (min-width: 768px) {
            .container-small {
                width: 500px;
            }

            .container-medium {
                width: 850px;
            }

            .container-large {
                width: 970px;
            }
        }

        @media (min-width: 992px) {
            .container-small {
                width: 500px;
            }

            .container-medium {
                width: 1000px;
            }

            .container-large {
                width: 1170px;
            }
        }

        @media (min-width: 1200px) {
            .container-small {
                width: 650px;
            }

            .container-medium {
                width: 1350px;
            }

            .container-large {
                width: 1500px;
            }
        }

        .container-small,
        .container-medium,
        .container-large {
            max-width: 100%;
        }

        .table-stripeds>tbody>tr:nth-child(even)>td,
        .table-stripeds>tbody>tr:nth-child(even)>th {
            background-color: #f8f9fa;
        }

        .table-stripeds>tbody>tr:nth-child(odd)>td,
        .table-stripeds>tbody>tr:nth-child(odd)>th {
            background-color: #ffffff;
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-dark navbar-expand-lg navbar-light bg-dark shadow-lg py-3 px-5 mb-5">
        <a class="navbar-brand mr-4" href="starting_home.php"><b>Omnibus</b><span class="text-secondary"> &nbsp; Terminal Master</span></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse ml-3 mt-1" id="navbarNav">
            <ul class="navbar-nav ">
                <li class="nav-item">
                    <a class="nav-link text-light" href="">Bookings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ml-2" href="./terminal_bus.php">Trips</a>
                </li>
            </ul>
        </div>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
            <span class="navbar-text text-dark ml-3 pr-3 pt-2">

            </span>
            <?php if (isset($_SESSION['compTerMasID'])) : ?>
                <a class="btn btn-secondary ml-3" href="starting_home.php?logout='1'">Logout</a>
            <?php endif ?>
        </div>
    </nav>


    <div class="container mb-5 px-5 py-3 bg-light rounded shadow">
        <div class="container my-4">
            <h6 class="mt-3"><b>Customer Booking Details</b></h6>
            <div class="mx-4">
                <?php foreach ($details as $detail) : ?>
                    <div class="mb-3 mt-4">
                        <b>Booked Customer <?php echo $x++ ?></b>
                    </div>
                    <div class="mx-1 row">
                        <div class="col-sm-12 col-md-6">
                            <span class="text-muted h6"> Fullname: </span>
                            <span><?php echo $detail['first_name'] . ' ' . $detail['last_name'] ?></span>
                        </div>
                    </div>
                    <div class="mx-1 row">
                        <div class="col-sm-12 col-md-6">
                            <span class="text-muted h6"> Seat number: </span>
                            <span><?php echo $detail['seat_number'] ?></span>
                        </div>
                    </div>
                    <div class="mx-1 row">
                        <div class="col-sm-12 col-md-12">
                            <span class="text-muted h6"> Valid ID: </span>
                            <span><?php echo $detail['valid_ID'] ?></span>
                            <?php
                            $file = $detail['valid_ID'];
                            $extention = pathinfo($file, PATHINFO_EXTENSION);
                            if (in_array($extention, ['jpeg', 'jpg', 'png'])) { ?>
                                <div class="col-4 mt-2">
                                    <a href="#imagemodal" class="btn btn-outline-primary shadow-sm py-1" data-id="../customer/uploads/<?php echo $detail['valid_ID']; ?>" data-bs-toggle="modal" data-bs-target="#imagemodal">View</a>
                                </div>
                            <?php } else if (in_array($extention, ['pdf'])) { ?>
                                <div class="col-4 mt-2">
                                    <form action="" method="POST">
                                        <button class="btn btn-outline-primary shadow-sm py-1" name="viewFilePDF" value="<?php echo $detail['valid_ID']; ?>">View</button>
                                    </form>
                                </div>
                            <?php
                            }
                            ?>
                        </div>

                        <div class="col-sm-12 col-md-12">
                            <span class="text-muted h6"> Vaccination Card/ Certificate: </span>
                            <span><?php echo $detail['vaccination_card'] ?></span>
                            <?php
                            $file = $detail['vaccination_card'];
                            $extention = pathinfo($file, PATHINFO_EXTENSION);
                            if (in_array($extention, ['jpeg', 'jpg', 'png'])) { ?>
                                <div class="col-4 mt-2">
                                    <a href="#imagemodal" class="btn btn-outline-primary shadow-sm py-1" data-id="../customer/uploads/<?php echo $detail['vaccination_card']; ?>" data-bs-toggle="modal" data-bs-target="#imagemodal">View</a>
                                </div>
                            <?php } else if (in_array($extention, ['pdf'])) { ?>
                                <div class="col-4 mt-2">
                                    <form action="" method="POST">
                                        <button class="btn btn-outline-primary shadow-sm py-1" name="viewFilePDF" value="<?php echo $detail['vaccination_card']; ?>">View</button>
                                    </form>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <span class="text-muted h6"> S-pass: </span>
                            <span><?php echo $detail['s_pass'] ?></span>
                            <?php
                            $file = $detail['s_pass'];
                            $extention = pathinfo($file, PATHINFO_EXTENSION);
                            if (in_array($extention, ['jpeg', 'jpg', 'png'])) { ?>
                                <div class="col-4 mt-2">
                                    <a href="#imagemodal" class="btn btn-outline-primary shadow-sm py-1" data-id="../customer/uploads/<?php echo $detail['s_pass']; ?>" data-bs-toggle="modal" data-bs-target="#imagemodal">View</a>
                                </div>
                            <?php } else if (in_array($extention, ['pdf'])) { ?>
                                <div class="col-4 mt-2">
                                    <form action="" method="POST">
                                        <button class="btn btn-outline-primary shadow-sm py-1" name="viewFilePDF" value="<?php echo $detail['s_pass']; ?>">View</button>
                                    </form>
                                </div>
                            <?php
                            }
                            ?>
                        </div>

                    <?php endforeach ?>
                    </div>
            </div>
        </div>

        <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" data-dismiss="modal">
                <img class="modal-content" id="img01" src="" class="imagepreview" style="width: 100%;">
            </div>
        </div>

        <footer class="text-center text-lg-start fixed-bottom">
            <div class="text-center text-light p-3" style="background-color: #212529">
                © 2021 Copyright:
                <a class="text-light" href="">Omnibus</a>
            </div>
        </footer>
</body>

</html>

<script>
    $('#imagemodal').on('show.bs.modal', function(e) {
        var bookId = $(e.relatedTarget).data('id');
        document.getElementById("img01").src = bookId;
    });
</script>