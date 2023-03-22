<?php
include('../database/db.php');
include('server_terminal_master.php') ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Omnibus</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/9bfa39220a.js" crossorigin="anonymous"></script>

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

<body class="bg-secondary vh-100">
    <div class="d-flex align-items-center h-100">
        <div class="container container-small vertical-center bg-white p-5">
            <div class="h1 text-center mb-3 mt-2">
                Omnibus <small class="text-primary">Terminal Master</small>
            </div>
            <h4 class="mb-1 ml-2 text-secondary">Verification</h4>
            <?php if (isset($_SESSION['success'])) { ?>
                <div class="alert alert-warning mx-3 mt-3" role="alert">
                    <?php echo $_SESSION['success']  ?>
                </div>
            <?php } ?>
            <?php if (isset($_SESSION['compTerMassuccess'])) { ?>
                <div class="alert alert-warning mx-3 mt-3" role="alert">
                    <?php echo $_SESSION['compTerMassuccess']  ?>
                </div>
            <?php } ?>
            <?php include('errors.php'); ?>
            <form class="mx-3 mb-3 mt-3" action="#" method="POST">
                <!-- <?php include('errors.php'); ?> -->
                <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>" required>
                <div class="form-group md-6">
                    <label for="verify" class="h6 text-muted">Enter verification code</label>
                    <input class="form-control" type="text" name="verification_code" placeholder="" required />
                </div>
                <?php if ($_SESSION['logged'] == 0) { ?>
                    <input class="btn btn-primary mt-3 px-4" type="submit" name="verify_email" value="Confirm">
                <?php } else { ?>
                    <input class="btn btn-primary mt-3 px-4" type="submit" name="verify_new_email" value="Confirm">
                <?php } ?>
            </form>
        </div>
    </div>
</body>

</html>