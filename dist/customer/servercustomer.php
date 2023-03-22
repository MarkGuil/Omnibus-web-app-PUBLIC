<?php
// include 'model.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

session_start();

$errors = array();
$firstname = array(
  'Johnathon', 'Anthony', 'Erasmo', 'Raleigh', 'Nancie', 'Tama', 'Camellia', 'Augustine', 'Christeen', 'Luz', 'Diego', 'Lyndia', 'Thomas', 'Georgianna',
  'Leigha', 'Alejandro', 'Marquis', 'Joan', 'Stephania', 'Elroy', 'Zonia', 'Buffy', 'Sharie', 'Blythe', 'Gaylene', 'Elida', 'Randy', 'Margarete',
  'Margarett', 'Dion', 'Tomi', 'Arden', 'Clora', 'Laine', 'Becki', 'Margherita', 'Bong', 'Jeanice', 'Qiana', 'Lawanda', 'Rebecka', 'Maribel',
  'Tami', 'Yuri', 'Michele', 'Rubi', 'Larisa', 'Lloyd', 'Tyisha', 'Samatha',
);

$lastname = array(
  'Mischke', 'Serna', 'Pingree', 'Mcnaught', 'Pepper', 'Schildgen', 'Mongold', 'Wrona', 'Geddes', 'Lanz', 'Fetzer', 'Schroeder', 'Block',
  'Mayoral', 'Fleishman', 'Roberie', 'Latson', 'Lupo', 'Motsinger', 'Drews', 'Coby', 'Redner', 'Culton', 'Howe', 'Stoval', 'Michaud', 'Mote',
  'Menjivar', 'Wiers', 'Paris', 'Grisby', 'Noren', 'Damron', 'Kazmierczak', 'Haslett', 'Guillemette', 'Buresh', 'Center', 'Kucera', 'Catt',
  'Badon', 'Grumbles', 'Antes', 'Byron', 'Volkman', 'Klemp', 'Pekar', 'Pecora', 'Schewe', 'Ramage',
);

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
    $query = "SELECT * FROM user_customer WHERE email='$email' AND password='$password'";
    $results = mysqli_query($db, $query);
    if (mysqli_num_rows($results) == 1) {
      $user = mysqli_fetch_object($results);
      if ($user->email_verified_at == null) {
        // die("Please verify your email <a href='email-verification.php?email=" . $email . "'>from here</a>");
        header('location: email-verification.php?email=' . $email);
      } else {
        $_SESSION['customername'] = getCustomerName($email, $password);
        $_SESSION['customerid'] = getCustomerID($email, $password);
        $_SESSION['customersuccess'] = "You are now logged in";
        header('location: registeredHome.php');
      }
    } else {
      array_push($errors, "Wrong username/password combination");
    }
  }
}

if (isset($_POST['login_demo'])) {
  $role = 1;
  if (!empty($_POST['email'])) {
    $email = mysqli_real_escape_string($db, $_POST['email']);
  }

  if (count($errors) == 0) {
    $_SESSION['customername'] = 'Demo';
    $_SESSION['customerid'] = 'demo_account';
    $_SESSION['customermobile'] = 'active_mobile';
    $_SESSION['customersuccess'] = "You are now logged in";
    header('location: ../customer/registeredHome.php');
  }
}

if (isset($_POST["verify_email"])) {
  $email = $_POST["email"];
  $verification_code = $_POST["verification_code"];

  $sql = "UPDATE `user_customer` SET `email_verified_at` = NOW() WHERE `email` = '" . $email . "' AND `verification_code` = '" . $verification_code . "'";
  $result  = mysqli_query($db, $sql);

  if (mysqli_affected_rows($db) == 0) {
    die("Verification code failed.");
  }

  header('location: registeredHome.php');
  exit();
}

if (isset($_POST['editprofile'])) {
  $username = mysqli_real_escape_string($db, $_POST['fullname']);
  $address = mysqli_real_escape_string($db, $_POST['address']);
  $birthdate = mysqli_real_escape_string($db, $_POST['birthdate']);
  $custid = $_SESSION['customerid'];

  if (empty($username)) {
    array_push($errors, "Username is required");
  }
  if (empty($address)) {
    array_push($errors, "Address is required");
  }
  if (empty($birthdate)) {
    array_push($errors, "Birthdate is required");
  }

  if (count($errors) == 0) {
    updateCustomerDetails($username, $address, $birthdate, $custid);
    $_SESSION['customername'] = $username;
    header('location: registeredProfile.php');
  }
}

if (isset($_POST['changeContactNumber'])) {
  $cnumber = mysqli_real_escape_string($db, $_POST['cnumber']);
  $custid = $_SESSION['customerid'];

  if (empty($cnumber)) {
    array_push($errors, "Contact Number is required");
  }

  updateMobileNumber($cnumber, $custid);
  header('location: registeredProfile.php');
}

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
    $query = "SELECT * FROM user_customer WHERE id='$custid' AND password='$cpassword'";
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

if (isset($_POST['searchBusTrip'])) {
  $origin = mysqli_real_escape_string($db, $_POST['pointA']);
  $dest = mysqli_real_escape_string($db, $_POST['pointB']);
  $date = mysqli_real_escape_string($db, $_POST['date']);
  $passenger = mysqli_real_escape_string($db, $_POST['numberofpassenger']);

  if (empty($origin)) {
    array_push($errors, "origin is required");
  }
  if (empty($dest)) {
    array_push($errors, "destination is required");
  }
  if (empty($date)) {
    array_push($errors, "date is required");
  }
  if (empty($passenger)) {
    array_push($errors, "Enter number of passenger");
  }

  $day = date('l', strtotime($date));

  if (count($errors) == 0) {

    $_SESSION['selectedorigin'] = $origin;
    $_SESSION['selecteddestination'] = $dest;
    $_SESSION['selecteddate'] = $date;
    $_SESSION['selectedday'] = $day;
    $_SESSION['selectednumofpassenger'] = $passenger;
    header('location: availableTrips.php');
  }
}

if (isset($_POST['confirmTrip'])) {
  $tripid = mysqli_real_escape_string($db, $_POST['tripsid']);
  $busid = mysqli_real_escape_string($db, $_POST['busid']);
  $compid = mysqli_real_escape_string($db, $_POST['compid']);
  $tripschedid = mysqli_real_escape_string($db, $_POST['tripschedid']);
  $fare = mysqli_real_escape_string($db, $_POST['tripfare']);
  $terminalid = mysqli_real_escape_string($db, $_POST['terminalid']);
  $duration = mysqli_real_escape_string($db, $_POST['duration']);
  $departure_time = mysqli_real_escape_string($db, $_POST['departure_time']);

  if (empty($tripid)) {
    array_push($errors, "origin is required");
  }
  if (empty($compid)) {
    array_push($errors, "destination is required");
  }
  if (empty($tripschedid)) {
    array_push($errors, "date is required");
  }

  if (count($errors) == 0) {

    $_SESSION['selectedtripid'] = $tripid;
    $_SESSION['selectedbusid'] = $busid;
    $_SESSION['selectedcompid'] = $compid;
    $_SESSION['selectedtsid'] = $tripschedid;
    $_SESSION['selectedfare'] = $fare;
    $_SESSION['selectedterminalid'] = $terminalid;
    $_SESSION['selectedduration'] = $duration;
    $_SESSION['selecteddeparture_time'] = $departure_time;
    header('location: confirmTrip.php');
  }
}

if (isset($_POST['confirmSeat'])) {
  $seatid = mysqli_real_escape_string($db, $_POST['seatID']);
  $max = mysqli_real_escape_string($db, $_POST['maxxs']);

  if (empty($seatid)) {
    array_push($errors, "Seat Number is required");
  }

  if (count($errors) == 0) {
    $pieces = explode(",", $seatid);
    if ($max == count($pieces)) {
      $_SESSION['selectedseatid'] = $seatid;
      header('location: fillup_Form.php');
    } else {
      array_push($errors, "Please select the correct amount of seat");
    }
  }
}

if (isset($_POST['confirmPassengerDetails'])) {
  $customerid = $_SESSION['customerid'];
  $numofpassenger = $_SESSION['selectednumofpassenger'];
  $terminalid = $_SESSION['selectedterminalid'];
  $companypid = $_SESSION['selectedcompid'];
  $tripid = $_SESSION['selectedtripid'];
  $sdate = $_SESSION['selecteddate'];
  $fare = $_SESSION['selectedfare'];

  $sorigin = $_SESSION['selectedorigin'];
  $sdest = $_SESSION['selecteddestination'];
  $sduration = $_SESSION['selectedduration'];
  $stime = $_SESSION['selecteddeparture_time'];
  $sdate = $_SESSION['selecteddate'];

  $totalAmount  = $fare * $numofpassenger;
  $totalFiles = $numofpassenger * 3;

  $totalvalidid = 0;
  $totalvacccard = 0;
  $totalspass = 0;
  $valid_file_count = 0;

  for ($i = 0; $i < $numofpassenger; $i++) {
    $bn = "busnumber" . $i;
    $fn = "fname" . $i;
    $ln = "lname" . $i;
    $age = "age" . $i;
    $filee1 = "files1" . $i;
    $filee2 = "files2" . $i;
    $filee3 = "files3" . $i;
    $genders = "gender" . $i;

    $seat_number = mysqli_real_escape_string($db, $_POST[$bn]);
    $fname = mysqli_real_escape_string($db, $_POST[$fn]);
    $lname = mysqli_real_escape_string($db, $_POST[$ln]);
    $age = mysqli_real_escape_string($db, $_POST[$age]);
    $gender = mysqli_real_escape_string($db, $_POST[$genders]);



    if (empty($seat_number)) {
      array_push($errors, "seat number is required");
    }
    if (empty($fname)) {
      array_push($errors, "First Name is required");
    }
    if (empty($lname)) {
      array_push($errors, "Last name is required");
    }
    if (empty($age)) {
      array_push($errors, "Age is required");
    }
    if (empty($gender)) {
      array_push($errors, "Gender is required");
    }

    if (trim($_FILES[$filee1]['name']) != '') {
      $filename1 = $_FILES[$filee1]['name'];
      $destination1 = 'uploads/' . $filename1;
      $extention1 = pathinfo($filename1, PATHINFO_EXTENSION);
      $file1 = $_FILES[$filee1]['tmp_name'];
      $size1 = $_FILES[$filee1]['size'];
      $actual_name1 = pathinfo($filename1, PATHINFO_FILENAME);
      $original_name1 = $actual_name1;
      $extensions1 = pathinfo($filename1, PATHINFO_EXTENSION);

      $filename2 = $_FILES[$filee2]['name'];
      $destination2 = 'uploads/' . $filename2;
      $extention2 = pathinfo($filename2, PATHINFO_EXTENSION);
      $file2 = $_FILES[$filee2]['tmp_name'];
      $size2 = $_FILES[$filee2]['size'];
      $actual_name2 = pathinfo($filename2, PATHINFO_FILENAME);
      $original_name2 = $actual_name2;
      $extensions2 = pathinfo($filename2, PATHINFO_EXTENSION);

      $filename3 = $_FILES[$filee3]['name'];
      $destination3 = 'uploads/' . $filename3;
      $extention3 = pathinfo($filename3, PATHINFO_EXTENSION);
      $file3 = $_FILES[$filee3]['tmp_name'];
      $size3 = $_FILES[$filee3]['size'];
      $actual_name3 = pathinfo($filename3, PATHINFO_FILENAME);
      $original_name3 = $actual_name3;
      $extensions3 = pathinfo($filename3, PATHINFO_EXTENSION);

      list($res1, $new_name1, $new_destination1) = checkValidID($actual_name1, $original_name1, $extensions1, $extention1, $size1);
      list($res2, $new_name2, $new_destination2) = checkValidID($actual_name2, $original_name2, $extensions2, $extention2, $size2);
      list($res3, $new_name3, $new_destination3) = checkValidID($actual_name3, $original_name3, $extensions3, $extention3, $size3);
      if ($res1 == -1 || $res2 == -1 || $res3 == -1) {
        array_push($errors, "Your file extention must be .jpeg .jpg .png or .pdf");
      } else if ($res1 == -2 || $res2 == -2 || $res3 == -2) {
        array_push($errors, "Your file is too large");
      } else {
        $totalvalidid = $totalvalidid + $res1;
        $totalvacccard = $totalvacccard + $res2;
        $totalspass = $totalspass + $res3;

        $valid_file_count = $totalvalidid + $totalvacccard + $totalspass;
      }
    }
  }

  $last_id = '';
  $mail = new PHPMailer(true);
  if ($valid_file_count == $totalFiles) {
    $random_number = substr(number_format(time() * rand(), 0, '', ''), 0, 10);
    $reference = hash('crc32', $random_number);

    $sql = "INSERT INTO booking_tbl (companyID, terminalID, bustripID, customerID, origin, destination, duration, departure_time, number_of_seats, fare_amount, total_amount, payment_status, booked_at, reference_id)
    VALUES ('" . $companypid . "', '" . $terminalid . "','" . $tripid . "', '" . $customerid . "', '" . $sorigin . "','" . $sdest . "', '" . $sduration . "', '" . $stime . "','" . $numofpassenger . "','" . $fare . "','" . $totalAmount . "','not paid','" . date("Y/m/d") . "','" . $reference . "')";
    // $last_id = '';
    if (mysqli_query($db, $sql)) {
      $last_id = mysqli_insert_id($db);
    } else {
      // echo "Error: " . $sql . "<br>" . mysqli_error($db);
    }

    $fullname1 = $_SESSION['customername'];
    $custid = $_SESSION['customerid'];
    $email1 = getCustomerEmail($custid, $fullname1);
    $subject1 = 'Booking successful';
    $body1 = '<p>You have successfully booked a trip. Trip date: ' . $stime . '</p>';

    list($fullname2, $email2) = getCompanyDetail($companypid);
    $subject2 = 'Booking successful';
    $body2 = '<p>Customer has successfully booked a trip from your Company. Booking reference ID: ' . $reference . '</p>';

    // 
    sendthisEmail($email1, $fullname1, $subject1, $body1);
    sendthisEmail($email2, $fullname2, $subject2, $body2);

    $new_number = getBusTakenSeat($tripid) + $numofpassenger;
    updateBusTakenSeat($tripid, $new_number);

    for ($i = 0; $i < $numofpassenger; $i++) {
      $bn = "busnumber" . $i;
      $fn = "fname" . $i;
      $ln = "lname" . $i;
      $age = "age" . $i;
      $filee1 = "files1" . $i;
      $filee2 = "files2" . $i;
      $filee3 = "files3" . $i;
      $genders = "gender" . $i;

      $seat_number = mysqli_real_escape_string($db, $_POST[$bn]);
      $fname = mysqli_real_escape_string($db, $_POST[$fn]);
      $lname = mysqli_real_escape_string($db, $_POST[$ln]);
      $age = mysqli_real_escape_string($db, $_POST[$age]);
      $gender = mysqli_real_escape_string($db, $_POST[$genders]);

      $filename1 = $_FILES[$filee1]['name'];
      $destination1 = 'uploads/' . $filename1;
      $extention1 = pathinfo($filename1, PATHINFO_EXTENSION);
      $file1 = $_FILES[$filee1]['tmp_name'];
      $size1 = $_FILES[$filee1]['size'];
      $actual_name1 = pathinfo($filename1, PATHINFO_FILENAME);
      $original_name1 = $actual_name1;
      $extensions1 = pathinfo($filename1, PATHINFO_EXTENSION);

      $filename2 = $_FILES[$filee2]['name'];
      $destination2 = 'uploads/' . $filename2;
      $extention2 = pathinfo($filename2, PATHINFO_EXTENSION);
      $file2 = $_FILES[$filee2]['tmp_name'];
      $size2 = $_FILES[$filee2]['size'];
      $actual_name2 = pathinfo($filename2, PATHINFO_FILENAME);
      $original_name2 = $actual_name2;
      $extensions2 = pathinfo($filename2, PATHINFO_EXTENSION);

      $filename3 = $_FILES[$filee3]['name'];
      $destination3 = 'uploads/' . $filename3;
      $extention3 = pathinfo($filename3, PATHINFO_EXTENSION);
      $file3 = $_FILES[$filee3]['tmp_name'];
      $size3 = $_FILES[$filee3]['size'];
      $actual_name3 = pathinfo($filename3, PATHINFO_FILENAME);
      $original_name3 = $actual_name3;
      $extensions3 = pathinfo($filename3, PATHINFO_EXTENSION);

      // file_check();
      $new_name1 = getNewFilename($actual_name1, $original_name1, $extensions1, $destination1, $file1);
      $new_name2 = getNewFilename($actual_name2, $original_name2, $extensions2, $destination2, $file2);
      $new_name3 = getNewFilename($actual_name3, $original_name3, $extensions3, $destination3, $file3);

      addCustomerBookingDetails($companypid, $last_id, $seat_number, $fname, $lname, $age, $gender, $new_name1, $new_name2, $new_name3);
    }
  }
  $_SESSION['bookingID'] = $last_id;
  header('location: bookingDetails.php');
}










if (isset($_POST['skipPassengerDetails'])) {
  if (!isset($_SESSION['demo_booking_table'])) {
    $demo_booking_table = array();
  }
  if (!isset($_SESSION['demo_booking_detail'])) {
    $demo_booking_detail = array();
  }
  $demo_taken_seats = 0;
  $demo_booking_table_ID = 0;

  $customerid = $_SESSION['customerid'];
  $numofpassenger = $_SESSION['selectednumofpassenger'];
  $terminalid = $_SESSION['selectedterminalid'];
  $companypid = $_SESSION['selectedcompid'];
  $tripid = $_SESSION['selectedtripid'];
  $sdate = $_SESSION['selecteddate'];
  $fare = $_SESSION['selectedfare'];

  $sorigin = $_SESSION['selectedorigin'];
  $sdest = $_SESSION['selecteddestination'];
  $sduration = $_SESSION['selectedduration'];
  $stime = $_SESSION['selecteddeparture_time'];
  $sdate = $_SESSION['selecteddate'];

  $totalAmount  = $fare * $numofpassenger;
  $totalFiles = $numofpassenger * 3;


  $random_number = substr(number_format(time() * rand(), 0, '', ''), 0, 10);
  $reference = hash('crc32', $random_number);

  if (isset($_SESSION['demo_booking_table'])) {
    array_push(
      $_SESSION['demo_booking_table'],
      array(
        "companyID" => $companypid,
        "terminalID" => $terminalid,
        "bustripID" => $tripid,
        "customerID" => $customerid,
        "origin" => $sorigin,
        "destination" => $sdest,
        "duration" => $sduration,
        "departure_time" => $stime,
        "number_of_seats" => $numofpassenger,
        "fare_amount" => $fare,
        "total_amount" => $totalAmount,
        "payment_status" => "not paid",
        "booked_at" => date("Y/m/d"),
        "booking_status" => "confirmed",
        "reference_id" => $reference,
      )
    );
  } else {
    array_push(
      $demo_booking_table,
      array(
        "companyID" => $companypid,
        "terminalID" => $terminalid,
        "bustripID" => $tripid,
        "customerID" => $customerid,
        "origin" => $sorigin,
        "destination" => $sdest,
        "duration" => $sduration,
        "departure_time" => $stime,
        "number_of_seats" => $numofpassenger,
        "fare_amount" => $fare,
        "total_amount" => $totalAmount,
        "payment_status" => "not paid",
        "booked_at" => date("Y/m/d"),
        "booking_status" => "confirmed",
        "reference_id" => $reference,
      )
    );
  }

  if (isset($_SESSION['demo_booking_table'])) {
    $demo_booking_table_ID = count($_SESSION['demo_booking_table']) - 1;
  } else {
    $demo_booking_table_ID = count($demo_booking_table) - 1;
  }

  $demo_taken_seats = $numofpassenger;

  for ($i = 0; $i < $numofpassenger; $i++) {
    $bn = "busnumbers" . $i;
    $fname = $firstname[rand(0, count($firstname) - 1)];
    $lname = $lastname[rand(0, count($lastname) - 1)];
    $age = "demo age " . $i;
    $filee1 = "Valid ID file " . $i;
    $filee2 = "Vaccination Card file " . $i;
    $filee3 = "S Pass file " . $i;
    $gender = "gender" . $i;

    $seat_number = mysqli_real_escape_string($db, $_POST[$bn]);

    if (isset($_SESSION['demo_booking_detail'])) {
      array_push(
        $_SESSION['demo_booking_detail'],
        array(
          "companyID" => $companypid,
          "bookingID" => $demo_booking_table_ID,
          "first_name" => $fname,
          "last_name" => $lname,
          "age" => $age,
          "gender" => $gender,
          "seat_number" => $seat_number,
          "valid_ID" => $filee1,
          "vaccination_card" => $filee2,
          "s_pass" => $filee3
        )
      );
    } else {
      array_push(
        $demo_booking_detail,
        array(
          "companyID" => $companypid,
          "bookingID" => $demo_booking_table_ID,
          "first_name" => $fname,
          "last_name" => $lname,
          "age" => $age,
          "gender" => $gender,
          "seat_number" => $seat_number,
          "valid_ID" => $filee1,
          "vaccination_card" => $filee2,
          "s_pass" => $filee3
        )
      );
    }
  }

  $_SESSION['bookingID'] = $demo_booking_table_ID;
  if (!isset($_SESSION['demo_booking_table']) && !isset($_SESSION['demo_booking_detail'])) {
    $_SESSION['demo_booking_table'] = $demo_booking_table;
    $_SESSION['demo_booking_detail'] = $demo_booking_detail;
  }

  header('location: bookingDetails.php');
}


if (isset($_POST['cancelBooking'])) {
  $bookingid = mysqli_real_escape_string($db, $_POST['bookingid']);
  $companyname = mysqli_real_escape_string($db, $_POST['companyname']);
  $companyemail = mysqli_real_escape_string($db, $_POST['companyemail']);
  $referenceid = mysqli_real_escape_string($db, $_POST['reference']);
  $reason = mysqli_real_escape_string($db, $_POST['reason']);
  $tripid = mysqli_real_escape_string($db, $_POST['tripid']);

  array_push($errors, $reason);
  $fullname1 = $_SESSION['customername'];
  $custid = $_SESSION['customerid'];
  if ($custid == "demo_account") {

    for ($a = 0; $a < count($_SESSION['demo_booking_table']); $a++) {
      if ($a == $bookingid) {
        $_SESSION['demo_booking_table'][$a]['booking_status'] = 'cancelled';
      }
    }

    header('location: registeredBookings.php');
  } else {
    $email1 = getCustomerEmail($custid, $fullname1);

    $subject1 = "Cancellation Request has been sent";
    $body1 = "<p>Cancellation request ha been sent. Please wait for further notice. A mail will be sent to this email once updated </p>";

    $subject2 = "Cancellation of trip";
    $body2 = '<p>Cancellation has been sent by our customer. Reference ID: ' . $referenceid . '</p><br><p>Reason for cancelation:</p><p>' . $reason . '</p>';

    sendthisEmail($email1, $fullname1, $subject1, $body1);
    sendthisEmail($companyemail, $companyname, $subject2, $body2);

    $new_number = getBusTakenSeat($tripid) + $numofpassenger;
    cancelBooking($bookingid);

    header('location: registeredBookings.php');
  }
}

// function random_id($bytes) {
//   $rand = mcrypt_create_iv($bytes, MCRYPT_DEV_URANDOM);
//   return bin2hex($rand);  
// }

function sendthisEmail($email, $fullname, $subject, $body)
{
  $mail = new PHPMailer(true);
  // $mail->SMTPDebug = 0;
  $mail->isSMTP();
  $mail->Host = '';
  $mail->SMTPAuth = "true";
  $mail->SMTPSecure = "tls";
  $mail->Port = '587';
  $mail->Username = "";
  $mail->Password = "";
  // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->setFrom("");
  $mail->isHTML(true);
  $mail->addAddress($email, $fullname);

  $mail->Subject = $subject;
  $mail->Body = $body;

  if (!$mail->Send()) {
    echo "<script>alert('An error Occured!')</script>";
  }
  $mail->smtpClose();
}

function checkValidID($actual_name, $original_name, $extensions, $extention, $size)
{
  $x = 1;
  $destination = '';
  while (file_exists('uploads/' . $actual_name . "." . $extensions)) {
    $actual_name = (string)$original_name . $x;
    $destination = 'uploads/' . $actual_name . "." . $extensions;
    $x++;
  }
  $new_name = $actual_name . "." . $extensions;
  if (!in_array($extention, ['pdf', 'PDF', 'jpeg', 'JPEG', 'jpg', 'JPG', 'png', 'PNG'])) {
    return [-1, -1, -1];
  } else if ($size >  2000000) {
    return [-2, -2, -2];
  } else {
    return [1, $new_name, $destination];
  }
}

function getNewFilename($actual_name, $original_name, $extensions, $destination, $file)
{
  $x = 1;
  // $destination = '';
  while (file_exists('uploads/' . $actual_name . "." . $extensions)) {
    $actual_name = (string)$original_name . $x;
    $destination = 'uploads/' . $actual_name . "." . $extensions;
    $x++;
  }
  $new_name = $actual_name . "." . $extensions;
  if (move_uploaded_file($file, $destination)) {
    return $new_name;
  }
}

function updateCustomerDetails($username, $address, $birthdate, $custid)
{
  $mysql = connect();
  $mysql->query("UPDATE `user_customer` SET `fullname`='$username', `address`='$address', `birthdate`='$birthdate' WHERE `id`='$custid'") or die($mysql->connect_error);
}

function updatePassword($npassword, $custid)
{
  $mysql = connect();
  $mysql->query("UPDATE `user_customer` SET `password`='$npassword' WHERE `id`='$custid'") or die($mysql->connect_error);
}

function updateMobileNumber($cnumber, $custid)
{
  $mysql = connect();
  $mysql->query("UPDATE `user_customer` SET `connumber`='$cnumber' WHERE `id`='$custid'") or die($mysql->connect_error);
}

function checkNameEmail($username, $email, $custid)
{
}

function getCompanyDetail($companyID)
{
  $mysql = connect();
  $result = $mysql->query("SELECT `fullname`,`email` FROM `user_partner_admin` WHERE `id`='$companyID'  limit 1") or die($mysql->connect_error);
  $dets = array_values($result->fetch_assoc());
  return [$dets[0], $dets[1]];
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

function getCustomerEmail($custid, $fullname)
{
  $mysql = connect();
  $result = $mysql->query("SELECT `email` FROM `user_customer` WHERE `id`='$custid' AND `fullname`='$fullname' limit 1") or die($mysql->connect_error);
  $name = array_values($result->fetch_assoc());
  return $name[0];
}

function uniqueCustomer($fullname, $email, $idtype)
{
  $mysql = connect();
  //   if ($idtype == 1) {
  $count = $mysql->query("SELECT COUNT(`fullname`) FROM `user_customer` WHERE `fullname`='$fullname' AND `email`='$email'") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  if ($result[0] > 0)
    return false;
  else
    return true;
  //   }
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

function getCustomerCount($id)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `count` FROM `users` WHERE `id`='$id' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

function isTripExist($origin, $dest, $day, $date)
{
  $mysql = connect();
  $result = $mysql->query("SELECT `id`,`partnerID`,`totalSeats` FROM `bus_sched` WHERE `origin` = '$origin' AND `destination` = '$dest' AND `day` = '$day'") or die($mysql->connect_error);
  $ids = $result->fetch_all(MYSQLI_ASSOC);
  foreach ($ids as $id) {
    if (checkinTrips($id['id'], $id['partnerID'], $date)) {
    } else {
      addTripSched($id['id'], $id['partnerID'], $id['totalSeats'], $date);
    }
  }
}

function checkinTrips($schedID, $partnerID, $date)
{
  $mysql = connect();
  $count = $mysql->query("SELECT COUNT(`id`) FROM `bus_trips_seat` WHERE `partnerID` = '$partnerID' AND `scheduleID` = '$schedID' AND `dates` = '$date'") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  if ($result[0] > 0)
    return true;
  else
    return false;
}

function addTripSched($schedID, $partnerID, $totalSeats, $date)
{
  $mysql = connect();
  $pName = getCompanyName($partnerID);
  $mysql->query("INSERT INTO `bus_trips_seat` (`partnerID`, `scheduleID`, `partnerName`, `dates`, `availableSeats`,`totalSeats`) VALUES('$partnerID', '$schedID', '$pName', '$date', '$totalSeats', '$totalSeats')") or die($mysql->connect_error);
}

function getCompanyName($partnerID)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `companyName` FROM `user_partner` WHERE `id`='$partnerID' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}


function addCustomerBookingDetails($companypid, $last_id, $seat_number, $fname, $lname, $age, $gender, $new_name1, $new_name2, $new_name3)
{
  $mysql = connect();
  $mysql->query("INSERT INTO `customer_booking_details` (`companyID`,`bookingID`,`first_name`, `last_name`, `age`, `gender`, `seat_number`, `valid_ID`, `vaccination_card`, `s_pass`) VALUES('$companypid','$last_id','$fname', '$lname', '$age', '$gender', '$seat_number', '$new_name1', '$new_name2', '$new_name3')") or die($mysql->connect_error);
}

function updateBusTakenSeat($tripid, $new_number)
{
  $mysql = connect();
  $mysql->query("UPDATE `bus_trip` SET `taken_seat`='$new_number' WHERE `id`='$tripid'") or die($mysql->connect_error);
}

function getBusTakenSeat($tripid)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `taken_seat` FROM `bus_trip` WHERE `id`='$tripid' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

function cancelBooking($bookingid)
{
  $mysql = connect();
  $mysql->query("UPDATE `booking_tbl` SET `booking_status`='requested for cancellation' WHERE `id`='$bookingid'") or die($mysql->connect_error);
}

function updatePaymentStatus($bookingID)
{
  $mysql = connect();
  $mysql->query("UPDATE `booking_tbl` SET `payment_status`='paid' WHERE `id`='$bookingID'") or die($mysql->connect_error);
}

if (isset($_POST['changespass'])) {
  $passengerid = mysqli_real_escape_string($db, $_POST['passengerid']);
  $bookingid = mysqli_real_escape_string($db, $_POST['bookingid']);
  if (trim($_FILES['spass']['name']) != '') {
    $filename1 = $_FILES['spass']['name'];
    $destination1 = 'uploads/' . $filename1;
    $extention1 = pathinfo($filename1, PATHINFO_EXTENSION);
    $file1 = $_FILES['spass']['tmp_name'];
    $size1 = $_FILES['spass']['size'];
    $actual_name1 = pathinfo($filename1, PATHINFO_FILENAME);
    $original_name1 = $actual_name1;
    $extensions1 = pathinfo($filename1, PATHINFO_EXTENSION);

    $x = 1;
    $destination = '';
    while (file_exists('uploads/' . $actual_name1 . "." . $extensions1)) {
      $actual_name1 = (string)$original_name1 . $x;
      $destination1 = 'uploads/' . $actual_name1 . "." . $extensions1;
      $x++;
    }
    $new_name = $actual_name1 . "." . $extensions1;
    if (!in_array($extention1, ['pdf', 'PDF', 'jpeg', 'JPEG', 'jpg', 'JPG', 'png', 'PNG'])) {
      array_push($errors, "Your file extention must be .jpeg .jpg .png or .pdf");
    } else if ($size1 >  2000000) {
      array_push($errors, "Your file is too large");
    } else {
      if (move_uploaded_file($file1, $destination1)) {
        changespass($new_name, $passengerid);
        header('location: viewBookedTickets.php?bid=' . $bookingid);
      }
    }
  } else {
    header('location: viewBookedTickets.php?bid=' . $bookingid);
  }
}

if (isset($_POST['changevaccard'])) {
  $passengerid = mysqli_real_escape_string($db, $_POST['passengerids']);
  $bookingid = mysqli_real_escape_string($db, $_POST['bookingid']);
  if (trim($_FILES['vaccard']['name']) != '') {
    $filename1 = $_FILES['vaccard']['name'];
    $destination1 = 'uploads/' . $filename1;
    $extention1 = pathinfo($filename1, PATHINFO_EXTENSION);
    $file1 = $_FILES['vaccard']['tmp_name'];
    $size1 = $_FILES['vaccard']['size'];
    $actual_name1 = pathinfo($filename1, PATHINFO_FILENAME);
    $original_name1 = $actual_name1;
    $extensions1 = pathinfo($filename1, PATHINFO_EXTENSION);

    $x = 1;
    $destination = '';
    while (file_exists('uploads/' . $actual_name1 . "." . $extensions1)) {
      $actual_name1 = (string)$original_name1 . $x;
      $destination1 = 'uploads/' . $actual_name1 . "." . $extensions1;
      $x++;
    }
    $new_name = $actual_name1 . "." . $extensions1;
    if (!in_array($extention1, ['pdf', 'PDF', 'jpeg', 'JPEG', 'jpg', 'JPG', 'png', 'PNG'])) {
      array_push($errors, "Your file extention must be .jpeg .jpg .png or .pdf");
    } else if ($size1 >  2000000) {
      array_push($errors, "Your file is too large");
    } else {
      if (move_uploaded_file($file1, $destination1)) {
        changevaccard($new_name, $passengerid);
        header('location: viewBookedTickets.php?bid=' . $bookingid);
      }
    }
  } else {
    header('location: viewBookedTickets.php?bid=' . $bookingid);
  }
}

if (isset($_POST['changesvalidid'])) {
  $passengerid = mysqli_real_escape_string($db, $_POST['passengeridss']);
  $bookingid = mysqli_real_escape_string($db, $_POST['bookingid']);
  if (trim($_FILES['validid']['name']) != '') {
    $filename1 = $_FILES['validid']['name'];
    $destination1 = 'uploads/' . $filename1;
    $extention1 = pathinfo($filename1, PATHINFO_EXTENSION);
    $file1 = $_FILES['validid']['tmp_name'];
    $size1 = $_FILES['validid']['size'];
    $actual_name1 = pathinfo($filename1, PATHINFO_FILENAME);
    $original_name1 = $actual_name1;
    $extensions1 = pathinfo($filename1, PATHINFO_EXTENSION);
    $x = 1;
    while (file_exists('uploads/' . $actual_name1 . "." . $extensions1)) {
      $actual_name1 = (string)$original_name1 . $x;
      $destination1 = 'uploads/' . $actual_name1 . "." . $extensions1;
      $x++;
    }
    $new_name = $actual_name1 . "." . $extensions1;
    if (!in_array($extention1, ['pdf', 'PDF', 'jpeg', 'JPEG', 'jpg', 'JPG', 'png', 'PNG'])) {
      array_push($errors, "Your file extention must be .jpeg .jpg .png or .pdf");
    } else if ($size1 >  2000000) {
      array_push($errors, "Your file is too large");
    } else {
      if (move_uploaded_file($file1, $destination1)) {
        changevalidid($new_name, $passengerid);
        header('location: viewBookedTickets.php?bid=' . $bookingid);
      }
    }
  } else {
    header('location: viewBookedTickets.php?bid=' . $bookingid);
  }
}

if (isset($_POST['refundBooking'])) {
  $bookingid = mysqli_real_escape_string($db, $_POST['bookingid']);
  $reference = mysqli_real_escape_string($db, $_POST['reference']);
  $companyname = mysqli_real_escape_string($db, $_POST['companyname']);
  $companyemail = mysqli_real_escape_string($db, $_POST['companyemail']);
  $fullname1 = $_SESSION['customername'];

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
  $mail->addAddress($companyemail, $companyname);
  $mail->isHTML(true);

  $mail->Subject = 'Refund Request';
  $mail->Body    = "<p>Refund request has been sent by our customer. Reference ID: '.$reference.'</p>";

  $mail->send();
  header('location: viewBookedTickets.php?bid=' . $bookingid);
}

function changespass($new_name, $passengerid)
{
  $mysql = connect();
  $mysql->query("UPDATE `customer_booking_details` SET `s_pass`='$new_name',`file_c_status`='pending' WHERE `id`='$passengerid'") or die($mysql->connect_error);
}

function changevaccard($new_name, $passengerid)
{
  $mysql = connect();
  $mysql->query("UPDATE `customer_booking_details` SET `vaccination_card`='$new_name',`file_b_status`='pending' WHERE `id`='$passengerid'") or die($mysql->connect_error);
}

function changevalidid($new_name, $passengerid)
{
  $mysql = connect();
  $mysql->query("UPDATE `customer_booking_details` SET `valid_ID`='$new_name',`file_a_status`='pending' WHERE `id`='$passengerid'") or die($mysql->connect_error);
}
