<?php
include('../database/db.php');
include('server_conductor.php'); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Omnibus</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

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

<body class="">
    <div class="container container-small vertical-center bg-white px-4 py-5">
        <div class="h1 text-center text-primary mb-5 mt-2">Omnibus</div>
        <h5 class="mb-4 ml-2 text-primary">Login</h5>
        <form class="mx-3 mb-5" action="loginConductor.php" method="POST">
            <?php include('errors.php'); ?>
            <div class="form-group md-6 mb-3">
                <label class="h6 text-muted">Email Address</label>
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="form-group md-6">
                <label for="inputPassword4" class="text-muted" style="font-weight: 500;">Password</label>
                <input type="password" class="form-control py-2" name="password" placeholder="Type your password" required>
            </div>

            <button type="submit" class="btn btn-primary mt-3 px-4" name="login_user"><b>Login</b></button>
        </form>
    </div>
</body>

</html>