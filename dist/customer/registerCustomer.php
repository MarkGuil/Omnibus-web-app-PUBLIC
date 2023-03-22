<?php
include('../database/db.php');
include('servercustomer.php'); ?>
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
    <div class="container container-small shadow vertical-center bg-white px-4 py-5">
        <div class="h1 text-center mb-3 mt-2 text-primary">Omnibus</div>
        <h4 class="mb-3 ml-2 text-primary">Register</h4>
        <form class="mx-3 mb-4" action="" method="POST" oninput='password2.setCustomValidity(password2.value != password1.value ? "Passwords do not match." : "")'>
            <?php include('errors.php'); ?>
            <div class="form-group md-6">
                <label class="h6 text-muted">Fullname</label>
                <input type="text" class="form-control" name="fullname" placeholder="Fullname" pattern="[a-zA-Z0-9\s]+" required>
            </div>
            <input type="hidden" class="form-control" name="usertype" value="1" readonly>
            <div class="form-group md-6">
                <label class="h6 text-muted">Address</label>
                <input type="text" class="form-control" name="address" placeholder="Address" required>
            </div>
            <div class="form-group md-6">
                <label class="h6 text-muted">Birthdate</label>
                <input type="date" class="form-control" name="birthdate" placeholder="Date" required>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6 form-group">
                    <label class="h6 text-muted">Password</label>
                    <input type="password" class="form-control" name="password1" placeholder="Password" required>
                </div>
                <div class="col-sm-12 col-md-6 form-group">
                    <label class="h6 text-muted">Retype Password</label>
                    <input type="password" class="form-control" name="password2" placeholder="Password" required>
                </div>
            </div>
            <hr>
            <span class="h6 text-primary">Contact Information</span>
            <div class="row mt-2 mb-3">
                <div class="col-sm-12 col-md-6 form-group ">
                    <label class="h6 text-muted">Contact Number</label>
                    <input type="text" class="form-control" name="cnumber" placeholder="Contact Number" maxlength="11" size="11" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                </div>
                <div class=" col-sm-12 col-md-6 form-group ">
                    <label class="h6 text-muted">Email Address</label>
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-2 px-4" name="adduser"><b>Register</b></button>
        </form>
        <span style="font-size: .9em;">Already have an account? </span><a style="font-size: .9em;" href="loginCustomer.php">Login</a>
    </div>
</body>

</html>