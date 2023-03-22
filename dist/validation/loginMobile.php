<?php
include 'includes/login_passenger.php';
include 'includes/login_demo.php';

if (session_id() == '') {
    session_start();
}

if (isset($_SESSION['customername'])) {
    header("location: ../customer/registeredHome.php");
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Omnibus</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <style>
        img[alt="www.000webhost.com"] {
            display: none;
        }
    </style>
    <style>
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

<body class="vh-100">
    <div class="d-flex align-items-center h-100">
        <div class="container container-small vertical-center bg-white px-3 py-5">
            <div class="h1 text-center text-primary mb-5 mt-2">Omnibus</div>
            <h5 class="mb-4 ml-2 text-primary">Login</h5>
            <form class="mx-3 mb-2" action="" id="login_user_mobile" method="POST">
                <?php include('includes/errors.php'); ?>
                <div class="form-group md-6 mb-3">
                    <label class="h6 text-muted">Email Address</label>
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                </div>
                <div class="form-group md-6 mb-4">
                    <label for="inputPassword4" class="text-muted" style="font-weight: 500;">Password</label>
                    <input type="password" class="form-control py-2" name="password" placeholder="Type your password" required>
                </div>
                <div class="form-check md-6 form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="role" value="2" role="switch" id="flexSwitchCheckDefault">
                    <label class="form-check-label" for="flexSwitchCheckDefault"> Continue as a conductor</label>
                </div>
            </form>
            <form class="" action="" id="login_demo_mobile" method="POST">
                <input type="hidden" class="form-control" name="email" placeholder="Email" value="Demo" required>
            </form>
            <div class="mx-3 mt-5 mb-5">
                <button type="submit" class="btn btn-primary w-100 px-4" name="login_user_mobile" form="login_user_mobile"><b>Login</b></button>
                <p class="mt-3 px-3 fs-3 text-center"> or </p>
                <button type="submit" class="btn btn-outline-primary w-100 px-4" name="login_demo_mobile" form="login_demo_mobile"><b>Login as demo user</b></button>
            </div>
            <div class="mt-2 mx-3">
                <div class="mt-1">
                    <span style="font-size: .9em;">Don't have an account? </span><a href="registerMobile.php" style="font-size: .9em;">Register</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>