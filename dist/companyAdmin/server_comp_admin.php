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
    $encryptpassword = md5($password);
    $query = "SELECT * FROM user_partner_admin WHERE email='$email' AND password='$encryptpassword'";
    $results = mysqli_query($db, $query);
    if (mysqli_num_rows($results) == 1) {
      if (getVerified($email) == NULL) {
        $_SESSION['success'] = "Your email is still not verified. Please check your email for the verification code";
        $_SESSION['logged'] = 0;
        header('location: email_verification.php?email=' . $email);
      } else {
        $_SESSION['compadminemail'] = $email;
        $_SESSION['compadminName'] = getcompName($email);
        $_SESSION['compadminID'] = getcompID($email);
        $_SESSION['compadminVerify'] = getVerified($email);
        $_SESSION['compadminsuccess'] = "You are now logged in";
        header('location: starting_home.php');
      }
    } else {
      array_push($errors, "Wrong email/password combination");
    }
  }
}

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

  if (uniqueCustomer($fullname, $email)) {
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
      updateCustomerCount(addCustomerCount($idtype), $idtype);
      $_SESSION['logged'] = 0;
      $_SESSION['success'] = "An email with a verification code was just sent to " . $email;
      header('location: email_verification.php?email=' . $email);
    }
  } else {
    array_push($errors, "Customer already exists");
  }
}

if (isset($_POST["verify_email"])) {
  $email = $_POST["email"];
  $verification_code = $_POST["verification_code"];

  $sql = "UPDATE user_partner_admin SET email_verified_at = NOW() WHERE email = '" . $email . "' AND verification_code = '" . $verification_code . "'";
  $result  = mysqli_query($db, $sql);

  if (mysqli_affected_rows($db) == 0) {
    array_push($errors, "Verification code failed.");
  } else {
    $_SESSION['success'] = "Your email has been verified. Try logging in again";
    header('location: login_page_comAdmin.php');
    exit();
  }
}

if (isset($_POST["upload_file"])) {
  $id = $_SESSION['compadminID'];
  $filename = $_FILES['ufile']['name'];
  $destination = 'uploads/' . $filename;
  $extention = pathinfo($filename, PATHINFO_EXTENSION);
  $file = $_FILES['ufile']['tmp_name'];
  $size = $_FILES['ufile']['size'];

  if (!in_array($extention, ['pdf', 'jpeg', 'jpg', 'png'])) {
    array_push($errors, "Your file extention must be .jpeg .jpg .png or .pdf");
  } else if ($_FILES['ufile']['size'] >  1000000) {
    array_push($errors, "Your file is too large");
  } else {
    if (move_uploaded_file($file, $destination)) {
      // uploadFile($filename,$size);
      $query = "UPDATE user_partner_admin SET fileName='$filename', size='$size' WHERE id = '$id'";
      $result  = mysqli_query($db, $query);

      if (mysqli_affected_rows($db) == 0) {
        array_push($errors, "Failed to upload file");
      } else {
        header('location: starting_home.php');
      }
    }
  }
}

if (isset($_POST['upload_files'])) {
  $count = count($_FILES['files']['name']);
  $id = $_SESSION['compadminID'];
  $filenotsent = "";
  $numoffilenotsent = 0;

  if (checkFiles($id) <= 10) {
    if ($count > 0) {
      for ($i = 0; $i < $count; $i++) {
        if (trim($_FILES['files']['name'][$i] != '')) {
          if (checkFiles($id) < 10) {
            $filename = $_FILES['files']['name'][$i];
            $destination = 'uploads/' . $filename;
            $extention = pathinfo($filename, PATHINFO_EXTENSION);
            $file = $_FILES['files']['tmp_name'][$i];
            $size = $_FILES['files']['size'][$i];

            if (!in_array($extention, ['pdf', 'jpeg', 'jpg', 'png'])) {
              array_push($errors, "Your file extention must be .jpeg .jpg .png or .pdf");
            } else if ($_FILES['files']['size'][$i] >  2000000) {
              array_push($errors, "Your file is too large");
            } else {
              if (move_uploaded_file($file, $destination)) {
                $query = "INSERT INTO `company_files` (`companyID`, `file_Name`, `size`) VALUES('$id', '$filename', '$size')";
                $result  = mysqli_query($db, $query);

                if (mysqli_affected_rows($db) == 0) {
                  $filenotsent = $filenotsent + " ," + $filename;
                  $numoffilenotsent++;
                  // echo "No file";
                } else {
                  //   echo "send file";
                  updateFileCount(addFileCount($id), $id);
                }
              }
            }
            // $guide = mysqli_real_escape_string($db, $_POST["files"][$i]);
            // insertFiles($id,$fileName,$size);
          }
        }
      }
      header('location: starting_home.php');
    } else {
      echo "No file";
      // header('location: home_admin_details.php');  
    }
  } else if (checkFiles($id) > 10) {
    echo "Already have 5 guidelines in storage, Cannot add guideline";
  }
}

if (isset($_POST["company_details_change"])) {
  $compName = mysqli_real_escape_string($db, $_POST['compName']);
  $address = mysqli_real_escape_string($db, $_POST['address']);
  $id = $_SESSION['compadminID'];

  if (empty($compName)) {
    array_push($errors, "Company Name is required");
  }
  if (empty($address)) {
    array_push($errors, "address is required");
  }

  if (count($errors) == 0) {
    updateCompanyDetails($id, $compName, $address);
    $_SESSION['compadminsuccess'] = "Company Name/ Company Address has been change successfully ";
    header('location: home_admin_details.php');
  }
}



/**************************************************PAYMENT****************************************** */
if (isset($_POST["company_payment_new"])) {
  $paypalEmail = mysqli_real_escape_string($db, $_POST['newpaypalEmail']);
  $id = $_SESSION['compadminID'];

  if (empty($paypalEmail)) {
    array_push($errors, "Email is required");
  }

  if (count($errors) == 0) {
    newPaymentDetails($id, $paypalEmail);
    $_SESSION['compadminsuccess'] = "Payment has been added";
    header('location: home_admin_details.php');
  }
}
if (isset($_POST["company_payment_change"])) {
  $paypalEmail = mysqli_real_escape_string($db, $_POST['paypalEmail']);
  $paypalid = mysqli_real_escape_string($db, $_POST['paypalid']);
  $id = $_SESSION['compadminID'];

  if (empty($paypalEmail)) {
    array_push($errors, "Email is required");
  }

  if (count($errors) == 0) {
    updatePaymentDetails($paypalid, $id, $paypalEmail);
    $_SESSION['compadminsuccess'] = "Payment has been added";
    header('location: home_admin_details.php');
  }
}

/*********************************************END PAYMENT****************************************** */



if (isset($_POST["email_change"])) {
  $cemail = $_SESSION['compadminemail'];
  $nemail = mysqli_real_escape_string($db, $_POST['email']);
  $id = $_SESSION['compadminID'];

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
      updateCompanyEmail($id, $nemail, $verification_code);
      unset($_SESSION['compadminVerify']);
      $_SESSION['logged'] = 1;
      $_SESSION['compadminsuccess'] = "Email has been change successfully. Please verify the email. An email with a verification code was just sent to " . $nemail;
      header('location: email_verification.php?email=' . $nemail);
      // }
    } else {
      array_push($errors, "Email Address already exists");
    }
  }
}

if (isset($_POST["verify_new_email"])) {
  $email = $_POST["email"];
  $verification_code = mysqli_real_escape_string($db, $_POST['verification_code']);
  $id = $_SESSION['compadminID'];

  if (empty($email)) {
    array_push($errors, "Email is required");
  }
  if (empty($verification_code)) {
    array_push($errors, "Verification code is required");
  }

  if (count($errors) == 0) {
    updateVerificationCode($id, $verification_code, $email);
    $_SESSION['compadminVerify'] = getVerified($email);
    $_SESSION['compadminsuccess'] = "Your email has been verified. Please login using your new email.";
    header('location: home_admin_details.php');
  }
}

if (isset($_POST["contact_number_change"])) {
  $email = $_SESSION['compadminemail'];
  $cnumber = mysqli_real_escape_string($db, $_POST['cnumber']);
  $id = $_SESSION['compadminID'];

  if (empty($email)) {
    array_push($errors, "Email is required");
  }
  if (empty($cnumber)) {
    array_push($errors, "Contact Number is required");
  }

  if (count($errors) == 0) {
    updateContactNumber($id, $cnumber, $email);
    $_SESSION['compadminsuccess'] = "Contact Number has been changed successfully.";
    header('location: home_admin_details.php');
  }
}

if (isset($_POST["change_password"])) {
  $email = $_SESSION['compadminemail'];
  $opassword = mysqli_real_escape_string($db, $_POST['oldpassword']);
  $npassword = mysqli_real_escape_string($db, $_POST['password1']);
  $id = $_SESSION['compadminID'];

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
        $_SESSION['compadminsuccess'] = "Your password has been changed successfully.";
        header('location: home_admin_details.php');
      } else {
        array_push($errors, "Old password is wrong. Please try again.");
      }
    }
  } else array_push($errors, "New password cannot be the same as the old password");
}

if (isset($_POST["edit_cp"])) {
  $fullname = mysqli_real_escape_string($db, $_POST['fullname']);
  $position = mysqli_real_escape_string($db, $_POST['position']);
  $id = $_SESSION['compadminID'];

  if (empty($fullname)) {
    array_push($errors, "Fullname is required");
  }
  if (empty($position)) {
    array_push($errors, "Position is required");
  }

  if (count($errors) == 0) {
    if (updateContactPerson($id, $fullname, $position) == 1) {
      $_SESSION['compadminsuccess'] = "Contact Person/ Account Manager has been change successfully ";
      header('location: home_admin_details.php');
    } else
      array_push($errors, "Unsuccessfully updated contact person");
  }
}

if (isset($_POST['add_guide'])) {
  $number = count($_POST["guide"]);
  $id = $_SESSION['compadminID'];

  if (checkguides($id) <= 5) {
    if ($number > 0) {
      for ($i = 0; $i < $number; $i++) {
        if (trim($_POST["guide"][$i] != '')) {
          if (checkguides($id) < 5) {
            $guide = mysqli_real_escape_string($db, $_POST["guide"][$i]);
            insertGuides($id, $guide);
          }
        }
      }
      $_SESSION['compadminsuccess'] = "Guidelines has been updated";
      header('location: home_admin_details.php');
    } else {
      header('location: home_admin_details.php');
    }
  } else if (checkguides($compid) > 5) {
    echo "Already have 5 guidelines in storage, Cannot add guideline";
  }
}

if (isset($_POST['edit_guide'])) {
  $newguide = htmlspecialchars($_POST['newguide']);
  $guideid = mysqli_real_escape_string($db, $_POST['guideID']);
  $compid = $_SESSION['compadminID'];

  if (empty($newguide)) {
    array_push($errors, "Guide is required. you cannot leave this blank");
  }
  if (count($errors) == 0) {
    $_SESSION['compadminsuccess'] = "Guidelines has been updated";
    changeGuide($guideid, $compid, $newguide);
    header('location: home_admin_details.php');
  }
}

if (isset($_POST['delete_guide'])) {
  $guideid = mysqli_real_escape_string($db, $_POST['guideID']);
  $compid = $_SESSION['compadminID'];

  if (count($errors) == 0) {
    $_SESSION['compadminsuccess'] = "Guidelines has been updated";
    deleteGuide($guideid, $compid);
    header('location: home_admin_details.php');
  }
}

if (isset($_GET["unsetSuccessAlert"])) {
  unset($_SESSION['compadminsuccess']);
}
if (isset($_POST['add_tmaster_account'])) {
  $terminalid = mysqli_real_escape_string($db, $_POST['optiontterminal']);
  $fullname = mysqli_real_escape_string($db, $_POST['fullname']);
  $saddress = mysqli_real_escape_string($db, $_POST['saddress']);
  $city = mysqli_real_escape_string($db, $_POST['city']);
  $province = mysqli_real_escape_string($db, $_POST['province']);
  $cnumber = mysqli_real_escape_string($db, $_POST['cnumber']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $tmpassword = mysqli_real_escape_string($db, $_POST['password1']);
  $compid = $_SESSION['compadminID'];

  $mail = new PHPMailer(true);

  if (empty($terminalid)) {
    array_push($errors, "Terminal is required. you cannot leave this blank");
  }
  if (empty($fullname)) {
    array_push($errors, "Name is required. you cannot leave this blank");
  }
  if (empty($saddress)) {
    array_push($errors, "Street address is required. you cannot leave this blank");
  }
  if (empty($city)) {
    array_push($errors, "City is required. you cannot leave this blank");
  }
  if (empty($province)) {
    array_push($errors, "Prvince is required. you cannot leave this blank");
  }
  if (empty($cnumber)) {
    array_push($errors, "Contact number is required. you cannot leave this blank");
  }
  if (empty($email)) {
    array_push($errors, "Email is required. you cannot leave this blank");
  }
  if (empty($tmpassword)) {
    array_push($errors, "Password is required");
  }
  //
  // if ($cnumber != 11) {
  //   array_push($errors, "Invalid Mobile Number");
  // }

  if (uniqueTMaster($fullname, $email, $compid)) {
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

      $mail->Subject = 'Account verification';
      $mail->Body    = '<p>Your account has been successfully created. You now just need to enter your verification code and change your password (your current password is ' . $tmpassword . '). Press the "Go to website" link and enter your verification code. Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p><br><a href="https://omnibus-ph.000webhostapp.com/terminalMaster/email_verification.php?email=' . $email . '" class="btn btn primary">Go to website</a>';

      $mail->send();
      $encrypttmpassword = md5($tmpassword);
      add_tmaster_account($compid, $terminalid, $fullname, $cnumber, $email, $saddress, $city, $province, $encrypttmpassword, $verification_code);
      update_terminal_status($terminalid, $compid, 1);
      update_tmaster_count($compid, $terminalid, addtm_count($compid, $terminalid));
      update_tmaster_usercount(4, addtm_usercount(1));
      $_SESSION['compadminsuccess'] = $fullname . " has been added as terminal master";
      header('location: home_admin_tmaster.php');
    }
  } else {
    array_push($errors, "Terminal Master already exists");
  }
}

if (isset($_POST['edit_tmaster_account'])) {
  $fullname = mysqli_real_escape_string($db, $_POST['fullname']);
  $saddress = mysqli_real_escape_string($db, $_POST['saddress']);
  $city = mysqli_real_escape_string($db, $_POST['city']);
  $province = mysqli_real_escape_string($db, $_POST['province']);
  $cnumber = mysqli_real_escape_string($db, $_POST['cnumber']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $userID = mysqli_real_escape_string($db, $_POST['userID']);
  $compid = $_SESSION['compadminID'];


  if (empty($fullname)) {
    array_push($errors, "Name is required. you cannot leave this blank");
  }
  if (empty($saddress)) {
    array_push($errors, "Street address is required. you cannot leave this blank");
  }
  if (empty($city)) {
    array_push($errors, "City is required. you cannot leave this blank");
  }
  if (empty($province)) {
    array_push($errors, "Prvince is required. you cannot leave this blank");
  }
  if (empty($cnumber)) {
    array_push($errors, "Contact number is required. you cannot leave this blank");
  }
  if (empty($email)) {
    array_push($errors, "Email is required. you cannot leave this blank");
  }

  if (uniqueTMasterE($fullname, $email, $compid, $userID)) {
    if (count($errors) == 0) {
      edit_tmaster_account($fullname, $saddress, $city, $province, $cnumber, $email, $compid, $userID);
      $_SESSION['compadminsuccess'] = $fullname . " details has been successfully updated ";
      header('location: home_admin_tmaster.php');
    }
  } else {
    array_push($errors, "Terminal Master already exists");
  }
}


if (isset($_POST['edit_tmaster_account_terminal'])) {
  $terminalid = mysqli_real_escape_string($db, $_POST['optiontterminal']);
  $userID = mysqli_real_escape_string($db, $_POST['userID']);
  $compid = $_SESSION['compadminID'];

  if (empty($terminalid)) {
    array_push($errors, "Terminal is required. you cannot leave this blank");
  }

  // if (uniqueTMasterE($fullname,$email,$compid,$userID)) { 
  if (count($errors) == 0) {
    edit_tmaster_terminal_account($terminalid, $compid, $userID);
    $_SESSION['compadminsuccess'] = "Terminal has been successfully change";
    header('location: home_admin_tmaster.php');
  }
  // } else {
  //   array_push($errors, "Terminal Master already exists");
  // }
}

if (isset($_POST['delete_TMAccount'])) {
  $terminalmasterid = mysqli_real_escape_string($db, $_POST['terminalmasterid']);
  $compid = $_SESSION['compadminID'];

  if (count($errors) == 0) {
    delete_tmaster_account($terminalmasterid, $compid);
    // update_tmaster_count($compid,$terminalmasterid,decreasetm_count($compid,$terminalmasterid));
    // update_terminal_status($terminalmasterid,$compid,0);
    // update_tmaster_usercount(4,decreasetm_usercount(1));
    $_SESSION['compadminsuccess'] = "Terminal master accoiunt has been successfully removed ";
    header('location: home_admin_tmaster.php');
  }
}

if (isset($_POST['add_branch'])) {
  $id = $_SESSION['compadminID'];
  $terminalName = mysqli_real_escape_string($db, $_POST['terminalName']);
  $saddress = mysqli_real_escape_string($db, $_POST['saddress']);
  $city = mysqli_real_escape_string($db, $_POST['city']);
  $province = mysqli_real_escape_string($db, $_POST['province']);
  $cnumber = mysqli_real_escape_string($db, $_POST['cnumber']);


  if (empty($terminalName)) {
    array_push($errors, "Terminal name is required. you cannot leave this blank");
  }
  if (empty($saddress)) {
    array_push($errors, "Street address is required. you cannot leave this blank");
  }
  if (empty($city)) {
    array_push($errors, "City is required. you cannot leave this blank");
  }
  if (empty($province)) {
    array_push($errors, "Prvince is required. you cannot leave this blank");
  }
  if (empty($cnumber)) {
    array_push($errors, "Contact number is required. you cannot leave this blank");
  }

  if (count($errors) == 0) {
    if (checkterminalexist($id, $terminalName, $saddress, $city, $province)) {
      addTerminal($id, $terminalName, $saddress, $city, $province, $cnumber);
      $_SESSION['compadminsuccess'] = "Terminal has been successfully added ";
      header('location: home_admin_branches.php');
    }
  }
}

if (isset($_POST['edit_terminal'])) {
  $compid = $_SESSION['compadminID'];
  $terminalID = mysqli_real_escape_string($db, $_POST['terminalID']);
  $terminalName = mysqli_real_escape_string($db, $_POST['terminalName']);
  $saddress = mysqli_real_escape_string($db, $_POST['saddress']);
  $city = mysqli_real_escape_string($db, $_POST['city']);
  $province = mysqli_real_escape_string($db, $_POST['province']);
  $cnumber = mysqli_real_escape_string($db, $_POST['cnumber']);


  if (empty($terminalName)) {
    array_push($errors, "Terminal name is required. you cannot leave this blank");
  }
  if (empty($saddress)) {
    array_push($errors, "Street address is required. you cannot leave this blank");
  }
  if (empty($city)) {
    array_push($errors, "City is required. you cannot leave this blank");
  }
  if (empty($province)) {
    array_push($errors, "Prvince is required. you cannot leave this blank");
  }
  if (empty($cnumber)) {
    array_push($errors, "Contact number is required. you cannot leave this blank");
  }

  if (count($errors) == 0) {
    if (checkupdateterminalexist($compid, $terminalID, $terminalName, $saddress, $city, $province)) {
      updateTerminal($compid, $terminalID, $terminalName, $saddress, $city, $province, $cnumber);
      $_SESSION['compadminsuccess'] = "Terminal has been successfully updated ";
      header('location: home_admin_branches.php');
    }
  }
}

if (isset($_POST['delete_terminal'])) {
  $terminalID = mysqli_real_escape_string($db, $_POST['terminalID']);
  $compid = $_SESSION['compadminID'];

  if (count($errors) == 0) {
    $_SESSION['compadminsuccess'] = "Terminal has been successfully deleted ";
    deleteTerminal($terminalID, $compid);
    header('location: home_admin_branches.php');
  }
}


if (isset($_POST['add_Employee'])) {
  $fullname = mysqli_real_escape_string($db, $_POST['fullname']);
  $address = mysqli_real_escape_string($db, $_POST['address']);
  $cnumber = mysqli_real_escape_string($db, $_POST['cnumber']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $role = mysqli_real_escape_string($db, $_POST['optionRole']);
  $empassword = mysqli_real_escape_string($db, $_POST['password1']);
  $compid = $_SESSION['compadminID'];

  $mail = new PHPMailer(true);

  if (empty($fullname)) {
    array_push($errors, "Name is required. you cannot leave this blank");
  }
  if (empty($address)) {
    array_push($errors, "Address is required. you cannot leave this blank");
  }
  if (empty($cnumber)) {
    array_push($errors, "Contact number is required. you cannot leave this blank");
  }
  if (empty($email)) {
    array_push($errors, "Email is required. you cannot leave this blank");
  }
  if (empty($role)) {
    array_push($errors, "Employee Role is required. you cannot leave this blank");
  }
  if (empty($empassword)) {
    array_push($errors, "Employee password is required. you cannot leave this blank");
  }

  if (uniqueEmployee($fullname, $email, $compid)) {
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

      $mail->Subject = 'Account verification';
      $mail->Body    = '<p>Your account has been successfully created. You now just need to enter your verification code and change your password (your current password is ' . $empassword . '). Press the "Go to website" link and enter your verification code. Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p><br><a href="https://omnibus-ph.000webhostapp.com/conductor/email-verification.php?email=' . $email . '" class="btn btn primary">Go to website</a>';

      $mail->send();
      $encrypttmpassword = md5($empassword);
      add_employee_account($compid, $fullname, $cnumber, $email, $address, $encrypttmpassword, $role, $verification_code);
      update_employee_usercount(6, addem_usercount(6));
      $_SESSION['compadminsuccess'] = $fullname . " has been added as Employee";
      header('location: home_admin_employees.php');
    }
  } else {
    array_push($errors, "Employee already exists");
  }
}

if (isset($_POST['edit_Employee'])) {
  $fullname = mysqli_real_escape_string($db, $_POST['fullname']);
  $address = mysqli_real_escape_string($db, $_POST['address']);
  $cnumber = mysqli_real_escape_string($db, $_POST['cnumber']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $employeeID = mysqli_real_escape_string($db, $_POST['employeeID']);
  $compid = $_SESSION['compadminID'];


  if (empty($fullname)) {
    array_push($errors, "Name is required. you cannot leave this blank");
  }
  if (empty($address)) {
    array_push($errors, "Address is required. you cannot leave this blank");
  }
  if (empty($cnumber)) {
    array_push($errors, "Contact number is required. you cannot leave this blank");
  }
  if (empty($email)) {
    array_push($errors, "Email is required. you cannot leave this blank");
  }

  if (uniqueEmployeeEdit($fullname, $email, $employeeID, $compid)) {
    if (count($errors) == 0) {
      edit_employee_account($employeeID, $fullname, $address, $cnumber, $email, $compid, $role);
      $_SESSION['compadminsuccess'] = "Employee list has been successfully updated";
      header('location: home_admin_employees.php');
    }
  } else {
    array_push($errors, "Employee already exists");
  }
}

if (isset($_POST['edit_Employee_role'])) {
  $role = mysqli_real_escape_string($db, $_POST['optionRolee']);
  $employeeID = mysqli_real_escape_string($db, $_POST['employeeID']);
  $compid = $_SESSION['compadminID'];

  if (empty($role)) {
    array_push($errors, "Please select a role");
  }

  if (count($errors) == 0) {
    edit_employee_role($employeeID, $role, $compid);
    $_SESSION['compadminsuccess'] = "Employee role has been successfully updated";
    header('location: home_admin_employees.php');
  }
}

if (isset($_POST['delete_employee'])) {
  $employeeID = mysqli_real_escape_string($db, $_POST['employeeID']);
  $compid = $_SESSION['compadminID'];

  if (count($errors) == 0) {
    $_SESSION['compadminsuccess'] = "Employee has been successfully deleted ";
    update_employee_usercount(6, removeem_usercount(6));
    deleteEmployee($employeeID, $compid);
    header('location: home_admin_employee.php');
  }
}

if (isset($_POST['addbus'])) {
  $busNumber = mysqli_real_escape_string($db, $_POST['busNumber']);
  $busModel = mysqli_real_escape_string($db, $_POST['busModel']);
  $plateNumber = mysqli_real_escape_string($db, $_POST['plateNumber']);
  $optionterminalID = mysqli_real_escape_string($db, $_POST['optionterminal']);
  $optionSeatID = mysqli_real_escape_string($db, $_POST['optionSeatType']);
  $protocol = "";
  if (isset($_POST['protocol'])) {
    $protocol = mysqli_real_escape_string($db, $_POST['protocol']);
  }
  $compid = $_SESSION['compadminID'];
  $status = 1;

  if ($protocol != "On") {
    $protocol = "Off";
  }

  if (empty($busNumber)) {
    array_push($errors, "Bus number is required. you cannot leave this blank");
  }
  if (empty($busModel)) {
    array_push($errors, "Bus model is required. you cannot leave this blank");
  }
  if (empty($plateNumber)) {
    array_push($errors, "Plate number is required. you cannot leave this blank");
  }
  if (empty($optionterminalID)) {
    array_push($errors, "Please select Terminal. you cannot leave this blank");
  }
  if (empty($optionSeatID)) {
    array_push($errors, "Please select seat configuration. you cannot leave this blank");
  }

  if (uniqueBus($busNumber, $plateNumber, $compid)) {
    if (count($errors) == 0) {
      add_bus($busNumber, $busModel, $plateNumber, $optionterminalID, getSeatType($optionSeatID, $compid), getTotalSeat($optionSeatID, $compid), $compid, $protocol);
      //   updateEmployeeStatus($compid,$optionDriverName,$status);
      $_SESSION['compadminsuccess'] = "Bus has been added to the list";
      header('location: home_admin_buses.php');
    }
  } else {
    array_push($errors, "Bus already exists");
  }
}

//ADD BUS SEAT
if (isset($_POST['addbusseat'])) {
  $seattype = mysqli_real_escape_string($db, $_POST['seattype']);
  $totalseat = mysqli_real_escape_string($db, $_POST['totalseat']);
  $compid = $_SESSION['compadminID'];
  // <li>29 seats - 2x1 seat layout</li>
  // <li>45 seats - 2x2 seat layout</li>
  // <li>49 seats - 2x2 seat layout</li>
  // <li>60 seats - 3x2 seat layout</li>
  // <li>61 seats - 3x2 seat layout</li>

  if (empty($seattype)) {
    array_push($errors, "Bus number is required. you cannot leave this blank");
  }
  if (empty($totalseat)) {
    array_push($errors, "Total seat is required. you cannot leave this blank");
  }
  if ($totalseat == 29 || $totalseat == 45 || $totalseat == 49 || $totalseat == 60 || $totalseat == 61) {
    if (uniqueseatconfig($seattype, $totalseat, $compid)) {
      if (count($errors) == 0) {
        add_bus_seats($seattype, $totalseat, $compid);
        $_SESSION['compadminsuccess'] = "Seat Config has been succesfully added";
        header('location: home_admin_buses.php');
      }
    } else {
      array_push($errors, "Set configuration already exists");
    }
  } else {
    array_push($errors, "Total seat is not yet supported. Contact us for more info");
  }
}

function uniqueseatconfig($seattype, $totalseat, $compid)
{
  $mysql = connect();
  $count = $mysql->query("SELECT COUNT(id) FROM seat_configuration WHERE companyID='$compid' AND seat_type='$seattype' AND total_seat='$totalseat'") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  if ($result[0] > 0)
    return false;
  else
    return true;
}

function add_bus_seats($seattype, $totalseat, $compid)
{
  $mysql = connect();
  $mysql->query("INSERT INTO seat_configuration (companyID, seat_type, total_seat) VALUES('$compid', '$seattype', '$totalseat')") or die($mysql->connect_error);
}

// Edit Bus
if (isset($_POST['editbus'])) {
  $busID = mysqli_real_escape_string($db, $_POST['busID']);
  $busNumber = mysqli_real_escape_string($db, $_POST['busNumber']);
  $busModel = mysqli_real_escape_string($db, $_POST['busModel']);
  $plateNumber = mysqli_real_escape_string($db, $_POST['plateNumber']);
  $protocol = mysqli_real_escape_string($db, $_POST['protocol']);
  if ($protocol != "On") {
    $protocol = "Off";
  }
  $compid = $_SESSION['compadminID'];


  if (empty($busNumber)) {
    array_push($errors, "Bus number is required. you cannot leave this blank");
  }
  if (empty($busModel)) {
    array_push($errors, "Bus model is required. you cannot leave this blank");
  }
  if (empty($plateNumber)) {
    array_push($errors, "Plate number is required. you cannot leave this blank");
  }

  if (uniqueeditBus($busNumber, $plateNumber, $compid, $busID, $protocol)) {
    if (count($errors) == 0) {
      update_bus($busNumber, $busModel, $plateNumber, $compid, $busID, $protocol);
      $_SESSION['compadminsuccess'] = "Bus detatils has been succesfully updated";
      header('location: home_admin_buses.php');
    }
  } else {
    array_push($errors, "Bus already exists");
  }
}

if (isset($_POST['editbusseat'])) {
  $busID = mysqli_real_escape_string($db, $_POST['busID']);
  $optionSeatType = mysqli_real_escape_string($db, $_POST['optionSeatType']);
  $compid = $_SESSION['compadminID'];


  if (empty($busID)) {
    array_push($errors, "Bus number is required. you cannot leave this blank");
  }
  if (empty($optionSeatType)) {
    array_push($errors, "Please select seat configuration. you cannot leave this blank");
  }

  if (count($errors) == 0) {
    update_bus_seat(getSeatType($optionSeatType, $compid), getTotalSeat($optionSeatType, $compid), $compid, $busID);
    $_SESSION['compadminsuccess'] = "Bus seat type has been succesfully updated";
    header('location: home_admin_buses.php');
  }
}

if (isset($_POST['delete_bus'])) {
  $busID = mysqli_real_escape_string($db, $_POST['busid']);
  $compid = $_SESSION['compadminID'];

  if (count($errors) == 0) {
    deletebus($busID, $compid);
    $_SESSION['compadminsuccess'] = "Bus has been removed from the list ";
    header('location: home_admin_buses.php');
  }
}


if (isset($_POST['add_trip'])) {
  $compid = $_SESSION['compadminID'];
  $tripcount = mysqli_real_escape_string($db, $_POST['tripCount']);
  $routeID = mysqli_real_escape_string($db, $_POST['optionRoute']);
  $busid = mysqli_real_escape_string($db, $_POST['optionBus']);
  // $arrival = mysqli_real_escape_string($db, $_POST['arrival']);
  // $departure1 = mysqli_real_escape_string($db, $_POST['departure1']);
  // $departure2 = mysqli_real_escape_string($db, $_POST['departure2']);
  $hour = mysqli_real_escape_string($db, $_POST['hour1']);
  $minute = mysqli_real_escape_string($db, $_POST['minutes1']);
  // $duration = mysqli_real_escape_string($db, $_POST['duration']);
  $operateFrom = mysqli_real_escape_string($db, $_POST['operateFrom']);
  $operateTo = mysqli_real_escape_string($db, $_POST['operateTo']);
  $recur = $_POST['recur'];


  if (empty($routeID)) {
    array_push($errors, "Route is required");
  }
  if (empty($busid)) {
    array_push($errors, "Bus is required");
  }
  // if (empty($arrival)) {
  //   array_push($errors, "arrival is required");
  // }
  // if (empty($departure1)) {
  //   array_push($errors, "departure is required");
  // }
  // if (empty($departure2)) {
  //   array_push($errors, "departure is required");
  // }
  if (empty($hour)) {
    array_push($errors, "duration time is required");
  }
  if (empty($minute)) {
    array_push($errors, "duration time is required");
  }
  // if (empty($duration)) {
  //   array_push($errors, "duration is required");
  // }
  if (empty($operateFrom)) {
    array_push($errors, "operate from is required");
  }
  if (empty($operateTo)) {
    array_push($errors, "operate to is required");
  }
  if (empty($recur)) {
    array_push($errors, "You didn't select any recurring days.");
  }

  // $arrtime = date('h:i:s ', strtotime($arrival));
  // $deptime1 = date('h:i:s ', strtotime($departure1));
  // $deptime2 = date('h:i:s ', strtotime($departure2));
  $duration = $hour . ":" . $minute;
  $origin = getPointA($compid, $routeID);
  $destination = getPointB($compid, $routeID);
  $fare = getFare($compid, $routeID);
  $terminalIDA = getpointaTermID($compid, $routeID);
  $terminalIDB = getpointbTermID($compid, $routeID);


  if (count($errors) == 0) {
    if (checkTrip($compid, $busid, $routeID, $origin, $destination)) {
      for ($x = 1; $x <= $tripcount; $x++) {
        $dept1 = date('h:i:s ', strtotime(mysqli_real_escape_string($db, $_POST['departure' . $x . ''])));
        $dept2 = date('h:i:s ', strtotime(mysqli_real_escape_string($db, $_POST['departure' . $x . '2'])));
        $arr1 = date('h:i:s ', strtotime(mysqli_real_escape_string($db, $_POST['departure' . $x . ''])));
        $arr2 = date('h:i:s ', strtotime(mysqli_real_escape_string($db, $_POST['departure' . $x . '2'])));
        $tripCode = $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
        addTrip($compid, $terminalIDA, $busid, $routeID, $dept1, $origin, $destination, $duration, $fare, $operateFrom, $operateTo, $tripCode);
        addTrip($compid, $terminalIDB, $busid, $routeID, $dept2, $destination, $origin, $duration, $fare, $operateFrom, $operateTo, $tripCode);
        $tripid1 = getTripID($compid, $busid, $routeID, $origin, $destination, $dept1, $tripCode);
        $tripid2 = getTripID($compid, $busid, $routeID, $destination, $origin, $dept2, $tripCode);
        // echo $tripid1." ";
        // echo $tripid2;
        $N = count($recur);
        // echo $N;
        for ($i = 0; $i < $N; $i++) {
          // echo $recur[$i]." ";
          addrecurrance($compid, $tripid1, $recur[$i]);
          addrecurrance($compid, $tripid2, $recur[$i]);
        }
        $_SESSION['compadminsuccess'] = "New trip has been added to the list";
        header('location: home_admin_trips.php');
      }
    } else {
      array_push($errors, "Trip already exist");
    }
  }
}

if (isset($_POST['edit_trip'])) {
  $compid = $_SESSION['compadminID'];
  $duration = mysqli_real_escape_string($db, $_POST['duration']);
  $tripid = mysqli_real_escape_string($db, $_POST['tripID']);


  if (empty($duration)) {
    array_push($errors, "duration is required");
  }

  if (count($errors) == 0) {
    updateTrip($tripid, $compid, $duration);
    $_SESSION['compadminsuccess'] = "Trip has been updated to the list";
  }
}

if (isset($_POST['delete_trip'])) {
  $tripID = mysqli_real_escape_string($db, $_POST['tripID']);
  $compid = $_SESSION['compadminID'];

  if (count($errors) == 0) {
    deleteRecurring($tripID, $compid);
    deleteTrip($tripID, $compid);
    $_SESSION['compadminsuccess'] = "Trip has been removed from the list ";
    header('location: home_admin_trips.php');
  }
}

if (isset($_POST['delete_book'])) {
  $bookID = mysqli_real_escape_string($db, $_POST['bookID']);
  $compid = $_SESSION['compadminID'];

  if (count($errors) == 0) {
    $_SESSION['compadminsuccess'] = "Booking has been removed from the list ";
    deletebook($bookID, $tripID, $compid);
    deletebookdetails($bokID, $tripID, $compid);
    header('location: home_admin_trips.php');
  }
}

if (isset($_POST['conbook'])) {
  $compid = $_SESSION['compadminID'];
  $bookID = mysqli_real_escape_string($db, $_POST['conbook']);
  confirmBook($compid, $bookID);
  header('location: home_admin_bookings.php');
}

if (isset($_POST['travelled'])) {
  $compid = $_SESSION['compadminID'];
  $bookID = mysqli_real_escape_string($db, $_POST['travelled']);
  travelledBook($compid, $bookID);
  header('location: home_admin_bookings.php');
}

if (isset($_POST['canbook'])) {
  $compid = $_SESSION['compadminID'];
  $bookID = mysqli_real_escape_string($db, $_POST['canbook']);
  $bustripID = mysqli_real_escape_string($db, $_POST['bustripID']);
  $taken_seat = mysqli_real_escape_string($db, $_POST['taken_seat']);
  $totalSeats = mysqli_real_escape_string($db, $_POST['totalSeats']);
  $new = $taken_seat - $totalSeats;
  // echo $new;
  cancelBook($compid, $bookID);
  updateBusTripTakenSeat($new, $bustripID);
  header('location: home_admin_bookings.php');
}

if (isset($_POST['add_new_trip'])) {
  $compid = $_SESSION['compadminID'];
  $routeid = mysqli_real_escape_string($db, $_POST['optionRoute']);
  $terminalid = mysqli_real_escape_string($db, $_POST['terminalID']);
  $origin = mysqli_real_escape_string($db, $_POST['thisorigin']);
  $destination = mysqli_real_escape_string($db, $_POST['thisdestination']);
  // $thisroute = mysqli_real_escape_string($db, $_POST['radio1']);
  $number = count($_POST["departure"]);

  if (empty($routeid)) {
    array_push($errors, "Route is required. you cannot leave this blank");
  }

  if (count($errors) == 0) {
    $assigned_by = get_admin_name($compid);
    $duration = getRouteDuration($routeid, $compid);
    if ($number > 0) {
      for ($i = 0; $i < $number; $i++) {
        if (trim($_POST["departure"][$i] != '')) {
          $departure = mysqli_real_escape_string($db, $_POST["departure"][$i]);
          addonenewtrip($origin, $destination, $duration, $departure, $routeid, $compid, $terminalid, $assigned_by);
        }
      }
      $_SESSION['compadminsuccess'] = "Trip has been updated";
      header('location: home_admin_trips.php');
    } else {
      header('location: home_admin_trips.php');
    }
  }
}


function addNewCompanyAdmin($idtype, $compName, $address, $fullname, $position, $cnumber, $email, $encryptpassword, $verification_code)
{
  $mysql = connect();
  //   if ($idtype == 5) {
  // $mysql->query("INSERT INTO `user_partner_admin` (`usertype`, `companyName`, `companyAddress`, `fullname`, `position`, `contactNumber`, `email`, `password`, `verification_code`) VALUES('$idtype', '$compName', '$address', '$fullname', '$position', '$cnumber', '$email', '$encryptpassword', '$verification_code')") or die($mysql->connect_error);
  $mysql->query("INSERT INTO `user_partner_admin`(`usertype`, `companyName`, `companyAddress`, `fullname`, `position`, `contactNumber`, `email`, `password`, `verification_code`) VALUES ('$idtype','$compName','$address','$fullname','$position','$cnumber','$email','$encryptpassword','$verification_code')") or die($mysql->connect_error);
  //   }
}

function uniqueCustomer($fullname, $email)
{
  $mysql = connect();
  $count = $mysql->query("SELECT COUNT(`fullname`) FROM `user_partner_admin` WHERE `fullname`='$fullname' AND `email`='$email'") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  if ($result[0] > 0)
    return false;
  else
    return true;
}

function uniqueEmail($email)
{
  $mysql = connect();
  $count = $mysql->query("SELECT COUNT(`fullname`) FROM `user_partner_admin` WHERE `email`='$email'") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  if ($result[0] > 0)
    return false;
  else
    return true;
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

function getVerified($email)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `email_verified_at` FROM `user_partner_admin` WHERE `email`='$email' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
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

function checkFiles($id)
{
  $mysql = connect();
  $count = $mysql->query("SELECT COUNT(`companyID`) FROM `company_files` WHERE `companyID`='$id'") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

function insertFiles($id, $fileName, $size)
{
  $mysql = connect();
  $mysql->query("INSERT INTO `company_files` (`companyID`, `fileName`, `size`) VALUES('$id', '$fileName', '$size')") or die($mysql->connect_error);
}

function updateFileCount($count, $id)
{
  $mysql = connect();
  $mysql->query("UPDATE `user_partner_admin` SET `fileCount`='$count' WHERE `id`='$id'") or die($mysql->connect_error);
}

function addFileCount($id)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `fileCount` FROM `user_partner_admin` WHERE `id`='$id' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  $result[0] = $result[0] + 1;
  return $result[0];
}

function updateContactPerson($id, $fullname, $position)
{
  $mysql = connect();
  $count = $mysql->query("UPDATE `user_partner_admin` SET `fullname`='$fullname', `position`='$position' WHERE `id` = '$id'") or die($mysql->connect_error);
  return $count;
}

function updateCompanyDetails($id, $compName, $address)
{
  $mysql = connect();
  $mysql->query("UPDATE `user_partner_admin` SET `companyName`='$compName', `companyAddress`='$address' WHERE `id` = '$id'") or die($mysql->connect_error);
}

function updateCompanyEmail($id, $email, $verification_code)
{
  $mysql = connect();
  $mysql->query("UPDATE `user_partner_admin` SET `email`='$email', `verification_code`='$verification_code', `email_verified_at`=null WHERE `id` = '$id'") or die($mysql->connect_error);
}

function updateVerificationCode($id, $verification_code, $email)
{
  $mysql = connect();
  $mysql->query("UPDATE `user_partner_admin` SET `email_verified_at` = NOW() WHERE `id` = '$id' AND  `email`= '$email' AND `verification_code` = '$verification_code'") or die($mysql->connect_error);
}

function updateContactNumber($id, $cnumber, $email)
{
  $mysql = connect();
  $mysql->query("UPDATE `user_partner_admin` SET `contactNumber`='$cnumber' WHERE `id` = '$id' AND `email` = '$email'") or die($mysql->connect_error);
}

function password_match($id, $opassword)
{
  $mysql = connect();
  $count = $mysql->query("SELECT COUNT(`fullname`) FROM `user_partner_admin` WHERE `id`='$id' AND `password`= '$opassword' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

function updatepassword($id, $encryptnpassword)
{
  $mysql = connect();
  $mysql->query("UPDATE `user_partner_admin` SET `password`='$encryptnpassword' WHERE `id` = '$id'") or die($mysql->connect_error);
}

function checkguides($id)
{
  $mysql = connect();
  $count = $mysql->query("SELECT COUNT(`companyID`) FROM `guidelines` WHERE `companyID`='$id'") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

function insertGuides($id, $guide)
{
  $mysql = connect();
  $mysql->query("INSERT INTO `guidelines` (`companyID`, `guideline`) VALUES('$id', '$guide')") or die($mysql->connect_error);
}

function changeGuide($guideid, $compid, $guide)
{
  $mysql = connect();
  $mysql->query("UPDATE `guidelines` SET `guideline`='$guide' WHERE `id`='$guideid' AND `companyID`='$compid'") or die($mysql->connect_error);
}

function deleteGuide($guideid, $compid)
{
  $mysql = connect();
  $mysql->query("DELETE FROM `guidelines` WHERE `id`='$guideid' AND `companyID`='$compid'") or die($mysql->connect_error);
}

/*****************************************************************/

function update_terminal_status($terminalid, $compid, $stat)
{
  $mysql = connect();
  if ($stat == 1) {
    $mysql->query("UPDATE `terminal` SET `status`=1 WHERE `id`='$terminalid' AND `companyID`='$compid'") or die($mysql->connect_error);
  } else {
    if (checktmaster_count($compid, $terminalid) == 0) {
      $mysql->query("UPDATE `terminal` SET `status`=0 WHERE `id`='$terminalid' AND `companyID`='$compid'") or die($mysql->connect_error);
    }
  }
}

function add_tmaster_account($compid, $terminalid, $fullname, $cnumber, $email, $saddress, $city, $province, $encrypttmpassword, $verification_code)
{
  $mysql = connect();
  $mysql->query("INSERT INTO `user_partner_tmaster` (`companyID`, `terminalID`, `usertype`, `fullname`, `connumber`, `email`, `street_address`, `city`, `province`, `password`, `verification_code`, `account_status`) VALUES('$compid','$terminalid', 4, '$fullname', '$cnumber', '$email', '$saddress', '$city', '$province', '$encrypttmpassword', '$verification_code', 0)") or die($mysql->connect_error);
}

function edit_tmaster_account($fullname, $saddress, $city, $province, $cnumber, $email, $compid, $userID)
{
  $mysql = connect();
  $mysql->query("UPDATE `user_partner_tmaster` SET `fullname`='$fullname', `connumber`='$cnumber', `email`='$email', `street_address`='$saddress', `city`='$city', `province`='$province' WHERE `id`='$userID' AND `companyID`='$compid'") or die($mysql->connect_error);
}

function edit_tmaster_terminal_account($terminalid, $compid, $userID)
{
  $mysql = connect();
  $mysql->query("UPDATE `user_partner_tmaster` SET `terminalID`='$terminalid' WHERE `id`='$userID' AND `companyID`='$compid'") or die($mysql->connect_error);
}

function uniqueTMaster($fullname, $email, $compid)
{
  $mysql = connect();
  $count = $mysql->query("SELECT COUNT(`fullname`) FROM `user_partner_tmaster` WHERE `companyID`='$compid' AND `fullname`='$fullname' AND `email`='$email'") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  if ($result[0] > 0)
    return false;
  else
    return true;
}

function uniqueTMasterE($fullname, $email, $compid, $userID)
{
  $mysql = connect();
  $count = $mysql->query("SELECT COUNT(`fullname`) FROM `user_partner_tmaster` WHERE `id`!='$userID' AND `companyID`='$compid' AND `fullname`='$fullname' AND `email`='$email'") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  if ($result[0] > 0)
    return false;
  else
    return true;
}

function getterminalID($id, $terminalName, $saddress, $city, $province)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `id` FROM `terminal` WHERE `companyID`='$id' AND `terminal_name`='$terminalName' AND `street_address`='$saddress' AND `city`='$city' AND `province`='$province'") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

function checkterminalexist($id, $terminalName, $saddress, $city, $province)
{
  $mysql = connect();
  $count = $mysql->query("SELECT COUNT(`id`) FROM `terminal` WHERE `companyID`='$id' AND `terminal_name`='$terminalName' AND `street_address`='$saddress' AND `city`='$city' AND `province`='$province'") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  if ($result[0] > 0)
    return false;
  else
    return true;
}

function checkupdateterminalexist($id, $terminalID, $terminalName, $saddress, $city, $province)
{
  $mysql = connect();
  $count = $mysql->query("SELECT COUNT(`id`) FROM `terminal` WHERE `id`!='$terminalID' AND `companyID`='$id' AND `terminal_name`='$terminalName' AND `street_address`='$saddress' AND `city`='$city' AND `province`='$province'") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  if ($result[0] > 0)
    return false;
  else
    return true;
}

function checktmaster_count($id, $terminalID)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `tmaster_count` FROM `terminal` WHERE `id`='$terminalID' AND `companyID`='$id'") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

function addTerminal($id, $terminalName, $saddress, $city, $province, $cnumber)
{
  $mysql = connect();
  $mysql->query("INSERT INTO `terminal` (`companyID`, `terminal_name`, `street_address`, `city`, `province`, `terminal_connumber` ) VALUES('$id', '$terminalName', '$saddress', '$city', '$province', '$cnumber')") or die($mysql->connect_error);
}

function updateTerminal($compid, $terminalID, $terminalName, $saddress, $city, $province, $cnumber)
{
  $mysql = connect();
  $mysql->query("UPDATE `terminal` SET `terminal_name`='$terminalName', `street_address`='$saddress', `city`='$city', `province`='$province', `terminal_connumber`='$cnumber' WHERE `id`='$terminalID' AND `companyID`='$compid'") or die($mysql->connect_error);
}

function deleteTerminal($terminalID, $compid)
{
  $mysql = connect();
  $mysql->query("DELETE FROM `terminal` WHERE `id`='$terminalID' AND `companyID`='$compid'") or die($mysql->connect_error);
}

function delete_tmaster_account($userID, $compid)
{
  $mysql = connect();
  $mysql->query("DELETE FROM `user_partner_tmaster` WHERE `id`='$userID' AND `companyID`='$compid'") or die($mysql->connect_error);
}

function updatetmaster_account($id, $terminalID, $option)
{
  $mysql = connect();
  $mysql->query("UPDATE `user_partner_tmaster` SET `terminalID`='$terminalID', `account_status`=1 WHERE `id`='$option' AND `companyID`='$id'") or die($mysql->connect_error);
}

function addtm_count($id, $terminalID)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `tmaster_count` FROM `terminal` WHERE `id`='$terminalID' AND `companyID`='$id' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  $result[0] = $result[0] + 1;
  return $result[0];
}

function decreasetm_count($id, $terminalID)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `tmaster_count` FROM `terminal` WHERE `id`='$terminalID' AND `companyID`='$id' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  $result[0] = $result[0] - 1;
  return $result[0];
}

function update_tmaster_count($compid, $terminalmasterid, $add)
{
  $mysql = connect();
  $mysql->query("UPDATE `terminal` SET `tmaster_count`='$add' WHERE `id`='$terminalmasterid' AND `companyID`='$compid'") or die($mysql->connect_error);
}

function addtm_usercount($id)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `count` FROM `users` WHERE `id`='$id' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  $result[0] = $result[0] + 1;
  return $result[0];
}

function decreasetm_usercount($id)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `count` FROM `users` WHERE `id`='$id' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  $result[0] = $result[0] - 1;
  return $result[0];
}

function update_tmaster_usercount($id, $add)
{
  $mysql = connect();
  $mysql->query("UPDATE `users` SET `count`='$add' WHERE `id`='$id'") or die($mysql->connect_error);
}


function add_employee_account($compid, $fullname, $cnumber, $email, $address, $encrypttmpassword, $role, $verification_code)
{
  $mysql = connect();
  $mysql->query("INSERT INTO `employees` (`usertype`, `companyID`, `fullname`, `address`, `contactNumber`, `email`, `role`, `password`, `verification_code`, `email_verified_at`, `status`) VALUES(6, '$compid', '$fullname', '$address', '$cnumber', '$email',  '$role', '$encrypttmpassword', '$verification_code', NULL, 0)") or die($mysql->connect_error);
}

function addem_usercount($id)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `count` FROM `users` WHERE `id`='$id' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  $result[0] = $result[0] + 1;
  return $result[0];
}

function removeem_usercount($id)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `count` FROM `users` WHERE `id`='$id' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  $result[0] = $result[0] - 1;
  return $result[0];
}

function update_employee_usercount($id, $add)
{
  $mysql = connect();
  $mysql->query("UPDATE `users` SET `count`='$add' WHERE `id`='$id'") or die($mysql->connect_error);
}

function uniqueEmployee($fullname, $email, $compid)
{
  $mysql = connect();
  $count = $mysql->query("SELECT COUNT(`id`) FROM `employees` WHERE `companyID`='$compid' AND `fullname`='$fullname' AND `email`='$email'") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  if ($result[0] > 0)
    return false;
  else
    return true;
}

function uniqueEmployeeEdit($fullname, $email, $employeeID, $compid)
{
  $mysql = connect();
  $count = $mysql->query("SELECT COUNT(`id`) FROM `employees` WHERE `id`!='$employeeID' AND `companyID`='$compid' AND `fullname`='$fullname' AND email='$email'") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  if ($result[0] > 0)
    return false;
  else
    return true;
}

function edit_employee_account($employeeID, $fullname, $address, $cnumber, $email, $compid)
{
  $mysql = connect();
  $mysql->query("UPDATE `employees` SET `fullname`='$fullname', `address`='$address', `contactNumber`='$cnumber', `email`='$email' WHERE `id`='$employeeID' AND `companyID`='$compid'") or die($mysql->connect_error);
}

function edit_employee_role($employeeID, $role, $compid)
{
  $mysql = connect();
  $mysql->query("UPDATE `employees` SET  `role`='$role' WHERE `id`='$employeeID' AND `companyID`='$compid'") or die($mysql->connect_error);
}

function deleteEmployee($employeeID, $compid)
{
  $mysql = connect();
  $mysql->query("DELETE FROM `employees` WHERE `id`='$employeeID' AND `companyID`='$compid'") or die($mysql->connect_error);
}

function uniqueBus($busNumber, $plateNumber, $compid)
{
  $mysql = connect();
  $count = $mysql->query("SELECT COUNT(`id`) FROM `buses` WHERE `companyID`='$compid' AND `busNumber`='$busNumber' AND `plate_number`='$plateNumber'") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  if ($result[0] > 0)
    return false;
  else
    return true;
}

function uniqueeditBus($busNumber, $plateNumber, $compid, $busID)
{
  $mysql = connect();
  $count = $mysql->query("SELECT COUNT(`id`) FROM buses WHERE `id`!='$busID' AND `companyID`='$compid' AND `busNumber`='$busNumber' AND `plate_number`='$plateNumber'") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  if ($result[0] > 0)
    return false;
  else
    return true;
}

function add_bus($busNumber, $busModel, $plateNumber, $optionterminalID, $seatType, $totalSeat, $compid, $protocol)
{
  $mysql = connect();
  $mysql->query("INSERT INTO `buses` (`companyID`, `terminalID`, `seat_type`, `total_seat`, `bus_model`, `busNumber`, `plate_number`, `protocol`) VALUES('$compid', '$optionterminalID', '$seatType','$totalSeat', '$busModel', '$busNumber', '$plateNumber', '$protocol')") or die($mysql->connect_error);
}

function update_bus($busNumber, $busModel, $plateNumber, $compid, $busID, $protocol)
{
  $mysql = connect();
  $mysql->query("UPDATE `buses` SET `bus_model`='$busModel',`busNumber`='$busNumber',`plate_number`='$plateNumber', `protocol`='$protocol' WHERE `id`='$busID' AND `companyID`='$compid'") or die($mysql->connect_error);
}

function update_bus_seat($optionSeatType, $totalSeat, $compid, $busID)
{
  $mysql = connect();
  $mysql->query("UPDATE `buses` SET `seat_type`='$optionSeatType',`total_seat`='$totalSeat' WHERE `id`='$busID' AND `companyID`='$compid'") or die($mysql->connect_error);
}

function deletebus($busID, $compid)
{
  $mysql = connect();
  $mysql->query("DELETE FROM `buses` WHERE `id`='$busID' AND `companyID`='$compid'") or die($mysql->connect_error);
}

function updateEmployeeStatus($companyID, $optionDriverName, $status)
{
  $mysql = connect();
  if ($status == 1) {
    $mysql->query("UPDATE `employees` SET `status`='$status' WHERE `companyID`='$companyID' AND `fullname`='$optionDriverName'") or die($mysql->connect_error);
  }
  if ($status == 0) {
    $mysql->query("UPDATE `employees` SET `status`='$status' WHERE `companyID`='$companyID' AND `fullname`='$optionDriverName'") or die($mysql->connect_error);
  }
}

function getSeatType($optionSeatID, $compid)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `seat_type` FROM `seat_configuration` WHERE `id`='$optionSeatID' AND `companyID`='$compid' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

function getTotalSeat($optionSeatID, $compid)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `total_seat` FROM `seat_configuration` WHERE `id`='$optionSeatID' AND `companyID`='$compid' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

function getPointA($compid, $routeID)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `pointA` FROM `routes` WHERE `id`='$routeID' AND `companyID`='$compid' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

function getPointB($compid, $routeID)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `pointB` FROM `routes` WHERE `id`='$routeID' AND `companyID`='$compid' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

function getfare($compid, $routeID)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `fare` FROM `routes` WHERE `id`='$routeID' AND `companyID`='$compid' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

function getpointaTermID($compid, $routeID)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `pointA_terminalID` FROM `routes` WHERE `id`='$routeID' AND `companyID`='$compid' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

function getpointbTermID($compid, $routeID)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `pointB_terminalID` FROM `routes` WHERE `id`='$routeID' AND `companyID`='$compid' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

function addTrip($compid, $terminalID, $busid, $routeID, $deptime, $origin, $destination, $duration, $fare, $operateFrom, $operateTo, $tripCode)
{
  $mysql = connect();
  $mysql->query("INSERT INTO `trips` (`companyID`,`terminalID`, `busID`, `routeID`, `origin`, `destination`, `departure_time`, `duration`, `fare`, `operate_from`,`operate_to`,`tripCode`) VALUES('$compid', '$terminalID', '$busid', '$routeID', '$origin','$destination','$deptime', '$duration', '$fare', '$operateFrom', '$operateTo', '$tripCode')") or die($mysql->connect_error);
}

function getTripID($compid, $busid, $routeID, $origin, $destination, $deptime, $tripCode)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `id` FROM `trips` WHERE `companyID`='$compid' AND `busID`='$busid' AND `routeID`='$routeID' AND `origin`='$origin' AND `destination`='$destination' AND `departure_time`='$deptime' AND `tripCode`='$tripCode' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

function addrecurrance($compid, $tripid, $recur)
{
  $mysql = connect();
  $mysql->query("INSERT INTO `trips_recurring_days` (`companyID`, `tripID`, `recurring_day`) VALUES('$compid', '$tripid', '$recur')") or die($mysql->connect_error);
}

function checkTrip($compid, $busid, $routeID, $origin, $destination)
{
  $mysql = connect();
  $count = $mysql->query("SELECT COUNT(`id`) FROM `trips` WHERE `companyID`='$compid' AND `busID`='$busid' AND `routeID`='$routeID' AND `origin`='$origin' AND `destination`='$destination' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  if ($result[0] > 0)
    return false;
  else
    return true;
}

function deleteTrip($tripID, $compid)
{
  $mysql = connect();
  $mysql->query("DELETE FROM `trips` WHERE `id`='$tripID' AND `companyID`='$compid'") or die($mysql->connect_error);
}

function deleteRecurring($tripID, $compid)
{
  $mysql = connect();
  $mysql->query("DELETE FROM `trips_recurring_days` WHERE `companyID`='$compid' AND `tripID`='$tripID'") or die($mysql->connect_error);
}

function updateTrip($tripID, $compid, $duration)
{
  $mysql = connect();
  $mysql->query("UPDATE `trips` SET `duration`='$duration' WHERE `id`='$tripID' AND `companyID`='$compid'") or die($mysql->connect_error);
}

function deleteBook($bookID, $tripID, $compid)
{
  $mysql = connect();
  $mysql->query("DELETE FROM `booking_tbl` WHERE `id`='$bookID' AND `companyID`='$compid' AND `tripID`='$tripID'") or die($mysql->connect_error);
}

function deletebookdetails($bookID)
{
  $mysql = connect();
  $mysql->query("DELETE FROM `customer_booking_details` WHERE `bookingID`='$bookID'") or die($mysql->connect_error);
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

function addonenewtrip($route1, $route2, $duration, $departure, $routeid, $compid, $terminalid, $assigned_by)
{
  $mysql = connect();
  $mysql->query("INSERT INTO `trip` (origin, destination, duration, departure_time, assigned_by, companyID, terminalID, routeID) VALUES('$route1', '$route2', '$duration', '$departure ','$assigned_by', '$compid', '$terminalid', '$routeid')") or die($mysql->connect_error);
}

function getRouteTermID($routeid, $compid, $origin1)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `pointA`,`pointA_terminalID`,`pointB_terminalID` FROM `routes` WHERE `id`='$routeid' AND `companyID`='$compid' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  if ($result[0] == $origin1) {
    return $result[1];
  } else {
    return $result[2];
  }
}

function getTripTermID($tripid, $compid)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `terminalID` FROM `trip` WHERE `id`='$tripid' AND `companyID`='$compid' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

function getRouteDuration($routeid, $compid)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `duration` FROM `routes` WHERE `id`='$routeid' AND `companyID`='$compid' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}
//////////////////// CODE BY ART

if (isset($_POST['edit_route'])) {

  $companyID = $_POST['companyID'];
  $recID = mysqli_real_escape_string($db, $_POST['recID']);
  $pointA = mysqli_real_escape_string($db, $_POST['pointA']);
  $pointB = mysqli_real_escape_string($db, $_POST['pointB']);
  $hour = mysqli_real_escape_string($db, $_POST['hour']);
  $minute = mysqli_real_escape_string($db, $_POST['minute']);
  // $fare = mysqli_real_escape_string($db, $_POST['fare']);

  if (empty($pointA)) {
    array_push($errors, "Point A is required. you cannot leave this blank");
  }
  if (empty($pointB)) {
    array_push($errors, "Point B is required. you cannot leave this blank");
  }
  // if (empty($fare)) {
  //   array_push($errors, "Fare is required. you cannot leave this blank");
  // }
  if (empty($hour)) {
    array_push($errors, "Hour is required. you cannot leave this blank");
  }
  if (empty($minute)) {
    array_push($errors, "Minutes is required. you cannot leave this blank");
  }

  if (count($errors) == 0) {
    if (!checkrouteifexists($companyID, $recID, $pointA, $pointB)) {
      $duration = $hour . ":" . $minute;
      updateRoute($recID, $pointA, $pointB, $duration);
      $_SESSION['compadminsuccess'] = "Route has been successfully updated ";
      header('location: home_admin_routes.php');
    }
  }
}

if (isset($_POST['delete_route'])) {
  $recID = mysqli_real_escape_string($db, $_POST['recID']);

  if (count($errors) == 0) {
    $_SESSION['compadminsuccess'] = "Route has been successfully deleted ";
    deleteRoute($recID);
    header('location: home_admin_routes.php');
  }
}

if (isset($_POST['add_route'])) {
  $companyID = $_POST['companyID'];
  // $pointA = mysqli_real_escape_string($db, $_POST['pointA']);
  // $pointB = mysqli_real_escape_string($db, $_POST['pointB']);
  $termidA = mysqli_real_escape_string($db, $_POST['optionterminalA']);
  $termidB = mysqli_real_escape_string($db, $_POST['optionterminalB']);
  $hour = mysqli_real_escape_string($db, $_POST['hour1']);
  $minute = mysqli_real_escape_string($db, $_POST['minutes1']);
  $maxbtwn = mysqli_real_escape_string($db, $_POST['maxbtwn']);
  $recID = 0;
  // echo $maxbtwn;
  if (empty($termidA)) {
    array_push($errors, "Terminal for point A is required. you cannot leave this blank");
  }
  if (empty($termidB)) {
    array_push($errors, "Terminal for point B is required. you cannot leave this blank");
  }
  if (empty($hour)) {
    array_push($errors, "Hour is required. you cannot leave this blank");
  }
  if (empty($minute)) {
    array_push($errors, "Minutes is required. you cannot leave this blank");
  }


  $pointA = getCityAddress($termidA, $companyID);
  $pointB = getCityAddress($termidB, $companyID);
  if (count($errors) == 0) {
    if (!checkrouteifexists($companyID, $recID, $pointA, $pointB)) {
      $duration = $hour . ":" . $minute;
      $last_ida = '';
      $last_idb = '';

      $sql = "INSERT INTO `routes`(`pointA`, `pointB`, `duration`, `companyID`, `pointA_terminalID`, `pointB_terminalID`)  VALUES('$pointA', '$pointB','$duration', '$companyID','$termidA','$termidB')";
      if (mysqli_query($db, $sql)) {
        $last_ida = mysqli_insert_id($db);
      } else {
      }
      $sql = "INSERT INTO `routes`(`pointA`, `pointB`, `duration`, `companyID`, `pointA_terminalID`, `pointB_terminalID`)  VALUES('$pointB', '$pointA','$duration', '$companyID','$termidB','$termidA')";
      if (mysqli_query($db, $sql)) {
        $last_idb = mysqli_insert_id($db);
      } else {
      }

      for ($s = 0; $s < $maxbtwn; $s++) {
        $between = mysqli_real_escape_string($db, $_POST['inbetween'][$s]);
        if (!empty($between)) {
          $betweenName = getCityAddress($between, $companyID);
          if ($pointA != $betweenName) {
            addinbetween($companyID, $last_ida, $pointA, $betweenName);
          }
          if ($pointB != $betweenName) {
            addinbetween($companyID, $last_idb, $pointB, $betweenName);
          }
        }
        // else {
        //   $betweenName = getCityAddress($between,$companyID);
        //   echo $betweenName;
        //   addinbetween($companyID,$last_ida,$last_idb,$betweenName);
        // }
      }
      // addRoute($companyID,$pointA,$pointB,$duration,$termidA,$termidB);
      // addRoute($companyID,$pointB,$pointA,$duration,$termidB,$termidA);
      $_SESSION['compadminsuccess'] = "Route has been successfully added ";
      header('location: home_admin_routes.php');
    } else {
      array_push($errors, "Route already exist");
    }
  }
}

function checkrouteifexists($companyID, $recID, $pointA, $pointB)
{
  $mysql = connect();
  // echo "SELECT * FROM routes WHERE id!=$recID AND companyID=$companyID 
  // AND ((pointA='$pointA' OR pointB='$pointA') AND (pointA='$pointB' OR pointB='$pointB'))";
  // $count = $mysql->query("SELECT * FROM `routes` WHERE `id`!='$recID' AND `companyID`='$companyID' 
  //     AND ((`pointA`='$pointA' OR `pointB`='$pointA') AND (`pointA`='$pointB' OR `pointB`='$pointB'))") 
  //     or die($mysql->connect_error);
  $count = $mysql->query("SELECT * FROM `routes` WHERE `id`!='$recID' AND `companyID`='$companyID' 
        AND ((`pointA`='$pointA' OR `pointB`='$pointB') AND (`pointA`='$pointB' OR `pointB`='$pointA'))")
    or die($mysql->connect_error);
  if ($count->num_rows == 0)
    return false;
  else
    return true;
}

function updateRoute($recID, $pointA, $pointB, $duration)
{
  $mysql = connect();
  $mysql->query("UPDATE `routes` SET `pointA`='$pointA', `pointB`='$pointB', `duration`='$duration' WHERE `id`='$recID'") or die($mysql->connect_error);
}

function deleteRoute($recID)
{
  $mysql = connect();
  $mysql->query("DELETE FROM `routes` WHERE `id`='$recID'") or die($mysql->connect_error);
}

function addRoute($companyID, $pointA, $pointB, $duration, $termidA, $termidB)
{
  $mysql = connect();
  $mysql->query("INSERT INTO `routes`(`pointA`, `pointB`, `duration`, `companyID`, `pointA_terminalID`, `pointB_terminalID`)  VALUES('$pointA', '$pointB','$duration', '$companyID','$termidA','$termidB')") or die($mysql->connect_error);
}

function addinbetween($companyID, $last_ida, $last_idb, $betweenName)
{
  $mysql = connect();
  $mysql->query("INSERT INTO `in_between`(`companyID`, `routeID`, `origin`, `in_between_address`)  VALUES('$companyID', '$last_ida','$last_idb', '$betweenName')") or die($mysql->connect_error);
}

function getCityAddress($termid, $companyID)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `city` FROM `terminal` WHERE `id`='$termid' AND `companyID`='$companyID' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}

/////////////////// END CODE BY ART

if (isset($_POST['add_bus_trip'])) {
  $compid = $_SESSION['compadminID'];
  $busid = mysqli_real_escape_string($db, $_POST['optionBus']);
  $driverid = mysqli_real_escape_string($db, $_POST['optionbusdriver']);
  $conductorid = mysqli_real_escape_string($db, $_POST['optionbusconductor']);
  $terminalid = mysqli_real_escape_string($db, $_POST['optionTerminalbus']);
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
  if (empty($terminalid)) {
    array_push($errors, "Terminal is required. you cannot leave this blank");
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
    $assigned_by = get_admin_name($compid);
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
    $_SESSION['compadminsuccess'] = "Bus trip has been successfully added ";
    header('location: bus_trip_view.php');
  }
}

if (isset($_POST['edit_bus_trip'])) {
  $compid = $_SESSION['compadminID'];
  $bustripID = mysqli_real_escape_string($db, $_POST['bustripID']);
  $busid = mysqli_real_escape_string($db, $_POST['optionBus']);
  $fare = mysqli_real_escape_string($db, $_POST['fare']);

  if (empty($busid)) {
    array_push($errors, "Bus is required. you cannot leave this blank");
  }
  if (empty($fare)) {
    array_push($errors, "Fare is required. you cannot leave this blank");
  }

  if (count($errors) == 0) {
    $totalseat = get_total_seat($busid, $compid);
    updateBusTrip($bustripID, $fare, $totalseat, $busid, $compid);
    $_SESSION['compadminsuccess'] = "Bus trip has been successfully updated";
    header('location: bus_trip_view.php');
  }
}

if (isset($_POST['edit_bus_trip_employee'])) {
  $compid = $_SESSION['compadminID'];
  $bustripID = mysqli_real_escape_string($db, $_POST['bustripID']);
  $conductorid = mysqli_real_escape_string($db, $_POST['optionbusconductor']);
  $driverid = mysqli_real_escape_string($db, $_POST['optionbusdriver']);

  if (empty($driverid)) {
    array_push($errors, "Driver is required. you cannot leave this blank");
  }
  if (empty($conductorid)) {
    array_push($errors, "Conductor is required. you cannot leave this blank");
  }

  if (count($errors) == 0) {
    updateBusTripEmployee($driverid, $conductorid, $bustripID, $compid);
    $_SESSION['compadminsuccess'] = "Bus trip has been successfully updated";
    header('location: bus_trip_view.php');
  }
}

if (isset($_POST['delete_bus_trip'])) {
  $compid = $_SESSION['compadminID'];
  $bustripID = mysqli_real_escape_string($db, $_POST['bustripID']);

  deleteBusTrip($bustripID, $compid);
  $_SESSION['compadminsuccess'] = "Bus trip has been successfully deleted ";
  header('location: bus_trip_view.php');
}

function get_admin_name($compid)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `fullname` FROM `user_partner_admin` WHERE `id`='$compid' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
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

function updateBusTrip($bustripID, $fare, $totalseat, $busid, $compid)
{
  $mysql = connect();
  $mysql->query("UPDATE `bus_trip` SET `fare`='$fare',`total_seat`='$totalseat',`taken_seat`=0,`busID`='$busid' WHERE `id`='$bustripID' AND `companyID`='$compid'") or die($mysql->connect_error);
}

function updateBusTripEmployee($driverid, $conductorid, $bustripID, $compid)
{
  $mysql = connect();
  $mysql->query("UPDATE `bus_trip` SET `driverID`='$driverid',`conductorID`='$conductorid' WHERE `id`='$bustripID' AND `companyID`='$compid'") or die($mysql->connect_error);
}

function deleteBusTrip($bustripID, $compid)
{
  $mysql = connect();
  $mysql->query("DELETE FROM `bus_trip` WHERE `id`='$bustripID' AND `companyID`='$compid'") or die($mysql->connect_error);
}

function get_total_seat($busid, $compid)
{
  $mysql = connect();
  $count = $mysql->query("SELECT `total_seat` FROM `buses` WHERE `id`='$busid' AND `companyID`='$compid' limit 1") or die($mysql->connect_error);
  $result = array_values($count->fetch_assoc());
  return $result[0];
}






function newPaymentDetails($id, $paypalEmail)
{
  $mysql = connect();
  $mysql->query("INSERT INTO `payment_bussiness_info` (`companyID`, `paypal_email`) VALUES('$id', '$paypalEmail')") or die($mysql->connect_error);
}

function updatePaymentDetails($paypalid, $id, $paypalEmail)
{
  $mysql = connect();
  $mysql->query("UPDATE `payment_bussiness_info` SET `paypal_email`='$paypalEmail' WHERE `id` = '$paypalid'") or die($mysql->connect_error);
}

if (isset($_POST['file_a_valid'])) {
  $compid = $_SESSION['compadminID'];
  $customerid = mysqli_real_escape_string($db, $_POST['customerid']);
  $bookID = mysqli_real_escape_string($db, $_POST['bookID']);

  updatefileastatus($customerid);
  header('location: customer_booking_details.php?i=' . $bookID);
}
if (isset($_POST['file_b_valid'])) {
  $compid = $_SESSION['compadminID'];
  $customerid = mysqli_real_escape_string($db, $_POST['customerid']);
  $bookID = mysqli_real_escape_string($db, $_POST['bookID']);

  updatefilebstatus($customerid);
  header('location: customer_booking_details.php?i=' . $bookID);
}
if (isset($_POST['file_c_valid'])) {
  $compid = $_SESSION['compadminID'];
  $customerid = mysqli_real_escape_string($db, $_POST['customerid']);
  $bookID = mysqli_real_escape_string($db, $_POST['bookID']);

  updatefilecstatus($customerid);
  header('location: customer_booking_details.php?i=' . $bookID);
}

if (isset($_POST['file_a_invalid'])) {
  $compid = $_SESSION['compadminID'];
  $customerid = mysqli_real_escape_string($db, $_POST['customerid']);
  $bookID = mysqli_real_escape_string($db, $_POST['bookID']);

  updatefileastatusinvalid($customerid);
  header('location: customer_booking_details.php?i=' . $bookID);
}
if (isset($_POST['file_b_invalid'])) {
  $compid = $_SESSION['compadminID'];
  $customerid = mysqli_real_escape_string($db, $_POST['customerid']);
  $bookID = mysqli_real_escape_string($db, $_POST['bookID']);

  updatefilebstatusinvalid($customerid);
  header('location: customer_booking_details.php?i=' . $bookID);
}
if (isset($_POST['file_c_invalid'])) {
  $compid = $_SESSION['compadminID'];
  $customerid = mysqli_real_escape_string($db, $_POST['customerid']);
  $bookID = mysqli_real_escape_string($db, $_POST['bookID']);

  updatefilecstatusinvalid($customerid);
  header('location: customer_booking_details.php?i=' . $bookID);
}

function updatefileastatus($customerid)
{
  $mysql = connect();
  $mysql->query("UPDATE `customer_booking_details` SET `file_a_status`='valid' WHERE `id` = '$customerid'") or die($mysql->connect_error);
}
function updatefilebstatus($customerid)
{
  $mysql = connect();
  $mysql->query("UPDATE `customer_booking_details` SET `file_b_status`='valid' WHERE `id` = '$customerid'") or die($mysql->connect_error);
}
function updatefilecstatus($customerid)
{
  $mysql = connect();
  $mysql->query("UPDATE `customer_booking_details` SET `file_c_status`='valid' WHERE `id` = '$customerid'") or die($mysql->connect_error);
}

function updatefileastatusinvalid($customerid)
{
  $mysql = connect();
  $mysql->query("UPDATE `customer_booking_details` SET `file_a_status`='invalid' WHERE `id` = '$customerid'") or die($mysql->connect_error);
}
function updatefilebstatusinvalid($customerid)
{
  $mysql = connect();
  $mysql->query("UPDATE `customer_booking_details` SET `file_b_status`='invalid' WHERE `id` = '$customerid'") or die($mysql->connect_error);
}
function updatefilecstatusinvalid($customerid)
{
  $mysql = connect();
  $mysql->query("UPDATE `customer_booking_details` SET `file_c_status`='invalid' WHERE `id` = '$customerid'") or die($mysql->connect_error);
}



if (isset($_POST['updatepayment_refunded'])) {
  $compid = $_SESSION['compadminID'];
  $booking_tbl_id = mysqli_real_escape_string($db, $_POST['booking_tbl_id']);

  updatebookingpaymentstatus($booking_tbl_id);
  header('location: home_admin_bookings.php');
}

function updatebookingpaymentstatus($booking_tbl_id)
{
  $mysql = connect();
  $mysql->query("UPDATE `booking_tbl` SET `payment_status`='refunded' WHERE `id` = '$booking_tbl_id'") or die($mysql->connect_error);
}
