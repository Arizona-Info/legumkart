<?php
		
	include ('db_connection.php');
	$sql_query3 = "UPDATE tbl_counsel_cases SET cc_flag = '0', cc_bill_pdf = '".$_REQUEST['id2']."' WHERE cc_id = '".$_REQUEST['id']."'";
    $result3 = mysqli_query($conn,$sql_query3);

?>