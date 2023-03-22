<?php
include('../database/db.php');
include('server.php'); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>OmnibusAdmin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

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

<body class="bg-secondary vh-100">
    <div class="d-flex align-items-center h-100">
        <div class="container container-small vertical-center bg-white p-5">
            <div class="h1 text-center mb-5 mt-2">
                Omnibus <small class="text-muted">admin</small>
            </div>
            <form class="mx-3" action="loginScreen.php" method="POST">
                <?php include('errors.php'); ?>
                <div class="form-grou md-6 mb-3">
                    <label for="inputEmail4" class="h6 text-muted">Name</label>
                    <input type="text" class="form-control" name="username" placeholder="Name" pattern="[a-zA-Z0-9\s]+" required>
                </div>
                <div class="form-group md-6">
                    <label for="inputPassword4" class="h6 text-muted">Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-dark mt-2" name="login_user">Login</button>
            </form>
            <div class="mx-3 mt-3">
                <a href="../index.php" class="">Home</a>
            </div>
        </div>
    </div>
</body>

</html>