<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

session_start();

$errors = array();
include '../database/db.php';

if (isset($_POST['adduser'])) {
    $fullname = mysqli_real_escape_string($db, $_POST['fullname']);
    $idtype = mysqli_real_escape_string($db, $_POST['usertype']);
    $address = mysqli_real_escape_string($db, $_POST['address']);
    $password = mysqli_real_escape_string($db, $_POST['password1']);
    $birthdate = mysqli_real_escape_string($db, $_POST['birthdate']);
    $cnumber = mysqli_real_escape_string($db, $_POST['cnumber']);
    $email = mysqli_real_escape_string($db, $_POST['email']);

    $mail = new PHPMailer(true);

    if (empty($fullname)) {
        array_push($errors, "Username is required");
    }
    if (empty($address)) {
        array_push($errors, "Address is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }
    if (empty($birthdate)) {
        array_push($errors, "Birthdate is required");
    }
    if (empty($cnumber)) {
        array_push($errors, "Contact Number is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }

    if (uniqueCustomer($fullname, $email, $idtype)) {
        if (count($errors) == 0) {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = '';
            $mail->SMTPAuth = true;
            $mail->Username = '';
            $mail->Password = '';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom('');
            $mail->addAddress($email, $fullname);
            $mail->isHTML(true);

            $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

            $mail->Subject = 'Email verification';
            $mail->Body    = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';

            $mail->send();

            $encryptpassword = md5($password);
            addnewcustomer($idtype, $fullname, $address, $cnumber, $email, $birthdate, $encryptpassword, $verification_code);
            updateCustomerCount(addCustomerCount($idtype), $idtype);
            $_SESSION['name'] = $fullname;
            $_SESSION['customersuccess'] = "You are now logged in";
            header('location: email-verification.php?email=' . $email);
        }
    } else {
        array_push($errors, "Customer already exists");
    }
}

if (isset($_POST['adduser_mobile'])) {
    $fullname = mysqli_real_escape_string($db, $_POST['fullname']);
    $idtype = mysqli_real_escape_string($db, $_POST['usertype']);
    $address = mysqli_real_escape_string($db, $_POST['address']);
    $password = mysqli_real_escape_string($db, $_POST['password1']);
    $birthdate = mysqli_real_escape_string($db, $_POST['birthdate']);
    $cnumber = mysqli_real_escape_string($db, $_POST['cnumber']);
    $email = mysqli_real_escape_string($db, $_POST['email']);

    $mail = new PHPMailer(true);

    if (empty($fullname)) {
        array_push($errors, "Username is required");
    }
    if (empty($address)) {
        array_push($errors, "Address is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }
    if (empty($birthdate)) {
        array_push($errors, "Birthdate is required");
    }
    if (empty($cnumber)) {
        array_push($errors, "Contact Number is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }

    if (uniqueCustomer($fullname, $email, $idtype)) {
        if (count($errors) == 0) {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = '';
            $mail->SMTPAuth = true;
            $mail->Username = '';
            $mail->Password = '';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom('');
            $mail->addAddress($email, $fullname);
            $mail->isHTML(true);

            $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

            $mail->Subject = 'Email verification';
            $mail->Body    = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';

            $mail->send();

            $encryptpassword = md5($password);
            addnewcustomer($idtype, $fullname, $address, $cnumber, $email, $birthdate, $encryptpassword, $verification_code);
            updateCustomerCount(addCustomerCount($idtype), $idtype);
            $_SESSION['name'] = $fullname;
            $_SESSION['customersuccess'] = "You are now logged in";
            header('location: email-verification-mobile.php?email=' . $email);
        }
    } else {
        array_push($errors, "Customer already exists");
    }
}

function uniqueCustomer($fullname, $email, $idtype)
{
    $mysql = connect();
    $count = $mysql->query("SELECT COUNT(`fullname`) FROM `user_customer` WHERE `fullname`='$fullname' AND `email`='$email'") or die($mysql->connect_error);
    $result = array_values($count->fetch_assoc());
    if ($result[0] > 0)
        return false;
    else
        return true;
}

function addnewcustomer($idtype, $fullname, $address, $cnumber, $email, $birthdate, $encryptpassword, $verification_code)
{
    $mysql = connect();
    $mysql->query("INSERT INTO `user_customer` (`usertype`, `fullname`, `address`, `connumber`, `email`, `birthdate`, `password`, `verification_code`) VALUES('1','$fullname','$address', '$cnumber', '$email', '$birthdate', '$encryptpassword', '$verification_code')") or die($mysql->connect_error);
}

function updateCustomerCount($count, $idtype)
{
    $mysql = connect();
    $mysql->query("UPDATE `users` SET `count`='$count' WHERE `id`='$idtype'") or die($mysql->connect_error);
}

function addCustomerCount($type)
{
    $mysql = connect();
    $count = $mysql->query("SELECT `count` FROM `users` WHERE `id`='$type' limit 1") or die($mysql->connect_error);
    $result = array_values($count->fetch_assoc());
    $result[0] = $result[0] + 1;
    return $result[0];
}
