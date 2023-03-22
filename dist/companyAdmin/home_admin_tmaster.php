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
                                <h5 class="text-secondary">Manage Terminal Master Accounts</h5>
                            </div>
                            <div class="col-sm-6 ">
                                <a href="#" class="btn btn-primary col-4" data-bs-toggle="modal" data-bs-target="#addTMAccountModal"><i class="material-icons">&#xE145;</i><span>Add New Account</span></a>
                            </div>
                        </div>
                    </div>
                    <table id="terminalMasterTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-secondary">ID</th>
                                <th class="text-secondary">Terminal</th>
                                <th class="text-secondary">Fullname</th>
                                <th class="text-secondary">Street Address</th>
                                <th class="text-secondary">City</th>
                                <th class="text-secondary">Province</th>
                                <th class="text-secondary">Contact Number</th>
                                <th class="text-secondary">Email</th>
                                <th class="text-secondary">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user) : ?>
                                <tr>
                                    <td><?php echo $user["id"] ?></td>
                                    <?php
                                    $tid = $user["terminalID"];
                                    $termresult = $conn->query("SELECT terminal_name FROM terminal WHERE id='$tid' AND companyID='$id'") or die($mysql->connect_error);
                                    $termresults = array_values($termresult->fetch_assoc());
                                    ?>
                                    <td id="mytermid<?php echo $user['id']; ?>"><?php echo $termresults[0] ?></td>
                                    <td id="fullname<?php echo $user['id']; ?>"><?php echo $user["fullname"] ?></td>
                                    <td id="saddress<?php echo $user['id']; ?>"><?php echo $user["street_address"] ?></td>
                                    <td id="city<?php echo $user['id']; ?>"><?php echo $user["city"] ?></td>
                                    <td id="province<?php echo $user['id']; ?>"><?php echo $user["province"] ?></td>
                                    <td id="connumber<?php echo $user['id']; ?>"><?php echo $user["connumber"] ?></td>
                                    <td id="email<?php echo $user['id']; ?>"><?php echo $user["email"] ?></td>
                                    <td>
                                        <div class="dropdown show">
                                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Actions
                                            </a>

                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                <a href="home_admin_branches.php#editTMAccountterminalModal" class="edit dropdown-item" data-bs-toggle="modal" data-userid="<?php echo $user['id']; ?>">
                                                    <i class="material-icons text-warning" data-bs-toggle="tooltip" title="Edit">&#xE254;</i>
                                                    Change terminal
                                                </a>
                                                <a href="home_admin_branches.php#editTMAccountModal" class="edit dropdown-item" data-bs-toggle="modal" data-userid="<?php echo $user['id']; ?>">
                                                    <i class="material-icons text-warning" data-bs-toggle="tooltip" title="Edit">&#xE254;</i>
                                                    Edit Personal Info
                                                </a>

                                                <div class="dropdown-divider"></div>

                                                <a href="home_admin_branches.php#deleteTMAccountModal" class="delete dropdown-item" data-bs-toggle="modal" data-userid="<?php echo $user['id']; ?>">
                                                    <i class="material-icons" data-bs-toggle="tooltip" title="Delete">&#xE872;</i>
                                                    Delete Account
                                                </a>
                                            </div>
                                        </div>

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

    <div class="modal" id="addTMAccountModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Add New Account</span>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form" id="add_tmaster_account" action="" method="Post" oninput='password2.setCustomValidity(password2.value != password1.value ? "Passwords do not match." : "")'>
                        <div class="cont-popup cont col-sm-12 col-sm-offset-12">
                            <div class="form-group md-6">
                                <label class="h6 text-muted">Select Terminal</label>
                                <div class="form-group md-6 mt-2" id="dynamic_field2">
                                    <div class="row pb-2">
                                        <div class="col-lg">
                                            <select class="form-select" name="optiontterminal" id="optionterminal1" aria-label="Default select example" required>
                                                <option class="text-muted" value="">Open this select menu</option>
                                                <?php foreach ($terminals as $terminal) : ?>
                                                    <option value="<?php echo $terminal['id']; ?>"><?php echo $terminal['terminal_name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group md-6 mt-3">
                                <label class="h6 text-muted">Fullname</label>
                                <input type="text" class="form-control" name="fullname" placeholder="" required>
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
                            <div class="row mt-3 ">
                                <div class="col form-group md-6">
                                    <label class="h6 text-muted">Contact Number</label>
                                    <input type="text" class="form-control" name="cnumber" placeholder="" maxlength="11" size="11" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                                </div>
                                <div class="col form-group md-6">
                                    <label class="h6 text-muted">Email Address</label>
                                    <input type="email" class="form-control" name="email" placeholder="" required>
                                </div>
                            </div>
                            <div class="row mt-3 ">
                                <div class="col form-group md-6">
                                    <label for="inputPassword4" class="h6 text-muted">Password</label>
                                    <input type="password" class="form-control" name="password1" autocomplete="on" placeholder="" required>
                                </div>
                                <div class="col form-group md-6">
                                    <label for="inputPassword4" class="h6 text-muted">Re-type Password</label>
                                    <input type="password" class="form-control" name="password2" autocomplete="on" placeholder="" required>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-success float-right mr-3 mt-4" type="submit" name="add_tmaster_account" form="add_tmaster_account">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="editTMAccountterminalModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Change terminal for this account</span>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form" id="edit_tmaster_account_terminal" action="" method="Post">
                        <div class="cont-popup cont col-sm-12 col-sm-offset-12">
                            <input type="hidden" class="form-control" name="userID" id="ceid">
                            <div class="form-group md-6">
                                <label class="h6 text-muted">Select Terminal</label>
                                <div class="form-group md-6 mt-2" id="dynamic_field2">
                                    <div class="row pb-2">
                                        <div class="col-lg">
                                            <select class="form-select" name="optiontterminal" id="optionterminal2" aria-label="Default select example" required>
                                                <option class="text-muted" value="">Open this select menu</option>
                                                <?php foreach ($terminals as $terminal) : ?>
                                                    <option value="<?php echo $terminal['id']; ?>"><?php echo $terminal['terminal_name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-success float-right mr-3 mt-4" type="submit" name="edit_tmaster_account_terminal" form="edit_tmaster_account_terminal">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="editTMAccountModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Edit Account</span>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form" id="edit_tmaster_account" action="" method="Post">
                        <div class="cont-popup cont col-sm-12 col-sm-offset-12">
                            <input type="hidden" class="form-control" name="userID" id="aeid">
                            <div class="form-group md-6">
                                <label class="h6 text-muted">Fullname</label>
                                <input type="text" class="form-control" name="fullname" placeholder="" required>
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
                            <div class="row mt-3 ">
                                <div class="col form-group md-6">
                                    <label class="h6 text-muted">Contact Number</label>
                                    <input type="text" class="form-control" name="cnumber" placeholder="" maxlength="11" size="11" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                                </div>
                                <div class="col form-group md-6">
                                    <label class="h6 text-muted">Email Address</label>
                                    <input type="email" class="form-control" name="email" placeholder="" required>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-success float-right mr-3 mt-4" type="submit" name="edit_tmaster_account" form="edit_tmaster_account">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="deleteTMAccountModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h6 class="text-light pt-2">Are you sure you want to delete <span id="pTest"></span>'s account</h6>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>This action cannot be undone. are you you want to continue?</p>
                    <div class="float-right">
                        <?php  ?>
                        <form class="form" id="delete_TMAccount" action="" method="Post">
                            <input type="hidden" class="form-control" name="terminalmasterid" value="">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="delete_TMAccount" form="delete_TMAccount" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>

</html>

<script>
    $('#editTMAccountModal').on('show.bs.modal', function(e) {
        var userID = $(e.relatedTarget).data('userid');
        var id = userID;
        var fn = $('#fullname' + id).text();
        var saddress = $('#saddress' + id).text();
        var city = $('#city' + id).text();
        var province = $('#province' + id).text();
        var num = $('#connumber' + id).text();
        var email = $('#email' + id).text();
        $(e.currentTarget).find('input[name="userID"]').val(userID);
        $(e.currentTarget).find('input[name="fullname"]').val(fn);
        $(e.currentTarget).find('input[name="saddress"]').val(saddress);
        $(e.currentTarget).find('input[name="city"]').val(city);
        $(e.currentTarget).find('input[name="province"]').val(province);
        $(e.currentTarget).find('input[name="cnumber"]').val(num);
        $(e.currentTarget).find('input[name="email"]').val(email);
    });

    $('#editTMAccountterminalModal').on('show.bs.modal', function(e) {
        var userID = $(e.relatedTarget).data('userid');
        var id = userID;
        $(e.currentTarget).find('input[name="userID"]').val(userID);
    });

    $('#deleteTMAccountModal').on('show.bs.modal', function(e) {
        var userID = $(e.relatedTarget).data('userid');
        var id = userID;
        var fn = $('#fullname' + id).text();
        $(e.currentTarget).find('input[name="terminalmasterid"]').val(userID);
        $('#pTest').text(fn);
    });

    $(document).ready(function() {
        $('#terminalMasterTable').DataTable({
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
                null,
                null,
                {
                    orderable: false
                }
            ],
        });
    });
</script>