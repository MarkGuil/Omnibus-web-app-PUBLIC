<?php
include('../database/db.php');
include('server_comp_admin.php'); ?>
<?php
if (session_id() == '') {
    session_start();
}

if (!isset($_SESSION['compadminName'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../validation/logina.php');
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['compadminemail']);
    unset($_SESSION['compadminName']);
    unset($_SESSION['compadminID']);
    header('location: ../validation/logina.php');
}

$name = $_SESSION['compadminName'];
$email = $_SESSION['compadminemail'];
$id = $_SESSION['compadminID'];

$qry = "SELECT a.id, a.origin, a.destination, a.departure_time, a.routeID, b.trip_date, b.tripID, b.busID FROM trip a LEFT OUTER JOIN bus_trip b ON(a.id = b.tripID) WHERE a.companyID = $id";
$result12 = mysqli_query($db, $qry);

$result222 = $conn->query("SELECT a.id, a.origin, a.destination, a.departure_time, a.routeID, b.id, b.trip_date, b.tripID, b.busID FROM trip a LEFT OUTER JOIN bus_trip b ON(a.id = b.tripID) AND a.companyID = $id AND b.companyID = $id");
$tripss = $result222->fetch_all(MYSQLI_ASSOC);

$result = $conn->query("SELECT * FROM trip WHERE companyID = $id");
$trips = $result->fetch_all(MYSQLI_ASSOC);
$result = $conn->query("SELECT DISTINCT terminalID FROM trip WHERE companyID = $id");
$tripTermIDS = $result->fetch_all(MYSQLI_ASSOC);
$result2 = $conn->query("SELECT * FROM routes WHERE companyID = $id");
$routes = $result2->fetch_all(MYSQLI_ASSOC);
$result3 = $conn->query("SELECT * FROM buses WHERE companyID = $id");
$buses = $result3->fetch_all(MYSQLI_ASSOC);
$result4 = $conn->query("SELECT DISTINCT pointA_terminalID,pointB_terminalID FROM routes WHERE companyID = $id");
$termids = $result4->fetch_all(MYSQLI_ASSOC);
$result5 = $conn->query("SELECT * FROM employees WHERE companyID = '$id'");
$emps = $result5->fetch_all(MYSQLI_ASSOC);
$result6 = $conn->query("SELECT DISTINCT origin,destination,routeID FROM trip WHERE companyID = $id ");
$routesF = $result6->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>OmnibusAdmin</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <?php include '../bootstrap/bootstrap.php' ?>

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

            <?php if (isset($_SESSION['compadminsuccess'])) { ?>
                <div class="container container-large mb-5 p-0 rounded shadow">
                    <div class="alert alert-success mt-3" role="alert">
                        <form action="" method="get" class="d-flex justify-content-between" id="unsetSuccessAlert">
                            <?php echo $_SESSION['compadminsuccess']  ?>
                            <button type="submit" name="unsetSuccessAlert" form="unsetSuccessAlert" class="btn-close" aria-label="Close"></button>
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
                        <div class="row mb-2">
                            <div class="col-sm-6 d-flex flex-row">

                                <h5 class="text-secondary mt-2">Manage bus trips</h5>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle mt-2" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
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
                                            <!-- <a href="weekly_view.php" class="dropdown-item" >Weekly View</button> -->
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
                                <label class="h6 text-muted">Filter Terminal</label>
                                <select class="select form-select text-muted" name="filter_termid" id="filter_termid" onchange="FetchRoutes(this.value)" aria-label="Default select example">
                                    <option value="" class="text-muted" selected>Select terminal</option>
                                    <?php foreach ($tripTermIDS as $tripTermID) : ?>
                                        <option value="<?php echo $tripTermID['terminalID']; ?>">
                                            <?php
                                            $termidss = $tripTermID['terminalID'];
                                            $ress = $conn->query("SELECT terminal_name FROM terminal WHERE id='$termidss' AND companyID='$id'");
                                            $trpress = array_values($ress->fetch_assoc());
                                            echo $trpress[0];
                                            ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col">
                                <label class="h6 text-muted">Filter route</label>
                                <select class="select form-select text-muted" name="filter_route" id="filter_route" onchange="FetchDates(this.value)" aria-label="Default select example">
                                    <option value="" class="text-muted" selected>Select route</option>
                                    <!-- <?php foreach ($routesF as $routeF) : ?>
                                <option value="<?php echo $routeF['routeID']; ?>">
                                <?php
                                                echo $routeF['origin'] . ' - ' . $routeF['destination'];
                                ?>
                                </option>
                            <?php endforeach; ?> -->
                                </select>
                            </div>
                            <div class="col">
                                <label class="h6 text-muted">Filter Date</label>
                                <input type="date" class="form-control" name="filter_date" id="filter_date" required>
                                <!-- <select class="select form-select text-muted" name="filter_date" id="filter_date" aria-label="Default select example">
                            <option value="" class="text-muted" selected>Select date</option>
                        </select> -->
                            </div>
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
                                    <!-- <th class="text-secondary">date</th> -->
                                    <th class="text-secondary">fare</th>
                                    <th class="text-secondary">bus #</th>
                                    <th class="text-secondary">driver</th>
                                    <th class="text-secondary">conductor</th>
                                    <th class="text-secondary">assigned by</th>
                                    <th class="text-secondary">Actions</th>
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
                                        <option value="<?php echo $bus['id']; ?>"><?php echo $bus['busNumber'] . " (" . $bus['bus_model'] . ") | " . $bus['seat_type'] . " seat type | " . $bus['total_seat'] . " total seat)"   ?></option>
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
                                    <!-- <th class="text-secondary">trip</th> -->
                                    <th class="text-secondary">route</th>
                                    <th class="text-secondary">date</th>
                                    <th class="text-secondary">time</th>
                                    <th class="text-secondary">fare</th>
                                    <th class="text-secondary">driver</th>
                                    <th class="text-secondary">conductor</th>
                                    <th class="text-secondary">assigned by</th>
                                    <th class="text-secondary">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    <?php }
                    if (isset($_GET['weekly_view'])) {
                        include 'extentions/weekly_view.php'; ?>
                    <?php } ?>
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
                            <select class="form-select text-muted" name="optionBus" id="optionBus" aria-label="Default select example" required>
                                <option value="" class="text-muted">Select Bus</option>
                                <?php foreach ($buses as $bus) : ?>
                                    <option value="<?php echo $bus['id']; ?>"><?php echo $bus['busNumber'] . " (" . $bus['bus_model'] . ") | " . $bus['seat_type'] . " seat type | " . $bus['total_seat'] . " total seat)"   ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group md-6 mt-3">
                            <label class="h6 text-muted">Fare</label>
                            <input type="number" class="form-control" name="fare" placeholder="" required>
                        </div>
                        <button class="btn btn-primary float-right mr-3 mt-4" type="submit" name="edit_bus_trip" form="edit_bus_trip">Save Changes</button>
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
                            <div class="col form-group md-6 mt-3">
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
                        <button class="btn btn-primary float-right mr-3 mt-4" type="submit" name="edit_bus_trip_employee" form="edit_bus_trip_employee">Save Changes</button>
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
                            <select class="form-select text-muted" name="optionBus" id="optionBus" aria-label="Default select example" required>
                                <option value="" class="text-muted" selected>Select Bus</option>
                                <?php foreach ($buses as $bus) : ?>
                                    <option value="<?php echo $bus['id']; ?>"><?php echo $bus['busNumber'] . " (" . $bus['bus_model'] . ") | " . $bus['seat_type'] . " seat type | " . $bus['total_seat'] . " total seat)"   ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row mt-3">
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
                        <div class="form-group md-6 mt-3">
                            <label class="h6 text-muted">Terminal</label>
                            <select class="form-select text-muted" name="optionTerminalbus" id="optionTerminalbus" onchange="FetchData(this.value)" aria-label="Default select example" required>
                                <option value="" class="text-muted">Select Terminal</option>
                                <?php foreach ($tripTermIDS as $tripTermID) : ?>
                                    <option value="<?php echo $tripTermID['terminalID']; ?>">
                                        <?php
                                        $termidss = $tripTermID['terminalID'];
                                        $ress = $conn->query("SELECT `terminal_name` FROM `terminal` WHERE `id`='$termidss' AND `companyID`='$id'");
                                        $trpress = array_values($ress->fetch_assoc());
                                        echo $trpress[0];
                                        ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row mt-3">
                            <div class="col form-group md-6">
                                <label class="h6 text-muted">Trip</label>
                                <select class="form-select text-muted" name="optionTrip" id="optionTrip" onchange="FetchTime(this.value)" aria-label="Default select example" required>
                                    <option value="" class="text-muted">Select Trip</option>
                                </select>
                            </div>
                            <div class="col form-group md-6">
                                <label class="h6 text-muted">Time</label>
                                <select class="form-select text-muted" name="optionTripTime" id="optionTripTime" aria-label="Default select example" required>
                                    <option value="" class="text-muted">Select Time</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group md-6 mt-3">
                            <label class="h6 text-muted">Fare</label>
                            <input type="number" class="form-control" name="fare" placeholder="" required>
                        </div>
                        <div class="row mt-3">
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
    function FetchRoutes(id) {
        $('#filter_route').html('');
        if (id == '') {
            $('#filter_route').html('<option>Select Route</option>');
        } else {
            $.ajax({
                type: 'post',
                url: 'extentions/fetchtripfilter.php',
                data: {
                    terminalid: id
                },
                success: function(data) {
                    $('#filter_route').html(data);
                }
            })
        }
    }

    function FetchDates(id) {
        $('#filter_date').html('');
        var val = $("#filter_termid :selected").val();
        if (id == '') {
            $('#filter_date').html('<option>Select Date</option>');
        } else {
            $.ajax({
                type: 'post',
                url: 'extentions/fetchdatefilter.php',
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
                url: 'extentions/fetchtripoption.php',
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
                url: 'extentions/fetchtriptimeoption.php',
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
        var val = $("#optionTerminalbus :selected").val();
        if (id == '') {
            $('#optionTripTime').html('<option>Select Time</option>');
        } else {
            $.ajax({
                type: 'post',
                url: 'extentions/fetchtriptimeoption.php',
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
        $('#bustrip').dataTable().fnClearTable();

        function fill_datatable(filter_termid = '', filter_route = '', filter_date = '') {
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
                        filter_termid: filter_termid,
                        filter_route: filter_route,
                        filter_date: filter_date
                    }
                }
            });
        }

        $('#filter').click(function() {
            var filter_termid = $("#filter_termid :selected").val();
            var filter_route = $("#filter_route :selected").val();
            var filter_date = $("#filter_date").val();

            if (filter_termid != '' && filter_route != '' && filter_date == '') {
                $('#bustrip').DataTable().destroy();
                fill_datatable(filter_termid, filter_route, filter_date);
            } else if (filter_termid != '' && filter_route != '' && filter_date != '') {
                $('#bustrip').DataTable().destroy();
                fill_datatable(filter_termid, filter_route, filter_date);
            } else {
                $('#bustrip').DataTable().destroy();
                $('#bustrip').dataTable().fnClearTable();
            }
        });
    });

    $(document).ready(function() {
        $('#bustrip_busview').dataTable().fnClearTable();

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
                // alert(filter_date_range);
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