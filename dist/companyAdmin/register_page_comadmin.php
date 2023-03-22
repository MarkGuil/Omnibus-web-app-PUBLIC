<?php
include('../database/db.php');
include('server_comp_admin.php'); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>OmnibusPartner</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <style>
        body {
            background-image: linear-gradient(rgba(18, 57, 176, 0.9), rgba(18, 57, 176, 0.9)), url('../images/bglarge.jpg');
            background-size: cover;
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


<body class=" vh-100">
    <div class="d-flex align-items-center h-100">
        <div class="container container-small vertical-center bg-white p-5">
            <div class="h1 text-center mb-5 mt-2">
                Omnibus <small class="text-primary">partner</small>
            </div>
            <h5 class="mb-3 ml-2 text-secondary">Company Registration Form</h5>
            <form class="mx-3 mb-3" action="" method="POST" oninput='password2.setCustomValidity(password2.value != password1.value ? "Passwords do not match." : "")'>
                <?php include('errors.php'); ?>
                <div class="form-group md-6">
                    <label class="h6 text-muted">Company Name</label>
                    <input type="text" class="form-control" name="compName" placeholder="" pattern="[a-zA-Z0-9\s]+" required>
                </div>
                <input type="hidden" class="form-control" name="usertype" value="4">
                <div class="form-group md-6">
                    <label class="h6 text-muted">Company Address</label>
                    <input type="text" class="form-control" name="address" placeholder="" required>
                </div>
                <div class="row mt-3 ">
                    <div class="col form-group md-6">
                        <label class="h6 text-muted">Company Contact Number</label>
                        <input type="text" class="form-control" name="cnumber" placeholder="" maxlength="11" size="11" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                    </div>
                    <div class="col form-group md-6">
                        <label class="h6 text-muted">Email Address</label>
                        <input type="email" class="form-control" name="email" placeholder="" required>
                    </div>
                </div>
                <div class="form-group md-6 mt-2">
                    <label class="h6 text-muted">Primary Contact - <small class="text-muted"> (First and last name)</small></label>
                    <input type="text" class="form-control" name="fullname" placeholder="" pattern="[a-zA-Z0-9\s]+" required>
                </div>
                <div class="form-group md-6 mt-2">
                    <label class="h6 text-muted">Position inside the company</label>
                    <input type="text" class="form-control" name="position" placeholder="" pattern="[a-zA-Z0-9\s]+" required>
                </div>
                <div class="row mt-3 ">
                    <div class="col form-group md-6">
                        <label for="inputPassword4" class="h6 text-muted">Password</label>
                        <input type="password" class="form-control" name="password1" placeholder="" required>
                    </div>
                    <div class="col form-group md-6">
                        <label for="inputPassword4" class="h6 text-muted">Confirm Password</label>
                        <input type="password" class="form-control" name="password2" placeholder="" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-2" name="addCompanyAdmin">Submit Form</button>
            </form>
            <a href="login_page_comAdmin.php" class="ml-3 ">Login</a>
            <div class="mt-3">
                <a href="../index.php" class="">Home</a>
            </div>
        </div>
    </div>
</body>

</html>