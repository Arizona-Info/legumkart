<?php
include("db_connection.php");
date_default_timezone_set("Asia/Kolkata");
include 'includes/functions.php';
define('SELF', basename($_SERVER['PHP_SELF']));


$proplist = mysqli_query($conn,"SELECT t2.firm_name, t2.lawyer_id, t2.type, t2.firm_id, t2.phone from tbl_cases t1, tbl_lawyers t2 where t1.next_date= date_add(CURDATE(),INTERVAL 1 DAY) AND t1.lawyer_id=t2.lawyer_id");

if(mysqli_num_rows($proplist) > 0)
{   
    $arry_ids = array();
    $arr_singleid = array();

    while($proprow=mysqli_fetch_array($proplist))
    {  
       
        $lawyerid = $proprow['lawyer_id'];
        $lawyers_list = "";+
        $message = "";
        $phone_list ="";

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
              
              $proplisted_lawyers = mysqli_query($conn,"SELECT lawyer_id,phone from tbl_lawyers where firm_id='".$firmidd."' OR lawyer_id='".$firmidd."'");
              while($proprowedd=mysqli_fetch_array($proplisted_lawyers))
              {
                $lawyers_list .= $proprowedd['lawyer_id'].",";
                $arry_ids[] = $proprowedd['lawyer_id'];
                $phone_list .= $proprowedd['phone'].",";
              }
             
              $lawyers_list = chop($lawyers_list,",");
              $phone_list = chop($phone_list,",");

             $prop = "SELECT t1.court_name, t1.court_number, t1.case_number, t1.client_name, t1.next_date, t2.phone, t2.firm_name, t2.lawyer_id, t2.type, t2.firm_id FROM tbl_cases t1, tbl_lawyers t2  WHERE t1.lawyer_id=t2.lawyer_id AND t1.lawyer_id IN ($lawyers_list) AND t1.next_date= date_add(CURDATE(),INTERVAL 1 DAY)";
           
             $proplisted = mysqli_query($conn,$prop);

             $i = 0;
             while($proprowed=mysqli_fetch_array($proplisted))
              {
                if($i/3 == 1){
                  $message .= ",,,";
                }
                $message .= 'Next Date For :'."\n".
                'Court : '.$proprowed['court_name'].'-'.$proprowed['court_number'].', Case No : '.$proprowed['case_number'].' for Client : '.$proprowed['client_name'].' is on '.date_format (new DateTime($proprowed['next_date']), 'd-M-y')."\n"." "."\n";
                $i += 1;
              }
            }
        }
        else 
           if($proprow['firm_id'] == 0 AND $proprow['type'] != 'firm')
           {
                
        if ((!in_array($lawyerid,$arry_ids, TRUE) AND !in_array($lawyerid,$arr_singleid, TRUE)))
            {
              // $sendto = $to;
              $phone_list = $proprow['phone'];
            $propg = "SELECT t1.court_name, t1.court_number, t1.case_number, t1.client_name, t1.next_date, t2.phone, t2.firm_name, t2.lawyer_id, t2.type, t2.firm_id FROM tbl_cases t1, tbl_lawyers t2  WHERE t1.lawyer_id=t2.lawyer_id AND t1.lawyer_id ='".$lawyerid."'  AND t1.next_date= date_add(CURDATE(),INTERVAL 1 DAY)";
           
             $proplistedg = mysqli_query($conn,$propg);

             $i = 0;
             while($proprowedg=mysqli_fetch_array($proplistedg))
              {
                if($i/3 == 1){
                  $message .= ",,,";
                }
                $message .= 'Next Date For :'."\n".
                'Court : '.$proprowedg['court_name'].'-'.$proprowedg['court_number'].', Case No : '.$proprowedg['case_number'].' for Client : '.$proprowedg['client_name'].' is on '.date_format (new DateTime($proprowedg['next_date']), 'd-M-y')."\n"." "."\n";
                $i += 1;
              }
              $arr_singleid[] = $lawyerid;
             } 
           }

           $qry_lim = "SELECT * FROM tbl_limit WHERE lawyer_id = '".$lawyerid."'";
           $select_lim = mysqli_query($conn, $qry_lim);
           $result_lim = mysqli_fetch_assoc($select_lim);
           $msg_len = null;

           if(mysqli_num_rows($select_lim) > 0)
           {

              if($result_lim['limit_sms'] > $result_lim['tot_sms']){
                $msg_len = strlen($message);
                $msg_len = round($msg_len/460);
                $add_limit = (substr_count($phone_list, ',')+1*$msg_len) + $result_lim['tot_sms'];

                $qry_update = "UPDATE tbl_limit SET tot_sms = '".$add_limit."' WHERE lawyer_id = '".$lawyerid."'";
                $update_data = mysqli_query($conn, $qry_update);
                if($update_data){


                  //splite the msg
                  $split_msg = (explode(",,,",$message));
                  $length = count($split_msg);
                  for ($i = 0; $i < $length; $i++) {
                    if($phone_list != ""){
                      $val = $split_msg[$i];
                      sendtransactionsms($phone_list,$val);
                    }
                  }

                }

              }
           }
           else
           {
              $qry_insert = "INSERT INTO tbl_limit(lawyer_id, limit_sms, limit_mail, tot_sms, tot_mail) VALUES('".$lawyerid."',200,200,0,0)";
              $insert_data = mysqli_query($conn, $qry_insert);
              if($insert_data){
                $split_msg = (explode(",,,",$message));
                  
                  //splite the msg
                  $length = count($split_msg);
                  for ($i = 0; $i < $length; $i++) {
                    if($phone_list != ""){
                      $val = $split_msg[$i];
                      sendtransactionsms($phone_list,$val);
                    }
                  }
              }
           }
        
        // $message = implode("",$split_msg);     

    }
  } 

$propnxtdt = mysqli_query($conn,"SELECT t3.firm_name, t3.lawyer_id, t3.type, t3.firm_id, t3.phone from tbl_cases t1, tbl_case_nextdt t2, tbl_lawyers t3 where t1.case_id=t2.next_case_id AND t1.lawyer_id=t3.lawyer_id AND t2.next_case_date= date_add(CURDATE(),INTERVAL 1 DAY)");

if(mysqli_num_rows($propnxtdt) > 0)
  {
    $arry_ids = array();
    $arr_singleid = array();

    while($proprownxt =mysqli_fetch_array($propnxtdt))
    {  
        
        $lawyerid = $proprownxt['lawyer_id'];
        $lawyers_list = "";
        $message = "";
        $phone_list ="";

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

            $proplisted_lawyerss = mysqli_query($conn,"SELECT lawyer_id,phone  from tbl_lawyers where firm_id='".$firmid."' OR lawyer_id='".$firmid."'");
            while($proprowedds=mysqli_fetch_array($proplisted_lawyerss))
              {
                $lawyers_list .= $proprowedds['lawyer_id'].",";
                $arry_ids[] = $proprowedds['lawyer_id'];
                $phone_list .= $proprowedds['phone'].",";
              }

                $lawyers_list = chop($lawyers_list,",");
                $phone_list = chop($phone_list,",");

             $props = "SELECT t1.court_name, t1.court_number, t1.case_number, t1.client_name,t2.next_case_date, t3.phone, t3.firm_name, t3.lawyer_id, t3.type, t3.firm_id from tbl_cases t1, tbl_case_nextdt t2, tbl_lawyers t3 where t1.case_id=t2.next_case_id AND t1.lawyer_id=t3.lawyer_id AND t2.next_case_date= date_add(CURDATE(),INTERVAL 1 DAY) AND t1.lawyer_id IN ($lawyers_list)";
            
             $proplisteds = mysqli_query($conn,$props);

             $i = 0;
             while($proproweds=mysqli_fetch_array($proplisteds))
              {
                if($i/3 == 1){
                  $message .= ",,,";
                }
                 $message .= 'Next Date For :'."\n".
                'Court : '.$proproweds['court_name'].'-'.$proproweds['court_number'].', Case No : '.$proproweds['case_number'].' for Client : '.$proproweds['client_name'].' is on '.date_format (new DateTime($proproweds['next_case_date']), 'd-M-y')."\n"." "."\n";
                $i+=1;
              }

            }
        }
        else 
           if($proprownxt['firm_id'] == 0 AND $proprownxt['type'] != 'firm')
           {

             if ((!in_array($lawyerid,$arry_ids, TRUE) AND !in_array($lawyerid,$arr_singleid, TRUE)))
            {
            
            $phone_list = $proprownxt['phone'];
            $propsz = "SELECT  t1.court_name, t1.court_number, t1.case_number, t1.client_name,t2.next_case_date, t3.phone, t3.firm_name, t3.lawyer_id, t3.type, t3.firm_id from tbl_cases t1, tbl_case_nextdt t2, tbl_lawyers t3 where t1.case_id=t2.next_case_id AND t1.lawyer_id=t3.lawyer_id AND t2.next_case_date= date_add(CURDATE(),INTERVAL 1 DAY) AND t1.lawyer_id = '".$lawyerid."'";
            
             $proplistedsz = mysqli_query($conn,$propsz);

             $i = 0;
             while($proprowedsz=mysqli_fetch_array($proplistedsz))
              {
                if($i/3 == 1){
                  $message .= ",,,";
                }
                $message .= 'Next Date For :'."\n".
                'Court : '.$proprowedsz['court_name'].'-'.$proprowedsz['court_number'].', Case No : '.$proprowedsz['case_number'].' for Client : '.$proprowedsz['client_name'].' is on '.date_format (new DateTime($proprowedsz['next_case_date']), 'd-M-y')."\n"." "."\n";
                $i+=1;
              }
               $arr_singleid[] = $lawyerid;
           }
          }

           $qry_lim = "SELECT * FROM tbl_limit WHERE lawyer_id = '".$lawyerid."'";
           $select_lim = mysqli_query($conn, $qry_lim);
           $result_lim = mysqli_fetch_assoc($select_lim);
           $msg_len = null;

           if(mysqli_num_rows($select_lim) > 0)
           {

              if($result_lim['limit_sms'] > $result_lim['tot_sms']){
                $msg_len = strlen($message);
                $msg_len = round($msg_len/460);
                $add_limit = (substr_count($phone_list, ',')+1*$msg_len) + $result_lim['tot_sms'];

                $qry_update = "UPDATE tbl_limit SET tot_sms = '".$add_limit."' WHERE lawyer_id = '".$lawyerid."'";
                $update_data = mysqli_query($conn, $qry_update);
                if($update_data){


                  //splite the msg
                  $split_msg = (explode(",,,",$message));
                  $length = count($split_msg);
                  for ($i = 0; $i < $length; $i++) {
                    if($phone_list != ""){
                      $val = $split_msg[$i];
                      sendtransactionsms($phone_list,$val);
                    }
                  }

                }

              }
           }
           else
           {
              $qry_insert = "INSERT INTO tbl_limit(lawyer_id, limit_sms, limit_mail, tot_sms, tot_mail) VALUES('".$lawyerid."',200,200,0,0)";
              $insert_data = mysqli_query($conn, $qry_insert);
              if($insert_data){
                $split_msg = (explode(",,,",$message));
                  
                  //splite the msg
                  $length = count($split_msg);
                  for ($i = 0; $i < $length; $i++) {
                    if($phone_list != ""){
                      $val = $split_msg[$i];
                      sendtransactionsms($phone_list,$val);
                    }
                  }
              }
           }

    }
  }

?>