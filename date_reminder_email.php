<?php
include("db_connection.php");
date_default_timezone_set("Asia/Kolkata");
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


$proplist = mysqli_query($conn,"SELECT t2.email, t2.firm_name, t2.lawyer_id, t2.type, t2.firm_id from tbl_cases t1, tbl_lawyers t2 where t1.next_date= date_add(CURDATE(),INTERVAL 1 DAY) AND t1.lawyer_id=t2.lawyer_id");

if(mysqli_num_rows($proplist) > 0)
{   
    $arry_ids = array();
    $arr_singleid = array();

    while($proprow=mysqli_fetch_array($proplist))
    {  
        
        $to = $proprow['email'];
        $lawyerid = $proprow['lawyer_id'];
        $sendto ="";
        $lawyers_list = "";+
        $message = "";

    if($proprow['type'] == 'firm' OR $proprow['firm_id'] != 0)
        {  
            if($proprow['firm_id'] != 0)
            {
              $propfirm_idd = mysqli_query($conn,"SELECT firm_id from tbl_lawyers where lawyer_id='".$proprow['lawyer_id']."'");
              $proprofirmidd =mysqli_fetch_array($propfirm_idd);
              $firmidd =  $proprofirmidd['firm_id'];
            }
            else
            {
              $firmidd =  $proprow['lawyer_id'];  
            }
            
            if ((!in_array($lawyerid,$arry_ids, TRUE) AND !in_array($lawyerid,$arr_singleid, TRUE)))
            {
              
              $proplisted_lawyers = mysqli_query($conn,"SELECT lawyer_id,email from tbl_lawyers where firm_id='".$firmidd."' OR lawyer_id='".$firmidd."'");
              while($proprowedd=mysqli_fetch_array($proplisted_lawyers))
              {
                $lawyers_list .= $proprowedd['lawyer_id'].",";
                $sendto .= $proprowedd['email'].",";
                $arry_ids[] = $proprowedd['lawyer_id'];
              }
             
              $lawyers_list = chop($lawyers_list,",");
              $sendto = chop($sendto,",");

             $prop = "SELECT t1.court_name, t1.court_number, t1.case_number, t1.client_name, t1.next_date, t2.email, t2.firm_name, t2.lawyer_id, t2.type, t2.firm_id FROM tbl_cases t1, tbl_lawyers t2  WHERE t1.lawyer_id=t2.lawyer_id AND t1.lawyer_id IN ($lawyers_list) AND t1.next_date= date_add(CURDATE(),INTERVAL 1 DAY)";
           
             $proplisted = mysqli_query($conn,$prop);
             while($proprowed=mysqli_fetch_array($proplisted))
              {
                $message .= 'Next Date For :'."\n".
                'Court : '.$proprowed['court_name'].'-'.$proprowed['court_number'].', Case No : '.$proprowed['case_number'].' for Client : '.$proprowed['client_name'].' is on '.date_format (new DateTime($proprowed['next_date']), 'd-M-y')."\n";
              }
            }
        }
        else 
           if($proprow['firm_id'] == 0 AND $proprow['type'] != 'firm')
           {
                
        if ((!in_array($lawyerid,$arry_ids, TRUE) AND !in_array($lawyerid,$arr_singleid, TRUE)))
            {
            $sendto = $to;
            $propg = "SELECT t1.court_name, t1.court_number, t1.case_number, t1.client_name, t1.next_date, t2.email, t2.firm_name, t2.lawyer_id, t2.type, t2.firm_id FROM tbl_cases t1, tbl_lawyers t2  WHERE t1.lawyer_id=t2.lawyer_id AND t1.lawyer_id ='".$lawyerid."'  AND t1.next_date= date_add(CURDATE(),INTERVAL 1 DAY)";
           
             $proplistedg = mysqli_query($conn,$propg);
             while($proprowedg=mysqli_fetch_array($proplistedg))
              {
                $message .= 'Next Date For :'."\n".
                'Court : '.$proprowedg['court_name'].'-'.$proprowedg['court_number'].', Case No : '.$proprowedg['case_number'].' for Client : '.$proprowedg['client_name'].' is on '.date_format (new DateTime($proprowedg['next_date']), 'd-M-y')."\n";
              }
              $arr_singleid[] = $lawyerid;
             } 
           }

        $mails->Subject = "Case Reminder";
        $mails->Body  = $message;
        
        $var=explode(',',$sendto);
        foreach($var as $row_email)
         {
           $mails->addAddress($row_email);
         }
        
        $mails->send();
        $mails->ClearAddresses();
        $mails->ClearAllRecipients();        

    }
  } 

$propnxtdt = mysqli_query($conn,"SELECT t3.email, t3.firm_name, t3.lawyer_id, t3.type, t3.firm_id from tbl_cases t1, tbl_case_nextdt t2, tbl_lawyers t3 where t1.case_id=t2.next_case_id AND t1.lawyer_id=t3.lawyer_id AND t2.next_case_date= date_add(CURDATE(),INTERVAL 1 DAY)");

if(mysqli_num_rows($propnxtdt) > 0)
  {
    $arry_ids = array();
    $arr_singleid = array();

    while($proprownxt =mysqli_fetch_array($propnxtdt))
    {  
        
        $to = $proprownxt['email'];
        $lawyerid = $proprownxt['lawyer_id'];
        $sendto ="";
        $lawyers_list = "";
        $message = "";
       
       if($proprownxt['type'] == 'firm' OR $proprownxt['firm_id'] != 0) 
        { 
            if($proprownxt['firm_id'] != 0)
            {
              $propfirm_id = mysqli_query($conn,"SELECT firm_id from tbl_lawyers where lawyer_id='".$proprownxt['lawyer_id']."'");
              $proprofirmid =mysqli_fetch_array($propfirm_id);
              $firmid =  $proprofirmid['firm_id'];
            }
            else
            {
              $firmid =  $proprownxt['lawyer_id'];  
            }
            

             if ((!in_array($lawyerid,$arry_ids, TRUE) AND !in_array($lawyerid,$arr_singleid, TRUE)))
            {

            $proplisted_lawyerss = mysqli_query($conn,"SELECT lawyer_id,email from tbl_lawyers where firm_id='".$firmid."' OR lawyer_id='".$firmid."'");
            while($proprowedds=mysqli_fetch_array($proplisted_lawyerss))
              {
                $lawyers_list .= $proprowedds['lawyer_id'].",";
                $sendto .= $proprowedds['email'].",";
                $arry_ids[] = $proprowedds['lawyer_id'];
               
              }

                $lawyers_list = chop($lawyers_list,",");
                $sendto = chop($sendto,",");

             $props = "SELECT t1.court_name, t1.court_number, t1.case_number, t1.client_name,t2.next_case_date, t3.email, t3.firm_name, t3.lawyer_id, t3.type, t3.firm_id from tbl_cases t1, tbl_case_nextdt t2, tbl_lawyers t3 where t1.case_id=t2.next_case_id AND t1.lawyer_id=t3.lawyer_id AND t2.next_case_date= date_add(CURDATE(),INTERVAL 1 DAY) AND t1.lawyer_id IN ($lawyers_list)";
            
             $proplisteds = mysqli_query($conn,$props);
             while($proproweds=mysqli_fetch_array($proplisteds))
              {
                 $message .= 'Next Date For :'."\n".
                'Court : '.$proproweds['court_name'].'-'.$proproweds['court_number'].', Case No : '.$proproweds['case_number'].' for Client : '.$proproweds['client_name'].' is on '.date_format (new DateTime($proproweds['next_case_date']), 'd-M-y')."\n";
              }

            }
        }
        else 
           if($proprownxt['firm_id'] == 0 AND $proprownxt['type'] != 'firm')
           {

             if ((!in_array($lawyerid,$arry_ids, TRUE) AND !in_array($lawyerid,$arr_singleid, TRUE)))
            {
            
            $sendto = $to;
            $propsz = "SELECT  t1.court_name, t1.court_number, t1.case_number, t1.client_name,t2.next_case_date, t3.email, t3.firm_name, t3.lawyer_id, t3.type, t3.firm_id from tbl_cases t1, tbl_case_nextdt t2, tbl_lawyers t3 where t1.case_id=t2.next_case_id AND t1.lawyer_id=t3.lawyer_id AND t2.next_case_date= date_add(CURDATE(),INTERVAL 1 DAY) AND t1.lawyer_id = '".$lawyerid."'";
            
             $proplistedsz = mysqli_query($conn,$propsz);
             while($proprowedsz=mysqli_fetch_array($proplistedsz))
              {
                $message .= 'Next Date For :'."\n".
                'Court : '.$proprowedsz['court_name'].'-'.$proprowedsz['court_number'].', Case No : '.$proprowedsz['case_number'].' for Client : '.$proprowedsz['client_name'].' is on '.date_format (new DateTime($proprowedsz['next_case_date']), 'd-M-y')."\n";
              }
               $arr_singleid[] = $lawyerid;
           }
          }

        $mails->Subject = "Case Reminder";
        $mails->Body  = $message;
        
         $varz=explode(',',$sendto);
         foreach($varz as $row_emailz)
         {
           $mails->addAddress($row_emailz);
         }
         
        $mails->send();
        $mails->ClearAddresses();
        $mails->ClearAllRecipients();

    }
  }

?>