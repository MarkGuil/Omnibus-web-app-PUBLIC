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

$result = $conn->query("SELECT * FROM trips WHERE companyID = $id");
$trips = $result->fetch_all(MYSQLI_ASSOC);
$result2 = $conn->query("SELECT * FROM routes WHERE companyID = $id");
$routes = $result2->fetch_all(MYSQLI_ASSOC);
$result3 = $conn->query("SELECT * FROM buses WHERE companyID = $id");
$buses = $result3->fetch_all(MYSQLI_ASSOC);

//   $var = date('Y-m-d');
//   echo $var;
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
    <nav class="navbar navbar-dark navbar-expand-lg navbar-light bg-dark shadow-lg py-3 px-5 mb-5">
        <a class="navbar-brand mr-4" href="home_admin_details.php"><b>Omnibus</b><span class="text-primary"> Company Admin</span></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse ml-3 mt-1" id="navbarNav">
            <ul class="navbar-nav ">
                <li class="nav-item">
                    <a class="nav-link" href="home_admin_branches.php">Terminals</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link ml-2" href="">Trips</a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link ml-2" href="home_admin_buses.php">Employee & Buses</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ml-2" href="home_admin_routes.php">Routes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ml-2" href="home_admin_trips.php">Trips</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ml-2" href="home_admin_bookings.php">Bookings</a>
                </li>
            </ul>
        </div>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
            <span class="navbar-text text-dark ml-3 pr-3 pt-2">
                <!-- <?php if (isset($_SESSION['success'])) : ?>
                    <div class="error success" >
                        <h3>
                            <?php
                            echo $_SESSION['success'];
                            unset($_SESSION['success']);
                            ?>
                        </h3>
                    </div>
                <?php endif ?> -->
            </span>
            <?php if (isset($_SESSION['compadminName'])) : ?>
                <a class="btn btn-secondary ml-3" href="starting_home.php?logout='1'">Logout</a>
            <?php endif ?>
        </div>
    </nav>

    <?php if (isset($_SESSION['compadminsuccess'])) { ?>
        <div class="container container-large mb-5 p-0 rounded shadow">
            <div class="alert alert-success mt-3" role="alert">
                <form action="" method="get" id="unsetSuccessAlert">
                    <button type="submit" class="close" name="unsetSuccessAlert" form="unsetSuccessAlert">&times;</button>
                </form>
                <?php echo $_SESSION['compadminsuccess']  ?>
            </div>
        </div>
    <?php } ?>
    <div class="container container-large mb-5 p-0 rounded shadow">
        <?php include('errors.php'); ?>
    </div>

    <div class="container  mb-5 px-5 py-5 bg-light rounded shadow">
        <form class="form" id="add_trip" action="" method="post">
            <div class="cont-popup cont col-sm-12 col-sm-offset-12">
                <div class="col-5">
                    <div class="form-group md-6">
                        <label class="h6 text-muted">Routes</label>
                        <select class="form-select text-muted" name="optionRoute" id="optionRoute" aria-label="Default select example">
                            <option value="" class="text-muted" selected>Select Route</option>
                            <?php foreach ($routes as $route) : ?>
                                <option value="<?php echo $route['id']; ?>"><?php echo $route['pointA'] . " - " . $route['pointB'] ?> ( vice- versa )</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div id="tripConfig" class="tripConfig" style="display:none">
                    <hr>
                    <div class="col-12">
                        <div class="row mb-3">
                            <h6 class="mt-3 text-muted">Period Operating:</h6>
                            <div class="col-4 form-group md-6">
                                <label class="h6 text-muted"><small>From</small></label>
                                <input type="date" class="form-control" id="operateFrom" name="operateFrom" min="<?php echo date("Y-m-d"); ?>" placeholder="00:00PM" required>
                            </div>
                            <div class="col-4 form-group md-6">
                                <label class="h6 text-muted"><small>To</small></label>
                                <input type="date" class="form-control" id="operateTo" name="operateTo" required>
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
                            </div>
                            <div class="col">

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
                            </div>
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" name="recur[]" type="checkbox" value="Sunday" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Every Sunday
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-6">
                        <div class=" form-group md-6 mb-4">
                            <label class="h6 text-muted">Bus</label>
                            <select class="form-select text-muted" name="optionBus" id="optionBus" aria-label="Default select example">
                                <option value="" class="text-muted" selected>Select Bus</option>
                                <?php foreach ($buses as $bus) : ?>
                                    <option value="<?php echo $bus['id']; ?>"><?php echo $bus['busNumber'] . " (" . $bus['bus_model'] . ") | " . $bus['seat_type'] . " seat type | " . $bus['total_seat'] . " total seat)"   ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-6 form-group md-6">
                        <label class="h6 text-muted">Trip Duration</label>
                        <div class="row">
                            <div class="col d-flex flex-row pb-0">
                                <input type="number" class="form-control" name="hour1" id="hour1" placeholder="" value="00" maxlength="2" size="2" max="24" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                                <p class="p-2 text-muted"><small> Hour</small></p>
                            </div>
                            <div class="col d-flex flex-row pb-0">
                                <input type="number" class="form-control" name="minutes1" id="minutes1" placeholder="" value="00" maxlength="2" size="2" max="60" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                                <p class="p-2 text-muted"><small> Minutes</small></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 form-group md-6">
                        <label class="h6 text-muted">Waiting Time</label>
                        <div class="row">
                            <div class="col d-flex flex-row pb-0">
                                <input type="number" class="form-control" name="waithour1" id="waithour1" placeholder="" value="00" maxlength="2" size="2" max="24" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                                <p class="p-2 text-muted"><small> Hour</small></p>
                            </div>
                            <div class="col d-flex flex-row pb-0">
                                <input type="number" class="form-control" name="waitminutes1" id="waitminutes1" placeholder="" value="00" maxlength="2" size="2" max="60" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                                <p class="p-2 text-muted"><small> Minutes</small></p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-12">
                        <h6 class="text-muted">Trips</h6>
                        <button class="add_field_button btn btn-primary">Add More Trip</button>
                        <input type="text" id="tripCount" name="tripCount" value="1">
                        <div class="form-group md-6">
                            <div class="row">
                                <div class="col-6 my-4">
                                    <div class="shadow py-4 px-4 bg-white">
                                        <div class="row pb-0">
                                            <p id=oridest></p>
                                            <div class="col form-group md-6">
                                                <label class="h6 text-muted">Departure TIme </label>
                                                <input type="time" class="form-control" name="departure1" id="deptime1" placeholder="00:00PM" min="04:30:00" required required>
                                            </div>
                                            <div class="col form-group md-6">
                                                <label class="h6 text-muted">Arrival time at destination</label>
                                                <input type="time" class="form-control" name="arrival1" id="arrivalTime1" placeholder="00:00PM" readonly required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <p>Vice Versa</p>
                                            <div class="col form-group md-6">
                                                <label class="h6 text-muted">Departure time </label>
                                                <input type="time" class="form-control" name="departure12" id="deptime12" placeholder="" readonly>
                                            </div>
                                            <div class="col form-group md-6">
                                                <label class="h6 text-muted">Arrival time at destination</label>
                                                <input type="time" class="form-control" name="arrival12" id="arrivalTime12" placeholder="00:00PM" max="20:00:00" readonly required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="input_fields_wrap row">
                            </div>
                        </div>
                        <hr>

                    </div>
                    <button class="btn btn-primary mr-3 mt-2" type="submit" name="add_trip" form="add_trip">Confirm</button>
                </div>
            </div>

        </form>
    </div>
    <footer class="text-center text-lg-start">
        <div class="text-center text-light p-3" style="background-color: #212529">
            © 2021 Copyright:
            <a class="text-light" href="">Omnibus</a>
        </div>
    </footer>
    <div class="modal" id="addTripModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Add Trip</span>
                    <div class="close text-white" data-bs-dismiss="modal">&times;</div>
                </div>
                <div class="modal-body">
                    <form class="form" id="add_trip" action="" method="post">
                        <div class="cont-popup cont col-sm-12 col-sm-offset-12">
                            <div class="form-group md-6">
                                <label class="h6 text-muted">Routes</label>
                                <select class="form-select text-muted" name="optionRoute" id="optionRoute" aria-label="Default select example">
                                    <option value="" class="text-muted" selected>Select Route</option>
                                    <?php foreach ($routes as $route) : ?>
                                        <option value="<?php echo $route['id']; ?>"><?php echo $route['pointA'] . " - " . $route['pointB'] ?> ( vice- versa )</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div id="tripConfig" class="tripConfig" style="display:none">
                                <hr>
                                <div class=" form-group md-6 mb-4">
                                    <label class="h6 text-muted">Bus</label>
                                    <select class="form-select text-muted" name="optionBus" id="optionBus" aria-label="Default select example">
                                        <option value="" class="text-muted" selected>Select Bus</option>
                                        <?php foreach ($buses as $bus) : ?>
                                            <option value="<?php echo $bus['id']; ?>"><?php echo $bus['busNumber'] . " (" . $bus['bus_model'] . ") | " . $bus['seat_type'] . " seat type | " . $bus['total_seat'] . " total seat)"   ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        <label class="h6 text-muted">Number of trip</label>
                                        <input type="text" class="form-control" name="numtrip" id="numtrip" required>
                                    </div>
                                    <div class="col form-group md-6">
                                        <label class="h6 text-muted">Trip Duration</label>
                                        <div class="row">
                                            <div class="col d-flex flex-row pb-0">
                                                <input type="number" class="form-control" name="hour1" id="hour1" placeholder="" value="00" maxlength="2" size="2" max="24" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                                                <p class="p-2 text-muted"><small> Hour</small></p>
                                            </div>
                                            <div class="col d-flex flex-row pb-0">
                                                <input type="number" class="form-control" name="minutes1" id="minutes1" placeholder="" value="00" maxlength="2" size="2" max="60" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                                                <p class="p-2 text-muted"><small> Minutes</small></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group md-6">
                                    <div class="row pb-0">
                                        <p id=oridest></p>
                                        <div class="col form-group md-6">
                                            <label class="h6 text-muted">Departure TIme </label>
                                            <input type="time" class="form-control" name="departure1" id="deptime" placeholder="00:00PM" min="04:30:00" required>
                                        </div>
                                        <div class="col form-group md-6">
                                            <label class="h6 text-muted">Arrival time at destination</label>
                                            <input type="time" class="form-control" name="arrival" id="arrivalTime" placeholder="00:00PM" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <p>Vice Versa</p>
                                        <div class="col form-group md-6">
                                            <label class="h6 text-muted">Departure time </label>
                                            <input type="time" class="form-control" name="departure2" id="deptime2" placeholder="" required>
                                        </div>
                                        <div class="col form-group md-6">
                                            <label class="h6 text-muted">Arrival time at destination</label>
                                            <input type="time" class="form-control" name="arrival2" id="arrivalTime2" placeholder="00:00PM" max="20:00:00" readonly>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <p>Period Operating:</p>
                                        <div class="col form-group md-6">
                                            <label class="h6 text-muted">From</label>
                                            <input type="date" class="form-control" id="operateFrom" name="operateFrom" min="<?php echo date("Y-m-d"); ?>" placeholder="00:00PM" required>
                                        </div>
                                        <div class="col form-group md-6">
                                            <label class="h6 text-muted">To</label>
                                            <input type="date" class="form-control" id="operateTo" name="operateTo" required>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-2">
                                            <p>Recurring</p>
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
                                        </div>
                                        <div class="col">

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
                                        </div>
                                        <div class="col">
                                            <div class="form-check">
                                                <input class="form-check-input" name="recur[]" type="checkbox" value="Sunday" id="flexCheckDefault">
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    Every Sunday
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary float-right mr-3 mt-2" type="submit" name="add_trip" form="add_trip">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


</body>

</html>

<script>
    $(document).ready(function() {

        var max_fields = 5; //maximum input boxes allowed
        var wrapper = $(".input_fields_wrap"); //Fields wrapper
        var add_button = $(".add_field_button"); //Add button ID

        var x = 1; //initlal text box count
        $(add_button).click(function(e) { //on add input button click
            var text = document.getElementById("oridest").innerHTML;
            e.preventDefault();
            var curtime = document.getElementById("arrivalTime" + x + "2").value;
            var addhour = document.getElementById("waithour1").value;
            var addmins = document.getElementById("waitminutes1").value;
            var addhour2 = document.getElementById("hour1").value;
            var addmins2 = document.getElementById("minutes1").value;
            var deptime1 = compute_time(curtime, addhour, addmins);
            var arrtime1 = compute_time(deptime1, addhour2, addmins2);
            var deptime2 = compute_time(arrtime1, addhour, addmins);
            var arrtime2 = compute_time(deptime2, addhour2, addmins2);
            if (x < max_fields && curtime != '') { //max input box allowed
                x++; //text box increment
                document.getElementById("tripCount").value = x;
                $(wrapper).append(`<div class="my-4 col-6"><div class="shadow py-4 px-4 bg-white">
                                                <div class="row pb-0">
                                                    <p>` + text + `</p>
                                                    <div class="col form-group md-6">
                                                        <label class="h6 text-muted">Departure TIme </label>
                                                        <input type="time" class="form-control" name="departure` + x + `" id="deptime` + x + `" value="` + deptime1 + `" placeholder="00:00PM" min="04:30:00" readonly required>  
                                                    </div>
                                                    <div class="col form-group md-6">
                                                        <label class="h6 text-muted">Arrival time at destination</label>
                                                        <input type="time" class="form-control" name="arrival` + x + `" id="arrivalTime` + x + `" value="` + arrtime1 + `" placeholder="00:00PM" readonly required>  
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <p>Vice Versa</p>
                                                    <div class="col form-group md-6">
                                                        <label class="h6 text-muted">Departure time </label>
                                                        <input type="time" class="form-control" name="departure` + x + `2" id="deptime` + x + `2" value="` + deptime2 + `"  placeholder="" readonly required>
                                                    </div>
                                                    <div class="col form-group md-6">
                                                        <label class="h6 text-muted">Arrival time at destination</label>
                                                        <input type="time" class="form-control" name="arrival` + x + `2" id="arrivalTime` + x + `2" value="` + arrtime2 + `" placeholder="00:00PM" max="20:00:00" readonly required>  
                                                    </div>
                                                </div>
                                            </div><a href="#" class="remove_field my-3 mx-1">Remove</a></div>`);
            }
        });

        $(wrapper).on("click", ".remove_field", function(e) { //user click on remove text
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
            document.getElementById("tripCount").value = x;
        })
    });

    $("#operateFrom").on("input", function() {
        var minimumm = $(this).val();
        document.getElementById("operateTo").setAttribute("min", minimumm);
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

    $("#optionRoute").on("input", function() {
        var route = $(this).val();
        document.getElementById("fare").val(route);
    });
    $("#deptime1").on('change ', function() {
        var times = [0, 0];
        var max = times.length;
        var deptime = document.getElementById("deptime1").value;
        var addhour = document.getElementById("hour1").value;
        var addmins = document.getElementById("minutes1").value;
        var addhour2 = document.getElementById("waithour1").value;
        var addmins2 = document.getElementById("waitminutes1").value;
        var date = new Date();
        var newdeptime = deptime + ":00";
        date.setHours(addhour);
        date.setMinutes(addmins);
        var hour = date.getHours(),
            min = date.getMinutes();
        var addTime = hour + ":" + min + ":00";


        var a = (newdeptime || '').split(':');
        var b = (addTime || '').split(':');

        for (var i = 0; i < max; i++) {
            a[i] = isNaN(parseInt(a[i])) ? 0 : parseInt(a[i]);
            b[i] = isNaN(parseInt(b[i])) ? 0 : parseInt(b[i]);
        }

        for (var i = 0; i < max; i++) {
            times[i] = a[i] + b[i];
        }

        var hours = times[0];
        var minutes = times[1];

        if (minutes >= 60) {
            var h = (minutes / 60) << 0;
            hours += h;
            minutes -= 60 * h;
        }
        var nextDeptime = ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2);
        var input = document.getElementById("deptime12");
        input.min = nextDeptime;
        input.max = "20:00:00";
        document.getElementById("arrivalTime1").value = nextDeptime;
        var arrtimee = compute_time(nextDeptime, addhour2, addmins2);
        document.getElementById("deptime12").value = arrtimee;
        var arrtimeee = compute_time(arrtimee, addhour, addmins);
        document.getElementById("arrivalTime12").value = arrtimeee;
    });

    $("#deptime2").on('change ', function() {
        var times = [0, 0];
        var max = times.length;
        var deptime2 = document.getElementById("deptime12").value;
        var addhour = document.getElementById("hour1").value;
        var addmins = document.getElementById("minutes1").value;
        var date = new Date();
        var newdeptime2 = deptime2 + ":00";
        date.setHours(addhour);
        date.setMinutes(addmins);
        var hour = date.getHours(),
            min = date.getMinutes();
        var addTime = hour + ":" + min + ":00";


        var a = (newdeptime2 || '').split(':');
        var b = (addTime || '').split(':');

        for (var i = 0; i < max; i++) {
            a[i] = isNaN(parseInt(a[i])) ? 0 : parseInt(a[i]);
            b[i] = isNaN(parseInt(b[i])) ? 0 : parseInt(b[i]);
        }

        for (var i = 0; i < max; i++) {
            times[i] = a[i] + b[i];
        }

        var hours = times[0];
        var minutes = times[1];

        if (minutes >= 60) {
            var h = (minutes / 60) << 0;
            hours += h;
            minutes -= 60 * h;
        }
        var nextDeptime2 = ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2);
        document.getElementById("arrivalTime12").value = nextDeptime2;
    });

    $("#hour1").on('change', function() {
        var times = [0, 0];
        var times2 = [0, 0];
        var max = times.length;
        var deptime = document.getElementById("deptime1").value;
        var deptime2 = document.getElementById("deptime12").value;
        var addhour = document.getElementById("hour1").value;
        var addmins = document.getElementById("minutes1").value;
        var date = new Date();
        var newdeptime = deptime + ":00";
        var newdeptime2 = deptime2 + ":00";
        date.setHours(addhour);
        date.setMinutes(addmins);
        var hour = date.getHours(),
            min = date.getMinutes();
        var addTime = hour + ":" + min + ":00";


        var a = (newdeptime || '').split(':');
        var a2 = (newdeptime2 || '').split(':');
        var b = (addTime || '').split(':');

        for (var i = 0; i < max; i++) {
            a[i] = isNaN(parseInt(a[i])) ? 0 : parseInt(a[i]);
            a2[i] = isNaN(parseInt(a2[i])) ? 0 : parseInt(a2[i]);
            b[i] = isNaN(parseInt(b[i])) ? 0 : parseInt(b[i]);
        }

        for (var i = 0; i < max; i++) {
            times[i] = a[i] + b[i];
            times2[i] = a2[i] + b[i];
        }

        var hours = times[0];
        var minutes = times[1];
        var hours2 = times2[0];
        var minutes2 = times2[1];

        if (minutes >= 60) {
            var h = (minutes / 60) << 0;
            hours += h;
            minutes -= 60 * h;
        }
        var nextDeptime = ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2);
        var input = document.getElementById("deptime12");
        input.min = nextDeptime;
        input.max = "20:00:00";
        if (deptime != "") {
            document.getElementById("arrivalTime1").value = nextDeptime;
        }
        var nextDeptime2 = ('0' + hours2).slice(-2) + ':' + ('0' + minutes2).slice(-2);
        if (deptime2 != "") {
            document.getElementById("arrivalTime12").value = nextDeptime2;
        }

    });

    $("#minutes1").on('change', function() {
        var times = [0, 0];
        var times2 = [0, 0];
        var max = times.length;
        var deptime = document.getElementById("deptime1").value;
        var deptime2 = document.getElementById("deptime12").value;
        var addhour = document.getElementById("hour1").value;
        var addmins = document.getElementById("minutes1").value;
        var date = new Date();
        var newdeptime = deptime + ":00";
        var newdeptime2 = deptime2 + ":00";
        date.setHours(addhour);
        date.setMinutes(addmins);
        var hour = date.getHours(),
            min = date.getMinutes();
        var addTime = hour + ":" + min + ":00";


        var a = (newdeptime || '').split(':');
        var a2 = (newdeptime2 || '').split(':');
        var b = (addTime || '').split(':');

        for (var i = 0; i < max; i++) {
            a[i] = isNaN(parseInt(a[i])) ? 0 : parseInt(a[i]);
            a2[i] = isNaN(parseInt(a2[i])) ? 0 : parseInt(a2[i]);
            b[i] = isNaN(parseInt(b[i])) ? 0 : parseInt(b[i]);
        }

        for (var i = 0; i < max; i++) {
            times[i] = a[i] + b[i];
            times2[i] = a2[i] + b[i];
        }

        var hours = times[0];
        var minutes = times[1];
        var hours2 = times2[0];
        var minutes2 = times2[1];

        if (minutes >= 60) {
            var h = (minutes / 60) << 0;
            hours += h;
            minutes -= 60 * h;
        }
        var nextDeptime = ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2);
        var input = document.getElementById("deptime12");
        input.min = nextDeptime;
        input.max = "20:00:00";
        if (deptime != "") {
            document.getElementById("arrivalTime1").value = nextDeptime;
        }
        var nextDeptime2 = ('0' + hours2).slice(-2) + ':' + ('0' + minutes2).slice(-2);
        if (deptime2 != "") {
            document.getElementById("arrivalTime12").value = nextDeptime2;
        }

    });


    $("#waithour1").on('change', function() {
        var times = [0, 0];
        var max = times.length;
        var arrtime2 = document.getElementById("arrivalTime1").value;
        var addhour = document.getElementById("waithour1").value;
        var addmins = document.getElementById("waitminutes1").value;
        var addhour2 = document.getElementById("hour1").value;
        var addmins2 = document.getElementById("minutes1").value;
        var date = new Date();
        var newarrtime2 = arrtime2 + ":00";
        date.setHours(addhour);
        date.setMinutes(addmins);
        var hour = date.getHours(),
            min = date.getMinutes();
        var addTime = hour + ":" + min + ":00";

        var a = (newarrtime2 || '').split(':');
        var b = (addTime || '').split(':');

        for (var i = 0; i < max; i++) {
            a[i] = isNaN(parseInt(a[i])) ? 0 : parseInt(a[i]);
            b[i] = isNaN(parseInt(b[i])) ? 0 : parseInt(b[i]);
        }

        for (var i = 0; i < max; i++) {
            times[i] = a[i] + b[i];
        }

        var hours = times[0];
        var minutes = times[1];

        if (minutes >= 60) {
            var h = (minutes / 60) << 0;
            hours += h;
            minutes -= 60 * h;
        }

        var nextarrtime2 = ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2);
        var arrtimee = compute_time(nextarrtime2, addhour2, addmins2);
        if (deptime2 != "") {
            document.getElementById("deptime12").value = nextarrtime2;
            document.getElementById("arrivalTime12").value = arrtimee;
        }

    });
    $("#waitminutes1").on('change', function() {
        var times = [0, 0];
        var max = times.length;
        var arrtime2 = document.getElementById("arrivalTime1").value;
        var addhour = document.getElementById("waithour1").value;
        var addmins = document.getElementById("waitminutes1").value;
        var addhour2 = document.getElementById("hour1").value;
        var addmins2 = document.getElementById("minutes1").value;
        var date = new Date();
        var newarrtime2 = arrtime2 + ":00";
        date.setHours(addhour);
        date.setMinutes(addmins);
        var hour = date.getHours(),
            min = date.getMinutes();
        var addTime = hour + ":" + min + ":00";

        var a = (newarrtime2 || '').split(':');
        var b = (addTime || '').split(':');

        for (var i = 0; i < max; i++) {
            a[i] = isNaN(parseInt(a[i])) ? 0 : parseInt(a[i]);
            b[i] = isNaN(parseInt(b[i])) ? 0 : parseInt(b[i]);
        }

        for (var i = 0; i < max; i++) {
            times[i] = a[i] + b[i];
        }

        var hours = times[0];
        var minutes = times[1];

        if (minutes >= 60) {
            var h = (minutes / 60) << 0;
            hours += h;
            minutes -= 60 * h;
        }

        var nextarrtime2 = ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2);
        var arrtimee = compute_time(nextarrtime2, addhour2, addmins2);
        if (deptime2 != "") {
            document.getElementById("deptime12").value = nextarrtime2;
            document.getElementById("arrivalTime12").value = arrtimee;
        }

    });

    $("#numtrip").on('change', function() {
        var times = [0, 0];
        var max = times.length;
        var arrtime2 = document.getElementById("arrivalTime12").value;
        var addhour = document.getElementById("waithour1").value;
        var addmins = document.getElementById("waitminutes1").value;
        var addhour2 = document.getElementById("hour1").value;
        var addmins2 = document.getElementById("minutes1").value;
        var date = new Date();
        var newarrtime2 = arrtime2 + ":00";
        date.setHours(addhour);
        date.setMinutes(addmins);
        var hour = date.getHours(),
            min = date.getMinutes();
        var addTime = hour + ":" + min + ":00";

        var a = (newarrtime2 || '').split(':');
        var b = (addTime || '').split(':');

        for (var i = 0; i < max; i++) {
            a[i] = isNaN(parseInt(a[i])) ? 0 : parseInt(a[i]);
            b[i] = isNaN(parseInt(b[i])) ? 0 : parseInt(b[i]);
        }

        for (var i = 0; i < max; i++) {
            times[i] = a[i] + b[i];
        }

        var hours = times[0];
        var minutes = times[1];

        if (minutes >= 60) {
            var h = (minutes / 60) << 0;
            hours += h;
            minutes -= 60 * h;
        }

        var nextarrtime2 = ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2);
        var arrtimee = compute_time(nextarrtime2, addhour2, addmins2);
        document.getElementById("deptime12").value = nextarrtime2;
        document.getElementById("arrivalTime12").value = arrtimee;

    });

    function compute_time(time, hour, min) {
        var times = [0, 0];
        var max = times.length;
        var arrtime2 = time;
        var addhour = hour;
        var addmins = min;
        var date = new Date();
        var newarrtime2 = arrtime2 + ":00";
        date.setHours(addhour);
        date.setMinutes(addmins);
        var hour = date.getHours(),
            min = date.getMinutes();
        var addTime = hour + ":" + min + ":00";


        var a = (newarrtime2 || '').split(':');
        var b = (addTime || '').split(':');

        for (var i = 0; i < max; i++) {
            a[i] = isNaN(parseInt(a[i])) ? 0 : parseInt(a[i]);
            b[i] = isNaN(parseInt(b[i])) ? 0 : parseInt(b[i]);
        }

        for (var i = 0; i < max; i++) {
            times[i] = a[i] + b[i];
        }

        var hours = times[0];
        var minutes = times[1];

        if (minutes >= 60) {
            var h = (minutes / 60) << 0;
            hours += h;
            minutes -= 60 * h;
        }
        var nextarrtime2 = ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2);
        return nextarrtime2;
    }
</script>