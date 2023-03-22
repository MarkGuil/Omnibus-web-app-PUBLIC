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
$compID = $_SESSION['compadminNameID'];
$termID = $_SESSION['terminalID'];
$email = $_SESSION['compTerMasemail'];
$id = $_SESSION['compTerMasID'];

$result = $conn->query("SELECT * FROM user_partner_tmaster WHERE id = '$id'");
$details = $result->fetch_all(MYSQLI_ASSOC);
$result2 = $conn->query("SELECT * FROM bus_trip WHERE companyID = '$compID'");
$tripDetails = $result2->fetch_all(MYSQLI_ASSOC);
$resultterminalDes = $conn->query("SELECT terminalID FROM user_partner_tmaster WHERE id = '$id'");
$terminaldestino = array_values($resultterminalDes->fetch_assoc());
$result3 = $conn->query("SELECT * FROM terminal WHERE companyID = '$compID'");
$tdetails = $result3->fetch_all(MYSQLI_ASSOC);
$result4 = $conn->query("SELECT DISTINCT origin,destination,routeID FROM trip WHERE companyID = '$compID' AND terminalID = '$termID'");
$routes = $result4->fetch_all(MYSQLI_ASSOC);
$result5 = $conn->query("SELECT * FROM routes WHERE companyID = '$compID' AND pointA_terminalID = '$termID'");
$allroutes = $result5->fetch_all(MYSQLI_ASSOC);
// echo $id;


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
                <div class="container container-large mb-5 p-0 rounded shadow">
                    <?php include('errors.php'); ?>
                </div>
                <div class="table-responsive">
                    <div class="table-wrapper">
                        <div class="table-title">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5 class="mt-1 text-secondary">Manage trips</h5>
                                </div>
                                <div class="col-sm-6 ">
                                    <a href="add_trip.php" class="btn btn-primary col-4"><i class="material-icons">&#xE145;</i><span>Add New Trip</span></a>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-4 ">
                                    <label class="h6 text-muted">Filter route</label>
                                    <select class="select form-select text-muted" name="filter_route" id="filter_route" aria-label="Default select example">
                                        <option value="" class="text-muted" selected>Select route</option>
                                        <?php foreach ($allroutes as $route) : ?>
                                            <option value="<?php echo $route['id']; ?>"><?php echo $route['pointA'] . ' - ' . $route['pointB']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-1"><br>
                                    <button type="button" name="filter" id="filter" class="btn btn-primary p-2 mt-1">Filter</button>
                                </div>
                            </div>
                        </div>
                        <table id="ontripTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-secondary">id</th>
                                    <th class="text-secondary">origin</th>
                                    <th class="text-secondary">destination</th>
                                    <th class="text-secondary">departure_time</th>
                                    <th class="text-secondary">duration</th>
                                    <th class="text-secondary">action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer class="text-center text-lg-start">
        <div class="text-center text-light p-3" style="background-color: #212529">
            © 2021 Copyright:
            <a class="text-light" href="">Omnibus</a>
        </div>
    </footer>

    <div class="modal" id="editTripModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Edit Trip</span>
                    <div class="close text-white" data-bs-dismiss="modal">&times;</div>
                </div>
                <div class="modal-body">
                    <form class="form" id="edit_trip" action="" method="post">
                        <div class="cont-popup cont col-sm-12 col-sm-offset-12">
                            <input type="hidden" class="form-control" name="tripID" value="">
                            <div class="row">
                                <div class="col form-group md-6">
                                    <label class="h6 text-muted">Origin</label>
                                    <input type="text" class="form-control" name="origin" placeholder="" readonly>
                                </div>
                                <div class="col form-group md-6">
                                    <label class="h6 text-muted">Destination</label>
                                    <input type="text" class="form-control" name="destination" placeholder="00:00PM" readonly>
                                </div>
                            </div>
                            <div class="form-group md-6">
                                <div class="row">
                                    <div class="col form-group md-6">
                                        <label class="h6 text-muted">Departure TIme</label>
                                        <input type="text" class="form-control" name="departure" placeholder="00:00PM" readonly>
                                    </div>
                                </div>
                                <label class="h6 text-muted">Trip Duration</label>
                                <div class="row">
                                    <div class="col d-flex flex-row pb-0">
                                        <input type="number" class="form-control" name="hour" id="hour" placeholder="" value="00" maxlength="2" size="2" max="24" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" readonly>
                                        <p class="p-2 text-muted"><small> Hour</small></p>
                                    </div>
                                    <div class="col d-flex flex-row pb-0">
                                        <input type="number" class="form-control" name="minute" id="minute" placeholder="" value="00" maxlength="2" size="2" max="60" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" readonly>
                                        <p class="p-2 text-muted"><small> Minutes</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary float-right mr-3 mt-2" type="submit" name="edit_trip" form="edit_trip">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="editBusTripModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Change bus</span>
                    <div class="close text-white" data-bs-dismiss="modal">&times;</div>
                </div>
                <div class="modal-body">
                    <form class="form" id="edit_bus_trip" action="" method="Post">
                        <div class=" form-group md-6 mb-4">
                            <input type="hidden" class="form-control" name="bustripID" value="">
                            <label class="h6 text-muted">Bus</label>
                            <select class="form-select text-muted" name="optionBus" id="optionBus" aria-label="Default select example">
                                <option value="" class="text-muted" selected>Select Bus</option>
                                <?php foreach ($buses as $bus) : ?>
                                    <option value="<?php echo $bus['id']; ?>"><?php echo $bus['busNumber'] . " (" . $bus['bus_model'] . ") | " . $bus['seat_type'] . " seat type | " . $bus['total_seat'] . " total seat)"   ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button class="btn btn-primary float-right mr-3 mt-2" type="submit" name="edit_bus_trip" form="edit_bus_trip">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="mymodaldeleteBusTrip">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h6 class="text-light pt-2">Delete Trip</h6>
                    <div class="close text-white" data-bs-dismiss="modal">&times;</div>
                </div>
                <div class="modal-body">
                    <p>This action cannot be undone. are you you want to continue?</p>
                    <div class="float-right">
                        <p id="busidtext" style="display:none;"></p>
                        <?php  ?>
                        <form class="form" id="delete_bus_trip" action="" method="Post">
                            <input type="hidden" class="form-control" name="bustripID" value="">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="delete_bus_trip" form="delete_bus_trip" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="addBusTripModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Add <span id="namee"></span>new bus trip</span>
                    <div class="close text-white" data-bs-dismiss="modal">&times;</div>
                </div>
                <div class="modal-body">
                    <form class="form" id="add_bus_trip" action="" method="Post">
                        <div class=" form-group md-6 mb-4">
                            <label class="h6 text-muted">Bus</label>
                            <select class="form-select text-muted" name="optionBus" id="optionBus" aria-label="Default select example">
                                <option value="" class="text-muted" selected>Select Bus</option>
                                <?php foreach ($buses as $bus) : ?>
                                    <option value="<?php echo $bus['id']; ?>"><?php echo $bus['busNumber'] . " (" . $bus['bus_model'] . ") | " . $bus['seat_type'] . " seat type | " . $bus['total_seat'] . " total seat)"   ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class=" form-group md-6 mb-4">
                            <label class="h6 text-muted">Trip</label>
                            <select class="form-select text-muted" name="optionTrip" id="optionTrip" aria-label="Default select example">
                                <option value="" class="text-muted" selected>Select Trip</option>
                                <?php foreach ($trips as $trip) : ?>
                                    <option value="<?php echo $trip['id']; ?>"><?php echo $trip['origin'] . " - " . $trip['destination'] . " | " . date('h:i A', strtotime($trip["departure_time"])); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row mb-3">
                            <h6 class="mt-3 text-muted">Period Operating:</h6>
                            <div class="col-6 form-group md-6">
                                <label class="h6 text-muted"><small>Start</small></label>
                                <input type="text" class="form-control" id="operateFrom1" name="operateFrom" min="<?php echo date("Y-m-d"); ?>" placeholder="00:00PM" required>
                            </div>
                            <!-- <div class="col-4 form-group md-6">
                                <label class="h6 text-muted"><small>End</small></label>
                                <input type="date" class="form-control" id="operateTo1" name="operateTo" required>
                            </div> -->
                        </div>
                        <div class="row mt-1">
                            <div class="col-2">
                                <h6 class="text-muted">Recurring</h6>
                            </div>
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" name="recur[]" type="checkbox" value="Monday" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Every Monday
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" name="recur[]" type="checkbox" value="Tuesday" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Every Tuesday
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" name="recur[]" type="checkbox" value="Wednesday" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Every Wednesday
                                    </label>
                                </div>
                                <!-- </div>
                            <div class="col"> -->
                                <div class="form-check">
                                    <input class="form-check-input" name="recur[]" type="checkbox" value="Thursday" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Every Thursday
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" name="recur[]" type="checkbox" value="Friday" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Every Friday
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" name="recur[]" type="checkbox" value="Saturday" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Every Saturday
                                    </label>
                                </div>
                                <!-- </div> -->
                                <!-- <div class="col"> -->
                                <div class="form-check">
                                    <input class="form-check-input" name="recur[]" type="checkbox" value="Sunday" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Every Sunday
                                    </label>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary float-right mr-3 mt-2" type="submit" name="add_bus_trip" form="add_bus_trip">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="mymodaldeleteTrips">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h6 class="text-light pt-2">Delete Trip</h6>
                    <div class="close text-white" data-bs-dismiss="modal">&times;</div>
                </div>
                <div class="modal-body">
                    <p>This action cannot be undone. are you you want to continue?</p>
                    <div class="float-right">
                        <p id="busidtext" style="display:none;"></p>
                        <?php  ?>
                        <form class="form" id="delete_trip" action="" method="Post">
                            <input type="hidden" class="form-control" name="tripID" value="">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="delete_trip" form="delete_trip" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    function FetchRoutes(id) {
        $('#filter_route').html('');
        if (id == '') {
            $('#filter_route').html('<option>Select route</option>');
        } else {
            $.ajax({
                type: 'post',
                url: 'extentions/fetchtripoption.php',
                data: {
                    terminalid: id
                },
                success: function(data) {
                    $('#filter_route').html(data);
                }
            })
        }
    }
    $('input[name="operateFrom"]').daterangepicker();

    $(".select option").each(function() {
        $(this).siblings('[value="' + this.value + '"]').remove();
    });

    $("#optionRoute").on("input", function() {
        var route = $(this).val();
        document.getElementById("fare").val(route);
    });

    $('#editBusTripModal').on('show.bs.modal', function(e) {
        var bustripID = $(e.relatedTarget).data('userid');
        var id = bustripID;
        $(e.currentTarget).find('input[name="bustripID"]').val(bustripID);
    });

    $('#mymodaldeleteBusTrip').on('show.bs.modal', function(e) {
        var bustripID = $(e.relatedTarget).data('userid');
        var id = bustripID;
        $(e.currentTarget).find('input[name="bustripID"]').val(bustripID);
    });

    $('#editTripModal').on('show.bs.modal', function(e) {
        var tripID = $(e.relatedTarget).data('userid');
        var id = tripID;
        var or = $('#origin' + id).text();
        var dest = $('#destination' + id).text();
        var departure = $('#departure' + id).text();
        var duration = $('#duration' + id).text();
        var hour = $('#hour' + id).text();
        var mins = $('#mins' + id).text();
        $(e.currentTarget).find('input[name="origin"]').val(or);
        $(e.currentTarget).find('input[name="tripID"]').val(tripID);
        $(e.currentTarget).find('input[name="destination"]').val(dest);
        $(e.currentTarget).find('input[name="departure"]').val(departure);
        $(e.currentTarget).find('input[name="duration"]').val(duration);
        $(e.currentTarget).find('input[name="hour"]').val(hour);
        $(e.currentTarget).find('input[name="minute"]').val(mins);
    });

    $('#mymodaldeleteTrips').on('show.bs.modal', function(e) {
        var tripID = $(e.relatedTarget).data('userid');
        var id = tripID;
        $(e.currentTarget).find('input[name="tripID"]').val(tripID);
    });

    $(document).ready(function() {
        fill_datatable();

        function fill_datatable(filter_route = '') {
            var datatable = $('#ontripTable').DataTable({
                "dom": "<'row'<'col-sm-12 col-md-6'f><'col-sm-12 col-md-12'l>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                columns: [
                    null,
                    null,
                    null,
                    null,
                    null,
                    {
                        orderable: false
                    }
                ],
                "processing": true,
                "serverSide": true,
                "order": [],
                "searching": false,
                "ajax": {
                    url: "fetch.php",
                    type: "POST",
                    data: {
                        filter_route: filter_route
                    }
                }
            });
        }

        $('#filter').click(function() {
            var filter_route = $("#filter_route :selected").val();
            if (filter_route != '') {
                $('#ontripTable').DataTable().destroy();
                fill_datatable(filter_route);
            } else {
                $('#ontripTable').DataTable().destroy();
                fill_datatable();
            }
        });
    });

    $(document).ready(function() {
        $('#bustrip').DataTable({
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
                {
                    orderable: false
                }
            ],
        });
    });

    $(function() {
        $('#optionRoute').change(function() {
            var text = $("#optionRoute :selected").text();
            var val = $("#optionRoute :selected").val();
            if (val == 0) {
                $('.tripConfig').hide();
            } else {
                $('.tripConfig').hide();
                $('#tripConfig').show();
                document.getElementById("oridest").innerHTML = text;
                // document.getElementById("oridest").val(text);
                // document.getElementById("myspan").textContent=text;
            }

        });
    });
</script>