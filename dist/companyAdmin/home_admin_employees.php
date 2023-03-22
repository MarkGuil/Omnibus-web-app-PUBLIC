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
                                <h5 class="text-secondary">Manage Drivers & Conductors</h5>
                            </div>
                            <div class="col-sm-6 ">
                                <a href="#" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#mymodal_adddriver"><i class="material-icons">&#xE145;</i><span>Add New Employee</span></a>
                            </div>
                        </div>
                    </div>
                    <table id="driverstable" class="table triptable table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-secondary">Employee ID</th>
                                <th class="text-secondary">Fullname</th>
                                <th class="text-secondary">Role</th>
                                <th class="text-secondary">Address</th>
                                <th class="text-secondary">Contact Number</th>
                                <th class="text-secondary">Email</th>
                                <th class="text-secondary">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($emps as $emp) : ?>
                                <tr>
                                    <td><?php echo $emp["id"] ?></td>
                                    <td id="fullname<?php echo $emp['id']; ?>"><?php echo $emp["fullname"] ?></td>
                                    <td id="role<?php echo $emp['id']; ?>"><?php echo $emp["role"] ?></td>
                                    <td id="address<?php echo $emp['id']; ?>"><?php echo $emp["address"] ?></td>
                                    <td id="connumber<?php echo $emp['id']; ?>"><?php echo $emp["contactNumber"] ?></td>
                                    <td id="email<?php echo $emp['id']; ?>"><?php echo $emp["email"] ?></td>
                                    <td>
                                        <div class="dropdown show">
                                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" data-flip="false" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Action
                                            </a>

                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                <a href="home_admin_buses.php#mymodal_editdriver" class="dropdown-item" data-bs-toggle="modal" data-userid="<?php echo $emp['id']; ?>">Edit details</a>
                                                <a href="home_admin_buses.php#mymodal_editrole" class="dropdown-item" data-bs-toggle="modal" data-userid="<?php echo $emp['id']; ?>">Change role</a>
                                                <div class="dropdown-divider"></div>
                                                <a href="home_admin_buses.php#mymodal_deleteEmp" class="dropdown-item text-danger" data-bs-toggle="modal" data-userid="<?php echo $emp['id']; ?>">Delete Employee</a>
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

    <div class="modal" id="mymodal_adddriver">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Add Employee</span>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="frm" id="add_Employee" action="" method="Post" oninput='password2.setCustomValidity(password2.value != password1.value ? "Passwords do not match." : "")'>
                        <div class="form-group md-6">
                            <label class="h6 text-muted">Fullname</label>
                            <input type="text" class="form-control" name="fullname" placeholder="" required>
                        </div>
                        <div class="form-group md-6 mt-3">
                            <label class="h6 text-muted">Address</label>
                            <input type="text" class="form-control" name="address" placeholder="" required>
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
                                <input type="password" class="form-control" name="password1" placeholder="" autocomplete="on" required>
                            </div>
                            <div class="col form-group md-6">
                                <label for="inputPassword4" class="h6 text-muted">Re-type Password</label>
                                <input type="password" class="form-control" name="password2" placeholder="" autocomplete="on" required>
                            </div>
                        </div>
                        <div class="form-group md-6 mt-3">
                            <label class="h6 text-muted">Role</label>
                            <select class="form-select" name="optionRole" aria-label="Default select example">
                                <option value="" class="text-muted" selected>Open this select menu</option>
                                <option value="Driver">Driver</option>
                                <option value="Conductor">Conductor</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success ml-3" type="submit" name="add_Employee" form="add_Employee">Add</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="mymodal_editdriver">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Edit Employee details</span>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="frm" id="edit_Employee" action="" method="Post">
                        <input type="hidden" class="form-control" name="employeeID" value="">
                        <div class="form-group md-6">
                            <label class="h6 text-muted">Fullname</label>
                            <input type="text" class="form-control" name="fullname" placeholder="" required>
                        </div>
                        <div class="form-group md-6">
                            <label class="h6 text-muted">Address</label>
                            <input type="text" class="form-control" name="address" placeholder="" required>
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
                        <div class="form-group md-6">
                            <label class="h6 text-muted">Role</label>
                            <input type="text" class="form-control" name="role" placeholder="" readonly>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success ml-3" type="submit" name="edit_Employee" form="edit_Employee">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="mymodal_editrole">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Change role for <span id="nameee"></span></span>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="frm" id="edit_Employee_role" action="" method="Post">
                        <input type="hidden" class="form-control" name="employeeID" value="">
                        <div class="form-group md-6">
                            <label class="h6 text-muted">Role</label>
                            <select class="something form-select" name="optionRolee" id="optionRolee" aria-label="Default select example">
                                <option selected>Open this select menu</option>
                                <option value="Driver">Driver</option>
                                <option value="Conductor">Conductor</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success ml-3" type="submit" name="edit_Employee_role" form="edit_Employee_role">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="mymodal_deleteEmp">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h6 class="text-light pt-2">Remove Employee</h6>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>This action cannot be undone. Do you want to continue?</p>
                    <div class="float-right">
                        <p id="busidtext" style="display:none;"></p>
                        <?php  ?>
                        <form class="form" id="delete_employee" action="" method="Post">
                            <input type="hidden" class="form-control" name="employeeID" value="">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="delete_employee" form="delete_employee" class="btn btn-danger">Delete</button>
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


    $('#mymodal_editdriver').on('show.bs.modal', function(e) {
        var userID = $(e.relatedTarget).data('userid');
        var id = userID;
        var fn = $('#fullname' + id).text();
        var role = $('#role' + id).text();
        var address = $('#address' + id).text();
        var num = $('#connumber' + id).text();
        var email = $('#email' + id).text();
        $(e.currentTarget).find('input[name="employeeID"]').val(userID);
        $(e.currentTarget).find('input[name="fullname"]').val(fn);
        $(e.currentTarget).find('input[name="address"]').val(address);
        $(e.currentTarget).find('input[name="cnumber"]').val(num);
        $(e.currentTarget).find('input[name="email"]').val(email);
        $(e.currentTarget).find('input[name="role"]').val(role);
    });

    $('#mymodal_editrole').on('show.bs.modal', function(e) {
        var userID = $(e.relatedTarget).data('userid');
        var id = userID;
        var fn = $('#fullname' + id).text();
        var role = $('#role' + id).text();
        $(e.currentTarget).find('input[name="employeeID"]').val(userID);
        $(e.currentTarget).find('input[name="role"]').val(role);
        var opt = document.getElementById('optionRolee').options[0];
        opt.value = role;
        opt.text = role;

        $("select[name='optionRolee'] > option").each(function() {
            if (code[this.text]) {
                $(this).remove();
            } else {
                code[this.text] = this.value;
            }
        });

        document.getElementById("nameee").innerText = fn;
    });


    $("#mymodal_editrole").on('hide.bs.modal', function() {
        location.reload(true);
    });

    $('#mymodal_deleteEmp').on('show.bs.modal', function(e) {
        var userID = $(e.relatedTarget).data('userid');
        var id = userID;
        var fn = $('#fullname' + id).text();
        $(e.currentTarget).find('input[name="employeeID"]').val(userID);

        document.getElementById("nameee").innerText = fn;
    });



    $(document).ready(function() {
        $('#driverstable').DataTable({
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
                {
                    orderable: false
                }
            ],
        });
    });
</script>