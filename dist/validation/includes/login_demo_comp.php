<?php

if (isset($_POST['login_demo_comp'])) {
    $role = 1;
    if (!empty($_POST['email'])) {
        $email = mysqli_real_escape_string($db, $_POST['email']);
    }

    if (count($errors) == 0) {
        $_SESSION['compadminemail'] = $email;
        $_SESSION['compadminName'] = 'Demo User';
        $_SESSION['compadminID'] = 'demo';
        $_SESSION['compadminVerify'] = date("Y-n-j g:i:s");;
        $_SESSION['compadminsuccess'] = "You are now logged in";
        header('location: ../companyAdmin/starting_home.php');
    }
}
