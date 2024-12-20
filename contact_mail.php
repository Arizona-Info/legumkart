<?php
include ('db_connection.php');
// session_start();
if($_POST["captcha"]==$_SESSION["captcha_code"])
{
    $quotesql= mysqli_query($conn,"INSERT INTO tbl_quotes(cust_name, cust_phone, cust_email, cust_query, quote_type, quote_date, lawyer_id, status) VALUES ('".$_POST['user_name']."','".$_POST['user_number']."','".$_POST['user_email']."','".$_POST['query']."','Quick','".date("Y-m-d")."', '".$_POST['lawyer_id']."', 'New')");
	
echo "success";

} else {
 
 echo "Wrong CAPTCHA";
}
?>