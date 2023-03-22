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

$result = $conn->query("SELECT * FROM routes WHERE companyID = $id");
$routes = $result->fetch_all(MYSQLI_ASSOC);
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
                                <h5 class="text-secondary">Manage Routes</h5>
                            </div>
                            <div class="col-sm-6 ">
                                <a href="#" class="btn btn-primary col-4" data-bs-toggle="modal" data-bs-target="#addRouteModal"><i class="material-icons">&#xE145;</i><span>Add New Route</span></a>
                            </div>
                        </div>
                    </div>
                    <table id="routeTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-secondary">Route ID</th>
                                <th class="text-secondary">Point A</th>
                                <th class="text-secondary">Point B</th>
                                <th class="text-secondary">Duration</th>
                                <th class="text-secondary">Nearby Terminal</th>
                                <!-- <th class="text-secondary">Fare</th> -->
                                <th class="text-secondary">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($routes as $route) : ?>
                                <tr>
                                    <td><?php echo $route["id"] ?></td>
                                    <td id="pointA<?php echo $route['id']; ?>"><?php echo $route["pointA"] ?></td>
                                    <td id="pointB<?php echo $route['id']; ?>"><?php echo $route["pointB"] ?></td>
                                    <td id="duration<?php echo $route['id']; ?>">
                                        <?php
                                        $pieces = explode(":", $route["duration"]);
                                        ?>
                                        <span id="hour<?php echo $route['id']; ?>"><?php echo $pieces[0]; ?></span> hrs : <span id="mins<?php echo $route['id']; ?>"><?php echo $pieces[1]; ?></span> mins

                                    </td>
                                    <td id="inbetween<?php echo $route['id']; ?>">
                                        <?php
                                        $routeidd = $route["id"];

                                        $result41 = $conn->query("SELECT * FROM in_between WHERE companyID = '$id' AND `routeID` = '$routeidd'");
                                        $in_between_addresses = $result41->fetch_all(MYSQLI_ASSOC);
                                        // foreach($in_between_addresses as $in_between_addresse){
                                        //     echo $in_between_addresse['in_between_address'].', ';
                                        // }
                                        $result = "";
                                        foreach ($in_between_addresses as $in_between_addresse) {
                                            $result .= (next($in_between_addresses)) ? $in_between_addresse['in_between_address'] . ", " : $in_between_addresse['in_between_address'];
                                        }
                                        echo $result;
                                        // $ressss = $conn->query("SELECT `in_between_address` FROM `in_between` WHERE `companyID`='$id' AND `routeID_A` = '$routeidd' OR `routeID_B` = '$routeidd'");
                                        // if($ressss){
                                        // $in_between_address = array_values($ressss->fetch_assoc());
                                        // echo $in_between_address[0];
                                        // }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="home_admin_branches.php#mymodaleditRoutes" class="edit" data-bs-toggle="modal" data-userid="<?php echo $route['id']; ?>"><i class="material-icons text-warning" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                                        <a href="home_admin_branches.php#mymodaldeleteRoutes" class="delete" data-bs-toggle="modal" data-userid="<?php echo $route['id']; ?>"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
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


    <div class="modal" id="addRouteModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Add Route</span>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form" id="add_route" action="" method="post">
                        <div class="cont-popup cont col-sm-12 col-sm-offset-12">
                            <div class="row">
                                <div class="col form-group md-6">
                                    <label class="h6 text-muted">Set terminal for point A</label>
                                    <select class="form-select text-muted" name="optionterminalA" id="optionterminalA" aria-label="Default select example">
                                        <option value="" class="text-muted" selected>Select terminal</option>
                                        <?php foreach ($terms as $term) : ?>
                                            <option value="<?php echo $term['id']; ?>"><?php echo $term['terminal_name'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col form-group md-6">
                                    <label class="h6 text-muted">Set terminal for point B</label>
                                    <select class="form-select text-muted" name="optionterminalB" id="optionterminalB" aria-label="Default select example">
                                        <option value="" class="text-muted" selected>Select terminal</option>

                                    </select>
                                </div>
                            </div>
                            <div id="tripConfig" class="tripConfig">
                                <div class="row">
                                    <div class="col">
                                        <label class="h6 text-muted">Available terminal near point B</label>
                                        <div class="form-group md-6" id="dynamic_field">
                                            <div class="row">
                                                <div class="col-lg">
                                                    <select class="form-select text-muted" name="inbetween[]" id="inbtwn" aria-label="Default select example">
                                                        <option value="" class="text-muted" selected>Select terminal</option>
                                                        <?php foreach ($terms as $term) : ?>
                                                            <option value="<?php echo $term['id']; ?>"><?php echo $term['terminal_name'] ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                                <div class="col-lg-3">
                                                    <button type="button" name="add" id="add" class="btn btn-success coll-2">Add More</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" id="maxbtwn" name="maxbtwn" value="1">
                                    <p class="text-danger" id="oops"></p>
                                </div>
                            </div>
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
                        <input type="hidden" class="form-control" name="companyID" id="companyID" value="<?php echo $id; ?>">
                        <button class="btn btn-primary float-right mr-3 mt-4" type="submit" name="add_route" form="add_route">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="mymodaleditRoutes">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Edit <span id="namee"></span> Information</span>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form" id="edit_route" action="" method="Post">
                        <div class="cont-popup cont col-sm-12 col-sm-offset-12">
                            <input type="hidden" class="form-control" name="recID" id="recID">
                            <input type="hidden" class="form-control" name="companyID" id="companyID" value="<?php echo $id; ?>">
                            <div class="form-group md-6">
                                <label class="h6 text-muted">Point A</label>
                                <input type="text" class="form-control" name="pointA" placeholder="" readonly>
                            </div>
                            <div class="form-group md-6 mt-3">
                                <label class="h6 text-muted">Point B</label>
                                <input type="text" class="form-control" name="pointB" placeholder="" readonly>
                            </div>
                            <label class="h6 text-muted mt-3">Trip Duration</label>
                            <div class="row mt-3">
                                <div class="col d-flex flex-row pb-0">
                                    <input type="number" class="form-control" name="hour" id="hour" placeholder="" value="00" maxlength="2" size="2" max="24" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                                    <p class="p-2 text-muted"><small> Hour</small></p>
                                </div>
                                <div class="col d-flex flex-row pb-0">
                                    <input type="number" class="form-control" name="minute" id="minute" placeholder="" value="00" maxlength="2" size="2" max="60" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                                    <p class="p-2 text-muted"><small> Minutes</small></p>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary float-right mr-3 mt-4" type="submit" name="edit_route" form="edit_route">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="mymodaldeleteRoutes">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h6 class="text-light pt-2">Delete Route</h6>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>This action cannot be undone. are you you want to continue?</p>
                    <div class="float-right">
                        <p id="busidtext" style="display:none;"></p>
                        <?php  ?>
                        <form class="form" id="delete_route" action="" method="Post">
                            <input type="hidden" class="form-control" name="recID" value="">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="delete_route" form="delete_route" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

<script>
    $('#mymodaleditRoutes').on('show.bs.modal', function(e) {
        var recID = $(e.relatedTarget).data('userid');
        var id = recID;
        var pointA = $('#pointA' + id).text();
        var pointB = $('#pointB' + id).text();
        var hour = $('#hour' + id).text();
        var mins = $('#mins' + id).text();
        // var fare=$('#fare'+id).text();
        $(e.currentTarget).find('input[name="recID"]').val(recID);
        $(e.currentTarget).find('input[name="pointA"]').val(pointA);
        $(e.currentTarget).find('input[name="pointB"]').val(pointB);
        $(e.currentTarget).find('input[name="hour"]').val(hour);
        $(e.currentTarget).find('input[name="minute"]').val(mins);
        // $(e.currentTarget).find('input[name="fare"]').val(fare);
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
                                                <div class="col-9">    
                                                    <select class="form-select text-muted" name="inbetween[]" id="inbtwn" aria-label="Default select example">
                                                        <option value="" class="text-muted" selected>Select terminal</option>
                                                        <?php foreach ($terms as $term) : ?>
                                                            <option value="<?php echo $term['id']; ?>"><?php echo $term['terminal_name'] ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                                <div class="col-1">
                                                    <button type="button" name="remove" id="` + i + `" class="btn btn-link text-danger btn_remove">
                                                        <i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i>
                                                    </button>
                                                </div>
                                            </div>`);
                document.getElementById('maxbtwn').value = num;
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

    $(document.body).on('change', "#optionterminalA", function(e) {
        var optVal = $("#optionterminalA option:selected").val();
        var selectobjecta = document.getElementById("optionterminalA");
        var selectobjectb = document.getElementById("optionterminalB");
        $('#optionterminalB option:not(:first)').remove();
        for (var i = 1; i < selectobjecta.length; i++) {
            if (optVal != '') {
                var opt1 = document.getElementById('optionterminalA').options[i];
                var opt2 = document.createElement('option');
                opt2.value = opt1.value;
                opt2.innerHTML = opt1.text;
                selectobjectb.appendChild(opt2);
            }
        }
        for (var i = 1; i < selectobjectb.length; i++) {
            if (selectobjectb.options[i].value == optVal) {
                selectobjectb.remove(i);
            }
        }
    });


    $('#mymodaldeleteRoutes').on('show.bs.modal', function(e) {
        var recID = $(e.relatedTarget).data('userid');
        $(e.currentTarget).find('input[name="recID"]').val(recID);
    });

    $(document).ready(function() {
        $('#routeTable').DataTable({
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
                {
                    orderable: false
                }
            ],
        });
    });
</script>