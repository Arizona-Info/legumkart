<?php
include("db_connection.php");
date_default_timezone_set("Asia/Kolkata");

$datatype = $_REQUEST['type'];
$dataval = $_REQUEST['dataval'];
$usrtype = $_REQUEST['usrtype'];

if(isset($_REQUEST['verifytype']) && $_REQUEST['verifytype'] == "email_verify"){
    include("PHPMailer/PHPMailerAutoload.php");

    $client_email='admin@arizonamediaz.co';

    $mails = new PHPMailer;
    $mails->isSMTP();                                       // Set mailer to use SMTP
    $mails->Host = 'mail.arizonamediaz.co';                  // Specify main and backup SMTP servers
    $mails->SMTPAuth = true;                               // Enable SMTP authentication
    $mails->Username = 'admin@arizonamediaz.co';                                // SMTP username
    $mails->Password = 'admin@123';                         // SMTP password
    $mails->SMTPSecure = 'tls';                             // Enable TLS encryption, `ssl` also accepted
    $mails->Port =  25;                                    // TCP port to connect to

    $mails->setFrom ($client_email);

    if($usrtype == 'lawyer')
    {
        $getsql = mysqli_query($conn,"SELECT email, firm_name, password FROM tbl_lawyers WHERE email = '".$dataval."'");
        $getnum = mysqli_num_rows($getsql);
        $getrec = mysqli_fetch_array($getsql);
        if($getnum > 0)
        {
         $to = $getrec['email'];
         
         $message = "Hi ".$getrec['firm_name'].",\n";
         $message .= "Your current password is: ".$getrec['password'];
         
         echo "Check your email & also spam to recover password";
        }
        else
        {
         echo "This email address is not in our record";
        }
    }

    if($usrtype == 'counselor')
    {
        $getsql = mysqli_query($conn,"SELECT counsel_email, counsel_name, counsel_password FROM tbl_counsel WHERE counsel_email = '".$dataval."'");
        $getnum = mysqli_num_rows($getsql);
        $getrec = mysqli_fetch_array($getsql);
        if($getnum > 0)
        {
         $to = $getrec['counsel_email'];
         
         $message = "Hi ".$getrec['counsel_name'].",\n";
         $message .= "Your current password is: ".$getrec['counsel_password'];
         
         echo "Check your email & also spam to recover password";
        }
        else
        {
         echo "This email address is not in our record";
        }
    }

    if($usrtype == 'intern')
    {
        $getsql = mysqli_query($conn,"SELECT intern_email, intern_name, intern_password FROM tbl_intern WHERE intern_email = '".$dataval."'");
        $getnum = mysqli_num_rows($getsql);
        $getrec = mysqli_fetch_array($getsql);
        if($getnum > 0)
        {
        $to = $getrec['intern_email'];
         
        $message = "Hi ".$getrec['intern_name'].",\n";
        $message .= "Your current password is: ".$getrec['intern_password'];
         
        echo "Check your email & also spam to recover password";
        }
    else
        {
         echo "This email address is not in our record";
        }
    }


    $mails->addAddress($to,'arizona'); 
    //$mails->addReplyTo($client_email);

    $mails->Subject = "Password Recover";
    $mails->Body  = $message;
    $mails->send();   
}
else if(isset($_REQUEST['verifytype']) && $_REQUEST['verifytype'] == "mobile_verify"){

    if(!isset($_SESSION)){
        session_start();
    }
    include 'includes/functions.php';
    define('SELF', basename($_SERVER['PHP_SELF']));


    if($usrtype == 'lawyer')
    {
        $getsql = mysqli_query($conn,"SELECT lawyer_id, phone, firm_name FROM tbl_lawyers WHERE email = '".$dataval."'");
        $getnum = mysqli_num_rows($getsql);
        $getrec = mysqli_fetch_array($getsql);
        if($getnum > 0 && isset($getrec['phone']) && $getrec['phone'] != "")
        {
         $to = $getrec['phone'];
         
         $otp = rand(10000,99999);
         $user_code = uniqid();

         $_SESSION['user_code'] = $user_code;

         $message = "Hi ".$getrec['firm_name'].",\n";
         $message .= "Your OTP is ".$otp;

         $update_otp = mysqli_query($conn,"UPDATE tbl_lawyers SET otp_code = '".$otp."', user_code = '".$_SESSION['user_code']."' WHERE lawyer_id = '".$getrec['lawyer_id']."'");

         if(mysqli_affected_rows($conn)){
            sendtransactionsms($to,$message);
            echo "okay";
            exit();
         }
         else{
            echo "Something went wrong please try again";
            exit();
         }

        }
        else
        {
         echo "Phone number is not available";
         exit();
        }
    }

    if($usrtype == 'counselor')
    {
        $getsql = mysqli_query($conn,"SELECT counsel_id, counsel_phone, counsel_name FROM tbl_counsel WHERE counsel_email = '".$dataval."'");
        $getnum = mysqli_num_rows($getsql);
        $getrec = mysqli_fetch_array($getsql);
        if($getnum > 0 && isset($getrec['counsel_phone']) && $getrec['counsel_phone'] != "")
        {
         $to = $getrec['counsel_phone'];
         
         $otp = rand(10000,99999);
         $user_code = uniqid();

         $_SESSION['user_code'] = $user_code;

         $message = "Hi ".$getrec['counsel_name'].",\n";
         $message .= "Your OTP is ".$otp;

         $update_otp = mysqli_query($conn,"UPDATE tbl_counsel SET otp_code = '".$otp."', user_code = '".$_SESSION['user_code']."' WHERE counsel_id = '".$getrec['counsel_id']."'");

         if(mysqli_affected_rows($conn)){
            sendtransactionsms($to,$message);
            echo "okay";
            exit();
         }
         else{
            echo "Something went wrong please try again";
            exit();
         }

        }
        else
        {
         echo "Phone number is not available";
         exit();
        }
    }

    if($usrtype == 'intern')
    {
        $getsql = mysqli_query($conn,"SELECT intern_id, intern_phone, intern_name FROM tbl_intern WHERE intern_email = '".$dataval."'");
        $getnum = mysqli_num_rows($getsql);
        $getrec = mysqli_fetch_array($getsql);
        if($getnum > 0 && isset($getrec['intern_phone']) && $getrec['intern_phone'] != "")
        {
         $to = $getrec['intern_phone'];
         
         $otp = rand(10000,99999);
         $user_code = uniqid();

         $_SESSION['user_code'] = $user_code;

         $message = "Hi ".$getrec['intern_name'].",\n";
         $message .= "Your OTP is ".$otp;

         $update_otp = mysqli_query($conn,"UPDATE tbl_intern SET otp_code = '".$otp."', user_code = '".$_SESSION['user_code']."' WHERE intern_id = '".$getrec['intern_id']."'");

         if(mysqli_affected_rows($conn)){
            sendtransactionsms($to,$message);
            echo "okay";
            exit();
         }
         else{
            echo "Something went wrong please try again";
            exit();
         }

        }
        else
        {
         echo "Phone number is not available";
         exit();
        }
    }

}

?>