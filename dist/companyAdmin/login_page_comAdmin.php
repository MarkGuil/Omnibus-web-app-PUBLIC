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


<body class="vh-100">
    <div class="d-flex align-items-center h-100">
        <div class="container container-small vertical-center bg-white p-5">
            <div class="h1 text-center mb-3 mt-2">
                Omnibus <small class="text-primary">partner</small>
            </div>
            <h4 class="mb-3 ml-2">Login</h4>
            <?php if (isset($_SESSION['success'])) { ?>
                <div class="alert alert-success mx-3 mt-3" role="alert">
                    <?php echo $_SESSION['success']  ?>
                </div>
            <?php } ?>
            <form class="mx-3 mb-3" action="#" method="POST">
                <?php include('errors.php'); ?>
                <div class="form-grou md-6 mb-3">
                    <label for="inputEmail4" class="h6 text-muted">Email Address</label>
                    <input type="email" class="form-control" name="email" placeholder="" required>
                </div>
                <div class="form-group md-6">
                    <label for="inputPassword4" class="h6 text-muted">Password</label>
                    <input type="password" class="form-control" name="password" placeholder="" required>
                </div>
                <button type="submit" class="btn btn-primary mt-2" name="login_user">Login</button>
            </form>
            <a href="register_page_comadmin.php" class="ml-3 ">Company Registration Form</a>
            <div class="mt-3">
                <a href="../index.php" class="">Home</a>
            </div>
        </div>
    </div>
</body>

</html>