<?php 
   $page = 'cases';
   include("header.php"); 

   if(!isset($_SESSION['user_id']))
   {
     echo  '<script>window.location="index.php"</script>';
   }
   
   if(isset($_POST['add_case']))
   {
   
   $stmt = mysqli_query($conn,"INSERT INTO tbl_cases(court_name, lawyer_id, case_number, category, client_name, status, next_date, client_phone,paymt_status, court_number, judge_name, party_a, party_b, stage) VALUES('".$_POST['court_name']."', '".$_SESSION['user_id']."', '".$_POST['case_number']."','".$_POST['category']."','".$_POST['client_name']."','Active','".$_POST['next_date']."','".$_POST['client_phone']."','Pending','".$_POST['court_number']."','".$_POST['judge_name']."','".$_POST['party_a']."','".$_POST['party_b']."','".$_POST['stage']."')");
   echo  '<script>alert("Case added successfully")</script>';
   }
   
   if(isset($_POST['edit_case']))
   {
   $eid = $_REQUEST['eid'];
   $stmt = mysqli_query($conn,"UPDATE tbl_cases SET court_name = '".$_POST['edit_court_name']."',court_number='".$_POST['edit_court_number']."', case_number = '".$_POST['edit_case_number']."', judge_name='".$_POST['edit_judge_name']."', category = '".$_POST['edit_category']."',client_name = '".$_POST['edit_client_name']."', client_phone='".$_POST['edit_client_phone']."', party_a='".$_POST['edit_party_a']."', party_b='".$_POST['edit_party_b']."' WHERE case_id='".$eid."'");
   echo  '<script>alert("Case edited successfully")</script>';
   }
   
   if(isset($_POST['status']))
      {
          $aid = $_REQUEST['lid']; 
          $val = $_REQUEST['lstatus'];
          $new_val = '';
          if($val == 'Active') { $new_val = 'InActive'; $nval = 'InActive';}  
          if($val == 'InActive') {$new_val = 'Active'; $nval = 'Active';} 
          $updstatus = mysqli_query($conn,"UPDATE tbl_cases SET status   = '".$new_val."' where case_id = '".$aid."'");
      }
   
   if(isset($_POST['delete_id']))
      {
      $did = $_REQUEST['did'];  
      $dellawyer = mysqli_query($conn,"DELETE FROM tbl_cases where case_id = '".$did."'");
      echo  '<script>alert("Case Deleted successfully")</script>';
      }
   
    if(isset($_POST['send_to_counsel']))
      {
       $stmt = mysqli_query($conn,"INSERT INTO tbl_counsel_cases(counsel_id, cc_case_id, cc_next_date, cc_status) VALUES('".$_POST['counselor_id']."', '".$_POST['caseeid']."', '".$_POST['case_datee']."', 'Pending')");
      echo  '<script>alert("Case successfully sent to Counselor")</script>';
      }
     
     if(isset($_POST['add_next_date']))
      {
      $stmt = mysqli_query($conn,"INSERT INTO tbl_case_nextdt(next_case_id, prev_case_date, next_case_date, next_stage) VALUES('".$_POST['next_case_id']."', '".$_POST['prev_case_date']."','".$_POST['next_case_date']."','".$_POST['next_stage']."')");
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
                  <div></div>
                  <h3>Cases  <?php if(isset($_POST['searchby_casedate'])) { echo ' ON '.date_format (new DateTime($_POST['case_date']), 'd-M-Y'); } ?><a href="#add_cases" data-toggle="modal" class="btn btn-dark add_new" style="text-transform:initial;">Add</a></h3>
                  <form action="" method="post">
                  <input name="case_date" type="text" class="example1" placeholder="Select Case Date" autocomplete="off">
                  <button name="searchby_casedate" type="submit" class="btn btn-dark">Go</button>
                  </form>
                  <!-- Add Cases -->
                  <div class="zind modal fade" id="add_cases">
                     <div class="modal-dialog">
                        <div class="modal-content">
                           <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                           <div class="modal-header">
                              <h3><i class="pe-7s-users"></i> Add Case</h3>
                           </div>
                           <div class="modal-body">
                              <form class="form" role="form" method="post" action="">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <input class="form-control" name="court_name" placeholder="Court Name" type="text" value="">
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <input class="form-control" name="court_number" placeholder="Court Number" type="text" value="">
                                    </div>
                                 </div>
                                 
                                 
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <input class="form-control" name="case_number" placeholder="Case Number" type="text" value="">
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <input class="form-control" name="judge_name" placeholder="Judge Name" type="text" value="">
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <input class="form-control example1" name="next_date" placeholder="Next Date" type="text" value="">
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <select class="form-control" name="category">
                                       <option>-- Select Category --</option>
                                       <option>Civil</option>
                                       <option>Criminal</option>
                                    </select>
                                    </div>
                                 </div>
                                 <div class="col-md-5">
                                    <div class="form-group">
                                       <input class="form-control" name="client_name" placeholder="Client Name" type="text" value="">
                                    </div>
                                 </div>
                                 <div class="col-md-3">
                                    <div class="form-group">
                                       <input class="form-control" name="party_a" placeholder="Party A" type="text" value="">
                                    </div>
                                 </div>
                                 <div class="col-md-1" style="padding-top:7px;">
                                    <p>VS</p>
                                 </div>
                                 <div class="col-md-3">
                                    <div class="form-group">
                                       <input class="form-control" name="party_b" placeholder="Party B" type="text" value="">
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <input class="form-control" name="client_phone" placeholder="Client Phone" type="text" value="">
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <select class="form-control" name="stage">
                                       <option>-- Select Stage --</option>
                                       <option>Admission</option>
                                       <option>Service</option>
                                       <option>Not-Heard</option>
                                       <option>Hearing</option>
                                       <option>Evidence</option>
                                       <option>Part-Heard</option>
                                       <option>Cross</option>
                                       <option>Arguments</option>
                                       <option>Reply</option>
                                       <option>Dismissal</option>
                                       <option>Bail</option>
                                       <option>Anticipatory Bail</option>
                                       <option>Interim</option>
                                       <option>Add Interim</option>
                                       <option>Defence Witness</option>
                                       <option>Prosecution Witness</option>
                                     </select>
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <button type="submit" class="btn btn-simple" name="add_case">Add</button>
                                 </div>
                              </form>
                           </div>
                        </div>
                     </div>
                  </div>
                  
   <!-- <div class="row interactions">
      <div class="col-md-12">
         <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item active">
               <a data-toggle="tab" href="#news" role="tab">Upcoming Cases</a>
            </li>
            <li class="nav-item">
               <a data-toggle="tab" href="#faq" role="tab">All Cases</a>
            </li>
         </ul>
      <div class="tab-content"> -->

 <?php if(isset($_POST['searchby_casedate'])) {
 ?>
<!-- All Cases -->               

   <div class="table-responsive">
      <table id="example2" class="table table-bordered">
         <thead>
            <tr>
            <?php 
            if($_SESSION['user_type']=='firm')
            {   ?>
               <th>Lawyer Name</th>  
            <?php
            }   
            ?>
               <th>Prev Date</th>
               <th>Court Details</th>
               <th>Case No.</th>
               <th>Category</th>
               <th>Client Name</th>
               <th>Name of Parties</th>
               <th>Stage</th>
               <th>Next Date</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
   <?php
   if($_SESSION['user_type']=='firm')
   {
   $lawyerqry = mysqli_query($conn,"SELECT t1.*,t2.* FROM tbl_cases t1,  tbl_lawyers t2 WHERE t1.lawyer_id=t2.lawyer_id AND t2.firm_id='".$_SESSION['user_id']."' AND t1.next_date='".$_POST['case_date']."'");
   }
   else
   {
   $lawyerqry = mysqli_query($conn,"SELECT * FROM tbl_cases WHERE lawyer_id='".$_SESSION['user_id']."' AND next_date='".$_POST['case_date']."'");
   }


   $lawyerno= mysqli_num_rows($lawyerqry);
   while($lawyerrow=mysqli_fetch_assoc($lawyerqry))
   { 

   $caseqry = mysqli_query($conn,"SELECT max(next_case_date) as MaxDate,next_stage,max(prev_case_date) as PrevMaxDate  FROM tbl_case_nextdt  WHERE next_case_id='".$lawyerrow['case_id']."'  AND next_case_date='".$_POST['case_date']."'");
   $caserow=mysqli_fetch_assoc($caseqry);
   
   if($caserow['MaxDate'] !='') 
        { $max_date= $caserow['MaxDate'];} 
   else { $max_date=$lawyerrow['next_date']; }

   if($caserow['next_stage'] !='') 
        { $case_stage= $caserow['next_stage'];} 
   else { $case_stage=$lawyerrow['stage']; }

   if($caserow['PrevMaxDate'] !='') 
        { $previous_date= $caserow['PrevMaxDate'];} 
   else { $previous_date=$lawyerrow['next_date']; }
   ?>
      
   <tr>
   <?php 
   if($_SESSION['user_type']=='firm')
   {?>
   <td><?php echo $lawyerrow['firm_name'];?></td>
   <?php 
   } ?>
      <td><?php echo date_format (new DateTime($previous_date), 'd-M-y'); ?></td>
      
      <td><u><a href="#court_detailss<?php echo $lawyerrow['case_id'];?>"  data-toggle="modal" title="Court Details"><?php echo $lawyerrow['court_name'];?></a></u>
         <div class="zind modal fade" id="court_detailss<?php echo $lawyerrow['case_id'];?>">
            <div class="modal-dialog">
               <div class="modal-content">
                  <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                  <div class="modal-header">
                     <h3><i class="pe-7s-users"></i> Court Details</h3>
                  </div>
                  <div class="modal-body">
                     <table class="table table-bordered">
                        <thead>
                           <tr>
                              <th>Court Number</th>
                              <th>Judge Name</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td><?php echo $lawyerrow['court_number'];?></td>
                              <td><?php echo $lawyerrow['judge_name'];?></td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </td>

      <td>
         <u><a href="#case_historyy<?php echo $lawyerrow['case_id'];?>"  data-toggle="modal" title="Case History"><?php echo $lawyerrow['case_number'];?></a></u>
<!-- Case History --> 
         <div class="zind modal fade" id="case_historyy<?php echo $lawyerrow['case_id'];?>">
            <div class="modal-dialog">
               <div class="modal-content">
                  <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                  <div class="modal-header">
                     <h3><i class="pe-7s-users"></i> Case History (Case Number: <?php echo $lawyerrow['case_number'];?>)</h3>
                  </div>
                  <div class="modal-body">
                     <table class="table table-bordered">
                        <thead>
                           <tr>
                              <th>Previous Date</th>
                              <th>Stage</th>
                              <!-- <th>Court Name</th> -->
                              <th>Next Date</th>
                              <!-- <th>Category</th>
                              <th>Client Name</th> -->
                              
                           </tr>
                        </thead>
                        <tbody>
                           <?php 
                              $lawyerqry16 = "SELECT * FROM tbl_cases t1, tbl_case_nextdt t2 WHERE t2.next_case_id='".$lawyerrow['case_id']."' AND t1.case_id=t2.next_case_id";
                              $lawyerresults16 = mysqli_query($conn,$lawyerqry16);
                              while($lawyerrow16=mysqli_fetch_assoc($lawyerresults16))
                              { 
                              ?>
                           <tr>
                              <td><?php echo date_format (new DateTime($lawyerrow16['prev_case_date']), 'd-M-y');?></td>
                              <!-- <td><?php echo $lawyerrow16['court_name'];?></td> -->
                              <td><?php echo $lawyerrow16['next_stage'];?></td>
                              <td><?php echo date_format (new DateTime($lawyerrow16['next_case_date']), 'd-M-y');?></td>
                              <!-- <td><?php echo $lawyerrow16['category'];?></td>
                              <td><?php echo $lawyerrow16['client_name'];?></td> -->
                              
                           </tr>
                           <?php } ?>    
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </td> 

      <td><?php echo $lawyerrow['category'];?></td>

      <td><u><a href="#client_detailss<?php echo $lawyerrow['case_id'];?>"  data-toggle="modal" title="Client Details"><?php echo $lawyerrow['client_name'];?></a></u>
         <div class="zind modal fade" id="client_detailss<?php echo $lawyerrow['case_id'];?>">
            <div class="modal-dialog">
               <div class="modal-content">
                  <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                  <div class="modal-header">
                     <h3><i class="pe-7s-users"></i> Client Details</h3>
                  </div>
                  <div class="modal-body">
                     <table class="table table-bordered">
                        <thead>
                           <tr>
                              <th>Client Phone</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td><?php echo $lawyerrow['client_phone'];?></td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </td>                  

      <td><?php echo $lawyerrow['party_a'].' vs '.$lawyerrow['party_b'];?></td>          
      <td <?php if($case_stage=='Evidence' || $case_stage=='Part-Heard' || $case_stage=='Cross' || $case_stage=='Arguments' || $case_stage=='Dismissal/Dispose' || $case_stage=='Withdrawn') { echo 'style="color:red"'; } ?>><?php echo $case_stage;?>
     </td>
                              
      <td><a href="#nextdate_casess<?php echo $lawyerrow['case_id'];?>" class="btn btn_sm1" data-toggle="modal" title="Edit"><u><?php echo date_format (new DateTime($max_date), 'd-M-y');?></u></a></td>
      
      <td class="action">
         <form action="lawyer_availability.php" method="post">
            <input type="hidden" name="send_case_id" value="<?php echo $lawyerrow['case_id'];?>">
            <button type="submit" class="btn btn_sm1" title="Send To Lawyer" name="send_to_lawyer"><i class="fa fa-upload"></i></button>
         </form>
         <a href="#send_counselorr<?php echo $lawyerrow['case_id'];?>" class="btn btn_sm1" data-toggle="modal" title="Send To Counselor"><i class="fa fa-upload"></i></a>
         <a href="#update_casess<?php echo $lawyerrow['case_id'];?>" class="btn btn_sm1" data-toggle="modal" title="Edit"><i class="fa fa-edit"></i></a>
         <a href="#delete_casess<?php echo $lawyerrow['case_id'];?>" class="btn btn_sm1" data-toggle="modal" title="Delete"><i class="fa fa-trash"></i></a>
      </td>
   </tr>

   <div class="zind modal fade" id="nextdate_casess<?php echo $lawyerrow['case_id'];?>">
      <div class="modal-dialog">
         <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
            <div class="modal-header">
               <h3><i class="pe-7s-users"></i> Next Date</h3>
            </div>
            <div class="modal-body">
               <form class="form" role="form" method="post" action="">
                  <input type="hidden" name="next_case_id" value="<?php echo $lawyerrow['case_id'];?>"> 
                  <input type="hidden" name="prev_case_date" value="<?php echo $max_date;?>">
                  
                  <div class="col-md-6">
                     <div class="form-group">
                        <input class="form-control example1" name="next_case_date" placeholder="Next Date" type="text" value="" autocomplete="off">
                     </div>
                  </div>

                  <div class="col-md-6">
            <div class="form-group">
               <select class="form-control" name="next_stage">
               <option>-- Select Stage --</option>
               <option>Admission</option>
               <option>Service</option>
               <option>Not-Heard</option>
               <option>Hearing</option>
               <option>Evidence</option>
               <option>Part-Heard</option>
               <option>Cross</option>
               <option>Arguments</option>
               <option>Reply</option>
               <option>Dismissal/Dispose</option>
               <option>Bail</option>
               <option>Anticipatory Bail</option>
               <option>Interim</option>
               <option>Add Interim</option>
               <option>Defence Witness</option>
               <option>Prosecution Witness</option>
               <option>Withdrawn</option>
             </select>
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
   <div class="zind modal fade" id="send_counselorr<?php echo $lawyerrow['case_id'];?>">
               <div class="modal-dialog">
                  <div class="modal-content">
                     <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                     <div class="modal-header">
                        <h3><i class="pe-7s-users"></i> Send To Counselor</h3>
                     </div>
                     <div class="modal-body">
                        <form class="form" role="form" method="post" action="">
                           <input type="hidden" name="caseeid" value="<?php echo $lawyerrow['case_id'];?>">
                           <input type="hidden" name="case_datee" value="<?php echo $max_date;?>">

                                   <div class="col-md-12">
                     <div class="form-group">
                        <select class="form-control" name="counselor_id">
                        <option>-- Select Counselor --</option>
         <?php 
               $counselqry = mysqli_query($conn,"SELECT counsel_id,counsel_name FROM tbl_counsel");
               while($counselrow=mysqli_fetch_assoc($counselqry))
               { 
         ?>                 
                        <option value="<?php echo $counselrow['counsel_id'];?>"><?php echo $counselrow['counsel_name'];?></option>
         <?php } ?>              
                      </select>
                     </div>
                  </div>
                           <div class="form-group">
                              <button type="submit" name="send_to_counsel" class="btn btn-simple" name="send_case">Send</button>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>

                  <!-- Edit Cases -->            
         <div class="zind modal fade" id="update_casess<?php echo $lawyerrow['case_id'];?>">
            <div class="modal-dialog">
               <div class="modal-content">
                  <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                  <div class="modal-header">
                     <h3><i class="pe-7s-users"></i> Update Case</h3>
                  </div>
                  <div class="modal-body">
                     <form class="form" role="form" method="post" action="">
                        <input type="hidden" name="eid" value="<?php echo $lawyerrow['case_id'];?>"> 
                        <div class="col-md-6">
                           <div class="form-group">
                              <label>Court Name :</label>
                              <input class="form-control" name="edit_court_name" placeholder="Court Name" type="text" value="<?php echo $lawyerrow['court_name'];?>">
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                           <label>Court Number :</label>
                              <input class="form-control" name="edit_court_number" placeholder="Court Number" type="text" value="<?php echo $lawyerrow['court_number'];?>">
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                           <label>Case No. :</label>
                              <input class="form-control" name="edit_case_number" placeholder="Case Number" type="text" value="<?php echo $lawyerrow['case_number'];?>">
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                           <label>Judge Name :</label>
                              <input class="form-control" name="edit_judge_name" placeholder="Judge Name" type="text" value="<?php echo $lawyerrow['judge_name'];?>">
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                           <label>Category :</label>
                              <select class="form-control" name="edit_category">
                              <option>-- Select Category --</option>
                              <option <?php if($lawyerrow['category'] == 'Civil') {echo 'selected';} ?> value="Civil">Civil</option>
                              <option <?php if($lawyerrow['category'] == 'Criminal') {echo 'selected';} ?> value="Criminal">Criminal</option>
                           </select>
                           </div>
                        </div>
                         <div class="col-md-6">
                           <div class="form-group">
                           <label>Phone :</label>
                              <input class="form-control" name="edit_client_phone" placeholder="Phone" type="text" value="<?php echo $lawyerrow['client_phone'];?>">
                           </div>
                        </div>
                           <div class="col-md-5">
                              <div class="form-group">
                              <label>Client Name :</label>
                                 <input class="form-control" name="edit_client_name" placeholder="Client Name" type="text" value="<?php echo $lawyerrow['client_name'];?>">
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="form-group">
                              <label>Party A :</label>
                                 <input class="form-control" name="edit_party_a" placeholder="Party A" type="text" value="<?php echo $lawyerrow['party_a'];?>">
                              </div>
                           </div>
                           <div class="col-md-1" style="padding-top:7px;">
                              <p>VS</p>
                           </div>
                           <div class="col-md-3">
                              <div class="form-group">
                              <label>Party B :</label>
                                 <input class="form-control" name="edit_party_b" placeholder="Party B" type="text" value="<?php echo $lawyerrow['party_b'];?>">
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
      
      <!-- Delete Cases -->
         <div class="zind modal fade" id="delete_casess<?php echo $lawyerrow['case_id'];?>">
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
                           <input type="hidden" name="did" value="<?php echo $lawyerrow['case_id'];?>">
                           <button type="submit" class="btn btn-simple" name="delete_id">Yes</button>
                        </form>
                        <button class="btn btn-simple" data-dismiss="modal">No</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <?php } ?>    
         </tbody>
      </table>
   </div>
<?php } else { ?>
<div class="table-responsive">
      <table id="example2" class="table table-bordered">
         <thead>
            <tr>
            <?php 
            if($_SESSION['user_type']=='firm')
            {   ?>
               <th>Lawyer Name</th>  
            <?php
            }   
            ?>
               <th>Prev Date</th>
               <th>Court Details</th>
               <th>Case No.</th>
               <th>Category</th>
               <th>Client Name</th>
               <th>Name of Parties</th>
               <th>Stage</th>
               <th>Next Date</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
   <?php
   if($_SESSION['user_type']=='firm')
   {
   $lawyerqry = mysqli_query($conn,"SELECT t1.*,t2.* FROM tbl_cases t1,  tbl_lawyers t2 WHERE t1.lawyer_id=t2.lawyer_id AND t2.firm_id='".$_SESSION['user_id']."'");
   }
   else
   {
   $lawyerqry = mysqli_query($conn,"SELECT * FROM tbl_cases WHERE lawyer_id='".$_SESSION['user_id']."'");
   }


   $lawyerno= mysqli_num_rows($lawyerqry);
   while($lawyerrow=mysqli_fetch_assoc($lawyerqry))
   { 

   $caseqry = mysqli_query($conn,"SELECT max(next_case_date) as MaxDate,next_stage,max(prev_case_date) as PrevMaxDate  FROM tbl_case_nextdt  WHERE next_case_id='".$lawyerrow['case_id']."'");
   $caserow=mysqli_fetch_assoc($caseqry);
   
   if($caserow['MaxDate'] !='') 
        { $max_date= $caserow['MaxDate'];} 
   else { $max_date=$lawyerrow['next_date']; }

   if($caserow['next_stage'] !='') 
        { $case_stage= $caserow['next_stage'];} 
   else { $case_stage=$lawyerrow['stage']; }

   if($caserow['PrevMaxDate'] !='') 
        { $previous_date= $caserow['PrevMaxDate'];} 
   else { $previous_date=$lawyerrow['next_date']; }
   ?>
      
   <tr>
   <?php 
   if($_SESSION['user_type']=='firm')
   {?>
   <td><?php echo $lawyerrow['firm_name'];?></td>
   <?php 
   } ?>
      <td><?php echo date_format (new DateTime($previous_date), 'd-M-y'); ?></td>
      
      <td><u><a href="#court_detailss<?php echo $lawyerrow['case_id'];?>"  data-toggle="modal" title="Court Details"><?php echo $lawyerrow['court_name'];?></a></u>
         <div class="zind modal fade" id="court_detailss<?php echo $lawyerrow['case_id'];?>">
            <div class="modal-dialog">
               <div class="modal-content">
                  <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                  <div class="modal-header">
                     <h3><i class="pe-7s-users"></i> Court Details</h3>
                  </div>
                  <div class="modal-body">
                     <table class="table table-bordered">
                        <thead>
                           <tr>
                              <th>Court Number</th>
                              <th>Judge Name</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td><?php echo $lawyerrow['court_number'];?></td>
                              <td><?php echo $lawyerrow['judge_name'];?></td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </td>

      <td>
         <u><a href="#case_historyy<?php echo $lawyerrow['case_id'];?>"  data-toggle="modal" title="Case History"><?php echo $lawyerrow['case_number'];?></a></u>
<!-- Case History --> 
         <div class="zind modal fade" id="case_historyy<?php echo $lawyerrow['case_id'];?>">
            <div class="modal-dialog">
               <div class="modal-content">
                  <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                  <div class="modal-header">
                     <h3><i class="pe-7s-users"></i> Case History (Case Number: <?php echo $lawyerrow['case_number'];?>)</h3>
                  </div>
                  <div class="modal-body">
                     <table class="table table-bordered">
                        <thead>
                           <tr>
                              <th>Previous Date</th>
                              <th>Stage</th>
                              <!-- <th>Court Name</th> -->
                              <th>Next Date</th>
                              <!-- <th>Category</th>
                              <th>Client Name</th> -->
                              
                           </tr>
                        </thead>
                        <tbody>
                           <?php 
                              $lawyerqry16 = "SELECT * FROM tbl_cases t1, tbl_case_nextdt t2 WHERE t2.next_case_id='".$lawyerrow['case_id']."' AND t1.case_id=t2.next_case_id";
                              $lawyerresults16 = mysqli_query($conn,$lawyerqry16);
                              while($lawyerrow16=mysqli_fetch_assoc($lawyerresults16))
                              { 
                              ?>
                           <tr>
                              <td><?php echo date_format (new DateTime($lawyerrow16['prev_case_date']), 'd-M-y');?></td>
                              <!-- <td><?php echo $lawyerrow16['court_name'];?></td> -->
                              <td><?php echo $lawyerrow16['next_stage'];?></td>
                              <td><?php echo date_format (new DateTime($lawyerrow16['next_case_date']), 'd-M-y');?></td>
                              <!-- <td><?php echo $lawyerrow16['category'];?></td>
                              <td><?php echo $lawyerrow16['client_name'];?></td> -->
                              
                           </tr>
                           <?php } ?>    
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </td> 

      <td><?php echo $lawyerrow['category'];?></td>

      <td><u><a href="#client_detailss<?php echo $lawyerrow['case_id'];?>"  data-toggle="modal" title="Client Details"><?php echo $lawyerrow['client_name'];?></a></u>
         <div class="zind modal fade" id="client_detailss<?php echo $lawyerrow['case_id'];?>">
            <div class="modal-dialog">
               <div class="modal-content">
                  <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                  <div class="modal-header">
                     <h3><i class="pe-7s-users"></i> Client Details</h3>
                  </div>
                  <div class="modal-body">
                     <table class="table table-bordered">
                        <thead>
                           <tr>
                              <th>Client Phone</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td><?php echo $lawyerrow['client_phone'];?></td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </td>                  

      <td><?php echo $lawyerrow['party_a'].' vs '.$lawyerrow['party_b'];?></td>          
      <td <?php if($case_stage=='Evidence' || $case_stage=='Part-Heard' || $case_stage=='Cross' || $case_stage=='Arguments' || $case_stage=='Dismissal/Dispose' || $case_stage=='Withdrawn') { echo 'style="color:red"'; } ?>><?php echo $case_stage;?>
     </td>
                              
      <td><a href="#nextdate_casess<?php echo $lawyerrow['case_id'];?>" class="btn btn_sm1" data-toggle="modal" title="Edit"><u><?php echo date_format (new DateTime($max_date), 'd-M-y');?></u></a></td>
      
      <td class="action">
         <form action="lawyer_availability.php" method="post">
            <input type="hidden" name="send_case_id" value="<?php echo $lawyerrow['case_id'];?>">
            <button type="submit" class="btn btn_sm1" title="Send To Lawyer" name="send_to_lawyer"><i class="fa fa-upload"></i></button>
         </form>
         <a href="#send_counselorr<?php echo $lawyerrow['case_id'];?>" class="btn btn_sm1" data-toggle="modal" title="Send To Counselor"><i class="fa fa-upload"></i></a>
         <a href="#update_casess<?php echo $lawyerrow['case_id'];?>" class="btn btn_sm1" data-toggle="modal" title="Edit"><i class="fa fa-edit"></i></a>
         <a href="#delete_casess<?php echo $lawyerrow['case_id'];?>" class="btn btn_sm1" data-toggle="modal" title="Delete"><i class="fa fa-trash"></i></a>
      </td>
   </tr>

   <div class="zind modal fade" id="nextdate_casess<?php echo $lawyerrow['case_id'];?>">
      <div class="modal-dialog">
         <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
            <div class="modal-header">
               <h3><i class="pe-7s-users"></i> Next Date</h3>
            </div>
            <div class="modal-body">
               <form class="form" role="form" method="post" action="">
                  <input type="hidden" name="next_case_id" value="<?php echo $lawyerrow['case_id'];?>"> 
                  <input type="hidden" name="prev_case_date" value="<?php echo $max_date;?>">
                  
                  <div class="col-md-6">
                     <div class="form-group">
                        <input class="form-control example1" name="next_case_date" placeholder="Next Date" type="text" value="" autocomplete="off">
                     </div>
                  </div>

                  <div class="col-md-6">
            <div class="form-group">
               <select class="form-control" name="next_stage">
               <option>-- Select Stage --</option>
               <option>Admission</option>
               <option>Service</option>
               <option>Not-Heard</option>
               <option>Hearing</option>
               <option>Evidence</option>
               <option>Part-Heard</option>
               <option>Cross</option>
               <option>Arguments</option>
               <option>Reply</option>
               <option>Dismissal/Dispose</option>
               <option>Bail</option>
               <option>Anticipatory Bail</option>
               <option>Interim</option>
               <option>Add Interim</option>
               <option>Defence Witness</option>
               <option>Prosecution Witness</option>
               <option>Withdrawn</option>
             </select>
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
   <div class="zind modal fade" id="send_counselorr<?php echo $lawyerrow['case_id'];?>">
               <div class="modal-dialog">
                  <div class="modal-content">
                     <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                     <div class="modal-header">
                        <h3><i class="pe-7s-users"></i> Send To Counselor</h3>
                     </div>
                     <div class="modal-body">
                        <form class="form" role="form" method="post" action="">
                           <input type="hidden" name="caseeid" value="<?php echo $lawyerrow['case_id'];?>">
                           <input type="hidden" name="case_datee" value="<?php echo $max_date;?>">

                                   <div class="col-md-12">
                     <div class="form-group">
                        <select class="form-control" name="counselor_id">
                        <option>-- Select Counselor --</option>
         <?php 
               $counselqry = mysqli_query($conn,"SELECT counsel_id,counsel_name FROM tbl_counsel");
               while($counselrow=mysqli_fetch_assoc($counselqry))
               { 
         ?>                 
                        <option value="<?php echo $counselrow['counsel_id'];?>"><?php echo $counselrow['counsel_name'];?></option>
         <?php } ?>              
                      </select>
                     </div>
                  </div>
                           <div class="form-group">
                              <button type="submit" name="send_to_counsel" class="btn btn-simple" name="send_case">Send</button>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>

                  <!-- Edit Cases -->            
         <div class="zind modal fade" id="update_casess<?php echo $lawyerrow['case_id'];?>">
            <div class="modal-dialog">
               <div class="modal-content">
                  <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                  <div class="modal-header">
                     <h3><i class="pe-7s-users"></i> Update Case</h3>
                  </div>
                  <div class="modal-body">
                     <form class="form" role="form" method="post" action="">
                        <input type="hidden" name="eid" value="<?php echo $lawyerrow['case_id'];?>"> 
                        <div class="col-md-6">
                           <div class="form-group">
                              <label>Court Name :</label>
                              <input class="form-control" name="edit_court_name" placeholder="Court Name" type="text" value="<?php echo $lawyerrow['court_name'];?>">
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                           <label>Court Number :</label>
                              <input class="form-control" name="edit_court_number" placeholder="Court Number" type="text" value="<?php echo $lawyerrow['court_number'];?>">
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                           <label>Case No. :</label>
                              <input class="form-control" name="edit_case_number" placeholder="Case Number" type="text" value="<?php echo $lawyerrow['case_number'];?>">
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                           <label>Judge Name :</label>
                              <input class="form-control" name="edit_judge_name" placeholder="Judge Name" type="text" value="<?php echo $lawyerrow['judge_name'];?>">
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                           <label>Category :</label>
                              <select class="form-control" name="edit_category">
                              <option>-- Select Category --</option>
                              <option <?php if($lawyerrow['category'] == 'Civil') {echo 'selected';} ?> value="Civil">Civil</option>
                              <option <?php if($lawyerrow['category'] == 'Criminal') {echo 'selected';} ?> value="Criminal">Criminal</option>
                           </select>
                           </div>
                        </div>
                         <div class="col-md-6">
                           <div class="form-group">
                           <label>Phone :</label>
                              <input class="form-control" name="edit_client_phone" placeholder="Phone" type="text" value="<?php echo $lawyerrow['client_phone'];?>">
                           </div>
                        </div>
                           <div class="col-md-5">
                              <div class="form-group">
                              <label>Client Name :</label>
                                 <input class="form-control" name="edit_client_name" placeholder="Client Name" type="text" value="<?php echo $lawyerrow['client_name'];?>">
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="form-group">
                              <label>Party A :</label>
                                 <input class="form-control" name="edit_party_a" placeholder="Party A" type="text" value="<?php echo $lawyerrow['party_a'];?>">
                              </div>
                           </div>
                           <div class="col-md-1" style="padding-top:7px;">
                              <p>VS</p>
                           </div>
                           <div class="col-md-3">
                              <div class="form-group">
                              <label>Party B :</label>
                                 <input class="form-control" name="edit_party_b" placeholder="Party B" type="text" value="<?php echo $lawyerrow['party_b'];?>">
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
      
      <!-- Delete Cases -->
         <div class="zind modal fade" id="delete_casess<?php echo $lawyerrow['case_id'];?>">
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
                           <input type="hidden" name="did" value="<?php echo $lawyerrow['case_id'];?>">
                           <button type="submit" class="btn btn-simple" name="delete_id">Yes</button>
                        </form>
                        <button class="btn btn-simple" data-dismiss="modal">No</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <?php } ?>    
         </tbody>
      </table>
   </div>

<?php } ?>
</div>

       
 
   </div>
   </div>
   </div>
   </div>
</section>
<!-- manage free slot end -->
<!-- manage free slot end -->
<?php 
   include("footer.php"); 
   ?>

   <script>
  $(function(){
    $("#example2").dataTable();
  })
  </script>