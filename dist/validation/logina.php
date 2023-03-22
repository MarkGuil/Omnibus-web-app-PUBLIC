<?php include('includes/login_company.php') ?>
<?php include('includes/login_demo_comp.php') ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>OmnibusPartner</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
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
        <div class="container container-small vertical-center shadow-lg bg-white p-5 ">
            <div class="h1 text-center mb-3 mt-2">
                Omnibus <small class="text-primary">partner</small>
            </div>
            <h4 class="mb-3 ml-2">Login</h4>
            <?php if (isset($_SESSION['success'])) { ?>
                <div class="alert alert-success mx-3 mt-3" role="alert">
                    <?php echo $_SESSION['success']  ?>
                </div>
            <?php } ?>
            <form class="mx-3 mb-3" id="login_user_comp" action="#" method="POST">
                <?php include('includes/errors.php'); ?>
                <div class="form-grou md-6 mb-3">
                    <label for="inputEmail4" class="h6 text-muted">Email Address</label>
                    <input type="email" class="form-control" name="email" placeholder="" required>
                </div>
                <div class="form-group md-6 mb-4">
                    <label for="inputPassword4" class="h6 text-muted">Password</label>
                    <input type="password" class="form-control" name="password" placeholder="" required>
                </div>
                <div class="form-check md-6 form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="role" value="2" role="switch" id="flexSwitchCheckDefault">
                    <label class="form-check-label" for="flexSwitchCheckDefault"> Continue as a terminal master</label>
                </div>
            </form>
            <!-- <form class="" action="#" id="login_demo_comp" method="POST">
                <input type="hidden" class="form-control" name="email" placeholder="Email" value="Demo" required>
            </form> -->
            <div class=" mx-3 mb-5">
                <button type="submit" class="btn btn-primary " name="login_user_comp" form="login_user_comp">Login</button>
                <span class="py-2 mt-2 px-3 fs-3"> or </span>
                <button type="button" class="btn btn-outline-primary px-4" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Not yet available"><b>Login as demo user</b></button>
            </div>
            <span>Don't have an account? <a href="registera.php" class="">Company Registration Form</a></span>
            <div class="mt-3">
                <a href="../index.php" class="">Home</a>
            </div>
        </div>
    </div>

</body>

</html>
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>