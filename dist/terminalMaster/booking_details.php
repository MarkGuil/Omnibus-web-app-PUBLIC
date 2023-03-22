<?php
include('../database/db.php');
include('server_terminal_master.php')
?>
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
//   echo $id;
$result = $conn->query("SELECT * FROM user_partner_tmaster WHERE id = '$id'");
$details = $result->fetch_all(MYSQLI_ASSOC);
$result2 = $conn->query("SELECT * FROM booking_tbl WHERE companyID = '$compID'");
$bookingDetails = $result2->fetch_all(MYSQLI_ASSOC);
$result4 = $conn->query("SELECT terminalID FROM user_partner_tmaster WHERE id = '$id'");
$terminaldestino = array_values($result4->fetch_assoc());
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OmnibusTerminal</title>
    <?php include 'extentions/bootstrap.php' ?>
    <link rel="stylesheet" type="text/css" href="extentions/style.css">


</head>

<body>
    <?php include 'partials/navbar.php'; ?>

    <main class="d-flex flex-nowrap min-vh-100">
        <?php include 'partials/sidebar.php'; ?>

        <div class="position-relative flex-fill container  py-3 px-2 ">

            <div class="container container-large mb-5 px-5 py-3 bg-light rounded shadow">
                <?php if (isset($_SESSION['compTerMassuccess'])) { ?>
                    <div class="container container-large mb-5 p-0 rounded shadow">
                        <div class="alert alert-success mt-3" role="alert">

                            <form action="" method="get" class="d-flex justify-content-between" id="unsetSuccessAlert">
                                <?php echo $_SESSION['compTerMassuccess']  ?>
                                <button type="submit" class="close" name="unsetSuccessAlert" form="unsetSuccessAlert" class="btn-close" aria-label="Close">&times;</button>
                            </form>

                        </div>
                    </div>
                <?php } ?>
                <div class="container container-large mb-5 p-0 rounded shadow">
                    <?php include('errors.php'); ?>
                </div>
                <div class="table-responsive">
                    <div class="table-wrapper">
                        <div class="table-title">
                            <div class="row">
                                <div class="col-sm-6  d-flex flex-row">
                                    <h5 class="text-secondary">Bookings</h5>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle mt-2" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?php if (isset($_GET['today']) || !isset($_GET['today']) && !isset($_GET['on_going']) && !isset($_GET['cancelled']) && !isset($_GET['travelled']) && !isset($_GET['request']) && !isset($_GET['missed'])) {
                                                echo "Today";
                                            } else if (isset($_GET['on_going'])) {
                                                echo "Upcoming trips";
                                            } else if (isset($_GET['cancelled'])) {
                                                echo "Cancelled trips";
                                            } else if (isset($_GET['travelled'])) {
                                                echo "Travelled trips";
                                            } else if (isset($_GET['request'])) {
                                                echo "Requests for cancellation of trips";
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
                                                <button class="dropdown-item" name="request">Requests for cancellation of trips</button>
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
        </div>
    </main>

    <footer class="text-center text-lg-start fixed-bottom">
        <div class="text-center text-light p-3" style="background-color: #212529">
            © 2021 Copyright:
            <a class="text-light" href="">Omnibus</a>
        </div>
    </footer>

    <div class="modal" id="mymodaldeleteBooking">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h6 class="text-light pt-2">Delete Booking</h6>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>This action cannot be undone. Are you sure you want to continue?</p>
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
</head>

</html>

<script>
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
                {
                    orderable: false
                }
            ],
        });
    });
</script>