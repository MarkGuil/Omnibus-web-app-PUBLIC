<?php

include '../database/db.php';

if (isset($_POST["verify_email"])) {
    $email = $_POST["email"];
    $verification_code = $_POST["verification_code"];

    $sql = "UPDATE user_partner_admin SET email_verified_at = NOW() WHERE email = '" . $email . "' AND verification_code = '" . $verification_code . "'";
    $result  = mysqli_query($db, $sql);

    if (mysqli_affected_rows($db) == 0) {
        array_push($errors, "Verification code failed.");
    } else {
        $_SESSION['success'] = "Your email has been verified. Try logging in again";
        header('location: logina.php');
        exit();
    }
}

if (isset($_POST["verify_email_tmaster"])) {
    $email = $_POST["email"];
    $verification_code = $_POST["verification_code"];

    $sql = "UPDATE user_partner_tmaster SET email_verified_at = NOW() WHERE email = '" . $email . "' AND verification_code = '" . $verification_code . "'";
    $result  = mysqli_query($db, $sql);

    if (mysqli_affected_rows($db) == 0) {
        array_push($errors, "Verification code failed.");
    }
    $_SESSION['success'] = "Your email has been verified. Try logging in again";
    header('location: logina.php');
    exit();
}
