<?php

	include ('db_connection.php');
	$id = $_REQUEST['id'];

	if(isset($_REQUEST['field']) && $_REQUEST['field'] == "matter_send"){
    	$user_qry = mysqli_query($conn,"UPDATE tbl_lawyer_cases SET lc_flag = '1' WHERE lc_id = '".$id."'"); 
		if($user_qry){
			echo "ok";
		}
		else{
			echo "error";
		}
	}


	if(isset($_REQUEST['field']) && $_REQUEST['field'] == "counsel_receiv"){
    	$user_qry = mysqli_query($conn,"UPDATE tbl_counsel_cases SET cc_flag = '1' WHERE cc_id = '".$id."'"); 
		if($user_qry){
			echo "ok";
		}
		else{
			echo "error";
		}
	}

	if(isset($_REQUEST['field']) && $_REQUEST['field'] == "quote_receiv"){
    	$user_qry = mysqli_query($conn,"UPDATE tbl_quotes SET flag_quote = '1' WHERE quote_id = '".$id."'"); 
		if($user_qry){
			echo "ok";
		}
		else{
			echo "error";
		}
	}

?>