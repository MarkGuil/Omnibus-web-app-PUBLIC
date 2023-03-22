<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
session_start();

$errors = array();

if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
    array_push($errors, "Username is required");
  }
  if (empty($password)) {
    array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
    $password = md5($password);
    $query = "SELECT * FROM user_admin WHERE name='$username' AND password='$password'";
    $results = mysqli_query($db, $query);
    if (mysqli_num_rows($results) == 1) {
      $_SESSION['name'] = $username;
      $_SESSION['success'] = "You are now logged in";
      $_SESSION['customercount'] = getCustomerCount(1);
      $_SESSION['partnercount'] = getCustomerCount(5);
      header('location: homeAdmin.php');
    } else {
      array_push($errors, "Wrong username/password combination");
    }
  }
}

if (isset($_POST['adduser'])) {
  $fullname = mysqli_real_escape_string($db, $_POST['fullname']);
  $idtype = mysqli_real_escape_string($db, $_POST['usertype']);
  $address = mysqli_real_escape_string($db, $_POST['address']);
  $password = mysqli_real_escape_string($db, $_POST['password']);
  $birthdate = mysqli_real_escape_string($db, $_POST['birthdate']);
  $cnumber = mysqli_real_escape_string($db, $_POST['cnumber']);
  $email = mysqli_real_escape_string($db, $_POST['email']);

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
      $encryptpassword = md5($password);
      addnewcustomer($idtype, $fullname, $address, $cnumber, $email, $birthdate, "", $encryptpassword);
      updateCustomerCount(addCustomerCount($idtype), $idtype);
      header('location: homeAdmin.php');
    }
  } else {
    array_push($errors, "Customer already exists");
  }
}

if (isset($_POST['addpartner'])) {
  $compName = mysqli_real_escape_string($db, $_POST['compName']);
  $location1 = mysqli_real_escape_string($db, $_POST['location1']);
  $location2 = mysqli_real_escape_string($db, $_POST['location2']);
  $fullname = mysqli_real_escape_string($db, $_POST['fullname']);
  $password = mysqli_real_escape_string($db, $_POST['password']);
  $cnumber = mysqli_real_escape_string($db, $_POST['cnumber']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $idtype = mysqli_real_escape_string($db, $_POST['usertype']);

  if (empty($compName)) {
    array_push($errors, "Company name is required");
  }
  if (empty($location1)) {
    array_push($errors, "Address is required");
  }
  if (empty($location2)) {
    array_push($errors, "Address is required");
  }
  if (empty($fullname)) {
    array_push($errors, "fullname is required");
  }
  if (empty($password)) {
    array_push($errors, "Password is required");
  }
  if (empty($cnumber)) {
    array_push($errors, "Contact Number is required");
  }
  if (empty($email)) {
    array_push($errors, "Email is required");
  }

  if (uniqueCustomer($fullname, $email, $idtype)) {
    if (count($errors) == 0) {
      $encryptpassword = md5($password);
      addOrigin($idtype, $location2);
      addnewcustomer($idtype, $compName, $fullname, $cnumber, $email, $location1, $location2, $encryptpassword);
      updateCustomerCount(addCustomerCount($idtype), $idtype);
      header('location: homeAdminPartner.php');
    }
  } else {
    array_push($errors, "Partner's name already exists");
  }
}



if (isset($_POST['edituser'])) {
  $fullname = mysqli_real_escape_string($db, $_POST['fullname']);
  $id = mysqli_real_escape_string($db, $_POST['user_id']);
  $address = mysqli_real_escape_string($db, $_POST['address']);
  $password = mysqli_real_escape_string($db, $_POST['password']);
  $birthdate = mysqli_real_escape_string($db, $_POST['birthdate']);
  $cnumber = mysqli_real_escape_string($db, $_POST['cnumber']);
  $email = mysqli_real_escape_string($db, $_POST['email']);

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

  if (count($errors) == 0) {
    $encryptpassword = md5($password);
    updatecustomer($id, $fullname, $address, $cnumber, $email, $birthdate, $encryptpassword);
    header('location: homeAdmin.php');
  }
}

if (isset($_POST['editpartner'])) {
  $fullname = mysqli_real_escape_string($db, $_POST['fullname']);
  $id = mysqli_real_escape_string($db, $_POST['user_id']);
  $address = mysqli_real_escape_string($db, $_POST['address']);
  $password = mysqli_real_escape_string($db, $_POST['password']);
  $birthdate = mysqli_real_escape_string($db, $_POST['birthdate']);
  $cnumber = mysqli_real_escape_string($db, $_POST['cnumber']);
  $email = mysqli_real_escape_string($db, $_POST['email']);

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

  if (count($errors) == 0) {
    $encryptpassword = md5($password);
    updatecustomer($id, $fullname, $address, $cnumber, $email, $birthdate, $encryptpassword);
    header('location: homeAdmin.php');
  }
}

if (isset($_POST['deleteuser'])) {
  $id = mysqli_real_escape_string($db, $_POST['user_id']);
  $idtype = mysqli_real_escape_string($db, $_POST['usertype']);
  deleteUser($id, $idtype);
  updateCustomerCount(decreaseCustomerCount($idtype), $idtype);
  header('location: homeAdmin.php');
}

if (isset($_POST['deletepartner'])) {
  $id = mysqli_real_escape_string($db, $_POST['user_id']);
  $idtype = mysqli_real_escape_string($db, $_POST['usertype']);
  deleteUser($id, $idtype);
  updateCustomerCount(decreaseCustomerCount($idtype), $idtype);
  header('location: homeAdminPartner.php');
}

if (isset($_POST['deletepartnerpending'])) {
  $id = mysqli_real_escape_string($db, $_POST['user_idss']);
  $idtype = mysqli_real_escape_string($db, $_POST['usertypess']);
  deleteUser($id, $idtype);
  updateCustomerCount(decreaseCustomerCount($idtype), $idtype);
  header('location: homeAdminPending.php');
}

if (isset($_POST['addpendingpartnerss'])) {
  $id = mysqli_real_escape_string($db, $_POST['user_idss']);
  $idtype = mysqli_real_escape_string($db, $_POST['usertype']);
  addOrigin($id, getOrigin($id, $idtype));
  addPendingtoPartner($id);
  updateCustomerCount(decreaseCustomerCount($idtype), $idtype);
  updateCustomerCount(addCustomerCount(2), 2);
  deleteUser($id, $idtype);
  header('location: homeAdminPending.php');
  // echo $_POST['user_idss'];

}

if (isset($_POST['verifyallFile'])) {
  $compid = mysqli_real_escape_string($db, $_POST['compid']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $name = mysqli_real_escape_string($db, $_POST['name']);
  $mail = new PHPMailer(true);
  $mail->SMTPDebug = 0;
  $mail->isSMTP();
  $mail->Host = '';
  $mail->SMTPAuth = true;
  $mail->Username = '';
  $mail->Password = '';
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port = 587;
  $mail->setFrom('');
  $mail->addAddress($email, $name);
  $mail->isHTML(true);

  $mail->Subject = 'Business Permit verification';
  $mail->Body    = '<p>Your file has been verified</p><a href="https://omnibus-ph.000webhostapp.com/companyAdmin/login_page_comAdmin.php">CLICK ME</a>';

  $mail->send();

  verifyallCompanyFiles($compid);
  updateCompanyFiles($compid);
  $scode = "";
  $encrypted_id = base64_encode($compid . $scode);
  header('location: company_admin_info.php?i=' . $encrypted_id);
}

if (isset($_POST['verifyFile'])) {
  $compid = mysqli_real_escape_string($db, $_POST['compid']);
  $fileid = mysqli_real_escape_string($db, $_POST['fileid']);

  verifyCompanyFile($fileid, $compid);
  updateCompanyFiles($compid);
  $scode = "";
  $encrypted_id = base64_encode($compid . $scode);
  header('location: company_admin_info.php?i=' . $encrypted_id);
}


///////////////////////////////////////////    FUNCTIONS     /////////////////////////////////////////////////////

function addnewcustomer($type, $fullname, $address, $cnumber, $email, $birthdate, $location2, $password)
{
  $mysql = connect();
  if ($type == 1) {
    $mysql->query("INSERT INTO `user_customer` (`usertype`, `fullname`, `address`, `connumber`, `email`, `birthdate`, `password`) VALUES('$type', '$fullname', '$address', '$cnumber', '$email', '$birthdate', '$password')") or die($mysql->connect_error);
  }
  if ($type == 2) {
    $mysql->query("INSERT INTO `user_partner` (`usertype`, `companyName`, `fullname`, `connumber`, `email`, `address`, `cityAddress`, `password`) VALUES('$type', '$fullname', '$address', '$cnumber', '$email', '$birthdate', '$location2', '$password')") or die($mysql->connect_error);
  }
}

function addPendingtoPartner($id)
{
  $mysql = connect();
  $mysql->query("INSERT INTO `user_partner` SELECT * FROM `user_pending` WHERE `id` = '$id'");
}


function getOrigin($id, $idtype)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `cityAddress` FROM `user_pending` WHERE `id`='$id' AND `usertype`='$idtype' LIMIT 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

function addOrigin($id, $location2)
{
  $mysql = connect();
  if (checkOrigin($id, $location2) == 0) {
    $mysql->query("INSERT INTO `origin` (`partnerID`,`origin`) VALUES('$id','$location2')");
  }
}

function checkOrigin($id, $location2)
{
  $mysql = connect();
  $count = $mysql->query("SELECT COUNT(`origin`) FROM `origin` WHERE `origin`='$location2' LIMIT 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

function uniqueCustomer($fullname, $email, $idtype)
{
  $mysql = connect();
  if ($idtype == 1) {
    $count = $mysql->query("SELECT COUNT(`fullname`) FROM `user_customer` WHERE `fullname`='$fullname' AND `email='$email'") or die($mysql->connect_error);
    $result = array_values($count->fetch_assoc());
    if ($result[0] > 0)
      return false;
    else
      return true;
  }
  if ($idtype == 2) {
    $count = $mysql->query("SELECT COUNT(`fullname`) FROM `user_partner` WHERE `fullname`='$fullname' AND `email`='$email'") or die($mysql->connect_error);
    $result = array_values($count->fetch_assoc());
    if ($result[0] > 0)
      return false;
    else
      return true;
  }
}

function updatecustomer($id, $fullname, $address, $cnumber, $email, $birthdate, $password)
{
  $mysql = connect();
  $mysql->query("UPDATE `user_customer` SET `fullname`='$fullname', `address`='$address', `connumber`='$cnumber', `email`='$email', `birthdate`='$birthdate', `password`='$password' WHERE `id`='$id'") or die($mysql->connect_error);
}

function updateCustomerCount($count, $idtype)
{
  $mysql = connect();
  $mysql->query("UPDATE `users` SET `count`='$count' WHERE `id`='$idtype'") or die($mysql->connect_error);
}

function getCustomerCount($id)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `count` FROM `users` WHERE `id`='$id' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

function addCustomerCount($type)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `count` FROM `users` WHERE `id`='$type' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  $result[0] = $result[0] + 1;
  return $result[0];
}

function decreaseCustomerCount($idtype)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `count` FROM `users` WHERE `id`='$idtype' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  $result[0] = $result[0] - 1;
  return $result[0];
}

function deleteUser($id, $idtype)
{
  $mysql = connect();
  if ($idtype == 1) {
    $mysql->query("DELETE FROM `user_customer` WHERE `id`='$id'") or die($mysql->connect_error);
  } else if ($idtype == 2) {
    $mysql->query("DELETE FROM `user_partner` WHERE `id`='$id'") or die($mysql->connect_error);
  } else if ($idtype == 4) {
    $mysql->query("DELETE FROM `user_pending` WHERE `id`='$id'") or die($mysql->connect_error);
  }
}

function verifyallCompanyFiles($compid)
{
  $mysql = connect();
  $mysql->query("UPDATE `company_files` SET `verified`= 1 WHERE `companyID`='$compid'") or die($mysql->connect_error);
}

function verifyCompanyFile($fileid, $compid)
{
  $mysql = connect();
  $mysql->query("UPDATE `company_files` SET `verified`= 1 WHERE `id`='$fileid' AND `companyID`='$compid'") or die($mysql->connect_error);
}

function updateCompanyFiles($compid)
{
  $mysql = connect();
  $mysql->query("UPDATE `user_partner_admin` SET `file_verified`= 1 WHERE `id`='$compid'") or die($mysql->connect_error);
}
