<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

session_start();

$errors = array();
include '../database/db.php';

if (isset($_POST['login_user_comp'])) {
    $role = 1;
    if (!empty($_POST['email'])) {
        $email = mysqli_real_escape_string($db, $_POST['email']);
    }
    if (!empty($_POST['password'])) {
        $password = mysqli_real_escape_string($db, $_POST['password']);
    }
    if (!empty($_POST['role'])) {
        $role = mysqli_real_escape_string($db, $_POST['role']);
    }

    if ($role == 1) {
        if (count($errors) == 0) {
            $encryptpassword = md5($password);
            $query = "SELECT * FROM user_partner_admin WHERE email='$email' AND password='$encryptpassword'";
            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) == 1) {
                if (getVerified($email, $role) == NULL) {
                    $_SESSION['success'] = "Your email is still not verified. Please check your email for the verification code";
                    $_SESSION['logged'] = 0;
                    header('location: company_email_verification?email=' . $email);
                } else {
                    $_SESSION['compadminemail'] = $email;
                    $_SESSION['compadminName'] = getcompName($email);
                    $_SESSION['compadminID'] = getcompID($email);
                    $_SESSION['compadminVerify'] = getVerified($email, $role);
                    $_SESSION['compadminsuccess'] = "You are now logged in";
                    header('location: ../companyAdmin/starting_home.php');
                }
            } else {
                array_push($errors, "Wrong email/password combination");
            }
        }
    } else {
        if (count($errors) == 0) {
            $encryptpassword = md5($password);
            $query = "SELECT * FROM user_partner_tmaster WHERE email='$email' AND password='$encryptpassword'";
            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) == 1) {
                if (getVerified($email, $role) == NULL) {
                    $_SESSION['compTerMassuccess'] = "Your email is still not verified. Please check your email for the verification code";
                    $_SESSION['logged'] = 1;
                    header('location: company_email_verification?email=' . $email);
                } else {
                    $_SESSION['compTerMasemail'] = $email;
                    $_SESSION['compadminName'] = getcompNameId($email);
                    $_SESSION['compadminNameID'] = getcompNameId($email);
                    $_SESSION['terminalID'] = getterminalId($email);
                    $_SESSION['compTerMasID'] = getTerMasID($email);
                    $_SESSION['compTerMasVerify'] = getVerified($email, $role);
                    $_SESSION['compTerMassuccess'] = "You are now logged in";
                    header('location: ../terminalMaster/starting_home.php');
                }
            } else {
                array_push($errors, "Wrong email/password combination");
            }
        }
    }
}



function getcompName($email)
{
    $mysql = connect();
    $count = $mysql->query("SELECT `companyName` FROM `user_partner_admin` WHERE `email`='$email' limit 1") or die($mysql->connect_error);
    $result = array_values($count->fetch_assoc());
    return $result[0];
}

function getcompID($email)
{
    $mysql = connect();
    $count = $mysql->query("SELECT `id` FROM `user_partner_admin` WHERE `email`='$email' limit 1") or die($mysql->connect_error);
    $result = array_values($count->fetch_assoc());
    return $result[0];
}

function getVerified($email, $role)
{
    $mysql = connect();
    if ($role == 1) {
        $count = $mysql->query("SELECT `email_verified_at` FROM `user_partner_admin` WHERE `email`='$email' limit 1") or die($mysql->connect_error);
        $result = array_values($count->fetch_assoc());
        return $result[0];
    } else {
        $count = $mysql->query("SELECT email_verified_at FROM user_partner_tmaster WHERE email='$email' limit 1") or die($mysql->connect_error);
        $result = array_values($count->fetch_assoc());
        return $result[0];
    }
}





function getcompNameId($email)
{
    $mysql = connect();
    $count = $mysql->query("SELECT `companyID` FROM `user_partner_tmaster` WHERE `email`='$email' limit 1") or die($mysql->connect_error);
    $result = array_values($count->fetch_assoc());
    return $result[0];
}

function getterminalId($email)
{
    $mysql = connect();
    $count = $mysql->query("SELECT `terminalID` FROM `user_partner_tmaster` WHERE `email`='$email' limit 1") or die($mysql->connect_error);
    $result = array_values($count->fetch_assoc());
    return $result[0];
}

function getTerMasID($email)
{
    $mysql = connect();
    $count = $mysql->query("SELECT `id` FROM `user_partner_tmaster` WHERE `email`='$email' limit 1") or die($mysql->connect_error);
    $result = array_values($count->fetch_assoc());
    return $result[0];
}
