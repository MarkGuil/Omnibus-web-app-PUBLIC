<?php
include 'includes/register_passenger.php';
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
        <div class="container container-small shadow-lg vertical-center bg-white px-5 py-5">
            <div class="h1 text-center mb-3 mt-2 text-primary"><a href="../index.php" class="text-decoration-none">Omnibus</a></div>
            <h4 class="mb-3 ml-2 text-primary">Register</h4>
            <form class="mx-3 mb-4" action="" method="POST" oninput='password2.setCustomValidity(password2.value != password1.value ? "Passwords do not match." : "")'>

                <?php include('includes/errors.php'); ?>
                <div class="form-group md-6 mb-3">
                    <label class="h6 text-muted">Fullname</label>
                    <input type="text" class="form-control" name="fullname" placeholder="Fullname" pattern="[a-zA-Z0-9\s]+" required>
                </div>
                <input type="hidden" class="form-control" name="usertype" value="1" readonly>
                <div class="form-group md-6 mb-3">
                    <label class="h6 text-muted">Address</label>
                    <input type="text" class="form-control" name="address" placeholder="Address" required>
                </div>
                <div class="form-group md-6 mb-3">
                    <label class="h6 text-muted">Birthdate</label>
                    <input type="date" class="form-control" name="birthdate" placeholder="Date" required>
                </div>
                <div class="row mb-3">
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
            <span style="font-size: .9em;">Already have an account? </span><a style="font-size: .9em;" href="loginb.php">Login</a>
            <div class="mt-3">
                <a href="../index.php" class="">Home</a>
            </div>
        </div>
    </div>

</body>

</html>