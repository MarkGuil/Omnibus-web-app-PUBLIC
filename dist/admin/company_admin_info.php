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

$scode = "SECRET_CODE_FOR_COMPANY_ID_12345678910";
$decrypt_id = base64_decode($_GET['i']);
$decrypted_id = preg_replace(sprintf('/%s/', $scode), '', $decrypt_id);
$result = $conn->query("SELECT * FROM user_partner_admin WHERE id = '$decrypted_id'");
$users = $result->fetch_all(MYSQLI_ASSOC);
$result2 = $conn->query("SELECT * FROM company_files WHERE companyID = '$decrypted_id'");
$compfiles = $result2->fetch_all(MYSQLI_ASSOC);

$image = 0;

if (isset($_POST['viewFilePDF'])) {
    $file = $_POST['viewFilePDF'];
    header("content-type: application/pdf");
    readfile("../companyAdmin/uploads/" . $file);
}
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
                    <a class="nav-link" href="homeAdmin.php">Customers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="homeAdmin_CompanyAdmin.php">Company Admins</a>
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
    <div class="container container-medium mt-5 bg-white px-5 py-4 rounded shadow">
        <h5 class="mt-3"><b>Company Details</b></h5>
        <div class="container my-4">
            <?php foreach ($users as $user) : ?>
                <div class="mx-1 row">
                    <div class="col-sm-12 col-md-6">
                        <span class="text-muted h6"> Company Name: </span>
                        <span><?php echo $user['companyName'] ?></span>
                    </div>
                </div>
                <div class="row mx-1 mt-2">
                    <div class="col-sm-12 col-md-6">
                        <span class="text-muted h6"> Company Address: </span>
                        <span><?php echo $user['companyAddress'] ?></span>
                    </div>
                </div>
                <div class="row mx-1 mt-2">
                    <div class="col-sm-12 col-md-6">
                        <span class="text-muted h6"> Company Contact Number: </span>
                        <span><?php echo $user['contactNumber'] ?></span>
                    </div>
                </div>
                <div class="row mx-1 mt-2">
                    <div class="col-sm-12 col-md-6">
                        <span class="text-muted h6"> Business email: </span>
                        <span><?php echo $user['email'] ?></span>
                    </div>
                </div>
                <hr>
                <div class="row mx-1 mt-2">
                    <div class="col-sm-12 col-md-6">
                        <span class="text-muted h6"> Contact Person: </span>
                        <span><?php echo $user['fullname'] ?></span>
                    </div>

                </div>
                <div class="row mx-1 mt-2">
                    <div class="col-sm-12 col-md-6">
                        <span class="text-muted h6"> Position: </span>
                        <span><?php echo $user['position'] ?></span>
                    </div>
                </div>
                <hr>
                <div class="row mx-1 mt-2">
                    <div class="col-sm-12 col-md-12">
                        <form action="" method="POST">
                            <input type="hidden" name="compid" value="<?php echo $decrypted_id ?>">
                            <input type="hidden" name="email" value="<?php echo $user['email'] ?>">
                            <input type="hidden" name="name" value="<?php echo $user['fullname'] ?>">
                            <!-- <button class="btn btn-warning py-1" name="viewFile">View</button> -->
                            <?php
                            $fileC = $user["fileCount"];
                            $fileV = $user["file_verified"];
                            if ($fileC != 0) {
                                echo "<button class='btn btn-success py-1 float-right shadow mr-4' name='verifyallFile'>Verify all documents</button>";
                            }
                            ?>
                        </form>
                    </div>
                </div>
                <div class="row mx-1 mt-2">
                    <div class="col-sm-12 col-md-6 py-1">
                        <span class="text-muted h6"> Documents: </span>
                        <?php foreach ($compfiles as $compfile) : ?>
                            <li class="text-muted mt-3"><?php echo $compfile['file_Name'] ?></li>
                        <?php endforeach; ?>
                    </div>
                    <div class="col-sm-12 col-md-3 py-1">
                        <span class="text-muted h6 mb-2"> Document Status: </span><br>
                        <span class="mt-2">
                            <?php
                            foreach ($compfiles as $compfile) {
                                $fileV = $compfile["verified"];
                                if ($fileV == 0) {
                                    echo "<p class='text-muted mt-3'>Pending</p>";
                                } elseif ($fileV != 0) {
                                    echo "<p class='text-muted mt-3'>Verified</p>";
                                }
                            }
                            ?>
                        </span>
                    </div>
                    <div class="col-sm-12 col-md-3 py-1">
                        <span class="text-muted h6"> Actions: </span>
                        <div class="row">
                            <?php foreach ($compfiles as $compfile) : ?>
                                <?php
                                $file = $compfile['file_Name'];
                                $fileV = $compfile["verified"];
                                $extention = pathinfo($file, PATHINFO_EXTENSION);
                                if (in_array($extention, ['jpeg', 'jpg', 'png'])) { ?>
                                    <div class="col-4 mt-2">
                                        <a href="#imagemodal" class="btn btn-outline-primary shadow-sm py-1" data-id="../companyAdmin/uploads/<?php echo $compfile['file_Name']; ?>" data-bs-toggle="modal" data-bs-target="#imagemodal">View</a>
                                    </div>
                                <?php } else if (in_array($extention, ['pdf'])) { ?>
                                    <div class="col-4 mt-2">
                                        <form action="" method="POST">
                                            <button class="btn btn-outline-primary shadow-sm py-1" name="viewFilePDF" value="<?php echo $compfile['file_Name']; ?>">View</button>
                                        </form>
                                    </div>
                                <?php
                                }
                                if ($fileV == 0) { ?>
                                    <div class="col-8 mt-2">
                                        <form action="" method="POST">
                                            <input type="hidden" name="compid" value="<?php echo $decrypted_id ?>">
                                            <input type="hidden" name="fileid" value="<?php echo $compfile['id'] ?>">
                                            <button class='btn btn-outline-success shadow-sm py-1' name='verifyFile'>Verify</button>
                                        </form>
                                    </div><br>
                                <?php } elseif ($fileV != 0) {
                                    echo "<div class='col-8'></div><br>";
                                } ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>


            <?php endforeach ?>
        </div>
    </div>


    <footer class="text-center text-lg-start">
        <div class="text-center text-light p-3" style="background-color: #212529">
            © 2021 Copyright:
            <a class="text-light" href="">Omnibus</a>
        </div>
    </footer>

    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" data-dismiss="modal">
            <img class="modal-content" id="img01" src="" class="imagepreview" style="width: 100%;">
        </div>
    </div>


</body>

</html>

<script>
    $('#imagemodal').on('show.bs.modal', function(e) {
        var bookId = $(e.relatedTarget).data('id');
        document.getElementById("img01").src = bookId;
    });
</script>