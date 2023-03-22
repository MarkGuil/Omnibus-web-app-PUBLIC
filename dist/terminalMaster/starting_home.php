<?php
include('../database/db.php');
include('server_terminal_master.php') ?>
<?php
if (session_id() == '') {
    session_start();
}

if (!isset($_SESSION['compadminName'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login_page.php');
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['compTerMasemail']);
    unset($_SESSION['compadminName']);
    unset($_SESSION['compTerMasID']);
    header("location: login_page.php");
}

$x = 1;
$compID = $_SESSION['compadminName'];
$email = $_SESSION['compTerMasemail'];
$id = $_SESSION['compTerMasID'];
$terminalid = $conn->query("SELECT terminalID FROM user_partner_tmaster WHERE id = '$id'");

$result = $conn->query("SELECT * FROM user_partner_tmaster WHERE id = '$id'");
$details = $result->fetch_all(MYSQLI_ASSOC);
$result2 = $conn->query("SELECT * FROM guidelines WHERE companyID = '$compID'");
$guides = $result2->fetch_all(MYSQLI_ASSOC);
//$tname = $conn->query("SELECT terminal_name FROM terminal WHERE id = '$terminalid'");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>OmnibusTerminal</title>
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
    <?php include 'partials/navbar.php'; ?>

    <main class="d-flex flex-nowrap min-vh-100">
        <?php include 'partials/sidebar.php'; ?>

        <div class="position-relative flex-fill container  py-3 px-2 ">

            <div class="container container-medium py-4 px-5 bg-white rounded shadow">
                <div class="row">
                    <div class="col-md-3 col-lg-4 col-xl-2">
                        <h6 class="pt-4 pb-3"><b>Your Account</b></h6>
                        <div class="px-1">
                            <form action="" method="get">
                                <button class="btn py-1 text-primary" name="adetailView" value="">
                                    <h6>Account Details</h6>
                                </button><br>
                                <button class="btn py-1 text-primary mt-2" name="ccomdlView">
                                    <h6>Update Account Details</h6>
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

                    <div class="col-md-9 col-lg-9 col-xl-10 pl-5 pr-4">
                        <?php foreach ($details as $detail) : ?>

                            <?php
                            if (isset($_GET['adetailView']) || !isset($_GET['adetailView']) && !isset($_GET['ccomdlView']) && !isset($_GET['cpassView']) && !isset($_GET['guidelinesView'])) {
                                include 'extentions/termaster_details_view.php';
                            }
                            if (isset($_GET['cpassView'])) {
                                include 'extentions/change_password_view.php';
                            }
                            if (isset($_GET['ccomdlView'])) {
                                include 'extentions/update_account_details_view.php';
                            }
                            if (isset($_GET['guidelinesView'])) {
                                include 'extentions/guidelines_view.php';
                            }
                            ?>
                        <?php endforeach ?>
                    </div>
                </div>
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