<?php 
   include("header.php"); 
   // $page = 'counsel_availability.php';
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


   if(!isset($_POST['search_counsel_availability']))
   {

     if(!isset($_POST['send_to_counsell']))
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
                  <h3>Counsel Availability</h3>
                  <div class="row">
                     <div class="col-sm-11 col-xs-12 normal_search">
                        <form method="post" action="">
                            <input type="hidden" name="send_case_id" value="<?php echo $_POST['send_case_id'];?>">
                            <input type="hidden" name="case_datee" value="<?php echo $_POST['case_datee'];?>">
                           <div class="input_field">
                              <input type="search" name="location" value="" placeholder="Search by Location or Counsel Name" class="form-control" required>
                              <button name="search_counsel_availability" type="submit" class="btn"><i class="fa fa-search"></i></button>
                           </div>
                           <p class="eg">( eg. Mumbai / Counsel Name )</p>
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

if(isset($_POST['search_counsel_availability']))
   {
?>
<section class="manage_freeslot">
   <div class="container-fluid">
      <div class="row">
         <div class="col-xs-12">
          <?php include("sidebar.php"); ?> 
            <div class="col-md-9 col-sm-9 col-xs-12">
               <div class="right_panel">
                  <h3>Counsels In <?php echo $_POST['location'];?></h3>
                  <div class="row">
                     <div class="col-sm-11">
                        <div class="table-responsive">
                           <table id="example" class="table table-bordered">
                              <thead>
                                 <tr>
                                    <!--<th>Sr. No.</th>-->
                                    <th>Counsel Name</th>
                                    <th>Designation</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Area Of Practice</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                 </tr>
                              </thead>
                              <tbody>
      <?php
      $ano =1;
      $sql_avail = mysqli_query($conn,"SELECT * FROM tbl_counsel WHERE (counsel_name LIKE '%".$_POST['location']."%' OR counsel_address LIKE '%".$_POST['location']."%') AND  counsel_status='Approved'");
      while($get_avail_rows = mysqli_fetch_array($sql_avail))
      {
      ?>
       <tr>
          <!--<td><?php echo $ano; ?></td>-->
          <td><?php echo $get_avail_rows['counsel_name']; ?></td>
          <td><?php echo $get_avail_rows['counsel_designation']; ?></td>
          <td><?php echo $get_avail_rows['counsel_phone']; ?></td>
          <td><?php echo $get_avail_rows['counsel_email']; ?></td>
          <td><?php echo $get_avail_rows['counsel_address']; ?></td>
          <td><?php echo $get_avail_rows['counsel_areaofpractice']; ?></td>
          <td><?php echo $get_avail_rows['counsel_description']; ?></td>
     

          <td class="action">
             <a href="#send_case<?php echo $ano;?>" class="btn btn_sm1" data-toggle="modal" title="Send To Counsel"><i class="fa fa-upload"></i></a>
          </td>
       </tr>

       <!-- Send Case -->
         <div class="zind modal fade" id="send_case<?php echo $ano;?>">
            <div class="modal-dialog">
               <div class="modal-content">
                     <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                     <div class="modal-header">
                        <h3><i class="pe-7s-user"></i> Send To Counsel</h3>
                     </div>
                     <div class="modal-body">
                        <form class="form" role="form" method="post" action="">
                           <div class="row">
                              <input type="hidden" name="caseeid" value="<?php echo $_POST['send_case_id'];?>">
                              <input type="hidden" name="counselor_id" value="<?php echo $get_avail_rows['counsel_id'];?>">
                              <input type="hidden" name="case_datee" value="<?php echo $_POST['case_datee'];?>">

                                <div class="col-xs-12">
                             

                                    <div class="form-group">
                                       <select class="form-control select_type_for" name="type_selection" required>
                                          <option value="">-- Select Type --</option>
                                          <option value="conference">For Conference</option>
                                          <option value="hearing">For Hearing</option>
                                       </select>
                                    </div>

                                    <div class="form-group if_hearing" style="display: none;">
                                       <select class="form-control" name="usr_time">
                                          <option value="11:00:00">11 AM</option>
                                          <option value="15:00:00">3 PM</option>
                                       </select>
                                       <!-- <input class="form-control if_hearing2" type="hidden" name="usr_time" required> -->
                                    </div>

                                    <div class="form-group">
                                      <input class="styled-checkbox" id="styled-checkbox-12<?php echo $get_avail_rows['counsel_id'];?>" type="checkbox" required>
                                      <label for="styled-checkbox-12<?php echo $get_avail_rows['counsel_id'];?>"><a href="#lawdisclaimersend" data-toggle="modal">Accept terms and conditions</a></label>
                                  </div>

                                 </div>
                              <div class="form-group col-xs-12">
                                 <button type="submit" name="send_to_counsel" class="btn btn-simple" name="send_case">Send</button>
                              </div>
                           </div>
                        </form>
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
                            <h3>Disclaimer</h3>
                        </div>
                        <div class="modal-body">
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

<script type="text/javascript">
   $(".select_type_for").change(function(){
      val = $(this).find('option:selected');
      val = val.val();
      if(val == "hearing"){
         $(".if_hearing").slideDown();
         $(".if_hearing2").attr("type","time");
      }
      else{
         $(".if_hearing").slideUp("");
         $(".if_hearing2").attr("type","hidden");
      }
   })
</script>