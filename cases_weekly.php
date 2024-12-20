<!-- ******* weekly cases ******* -->  
<div class="tab-pane fade" id="all" role="tabpanel">
   <div class="table-responsive action_table">
      <table id="attendenceDetailedTable2" class="display responsive nowrap" cellspacing="0" width="100%">
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
            $next_qry = "SELECT MAX(nextdt_id) AS nextdt_id, next_case_id, next_case_date, next_stage, next_judge, prev_case_date, lawyer_id FROM tbl_case_nextdt WHERE lawyer_id IN (".$lawyer_id.") AND (next_case_date BETWEEN '".date('Y-m-d', strtotime('+2 day'))."' AND '".date('Y-m-d', strtotime('+7 day'))."') GROUP BY next_case_id";
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
            $qry_cases = "SELECT lawyer_id,case_id, next_date, judge_name, stage, court_number, case_number, party_a, party_b, court_name, client_name, category, file_no, client_phone, client_email FROM tbl_cases WHERE (".$cases_id."(next_date BETWEEN '".date('Y-m-d', strtotime('+2 day'))."' AND '".date('Y-m-d', strtotime('+7 day'))."')) AND lawyer_id  IN (".$lawyer_id.")  ORDER BY orderby_date ASC, CASE 
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
            // $weekly_count = mysqli_num_rows($select_cases);

            while ($select_cases_result = mysqli_fetch_assoc($select_cases)) {

              $pre_date = "";
              $next_date = "";
              $judge = "";
              $stage = "";
              $att_lawyer_name = "";
              $nextdt_id = "";
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
            ?>
               
          <tr>
            <?php 
            if($_SESSION['user_type']=='firm' OR $_SESSION['user_firm_id']!=0)
            {?>
            <td><?php echo $result_lawyer['firm_name'];?></td>
            <?php } ?>

            <?php if($pre_date == ""){ echo "<td>New Case</td>"; }else{ ?>
            <td><?php echo date_format (new DateTime($pre_date), 'd-M-y'); ?></td>
            <?php } ?>
      
            <td><u><a href="#court_detailsc2<?php echo $select_cases_result['case_id'];?>"  data-toggle="modal" title="Court Details"><?php echo $select_cases_result['court_name'];?></a></u></td>

            <td>
            <?php
              if($nextdt_id != ""){
            ?>
            <u><a href="#" onclick="allcaseshistory(<?php echo $select_cases_result['case_id'];?>,'<?php echo $select_cases_result['case_number'];?>')" title="Case History"><?php echo $select_cases_result['case_number'];?></a></u>
            <?php }else{ echo $select_cases_result['case_number']; }?>
            </td>
         
            <td><?php echo $select_cases_result['party_a'].' <strong>Vs</strong> '.$select_cases_result['party_b'];?></td>
            
            <td <?php if($stage=='Evidence' || $stage=='Part-Heard' || $stage=='Cross' || $stage=='Arguments' || $stage=='Dismissed/Disposed' || $stage=='Withdrawn' || $stage=='Decree') { echo 'style="color:red"'; } ?>><?php echo $stage;?></td>

            <td><?php 
              $next_date = date_create($next_date);
              echo date_format($next_date,"d-M-y");
              // echo date('d-M-y', $next_date); 
            ?></td>

            <td><u><a href="#client_detailsc2<?php echo $select_cases_result['case_id'];?>"  data-toggle="modal" title="Client Details"><?php echo $select_cases_result['client_name'];?></a></u></td>                  
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
                            <input type="hidden" name="case_datee" value="<?php echo date_format($next_date,"Y-m-d");?>">
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
                        <a href="#" class="btn_sm1" onclick="update_cases(<?php echo "'".$nextdt_id."',".$select_cases_result['case_id'].",'".$select_cases_result['court_name']."','".$select_cases_result['court_number']."','".$select_cases_result['case_number']."','".$judge."','".$select_cases_result['category']."','".$select_cases_result['client_name']."','".$select_cases_result['client_phone']."','".$select_cases_result['client_email']."','".$select_cases_result['file_no']."','".$select_cases_result['party_a']."','".$select_cases_result['party_b']."','".date_format($next_date,"Y-m-d")."','".$stage."'"; ?>)" title="Edit"><i class="fa fa-edit"></i> Edit</a>
                     </li>
                     <?php
                        if(isset($_SESSION['user_firm_id']) && $_SESSION['user_firm_id'] == 0)
                           {
                        ?>
                     <li>
                        <a href="#delete_casesc2<?php echo $select_cases_result['case_id'];?>" class="btn btn_sm1" data-toggle="modal" title="Delete"><i class="fa fa-trash"></i> Delete</a>
                     </li>
                     <?php } ?>
                  </ul>
               </div>
            </td>
        </tr>

<div class="zind modal fade" id="court_detailsc2<?php echo $select_cases_result['case_id'];?>">
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

<?php if($nextdt_id != ""){ ?>
<div class="zind modal fade" id="case_historyc2<?php echo $select_cases_result['case_id'];?>">
      <div class="modal-dialog">
         <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
            <div class="modal-header">
               <h3><i class="pe-7s-users"></i> Case History (Case Number: <?php echo $select_cases_result['case_number'];?>)</h3>
            </div>
            <div class="modal-body">
               <div class="table_box">
                  <div class="heading">
                        <div class="cell">
                           <p>Previous Date</p>
                        </div>
                        <div class="cell">
                           <p>Stage</p>
                        </div>
                        <div class="cell">
                           <p>Next Date</p>
                        </div>
                        <div class="cell">
                             <p>Attended By</p>
                         </div>
                         <div class="cell">
                             <p>Judge Name</p>
                         </div>
                  </div>
                  <?php 
                     // $lawyerqry16 = "SELECT * FROM tbl_cases t1, tbl_case_nextdt t2 WHERE t2.next_case_id='".$select_cases_result['case_id']."' AND t1.case_id=t2.next_case_id";


                  $lawyerqry16 = "SELECT t2.prev_case_date,t2.next_stage,t2.next_case_date,t3.firm_name,t2.next_judge  FROM tbl_cases t1, tbl_case_nextdt t2 ,tbl_lawyers t3 WHERE t2.next_case_id='".$select_cases_result['case_id']."' AND t1.case_id=t2.next_case_id AND t2.lawyer_id=t3.lawyer_id";

                  $lawyerqry18 = "SELECT t1.next_date, t1.stage, t1.judge_name, t2.firm_name FROM tbl_cases t1, tbl_lawyers t2 WHERE t2.lawyer_id = t1.lawyer_id AND t1.case_id = '".$select_cases_result['case_id']."'";

                  $lawyerresults18 = mysqli_query($conn,$lawyerqry18);
                  $lawyerrow18 = mysqli_fetch_assoc($lawyerresults18);


                  ?>

                <div class="sub_heading">
                   <div class="sub_cell">
                       <p><?php echo date_format (new DateTime($lawyerrow18['next_date']), 'd-M-y');?></p>
                   </div>
                   <div class="sub_cell">
                       <p><?php echo $lawyerrow18['stage'];?></p>
                   </div>
                   <div class="sub_cell">
                       <p><?php echo date_format (new DateTime($lawyerrow18['next_date']), 'd-M-y');?></p>
                   </div>
                   <div class="sub_cell">
                       <p><?php echo $lawyerrow18['firm_name'];?></p>
                   </div>
                   <div class="sub_cell">
                       <p><?php echo $lawyerrow18['judge_name'];?></p>
                   </div>
              </div>
                          
                <?php 

                     $lawyerresults16 = mysqli_query($conn,$lawyerqry16);
                     while($lawyerrow16=mysqli_fetch_assoc($lawyerresults16))
                     { 
                  ?>
                  <div class="sub_heading">
                       <div class="sub_cell">
                           <p><?php echo date_format (new DateTime($lawyerrow16['prev_case_date']), 'd-M-y');?></p>
                       </div>
                       <div class="sub_cell">
                           <p><?php echo $lawyerrow16['next_stage'];?></p>
                       </div>
                       <div class="sub_cell">
                           <p><?php echo date_format (new DateTime($lawyerrow16['next_case_date']), 'd-M-y');?></p>
                       </div>
                       <div class="sub_cell">
                            <p><?php echo $lawyerrow16['firm_name'];?></p>
                       </div>
                       <div class="sub_cell">
                           <p><?php echo $lawyerrow16['next_judge'];?></p>
                       </div>
                  </div>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
</div>
<?php } ?>

<div class="zind modal fade" id="client_detailsc2<?php echo $select_cases_result['case_id'];?>">
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
      
      <?php
          if(isset($_SESSION['user_firm_id']) && $_SESSION['user_firm_id'] == 0){
      ?>
      <!-- Delete Cases -->
         <div class="zind modal fade" id="delete_casesc2<?php echo $select_cases_result['case_id'];?>">
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
         <?php } } ?>

         <div class="zind modal fade" id="lawdisclaimertomorrow">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                    <div class="modal-header">
                            <h3><!-- <i class="pe-7s-note2"></i> -->Disclaimer</h3>
                        </div>
                        <div class="modal-body">
                            <!-- Counselor tomorrow  -->
                            Neither your receipt of information from this website, nor your use of this website. The content of this website is intended to convey general information. You should not send us any confidential information in response to this webpage. Such responses will not create a lawyer-client relationship, and whatever you disclose to us will not be privileged or confidential unless we have agreed to act as your legal counsel and you have executed a written engagement agreement.
                        </div>
                    </div>
                </div>
            </div> 

         </tbody>
      </table>
   </div>
</div>
<!-- ******* weekly cases  END ******* --> 

