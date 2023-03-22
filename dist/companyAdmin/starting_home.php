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
if ($name == 'Demo User') {
    header("location: home_admin_details.php");
} else {
    $result = $conn->query("SELECT fileCount,file_verified FROM user_partner_admin WHERE id = '$id'");
    $files = $result->fetch_all(MYSQLI_ASSOC);
    foreach ($files as $file) {
        if ($file['file_verified'] == "1") {
            header("location: home_admin_details.php");
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>OmnibusAdmin</title>
    <?php include 'extentions/bootstrap.php' ?>

    <style>
        body {
            background-color: #F4F4F4;
        }

        @media (min-width: 768px) {
            .container-small {
                width: 500px;
            }

            .container-large {
                width: 970px;
            }
        }

        @media (min-width: 992px) {
            .container-small {
                width: 500px;
            }

            .container-large {
                width: 1170px;
            }
        }

        @media (min-width: 1200px) {
            .container-small {
                width: 650px;
            }

            .container-large {
                width: 1500px;
            }
        }

        .container-small,
        .container-large {
            max-width: 100%;
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-dark navbar-expand-lg navbar-light bg-dark shadow-lg py-3 px-5 mb-5">
        <a class="navbar-brand mr-4" href=""><b>Omnibus</b><span class="text-primary"> Company Admin</span></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse ml-3 mt-1" id="navbarNav">

        </div>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
            <?php if (isset($_SESSION['compadminName'])) : ?>
                <a class="btn btn-secondary ml-3" href="starting_home.php?logout='1'">Logout</a>
            <?php endif ?>
        </div>
    </nav>
    <?php
    foreach ($files as $file) {
        if ($file['fileCount'] == 0) {
    ?>
            <div class="container mt-5 mb-5 d-flex justify-content-center">
                <div class="container container-small p-0 shadow bg-light">
                    <div class="container text-center bg-dark mx-0 px-3 py-3">
                        <h5 class="text-light">Documents</h5>
                    </div>
                    <form class="mx-3 mb-3 mt-3" action="#" method="POST" enctype="multipart/form-data">
                        <?php include('errors.php'); ?>
                        <div class="form-group md-6">
                            <label for="verify" class="h6 text-muted my-2">Please upload any of the following documents </label><br> <small class="text-muted">(* Allows images files and pdf)</small><br><br>
                            <b>
                                <ul class="text-info">
                                    <li>Business Permit</li>
                                    <li>Bureau of internal Revenue (BIR)</li>
                                    <li>Mayors Permit</li>
                                    <li>Tax identification Number (TIN)</li>
                                    <li>Business Name Registration (DTI)</li>
                                </ul>
                            </b><br>
                            <div class="form-group md-6 mt-2" id="dynamic_field">
                                <div class="row pb-2">
                                    <div class="col-lg">
                                        <input type="file" name="files[]" class="form-control name_list">
                                    </div>
                                    <div class="col-lg-3">
                                        <button type="button" name="add" id="add" class="btn btn-primary">Add more file</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input class="btn btn-secondary mt-2 px-4" type="submit" name="upload_files" value="Upload">
                    </form>
                </div>
            </div>
        <?php } else { ?>
            <div class="container mt-5 mb-5 d-flex justify-content-center">
                <div class="container container-small p-5 shadow rounded bg-light">
                    <div class="text-center text dark">
                        <i class="fas fa-check-circle display-1 mb-2 text-success"></i>
                        <h1 class="text-secondary" style="font-weight:800">Thank You!</h1>
                        <h5 class="px-5 text-secondary">Your file has been sent. We will be contacting you shortly!</h5>
                    </div>
                </div>
            </div>
    <?php }
    } ?>

    <footer class="text-center text-lg-start fixed-bottom">
        <div class="text-center text-light p-3" style="background-color: #212529">
            © 2021 Copyright:
            <a class="text-light" href="">Omnibus</a>
        </div>
    </footer>

    <!-- <form class="frm" id="add_guide" action="" method="Post">
                            <div class="form-group md-6" id="dynamic_field">
                                <div class="row">
                                    <div class="col-lg">
                                    <input type="file" name="files[]" class="form-control name_list">
                                    </div>
                                    <div class="col-lg-2">
                                    <button type="button" name="add" id="add" class="btn btn-primary col-2">Add More</button>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="compid" value="<?php echo $id; ?>">
                            <p class="text-danger" id="oops"></p>
                            <button class="btn btn-primary ml-3" type="submit" name="add_guide" form="add_guide">Add New Guideline/s</button>
                    </form> -->


</body>

</html>

<script>
    $(document).ready(function() {
        var i = 1;
        var max = 10;
        $('#add').click(function() {
            if (i < max) {
                i++;
                $('#dynamic_field').append('<hr><div class="row pt-2" id="row' + i + '"><div class="col-10"><input type="file" name="files[]" class="form-control name_list"></div><div class="col-1"><button type="button" name="remove" id="' + i + '" class="btn btn-link text-danger btn_remove"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></button></div></div>');
            } else {
                document.getElementById('oops').innerText = "*You Can only send 10 or less files";
            }
        });
        $(document).on('click', '.btn_remove', function() {
            button_id = $(this).attr("id");
            $('#row' + button_id + '').remove();
            max++;
        });
    });
</script>