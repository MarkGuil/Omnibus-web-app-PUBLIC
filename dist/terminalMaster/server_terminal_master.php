<?php
// include 'model.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';
session_start();

$errors = array();

//login
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
    $encryptpassword = md5($password);
    $query = "SELECT * FROM user_partner_tmaster WHERE email='$email' AND password='$encryptpassword'";
    $results = mysqli_query($db, $query);
    if (mysqli_num_rows($results) == 1) {
      if (getVerified($email) == NULL) {
        $_SESSION['compTerMassuccess'] = "Your email is still not verified. Please check your email for the verification code";
        $_SESSION['logged'] = 0;
        header('location: email_verification.php?email=' . $email);
      } else {
        $_SESSION['compTerMasemail'] = $email;
        $_SESSION['compadminName'] = getcompNameId($email);
        $_SESSION['compadminNameID'] = getcompNameId($email);
        $_SESSION['terminalID'] = getterminalId($email);
        $_SESSION['compTerMasID'] = getTerMasID($email);
        $_SESSION['compTerMasVerify'] = getVerified($email);
        $_SESSION['compTerMassuccess'] = "You are now logged in";
        // echo getcompNameId($email);
        header('location: starting_home.php');
      }
    } else {
      array_push($errors, "Wrong email/password combination");
    }
  }
}

//password edit
if (isset($_POST["change_password"])) {
  $email = $_SESSION['compTerMasemail'];
  $opassword = mysqli_real_escape_string($db, $_POST['oldpassword']);
  $npassword = mysqli_real_escape_string($db, $_POST['password1']);
  $id = $_SESSION['compTerMasID'];

  if (empty($opassword)) {
    array_push($errors, "Please enter your current password");
  }
  if (empty($npassword)) {
    array_push($errors, "New password is required");
  }
  if ($opassword != $npassword) {
    if (count($errors) == 0) {
      $encryptopassword = md5($opassword);
      if (password_match($id, $encryptopassword) == 1) {
        $encryptnpassword = md5($npassword);
        updatepassword($id, $encryptnpassword);
        $_SESSION['compTerMassuccess'] = "Your password has been changed successfully.";
        header('location: starting_home.php');
      } else {
        array_push($errors, "Old password is wrong. Please try again.");
      }
    }
  } else array_push($errors, "New password cannot be the same as the old password");
}

function password_match($id, $opassword)
{
  $mysql = connect();
  $count = $mysql->query("SELECT COUNT(fullname) FROM user_partner_tmaster WHERE id='$id' AND password = '$opassword' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

function updatepassword($id, $encryptnpassword)
{
  $mysql = connect();
  $mysql->query("UPDATE user_partner_tmaster SET password='$encryptnpassword' WHERE id = '$id'") or die($mysql->connect_error);
}

//change detail
// - name and address
if (isset($_POST["company_details_change"])) {

  $terName = mysqli_real_escape_string($db, $_POST['terName']);
  $saddress = mysqli_real_escape_string($db, $_POST['saddress']);
  $city = mysqli_real_escape_string($db, $_POST['city']);
  $province = mysqli_real_escape_string($db, $_POST['province']);
  $id = $_SESSION['compTerMasID'];

  if (empty($terName)) {
    array_push($errors, "Name is required");
  }
  if (empty($saddress)) {
    array_push($errors, "Address is required");
  }

  if (count($errors) == 0) {
    updateAccountDetails($id, $terName, $saddress, $city, $province);
    $_SESSION['compTerMassuccess'] = "Terminal Master Name/ Address has been change successfully ";
    header('location: starting_home.php');
  }
}

function updateAccountDetails($id, $terName, $saddress, $city, $province)
{
  $mysql = connect();
  $mysql->query("UPDATE user_partner_tmaster SET fullname='$terName', `street_address`='$saddress', `city`='$city', `province`='$province' WHERE id = '$id'") or die($mysql->connect_error);
}

// - email and contact details
// -- email
if (isset($_POST["verify_email"])) {
  $email = $_POST["email"];
  $verification_code = $_POST["verification_code"];

  $sql = "UPDATE user_partner_tmaster SET email_verified_at = NOW() WHERE email = '" . $email . "' AND verification_code = '" . $verification_code . "'";
  $result  = mysqli_query($db, $sql);

  if (mysqli_affected_rows($db) == 0) {
    array_push($errors, "Verification code failed.");
  }
  $_SESSION['compTerMassuccess'] = "Your email has been verified. Try logging in again";
  header('location: login_page.php');
  exit();
}

if (isset($_POST["email_change"])) {
  $cemail = $_SESSION['compTerMasemail'];
  $nemail = mysqli_real_escape_string($db, $_POST['email']);
  $id = $_SESSION['compTerMasID'];

  $mail = new PHPMailer(true);

  if (empty($email)) {
    array_push($errors, "Email Address is required");
  }
  if ($nemail != $cemail) {
    if (uniqueEmail($nemail)) {
      // if (count($errors) == 0) {
      $mail->SMTPDebug = 0;
      $mail->isSMTP();
      $mail->Host = '';
      $mail->SMTPAuth = true;
      $mail->Username = '';
      $mail->Password = '';
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = 587;
      $mail->setFrom('');
      $mail->addAddress($nemail);
      $mail->isHTML(true);

      $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

      $mail->Subject = 'Email verification';
      $mail->Body    = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';

      $mail->send();
      updateAccountEmail($id, $nemail, $verification_code);
      unset($_SESSION['compadminVerify']);
      $_SESSION['logged'] = 0;
      $_SESSION['compTerMassuccess'] = "Email has been change successfully. Please verify the email. An email with a verification code was just sent to " . $nemail;
      header('location: email_verification.php?email=' . $nemail);
      // }
    } else {
      array_push($errors, "Email Address already exists");
    }
  }
}

function uniqueEmail($email)
{
  $mysql = connect();
  $count = $mysql->query("SELECT COUNT(fullname) FROM user_partner_tmaster WHERE email='$email'") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  if ($result[0] > 0)
    return false;
  else
    return true;
}

function updateAccountEmail($id, $email, $verification_code)
{
  $mysql = connect();
  $mysql->query("UPDATE user_partner_tmaster SET email='$email', verification_code='$verification_code', email_verified_at=null WHERE id = '$id'") or die($mysql->connect_error);
}

// -- change email
if (isset($_POST["verify_new_email"])) {
  $email = $_POST["email"];
  $verification_code = mysqli_real_escape_string($db, $_POST['verification_code']);
  $id = $_SESSION['compTerMasID'];

  if (empty($email)) {
    array_push($errors, "Email is required");
  }
  if (empty($verification_code)) {
    array_push($errors, "Verification code is required");
  }

  if (count($errors) == 0) {
    updateVerificationCode($id, $verification_code, $email);
    $_SESSION['compTerMasVerify'] = getVerified($email);
    $_SESSION['compTerMassuccess'] = "Your email has been verified. Please login using your new email.";
    header('location: starting_home.php');
  }
}

function updateVerificationCode($id, $verification_code, $email)
{
  $mysql = connect();
  $mysql->query("UPDATE user_partner_tmaster SET email_verified_at = NOW() WHERE id = '$id' AND  email = '$email' AND verification_code = '$verification_code'") or die($mysql->connect_error);
}

function getVerified($email)
{
  $mysql = connect();
  $count = $mysql->query("SELECT email_verified_at FROM user_partner_tmaster WHERE email='$email' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

// --change contact number
if (isset($_POST["contact_number_change"])) {
  $email = $_SESSION['compTerMasemail'];
  $cnumber = mysqli_real_escape_string($db, $_POST['cnumber']);
  $id = $_SESSION['compTerMasID'];

  if (empty($email)) {
    array_push($errors, "Email is required");
  }
  if (empty($cnumber)) {
    array_push($errors, "Contact Number is required");
  }

  if (count($errors) == 0) {
    updateContactNumber($id, $cnumber, $email);
    $_SESSION['compTerMassuccess'] = "Contact Number has been changed successfully.";
    header('location: starting_home.php');
  }
}

function updateContactNumber($id, $cnumber, $email)
{
  $mysql = connect();
  $mysql->query("UPDATE user_partner_tmaster SET connumber='$cnumber' WHERE id = '$id' AND email = '$email'") or die($mysql->connect_error);
}
//////////////

if (isset($_GET["unsetSuccessAlert"])) {
  unset($_SESSION['compTerMassuccess']);
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

function checkguides($id)
{
  $mysql = connect();
  $count = $mysql->query("SELECT COUNT(`companyID`) FROM `guidelines` WHERE `companyID`='$id'") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

/*****************************************************************/


if (isset($_POST['add_bus_trip'])) {
  $compid = $_SESSION['compadminNameID'];
  $terminalid = $_SESSION['terminalID'];
  $terminalmasterid = $_SESSION['compTerMasID'];
  $busid = mysqli_real_escape_string($db, $_POST['optionBus']);
  $driverid = mysqli_real_escape_string($db, $_POST['optionbusdriver']);
  $conductorid = mysqli_real_escape_string($db, $_POST['optionbusconductor']);
  $routeid = mysqli_real_escape_string($db, $_POST['optionTrip']);
  $tripid = mysqli_real_escape_string($db, $_POST['optionTripTime']);
  $date = mysqli_real_escape_string($db, $_POST['operateFrom']);
  $fare = mysqli_real_escape_string($db, $_POST['fare']);
  $recur = $_POST['recur'];

  if (empty($busid)) {
    array_push($errors, "Bus is required. you cannot leave this blank");
  }
  if (empty($driverid)) {
    array_push($errors, "Driver is required. you cannot leave this blank");
  }
  if (empty($conductorid)) {
    array_push($errors, "Conductor is required. you cannot leave this blank");
  }
  if (empty($routeid)) {
    array_push($errors, "Route is required. you cannot leave this blank");
  }
  if (empty($tripid)) {
    array_push($errors, "Trip is required. you cannot leave this blank");
  }
  if (empty($date)) {
    array_push($errors, "Start date is required. you cannot leave this blank");
  }
  if (empty($fare)) {
    array_push($errors, "Fare is required. you cannot leave this blank");
  }

  if (count($errors) == 0) {
    $totalseat = get_total_seat($busid, $compid);
    // $assigned_by = get_terminalmaster_name($nameAssigned,$compid,$terminalid);
    $assigned_by = getTerminalMasterName($terminalmasterid);
    $str_arr = explode(" - ", $date);

    $begin = new DateTime($str_arr[0]);
    $end   = new DateTime($str_arr[1]);
    $operatesF = $begin->format("Y-m-d");
    $operatesE = $end->format("Y-m-d");
    for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
      $date = $i->format("Y-m-d");
      $day = $i->format("l");
      $N = count($recur);
      for ($j = 0; $j < $N; $j++) {
        if ($recur[$j] == $day) {
          if (checkBusTrip($tripid, $date, $routeid, $busid, $compid, $terminalid)) {
            addBusTrip($date, $operatesF, $operatesE, $tripid, $routeid, $busid, $compid, $terminalid, $driverid, $conductorid, $fare, $totalseat, $assigned_by);
          }
        }
      }
    }
    $_SESSION['compTerMassuccess'] = "Bus trip has been successfully added ";
    header('location: bus_trip_view.php');
  }
}



function addBusTrip($date, $operatesF, $operatesE, $tripid, $routeid, $busid, $compid, $terminalid, $driverid, $conductorid, $fare, $totalseat, $assigned_by)
{
  $mysql = connect();
  $mysql->query("INSERT INTO `bus_trip`(`companyID`,`assigned_by`,`trip_date`, `operates_from`, `operates_to`, `fare`, `total_seat`, `driverID`, `conductorID`, `tripID`, `routeID`, `busID`, `terminalID`)  VALUES('$compid', '$assigned_by', '$date','$operatesF', '$operatesE', '$fare', '$totalseat', '$driverid', '$conductorid', '$tripid', '$routeid', '$busid','$terminalid')") or die($mysql->connect_error);
}

function checkBusTrip($tripid, $date, $routeid, $busid, $compid, $terminalid)
{
  $mysql = connect();
  $count = $mysql->query("SELECT COUNT(`id`) FROM `bus_trip` WHERE `companyID`='$compid' AND `trip_date`='$date' AND `tripID`='$tripid' AND `routeID`='$routeid' AND `busID`='$busid' AND `terminalID`='$terminalid'") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  if ($result[0] > 0)
    return false;
  else
    return true;
}

function get_total_seat($busid, $compid)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `total_seat` FROM `buses` WHERE `id`='$busid' AND `companyID`='$compid' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

/*****************************************************************/

if (isset($_POST['add_new_trip'])) {
  $compid = $_SESSION['compadminNameID'];
  $terminalid = $_SESSION['terminalID'];
  $terminalmasterid = $_SESSION['compTerMasID'];
  $routeid = mysqli_real_escape_string($db, $_POST['optionRoute']);
  $origin = mysqli_real_escape_string($db, $_POST['thisorigin']);
  $destination = mysqli_real_escape_string($db, $_POST['thisdestination']);

  $number = count($_POST["departure"]);

  if (empty($routeid)) {
    array_push($errors, "Route is required. you cannot leave this blank");
  }


  if (count($errors) == 0) {
    $assigned_by = getTerminalMasterName($terminalmasterid);
    $duration = getRouteDuration($routeid, $compid);
    if ($number > 0) {
      for ($i = 0; $i < $number; $i++) {
        if (trim($_POST["departure"][$i] != '')) {
          $departure = mysqli_real_escape_string($db, $_POST["departure"][$i]);
          addonenewtrip($origin, $destination, $duration, $departure, $routeid, $compid, $terminalid, $assigned_by);
        }
      }
      $_SESSION['compTerMassuccess'] = "Trip has been updated";
      header('location: terminal_bus.php');
    } else {
      header('location: home_admin_trips.php');
    }
  }
}


function getRouteDuration($routeid, $compid)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `duration` FROM `routes` WHERE `id`='$routeid' AND `companyID`='$compid' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

function addonenewtrip($route1, $route2, $duration, $departure, $routeid, $compid, $terminalid, $assigned_by)
{
  $mysql = connect();
  $mysql->query("INSERT INTO `trip` (`origin`, `destination`, `duration`, `departure_time`, `assigned_by`,`companyID`,`terminalID`,`routeID`) VALUES('$route1', '$route2', '$duration', '$departure', '$assigned_by', '$compid', '$terminalid', '$routeid')") or die($mysql->connect_error);
}

function getTerminalMasterName($terminalmasterid)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `fullname` FROM `user_partner_tmaster` WHERE `id`='$terminalmasterid' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

/*****************************************************************/

if (isset($_POST['conbook'])) {
  $compid = $_SESSION['compadminNameID'];
  $bookID = mysqli_real_escape_string($db, $_POST['conbook']);
  confirmBook($compid, $bookID);
  header('location: booking_details.php');
}

if (isset($_POST['travelled'])) {
  $compid = $_SESSION['compadminNameID'];
  $bookID = mysqli_real_escape_string($db, $_POST['travelled']);
  travelledBook($compid, $bookID);
  header('location: booking_details.php');
}

if (isset($_POST['canbook'])) {
  $compid = $_SESSION['compadminNameID'];
  $bookID = mysqli_real_escape_string($db, $_POST['canbook']);
  $bustripID = mysqli_real_escape_string($db, $_POST['bustripID']);
  $taken_seat = mysqli_real_escape_string($db, $_POST['taken_seat']);
  $totalSeats = mysqli_real_escape_string($db, $_POST['totalSeats']);
  $new = $taken_seat - $totalSeats;
  cancelBook($compid, $bookID);
  updateBusTripTakenSeat($new, $bustripID);
  header('location: booking_details.php');
}

function confirmBook($compid, $bookID)
{
  $mysql = connect();
  $mysql->query("UPDATE `booking_tbl` SET `booking_status`=1 WHERE `id`='$bookID' AND `companyID`='$compid'") or die($mysql->connect_error);
}

function cancelBook($compid, $bookID)
{
  $mysql = connect();
  $mysql->query("UPDATE `booking_tbl` SET `booking_status`='cancelled' WHERE `id`='$bookID' AND `companyID`='$compid'") or die($mysql->connect_error);
}

function updateBusTripTakenSeat($new, $bustripID)
{
  $mysql = connect();
  $mysql->query("UPDATE `bus_trip` SET `taken_seat`= '$new' WHERE `id`='$bustripID' ") or die($mysql->connect_error);
}

function travelledBook($compid, $bookID)
{
  $mysql = connect();
  $mysql->query("UPDATE `booking_tbl` SET `booking_status`='travelled' WHERE `id`='$bookID' AND `companyID`='$compid'") or die($mysql->connect_error);
}
