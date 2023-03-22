<?php
include('../database/db.php');
include('server.php'); ?>
<?php
if (session_id() == '') {
    session_start();
}

if (!isset($_SESSION['name'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: loginScreen.php');
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['name']);
    header("location: loginScreen.php");
}


$result = $conn->query("SELECT * FROM user_pending");
$users = $result->fetch_all(MYSQLI_ASSOC);


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>OmnibusAdmin</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!--  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script> -->

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css" />

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>

    <link rel="stylesheet" type="text/css" href="adminCSS/style.css">
    <style>
        .dataTables_length {
            text-align: right !important;

        }

        .dataTables_filter {
            text-align: left !important;

        }

        .dataTables_filter input {
            width: 100% !important;
            /*margin-right: 100px;*/

        }

        .dataTables_filter label {
            color: #7E7E7E;
        }

        .dataTables_length label {
            color: #7E7E7E;
        }

        .dataTables_info {
            padding-top: 5px !important;
            font-size: 13px;
            color: #7E7E7E;
        }

        .sorting_asc {
            color: #384C65 !important;
            background-color: #DDDDE6 !important;
        }

        .sorting_desc {
            color: #384C65 !important;
            background-color: #DDDDE6 !important;
        }
    </style>


</head>

<body>
    <nav class="navbar navbar-dark navbar-expand-lg bg-dark shadow-lg py-3 px-5 mb-5">
        <a class="navbar-brand mr-4" href="#"><b>Omnibus Admin</b></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse ml-2 mt-1" id="navbarNav">
            <ul class="navbar-nav ">
                <li class="nav-item">
                    <a class="nav-link" href="homeAdmin.php">Customer</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="homeAdmin_CompanyAdmin.php">Company Admins</a>
                </li>
                <li class="nav-item">
                    <div class="dropdown">
                        <button class="btn btn-white dropdown-toggle text-muted" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Partner
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                            <a class="dropdown-item" href="homeAdminPartner.php">Registered List</a>
                            <a class="dropdown-item active" href="homeAdminPending.php">Pending List</a>
                        </div>
                    </div>
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
                <?php if (isset($_SESSION['name'])) : ?>
                    <span class="text-light">Welcome </span><strong class="text-light"><?php echo $_SESSION['name']; ?></strong>
                <?php endif ?>
            </span>
            <?php if (isset($_SESSION['name'])) : ?>
                <a class="btn btn-dark ml-3" href="homeAdmin.php?logout='1'">Logout</a>
            <?php endif ?>
        </div>
    </nav>
    <?php if (isset($_SESSION['fullname'])) : ?>
        Welcome <strong><?php echo $_SESSION['fullname']; ?></strong>
    <?php endif ?>
    <?php if (isset($_SESSION['success'])) : ?>
        <div class="error success">
            <h3>
                <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
            </h3>
        </div>
    <?php endif ?>
    <?php include('errors.php'); ?>
    <div class="container container-large mt-5 bg-white px-5 py-3 rounded shadow">
        <h5 class="mt-2 mb-3"><b>Pending Partner List</b></h5>
        <div class="container border border-muted px-4 mb-3">
            <div class="container-xl">
                <div class="table-responsive">
                    <div class="table-wrapper">
                        <div class="table-title">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5 class="text-secondary">Manage Partners</h5>
                                </div>
                                <!--  <div class="col-sm-6 ">
                                    <a href="#" class="btn btn-success col-4" data-bs-toggle="modal" data-bs-target="#mymodal"><i class="material-icons">&#xE145;</i><span>Add New Partner</span></a>
                                </div> -->
                            </div>
                        </div>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-secondary">ID</th>
                                    <!-- <th class="text-secondary">Partner ID</th> -->
                                    <th class="text-secondary">Company Name</th>
                                    <th class="text-secondary">Fullname</th>
                                    <th class="text-secondary">Contact Number</th>
                                    <th class="text-secondary">Email</th>
                                    <th class="text-secondary">Province</th>
                                    <th class="text-secondary">City</th>
                                    <th class="text-secondary">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user) : ?>
                                    <tr>
                                        <td><?php echo $user["id"] ?></td>
                                        <!-- <td id="companyID<?php echo $user['id']; ?>"><?php echo $user["companyID"] ?></td> -->
                                        <td id="companyName<?php echo $user['id']; ?>"><?php echo $user["companyName"] ?></td>
                                        <td id="fullname<?php echo $user['id']; ?>"><?php echo $user["fullname"] ?></td>
                                        <td id="connumber<?php echo $user['id']; ?>"><?php echo $user["connumber"] ?></td>
                                        <td id="email<?php echo $user['id']; ?>"><?php echo $user["email"] ?></td>
                                        <td id="address1<?php echo $user['id']; ?>"><?php echo $user["address"] ?></td>
                                        <td id="address2<?php echo $user['id']; ?>"><?php echo $user["cityAddress"] ?></td>
                                        <td>
                                            <a href="homeAdminPartner.php#mymodaladd" class="edit" data-bs-toggle="modal" data-userid="<?php echo $user['id']; ?>"><i class="material-icons text-success" data-toggle="tooltip" title="Edit">&#xe145;</i></a>
                                            <a href="homeAdminPartner.php#mymodaldelete" class="delete" data-bs-toggle="modal" data-userid="<?php echo $user['id']; ?>"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


    <footer class="text-center text-lg-start">
        <div class="text-center text-light p-3" style="background-color: #212529">
            © 2021 Copyright:
            <a class="text-light" href="">Omnibus</a>
        </div>
    </footer>

    <div class="modal" id="mymodal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Add Partner</span>
                    <div class="close text-white" data-bs-dismiss="modal">&times;</div>
                </div>
                <div class="modal-body">
                    <form class="frm" id="add_form" action="" method="Post">
                        <!-- <div class="cont-popup cont col-sm-12 col-sm-offset-12"> -->
                        <div class="form-group md-6">
                            <label class="h6 text-muted">Company Name</label>
                            <input type="text" class="form-control" name="compName" placeholder="Company Name" pattern="[a-zA-Z0-9\s]+">
                        </div>
                        <!-- <input type="hidden" class="form-control" name="usertype" value="4"> -->
                        <div class="form-group md-6">
                            <label class="h6 text-muted">Location</label>
                            <input type="text" class="form-control" name="location" placeholder="Address">
                        </div>
                        <div class="row">
                            <div class="col form-group md-6">
                                <label class="h6 text-muted">Contact Person/ Fullame</label>
                                <input type="text" class="form-control" name="fullname" placeholder="Fullname">

                            </div>
                            <div class="col form-group md-6">
                                <label class="h6 text-muted">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Password">
                            </div>
                        </div>
                        <hr>
                        <span class="h6">Contact Information</span>
                        <div class="row mt-3 ">
                            <div class="col form-group md-6">
                                <label class="h6 text-muted">Contact Number</label>
                                <input type="text" class="form-control" name="cnumber" placeholder="Contact Number" maxlength="11" size="11" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                            </div>
                            <div class="col form-group md-6">
                                <label class="h6 text-muted">Email Address</label>
                                <input type="email" class="form-control" name="email" placeholder="Email">
                            </div>
                        </div>
                        <!-- </div> -->
                        <!-- <span>Your Class code: </span> <span id="code"><?= $classCode; ?></span> -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success ml-3" type="submit" name="addpartner" form="add_form">Add New Partner</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="mymodaledit">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Confirm Partner Information</span>
                    <div class="close text-white" data-bs-dismiss="modal">&times;</div>
                </div>
                <div class="modal-body">
                    <form class="form" id="edit_form" action="" method="Post">
                        <div class="cont-popup cont col-sm-12 col-sm-offset-12">
                            <div class="form-group md-6">
                                <label class="h6 text-muted">Company Name</label>
                                <input type="text" class="form-control" name="compName" placeholder="Company Name" pattern="[a-zA-Z0-9\s]+" required>
                            </div>
                            <input type="hidden" class="form-control" name="user_id" id="eid">
                            <div class="form-group md-6">
                                <label class="h6 text-muted">Province</label>
                                <input type="text" class="form-control" name="location1" placeholder="Address" required>
                            </div>
                            <div class="form-group md-6">
                                <label class="h6 text-muted">City</label>
                                <input type="text" class="form-control" name="location2" placeholder="Address" required>
                            </div>
                            <!-- <div class="row"> -->
                            <div class="form-group md-6">
                                <label class="h6 text-muted">Contact Person/ Fullame</label>
                                <input type="text" class="form-control" name="fullname" placeholder="Fullname" required>
                            </div>
                            <!-- </div>   -->
                            <hr>
                            <span class="h6">Contact Information</span>
                            <div class="row mt-3 ">
                                <div class="col form-group md-6">
                                    <label class="h6 text-muted">Contact Number</label>
                                    <input type="text" class="form-control" name="cnumber" placeholder="Contact Number" maxlength="11" size="11" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                                </div>
                                <div class="col form-group md-6">
                                    <label class="h6 text-muted">Email Address</label>
                                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                                </div>
                            </div>
                        </div>
                        <!-- <span>Your Class code: </span> <span id="code"><?= $classCode; ?></span> -->
                        <button class="btn btn-success ml-3" type="submit" name="pen" form="edit_form">Save Changes</button>
                    </form>
                </div>
                <!-- <div class="modal-footer"> -->

                <!-- </div> -->
            </div>
        </div>
    </div>

    <div class="modal" id="mymodaladd">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Edit Partner Information</span>
                    <div class="close text-white" data-bs-dismiss="modal">&times;</div>
                </div>
                <div class="modal-body">
                    <p>This action cannot be undone. are you you want to continue?</p>
                    <div class="float-right">
                        <form class="form" id="add_form_pending" action="" method="Post">
                            <input type="hidden" class="form-control" name="user_idss" value="">
                            <input type="hidden" class="form-control" name="usertype" value="4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="addpendingpartnerss" form="add_form_pending" class="btn btn-success">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="mymodaldelete">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h6 class="text-light pt-2">Are you sure you want to delete <span id="pTest">this</span>'s account partner</h6>
                    <div class="close text-white" data-bs-dismiss="modal">&times;</div>
                </div>
                <div class="modal-body">
                    <p>This action cannot be undone. are you you want to continue?</p>
                    <div class="float-right">
                        <form class="form" id="delete_form" action="" method="Post">
                            <input type="hidden" class="form-control" name="user_idss" value="">
                            <input type="hidden" class="form-control" name="usertypess" value="4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="deletepartnerpending" form="delete_form" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>


<script type="text/javascript">
    var myModal = document.getElementById('mymodal')
    var myInput = document.getElementById('myInput')

    myModal.addEventListener('shown.bs.modal', function() {
        myInput.focus()
    })

    function add_customer() {
        document.getElementById("add_form").submit();
    }

    $('#mymodaledit').on('show.bs.modal', function(e) {
        var userid = $(e.relatedTarget).data('userid');
        var id = userid;
        var fn = $('#companyName' + id).text();
        var address = $('#fullname' + id).text();
        var num = $('#connumber' + id).text();
        var email = $('#email' + id).text();
        var prov = $('#address1' + id).text();
        var city = $('#address2' + id).text();
        $(e.currentTarget).find('input[name="user_id"]').val(userid);
        $(e.currentTarget).find('input[name="compName"]').val(fn);
        $(e.currentTarget).find('input[name="fullname"]').val(address);
        $(e.currentTarget).find('input[name="location1"]').val(prov);
        $(e.currentTarget).find('input[name="location2"]').val(city);
        $(e.currentTarget).find('input[name="cnumber"]').val(num);
        $(e.currentTarget).find('input[name="email"]').val(email);

        // $.get('homeAdmin.php?id='+id);
    });

    $('#mymodaladd').on('show.bs.modal', function(e) {
        var userid = $(e.relatedTarget).data('userid');
        var id = userid;
        // var fn=$('#companyName'+id).text();
        $(e.currentTarget).find('input[name="user_idss"]').val(userid);
        // $('#pTest').text(fn);
    });

    $('#mymodaldelete').on('show.bs.modal', function(e) {
        var userid = $(e.relatedTarget).data('userid');
        var id = userid;
        var fn = $('#companyName' + id).text();
        $(e.currentTarget).find('input[name="user_idss"]').val(userid);
        $('#pTest').text(fn);
    });

    $(document).ready(function() {
        $('table').DataTable({
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
                {
                    orderable: false
                }
            ],
        });
    });


    // if ( window.history.replaceState ) {
    //         window.history.replaceState( null, null, window.location.href );
    //     }
</script>