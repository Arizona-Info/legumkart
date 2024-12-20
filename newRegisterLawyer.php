<?php
	
	require_once 'db_connection.php';
	$previousDate = date('Y-m-d', strtotime(date('Y-m-d'). ' - 1 day'));
	
	$sql = "SELECT firm_name, phone, type, firm_id FROM tbl_lawyers	WHERE reg_date LIKE '%".$previousDate."%'";
	$result = mysqli_query($conn, $sql);

	if(mysqli_num_rows($result)<1){
		exit();
	}

	$tableContain = '';
	while ($finalResult = mysqli_fetch_assoc($result)) {
		$lawyerType = '';
		if($finalResult['firm_id'] != 0){
			$sql = "SELECT firm_name FROM tbl_lawyers WHERE lawyer_id = '".$finalResult['firm_id']."'";
			$result2 = mysqli_query($conn, $sql);
			$finalResult2 = mysqli_fetch_assoc($result2);
			$lawyerType = $finalResult2['firm_name'];
		}

		$tableContain .= '<tr><td>'.$finalResult['firm_name'].'</td><td>'.$finalResult['phone'].'</td><td>'.$finalResult['type'].'</td><td>'.$lawyerType.'</td></tr>';
	}



	include("PHPMailer/PHPMailerAutoload.php");
    $to    = 'rsvns06@gmail.com';
    $toCC    = 'info@arizonamediaz.com';

    $mails = new PHPMailer;
    $mails->isSMTP();                                       // Set mailer to use SMTP
    $mails->Host = 'legumkart.com';               // Specify main and backup SMTP servers
    $mails->SMTPAuth = true;                               // Enable SMTP authentication
    $mails->Username = 'admin@legumkart.com';                                // SMTP username
    $mails->Password = 'admin@123908';                         // SMTP password
    $mails->SMTPSecure = 'ssl';                             // Enable TLS encryption, `ssl` also accepted
    $mails->Port =  465;                                    // TCP port to connect to
    
    $mails->setFrom ('admin@legumkart.com');
    $mails->addAddress($to,''); 
    $mails->addCC($toCC, 'Arizona Mediaz');

    $mails->Subject = "Legumkart - Registered User Lists";

   	$message = 	"Hi,<br>
   				New lawyer registered details given below<br><br>
   				<table border='1' style='width: 100%'>
					<thead>
						<tr align='center'>
						<tr>
							<td>Name</td>
							<td>Phone</td>
							<td>Type</td>
							<td>Under Firm</td>
						</tr>
					</thead>
					<tbody>
						".$tableContain."
					</tbody>
				</table>";

	$mails->Body  = $message;
	$mails->IsHTML(true);
	$mails->send();

?>