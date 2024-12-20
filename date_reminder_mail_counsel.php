<?php
include("db_connection.php");
date_default_timezone_set("Asia/Kolkata");
include 'includes/functions.php';
define('SELF', basename($_SERVER['PHP_SELF']));

$client_email='admin@legumkart.com';

include("PHPMailer/PHPMailerAutoload.php");

$mails = new PHPMailer;
$mails->isSMTP();                                       
$mails->Host = 'legumkart.com';                 
$mails->SMTPAuth = true;                               
$mails->Username = 'admin@legumkart.com';                                
$mails->Password = 'admin@123908';                         
$mails->SMTPSecure = 'ssl';                            
$mails->Port =  465;                                    

$mails->setFrom($client_email);


$proplist = mysqli_query($conn,"SELECT t2.counsel_email, t2.counsel_name, t2.counsel_id, t2.counsel_phone from tbl_counsel_cases t1, tbl_counsel t2 where t1.cc_date= date_add(CURDATE(),INTERVAL 1 DAY) AND t1.counsel_id = t2.counsel_id GROUP BY t2.counsel_email");

if(mysqli_num_rows($proplist) > 0)
{   

    while($proprow=mysqli_fetch_array($proplist))
    {  
       
        $to = $proprow['counsel_email'];
        $counsel_id = $proprow['counsel_id'];
        $sendto = $proprow['counsel_email'];
        $message = "";

        $prop = "SELECT t1.cc_type, t1.cc_place, t1.cc_date, t1.cc_hearing_time, t1.cc_case_id, t2.counsel_email, t2.counsel_phone, t2.counsel_name, t2.counsel_id FROM tbl_counsel_cases t1, tbl_counsel t2  WHERE t1.counsel_id=t2.counsel_id AND t1.cc_date = date_add(CURDATE(),INTERVAL 1 DAY) AND t2.counsel_id = '".$counsel_id."'";
        $proplisted = mysqli_query($conn,$prop);

        $i = 0;
        while($proprowed=mysqli_fetch_array($proplisted))
        {

          $lawyer_name = "SELECT t1.firm_name, t2.court_name, t2.court_number, t2.case_number FROM tbl_lawyers t1, tbl_cases t2 WHERE t1.lawyer_id = t2.lawyer_id AND t2.case_id = '".$proprowed['cc_case_id']."'";
          $lawyer_select = mysqli_query($conn, $lawyer_name);
          $lawyer_result = mysqli_fetch_assoc($lawyer_select);

          if($proprowed['cc_type'] == "Conference"){
            $message .= 'Next Date For :'."\n"
          .$proprowed['cc_type'].' - '.'Court: '.$lawyer_result['court_name'].' - '.$lawyer_result['court_number'].', Case No: '.$lawyer_result['case_number'].', '.$proprowed['cc_place'].' for Lawyer "'.$lawyer_result['firm_name'].'" is on '.date_format (new DateTime($proprowed['cc_date']), 'd-M-y')."\n"." "."\n";
          }
          else{
            $message .= 'Next Date For :'."\n"
          .$proprowed['cc_type'].' - Court: '.$lawyer_result['court_name'].' - '.$lawyer_result['court_number'].', Case No: '.$lawyer_result['case_number'].' for Lawyer "'.$lawyer_result['firm_name'].'" is on '.date_format (new DateTime($proprowed['cc_date']), 'd-M-y').' / '.$proprowed['cc_hearing_time']."\n"." "."\n";
          }

          $i += 1;
        }
       
        $mails->Subject = "Case Reminder";
        $mails->Body  = $message;
        
        $mails->addAddress($sendto);
        
        
        $mails->send();
        $mails->ClearAddresses();
        $mails->ClearAllRecipients();
    }
  } 
?>