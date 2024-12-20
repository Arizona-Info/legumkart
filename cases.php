<?php 
   $page = 'cases';
   include("header.php"); 

   if(!isset($_SESSION['user_id']))
   {
     echo  '<script>window.location="index.php"</script>';
   }
   
   if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] == "Counsel" || $_SESSION['user_type'] == "Intern")
   {
     echo  '<script>window.location="index.php"</script>';
   }

   $flag=''; 

   if($_SESSION['user_type']=='firm')
   {  
      $calc_tot = mysqli_query($conn,"SELECT SUM(t1.files_tot_size_bytes) AS tot_files_size FROM tbl_cases t1,  tbl_lawyers t2 WHERE (t1.lawyer_id=t2.lawyer_id AND t2.firm_id='".$_SESSION['user_id']."') OR (t1.lawyer_id=t2.lawyer_id AND  t1.lawyer_id='".$_SESSION['user_id']."')");
   
      // query to get package size allocated to firm
      $showqry = mysqli_query($conn,"SELECT t3.pack_size FROM tbl_lawyer_package t1 INNER JOIN tbl_lawyers t2 ON t1.lawyer_id=t2.lawyer_id INNER JOIN tbl_package t3 ON t1.package_id=t3.pack_id WHERE t1.lawyer_id='".$_SESSION['user_id']."'");

      $flag='firm_owner';
   }
   else
   {
    if(isset($_SESSION['user_firm_id']) && $_SESSION['user_firm_id'] == 0)
    {
         $calc_tot = mysqli_query($conn,"SELECT SUM(t1.files_tot_size_bytes) AS tot_files_size FROM tbl_cases t1, tbl_lawyers t2  WHERE t1.lawyer_id=t2.lawyer_id AND (t1.lawyer_id='".$_SESSION['user_id']."')");

         // query to get package size allocated to a single lawyer
         $showqry = mysqli_query($conn,"SELECT t3.pack_size FROM tbl_lawyer_package t1 INNER JOIN tbl_lawyers t2 ON t1.lawyer_id=t2.lawyer_id INNER JOIN tbl_package t3 ON t1.package_id=t3.pack_id WHERE t1.lawyer_id='".$_SESSION['user_id']."'");

         $flag='single_lawyer';  
    }
    if(isset($_SESSION['user_firm_id']) && $_SESSION['user_firm_id'] != 0)
    {
         $calc_tot = mysqli_query($conn,"SELECT SUM(t1.files_tot_size_bytes) AS tot_files_size FROM tbl_cases t1, tbl_lawyers t2  WHERE t1.lawyer_id=t2.lawyer_id AND t1.lawyer_id IN (SELECT lawyer_id FROM tbl_lawyers WHERE lawyer_id = '".$_SESSION['user_firm_id']."' OR firm_id = '".$_SESSION['user_firm_id']."') ");

         $showqry = mysqli_query($conn,"SELECT t3.pack_size FROM tbl_lawyer_package t1 INNER JOIN tbl_lawyers t2 ON t1.lawyer_id=t2.lawyer_id INNER JOIN tbl_package t3 ON t1.package_id=t3.pack_id WHERE t1.lawyer_id IN (SELECT lawyer_id FROM tbl_lawyers WHERE lawyer_id = '".$_SESSION['user_firm_id']."' OR firm_id = '".$_SESSION['user_firm_id']."')");
          
          $flag='firm_lawyers';
    }
   }

   $row_tot_files_size=mysqli_fetch_assoc($calc_tot);

   $row_space_allocated=mysqli_fetch_assoc($showqry);
   $_SESSION['allocated_space_inbytes']=$row_space_allocated['pack_size']*1024*1024*1024;

   $_SESSION['one_gb_demo_space'] = 1*1024*1024*1024;
   
   //check if that lawyer/firm has package
   $_SESSION['rows_package'] = mysqli_num_rows($showqry);
   $_SESSION['tot_files_size'] = $row_tot_files_size['tot_files_size'];

  
   if(isset($_POST['add_case']))
   {

  if($_POST['stage']=='other' && $_POST['add_newstage'] !='')
   {
     $stage = $_POST['add_newstage'];
   }
   else
   {
    $stage = $_POST['stage'];
   }

   
   $stmt = mysqli_query($conn,"INSERT INTO tbl_cases(court_name, lawyer_id, case_number, category, client_name, status, next_date, client_phone,client_email,paymt_status, counsel_paymt_status ,court_number, judge_name, party_a, party_b, stage, file_no, orderby_date) VALUES('".$_POST['court_name']."', '".$_SESSION['user_id']."', '".$_POST['case_number']."','".$_POST['category']."','".$_POST['client_name']."','Active','".$_POST['next_date']."','".$_POST['client_phone']."','".$_POST['client_email']."','Pending', 'Pending','".$_POST['court_number']."','".$_POST['judge_name']."','".$_POST['party_a']."','".$_POST['party_b']."','".$stage."', '".$_POST['file_no']."','".$_POST['next_date']."')");
   if($stmt){
       echo  '<script>alert("Case added successfully")</script>';
   }
   else{
       echo  '<script>alert("Unable to insert the case")</script>';
   }
   
   }
   
   if(isset($_POST['edit_case']))
   {
   $eid = $_REQUEST['eid'];

  
   $query = "";
   if($_REQUEST['nextdt_id'] == ""){
    $query = "judge_name='".$_POST['edit_judge_name']."', next_date='".$_POST['edit_next_date']."',stage='".$_POST['edit_stage']."',";
   }

   $valsdg = "";

   $stmt = mysqli_query($conn,"UPDATE tbl_cases SET court_name = '".$_POST['edit_court_name']."',court_number='".$_POST['edit_court_number']."', case_number = '".$_POST['edit_case_number']."', ".$query." category = '".$_POST['edit_category']."',client_name = '".$_POST['edit_client_name']."', client_phone='".$_POST['edit_client_phone']."', client_email='".$_POST['edit_client_email']."', party_a='".$_POST['edit_party_a']."', party_b='".$_POST['edit_party_b']."', file_no ='".$_POST['file_no']."', orderby_date = '".$_POST['edit_next_date']."' WHERE case_id='".$eid."'");

   if($_REQUEST['nextdt_id'] != "")
    {
      $new_id = mysqli_query($conn,"SELECT nextdt_id FROM tbl_case_nextdt WHERE next_case_id = '".$eid."' ORDER BY nextdt_id desc");
      $new_id_result = mysqli_fetch_assoc($new_id);
      $nextdt_id = $new_id_result['nextdt_id'];
      $stmt_dtandstage = mysqli_query($conn,"UPDATE tbl_case_nextdt SET next_judge = '".$_POST['edit_judge_name']."', next_stage = '".$_POST['edit_stage']."', next_case_date = '".$_POST['edit_next_date']."' WHERE nextdt_id='".$nextdt_id."'");
    }


   echo  '<script>alert("Case edited successfully")</script>';
   }
   
   // if(isset($_POST['status']))
   //    {
   //        $aid = $_REQUEST['lid']; 
   //        $val = $_REQUEST['lstatus'];
   //        $new_val = '';
   //        if($val == 'Active') { $new_val = 'InActive'; $nval = 'InActive';}  
   //        if($val == 'InActive') {$new_val = 'Active'; $nval = 'Active';} 
   //        $updstatus = mysqli_query($conn,"UPDATE tbl_cases SET status   = '".$new_val."' where case_id = '".$aid."'");
   //    }
   
   if(isset($_POST['delete_id']))
      {
          $did = $_REQUEST['did']; 
          $stmt_dtandstage = mysqli_query($conn,"DELETE FROM tbl_cases WHERE case_id = '".$did."'");
          $qry_find_nextdate_bef_deletion = mysqli_query($conn,"DELETE FROM tbl_case_nextdt WHERE next_case_id='".$did."'");
          echo  '<script>alert("Case Deleted successfully")</script>';
        //   $did = $_REQUEST['did']; 
        //   $ans = "" ;
          
        //   // here we r fetching previous date of the record beeing deleted from child table bcoz this previous date was actually the next date in the record above the deleted record.We will pick this date and update column orderbydate in master table...this will solve our sorting purpose
        //   $qry_find_nextdate_bef_deletion = mysqli_query($conn,"SELECT nextdt_id, prev_case_date FROM tbl_case_nextdt WHERE next_case_id='".$did."' ORDER BY next_case_date DESC LIMIT 1");
        //   $number= mysqli_num_rows($qry_find_nextdate_bef_deletion);
        //   $row=mysqli_fetch_assoc($qry_find_nextdate_bef_deletion);
        //     if($number > 0){
        //     //   echo  '<script>alert('.$number.')</script>';
        //       $upd_orderby_date_aft_deletion = mysqli_query($conn,"UPDATE tbl_cases SET orderby_date = '".$row['prev_case_date']."' where case_id = '".$did."'");
        //     }

          
        //   $stmt_dtandstage = mysqli_query($conn,"DELETE FROM tbl_case_nextdt WHERE next_case_id ='".$did."' ORDER BY nextdt_id DESC limit 1");          
            
        //   if(mysqli_affected_rows($conn) > 0)
        //   {
           
        //     echo  '<script>alert("Case Deleted successfully")</script>';
        //   }
        //   else{
        //     $ans = "next";
           
        //   }

        //   if($ans == "next")
        //   {
        //     $stmt_dtandstage = mysqli_query($conn,"DELETE FROM tbl_cases WHERE case_id = '".$did."'");

        //       if(mysqli_affected_rows($conn) > 0)
        //     {
        //       echo  '<script>alert("Case Deleted successfully")</script>';
        //     }
        //     else
        //     {
        //       echo  '<script>alert("Unable to delete")</script>';
        //     }

        //   }
      }
   
    if(isset($_POST['send_to_counsel']))
      {

        if($_POST['type_selection'] == 'conference'){
            $stmt = mysqli_query($conn,"INSERT INTO tbl_counsel_cases(counsel_id, cc_case_id, cc_next_date, cc_status,cc_flag,cc_type) VALUES('".$_POST['counselor_id']."', '".$_POST['caseeid']."', '".$_POST['case_datee']."', 'Pending','2','Conference')");

            echo  '<script>alert("Case successfully sent to Counselor")</script>';
        }
        else if($_POST['type_selection'] == 'hearing'){
            $stmt = mysqli_query($conn,"INSERT INTO tbl_counsel_cases(counsel_id, cc_case_id, cc_next_date, cc_status,cc_flag,cc_type,cc_hearing_time ) VALUES('".$_POST['counselor_id']."', '".$_POST['caseeid']."', '".$_POST['case_datee']."', 'Pending','2','Hearing','".$_POST['usr_time']."')");

            echo  '<script>alert("Case successfully sent to Counselor")</script>';
        }
        else{
            echo  '<script>alert("Something went wrong, please try again")</script>';
        }

      }
     
     if(isset($_POST['add_next_date']))
      {

        if($_POST['next_stage']=='other' && $_POST['add_newstage'] !='')
        {
          $stage_ad = $_POST['add_newstage'];
        }
        else
        {
          $stage_ad = $_POST['next_stage'];
        }

      $stmt = mysqli_query($conn,"INSERT INTO tbl_case_nextdt(next_case_id, prev_case_date, next_case_date, next_stage, lawyer_id, next_judge) VALUES('".$_POST['next_case_id']."', '".$_POST['prev_case_date']."','".$_POST['next_case_date']."','".$stage_ad."','".$_SESSION['user_id']."','".$_POST['judge_name']."')");

      $upd_orderby_date2 = mysqli_query($conn,"UPDATE tbl_cases SET orderby_date = '".$_POST['next_case_date']."' where case_id = '".$_POST['next_case_id']."'");
      
       echo  '<script>alert("Next Date added successfully")</script>';
      }
      

   ?>
<!-- manage free slot start -->
<section class="manage_freeslot">
   <div class="container-fluid">
      <div class="row">
         <div class="col-xs-12">
           <?php include("sidebar.php"); ?>
            <div class="col-sm-9 col-xs-12">
               <div class="right_panel">
                  <div class="row">
                    <div class="col-xs-12 title_stripe">
                      <h3 class="">Case Diary</h3>
                      <a href="#add_cases" data-toggle="modal" class="btn btn-dark add_new" style="text-transform:initial;">Add</a>
                      <a class="add_new">&nbsp</a>
                      <b class="add_new total_count_view">Loading...</b>
                    </div>
                  </div>
                  <hr>

                  <!-- Add Cases -->
                  <div class="zind modal fade" id="add_cases">
                     <div class="modal-dialog">
                        <div class="modal-content">
                           <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                           <div class="modal-header">
                              <h3><i class="pe-7s-users"></i> Add Case</h3>
                           </div>
                           <div class="modal-body">
                              <form class="form" role="form" method="post" action=""  autocomplete="off">
                                <div class="row">
                                 <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                       <input class="form-control" name="court_name" placeholder="Court Name" type="text" value="" required>
                                    </div>
                                 </div>
                                 <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                       <input class="form-control" name="court_number" placeholder="Court Number" type="text" value="">
                                    </div>
                                 </div>
                                 <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                       <input class="form-control" name="case_number" placeholder="Case Number" type="text" value=""  required>
                                    </div>
                                 </div>
                                 <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                       <input class="form-control" name="judge_name" placeholder="Judge Name" type="text" value="">
                                    </div>
                                 </div>
                                 <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                       <input class="form-control example1" name="next_date" placeholder="Next Date" type="text" value=""  autocomplete="off"  required>
                                    </div>
                                 </div>
                                 <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                       <select class="form-control" name="category"  required>
                                       <option value="">-- Select Category --</option>
                                       <option>Civil</option>
                                       <option>Criminal</option>
                                    </select>
                                    </div>
                                 </div>
                                 <div class="col-md-5 col-xs-12">
                                    <div class="form-group">
                                       <input class="form-control" name="client_name" placeholder="Client Name" type="text" value=""  required>
                                    </div>
                                 </div>
                                 <div class="col-md-3 col-sm-5 col-xs-12">
                                    <div class="form-group">
                                       <input class="form-control" name="party_a" placeholder="Party A" type="text" value=""  required>
                                    </div>
                                 </div>
                                 <div class="col-md-1 col-sm-2 col-xs-12" style="padding-top:7px;">
                                    <p class="text-center">VS</p>
                                 </div>
                                 <div class="col-md-3 col-sm-5 col-xs-12">
                                    <div class="form-group">
                                       <input class="form-control" name="party_b" placeholder="Party B" type="text" value=""  required>
                                    </div>
                                 </div>
                                 <div class="col-sm-6 col-xs-12">
                                    <div class="form-group">
                                       <input class="form-control" name="client_phone" placeholder="Client Phone" type="text" value="">
                                    </div>
                                 </div>
                                 <div class="col-md-6 col-xs-12">
                                       <div class="form-group">
                                          <input class="form-control" name="client_email" placeholder="Client Email" type="text" value="">
                                       </div>
                                    </div>
                                  <div class="col-sm-6 col-xs-12">
                                   <div class="form-group">
                                      <select class="form-control" id="opt" name="stage"  onchange="showDivp(this.value)" required>
                                         <option value="">-- Select Stage --</option>
                                         <?php

                                            $qry = "SELECT stage_name FROM tbl_stage WHERE status = '1'";
                                            $result = mysqli_query($conn, $qry);
                                            while ($result2 = mysqli_fetch_assoc($result)) {
                                              ?>
                                              <option><?php echo $result2['stage_name']; ?></option>
                                              <?php
                                            }

                                          ?>                 
                                        <option value="other" style="color:red"><strong>Other</strong></option>
                                      </select>
                                   </div>
                                </div> 
                                 <div id="hide_show_divp" style="display:none">
                                       <div class="col-md-6 col-xs-12">
                                          <div class="form-group">
                                             <input class="form-control" name="add_newstage" placeholder="Enter New Stage" type="text" style="background:yellow">
                                          </div>
                                       </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                       <input class="form-control" name="file_no" placeholder="File Number" type="text">
                                    </div>
                                </div>
                                 <div class="form-group col-xs-12">
                                    <button type="submit" class="btn btn-simple" name="add_case">Add</button>
                                 </div>
                               </div>
                              </form>
                           </div>
                        </div>
                     </div>
                  </div>

   <div class="row interactions">
      <div class="col-md-12">
         <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item active" onclick="cases_today()">
               <a data-toggle="tab" href="#today" role="tab">Today (<?php echo date('d-M');?>)</a>
            </li>
            <li class="nav-item" onclick="cases_tomorrow()">
               <a data-toggle="tab" href="#tomorrow" role="tab">Tomorrow (<?php echo date('d-M', strtotime('+1 day'));?>)</a>
            </li>
            <li class="nav-item" onclick="cases_weekly()">
               <a data-toggle="tab" href="#all" role="tab">Weekly (<?php echo date('d-M', strtotime('+2 day')).' to '.date('d-M', strtotime('+7 day'));?>)</a>
            </li>
         </ul>
         <div class="tab-content">

            <?php include("cases_today_pagination.php"); ?>

            <?php include("cases_tomorrow_pagination.php"); ?>

            <?php include("cases_weekly_pagination.php"); ?>
         </div>
      </div>
   </div>

   </div>
   </div>
   </div>
   </div>
   </div>
</section>


<!-- Update cases modal - Start -->
<div class="zind modal fade" id="update_cases">
 <div class="modal-dialog">
    <div class="modal-content">
       <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
       <div class="modal-header">
          <h3><i class="pe-7s-users"></i> Update Case</h3>
       </div>
       <div class="modal-body">
          <form class="form" role="form" method="post" action="">

              
             <input type="hidden" class="nextdt_id" name="nextdt_id" value=""> 

             <input type="hidden" class="eid" name="eid" value=""> 
             <div class="col-md-6">
                <div class="form-group">
                   <label>Court Name :</label>
                   <input class="form-control edit_court_name" name="edit_court_name" placeholder="Court Name" type="text" value="" required>
                </div>
             </div>
             <div class="col-md-6">
                <div class="form-group">
                   <label>Court Number :</label>
                   <input class="form-control edit_court_number" name="edit_court_number" placeholder="Court Number" type="text" value="">
                </div>
             </div>
             <div class="col-md-6">
                <div class="form-group">
                   <label>Case No. :</label>
                   <input class="form-control edit_case_number" name="edit_case_number" placeholder="Case Number" type="text" value="" required>
                </div>
             </div>
             <div class="col-md-6">
                <div class="form-group">
                   <label>Judge Name :</label>
                   <input class="form-control edit_judge_name" name="edit_judge_name" placeholder="Judge Name" type="text" value="">
                </div>
             </div>
             <div class="col-md-6">
                <div class="form-group">
                   <label>Category :</label>
                   <select class="form-control edit_category" name="edit_category" required>
                      <option value="">-- Select Category --</option>
                      <option value="Civil" >Civil</option>
                      <option value="Criminal" >Criminal</option>
                   </select>
                </div>
             </div>
              <div class="col-md-6">
                <div class="form-group">
                   <label>Client Name :</label>
                   <input class="form-control edit_client_name" name="edit_client_name" placeholder="Client Name" type="text" value="" required>
                </div>
             </div>
             <div class="col-md-6">
                <div class="form-group">
                   <label>Phone :</label>
                   <input class="form-control edit_client_phone" name="edit_client_phone" placeholder="Client Phone" type="text" value="">
                </div>
             </div>
             <div class="col-md-6">
                <div class="form-group">
                   <label>Email :</label>
                   <input class="form-control edit_client_email" name="edit_client_email" placeholder="Client Email" type="text" value="">
                </div>
             </div>


             <div class="col-md-6 col-xs-12">
                 <div class="form-group">
                 <label>Next Date :</label>   
                    <input class="form-control example1 edit_next_date" name="edit_next_date" placeholder="Next Date" type="text" value=""  autocomplete="off"  required>
                 </div>
              </div>
               <div class="col-md-6 col-xs-12">
                 <div class="form-group">
                 <label>Stage :</label>   
              <select class="form-control edit_stage" id="optt" name="edit_stage"  onchange="showDive(this.value)" required>
                 <option value="">-- Select Stage --</option>
                 <?php

                    $qry = "SELECT stage_name FROM tbl_stage WHERE status = '1'";
                    $result = mysqli_query($conn, $qry);
                    while ($result2 = mysqli_fetch_assoc($result)) 
                    {
                      ?>
                      <option><?php echo $result2['stage_name']; ?></option>
                <?php
                    } ?>
                   <option value="other" style="color:red"><strong>Other</strong></option>                          
              </select>
                </div>
            </div>

              <div id="hide_show_divv"  style="display: none">
                  <div class="col-md-6 col-xs-12">
                     <div class="form-group">
                     <label>New Stage :</label>
                        <input class="form-control" name="edit_newstage" placeholder="Enter New Stage" type="text"  style="background:yellow">
                     </div>
                  </div>
                </div> 

            
             <div class="col-md-5">
                <div class="form-group">
                <label>File Number :</label>
                   <input class="form-control file_no" name="file_no" placeholder="File Number" type="text" value="">
                </div>
            </div>
             <div class="col-md-3">
                <div class="form-group">
                   <label>Party A :</label>
                   <input class="form-control edit_party_a" name="edit_party_a" placeholder="Party A" type="text" value="" required>
                </div>
             </div>
             <div class="col-md-1" style="padding-top:7px;">
                <p>VS</p>
             </div>
             <div class="col-md-3">
                <div class="form-group">
                   <label>Party B :</label>
                   <input class="form-control edit_party_b" name="edit_party_b" placeholder="Party B" type="text" value="" required>
                </div>
             </div>
             <div class="form-group">
                <button type="submit" class="btn btn-simple" name="edit_case">Update</button>
             </div>
          </form>
       </div>
    </div>
 </div>
</div>
<!-- Update cases modal - End -->


<!-- Cases History Modal Start -->
  <div class="zind modal fade" id="case_historyd">
    <div class="modal-dialog">
       <div class="modal-content case_history_modal">
       </div>
    </div>
  </div>
<!-- Cases History Modal End -->


<!-- Upload Files Against A Particular Case Selected -->  
<div class="zind modal fade" id="upload_files_cases">
    <div class="modal-dialog">
       <div class="modal-content">
          <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
          <div class="modal-header">
             <h3 class="add_title_in_upload_files"></h3>
          </div>
          <div class="modal-body">
               <form method="POST" action="upload.php" enctype="multipart/form-data">
                  <div class="form-group">
                      <input type="file" class="form-control" name="upload[]" id="file" multiple onchange="javascript:updateList()">
                   </div>
                   <input type="hidden" name="case_id" class="input_value_in_upload_files" value="">
                   <div class="form-group">   
                      <input type="submit" class="btn btn-simple" value="Upload">
                   </div>    
               </form>
               <br/>Selected files:
              <div id="fileList"></div>
          </div>
       </div>
    </div>
 </div>


 <div class="zind modal fade" id="restrict_upload_cases">
    <div class="modal-dialog">
       <div class="modal-content">
          <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
          <div class="modal-header">
             <h3 class="add_title_in_restrict_files"></h3>
          </div>
          <div class="modal-body1 modal-body">
             <p>Package Limit Crossed.Pl.contact Admin... </p>
             <div class="del_btn">
                <button class="btn btn-simple" data-dismiss="modal">Close</button>
             </div>
          </div>
       </div>
    </div>
 </div>
 <!-- Upload Files Against A Particular Case Selected -->  

 <!-- court details - start -->
   <div class="zind modal fade" id="court_detailsd">
      <div class="modal-dialog">
         <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
            <div class="modal-header">
               <h3>Court Details</h3>
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
                           <p class="cases_all_court_number"></p>
                       </div>
                       <div class="sub_cell">
                           <p class="cases_all_judge_name"></p>
                       </div>
                   </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- court details - End -->


   <!-- client details - start -->
   <div class="zind modal fade" id="client_detailsd">
      <div class="modal-dialog">
         <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
            <div class="modal-header">
               <h3>Court Details</h3>
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
                           <p class="cases_all_client_phone"></p>
                       </div>
                       <div class="sub_cell">
                           <p class="cases_all_client_email"></p>
                       </div>
                   </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- client details - End -->

   <!-- Modal popup add next cases date - End -->
   <div class="zind modal fade" id="nextdate_casesb">
      <div class="modal-dialog">
        <div class="modal-content">
          <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
          <div class="modal-header">
            <h3><i class="pe-7s-users"></i> Next Date</h3>
          </div>
          <div class="modal-body">
            <form class="form" role="form" method="post" action="">
            <input type="hidden" name="next_case_id" id="next_case_id" value="">
            <input type="hidden" name="prev_case_date" id="prev_case_date" value=""> 
            <div class="col-md-6">
            <div class="form-group">
            <input class="form-control example1" value="" id="next_case_date" name="next_case_date" placeholder="Next Date" type="text" autocomplete="off" required>
            </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
            <input class="form-control" name="judge_name" id="judge_name" placeholder="Judge Name" type="text" value="" autocomplete="off">
            </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
            <select class="form-control" name="next_stage" id="next_stage" onchange="showDivtwo(this.value)" required>
            <option value="">-- Select Stage --</option>
            <?php

            $qry = "SELECT stage_name FROM tbl_stage WHERE status = '1'";
            $result = mysqli_query($conn, $qry);
            while ($result2 = mysqli_fetch_assoc($result)) { ?>
            <option><?php echo $result2['stage_name'];?></option>
            <?php }
            ?>
            <option value="other" style="color:red"><strong>Other</strong></option> 
            </select>
            </div>
            </div>
            <div id="hide_show_divtwo"  style="display: none">
            <div class="col-md-6">
            <div class="form-group">
            <input  class="form-control" name="add_newstage" placeholder="Enter New Stage" type="text" style="background:yellow">
            </div>
            </div>
            </div>

            <div class="col-md-12">
            <div class="form-group">
            <button type="submit" class="btn btn-simple" name="add_next_date">Add</button>
            </div>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal popup add next cases date - End  -->

    <!-- delete the cases - start -->
    <?php
          if(isset($_SESSION['user_firm_id']) && $_SESSION['user_firm_id'] == 0){
    ?>
  <div class="zind modal fade" id="delete_casesb">
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
                    <input type="hidden" id="did" name="did" value="">
                    <button type="submit" class="btn btn-simple" name="delete_id">Yes</button>
                 </form>
                 <button class="btn btn-simple" data-dismiss="modal">No</button>
              </div>
           </div>
        </div>
     </div>
  </div>
    <?php } ?>
  <!-- delete the cases - End -->
   

<!-- manage free slot end -->
<!-- manage free slot end -->
<?php 
   include("footer.php"); 
   ?>


<script type="text/javascript" src="js/jquery.bootpag.min.js"></script>
<script type="text/javascript">
   $(document).ready(function() {
       
    
    var today = setInterval(function(){
        $("#results_today").load("pagination_fetch_pages_today_cases.php",{limit: 25}, 
            function (responseText, textStatus, req) {
                if (textStatus == "error") {
                  console.log('error');
                }
                else{
                    clearInterval(today);
                    console.log(textStatus);
                }
        });  //initial page number to load        
    }, 3000);
    
     $(".pagination_today").bootpag({
        total: <?php echo $pages_today; ?>,
        page: <?php echo $_SESSION['cases_today_pagination_page']; ?>,
        maxVisible: 10 
     }).on("page", function(e, num){
       e.preventDefault();
    //   $("#results").html('<div class="loading-indication"><b>Loading...</b></div>');
        $("#results_today").html('<div class="loading-indication"><img src="assets/img/ajax-loader.gif" /></div>');
       $("#results_today").load("pagination_fetch_pages_today_cases.php", {'page':num});
     });


    var tomorrow = setInterval(function(){
        $("#results_tomorrow").load("pagination_fetch_pages_tomorrow_cases.php",{limit: 25}, 
            function (responseText, textStatus, req) {
                if (textStatus == "error") {
                  console.log('error');
                }
                else{
                    clearInterval(tomorrow);
                    console.log(textStatus);
                }
        });  //initial page number to load        
    }, 3000);

     $(".pagination_tomorrow").bootpag({
        total: <?php echo $pages_tomorrow; ?>,
        page: <?php echo $_SESSION['cases_tomorrow_pagination_page']; ?>,
        maxVisible: 10 
     }).on("page", function(e, num){
       e.preventDefault();
    //   $("#results").html('<div class="loading-indication"><b>Loading...</b></div>');
        $("#results_tomorrow").html('<div class="loading-indication"><img src="assets/img/ajax-loader.gif" /></div>');
       $("#results_tomorrow").load("pagination_fetch_pages_tomorrow_cases.php", {'page':num});
     });


    var weekly = setInterval(function(){
        $("#results_weekly").load("pagination_fetch_pages_weekly_cases.php",{limit: 25}, 
            function (responseText, textStatus, req) {
                if (textStatus == "error") {
                  console.log('error');
                }
                else{
                    clearInterval(weekly);
                    console.log(textStatus);
                }
        });  //initial page number to load        
    }, 3000);

     $(".pagination_weekly").bootpag({
        total: <?php echo $pages_weekly; ?>,
        page: <?php echo $_SESSION['cases_weekly_pagination_page']; ?>,
        maxVisible: 10 
     }).on("page", function(e, num){
       e.preventDefault();
    //   $("#results").html('<div class="loading-indication"><b>Loading...</b></div>');
        $("#results_weekly").html('<div class="loading-indication"><img src="assets/img/ajax-loader.gif" /></div>');
       $("#results_weekly").load("pagination_fetch_pages_weekly_cases.php", {'page':num});
     });


   });
</script>

<script>
function showDivp(elem)
{
   if(elem == 'other')
   {
      document.getElementById('hide_show_divp').style.display = "block";
   }
    else
   { 
     document.getElementById('hide_show_divp').style.display = "none";
   } 
} 
  
</script>

<script type="text/javascript">
  function update_cases(nextdt_id, case_id, court_name, court_number, case_number, judge_name, category, client_name, client_phone, client_email, file_no, party_a, party_b, next_date, next_stage){
   
   $('.nextdt_id').val(nextdt_id);
   $('.eid').val(case_id);
   $('.edit_court_name').val(court_name);
   $('.edit_court_number').val(court_number);
   $('.edit_case_number').val(case_number);
   $('.edit_judge_name').val(judge_name);
   $('.edit_next_date').val(next_date);

    index_val = 0;
    validate_index_selection = 0;

    $('select#optt').find('option').each(function() {
      if($(this).val() == next_stage){
        $("#optt").prop("selectedIndex", index_val);
        validate_index_selection = validate_index_selection + 1;
      }
      index_val = index_val + 1;
    });

    if(validate_index_selection == 0){
        $('#optt').append($("<option></option>").attr("value",next_stage).text(next_stage));
        $("#optt").prop("selectedIndex", index_val);
    }

    if(category == "Civil"){
      $(".edit_category").prop("selectedIndex", 1);
    }
    else if(category == "Criminal"){
      $(".edit_category").prop("selectedIndex", 2);
    }
    else{
      $(".edit_category").prop("selectedIndex", 0);
    }
   
   $('.edit_client_name').val(client_name);
   $('.edit_client_phone').val(client_phone);
   $('.edit_client_email').val(client_email);
   $('.file_no').val(file_no);
   $('.edit_party_a').val(party_a);
   $('.edit_party_b ').val(party_b);
   $('#update_cases').modal('show');
  }
</script>

<script type="text/javascript">
  function upload_files(case_id,cases_number){
    $('.add_title_in_upload_files').text("Upload Files For Case "+cases_number);
    $('.input_value_in_upload_files').val(case_id);
    $('#fileList').text("");
    $("#file").val(null);
    $('#upload_files_cases').modal('show');
  }
</script>

<script type="text/javascript">
  function restrict_upload(cases_number){
    $('.add_title_in_upload_files').text("Upload Files For Case "+cases_number);
    $('#restrict_upload_cases').modal('show');
  }
</script>

<script type="text/javascript">
  function allcaseshistory(case_id,cases_number){
    $('.case_history_modal').html('<h1>Loading...</h1>');
    $.ajax({
      type:"post",
      data:{ case_id : case_id, cases_number : cases_number , action : 'case_all_history'},
      url:"ajax_all.php",
      success:function(rec){
        $('.case_history_modal').html(rec);
      }
    })
    $('#case_historyd').modal('show');
  }
</script>

<script type="text/javascript">
updateList = function() {
  // alert('hi');
  var input = document.getElementById('file');
  var output = document.getElementById('fileList');

  output.innerHTML = '<ul>';
  for (var i = 0; i < input.files.length; ++i) {
    output.innerHTML += '<li>' + input.files.item(i).name + '</li>';
  }
  output.innerHTML += '</ul>';
}
</script>

<script>
function showDive(elem)
{
   if(elem == 'other')
   {
    document.getElementById('hide_show_divv').style.display = "block";
   }
    else
   { 
     document.getElementById('hide_show_divv').style.display = "none";
   } 
}  
</script>


<script type="text/javascript">
  function court_detailsd(court_number, judge_name){
    $("#court_detailsd").modal('show');
    $(".cases_all_court_number").text(court_number);
    $(".cases_all_judge_name").text(judge_name);
  }
</script>

<script type="text/javascript">
  function client_detailsd(client_phone, client_email){
    $("#client_detailsd").modal('show');
    $(".cases_all_client_phone").text(client_phone);
    $(".cases_all_client_email").text(client_email);
  }
</script>

<!-- Cases fetch for update next date - Start -->
<script type="text/javascript">
  function update_next_dt(case_id, next_date, judge, case_stage){
    $("#next_case_id").val(case_id);
    $("#prev_case_date").val(next_date);
    $("#next_case_date").val(next_date);
    $("#judge_name").val(judge);

    var index_val = 0;
    var validate_index_selection = 0;

    $('select#next_stage').find('option').each(function() {
      if($(this).val() == case_stage){
        $("#next_stage").prop("selectedIndex", index_val);
        validate_index_selection = validate_index_selection + 1;
      }
      index_val = index_val + 1;
    });

    if(validate_index_selection == 0){
        $('#next_stage').append($("<option></option>").attr("value",case_stage).text(case_stage));
        $("#next_stage").prop("selectedIndex", index_val);
    }

    // $("#next_stage").val(case_stage);
    $("#hide_show_divtwo").css("display","none");
    $("#nextdate_casesb").modal('show');
  }
</script>
<!-- Cases fetch for update next date - End -->

<script>
function showDivtwo(elem)
{
   if(elem == 'other')
      {
      document.getElementById('hide_show_divtwo').style.display = "block";
      }
   else
   { 
     document.getElementById('hide_show_divtwo').style.display = "none";
   } 
}
</script>

<!-- Today  -->
<script type="text/javascript">
  function open_cases_details1(val, id){
    $(".hide_data_on_click1").hide();  //compulsory
    $(".open_cases_details1").show();
    $(".close_cases_details1").hide();

    $(".open_cases_details1"+id).hide();
    $(".close_cases_details1"+id).show();
    $(".show_data_on_click1"+id).show();
  }

  function close_cases_details1(val, id){
    $(".hide_data_on_click1").hide();  //compulsory
    $(".open_cases_details1").show();

    $(".close_cases_details1").hide();
    $(".open_cases_details1"+id).show();
  }

</script>
<!-- Today  -->


<!-- Tomorrow  -->
<script type="text/javascript">
  function open_cases_details2(val, id){
    $(".hide_data_on_click2").hide();  //compulsory
    $(".open_cases_details2").show();
    $(".close_cases_details2").hide();

    $(".open_cases_details2"+id).hide();
    $(".close_cases_details2"+id).show();
    $(".show_data_on_click2"+id).show();
  }

  function close_cases_details2(val, id){
    $(".hide_data_on_click2").hide();  //compulsory
    $(".open_cases_details2").show();

    $(".close_cases_details2").hide();
    $(".open_cases_details2"+id).show();
  }

</script>
<!-- Tomorrow  -->


<!-- Weekly  -->
<script type="text/javascript">
  function open_cases_details3(val, id){
    $(".hide_data_on_click3").hide();  //compulsory
    $(".open_cases_details3").show();
    $(".close_cases_details3").hide();

    $(".open_cases_details3"+id).hide();
    $(".close_cases_details3"+id).show();
    $(".show_data_on_click3"+id).show();
  }

  function close_cases_details3(val, id){
    $(".hide_data_on_click3").hide();  //compulsory
    $(".open_cases_details3").show();

    $(".close_cases_details3").hide();
    $(".open_cases_details3"+id).show();
  }

</script>
<!-- Weekly  -->

<!-- Delete cases - Start -->
<script type="text/javascript">
  function deleteCases(id){
    $("#did").val(id);
    $("#delete_casesb").modal('show');
  }
</script>
<!-- Delete cases - End -->

<script type="text/javascript">
  $(document).ready(function(){

    change_total_cases = setInterval(function(){

      val = $(".NumberOfCasesToday").val();
      if(typeof val === "undefined"){

      }
      else{
        totalCases = $("#total_cases_available_today").val();

        if(totalCases != 0){
          $(".total_count_view").text(val+ totalCases +" Entries");
        }
        else{
          $(".total_count_view").text("Showing 0 To 0 Of 0 Entries");
        }
      }

    }, 500);

  })
</script>

<script type="text/javascript">
  function cases_today(){
      clearInterval(change_total_cases);
      change_total_cases = setInterval(function(){

        val = $(".NumberOfCasesToday").val();
        if(typeof val === "undefined"){

        }
        else{
          totalCases = $("#total_cases_available_today").val();

          if(totalCases != 0){
            $(".total_count_view").text(val+ totalCases +" Entries");
          }
          else{
            $(".total_count_view").text("Showing 0 To 0 Of 0 Entries");
          }
        }

      }, 500);
    }

    function cases_tomorrow(){
      clearInterval(change_total_cases);
      change_total_cases = setInterval(function(){

        val = $(".NumberOfCasesTomorrow").val();
        if(typeof val === "undefined"){

        }
        else{
          totalCases = $("#total_cases_available_tomorrow").val();

          if(totalCases != 0){
            $(".total_count_view").text(val+ totalCases +" Entries");
          }
          else{
            $(".total_count_view").text("Showing 0 To 0 Of 0 Entries");
          }
        }
      }, 500);
    }


    function cases_weekly(){
      clearInterval(change_total_cases);
      change_total_cases = setInterval(function(){

        val = $(".NumberOfCasesWeekly").val();
        if(typeof val === "undefined"){

        }
        else{
          totalCases = $("#total_cases_available_weekly").val();

          if(totalCases != 0){
            $(".total_count_view").text(val+ totalCases +" Entries");
          }
          else{
            $(".total_count_view").text("Showing 0 To 0 Of 0 Entries");
          }
        }

      }, 500);
    }
</script>