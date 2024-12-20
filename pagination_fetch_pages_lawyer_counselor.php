


<?php 
include ('db_connection.php');
$string_query = "";
$item_per_page = 10;
$emp_ids = "";
$string_query = "";

if(isset($_POST['page'])){
	$_SESSION['counsel_pagination_page'] = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	$page_number = $_SESSION['counsel_pagination_page'];
	if(!is_numeric($page_number)){
	 	die('Invalid page number!');
	 	$_SESSION['counsel_pagination_page'] = 1;
	}
}
else
{
	$page_number = $_SESSION['counsel_pagination_page'];
}



if(isset($_SESSION['councel_search']) && $_SESSION['councel_search'] != ""){

            $search_result12 = strtoupper($_SESSION['councel_search']);

            if($search_result12 == "AVAILABLE"){
              $search_result12 = "Accepted";
            }
            set_error_handler (
                function($errno, $errstr, $errfile, $errline) {
                    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);     
                }
            );

            try {
              $date_query = date_create($search_result12);
              $date_query = "  OR t1.cc_next_date = '".date_format($date_query,"Y-m-d")."'";
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

            $string_query = " AND (t2.court_name like '%".$search_result12."%' OR t2.judge_name like '%".$search_result12."%' OR t1.cc_type like '%".$search_result12."%' OR t1.cc_status like '%".$search_result12."%' OR t2.party_b like '%".$date_vs2."%' OR t2.party_a like '%".$date_vs2."%' OR t2.party_b like '%".$date_vs1."%' OR t2.party_a like '%".$date_vs1."%' ".$date_query.")";
          }




   
  //get current starting point of records
	$position = (($page_number-1) * $item_per_page);

if($_SESSION['user_firm_id'] != 0)
{
    $user_qry = mysqli_query($conn,"SELECT lawyer_id FROM tbl_lawyers WHERE firm_id='".$_SESSION['user_firm_id']."' OR lawyer_id='".$_SESSION['user_firm_id']."'"); 
    while($totuserrows = mysqli_fetch_array($user_qry))
    {
      $emp_ids.=$totuserrows['lawyer_id'].",";
    }
    $emp_ids = chop($emp_ids,",");
}

if($_SESSION['user_type'] == 'firm')
{
    
    $user_qry = mysqli_query($conn,"SELECT lawyer_id FROM tbl_lawyers WHERE firm_id='".$_SESSION['user_id']."' OR lawyer_id='".$_SESSION['user_id']."'"); 
    while($totuserrows = mysqli_fetch_array($user_qry))
    {
      $emp_ids.=$totuserrows['lawyer_id'].",";
    }
    $emp_ids = chop($emp_ids,",");
}

$srl =1;
  
if($emp_ids != '') 
{
  $counselcases = mysqli_query($conn,"SELECT t1.*, t2.* FROM tbl_counsel_cases t1, tbl_cases t2 WHERE t1.cc_case_id=t2.case_id AND t2.lawyer_id IN ($emp_ids) ".$string_query." ORDER BY cc_id DESC LIMIT $position, $item_per_page");
}
else
{
   $counselcases = mysqli_query($conn,"SELECT t1.*, t2.* FROM tbl_counsel_cases t1, tbl_cases t2 WHERE t1.cc_case_id=t2.case_id  AND t2.lawyer_id='".$_SESSION['user_id']."'".$string_query." ORDER BY cc_id DESC LIMIT $position, $item_per_page");
}

if(mysqli_num_rows($counselcases) > 0){

while($counselcasesrow=mysqli_fetch_assoc($counselcases))
   { 
    
    $counselnme = mysqli_query($conn,"SELECT * FROM tbl_counsel WHERE counsel_id='".$counselcasesrow['counsel_id']."'");
    $counselnmerow=mysqli_fetch_array($counselnme);

    if($counselcasesrow['cc_date']=='0000-00-00')
    {
       $counseldate = 'NA';
    }
    else
    {
       $counseldate = date_format (new DateTime($counselcasesrow['cc_date']), 'd-M-y');
    }

    if($counselcasesrow['cc_time']=='00:00:00')
    {
       $counseltime = 'NA';
    }
    else
    {
       $counseltime = $counselcasesrow['cc_time'];
    }
?> 
                             <tr class="row_click" <?php if($counselcasesrow['cc_flag'] == 0 && $counselcasesrow['lawyer_id'] == $_SESSION['user_id']){ echo "style='cursor:pointer'"; }?> id="change_color<?php echo $counselcasesrow['cc_id'];?>">
                                <?php
                                	echo "<td><a href='#' class='open_cases_details".$counselcasesrow['cc_id']." open_cases_details' onclick='open_cases_details(this,".$counselcasesrow['cc_id'].")' ><i class='fa fa-plus-circle' aria-hidden='true'></i></a>
                                		<a href='#' class='close_cases_details".$counselcasesrow['cc_id']." close_cases_details' onclick='close_cases_details(this,".$counselcasesrow['cc_id'].")' style='display:none' ><i class='fa fa-minus-circle' aria-hidden='true'></i></a>
                                	</td>";
                                ?>

                                <td><?php echo $counselnmerow['counsel_name'];?></td>
                                <td><?php echo $counselcasesrow['court_name'];?></td>
                                <td><?php echo $counselcasesrow['judge_name'];?></td>
                                <td class="action" <?php if($counselcasesrow['cc_flag'] == 0 && $counselcasesrow['lawyer_id'] == $_SESSION['user_id']){ echo "style='color: red'"; }?>><?php if($counselcasesrow['cc_status'] == "Accepted"){ echo "Available";}else{ echo $counselcasesrow['cc_status']; } ?></td>

                                <?php
                                	echo "</tr>
						                    <tr class='show_data_on_click".$counselcasesrow['cc_id']." hide_data_on_click' style='display:none'>
						                    <td colspan='5'>
						                    <table>";
                                ?>

                                <tr>
                                	<td><b>Court Hearing Date</b></td>
                                	<td><?php echo date_format(new DateTime($counselcasesrow['cc_next_date']), 'd-M-y');?></td>
                                </tr>

                                <tr>
                                	<td><b>Type:</b></td>
                                	<td>
                                    <u><a href="#conselordetail<?php echo $srl;?>"  data-toggle="modal" title="Court Details"><?php echo $counselcasesrow['cc_type'];?></a></u>

                                  <div class="zind modal fade" id="conselordetail<?php echo $srl;?>">
                                     <div class="modal-dialog">
                                        <div class="modal-content">
                                           <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                                           <div class="modal-header">
                                              <h3><i class="pe-7s-users"></i> <?php if($counselcasesrow['cc_type'] == 'Conference'){echo "Conference";}else{echo "Hearing";} ?> Details (<?php echo $counselnmerow['counsel_name']; ?>)</h3>
                                           </div>
                                           <div class="modal-body">

                                              <?php if($counselcasesrow['cc_type'] == 'Conference'){ ?>

                                              <div class="table_box">
                                                 <div class="heading">
                                                       <div class="cell">
                                                          <p>Conference Date</p>
                                                       </div>
                                                       <div class="cell">
                                                          <p>Conference Time</p>
                                                       </div>
                                                       <div class="cell">
                                                          <p>Conference Place</p>
                                                       </div>
                                                  </div>
                                                  <div class="sub_heading">
                                                      <div class="sub_cell">
                                                          <?php echo $counseldate;?>
                                                      </div>
                                                      <div class="sub_cell">
                                                          <?php echo $counseltime;?>
                                                      </div>
                                                      <div class="sub_cell">
                                                          <?php echo $counselcasesrow['cc_place'];?>
                                                      </div>
                                                  </div>
                                              </div>
                                            <?php }
                                            else if($counselcasesrow['cc_type'] == 'Hearing'){?>

                                                <div class="table_box">
                                                 <div class="heading">
                                                       <div class="cell">
                                                          <p>Hearing Date</p>
                                                       </div>
                                                       <div class="cell">
                                                          <p>Hearing Time</p>
                                                       </div>
                                                  </div>
                                                  <div class="sub_heading">
                                                      <div class="sub_cell">
                                                          <?php echo date_format(new DateTime($counselcasesrow['cc_next_date']), 'd-M-y');?>
                                                      </div>
                                                      <div class="sub_cell">
                                                          <?php echo date_format(date_create($counselcasesrow['cc_hearing_time']), 'g:i A');?>
                                                      </div>
                                                  </div>
                                              </div>

                                              <?php } ?>
                                           </div>
                                        </div>
                                     </div>
                                  </div>
                                  </td>
                                </tr>

                                <tr>
                                	<td><b>Parties:</b></td>
                                	<td><?php echo $counselcasesrow['party_a'].' vs '.$counselcasesrow['party_b'];?></td>
                                </tr>

                                <tr>
                                	<td><b>Bill:</b></td>
                                	<td>
                                  <?php if($counselcasesrow['cc_bill_pdf'] != ""){ ?>
                                      <a target="_blank" href="admin/uploads/<?php echo $counselcasesrow['cc_bill_pdf']; ?>.pdf"><u>View</u></a>
                                  <?php }?>
                                	</td>
                                </tr>
                                </table>
                                </td>
                             </tr>
<?php $srl++; } 

	$srl = $srl - 1;
	echo "<input type='hidden' class='NumberOfCases' value = 'Showing ".((($page_number - 1)*10)+1)." To ".((($page_number - 1)*10)+$srl)." Of '>";

    }
    else{
    	echo '<tr><td colspan="5"><div class="col-sm-7 col-xs-12 padding-right-none"><div class="no_result"><img src="img/about.png"><p>No Results Found.</p></div></div></td></tr>';
          echo "<input type='hidden' class='NumberOfCases' value = 'Showing 0 To 0 Of '>";
    }
?>
