<?php 
include ('db_connection.php');
$string_query = "";
$item_per_page = 10;
$emp_ids = "";

if(isset($_POST['page'])){
  $_SESSION['counselor_cases_pagination_page'] = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
  $page_number = $_SESSION['counselor_cases_pagination_page'];
  if(!is_numeric($page_number)){
    die('Invalid page number!');
    $_SESSION['counselor_cases_pagination_page'] = 1;
  }
}
else
{
  $page_number = $_SESSION['counselor_cases_pagination_page'];
}

$position = (($page_number-1) * $item_per_page);


if(isset($_SESSION['counselor_cases_search']) && $_SESSION['counselor_cases_search'] != ""){
    $search_result12 = strtoupper($_SESSION['counselor_cases_search']);
    set_error_handler (
        function($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, $errno, 0, $errfile, $errline);     
        }
    );

    try {
      $date_query = date_create($search_result12);
      $date_query = "  OR cc_next_date = '".date_format($date_query,"Y-m-d")."'";
    }
    catch(Exception $e) {
      $date_query = "";
    }

    $date_vs = explode(" VS",$search_result12);
    if(isset($date_vs[0]) && isset($date_vs[1]) && $date_vs[0] != ""){
      $date_vs1 = $date_vs[0];
      if($date_vs[1] != ""){
        $date_vs2 = $date_vs[1];
      }
      else{
          $date_vs2 = 'data not available215';
      }
    }
    else{
      $date_vs1 = $search_result12;
      $date_vs2 = $search_result12;
    }
    
    $data_1 = "";
    if(strpos("NO", $search_result12) !== false){
      $data_1 = " OR cc_status = 'Not Available'";
    }

    $string_query = " AND (judge_name like '%".$search_result12."%' OR court_name like '%".$search_result12."%' OR court_number like '%".$search_result12."%' OR cc_type like '%".$search_result12."%' OR party_b like '%".$date_vs2."%' OR party_a like '%".$date_vs2."%' OR party_b like '%".$date_vs1."%' OR party_a like '%".$date_vs1."%' ".$date_query.$data_1.")";
}


$qry_address = "SELECT counsel_address FROM tbl_counsel WHERE counsel_id = '".$_SESSION['user_id']."'";
$address_result = mysqli_query($conn, $qry_address);
$address_result = mysqli_fetch_assoc($address_result);

   $srl = 0;
    $counselcases = mysqli_query($conn,"SELECT t1.*, t2.* FROM tbl_counsel_cases t1, tbl_cases t2 WHERE t1.cc_case_id=t2.case_id AND t1.cc_status!='Accepted' AND counsel_id = '".$_SESSION['user_id']."'".$string_query." LIMIT $position, $item_per_page");

if(mysqli_num_rows($counselcases)>0){

   while($counselcasesrow=mysqli_fetch_assoc($counselcases))
   { 
    
    $counsellawyer = mysqli_query($conn,"SELECT * FROM tbl_lawyers WHERE lawyer_id='".$counselcasesrow['lawyer_id']."'");
    $counsellawyerrow=mysqli_fetch_array($counsellawyer);
    
  ?> 
               <tr>
                  <!-- <td><?php //echo $srl;?></td> -->
                   <td><u><a href="#" onclick="viewLawyerDetails(<?php echo "'".$counsellawyerrow['phone']."','".$counsellawyerrow['email']."'"; ?>)" title="View Lawyer Details"><?php echo $counsellawyerrow['firm_name'];?></a></u></td>

                  <td><?php echo $counselcasesrow['judge_name'];?></td>
                  <td><?php echo $counselcasesrow['court_name']." (".$counselcasesrow['court_number'].")";?></td>
                  <td><?php echo date_format (new DateTime($counselcasesrow['cc_next_date']), 'd-M-y');
                    if($counselcasesrow['cc_hearing_time'] != "00:00:00"){
                      echo " (".date_format(date_create($counselcasesrow['cc_hearing_time']), 'g:i A').")";
                    }                                    

                  ?></td>
                  <td><?php echo $counselcasesrow['party_a'].' vs '.$counselcasesrow['party_b'];?></td>

                  <td><?php echo $counselcasesrow['cc_type']; ?></td>

                  <td class="toggle_btns">
                     <input id="toggle-on<?php echo $srl;?>" class="radio_btn toggle-left" name="toggle<?php echo $srl;?>" value="false" type="radio" onclick="javascript:yesno(<?php echo "'".$srl."','".$counselcasesrow['cc_id']."','".$counselcasesrow['cc_type']."','".$address_result['counsel_address']."','".$counselcasesrow['cc_next_date']."'";?>);">
                     <label for="toggle-on<?php echo $srl;?>" class="btn" <?php if($counselcasesrow['cc_flag'] == 2){ echo "style='background: #fff'"; }?>>Yes</label>
                     <input id="toggle-off<?php echo $srl;?>" class="radio_btn toggle-right" name="toggle<?php echo $srl;?>" value="true" type="radio" <?php 
                     if($counselcasesrow['cc_status'] != "Not Available")
                      { 
                        $value_enter = "'".$srl."','".$counselcasesrow['cc_id']."','".$counselcasesrow['cc_type']."','".$address_result['counsel_address']."','".$counselcasesrow['cc_next_date']."'";
                        echo 'onclick="yesno('.$value_enter.')"'; 
                      }
                    ?>  <?php 
                     if($counselcasesrow['cc_status'] == "Not Available")
                      { 
                        echo "checked"; 
                      }
                    ?>>
                     <label for="toggle-off<?php echo $srl;?>" class="btn" <?php if($counselcasesrow['cc_flag'] == 2){ echo "style='background: #fff'"; }?>>No</label>
                  </td>
               </tr>
<?php $srl++; 
      }
      echo "<input type='hidden' class='NumberOfCases' value = 'Showing ".((($page_number - 1)*10)+1)." To ".((($page_number - 1)*10)+$srl)." Of '>";
}
else{
      echo '<tr><td colspan="7"><div class="col-sm-7 col-xs-12 padding-right-none"><div class="no_result"><img src="img/about.png"><p>No Results Found.</p></div></div></td></tr>';
      echo "<input type='hidden' class='NumberOfCases' value = 'Showing 0 To 0 Of '>";
} 

?>