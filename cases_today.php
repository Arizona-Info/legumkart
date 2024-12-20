<!-- ******* todays cases ******* -->   
<div class="tab-pane fade in active" id="today" role="tabpanel">
   <div class="table-responsive action_table">
   <table id="attendenceDetailedTable" class="display responsive nowrap" cellspacing="0" width="100%">
      <!-- <table id="example" class="table table-bordered"> -->
         <thead>
            <tr>
               <?php 
                  if($_SESSION['user_type']=='firm' OR $_SESSION['user_firm_id']!=0)
                   {   ?>
               <th>Lawyer</th>
               <?php
                  }   
                  ?>
               <th>Prev Date</th>
               <th>Court Details</th>
               <th>Case No.</th>
               <th>Name of Parties</th>
               <th>Stage</th>
               <th>Next Date</th>
               <th>Client Name</th>
               <th>Category</th>
               <th>File No.</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            <?php
              $next_case_date = [];
              $next_stage = [];
              $next_judge = [];
              $prev_case_date = [];
              $next_lawyer_id = [];
              $next_date_id = [];
              $lawyer_id = "";

              // Get Number of Lawyer ID - Start
              if($_SESSION['user_type']=='firm'){
                $qry_firm = "SELECT lawyer_id FROM tbl_lawyers WHERE firm_id = '".$_SESSION['user_id']."' OR lawyer_id = '".$_SESSION['user_id']."'";
                $select_qry = mysqli_query($conn, $qry_firm);
                while ($select_result = mysqli_fetch_assoc($select_qry)) {
                  $lawyer_id .= $select_result['lawyer_id'].",";
                }
                $lawyer_id = chop($lawyer_id, ",");
              }
              else if($_SESSION['user_type'] == 'lawyer' && $_SESSION['user_firm_id'] != 0){
                $qry_firm = "SELECT lawyer_id FROM tbl_lawyers WHERE firm_id = '".$_SESSION['user_firm_id']."' OR lawyer_id = '".$_SESSION['user_firm_id']."'";
                $select_qry = mysqli_query($conn, $qry_firm);
                while ($select_result = mysqli_fetch_assoc($select_qry)) {
                  $lawyer_id .= $select_result['lawyer_id'].",";
                }
                $lawyer_id = chop($lawyer_id, ",");
              }
              else{
                $lawyer_id = $_SESSION['user_id'];
              }
              // Get Number of Lawyer ID - End

              //Get number of date in table_ next_date - Start
            //   $next_qry = "SELECT MAX(nextdt_id) AS nextdt_id, next_case_id, next_case_date, next_stage, next_judge, prev_case_date, lawyer_id, nextdt_id FROM tbl_case_nextdt WHERE lawyer_id IN (".$lawyer_id.") AND next_case_date = '".date('Y-m-d')."' GROUP BY next_case_id";
            $next_qry = "SELECT nextdt_id, next_case_id, next_case_date, next_stage, next_judge, prev_case_date, lawyer_id, nextdt_id FROM tbl_case_nextdt WHERE nextdt_id IN (SELECT MAX(nextdt_id) FROM tbl_case_nextdt WHERE lawyer_id IN (".$lawyer_id.") AND next_case_date = '".date('Y-m-d')."' GROUP BY next_case_id)";
            
              $select_next = mysqli_query($conn, $next_qry);

              $cases_id = "";

              while ($select_next_result = mysqli_fetch_assoc($select_next)) {
                $cases_id .= $select_next_result['next_case_id'].',';
                $next_case_date[$select_next_result['next_case_id']] = $select_next_result['next_case_date'];
                $next_stage[$select_next_result['next_case_id']] = $select_next_result['next_stage'];
                $next_judge[$select_next_result['next_case_id']] = $select_next_result['next_judge'];
                $prev_case_date[$select_next_result['next_case_id']] = $select_next_result['prev_case_date'];
                $next_lawyer_id[$select_next_result['next_case_id']] = $select_next_result['lawyer_id'];
                $next_date_id[$select_next_result['next_case_id']] = $select_next_result['nextdt_id'];
              }
              if($cases_id != ""){
                $cases_id = "case_id IN (".chop($cases_id, ",").") OR ";
              }
              //Get number of date in table_ next_date - End
               
              // Get specific date cases - Start
              $qry_cases = "SELECT lawyer_id,case_id, next_date, judge_name, stage, court_number, case_number, party_a, party_b, court_name, client_name, category, file_no, client_phone, client_email FROM tbl_cases WHERE (".$cases_id."next_date = '".date('Y-m-d')."') AND lawyer_id  IN (".$lawyer_id.")  ORDER BY CASE 
                                           WHEN court_name LIKE '%supreme%' THEN '1'
                                           WHEN court_name LIKE '%high%' THEN '2'
                                           WHEN court_name LIKE '%nclt%' THEN '3'
                                           WHEN court_name LIKE '%nclat%' THEN '4'
                                           WHEN court_name LIKE '%ncp%' THEN '5'
                                           WHEN court_name LIKE '%drt%' THEN '6'
                                           WHEN court_name LIKE '%drat%' THEN '7'
                                           WHEN court_name LIKE '%senior%' THEN '8'
                                           WHEN court_name LIKE '%civil%' THEN '9'
                                           WHEN court_name LIKE '%district%' THEN '10'
                                           WHEN court_name LIKE '%family%' THEN '11'
                                           WHEN court_name LIKE '%consumer%' THEN '12'
                                           WHEN court_name LIKE '%state%' THEN '13'
                                           WHEN court_name LIKE '%magistrate%' THEN '12'
                                           WHEN court_name LIKE '%jmfc%' THEN '13'
                                           ELSE court_name END ASC";

              $select_cases = mysqli_query($conn, $qry_cases);
              // $today_count = mysqli_num_rows($select_cases);

              while ($select_cases_result = mysqli_fetch_assoc($select_cases)) {

                $pre_date = "";
                $next_date = "";
                $judge = "";
                $stage = "";
                $nextdt_id = "";
                $att_lawyer_name = "";
                if(isset($prev_case_date[$select_cases_result['case_id']])){
                  $pre_date = $prev_case_date[$select_cases_result['case_id']];
                  $next_date = $next_case_date[$select_cases_result['case_id']];
                  $judge = $next_judge[$select_cases_result['case_id']];
                  $stage = $next_stage[$select_cases_result['case_id']];
                  $att_lawyer_name = $next_lawyer_id[$select_cases_result['case_id']];
                  $nextdt_id = $next_date_id[$select_cases_result['case_id']];
                }
                else{
                  $pre_date = '';
                  $next_date = $select_cases_result['next_date'];
                  $judge = $select_cases_result['judge_name'];
                  $stage = $select_cases_result['stage'];
                  $att_lawyer_name = $select_cases_result['lawyer_id'];
                }

                $qry_lawyer = "SELECT firm_name FROM tbl_lawyers WHERE lawyer_id = '".$att_lawyer_name."'";
                $select_lawyer = mysqli_query($conn, $qry_lawyer);
                $result_lawyer = mysqli_fetch_assoc($select_lawyer);

                // for last max date view - Start
                $next_date2 = "";
                $qry_next_date = "SELECT next_case_date FROM tbl_case_nextdt WHERE next_case_id = '".$select_cases_result['case_id']."' ORDER BY nextdt_id DESC LIMIT 1";
                $select_next_date = mysqli_query($conn, $qry_next_date);
                $result_next_date = mysqli_fetch_assoc($select_next_date);
                // for last max date view - End

               ?>
              <tr>
              
                <?php 
                  if($_SESSION['user_type']=='firm' OR $_SESSION['user_firm_id']!=0)
                  { ?>
                <td><?php echo $result_lawyer['firm_name'];?></td>
                <?php 
                  } ?>

                <?php if($pre_date == ""){ echo "<td>New Case</td>"; }else{ ?>
                <td><?php echo date_format (new DateTime(date('Y-m-d')), 'd-M-y'); ?></td>
                <?php } ?>

                <td><u><a href="#court_detailsb<?php echo $select_cases_result['case_id'];?>"  data-toggle="modal" title="Court Details"><?php echo $select_cases_result['court_name'];?></a></u></td>
               
                <td>
                <?php
                  if($nextdt_id != ""){
                ?>
                <u><a href="#" onclick="allcaseshistory(<?php echo $select_cases_result['case_id'];?>,'<?php echo $select_cases_result['case_number'];?>')" title="Case History"><?php echo $select_cases_result['case_number'];?></a></u>
                <?php }else{ echo $select_cases_result['case_number']; }?>
                </td>

                <td><?php echo $select_cases_result['party_a'].' <strong>Vs</strong> '.$select_cases_result['party_b'];?></td>

                <td <?php if($stage=='Evidence' || $stage=='Part-Heard' || $stage=='Cross' || $stage=='Arguments' || $stage=='Dismissed/Disposed' || $stage=='Withdrawn' || $stage=='Decree') { echo 'style="color:red"'; } ?>><?php echo $stage;?></td>
               
                <?php if($result_next_date['next_case_date'] != date('Y-m-d') && $result_next_date['next_case_date'] != ""){ ?> 
                <td><?php echo date_format (new DateTime($result_next_date['next_case_date']), 'd-M-y');?></td>
                <?php }
                    else if($stage == "Dismissed/Disposed" || $stage == "NOC" || $stage == "Withdrwan" || $stage == "Decree"){
                    echo "<td>".date_format (new DateTime($result_next_date['next_case_date']), 'd-M-y')."</td>";
                  }
                else { ?>
                <td><a href="#nextdate_casesb<?php echo $select_cases_result['case_id'];?>" class="btn btn_sm1" data-toggle="modal" title="Edit"><u>Add</u></a></td>
                <?php } ?>
               
               <td><u><a href="#client_detailsb<?php echo $select_cases_result['case_id'];?>"  data-toggle="modal" title="Client Details"><?php echo $select_cases_result['client_name'];?></a></u></td>

               <td><?php echo $select_cases_result['category'];?></td>
               
               <td><?php echo $select_cases_result['file_no'];?></td>
               
               <td class="action">
                   <div class="action_btn">
                      <button class="btn btn_sm dropdown-toggle" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-tasks"></i></button>
                      <ul class="dropdown-menu" aria-labelledby="actionDropdown">
                         <li>
                            <form action="lawyer_availability.php" method="post">
                               <input type="hidden" name="send_case_id" value="<?php echo $select_cases_result['case_id'];?>">
                               <button type="submit" class="btn btn_sm1" title="Send To Lawyer" name="send_to_lawyer"><i class="fa fa-upload"></i> Send To Lawyer</button>
                            </form>
                         </li>
                         <li>
                            <form action="counsel_availability.php" method="post">
                              <input type="hidden" name="send_case_id" value="<?php echo $select_cases_result['case_id'];?>">
                              <input type="hidden" name="case_datee" value="<?php echo $next_date;?>">
                              <button type="submit" class="btn btn_sm1" title="Send To Counsel" name="send_to_counsell"><i class="fa fa-upload"></i> Send To Counsel</button>
                            </form>
                         </li>

                         <?php if($rows_package > 0)
                       { ?>
                         <li>
                            <?php   
                             // For Package Users With 1 GB 
                           if($row_tot_files_size['tot_files_size'] < $allocated_space_inbytes)
                            {  ?>
                            <a href="#" class="btn btn_sm1" onclick="upload_files('<?php echo $select_cases_result['case_id']; ?>','<?php echo $select_cases_result['case_number']; ?>')" data-toggle="modal" title="Upload Files"><i class="fa fa-upload"></i> Upload Files</a>
                            <?php    } else { ?>
                            <a href="#" class="btn btn_sm1" onclick="restrict_upload('<?php echo $select_cases_result['case_number']; ?>')" data-toggle="modal" title="Upload Files"><i class="fa fa-upload"></i> Upload Files</a> 
                             <?php    } ?>
                         </li>
                         <li>
                           <form action="upload.php" method="post">
                                <input type="hidden" name="view_case_id" value="<?php echo $select_cases_result['case_id'];?>">
                                <button type="submit" class="btn btn_sm1" title="View Uploaded case files" name="viewcasefiles"><i class="fa fa-eye" aria-hidden="true"></i> View Uploaded Files</button>
                             </form>
                         </li>
                         <?php 
                       } else 
                       {  ?>
                         
                         <li>
                            <?php    
                            // For Demo Users With 1 GB
                           if($row_tot_files_size['tot_files_size'] < $one_gb_demo_space)
                            {  ?>
                            <a href="#" class="btn btn_sm1" onclick="upload_files('<?php echo $select_cases_result['case_id']; ?>','<?php echo $select_cases_result['case_number']; ?>')" data-toggle="modal" title="Upload Files"><i class="fa fa-upload"></i> Upload Files</a>
                            <?php    } else { ?>
                                <a href="#" class="btn btn_sm1" onclick="restrict_upload('<?php echo $select_cases_result['case_number']; ?>')" data-toggle="modal" title="Upload Files"><i class="fa fa-upload"></i> Upload Files</a> 
                             <?php   } ?>
                         </li>
                         <li>
                           <form action="upload.php" method="post">
                                <input type="hidden" name="view_case_id" value="<?php echo $select_cases_result['case_id'];?>">
                                <button type="submit" class="btn btn_sm1" title="View Uploaded case files" name="viewcasefiles"><i class="fa fa-eye" aria-hidden="true"></i> View Uploaded Files</button>
                             </form>
                         </li>
                       <?php 
                     } ?>

                         <li>
                            <a href="#" class="btn_sm1" onclick="update_cases(<?php echo "'".$nextdt_id."',".$select_cases_result['case_id'].",'".$select_cases_result['court_name']."','".$select_cases_result['court_number']."','".$select_cases_result['case_number']."','".$judge."','".$select_cases_result['category']."','".$select_cases_result['client_name']."','".$select_cases_result['client_phone']."','".$select_cases_result['client_email']."','".$select_cases_result['file_no']."','".$select_cases_result['party_a']."','".$select_cases_result['party_b']."','".$result_next_date['next_case_date']."','".$stage."'"; ?>)" title="Edit"><i class="fa fa-edit"></i> Edit</a>
                         </li>
                         <?php
                            if(isset($_SESSION['user_firm_id']) && $_SESSION['user_firm_id'] == 0)
                               {
                            ?>
                         <li>
                            <a href="#delete_casesb<?php echo $select_cases_result['case_id'];?>" class="btn btn_sm1" data-toggle="modal" title="Delete"><i class="fa fa-trash"></i> Delete</a>
                         </li>
                         <?php } ?>
                      </ul>
                   </div>
               </td>
            </tr>
            
            <div class="zind modal fade" id="court_detailsb<?php echo $select_cases_result['case_id'];?>">
               <div class="modal-dialog">
                  <div class="modal-content">
                     <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                     <div class="modal-header">
                        <h3><i class="pe-7s-users"></i> Court Details</h3>
                     </div>
                     <div class="modal-body">
                        <div class="table_box">
                           <div class="heading">
                                 <div class="cell">
                                    <p>Court Number</p>
                                 </div>
                                 <div class="cell">
                                    <p>Judge Name</p>
                                 </div>
                            </div>
                            <div class="sub_heading">
                                <div class="sub_cell">
                                    <p><?php echo $select_cases_result['court_number'];?></p>
                                </div>
                                <div class="sub_cell">
                                    <p><?php echo $judge;?></p>
                                </div>
                            </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            
            <div class="zind modal fade" id="client_detailsb<?php echo $select_cases_result['case_id'];?>">
               <div class="modal-dialog">
                  <div class="modal-content">
                     <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                     <div class="modal-header">
                        <h3><i class="pe-7s-users"></i> Client Details</h3>
                     </div>
                     <div class="modal-body">
                        <div class="table_box">
                           <div class="heading">
                                 <div class="cell">
                                    <p>Client Phone</p>
                                 </div>
                                 <div class="cell">
                                    <p>Client Email</p>
                                 </div>
                           </div>
                           <div class="sub_heading">
                                <div class="sub_cell">
                                    <p><?php echo $select_cases_result['client_phone'];?></p>
                                </div>
                                <div class="sub_cell">
                                   <p><?php echo $select_cases_result['client_email'];?></p>
                                </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>

            <div class="zind modal fade" id="nextdate_casesb<?php echo $select_cases_result['case_id'];?>">
               <div class="modal-dialog">
                  <div class="modal-content">
                     <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                     <div class="modal-header">
                        <h3><i class="pe-7s-users"></i> Next Date</h3>
                     </div>
                     <div class="modal-body">
                        <form class="form" role="form" method="post" action="">
                           <input type="hidden" name="next_case_id" value="<?php echo $select_cases_result['case_id'];?>"> 
                           <input type="hidden" name="prev_case_date" value="<?php echo date('Y-m-d'); ?>">
                                
                            <input type="hidden" name="mobile_for_sms" value="<?php echo $select_cases_result['client_phone']; ?>">
                           <input type="hidden" name="case_number" value="<?php echo $select_cases_result['case_number']; ?>">
                           <input type="hidden" name="court_name" value="<?php echo $select_cases_result['court_name']; ?>">
                           
                           <div class="col-md-6">
                              <div class="form-group">
                                 <input class="form-control example1" name="next_case_date" placeholder="Next Date" type="text" value="" autocomplete="off" required>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <select class="form-control" name="next_stage" onchange="showDivz(this.value,<?php echo $select_cases_result['case_id'];?>)" required>
                                    <option value="">-- Select Stage --</option>
                                    <?php

                                      $qry = "SELECT stage_name FROM tbl_stage WHERE status = '1'";
                                      $result = mysqli_query($conn, $qry);
                                      while ($result2 = mysqli_fetch_assoc($result)) { ?>
                                          <option <?php if($stage == $result2['stage_name']) { echo "selected"; } ?>><?php echo $result2['stage_name'];?></option>
                                      <?php }
                                        if($stage != $result2['stage_name']) 
                                           { ?>
                                             <option selected><?php echo $stage; ?></option>
                                     <?php } 
                                    ?>
                                    <option value="other" style="color:red"><strong>Other</strong></option>
                                 </select>
                              </div>
                           </div>
                           <div id="hide_show_div<?php echo $select_cases_result['case_id'];?>"  style="display: none">
                            <div class="col-md-6 col-xs-12">
                               <div class="form-group">
                                  <input class="form-control" name="newstage" placeholder="Enter New Stage" type="text" style="background:yellow">
                               </div>
                            </div>
                          </div>

                           <div class="col-md-6">
                              <div class="form-group">
                                 <input class="form-control" name="judge_name" placeholder="Judge Name" type="text" value="<?php echo $judge; ?>" autocomplete="off">
                              </div>
                           </div>
               
                           <div class="form-group">
                              <button type="submit" class="btn btn-simple" name="add_next_date">Update</button>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
            <!-- Counselor Cases --> 

            <?php
                if(isset($_SESSION['user_firm_id']) && $_SESSION['user_firm_id'] == 0){
            ?>
            <!-- Delete Cases -->
            <div class="zind modal fade" id="delete_casesb<?php echo $select_cases_result['case_id'];?>">
               <div class="modal-dialog">
                  <div class="modal-content">
                     <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                     <div class="modal-header">
                        <h3><i class="pe-7s-users"></i> Delete Case</h3>
                     </div>
                     <div class="modal-body1 modal-body">
                        <p>Are you sure you want to delete this case... </p>
                        <div class="del_btn">
                           <form action="" method="post">
                              <input type="hidden" name="did" value="<?php echo $select_cases_result['case_id'];?>">
                              <button type="submit" class="btn btn-simple" name="delete_id">Yes</button>
                           </form>
                           <button class="btn btn-simple" data-dismiss="modal">No</button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <?php }} ?> 

            <div class="zind modal fade" id="lawdisclaimertoday">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                    <div class="modal-header">
                            <h3><!-- <i class="pe-7s-note2"></i> -->Disclaimer</h3>
                        </div>
                        <div class="modal-body">
                            <!-- Counselor today  -->
                            Neither your receipt of information from this website, nor your use of this website. The content of this website is intended to convey general information. You should not send us any confidential information in response to this webpage. Such responses will not create a lawyer-client relationship, and whatever you disclose to us will not be privileged or confidential unless we have agreed to act as your legal counsel and you have executed a written engagement agreement.
                        </div>
                    </div>
                </div>
            </div>

         </tbody>
      </table>
   </div>
</div>
<!-- ******* todays cases  END *******  -->
 <script>
function showDivz(elem,id)
{
   if(elem == 'other')
   {
    document.getElementById('hide_show_div'+id).style.display = "block";
   }
    else
   { 
     document.getElementById('hide_show_div'+id).style.display = "none";
   } 
}  
</script>