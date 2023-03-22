<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

session_start();

$errors = array();
include '../database/db.php';

if (isset($_POST['login_user'])) {
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
            $password = md5($password);
            $query = "SELECT * FROM `user_customer` WHERE `email`='$email' AND `password`='$password'";
            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) == 1) {
                $user = mysqli_fetch_object($results);
                if ($user->email_verified_at == null) {
                    $_SESSION['success'] = "Your email is still not verified. Please check your email for the verification code";
                    $_SESSION['logged'] = 0;
                    header('location: email-verification.php?email=' . $email);
                } else {
                    $_SESSION['customername'] = getCustomerName($email, $password);
                    $_SESSION['customerid'] = getCustomerID($email, $password);
                    $_SESSION['isMobile'] = false;
                    $_SESSION['customersuccess'] = "You are now logged in";
                    header('location: ../customer/registeredHome.php');
                }
            } else {
                array_push($errors, "Wrong username/password combination");
            }
        }
    } else {
        if (count($errors) == 0) {
            $password = md5($password);
            $query = "SELECT * FROM `employees` WHERE `email`='$email' AND `password`='$password'";
            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) == 1) {
                $user = mysqli_fetch_object($results);
                if ($user->email_verified_at == null) {
                    $_SESSION['success'] = "Your email is still not verified. Please check your email for the verification code";
                    $_SESSION['logged'] = 1;
                    header('location: email-verification.php?email=' . $email);
                } else {
                    $_SESSION['conductorname'] = getConductorName($email, $password);
                    $_SESSION['conductorid'] = getConductorID($email, $password);
                    $_SESSION['isMobile'] = false;
                    $_SESSION['conductorsuccess'] = "You are now logged in";
                    header('location: ../conductor/homeConductor.php');
                }
            } else {
                array_push($errors, "Wrong username/password combination");
            }
        }
    }
}

if (isset($_POST['login_user_mobile'])) {
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
            $password = md5($password);
            $query = "SELECT * FROM `user_customer` WHERE `email`='$email' AND `password`='$password'";
            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) == 1) {
                $user = mysqli_fetch_object($results);
                if ($user->email_verified_at == null) {
                    header('location: email-verification-mobile.php?email=' . $email);
                } else {
                    $_SESSION['customername'] = getCustomerName($email, $password);
                    $_SESSION['customerid'] = getCustomerID($email, $password);
                    $_SESSION['isMobile'] = true;
                    $_SESSION['customersuccess'] = "You are now logged in";
                    header('location: ../customer/registeredHome.php');
                }
            } else {
                array_push($errors, "Wrong username/password combination");
            }
        }
    } else {
        if (count($errors) == 0) {
            $password = md5($password);
            $query = "SELECT * FROM `employees` WHERE `email`='$email' AND `password`='$password'";
            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) == 1) {
                $user = mysqli_fetch_object($results);
                if ($user->email_verified_at == null) {
                    header('location: email-verification-mobile.php?email=' . $email);
                } else {
                    $_SESSION['conductorname'] = getConductorName($email, $password);
                    $_SESSION['conductorid'] = getConductorID($email, $password);
                    $_SESSION['isMobile'] = true;
                    $_SESSION['conductorsuccess'] = "You are now logged in";
                    header('location: ../conductor/homeConductor.php');
                }
            } else {
                array_push($errors, "Wrong username/password combination");
            }
        }
    }
}


function getCustomerID($email, $password)
{
    $mysql = connect();
    $result = $mysql->query("SELECT `id` FROM `user_customer` WHERE `email`='$email' AND `password`='$password' limit 1") or die($mysql->connect_error);
    $id = array_values($result->fetch_assoc());
    return $id[0];
}

function getCustomerName($email, $password)
{
    $mysql = connect();
    $result = $mysql->query("SELECT `fullname` FROM `user_customer` WHERE `email`='$email' AND `password`='$password' limit 1") or die($mysql->connect_error);
    $name = array_values($result->fetch_assoc());
    return $name[0];
}

function getConductorID($email, $password)
{
    $mysql = connect();
    $result = $mysql->query("SELECT id FROM employees WHERE email='$email' AND password='$password' limit 1") or die($mysql->connect_error);
    $id = array_values($result->fetch_assoc());
    return $id[0];
}

function getConductorName($email, $password)
{
    $mysql = connect();
    $result = $mysql->query("SELECT fullname FROM employees WHERE email='$email' AND password='$password' limit 1") or die($mysql->connect_error);
    $name = array_values($result->fetch_assoc());
    return $name[0];
}
