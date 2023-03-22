<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

if (session_id() == '') {
  session_start();
}

// STEP 1: read POST data
// Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
// Instead, read raw POST data from the input stream.

$raw_post_data = file_get_contents('php://input');


$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
  $keyval = explode('=', $keyval);
  if (count($keyval) == 2)
    $myPost[$keyval[0]] = urldecode($keyval[1]);
}

// read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'

$req = 'cmd=_notify-validate';
if (function_exists('get_magic_quotes_gpc')) {
  $get_magic_quotes_exists = true;
}
foreach ($myPost as $key => $value) {
  if ($get_magic_quotes_exists == true && 'get_magic_quotes_gpc()' == 1) {
    $value = urlencode(stripslashes($value));
  } else {
    $value = urlencode($value);
  }
  $req .= "&$key=$value";
}


// Step 2: POST IPN data back to PayPal to validate
$ch = curl_init('https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close', 'User-Agent: omnibus'));
// In wamp-like environments that do not come bundled with root authority certificates,
// please download 'cacert.pem' from "https://curl.haxx.se/docs/caextract.html" and set
// the directory path of the certificate as shown below:
// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
if (!($res = curl_exec($ch))) {
  // error_log("Got " . curl_error($ch) . " when processing IPN data");

  curl_close($ch);
  exit;
}
curl_close($ch);


// inspect IPN validation result and act accordingly
if (strcmp($res, "VERIFIED") == 0) {
  // The IPN is verified, process it:
  // check whether the payment_status is Completed
  // check that txn_id has not been previously processed
  // check that receiver_email is your Primary PayPal email
  // check that payment_amount/payment_currency are correct
  // process the notification
  // assign posted variables to local variables
  $item_name = $_POST['item_name'];
  $item_number = $_POST['item_number'];
  $payment_status = $_POST['payment_status'];
  $payment_amount = $_POST['mc_gross'];
  $payment_currency = $_POST['mc_currency'];
  $txn_id = $_POST['txn_id'];
  $receiver_email = $_POST['receiver_email'];
  $payer_email = $_POST['payer_email'];
  // IPN message values depend upon the type of notification sent.

  $mail = new PHPMailer(true);

  $mail->SMTPDebug = 0;
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'startupomnibus@gmail.com';
  $mail->Password = 'qcnukqyzrcvalwon';
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port = 587;
  $mail->setFrom('startupomnibus@gmail.com');
  $mail->addAddress($payer_email, 'Customer');
  $mail->isHTML(true);

  $mail->Subject = 'Payment Successful';
  $mail->Body    = '<p>Your Payment was successful, Thank you for booking!</p>';

  $mail->send();

  if (isset($_GET['booking_id'])) {
    $bookingID = $_GET['booking_id'];
    updatePaymentStatus($bookingID, $payer_email);
  }
} else if (strcmp($res, "INVALID") == 0) {
  // IPN invalid, log for manual investigation

  echo "The response from IPN was: <b>" . $res . "</b>";
}


function connect()
{
  $mysql = new mysqli('localhost', 'id18187063_omnibus', '^4(-$[Fc>CDh=cN3', 'id18187063_omnibus_db');
  return $mysql;
}

function updatePaymentStatus($bookingID, $payer_email)
{
  $mysql = connect();
  $mysql->query("UPDATE `booking_tbl` SET `payment_status`='paid', `payer_email`='$payer_email', `payment_used`='PayPal' WHERE `id`='$bookingID'") or die($mysql->connect_error);
}
