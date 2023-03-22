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
$x = 1;
$default = 1;
$name = $_SESSION['compadminName'];
$email = $_SESSION['compadminemail'];
$id = $_SESSION['compadminID'];
$result = $conn->query("SELECT * FROM buses WHERE companyID = '$id'");
$buses = $result->fetch_all(MYSQLI_ASSOC);
$result2 = $conn->query("SELECT * FROM seat_configuration");
$configs = $result2->fetch_all(MYSQLI_ASSOC);
$result3 = $conn->query("SELECT * FROM employees WHERE companyID = '$id'");
$emps = $result3->fetch_all(MYSQLI_ASSOC);
$result4 = $conn->query("SELECT * FROM terminal WHERE companyID = '$id'");
$terms = $result4->fetch_all(MYSQLI_ASSOC);
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
            <div class="container container-large mb-5 p-0 rounded shadow">
                <?php include('errors.php'); ?>
            </div>

            <div class="table-responsive">
                <div class="table-wrapper">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-6">
                                <h5 class="text-secondary">Manage Buses</h5>
                            </div>
                            <div class="col-sm-6 ">
                                <a href="#" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#mymodalbusconfig"><i class="material-icons">&#xE145;</i><span>Add New seat configuration</span></a>
                                <a href="#" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#mymodalbus"><i class="material-icons">&#xE145;</i><span>Add New Bus</span></a>
                            </div>
                        </div>
                    </div>
                    <table id="bustable" class="table triptable table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-secondary">Bus ID</th>
                                <th class="text-secondary">Bus Terminal</th>
                                <th class="text-secondary">Bus Model</th>
                                <th class="text-secondary">Bus Number</th>
                                <th class="text-secondary">Plate Number</th>
                                <th class="text-secondary">Seat Type</th>
                                <th class="text-secondary">Total Seat</th>
                                <th class="text-secondary">COVID-19 Protocol</th>
                                <!-- <th class="text-secondary">Conductor</th> -->
                                <th class="text-secondary">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($buses as $bus) : ?>
                                <tr>
                                    <td><?php echo $bus["id"] ?></td>
                                    <td id="busterminal<?php echo $bus['id']; ?>">
                                        <?php
                                        $terminalID = $bus["terminalID"];
                                        if ($terminalID != 0) {
                                            $count32 = $conn->query("SELECT terminal_name FROM terminal WHERE id='$terminalID' AND companyID='$id'") or die($mysql->connect_error);
                                            $result32 = array_values($count32->fetch_assoc());
                                            echo $result32[0];
                                        } else {
                                            echo "Terminal not selected";
                                        }
                                        ?>
                                    </td>
                                    <td id="busmodel<?php echo $bus['id']; ?>"><?php echo $bus["bus_model"] ?></td>
                                    <td id="busnumber<?php echo $bus['id']; ?>"><?php echo $bus["busNumber"] ?></td>
                                    <td id="busplatenumber<?php echo $bus['id']; ?>"><?php echo $bus["plate_number"] ?></td>
                                    <td id="seattype<?php echo $bus['id']; ?>">
                                        <?php
                                        echo $bus['seat_type'];
                                        ?>
                                    </td>
                                    <td id="totalseat<?php echo $bus['id']; ?>">
                                        <?php
                                        echo $bus['total_seat'];
                                        ?>
                                    </td>
                                    <td id="driver<?php echo $bus['id']; ?>"><?php echo $bus["protocol"] ?></td>
                                    <!-- <td id="conductor<?php echo $bus['id']; ?>"><?php echo $bus["conductor_name"] ?></td> -->
                                    <td>
                                        <div class="dropdown show">
                                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" data-flip="false" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Action
                                            </a>

                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                <a href="home_admin_buses.php#mymodaleditbus" class="dropdown-item" data-bs-toggle="modal" data-userid="<?php echo $bus['id']; ?>">Edit details</a>
                                                <a href="home_admin_buses.php#mymodaleditbusseat" class="dropdown-item" data-bs-toggle="modal" data-userid="<?php echo $bus['id']; ?>">Change seat type</a>
                                                <div class="dropdown-divider"></div>
                                                <a href="home_admin_buses.php#mymodaldeletebus" class="dropdown-item text-danger" data-bs-toggle="modal" data-userid="<?php echo $bus['id']; ?>">Delete Bus</a>
                                            </div>
                                        </div>
                                        <!-- <a href="home_admin_buses.php#mymodaleditbus" class="edit" data-bs-toggle="modal" data-userid="<?php echo $bus['id']; ?>"><i class="material-icons text-success" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                                                 <a href="home_admin_buses.php#mymodaldeletebus" class="delete" data-bs-toggle="modal" data-userid="<?php echo $bus['id']; ?>"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a> -->
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

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

    <!--BUS SEAT CONFIGURATION MODAL-->
    <div class="modal" id="mymodalbusconfig">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Add Bus Seat Configuration</span>
                    <div class="close text-white" data-bs-dismiss="modal">&times;</div>
                </div>
                <div class="modal-body">
                    <form class="frm" id="addbusseat" action="" method="Post">
                        <div class="alert alert-info" role="alert">
                            Supported number of seat and seat layout
                            <li>29 seats - 2x1 seat layout with middle comfort room</li>
                            <li>45 seats - 2x2 seat layout</li>
                            <li>49 seats - 2x2 seat layout</li>
                            <li>60 seats - 3x2 seat layout</li>
                            <li>61 seats - 3x2 seat layout</li>
                        </div>
                        <div class="row">
                            <div class="col form-group md-6">
                                <label class="h6 text-muted">Total Seat</label>
                                <input type="number" class="form-control" name="totalseat" required>
                            </div>
                            <div class="col form-group md-6">
                                <label class="h6 text-muted">Seat Layout</label>
                                <input type="text" class="form-control" name="seattype" required>
                            </div>
                        </div>

                        <input type="hidden" class="form-control" name="compid" value="<?php echo $id; ?>">
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success ml-3" type="submit" name="addbusseat" form="addbusseat">Add</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="mymodalbus">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Add Bus</span>
                    <div class="close text-white" data-bs-dismiss="modal">&times;</div>
                </div>
                <div class="modal-body">
                    <form class="frm" id="addbus" action="" method="Post">
                        <div class="row">
                            <div class="col form-group md-6">
                                <label class="h6 text-muted">Bus Number</label>
                                <input type="text" class="form-control" name="busNumber" maxlength="6" size="6" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                            </div>
                            <div class="col form-group md-6">
                                <label class="h6 text-muted">Bus Model</label>
                                <input type="text" class="form-control" name="busModel" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col form-group md-6">
                                <label class="h6 text-muted">Plate Number</label>
                                <input type="text" class="form-control" name="plateNumber" maxlength="10" size="10" pattern="[a-zA-Z0-9\s]+" required>
                            </div>
                            <div class="col form-group md-6">
                                <label class="h6 text-muted">Bus Seat Type</label>
                                <select class="form-select text-muted" name="optionSeatType" aria-label="Default select example">
                                    <option value="" class="text-muted" selected>Select Seat Configuration</option>
                                    <?php foreach ($configs as $config) : ?>
                                        <?php if ($config['companyID'] == $id) { ?>
                                            <option value="<?php echo $config['id']; ?>"><?php echo $config['seat_type'] . " Bus row (" . $config['total_seat'] . " seats)" ?></option>
                                        <?php } ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group md-6 mt-3">
                            <label class="h6 text-muted">Set Terminal</label>
                            <select class="form-select text-muted" name="optionterminal" aria-label="Default select example">
                                <option value="" class="text-muted" selected>Select terminal</option>
                                <?php foreach ($terms as $term) : ?>
                                    <option value="<?php echo $term['id']; ?>"><?php echo $term['terminal_name'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="row mt-3">
                            <div class="col form-group md-6">
                                <input type="checkbox" name="protocol" value="On">
                                <label class="h6 text-muted">Covid-19 Protocol(Social Distancing)</label>
                            </div>
                        </div>
                        <input type="hidden" class="form-control" name="compid" value="<?php echo $id; ?>">
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success ml-3" type="submit" name="addbus" form="addbus">Add</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="mymodaleditbus">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Edit Bus</span>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="frm" id="editbus" action="" method="Post">
                        <div class="row">
                            <input type="hidden" class="form-control" name="busID" value="">
                            <div class="col form-group md-6">
                                <label class="h6 text-muted">Bus Number</label>
                                <input type="text" class="form-control" name="busNumber" maxlength="6" size="6" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                            </div>
                            <div class="col form-group md-6">
                                <label class="h6 text-muted">Bus Model</label>
                                <input type="text" class="form-control" name="busModel" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col form-group md-6">
                                <label class="h6 text-muted">Plate Number</label>
                                <input type="text" class="form-control" name="plateNumber" maxlength="10" size="10" pattern="[a-zA-Z0-9\s]+" required>
                            </div>
                            <br>
                        </div>
                        <div class="row mt-3">
                            <div class="col form-group md-6">
                                <input type="checkbox" name="protocol" value="On">
                                <label class="h6 text-muted">Covid-19 Protocol(Social Distancing)</label>
                            </div>
                        </div>
                        <input type="hidden" class="form-control" name="compid" value="<?php echo $id; ?>">
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success ml-3" type="submit" name="editbus" form="editbus">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="mymodaleditbusseat">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Change seat type</span>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="frm" id="editbusseat" action="" method="Post">
                        <input type="hidden" class="form-control" name="busID" value="">
                        <div class="form-group md-6">
                            <label class="h6 text-muted">Bus Seat Type</label>
                            <select class="form-select text-muted" name="optionSeatType" aria-label="Default select example">
                                <option value="" class="text-muted" selected>Select Seat Configuration</option>
                                <?php foreach ($configs as $config) : ?>
                                    <?php if ($config['companyID'] == $id) { ?>
                                        <option value="<?php echo $config['id']; ?>"><?php echo $config['seat_type'] . " Bus row (" . $config['total_seat'] . " seats)" ?></option>
                                    <?php } ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <input type="hidden" class="form-control" name="compid" value="<?php echo $id; ?>">
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success ml-3" type="submit" name="editbusseat" form="editbusseat">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="mymodaldeletebus">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h6 class="text-light pt-2">Remove Bus</h6>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>This action cannot be undone. are you you want to continue?</p>
                    <div class="float-right">
                        <p id="busidtext" style="display:none;"></p>
                        <?php  ?>
                        <form class="form" id="delete_bus" action="" method="Post">
                            <input type="hidden" class="form-control" name="busid" value="">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="delete_bus" form="delete_bus" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

<script>
    var code = {};

    $('#mymodaleditbus').on('show.bs.modal', function(e) {
        var userID = $(e.relatedTarget).data('userid');
        var id = userID;
        var busmodel = $('#busmodel' + id).text();
        var busnumber = $('#busnumber' + id).text();
        var buspnumber = $('#busplatenumber' + id).text();
        var seattype = $('#seattype' + id).text();
        $(e.currentTarget).find('input[name="busID"]').val(userID);
        $(e.currentTarget).find('input[name="busNumber"]').val(busnumber);
        $(e.currentTarget).find('input[name="busModel"]').val(busmodel);
        $(e.currentTarget).find('input[name="plateNumber"]').val(buspnumber);

        // $(e.currentTarget).find('input[name="email"]').val(email);
        // $(e.currentTarget).find('input[name="role"]').val(role);
    });

    $('#mymodaleditbusseat').on('show.bs.modal', function(e) {
        var userID = $(e.relatedTarget).data('userid');
        var id = userID;
        $(e.currentTarget).find('input[name="busID"]').val(userID);
    });

    $('#mymodaldeletebus').on('show.bs.modal', function(e) {
        var userID = $(e.relatedTarget).data('userid');
        var id = userID;
        var fn = $('#fullname' + id).text();
        $(e.currentTarget).find('input[name="busid"]').val(userID);

        document.getElementById("nameee").innerText = fn;
    });


    $(document).ready(function() {
        $('#bustable').DataTable({
            "dom": "<'row'<'col-sm-12 col-md-6'f><'col-sm-12 col-md-6'>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "language": {
                "info": "Showing <b>_START_</b> to <b>_END_</b> of <b>_TOTAL_</b> entries"
            },
            "pageLength": 5,
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
        });
    });
</script>