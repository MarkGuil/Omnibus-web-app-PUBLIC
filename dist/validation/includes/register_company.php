<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

session_start();

$errors = array();
include '../database/db.php';

if (isset($_POST['addCompanyAdmin'])) {
    $compName = mysqli_real_escape_string($db, $_POST['compName']);
    $idtype = 5;
    $address = mysqli_real_escape_string($db, $_POST['address']);
    $cnumber = mysqli_real_escape_string($db, $_POST['cnumber']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $fullname = mysqli_real_escape_string($db, $_POST['fullname']);
    $position = mysqli_real_escape_string($db, $_POST['position']);
    $password = mysqli_real_escape_string($db, $_POST['password1']);

    $mail = new PHPMailer(true);

    if (empty($compName)) {
        array_push($errors, "Company Name is required");
    }
    if (empty($fullname)) {
        array_push($errors, "fullname is required");
    }
    if (empty($address)) {
        array_push($errors, "Address is required");
    }
    if (empty($cnumber)) {
        array_push($errors, "Contact Number is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($position)) {
        array_push($errors, "Your Position inside the company is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    if (uniqueCompany($fullname, $email)) {
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
            addNewCompanyAdmin($idtype, $compName, $address, $fullname, $position, $cnumber, $email, $encryptpassword, $verification_code);
            updateCompanyCount(addCompanyCount($idtype), $idtype);
            $_SESSION['logged'] = 0;
            $_SESSION['success'] = "An email with a verification code was just sent to " . $email;
            header('location: company_email_verification.php?email=' . $email);
        }
    } else {
        array_push($errors, "Customer already exists");
    }
}

function addNewCompanyAdmin($idtype, $compName, $address, $fullname, $position, $cnumber, $email, $encryptpassword, $verification_code)
{
    $mysql = connect();
    $mysql->query("INSERT INTO `user_partner_admin`(`usertype`, `companyName`, `companyAddress`, `fullname`, `position`, `contactNumber`, `email`, `password`, `verification_code`) VALUES ('$idtype','$compName','$address','$fullname','$position','$cnumber','$email','$encryptpassword','$verification_code')") or die($mysql->connect_error);
}

function uniqueCompany($fullname, $email)
{
    $mysql = connect();
    $count = $mysql->query("SELECT COUNT(`fullname`) FROM `user_partner_admin` WHERE `fullname`='$fullname' AND `email`='$email'") or die($mysql->connect_error);
    $result = array_values($count->fetch_assoc());
    if ($result[0] > 0)
        return false;
    else
        return true;
}

function updateCompanyCount($count, $idtype)
{
    $mysql = connect();
    $mysql->query("UPDATE `users` SET `count`='$count' WHERE `id`='$idtype'") or die($mysql->connect_error);
}

function addCompanyCount($type)
{
    $mysql = connect();
    $count = $mysql->query("SELECT `count` FROM `users` WHERE `id`='$type' limit 1") or die($mysql->connect_error);
    $result = array_values($count->fetch_assoc());
    $result[0] = $result[0] + 1;
    return $result[0];
}
