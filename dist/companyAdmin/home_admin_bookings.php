<?php
include('../database/db.php');
include('server_comp_admin.php'); ?>
<?php
if (session_id() == '') {
    session_start();
}

if (!isset($_SESSION['compadminName'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login_page_comAdmin.php');
    header('location: ../validation/logina.php');
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['compadminemail']);
    unset($_SESSION['compadminName']);
    unset($_SESSION['compadminID']);
    header('location: ../validation/logina.php');
}
$x = 1;
$name = $_SESSION['compadminName'];
$email = $_SESSION['compadminemail'];
$id = $_SESSION['compadminID'];

$resultt = $conn->query("SELECT * FROM terminal WHERE companyID = $id");
$terminalr = $resultt->fetch_all(MYSQLI_ASSOC);
//   $result = $conn->query("SELECT * FROM booking_tbl WHERE companyID = '$id'");
//   $details = $result->fetch_all(MYSQLI_ASSOC);
//   $result2 = $conn->query("SELECT * FROM trips WHERE companyID = '$name'");
//   $tripDetails = $result2->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>OmnibusAdmin</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <?php include 'extentions/bootstrap.php' ?>

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
    <?php include 'partials/navbar.php'; ?>

    <main class="d-flex flex-nowrap min-vh-100">
        <?php include 'partials/sidebar.php'; ?>

        <div class="position-relative flex-fill container bg-white py-3 px-2 ">


            <div class="table-responsive">
                <div class="table-wrapper">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-6  d-flex flex-row">
                                <h5 class="text-secondary">Bookings</h5>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle " type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <?php if (isset($_GET['today']) || !isset($_GET['today']) && !isset($_GET['on_going']) && !isset($_GET['cancelled']) && !isset($_GET['travelled']) && !isset($_GET['request']) && !isset($_GET['missed'])) {
                                            echo "Today";
                                        } else if (isset($_GET['on_going'])) {
                                            echo "Upcoming trips";
                                        } else if (isset($_GET['cancelled'])) {
                                            echo "Cancelled trips";
                                        } else if (isset($_GET['travelled'])) {
                                            echo "Travelled trips";
                                        } else if (isset($_GET['request'])) {
                                            echo "Cancellation requests";
                                        } else if (isset($_GET['missed'])) {
                                            echo "Trips missed ";
                                        } else {
                                            echo "views";
                                        }

                                        ?>
                                    </button>
                                    <form action="" method="GET">
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <button class="dropdown-item" name="today">Today</button>
                                            <button class="dropdown-item" name="on_going">Upcoming trips</button>
                                            <button class="dropdown-item" name="travelled">Travelled trips</button>
                                            <button class="dropdown-item" name="cancelled">Cancelled trips</button>
                                            <button class="dropdown-item" name="request">Cancellation requests</button>
                                            <button class="dropdown-item" name="missed">Trips missed</button>
                                            <!-- <a href="weekly_view.php" class="dropdown-item" >Weekly View</button> -->
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($_GET['today']) || !isset($_GET['today']) &&  !isset($_GET['on_going']) && !isset($_GET['cancelled']) && !isset($_GET['travelled']) && !isset($_GET['request']) && !isset($_GET['missed'])) {
                        include 'extentions/todayTableView.php';
                    } else if (isset($_GET['on_going'])) {
                        include 'extentions/onGoingTableView.php';
                    } else if (isset($_GET['cancelled'])) {
                        include 'extentions/cancelledTableView.php';
                    } else if (isset($_GET['travelled'])) {
                        include 'extentions/travelledTableView.php';
                    } else if (isset($_GET['request'])) {
                        include 'extentions/requestTableView.php';
                    } else if (isset($_GET['missed'])) {
                        include 'extentions/missedTableView.php';
                    }

                    ?>

                </div>
            </div>

        </div>
    </main>

    <footer class="text-center text-lg-start">
        <div class="text-center text-light p-3" style="background-color: #212529">
            © 2021 Copyright:
            <a class="text-light" href="./starting_home.php">Omnibus</a>
        </div>
    </footer>

    <div class="modal" id="mymodaldeleteBooking">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h6 class="text-light pt-2">Delete Booking</h6>
                    <div class="close text-white" data-bs-dismiss="modal">&times;</div>
                </div>
                <div class="modal-body">
                    <p>This action cannot be undone. are you you want to continue?</p>
                    <div class="float-right">
                        <p id="busidtext" style="display:none;"></p>
                        <?php  ?>
                        <form class="form" id="delete_book" action="" method="Post">
                            <input type="hidden" class="form-control" name="bookID" value="">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="delete_book" form="delete_book" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    function FetchRoutesBookings(id) {
        $('#filter_route_booking').html('');
        // alert(id);
        if (id == '') {
            $('#filter_route_booking').html('<option>Select Route</option>');
        } else {
            $.ajax({
                type: 'post',
                url: 'extentions/fetchtripfilter.php',
                data: {
                    terminalid: id
                },
                success: function(data) {
                    $('#filter_route_booking').html(data);
                }
            })
        }
    }

    $('#mymodaleditRoutes').on('show.bs.modal', function(e) {
        var recID = $(e.relatedTarget).data('userid');
        var id = recID;
        var pointA = $('#pointA' + id).text();
        var pointB = $('#pointB' + id).text();
        $(e.currentTarget).find('input[name="recID"]').val(recID);
        $(e.currentTarget).find('input[name="pointA"]').val(pointA);
        $(e.currentTarget).find('input[name="pointB"]').val(pointB);
    });

    $('#mymodaldeleteBooking').on('show.bs.modal', function(e) {
        var recID = $(e.relatedTarget).data('userid');
        $(e.currentTarget).find('input[name="bookID"]').val(recID);
    });

    $(document).ready(function() {
        $('#bookTable').DataTable({
            "dom": "<'row'<'col-sm-12 col-md-6'f><'col-sm-12 col-md-6'l>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "language": {
                "info": "Showing <b>_START_</b> to <b>_END_</b> of <b>_TOTAL_</b> entries"
            },
            columns: [
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                {
                    orderable: false
                }
            ],
        });
    });
</script>