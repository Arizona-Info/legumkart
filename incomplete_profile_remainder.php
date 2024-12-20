<?php
	
	include("db_connection.php");
	date_default_timezone_set("Asia/Kolkata");

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

	$qry_select = "SELECT email, firm_name FROM tbl_lawyers WHERE firm_name = '' OR email = '' OR phone = '' OR languages = '' OR practice_courts = '' OR address = '' OR about = '' OR about_us = '' OR address2 = '' OR address3 = '' OR enroll_number = '' OR pan_number = '' OR firm_logo = ''";

	$select_incomple_profile = mysqli_query($conn, $qry_select);
	while ($select_result = mysqli_fetch_assoc($select_incomple_profile)) {
		if (filter_var($select_result['email'], FILTER_VALIDATE_EMAIL)) {

			$message = 'Dear '.$select_result['firm_name'].',<br>'."\n\n".
			'Thank you for registering to the Legumkart!<br>'."\n".
			'We’re mailing to let you know that you have not yet completed your profile process and are missing a few important items.<br>'."\n".
			'Please complete the profile infomation as it beneficial to your future.<br>'."\n\n".
			'Thanks and Regards,<br>'."\n".
			'legumkart.com';

// 			echo $select_result['email']." - ".$message."<br><br>";

			$mails->Subject = "Legumkart - Profile Reminder";
	         $mails->Body  = $message;
	         $mails->addAddress($sendto);
	         $mails->send();
	         $mails->ClearAddresses();
	         $mails->ClearAllRecipients();
		}
	}

?>