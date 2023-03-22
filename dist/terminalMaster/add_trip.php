<?php
include('../database/db.php');
include('server_terminal_master.php') ?>
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
    unset($_SESSION['compTerMasemail']);
    unset($_SESSION['compadminName']);
    unset($_SESSION['compadminNameID']);
    unset($_SESSION['compTerMasID']);
    header('location: ../validation/logina.php');
}

$compID = $_SESSION['compadminNameID'];
$termID = $_SESSION['terminalID'];
$email = $_SESSION['compTerMasemail'];
$id = $_SESSION['compTerMasID'];

$result = $conn->query("SELECT * FROM trip WHERE companyID = $compID");
$trips = $result->fetch_all(MYSQLI_ASSOC);
$result3 = $conn->query("SELECT * FROM buses WHERE companyID = $compID");
$buses = $result3->fetch_all(MYSQLI_ASSOC);
$result4 = $conn->query("SELECT DISTINCT pointA_terminalID,pointB_terminalID FROM routes WHERE companyID = $compID");
$termids = $result4->fetch_all(MYSQLI_ASSOC);
$result2 = $conn->query("SELECT * FROM routes WHERE companyID = $compID AND pointA_terminalID= $termID");
$routes = $result2->fetch_all(MYSQLI_ASSOC);

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

        <div class="position-relative flex-fill container  py-3 px-2 ">

            <div class="container mb-5 px-5 py-5 bg-light rounded shadow">
                <?php if (isset($_SESSION['compadminsuccess'])) { ?>
                    <div class="container container-large mb-5 p-0 rounded shadow">
                        <div class="alert alert-success mt-3" role="alert">

                            <form action="" method="get" class="d-flex justify-content-between" id="unsetSuccessAlert">
                                <?php echo $_SESSION['compadminsuccess']  ?>
                                <button type="submit" class="close" name="unsetSuccessAlert" form="unsetSuccessAlert" class="btn-close" aria-label="Close"></button>
                            </form>

                        </div>
                    </div>
                <?php } ?>
                <div class="container container-large mb-5 p-0 rounded shadow">
                    <?php include('errors.php'); ?>
                </div>
                <form class="form" id="add_new_trip" action="" method="post">
                    <div class="col-5">
                        <div class="form-group md-6">
                            <label class="h6 text-muted">Routes</label>
                            <select class="form-select text-muted" name="optionRoute" id="optionRoute" aria-label="Default select example">
                                <option value="" class="text-muted" selected>Select Route</option>
                                <?php foreach ($routes as $route) : ?>
                                    <option value="<?php echo $route['id']; ?>"><?php echo $route['pointA'] . " - " . $route['pointB'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div id="tripConfig" class="tripConfig" style="display:none">
                        <div class="col-7 mt-3">
                            <div class="form-group md-6" id="dynamic_field">
                                <div class="row">
                                    <div class="col-lg">
                                        <input type="time" class="form-control" name="departure[]" id="deptime" required>
                                    </div>
                                    <div class="col-lg-3">
                                        <button type="button" name="add" id="add" class="btn btn-success coll-2">Add More</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="max" value="1">
                        <p class="text-danger" id="oops"></p>
                    </div>


                    <input type="hidden" id="thisorigin" name="thisorigin" value="" readonly>
                    <input type="hidden" id="thisdestination" name="thisdestination" value="" readonly>
                    <button class="btn btn-primary mr-3 mt-2" type="submit" name="add_new_trip" form="add_new_trip">Add Trip</button>
                    <?php  ?>

                </form>
            </div>
        </div>
    </main>
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
                                                <div class="col-9">    
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
</script>