<?php

if (isset($_POST["verify_email"])) {
    $email = $_POST["email"];
    $verification_code = $_POST["verification_code"];

    $sql = "UPDATE user_customer SET email_verified_at = NOW() WHERE email = '" . $email . "' AND verification_code = '" . $verification_code . "'";
    $result  = mysqli_query($db, $sql);

    if (mysqli_affected_rows($db) == 0) {
        die("Verification code failed.");
    }

    header('location: loginb.php');
    exit();
}
