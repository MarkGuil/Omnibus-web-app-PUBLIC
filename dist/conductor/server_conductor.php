<?php

// include 'model.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

session_start();

$errors = array();

if (isset($_POST['login_user'])) {
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($email)) {
    array_push($errors, "Email is required");
  }
  if (empty($password)) {
    array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
    $password = md5($password);
    $query = "SELECT * FROM employees WHERE email='$email' AND password='$password'";
    $results = mysqli_query($db, $query);
    if (mysqli_num_rows($results) == 1) {
      $user = mysqli_fetch_object($results);
      if ($user->email_verified_at == null) {
        // die("Please verify your email <a href='email-verification.php?email=" . $email . "'>from here</a>");
        header('location: email-verification.php?email=' . $email);
        // echo "hello";
      } else {
        $_SESSION['conductorname'] = getConductorName($email, $password);
        $_SESSION['conductorid'] = getConductorID($email, $password);
        $_SESSION['conductorsuccess'] = "You are now logged in";
        header('location: homeConductor.php');
      }
    } else {
      array_push($errors, "Wrong username/password combination");
    }
  }
}

//email verification
if (isset($_POST["verify_email"])) {
  $email = $_POST["email"];
  $verification_code = $_POST["verification_code"];

  // mark email as verified
  $sql = "UPDATE employees SET email_verified_at = NOW() WHERE email = '" . $email . "' AND verification_code = '" . $verification_code . "'";
  $result  = mysqli_query($db, $sql);

  if (mysqli_affected_rows($db) == 0) {
    die("Verification code failed.");
  }

  header('location: homeConductor.php');
  exit();
}


if (isset($_POST['openList'])) {
  $tripid = mysqli_real_escape_string($db, $_POST['tripsid']);
  $busid = mysqli_real_escape_string($db, $_POST['busid']);
  $compid = mysqli_real_escape_string($db, $_POST['compid']);
  $routeid = mysqli_real_escape_string($db, $_POST['routeid']);
  $trmid = mysqli_real_escape_string($db, $_POST['trmid']);
  $conductorid = mysqli_real_escape_string($db, $_POST['conductorid']);
  $tripdate = mysqli_real_escape_string($db, $_POST['tripdate']);
  $departtime = mysqli_real_escape_string($db, $_POST['departtime']);
  $bustripid = mysqli_real_escape_string($db, $_POST['bustripid']);

  if (empty($tripid)) {
    array_push($errors, "origin is required");
  }
  if (empty($compid)) {
    array_push($errors, "destination is required");
  }

  if (count($errors) == 0) {
    $_SESSION['selectedtripid'] = $tripid;
    $_SESSION['selectedbusid'] = $busid;
    $_SESSION['selectedcompid'] = $compid;
    $_SESSION['selectedroute'] = $routeid;
    $_SESSION['selectedtrmid'] = $trmid;
    $_SESSION['selectedconid'] = $conductorid;
    $_SESSION['selecteddate'] = $tripdate;
    $_SESSION['selectedtime'] = $departtime;
    $_SESSION['selectedbustrip'] = $bustripid;

    header('location: bookingList.php');
  }
}

//from bookinglist to setting booking status
if (isset($_POST['setstatus'])) {
  $btripid = mysqli_real_escape_string($db, $_POST['bustripsid']);
  $status = mysqli_real_escape_string($db, $_POST['bookingstatus']);
  $compid = mysqli_real_escape_string($db, $_POST['compid']);
  $bookid = mysqli_real_escape_string($db, $_POST['bookid']);
  $cid = mysqli_real_escape_string($db, $_POST['customerid']);
  $reference = mysqli_real_escape_string($db, $_POST['refid']);
  $seatnumber = mysqli_real_escape_string($db, $_POST['seatnumber']);
  $conductorid = mysqli_real_escape_string($db, $_POST['conductorid']);

  if (empty($btripid)) {
    array_push($errors, "origin is required");
  }
  if (empty($compid)) {
    array_push($errors, "destination is required");
  }

  if (count($errors) == 0) {
    $_SESSION['selectedbtripid'] = $tripid;
    $_SESSION['selectedstatus'] = $status;
    $_SESSION['selectedcompid'] = $compid;
    $_SESSION['selectedbookid'] = $bookid;
    $_SESSION['selectedcustomerid'] = $cid;
    $_SESSION['selectedreference'] = $reference;
    $_SESSION['selectedconductor'] = $conductorid;
    $_SESSION['selectedseat'] = $seatnumber;
    header('location: setstatus.php');
  }
}

//setoccupied
if (isset($_POST['setoccupied'])) {
  $bookingid = mysqli_real_escape_string($db, $_POST['bookingid']);
  $reference = mysqli_real_escape_string($db, $_POST['reference']);
  $custid = $_SESSION['conductorid'];
  if (empty($bookingid)) {
    array_push($errors, "book details is required");
  }
  if (count($errors) == 0) {
    occupiedBooking($bookingid, $reference);
    header('location: bookingList.php');
  }
}

//setunoccupied
if (isset($_POST['setunoccupied'])) {
  $bookingid = mysqli_real_escape_string($db, $_POST['bookingid']);
  $reference = mysqli_real_escape_string($db, $_POST['reference']);
  $custid = $_SESSION['conductorid'];
  if (empty($bookingid)) {
    array_push($errors, "book details is required");
  }
  if (count($errors) == 0) {
    unoccupiedBooking($bookingid, $reference);
    header('location: bookingList.php');
  }
}


//editProfile
if (isset($_POST['editprofile'])) {
  $username = mysqli_real_escape_string($db, $_POST['fullname']);
  $address = mysqli_real_escape_string($db, $_POST['address']);
  $cid = mysqli_real_escape_string($db, $_POST['cid']);
  $custid = $_SESSION['conductorid'];

  if (empty($username)) {
    array_push($errors, "Username is required");
  }
  if (empty($address)) {
    array_push($errors, "Address is required");
  }
  if (count($errors) == 0) {
    updateConductorDetails($username, $address, $cid);
    $_SESSION['conductorname'] = $username;
    header('location: registeredProfile.php');
  }
}

//changeEmail
if (isset($_POST['changeEmail'])) {
  $emails = mysqli_real_escape_string($db, $_POST['email']);

  $custid = $_SESSION['conductorid'];

  if (empty($emails)) {
    array_push($errors, "Email is required");
  }
  if (count($errors) == 0) {
    changeEmail($emails, $custid);
    header('location: registeredProfile.php');
  }
}

//change number
if (isset($_POST['changeContactNumber'])) {
  $cnumber = mysqli_real_escape_string($db, $_POST['cnumber']);
  $cid = mysqli_real_escape_string($db, $_POST['cid']);
  $custid = $_SESSION['conductorid'];

  if (empty($cnumber)) {
    array_push($errors, "Contact number is required");
  }
  if (count($errors) == 0) {
    updateCustomerNumber($cnumber, $cid);
    header('location: registeredProfile.php');
  }
}

//change Password
if (isset($_POST['changePassword'])) {
  $curpass = mysqli_real_escape_string($db, $_POST['curentpassword']);
  $newpass = mysqli_real_escape_string($db, $_POST['password1']);
  $custid = $_SESSION['customerid'];

  if (empty($curpass)) {
    array_push($errors, "Current Password is required");
  }
  if (empty($newpass)) {
    array_push($errors, "New Password is required");
  }

  if (count($errors) == 0) {
    $cpassword = md5($curpass);
    $query = "SELECT * FROM employees WHERE id='$custid' AND password='$cpassword'";
    $results = mysqli_query($db, $query);
    if (mysqli_num_rows($results) == 1) {
      $npassword = md5($newpass);
      updatePassword($npassword, $custid);
      header('location: registeredProfile.php');
    } else {
      array_push($errors, "Current password combination is wrong");
    }
  }
}

//Functions
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

function updateConductorDetails($username, $address, $cid)
{
  $mysql = connect();
  $mysql->query("UPDATE `employees` SET `fullname`='$username', `address`='$address' WHERE `id`='$cid'") or die($mysql->connect_error);
}

function updateCustomerNumber($cnumber, $cid)
{
  $mysql = connect();
  $mysql->query("UPDATE `employees` SET `contactNumber`='$cnumber' WHERE `id`='$cid'") or die($mysql->connect_error);
}
function changeEmail($emails, $custid)
{
  $mysql = connect();
  $mysql->query("UPDATE `employees` SET `email`='$emails' WHERE `id`='$custid'") or die($mysql->connect_error);
}

function updatePassword($npassword, $custid)
{
  $mysql = connect();
  $mysql->query("UPDATE `employees` SET `password`='$npassword' WHERE `id`='$custid'") or die($mysql->connect_error);
}

function occupiedBooking($bookingid, $reference)
{
  $mysql = connect();
  $mysql->query("UPDATE `booking_tbl` SET `booking_status`='travelled' WHERE `id`='$bookingid' AND `reference_id`='$reference'") or die($mysql->connect_error);
}

function unoccupiedBooking($bookingid, $reference)
{
  $mysql = connect();
  $mysql->query("UPDATE `booking_tbl` SET `booking_status`='confirmed' WHERE `id`='$bookingid' AND `reference_id`='$reference'") or die($mysql->connect_error);
}
