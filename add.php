<?php
@session_start();

if(isset($_POST['lawyerid']))
{  
   if(!isset($_SESSION['Lawyer_id']))
   {
   	 $_SESSION['Lawyer_id'] =array();
   }

   if (!in_array($_POST['lawyerid'], $_SESSION['Lawyer_id']))
   {
      $_SESSION['Lawyer_id'][] = $_POST['lawyerid'];
   }
   else
   {  
   	$del_key = array_search($_POST['lawyerid'], $_SESSION['Lawyer_id']);
    unset($_SESSION['Lawyer_id'][$del_key]);
   }
}

?>