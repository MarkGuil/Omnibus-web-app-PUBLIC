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
$result1 = $conn->query("SELECT * FROM bus_trip WHERE companyID = $id");
$btrips = $result1->fetch_all(MYSQLI_ASSOC);
$result2 = $conn->query("SELECT * FROM routes WHERE companyID = $id");
$routes = $result2->fetch_all(MYSQLI_ASSOC);
$result3 = $conn->query("SELECT * FROM buses WHERE companyID = $id");
$buses = $result3->fetch_all(MYSQLI_ASSOC);
$result4 = $conn->query("SELECT DISTINCT pointA_terminalID FROM routes WHERE companyID = $id");
$termids = $result4->fetch_all(MYSQLI_ASSOC);
$result5 = $conn->query("SELECT * FROM terminal WHERE companyID = $id");
$termidsisi = $result5->fetch_all(MYSQLI_ASSOC);

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
            <div class="container p-0 rounded shadow">
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
                                <a href="add_trip.php" class="btn btn-primary"><i class="material-icons">&#xE145;</i><span>Add New Trip</span></a>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-4">
                                <label class="h6 text-muted">Filter terminal</label>
                                <select class="select form-select text-muted" name="filter_termides" id="filter_termides" onchange="FetchRoutesss(this.value)" aria-label="Default select example">
                                    <option value="" class="text-muted" selected>Show all</option>
                                    <?php foreach ($termidsisi as $termid) : ?>
                                        <option value="<?php echo $termid['id']; ?>">
                                            <?php
                                            echo $termid['terminal_name'];
                                            ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-4 ">
                                <label class="h6 text-muted">Filter route</label>
                                <select class="select form-select text-muted" name="filter_routees" id="filter_routees" aria-label="Default select example">
                                    <option value="" class="text-muted" selected>Select route</option>
                                </select>
                            </div>
                            <div class="col-1"><br>
                                <button type="button" name="filter" id="filter" class="btn btn-primary p-2">Filter</button>
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

        <div class="modal" id="editTripModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-dark">
                        <span class="text-light h6">Edit Trip</span>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                <div class="form-group md-6 mt-3">
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
                            <button class="btn btn-success float-right mr-3 mt-4" type="submit" name="edit_trip" form="edit_trip">Confirm</button>
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
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
                            <button class="btn btn-success float-right mr-3 mt-4" type="submit" name="edit_bus_trip" form="edit_bus_trip">Save Changes</button>
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
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
                            <div class=" form-group md-6">
                                <label class="h6 text-muted">Bus</label>
                                <select class="form-select text-muted" name="optionBus" id="optionBus" aria-label="Default select example">
                                    <option value="" class="text-muted" selected>Select Bus</option>
                                    <?php foreach ($buses as $bus) : ?>
                                        <option value="<?php echo $bus['id']; ?>"><?php echo $bus['busNumber'] . " (" . $bus['bus_model'] . ") | " . $bus['seat_type'] . " seat type | " . $bus['total_seat'] . " total seat)"   ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class=" form-group md-6 mt-3">
                                <label class="h6 text-muted">Trip</label>
                                <select class="form-select text-muted" name="optionTrip" id="optionTrip" aria-label="Default select example">
                                    <option value="" class="text-muted" selected>Select Trip</option>
                                    <?php foreach ($trips as $trip) : ?>
                                        <option value="<?php echo $trip['id']; ?>"><?php echo $trip['origin'] . " - " . $trip['destination'] . " | " . date('h:i A', strtotime($trip["departure_time"])); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="row mt-3">
                                <h6 class="mt-3 text-muted">Period Operating:</h6>
                                <div class="col-6 form-group md-6">
                                    <label class="h6 text-muted"><small>Start</small></label>
                                    <input type="text" class="form-control" id="operateFrom1" name="operateFrom" min="<?php echo date("Y-m-d"); ?>" placeholder="00:00PM" required>
                                </div>
                            </div>
                            <div class="row mt-3">
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
                            <button class="btn btn-primary float-right mr-3 mt-5" type="submit" name="add_bus_trip" form="add_bus_trip">Add</button>
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
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
    </main>


    <footer class="bottom-0 text-center text-lg-start">
        <div class="text-center text-light p-3" style="background-color: #212529">
            © 2021 Copyright:
            <a class="text-light" href="">Omnibus</a>
        </div>
    </footer>


</body>

</html>

<script>
    function FetchRoutesss(id) {
        $('#filter_routees').html('');
        if (id == '') {
            $('#filter_routees').html('<option>Select route</option>');
            // $('#optionTripTime').html('<option>Select Time</option>');
            // alert(id);
        } else {
            $.ajax({
                type: 'post',
                url: 'extentions/fetchtripoption.php',
                data: {
                    terminalid: id
                },
                success: function(data) {
                    $('#filter_routees').html(data);
                }
            })
            // alert(id);
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
        // alert(or);
        var dest = $('#destination' + id).text();
        // var fare = $('#fare' + id).text();
        // var arrival = $('#arrival' + id).text();
        var departure = $('#departure' + id).text();
        var duration = $('#duration' + id).text();
        var hour = $('#hour' + id).text();
        var mins = $('#mins' + id).text();
        // var ofrom = $('#ofrom' + id).text();
        // var oto = $('#oto' + id).text();
        $(e.currentTarget).find('input[name="origin"]').val(or);
        $(e.currentTarget).find('input[name="tripID"]').val(tripID);
        $(e.currentTarget).find('input[name="destination"]').val(dest);
        $(e.currentTarget).find('input[name="departure"]').val(departure);
        // $(e.currentTarget).find('input[name="arrival"]').val(departure);
        $(e.currentTarget).find('input[name="duration"]').val(duration);
        $(e.currentTarget).find('input[name="hour"]').val(hour);
        $(e.currentTarget).find('input[name="minute"]').val(mins);
        // $(e.currentTarget).find('input[name="operateFrom"]').val(ofrom);
        // $(e.currentTarget).find('input[name="operateTo"]').val(oto);
        // $('#datePicker').val(ofrom);
    });

    $('#mymodaldeleteTrips').on('show.bs.modal', function(e) {
        var tripID = $(e.relatedTarget).data('userid');
        var id = tripID;
        $(e.currentTarget).find('input[name="tripID"]').val(tripID);
    });

    // $('#mymodaldeleteRoutes').on('show.bs.modal', function(e) {
    //     var recID = $(e.relatedTarget).data('userid');
    //     $(e.currentTarget).find('input[name="recID"]').val(recID);
    // });
    // $(document).ready(function() {
    //     $('#ontripTable').DataTable({
    //         "dom": "<'row'<'col-sm-12 col-md-6'f><'col-sm-12 col-md-6'l>>" +
    //             "<'row'<'col-sm-12'tr>>" +
    //             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    //         "language": {"info": "Showing <b>_START_</b> to <b>_END_</b> of <b>_TOTAL_</b> entries"},
    //         columns: [
    //             null,
    //             null,
    //             null,
    //             null,
    //             null,
    //         ],
    //     });
    // });

    $(document).ready(function() {
        fill_datatable();

        function fill_datatable(filter_termid = '', filter_route = '') {
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
                        filter_termid: filter_termid,
                        filter_route: filter_route
                    }
                }
            });
        }

        $('#filter').click(function() {
            var filter_termid = $("#filter_termides :selected").val();
            var filter_route = $("#filter_routees :selected").val();
            if (filter_termid != '' && filter_route == '') {
                $('#ontripTable').DataTable().destroy();
                fill_datatable(filter_termid, filter_route);
            } else if (filter_termid != '' && filter_route != '') {
                $('#ontripTable').DataTable().destroy();
                fill_datatable(filter_termid, filter_route);
            } else {
                // alert(filter_termid);
                $('#ontripTable').DataTable().destroy();
                fill_datatable();
            }
        });

        // $('#filter_termid').change(function(){
        //     // var filter_termid = $('#filter_termid').val();
        //     var filter_termid = $("#filter_termid :selected").val();
        //     var filter_route = $("#filter_route :selected").val();
        //     // alert(filter_termid =$(this).val());
        //     if(filter_termid != '')
        //     {
        //         $('#ontripTable').DataTable().destroy();
        //         fill_datatable(filter_termid,filter_route);
        //     }
        //     else
        //     {
        //         // alert(filter_termid);
        //         $('#ontripTable').DataTable().destroy();
        //         fill_datatable();
        //     }
        // });

        // $('#filter_route').change(function(){
        //     // var filter_termid = $('#filter_termid').val();
        //     var filter_termid = $("#filter_termid :selected").val();
        //     var filter_route = $("#filter_route :selected").val();
        //     // alert(filter_termid =$(this).val());
        //     if(filter_route != '')
        //     {
        //         $('#ontripTable').DataTable().destroy();
        //         fill_datatable(filter_termid,filter_route);
        //     }
        //     else
        //     {
        //         // alert(filter_termid);
        //         $('#ontripTable').DataTable().destroy();
        //         fill_datatable();
        //     }
        // });
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