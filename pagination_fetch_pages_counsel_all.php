<?php 
include ('db_connection.php');
$string_query = "";
$item_per_page = 10;

if(isset($_POST['page'])){
  $_SESSION['all_counsel_pagination_page'] = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
  $page_number = $_SESSION['all_counsel_pagination_page'];
  if(!is_numeric($page_number)){
    die('Invalid page number!');
    $_SESSION['all_counsel_pagination_page'] = 1;
  }
}
else
{
  $page_number = $_SESSION['all_counsel_pagination_page'];
}


$position = (($page_number-1) * $item_per_page);

if(isset($_SESSION['all_counsel_search']) && $_SESSION['all_counsel_search'] != ""){
    $search_result12 = strtoupper($_SESSION['all_counsel_search']);
    set_error_handler (
        function($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, $errno, 0, $errfile, $errline);     
        }
    );

    try {
      $date_query = date_create($search_result12);
      $date_query = " OR cc_date = '".date_format($date_query,"Y-m-d")."' OR cc_next_date = '".date_format($date_query,"Y-m-d")."'";
    }
    catch(Exception $e) {
      $date_query = "";
    }

    
    $data_1 = "";
    if(strpos("ATTENDED", $search_result12) !== false){
      $data_1 = " OR cc_action LIKE '%Attended%'";
    }
    else if(strpos("NOT ATTENDED", $search_result12) !== false){
      $data_1 = " OR (cc_bill_pdf = '' AND (cc_action LIKE '%Not Attended%'  OR cc_action LIKE '%Pending%'))";
    }
    else if(strpos("GENERATE BILL", $search_result12) !== false){
      $data_1 = " OR cc_bill_pdf = ''";
    }
    else if(strpos("VIEW", $search_result12) !== false || strpos("BILL", $search_result12) !== false || strpos("INVOICE", $search_result12) !== false){
      $data_1 = " OR cc_bill_pdf != ''";
    }

    $string_query = " AND (judge_name like '%".$search_result12."%' OR court_name like '%".$search_result12."%' OR court_number like '%".$search_result12."%' OR cc_type like '%".$search_result12."%'".$date_query.$data_1.")";
}

   $srl =0;
   $counselcases = mysqli_query($conn,"SELECT t1.*, t2.* FROM tbl_counsel_cases t1, tbl_cases t2 WHERE t1.cc_case_id=t2.case_id AND t1.cc_status='Accepted' AND t1.counsel_id = '".$_SESSION['user_id']."'".$string_query." ORDER BY cc_id DESC LIMIT $position, $item_per_page");

if(mysqli_num_rows($counselcases)>0){

   while($counselcasesrow=mysqli_fetch_assoc($counselcases))
   { 
    
    $counsellawyer = mysqli_query($conn,"SELECT * FROM tbl_lawyers WHERE lawyer_id='".$counselcasesrow['lawyer_id']."'");
    $counsellawyerrow=mysqli_fetch_array($counsellawyer);
?>    
  <tr>
      <td><u><a href="#" onclick="lawyerDetails(<?php echo "'".$counsellawyerrow['phone']."','".$counsellawyerrow['email']."','".$counselcasesrow['court_number']."','".$counselcasesrow['court_name']."','".$counselcasesrow['judge_name']."','".$counselcasesrow['case_number']."','".$counselcasesrow['client_name']."','".$counselcasesrow['party_a']."','".$counselcasesrow['party_b']."','".date_format (new DateTime($counselcasesrow['cc_next_date']), 'd-M-y')."'"; ?>)" title="View Lawyer/Court Details"><?php echo $counsellawyerrow['firm_name'];?></a></u></td>
      
      <td>
        <?php if($counselcasesrow['cc_type'] == 'Conference'){?>
          <u><a href="#" onclick="conferenceDetails(<?php echo "'".$counsellawyerrow['firm_name']."','".$counselcasesrow['cc_place']."'"; ?>)" title="View Conference Place"><?php echo $counselcasesrow['cc_type'];?></a></u>
         <?php }else if($counselcasesrow['cc_type'] == 'Hearing'){?>
             <p>Hearing</p>
         <?php }else{ echo ""; }?>
      </td>

    <?php if($counselcasesrow['cc_type'] == 'Conference'){ ?>
        <td><?php echo date_format (new DateTime($counselcasesrow['cc_date']), 'd-M-y');?>/<?php echo date_format(date_create($counselcasesrow['cc_time']), 'g:i A');?></td>
    <?php }else if($counselcasesrow['cc_type'] == 'Hearing'){?> 
        <td><?php echo date_format(new DateTime($counselcasesrow['cc_next_date']), 'd-M-y');?>/<?php echo date_format(date_create($counselcasesrow['cc_hearing_time']), 'g:i A');?></td>
    <?php }else{ echo "<td></td>"; }?> 

      <td>
        <form>
          <?php if($counselcasesrow['cc_bill_pdf'] == ""){?>
          <!-- <div class="form-group"> -->
          <?php 
          $result1 = mysqli_query($conn,"SELECT cc_action FROM tbl_counsel_cases  WHERE cc_id=".$counselcasesrow['cc_id']);
          $row = mysqli_fetch_array($result1);
          ?>   
          <div class="form-group">  
            <select id="mydiv_<?php echo $counselcasesrow['cc_id']; ?>" class="form-control" onchange="saveStatus('<?php echo $counselcasesrow['cc_id'];?>',this.value)">
              <?php if($row['cc_action'] == "Pending"){ ?><option value="">-Select Action-</option><?php } ?>
              <option <?php if($row['cc_action']=="Attended") { echo "selected"; } ?>>Attended</option>
              <option <?php if($row['cc_action']=="Not Attended") { echo "selected"; } ?>>Not Attended</option>
            </select>     
          </div>
          <?php }else{ echo "Attended";} ?>
        </form>
      </td>

      <td>
      <?php if($counselcasesrow['cc_bill_pdf'] == ""){ ?>
      <u><a href="#" onclick="generateBillBtn(<?php echo "'".$counselcasesrow['cc_id']."','".$counsellawyerrow['lawyer_id']."','".$counselcasesrow['cc_type']."','".$counsellawyerrow['firm_name']."'"; ?>)" title="Court Details" <?php if($row['cc_action']=="Not Attended") { echo 'style="display: none;"'; } ?> id="visible_<?php echo $counselcasesrow['cc_id'];?>">Generate Bill</a></u>

      <?php }else if($counselcasesrow['cc_bill_pdf'] != ""){ ?>
          <a target="_blank" href="admin/uploads/<?php echo $counselcasesrow['cc_bill_pdf']; ?>.pdf" ><u>View</u></a>
      <?php } ?>
      </td>
</tr>

    <?php $srl++; } 
        echo "<input type='hidden' class='NumberOfCasesAll' value = 'Showing ".((($page_number - 1)*10)+1)." To ".((($page_number - 1)*10)+$srl)." Of '>";
    }
    else{
      echo '<tr><td colspan="5"><div class="col-sm-7 col-xs-12 padding-right-none"><div class="no_result"><img src="img/about.png"><p>No Results Found.</p></div></div></td></tr>';
      echo "<input type='hidden' class='NumberOfCasesAll' value = 'Showing 0 To 0 Of '>";
    }

    ?> 