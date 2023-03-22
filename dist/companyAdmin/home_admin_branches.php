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
$result = $conn->query("SELECT * FROM user_partner_tmaster WHERE companyID = '$id'");
$users = $result->fetch_all(MYSQLI_ASSOC);
$result2 = $conn->query("SELECT * FROM terminal WHERE companyID = '$id'");
$terminals = $result2->fetch_all(MYSQLI_ASSOC);
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
                                <h5 class="text-secondary">Manage Terminals</h5>
                            </div>
                            <div class="col-sm-6 ">
                                <a href="#" class="btn btn-primary col-4" data-bs-toggle="modal" data-bs-target="#addBranchesModal"><i class="material-icons">&#xE145;</i><span>Add New Terminal</span></a>
                            </div>
                        </div>
                    </div>
                    <table id="terminalTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-secondary">Terminal ID</th>
                                <th class="text-secondary">Terminal Name</th>
                                <th class="text-secondary">Street Address</th>
                                <th class="text-secondary">City</th>
                                <th class="text-secondary">Province</th>
                                <th class="text-secondary">Contact Number</th>
                                <th class="text-secondary">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($terminals as $terminal) : ?>
                                <tr>
                                    <td><?php echo $terminal["id"] ?></td>
                                    <td id="termname<?php echo $terminal['id']; ?>"><?php echo $terminal["terminal_name"] ?></td>
                                    <td id="saddress<?php echo $terminal['id']; ?>"><?php echo $terminal["street_address"] ?></td>
                                    <td id="city<?php echo $terminal['id']; ?>"><?php echo $terminal["city"] ?></td>
                                    <td id="province<?php echo $terminal['id']; ?>"><?php echo $terminal["province"] ?></td>
                                    <td id="termconnumber<?php echo $terminal['id']; ?>"><?php echo $terminal["terminal_connumber"] ?></td>
                                    <td>
                                        <a href="home_admin_branches.php#mymodaleditBranches" class="edit" data-bs-toggle="modal" data-userid="<?php echo $terminal['id']; ?>"><i class="material-icons text-warning" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                                        <a href="home_admin_branches.php#mymodaldeleteBranches" class="delete" data-bs-toggle="modal" data-userid="<?php echo $terminal['id']; ?>"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
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

    <div class="modal" id="addBranchesModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Add Terminal</span>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form" id="add_branch" action="" method="Post">
                        <div class="cont-popup cont col-sm-12 col-sm-offset-12">
                            <div class="form-group md-6">
                                <label class="h6 text-muted">Terminal Name</label>
                                <input type="text" class="form-control" name="terminalName" placeholder="" required>
                            </div>
                            <div class="form-group mt-3 md-6">
                                <label class="h6 text-muted">Terminal Contact Number</label>
                                <input type="text" class="form-control" name="cnumber" placeholder="" maxlength="11" size="11" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                            </div>
                            <div class="form-group mt-3 md-6">
                                <label class="h6 text-muted">Street Name</label>
                                <input type="text" class="form-control" name="saddress" placeholder="" required>
                            </div>
                            <div class="form-group mt-3 md-6">
                                <label class="h6 text-muted">City</label>
                                <input type="text" class="form-control" name="city" placeholder="" required>
                            </div>
                            <div class="form-group mt-3 md-6">
                                <label class="h6 text-muted">Province</label>
                                <input type="text" class="form-control" name="province" placeholder="" required>
                            </div>
                        </div>
                        <button class="btn btn-success float-right mr-3 mt-4" type="submit" name="add_branch" form="add_branch">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="mymodaleditBranches">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Edit <span id="namee"></span> Information</span>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form" id="edit_terminal" action="" method="Post">
                        <div class="cont-popup cont col-sm-12 col-sm-offset-12">
                            <input type="hidden" class="form-control" name="terminalID" id="eid">
                            <div class="form-group md-6">
                                <label class="h6 text-muted">Terminal Name</label>
                                <input type="text" class="form-control" name="terminalName" placeholder="" required>
                            </div>
                            <div class="form-group md-6 mt-3">
                                <label class="h6 text-muted">Street Name</label>
                                <input type="text" class="form-control" name="saddress" placeholder="" required>
                            </div>
                            <div class="form-group md-6 mt-3">
                                <label class="h6 text-muted">City</label>
                                <input type="text" class="form-control" name="city" placeholder="" required>
                            </div>
                            <div class="form-group md-6 mt-3">
                                <label class="h6 text-muted">Province</label>
                                <input type="text" class="form-control" name="province" placeholder="" required>
                            </div>
                            <div class="form-group md-6 mt-3">
                                <label class="h6 text-muted">Terminal Contact Number</label>
                                <input type="text" class="form-control" name="cnumber" placeholder="" maxlength="11" size="11" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                            </div>
                        </div>
                        <button class="btn btn-primary float-right mr-3 mt-4" type="submit" name="edit_terminal" form="edit_terminal">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="mymodaldeleteBranches">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h6 class="text-light pt-2">Delete Terminal</h6>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>This action cannot be undone. are you you want to continue?</p>
                    <div class="float-right">
                        <p id="busidtext" style="display:none;"></p>
                        <?php  ?>
                        <form class="form" id="delete_terminal" action="" method="Post">
                            <input type="hidden" class="form-control" name="terminalID" value="">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="delete_terminal" form="delete_terminal" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

<script>
    $('#mymodaleditBranches').on('show.bs.modal', function(e) {
        var branchID = $(e.relatedTarget).data('userid');
        var id = branchID;
        var tname = $('#termname' + id).text();
        var saddress = $('#saddress' + id).text();
        var city = $('#city' + id).text();
        var province = $('#province' + id).text();
        var tnum = $('#termconnumber' + id).text();
        $(e.currentTarget).find('input[name="terminalID"]').val(branchID);
        $(e.currentTarget).find('input[name="terminalName"]').val(tname);
        $(e.currentTarget).find('input[name="saddress"]').val(saddress);
        $(e.currentTarget).find('input[name="city"]').val(city);
        $(e.currentTarget).find('input[name="province"]').val(province);
        $(e.currentTarget).find('input[name="cnumber"]').val(tnum);
    });

    $('#mymodaldeleteBranches').on('show.bs.modal', function(e) {
        var branchID = $(e.relatedTarget).data('userid');
        var id = branchID;
        var address = $('#address' + id).text();
        var busid = $('#busid' + id).text();
        $(e.currentTarget).find('input[name="terminalID"]').val(branchID);
        $('#pTest').text(address);
    });

    $(document).ready(function() {
        $('#terminalTable').DataTable({
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
                {
                    orderable: false
                }
            ],
        });
    });
</script>