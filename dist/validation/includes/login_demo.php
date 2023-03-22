<?php

if (isset($_POST['login_demo'])) {
    $role = 1;
    if (!empty($_POST['email'])) {
        $email = mysqli_real_escape_string($db, $_POST['email']);
    }

    if (count($errors) == 0) {
        $_SESSION['customername'] = 'Demo';
        $_SESSION['customerid'] = 'demo_account';
        $_SESSION['isMobile'] = false;
        $_SESSION['customersuccess'] = "You are now logged in";
        header('location: ../customer/registeredHome.php');
    }
}


if (isset($_POST['login_demo_mobile'])) {
    $role = 1;
    if (!empty($_POST['email'])) {
        $email = mysqli_real_escape_string($db, $_POST['email']);
    }

    if (count($errors) == 0) {
        $_SESSION['customername'] = 'Demo';
        $_SESSION['customerid'] = 'demo_account';
        $_SESSION['isMobile'] = true;
        $_SESSION['customersuccess'] = "You are now logged in";
        header('location: ../customer/registeredHome.php');
    }
}
