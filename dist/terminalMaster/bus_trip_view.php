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
$compID = $_SESSION['compadminNameID'];
$termID = $_SESSION['terminalID'];
$email = $_SESSION['compTerMasemail'];
$id = $_SESSION['compTerMasID'];

$result = $conn->query("SELECT * FROM `user_partner_tmaster` WHERE `id` = '$id'");
$details = $result->fetch_all(MYSQLI_ASSOC);
$result2 = $conn->query("SELECT * FROM `bus_trip` WHERE `companyID` = '$compID'");
$tripDetails = $result2->fetch_all(MYSQLI_ASSOC);
$resultterminalDes = $conn->query("SELECT `terminalID` FROM `user_partner_tmaster` WHERE `id` = '$id'");
$terminaldestino = array_values($resultterminalDes->fetch_assoc());
$result3 = $conn->query("SELECT * FROM `terminal` WHERE `companyID` = '$compID'");
$tdetails = $result3->fetch_all(MYSQLI_ASSOC);
$result4 = $conn->query("SELECT DISTINCT `busID` FROM `bus_trip` WHERE `companyID` = '$compID' AND `terminalID` = '$termID'");
$buses = $result4->fetch_all(MYSQLI_ASSOC);
$result5 = $conn->query("SELECT * FROM `routes` WHERE `companyID` = '$compID' AND `pointA_terminalID` = '$termID'");
$allroutes = $result5->fetch_all(MYSQLI_ASSOC);

$result6 = $conn->query("SELECT * FROM `buses` WHERE `companyID` = '$compID'");
$busesss = $result6->fetch_all(MYSQLI_ASSOC);
$result7 = $conn->query("SELECT id,pointA,pointB,duration FROM `routes` WHERE `companyID` = '$compID' AND `pointA_terminalID` = '$termID'");
$termtrips = $result7->fetch_all(MYSQLI_ASSOC);
$result8 = $conn->query("SELECT * FROM `employees` WHERE `companyID` = '$compID'");
$emps = $result8->fetch_all(MYSQLI_ASSOC);
// echo $compID;


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


            <div class="container container-large mb-5 p-0 rounded shadow">
                <?php include('errors.php'); ?>
            </div>

            <div class="container container-large mb-5 px-5 py-3 bg-light rounded shadow">
                <div class="table-responsive">
                    <div class="table-wrapper">
                        <div class="table-title">
                            <div class="row mb-2">
                                <div class="col-sm-6 d-flex flex-row">

                                    <h5 class="text-secondary mt-2">Manage bus trips</h5>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle mt-2" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?php if (isset($_GET['terminal_view']) || !isset($_GET['terminal_view']) && !isset($_GET['bus_view']) && !isset($_GET['weekly_view'])) {
                                                echo "Terminal view";
                                            } else if (isset($_GET['bus_view'])) {
                                                echo "Bus view";
                                            } else if (isset($_GET['weekly_view'])) {
                                                echo "Weekly view";
                                            } else {
                                                echo "views";
                                            }

                                            ?>
                                        </button>
                                        <form action="" method="GET">
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <button class="dropdown-item" name="terminal_view">Terminal View</button>
                                                <button class="dropdown-item" name="bus_view">Bus View</button>
                                                <button class="dropdown-item" name="weekly_view">Weekly View</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                    <a href="#" class="btn btn-primary col-4" data-bs-toggle="modal" data-bs-target="#addBusTripModal"><i class="material-icons">&#xE145;</i><span>Add Bus Trip</span></a>
                                </div>
                            </div>
                            <hr>
                        </div>
                        <?php
                        if (isset($_GET['terminal_view']) || !isset($_GET['terminal_view']) && !isset($_GET['bus_view']) && !isset($_GET['weekly_view'])) {
                        ?>
                            <div class="row mx-2 py-2  bg-light">
                                <div class="col">
                                    <label class="h6 text-muted">Filter route</label>
                                    <select class="select form-select text-muted" name="filter_route" id="filter_route" aria-label="Default select example">
                                        <option value="" class="text-muted" selected>Select route</option>
                                        <?php foreach ($allroutes as $route) : ?>
                                            <option value="<?php echo $route['id']; ?>"><?php echo $route['pointA'] . ' - ' . $route['pointB']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="h6 text-muted">Filter Date</label>
                                    <input type="date" class="form-control" name="filter_date" id="filter_date" required>
                                </div>
                                <input type="hidden" class="form-control" name="filter_termid" id="filter_termid" value="<?php echo $termID ?>" readonly>

                                <div class="col-2"><br>
                                    <button type="button" name="filter" id="filter" class="btn btn-info mt-1 px-5">Filter</button>
                                </div>
                            </div>
                            <table id="bustrip" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-secondary">id</th>
                                        <th class="text-secondary">date</th>
                                        <th class="text-secondary">time</th>
                                        <th class="text-secondary">fare</th>
                                        <th class="text-secondary">bus #</th>
                                        <th class="text-secondary">driver</th>
                                        <th class="text-secondary">conductor</th>
                                        <th class="text-secondary">assigned by</th>
                                        <th class="text-secondary">action</th>
                                    </tr>
                                </thead>
                            </table>
                        <?php }
                        if (isset($_GET['bus_view'])) { ?>
                            <div class="row mx-2 py-2  bg-light">
                                <div class="col">
                                    <label class="h6 text-muted">Filter bus</label>
                                    <select class="select form-select text-muted" name="filter_bus" id="filter_bus" onchange="" aria-label="Default select example">
                                        <option value="" class="text-muted" selected>Select bus</option>
                                        <?php foreach ($buses as $bus) : ?>

                                            <option value="<?php echo $bus['busID']; ?>">
                                                <?php
                                                $busiddd = $bus['busID'];
                                                $result113 = $conn->query("SELECT seat_type,total_seat,bus_model,busNumber FROM buses WHERE id = '$busiddd' AND companyID = '$compID'");
                                                $busess = array_values($result113->fetch_assoc());
                                                echo $busess[3] . " (" . $busess[2] . ") | " . $busess[0] . " seat type | " . $busess[1] . " total seat)";
                                                ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="h6 text-muted">Filter date range</label>
                                    <input type="text" class="form-control" id="filter_date_range" name="filter_date_range" min="<?php echo date("Y-m-d"); ?>" placeholder="00:00PM" required>
                                </div>
                                <div class="col-2"><br>
                                    <button type="button" name="filter2" id="filter2" class="btn btn-info mt-1 px-5">Filter</button>
                                </div>
                            </div>
                            <table id="bustrip_busview" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-secondary">id</th>
                                        <th class="text-secondary">route</th>
                                        <th class="text-secondary">date</th>
                                        <th class="text-secondary">time</th>
                                        <th class="text-secondary">fare</th>
                                        <th class="text-secondary">driver</th>
                                        <th class="text-secondary">conductor</th>
                                        <th class="text-secondary">assigned by</th>
                                        <!-- <th class="text-secondary">Actions</th> -->
                                    </tr>
                                </thead>
                            </table>
                        <?php }
                        if (isset($_GET['weekly_view'])) {
                            include 'weekly_view.php'; ?>
                        <?php } ?>
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
                        <div class="form-group md-6">
                            <label class="h6 text-muted">Fare</label>
                            <input type="number" class="form-control" name="fare" placeholder="" required>
                        </div>
                        <button class="btn btn-primary float-right mr-3 mt-2" type="submit" name="edit_bus_trip" form="edit_bus_trip">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="editBusTripModaldc">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Change employee & conductor</span>
                    <div class="close text-white" data-bs-dismiss="modal">&times;</div>
                </div>
                <div class="modal-body">
                    <form class="form" id="edit_bus_trip_employee" action="" method="Post">
                        <input type="hidden" class="form-control" name="bustripID" value="">
                        <div class="row">
                            <div class="col form-group md-6">
                                <label class="h6 text-muted">Driver</label>
                                <select class="form-select text-muted" name="optionbusdriver" aria-label="Default select example">
                                    <option value="" class="text-muted" selected>Select Driver</option>
                                    <?php
                                    foreach ($emps as $emp) {
                                        if ($emp['role'] == "Driver") {
                                    ?>
                                            <option value="<?php echo $emp['id']; ?>"><?php echo $emp['fullname'] ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="col form-group md-6">
                                <label class="h6 text-muted">Conductor</label>
                                <select class="form-select text-muted" name="optionbusconductor" aria-label="Default select example">
                                    <option value="" class="text-muted" selected>Select Conductor</option>
                                    <?php
                                    foreach ($emps as $emp) {
                                        if ($emp['role'] == "Conductor") {
                                    ?>
                                            <option value="<?php echo $emp['id']; ?>"><?php echo $emp['fullname'] ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-primary float-right mr-3 mt-2" type="submit" name="edit_bus_trip_employee" form="edit_bus_trip_employee">Save Changes</button>
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
                            <input type="text" class="form-control" name="bustripID" value="">
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
                            <select class="form-select text-muted" name="optionBus" id="optionBus" aria-label="Default select example" required>
                                <option value="" class="text-muted" selected>Select Bus</option>
                                <?php foreach ($busesss as $bus) : ?>
                                    <option value="<?php echo $bus['id']; ?>"><?php echo $bus['busNumber'] . " (" . $bus['bus_model'] . ") | " . $bus['seat_type'] . " seat type | " . $bus['total_seat'] . " total seat)"   ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row mb-4">
                            <div class="col form-group md-6">
                                <label class="h6 text-muted">Set Driver</label>
                                <select class="form-select text-muted" name="optionbusdriver" aria-label="Default select example" required>
                                    <option value="" class="text-muted" selected>Select Driver</option>
                                    <?php
                                    foreach ($emps as $emp) {
                                        if ($emp['role'] == "Driver") {
                                    ?>
                                            <option value="<?php echo $emp['id']; ?>"><?php echo $emp['fullname'] ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="col form-group md-6">
                                <label class="h6 text-muted">Set Conductor</label>
                                <select class="form-select text-muted" name="optionbusconductor" aria-label="Default select example" required>
                                    <option value="" class="text-muted" selected>Select Conductor</option>
                                    <?php
                                    foreach ($emps as $emp) {
                                        if ($emp['role'] == "Conductor") {
                                    ?>
                                            <option value="<?php echo $emp['id']; ?>"><?php echo $emp['fullname'] ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col form-group md-6 mb-4">
                                <label class="h6 text-muted">Trip</label>
                                <select class="form-select text-muted" name="optionTrip" id="optionTrip" onchange="FetchTime(this.value)" aria-label="Default select example" required>
                                    <option value="" class="text-muted" selected>Select Trip</option>
                                    <?php foreach ($termtrips as $tms) : ?>
                                        <option value="<?php echo $tms['id'] ?>"><?php echo $tms['pointA'] . ' - ' . $tms['id'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col form-group md-6 mb-4">
                                <label class="h6 text-muted">Time</label>
                                <select class="form-select text-muted" name="optionTripTime" id="optionTripTime" aria-label="Default select example">
                                    <option value="" class="text-muted" selected>Select Time</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group md-6">
                            <label class="h6 text-muted">Fare</label>
                            <input type="number" class="form-control" name="fare" placeholder="" required>
                        </div>
                        <div class="row mb-3">
                            <h6 class="mt-3 text-muted">Period Operating:</h6>
                            <div class="col-6 form-group md-6">
                                <label class="h6 text-muted"><small>Start - End</small></label>
                                <input type="text" class="form-control" id="operateFrom1" name="operateFrom" min="<?php echo date("Y-m-d"); ?>" placeholder="00:00PM" required>
                            </div>
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

</body>

</html>

<script>
    function FetchDates(id) {
        $('#filter_date').html('');
        var val = $("#filter_termid :selected").val();
        if (id == '') {
            $('#filter_date').html('<option>Select Date</option>');
        } else {
            $.ajax({
                type: 'post',
                url: 'fetchdatefilter.php',
                data: {
                    routeid: id,
                    terminalid: val
                },
                success: function(data) {
                    $('#filter_date').html(data);
                }
            })
        }
    }

    function FetchData(id) {
        $('#optionTrip').html('');
        if (id == '') {
            $('#optionTrip').html('<option>Select Trip</option>');
            $('#optionTripTime').html('<option>Select Time</option>');
        } else {
            $.ajax({
                type: 'post',
                url: 'fetchtripoption.php',
                data: {
                    terminalid: id
                },
                success: function(data) {
                    $('#optionTrip').html(data);
                }
            })
        }
    }

    function FetchDate(id) {
        $('#optionTripTime').html('');
        var val = $("#optionTerminalbus :selected").val();
        if (id == '') {
            $('#optionTripTime').html('<option>Select Time</option>');
        } else {
            $.ajax({
                type: 'post',
                url: 'fetchtriptimeoption.php',
                data: {
                    routeid: id,
                    terminalid: val
                },
                success: function(data) {
                    $('#optionTripTime').html(data);
                }
            })
        }
    }

    function FetchTime(id) {
        $('#optionTripTime').html('');
        if (id == '') {
            $('#optionTripTime').html('<option>Select Time</option>');
        } else {
            $.ajax({
                type: 'post',
                url: 'extentions/fetchtriptimeoption.php',
                data: {
                    routeid: id
                },
                success: function(data) {
                    $('#optionTripTime').html(data);
                }
            })
        }
    }

    $('input[name="operateFrom"]').daterangepicker({
        minDate: moment()
    });

    $('input[name="filter_date_range"]').daterangepicker();

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
        var fare = $('#fare' + id).text();
        var busid = $('#busID' + id).text();
        var busname = $('#busName' + id).text();
        $(e.currentTarget).find('input[name="bustripID"]').val(bustripID);
        $(e.currentTarget).find('input[name="fare"]').val(fare);
    });

    $('#editBusTripModaldc').on('show.bs.modal', function(e) {
        var bustripID = $(e.relatedTarget).data('userid');
        var id = bustripID;
        $(e.currentTarget).find('input[name="bustripID"]').val(bustripID);
    });

    $('#mymodaldeleteBusTrip').on('show.bs.modal', function(e) {
        var bustripID = $(e.relatedTarget).data('userid');
        var id = bustripID;
        $(e.currentTarget).find('input[name="bustripID"]').val(bustripID);
    });

    $(document).ready(function() {
        function fill_datatable(filter_route = '', filter_date = '') {
            var datatable = $('#bustrip').DataTable({
                "dom": "<'row'<'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'i><'col-sm-12 col-md-4'p>>",
                columns: [
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
                "language": {
                    "lengthMenu": "<small>Rows per page _MENU_ </small>"
                },
                "processing": true,
                "serverSide": true,
                "order": [],
                "searching": false,
                "ajax": {
                    url: "extentions/fetchbustriptable.php",
                    type: "POST",
                    data: {
                        filter_route: filter_route,
                        filter_date: filter_date
                    }
                }
            });
        }

        $('#filter').click(function() {
            var filter_route = $("#filter_route :selected").val();
            var filter_date = $("#filter_date").val();
            if (filter_route != '' && filter_date == '') {
                $('#bustrip').DataTable().destroy();
                fill_datatable(filter_route, filter_date);
            } else if (filter_route != '' && filter_date != '') {
                $('#bustrip').DataTable().destroy();
                fill_datatable(filter_route, filter_date);
            } else {
                $('#bustrip').DataTable().destroy();
                $('#bustrip').dataTable().fnClearTable();
            }
        });
    });

    $(document).ready(function() {
        function fill_datatable2(filter_bus = '', filter_date_range = '') {
            var datatable = $('#bustrip_busview').DataTable({
                "dom": "<'row'<'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'i><'col-sm-12 col-md-4'p>>",
                columns: [
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null
                ],
                "language": {
                    "lengthMenu": "<small>Rows per page _MENU_ </small>"
                },
                "processing": true,
                "serverSide": true,
                "order": [],
                "searching": false,
                "ajax": {
                    url: "extentions/fetchbustriptable_busview.php",
                    type: "POST",
                    data: {
                        filter_bus: filter_bus,
                        filter_date_range: filter_date_range
                    }
                }
            });
        }

        $('#filter2').click(function() {
            var filter_bus = $("#filter_bus :selected").val();
            var filter_date_range = $("#filter_date_range").val();
            if (filter_bus != '' && filter_date_range != '') {
                $('#bustrip_busview').DataTable().destroy();
                fill_datatable2(filter_bus, filter_date_range);
            } else {
                $('#bustrip_busview').DataTable().destroy();
                $('#bustrip_busview').dataTable().fnClearTable();
            }
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
            }

        });
    });
</script>