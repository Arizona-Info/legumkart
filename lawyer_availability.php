<?php 
   include("header.php"); 
   $page = 'lawyer_availability.php';
   if(!isset($_SESSION['user_id']))
   {
     echo  '<script>window.location="index.php"</script>';
   }

   if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] == "Counsel" || $_SESSION['user_type'] == "Intern")
   {
     echo  '<script>window.location="index.php"</script>';
   }
   
  if(isset($_POST['send_casee_idd']))
   {
    $lawyerqry = mysqli_query($conn,"SELECT next_date FROM tbl_cases WHERE case_id='".$_POST['send_case_id']."'");
    $lawyerrow=mysqli_fetch_assoc($lawyerqry);

    $caseqry = mysqli_query($conn,"SELECT max(next_case_date) as MaxDate FROM tbl_case_nextdt  WHERE next_case_id='".$_POST['send_case_id']."'");
    $caserow=mysqli_fetch_assoc($caseqry);

    if($caserow['MaxDate'] !='') 
        { $max_date= $caserow['MaxDate'];} 
   else { $max_date=$lawyerrow['next_date']; }

    $stmt = mysqli_query($conn,"INSERT INTO tbl_lawyer_cases(lc_lawyer_id, lc_case_id, lc_next_date, lc_status,lc_flag) VALUES('".$_POST['receiver_lawyer_id']."', '".$_POST['send_case_id']."', '".$max_date."', 'Pending','2')");
    echo  '<script>alert("Case successfully sent to Lawyer")</script>';
   }

   if(!isset($_POST['search_availability']))
   {

     if(!isset($_POST['send_to_lawyer']))
     {
     echo  '<script>window.location="cases.php"</script>';
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
                  <h3>Lawyer Availability</h3>
                  <div class="row">
                     <div class="col-sm-11 col-xs-12 normal_search">
                        <form method="post" action="">
                            <input type="hidden" name="send_case_id" value="<?php echo $_POST['send_case_id'];?>">
                           <div class="input_field">
                              <input type="search" name="location" value="" placeholder="Search by Location or Lawyer/Firm Name" class="form-control" required>
                              <button name="search_availability" type="submit" class="btn"><i class="fa fa-search"></i></button>
                           </div>
                           <p class="eg">( eg. Mumbai / TheLawMap )</p>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- manage free slot end -->

<?php }

if(isset($_POST['search_availability']))
   {
?>
<section class="manage_freeslot">
   <div class="container-fluid">
      <div class="row">
         <div class="col-xs-12">
          <?php include("sidebar.php"); ?> 
            <div class="col-md-9 col-sm-9 col-xs-12">
               <div class="right_panel">
                  <h3>Result (<?php echo $_POST['location'];?>)</h3>
                  <div class="row">
                     <div class="col-sm-11">
                        <div class="table-responsive">
                           <table id="example" class="table table-bordered">
                              <thead>
                                 <tr>
                                    <!--<th>Sr. No.</th>-->
                                    <!-- <th>Date</th> -->
                                    <th>Court Name</th>
                                    <th>Lawyer Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                 </tr>
                              </thead>
                              <tbody>
      <?php
      $ano =1;
      //A lawyer will be shown in search result only if he/she added atleast one case.
      
      $sql_avail = mysqli_query($conn,"SELECT t2.lawyer_id, t1.next_date, t1.court_name, t2.firm_name, t2.phone, t2.email, t2.availability FROM tbl_cases t1, tbl_lawyers t2 WHERE (t1.court_name LIKE '%".$_POST['location']."%' OR t2.address LIKE '%".$_POST['location']."%' OR t2.address2 LIKE '%".$_POST['location']."%' OR t2.address3 LIKE '%".$_POST['location']."%' OR t2.firm_name LIKE '%".$_POST['location']."%') AND t2.availability='Yes' AND t1.lawyer_id != '".$_SESSION['user_id']."' AND t2.lawyer_id != '".$_SESSION['user_id']."'  Group BY t2.lawyer_id ");
      // $sql_avail = mysqli_query($conn,"SELECT t1.lawyer_id, t1.next_date, t1.court_name, t2.firm_name, t2.phone, t2.email, t2.availability, t3.next_case_date,t3.next_case_id FROM tbl_cases t1, tbl_lawyers t2, tbl_case_nextdt t3 WHERE t1.court_name LIKE '%".$_POST['location']."%' AND t1.lawyer_id=t2.lawyer_id AND t2.availability='Yes' AND t1.lawyer_id!='".$_SESSION['user_id']."' AND t1.case_id=t3.next_case_id AND (t1.next_date >='".date('Y-m-d')."' OR t3.next_case_date>='".date('Y-m-d')."')");  
      while($get_avail_rows = mysqli_fetch_array($sql_avail))
      {
      ?>
       <tr>
          <!--<td><?php echo $ano; ?></td>-->
         
          <td><?php echo $get_avail_rows['court_name']; ?></td>
          <td><?php echo $get_avail_rows['firm_name']; ?></td>
          <td><?php echo $get_avail_rows['phone']; ?></td>
          <td><?php echo $get_avail_rows['email']; ?></td>

          <td class="action">
             <a href="#send_case<?php echo $ano;?>" class="btn btn_sm1" data-toggle="modal" title="Send To Lawyer"><i class="fa fa-upload"></i></a>
          </td>
       </tr>

       <!-- Send Case -->
         <div class="zind modal fade" id="send_case<?php echo $ano;?>">
            <div class="modal-dialog">
               <div class="modal-content">
                  <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                  <div class="modal-header">
                     <h3><i class="pe-7s-users"></i> Send Case</h3>
                  </div>
                  <div class="modal-body1 modal-body">
                     <p>Are you sure you want to Send this case to Lawyer <?php echo $get_avail_rows['firm_name'];?></p>
                     <div class="del_btn">
                        <form action="" method="post">
                           <input type="hidden" name="send_case_id" value="<?php echo $_POST['send_case_id'];?>">
                           <input type="hidden" name="receiver_lawyer_id" value="<?php echo $get_avail_rows['lawyer_id'];?>">

                           <div class="form-group">
                            <input class="styled-checkbox" id="styled-checkbox-15<?php echo $get_avail_rows['lawyer_id'];?>" type="checkbox" required>
                            <label for="styled-checkbox-15<?php echo $get_avail_rows['lawyer_id'];?>"><a href="#lawdisclaimersend" data-toggle="modal">Accept terms and conditions</a></label>
                         </div>

                           <button type="submit" class="btn btn-simple" name="send_casee_idd">Yes</button>
                        </form>
                        <button class="btn btn-simple" data-dismiss="modal">No</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>

      <?php $ano++; } ?>

      <div class="zind modal fade" id="lawdisclaimersend">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                    <div class="modal-header">
                            <h3><!-- <i class="pe-7s-note2"></i> -->Disclaimer</h3>
                        </div>
                        <div class="modal-body">
                            <!-- lawyer availability  -->
                            Neither your receipt of information from this website, nor your use of this website. The content of this website is intended to convey general information. You should not send us any confidential information in response to this webpage. Such responses will not create a lawyer-client relationship, and whatever you disclose to us will not be privileged or confidential unless we have agreed to act as your legal counsel and you have executed a written engagement agreement.
                        </div>
                    </div>
                </div>
            </div>
                                 
                                 
                              
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<?php 
}
   include("footer.php"); 
?>