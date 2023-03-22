<?php
include('../../database/db.php');
include('../server_comp_admin.php'); ?>
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

$result = $conn->query("SELECT * FROM `trip` WHERE `companyID` = $id");
$trips = $result->fetch_all(MYSQLI_ASSOC);
$result = $conn->query("SELECT DISTINCT `terminalID` FROM `trip` WHERE `companyID` = $id");
$tripTermIDS = $result->fetch_all(MYSQLI_ASSOC);
$result3 = $conn->query("SELECT * FROM `buses` WHERE `companyID` = $id");
$buses = $result3->fetch_all(MYSQLI_ASSOC);
$result4 = $conn->query("SELECT DISTINCT `pointA_terminalID`,`pointB_terminalID` FROM `routes` WHERE `companyID` = $id");
$termids = $result4->fetch_all(MYSQLI_ASSOC);

//   $var = date('Y-m-d');
//   echo $var;
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>OmnibusAdmin</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <?php include '../extentions/bootstrap.php' ?>
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
        <a class="navbar-brand mr-4" href="home_admin_details.php"><b>Omnibus</b><span class="text-primary"> Company Admin</span></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse ml-3 mt-1" id="navbarNav">
            <ul class="navbar-nav ">
                <li class="nav-item">
                    <a class="nav-link ml-2" href="../trips_dashboard.php">Weekly View</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../home_admin_branches.php">Terminals</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link ml-2" href="">Trips</a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link ml-2" href="../home_admin_buses.php">Employee & Buses</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ml-2" href="../home_admin_routes.php">Routes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ml-2" href="../home_admin_trips.php">Trips</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ml-2" href="../home_admin_bookings.php">Bookings</a>
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
                <form action="" method="get" class="d-flex justify-content-between" id="unsetSuccessAlert">
                    <?php echo $_SESSION['compadminsuccess']  ?>
                    <button type="submit" name="unsetSuccessAlert" form="unsetSuccessAlert" class="btn-close" aria-label="Close"></button>
                </form>
            </div>
        </div>
    <?php } ?>
    <div class="container container-large mb-5 p-0 rounded shadow">
        <?php include('../errors.php'); ?>
    </div>

    <div class="container mb-5 px-5 py-5 bg-light rounded shadow">
        <form action="" method="get">
            <div class="col-5">
                <label class="h6 text-muted">Terminal</label>
                <div class="form-group md-6 d-flex flex-row">
                    <!-- <select class="select form-select text-muted" name="optionTerminal" id="optionTerminal" onchange="document.getElementById('text_content').value=this.options[this.selectedIndex].text" aria-label="Default select example">
                    <option value="" class="text-muted" selected>Select terminal</option>
                    <?php foreach ($termids as $termid) : ?>
                        <option value="<?php echo $termid['pointA_terminalID']; ?>">
                        <?php
                        $termidss = $termid['pointA_terminalID'];
                        $ress = $conn->query("SELECT `terminal_name` FROM `terminal` WHERE `id` = '$termidss' AND `companyID`='$id'");
                        $terminalIDss = array_values($ress->fetch_assoc());
                        echo $terminalIDss[0];
                        ?>
                    </option>
                        <option value="<?php echo $termid['pointB_terminalID']; ?>">
                        <?php
                        $termidss = $termid['pointB_terminalID'];
                        $ress = $conn->query("SELECT `terminal_name` FROM `terminal` WHERE `id` = '$termidss' AND `companyID`='$id'");
                        $terminalIDss = array_values($ress->fetch_assoc());
                        echo $terminalIDss[0];
                        ?>
                        </option>
                    <?php endforeach; ?>
                </select> -->
                    <select class="select form-select text-muted" name="optionTerminal" id="optionTerminal" onchange="document.getElementById('text_content').value=this.options[this.selectedIndex].text" aria-label="Default select example">
                        <option value="" class="text-muted" selected>Select terminal</option>
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
                    <input type="hidden" name="terminalName" id="text_content" value="" />
                    <button class="btn btn-primary py-1" name="showTermRoutes" value="">Select</button>
                </div>
            </div>
        </form>
        <hr>
        <form class="form" id="add_new_trip" action="" method="post">
            <?php
            if (isset($_GET['showTermRoutes'])) {
                $selectedTermID = $_GET['optionTerminal'];
                $selectedTermNAME = $_GET['terminalName'];
                if ($selectedTermID != '') {
                    $result2 = $conn->query("SELECT * FROM `routes` WHERE `companyID` = $id AND `pointA_terminalID`= $selectedTermID");
                    $routes = $result2->fetch_all(MYSQLI_ASSOC);
                    echo '<input type="hidden" id="terminalID" name="terminalID" value="' . $selectedTermID . '">';
                }
                if ($selectedTermNAME != '') {
                    echo "<h5 class='text-primary'>" . $selectedTermNAME . "</h5>";
                } else {
                    echo "<h5 class='text-primary'>Please Select Terminal</h5>";
                }
            ?>
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
                    <div class=" form-group md-6 mb-4">
                        <label class="h6 text-muted">Trip</label>
                        <select class="form-select text-muted" name="optionTrip" id="optionTrip" aria-label="Default select example">
                            <option value="" class="text-muted" selected>Select Trip</option>
                            <?php foreach ($trips as $trip) : ?>
                                <option value="<?php echo $trip['id']; ?>"><?php echo $trip['origin'] . " - " . $trip['destination'] . " | " . date('h:i A', strtotime($trip["departure_time"])); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class=" form-group md-6 mb-4">
                        <label class="h6 text-muted">Time</label>
                        <select class="form-select text-muted" name="optionTrip" id="optionTrip" aria-label="Default select example">
                            <option value="" class="text-muted" selected>Select Trip</option>
                            <?php foreach ($trips as $trip) : ?>
                                <option value="<?php echo $trip['id']; ?>"><?php echo $trip['origin'] . " - " . $trip['destination'] . " | " . date('h:i A', strtotime($trip["departure_time"])); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group md-6">
                        <label class="h6 text-muted">Fare</label>
                        <input type="number" class="form-control" name="fare" placeholder="" required>
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
                </div>
                <button class="btn btn-primary mr-3 mt-2" type="submit" name="add_new_trip" form="add_new_trip">Add Bus Trip</button>
            <?php } ?>
        </form>
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
    $(".select option").each(function() {
        $(this).siblings('[value="' + this.value + '"]').remove();
    });
    $(document).ready(function() {
        var i = 1;
        var num = 1;
        var max = 15;
        $('#add').click(function() {
            if (i < max) {
                i++;
                num++;
                $('#dynamic_field').append(`<div class="row pt-3" id="row` + i + `">
                                                <div class="col-10">    
                                                    <input type="time" class="form-control" name="departure[]" id="deptime" required>
                                                </div>
                                                <div class="col-1">
                                                    <button type="button" name="remove" id="` + i + `" class="btn btn-link text-danger btn_remove">
                                                        <i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i>
                                                    </button>
                                                </div>
                                            </div>`);
                document.getElementById('max').value = num;
            } else {
                //    document.getElementById('oops').innerText = "*Can only set 5 or less guidelines";
            }
        });
        $(document).on('click', '.btn_remove', function() {
            button_id = $(this).attr("id");
            $('#row' + button_id + '').remove();
            num--;
            document.getElementById('max').value = num;
            max++;
        });
    });

    $("#operateFrom").on("input", function() {
        var minimumm = $(this).val();
        document.getElementById("operateTo").setAttribute("min", minimumm);
    });

    $(function() {
        $('#optionRoute').change(function() {
            var text = $("#optionRoute :selected").text();
            var val = $("#optionRoute :selected").val();
            var nameArr = text.split(' - ');
            // alert(nameArr[0]);
            console.log();
            if (val == 0) {
                $('.tripConfig').hide();
            } else {
                $('.tripConfig').hide();
                document.getElementById('thisorigin').value = nameArr[0];
                document.getElementById('thisdestination').value = nameArr[1];
                $('#tripConfig').show();
                document.getElementById("oridest").innerHTML = text;
            }

        });
    });

    // $('select[name="optionTerminal"]').on('change', function() {
    //         var text = $("#optionTerminal :selected").text();
    //         var some_value = $("#optionTerminal :selected").val();
    // alert($some_value);
    // var nameArr = text.split(' | ');
    // var $myselect_a_option = $("#myselect_a").val();
    // if($myselect_a_option != ''){  
    // var $some_value = '58';
    // $("select[name='optionRoute'] option.filterable_option[data-customattribute!=" + $some_value + "]").remove();
    // $('#optionRoute').find('option[value!="'+some_value+'"]').remove();
    // $('#optionRoute').siblings('[value="'+ some_value +'"]').remove();
    // $('#optionRoute option[value="'+ some_value +'"]').remove();
    // $('#optionRoute').find('option[value!="'+some_value+'"]').remove();
    // $('#optionRoute')
    // .find('option[value!="'+some_value+'"]')
    // .remove();
    // }

    // });

    // $("#optionRoute").on("change", function() {
    //    var route = $(this).val(); 
    //    var text = $("#optionRoute :selected").text();

    //    alert(text);
    //    var nameArr = text.split(' - ');
    //     document.getElementById('thisorigin').val(nameArr[0]);
    //     document.getElementById('thisdestination').val(nameArr[1]);
    // });
    $("#exampleRadios1").on("input", function() {
        var selected = $(this).val();
        // document.getElementById("same").disabled = true; 
        // document.getElementById("diff").disabled = true; 
    });
    $("#exampleRadios2").on("input", function() {
        var selected = $(this).val();
        // document.getElementById("same").disabled = true; 
        // document.getElementById("diff").disabled = true; 
    });
    $("#exampleRadios3").on("input", function() {
        var selected = $(this).val();
        // document.getElementById("same").disabled = false; 
        // document.getElementById("diff").disabled = false; 
    });
</script>