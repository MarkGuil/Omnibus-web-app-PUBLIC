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
if (isset($_POST['viewFilePDF'])) {
    $file = $_POST['viewFilePDF'];
    header("content-type: application/pdf");
    readfile("uploads/" . $file);
}
//   if($_SESSION['compadminVerify'] == NULL){

//   }
$x = 1;
$default = 1;
$name = $_SESSION['compadminName'];
$email = $_SESSION['compadminemail'];
$id = $_SESSION['compadminID'];
//   $payments = "Please enter your PayPal email to accept payments";
$result = $conn->query("SELECT * FROM user_partner_admin WHERE id = '$id'");
$details = $result->fetch_all(MYSQLI_ASSOC);
$result2 = $conn->query("SELECT * FROM guidelines WHERE companyID = '$id'");
$guides = $result2->fetch_all(MYSQLI_ASSOC);
$result3 = $conn->query("SELECT * FROM company_files WHERE companyID = '$id'");
$compfiles = $result3->fetch_all(MYSQLI_ASSOC);
$result4 = $conn->query("SELECT * FROM payment_bussiness_info WHERE companyID = '$id'");
// if ($result4) {
$payments = $result4->fetch_all(MYSQLI_ASSOC);
// }
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

            <div class="container container-medium py-4 px-5 bg-white rounded shadow">
                <div class="row">
                    <div class="col-md-4 col-lg-4 col-xl-3">
                        <h6 class="pt-4 pb-3"><b>Your Account</b></h6>
                        <div class="px-1">
                            <form action="" method="get">
                                <button class="btn py-1 text-primary" name="adetailView" value="">
                                    <h6>Account details</h6>
                                </button><br>
                                <button class="btn py-1 text-primary mt-2" name="documentsView" value="">
                                    <h6>Documents</h6>
                                </button><br>
                                <button class="btn py-1 text-primary mt-2" name="paymentView">
                                    <h6>Payments</h6>
                                </button><br>
                                <button class="btn py-1 text-primary mt-2" name="ccomdlView">
                                    <h6>Update Company Details</h6>
                                </button><br>
                                <button class="btn py-1 text-primary mt-2" name="cpassView">
                                    <h6>Change Password</h6>
                                </button><br>
                            </form>
                        </div>
                        <h6 class="pt-4 pb-3"><b>Organization</b></h6>
                        <div class="px-1">
                            <form action="" method="get">
                                <!-- <button class="btn py-1 text-primary" name="sadminsView"><h6>Sub-admin</h6></button><br> -->
                                <button class="btn py-1 text-primary mt-2" name="guidelinesView">
                                    <h6>Guidelines</h6>
                                </button><br>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-8 col-lg-8 col-xl-9 pl-5 pr-4">
                        <?php
                        if (isset($_SESSION['compadminName']) && $_SESSION['compadminName'] == "Demo User") {
                            if (isset($_GET['adetailView']) || !isset($_GET['adetailView']) && !isset($_GET['documentsView']) && !isset($_GET['paymentView']) && !isset($_GET['ccomdlView']) && !isset($_GET['cpassView']) && !isset($_GET['guidelinesView'])) {
                                include 'extentions/company_details_view.php';
                            }
                            if (isset($_GET['documentsView'])) {
                                include 'extentions/company_documents_view.php';
                            }
                            if (isset($_GET['paymentView'])) {
                                include 'extentions/payments_view.php';
                            }
                            if (isset($_GET['ccomdlView'])) {
                                include 'extentions/update_company_details_view.php';
                            }
                            if (isset($_GET['cpassView'])) {
                                include 'extentions/change_password_view.php';
                            }
                            if (isset($_GET['guidelinesView'])) {
                                include 'extentions/guidelines_view.php';
                            }
                        } else {
                            foreach ($details as $detail) {
                        ?>

                                <?php
                                if (isset($_GET['adetailView']) || !isset($_GET['adetailView']) && !isset($_GET['documentsView']) && !isset($_GET['paymentView']) && !isset($_GET['ccomdlView']) && !isset($_GET['cpassView']) && !isset($_GET['guidelinesView'])) {
                                    include 'extentions/company_details_view.php';
                                }
                                if (isset($_GET['documentsView'])) {
                                    include 'extentions/company_documents_view.php';
                                }
                                if (isset($_GET['paymentView'])) {
                                    include 'extentions/payments_view.php';
                                }
                                if (isset($_GET['ccomdlView'])) {
                                    include 'extentions/update_company_details_view.php';
                                }
                                if (isset($_GET['cpassView'])) {
                                    include 'extentions/change_password_view.php';
                                }
                                if (isset($_GET['guidelinesView'])) {
                                    include 'extentions/guidelines_view.php';
                                }
                                ?>
                        <?php }
                        } ?>
                    </div>
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

    <div class="modal fade" id="editContactPersonModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Edit Contact Person/ Account Manager details</span>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="ml-3">Click save when your done.</p>
                    <form class="form" id="edit_cp" action="" method="Post">
                        <div class="cont-popup cont col-sm-12 col-sm-offset-12 mb-3">
                            <?php foreach ($details as $detail) : ?>
                                <div class="col form-group md-6">
                                    <label class="h6 text-muted">Fullname</label>
                                    <input type="text" class="form-control" name="fullname" value="<?php echo $detail['fullname'] ?>" required>
                                </div>
                                <div class="col form-group md-6 mt-3">
                                    <label class="h6 text-muted">Position</label>
                                    <input type="text" class="form-control" name="position" value="<?php echo $detail['position'] ?>" required>
                                </div>
                            <?php endforeach ?>
                        </div>
                        <button class="btn btn-success mt-2 me-3" type="submit" name="edit_cp" form="edit_cp">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="verifyEmailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Verify Email</span>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="ml-3 text-muted">Please enter the verification code sent to your email.</p>
                    <form class="form" id="verify_new_email" action="" method="Post">
                        <div class="cont-popup cont col-sm-12 col-sm-offset-12 mb-3">
                            <?php foreach ($details as $detail) : ?>
                                <div class="col form-group md-6">
                                    <label class="h6 text-muted">Verification Code</label>
                                    <input class="form-control" type="text" name="verification_code" placeholder="" required />
                                </div>
                            <?php endforeach ?>
                        </div>
                        <button class="btn btn-success mt-2 ml-3" type="submit" name="verify_new_email" form="verify_new_email">Verify</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="guidelines_modal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Add Guidelines</span>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="frm" id="add_guide" action="" method="Post">
                        <div class="form-group md-6" id="dynamic_field">
                            <div class="row">
                                <div class="col-lg">
                                    <textarea name="guide[]" placeholder="Enter Guideline 1" class="form-control name_list" required /></textarea>
                                </div>
                                <div class="col-lg-2">
                                    <button type="button" name="add" id="add" class="btn btn-primary coll-2">Add More</button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="compid" value="<?php echo $id; ?>">
                        <input type="hidden" id="max" value="1">
                        <p class="text-danger" id="oops"></p>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success ml-3" type="submit" name="add_guide" form="add_guide">Add New Guideline/s</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="mymodaleditguide">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Edit Selected Guideline</span>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Click save changes when your done.</p>
                    <form class="form" id="edit_guide" action="" method="Post">
                        <div class="cont-popup cont col-sm-12 col-sm-offset-12 mb-3">
                            <input type="hidden" class="form-control" name="guideID">
                            <textarea type="text" class="form-control" name="newguide" id="eguide" required></textarea>
                        </div>
                        <!-- <span>Your Class code: </span> <span id="code"><?= $classCode; ?></span> -->
                        <button class="btn btn-success float-right mr-4" type="submit" name="edit_guide" form="edit_guide">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="mymodaldeleteguide">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <span class="text-light h6">Delete Selected Guideline</span>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>This action cannot be undone. are you you want to continue?</p>
                    <form class="form" id="delete_guide" action="" method="Post">
                        <div class="cont-popup cont col-sm-12 col-sm-offset-12 mb-0">
                            <input type="hidden" class="form-control" name="guideID" id="eid">
                        </div>
                        <button class="btn btn-danger float-right mr-4" type="submit" name="delete_guide" form="delete_guide">Delete Guide</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" data-bs-dismiss="modal">
            <img class="modal-content" id="img01" src="" class="imagepreview" style="width: 100%;">
        </div>
    </div>


</body>

</html>

<script>
    $(document).ready(function() {
        var i = 1;
        var max = 5;
        $('#add').click(function() {
            if (i < max) {
                i++;
                $('#dynamic_field').append('<div class="row pt-3" id="row' + i + '"><div class="col-10"><textarea name="guide[]" placeholder="Enter Guideline ' + i + '" class="form-control name_list" /></textarea></div><div class="col-1"><button type="button" name="remove" id="' + i + '" class="btn btn-link text-danger mt-2 btn_remove"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></button></div></div>');
                document.getElementById('max').value = max;
            } else {
                document.getElementById('oops').innerText = "*Can only set 5 or less guidelines";
            }
        });
        $(document).on('click', '.btn_remove', function() {
            button_id = $(this).attr("id");
            $('#row' + button_id + '').remove();
            max++;
        });
        $('#submit').click(function() {
            $.ajax({
                url: "name.php",
                method: "POST",
                data: $('#add_guide').serialize(),
                success: function(data) {
                    alert(data);
                    $('#add_guide')[0].reset();
                }
            });
        });
    });

    $('#mymodaleditguide').on('show.bs.modal', function(e) {
        var guideID = $(e.relatedTarget).data('userid');
        var id = guideID;
        var newguide = $('#guidetext' + id).text();
        $(e.currentTarget).find('input[name="eid"]').val(newguide);
        $(e.currentTarget).find('input[name="guideID"]').val(guideID);
        document.getElementById("eguide").value = newguide;

        // $.get('homeAdmin.php?id='+id);
    });

    $('#mymodaldeleteguide').on('show.bs.modal', function(e) {
        var guideID = $(e.relatedTarget).data('userid');
        $(e.currentTarget).find('input[name="guideID"]').val(guideID);
    });


    $('#exampleModal').on('show.bs.modal', function(e) {
        var userid = $(e.relatedTarget).data('userid');
        var id = userid;
        var fullame = $('#cpFullname' + id).text();
        var position = $('#cpPostion' + id).text();
        $(e.currentTarget).find('input[name="fullname"]').val(fullname);
        $(e.currentTarget).find('input[name="position"]').val(position);
    });

    $('#imagemodal').on('show.bs.modal', function(e) {
        var bookId = $(e.relatedTarget).data('id');
        document.getElementById("img01").src = bookId;
    });
</script>