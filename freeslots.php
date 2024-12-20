<?php 
   include("header.php"); 
   $page = 'freeslots.php';
    if(!isset($_SESSION['user_id']))
   {
     echo  '<script>window.location="index.php"</script>';
   }
   
   if(isset($_POST['add_slots']))
   {
   
     $stmt = mysqli_query($conn,"INSERT INTO tbl_freeslots(lawyer_id, start_time, end_time, day_week, start_date, end_date, status, type) VALUES('".$_SESSION['user_id']."', '".date("H:i:s",strtotime($_POST['start_time']))."', '".date("H:i:s",strtotime($_POST['end_time']))."', '".$_POST['day_week']."', '".$_POST['start_date']."', '".$_POST['end_date']."', 'Yes', '".$_POST['type']."')");
   }

   if(isset($_POST['edit_slot']))
   {
    $eid = $_REQUEST['eid'];
    $stmt = mysqli_query($conn,"UPDATE tbl_freeslots SET type = '".$_POST['edit_type']."',day_week = '".$_POST['edit_day_week']."',start_time = '".date("H:i:s",strtotime($_POST['edit_start_time']))."', end_time ='".date("H:i:s",strtotime($_POST['edit_end_time']))."',start_date = '".$_POST['edit_start_date']."', end_date ='".$_POST['edit_end_date']."'  WHERE id='".$eid."'");
    }

   if(isset($_POST['status']))
    {
        $aid = $_REQUEST['lid']; 
        $val = $_REQUEST['lstatus'];
        $new_val = '';
        if($val == 'Yes') { $new_val = 'No'; $nval = 'No';}  
        if($val == 'No') {$new_val = 'Yes'; $nval = 'Yes';} 
        $updstatus = mysqli_query($conn,"UPDATE tbl_freeslots SET status   = '".$new_val."' where id = '".$aid."'");
        // $msg = $nval." Successfully";
    }


   if(isset($_POST['delete_slot']))
    {
        $did = $_REQUEST['did'];  
        $dellawyer = mysqli_query($conn,"DELETE FROM tbl_freeslots where id = '".$did."'");
    }
   ?>
<!-- manage free slot start -->
<section class="manage_freeslot">
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-12 col-xs-12">

<?php include("sidebar.php"); ?>

            <div class="col-md-9 col-sm-9 col-xs-12">
               
               <div class="right_panel">
                  <h3>Manage Free Slot<a href="#add_freeslots" data-toggle="modal" class="btn btn-dark add_new" style="text-transform:initial;">Add</a></h3>

                      <!-- add free slots -->                       
                        <div class="zind modal fade" id="add_freeslots">
                           <div class="modal-dialog">
                              <div class="modal-content">
                                 <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                                 <div class="modal-header">
                                    <h3><i class="pe-7s-users"></i> Add Free Slots</h3>
                                 </div>
                                 <div class="modal-body">
                                    <form class="form" action="" method="post">
                                       <div class="col-md-6">
                                          <div class="form-group">
                                             <select  class="add form-control" name="type">
                                                <option value="Select_type">Select Type</option>
                                                <option value="Weekly">Weekly</option>
                                                <option value="Monthly">Monthly</option>
                                                <option value="Yearly">Yearly</option>
                                             </select>
                                          </div>
                                       </div>
                                       <div class="col-md-6">
                                          <div class="form-group">
                                             <select  class="add form-control" name="day_week">
                                                <option value="select_day">Select Day</option>
                                                <option value="Monday">Monday</option>
                                                <option value="Tuesday">Tuesday</option>
                                                <option value="Wednesday">Wednesday</option>
                                                <option value="Thursday">Thursday</option>
                                                <option value="Friday">Friday</option>
                                                <option value="Saturday">Saturday</option>
                                             </select>
                                          </div>
                                       </div>
                                       <div class="col-md-6">
                                          <div class="form-group">
                                             <input name="start_time" type="text" placeholder="Start Time" class="form-control scrollDefaultExample">
                                          </div>
                                       </div>
                                       <div class="col-md-6">
                                          <div class="form-group">
                                             <input name="end_time" type="text" placeholder="End Time" class="form-control scrollDefaultExample">
                                          </div>
                                       </div>
                                       <div class="col-md-6">
                                          <div class="form-group">
                                             <input name="start_date" type="text" placeholder="Start Day" class="form-control example1">
                                          </div>
                                       </div>
                                       <div class="col-md-6">
                                          <div class="form-group">
                                             <input name="end_date" type="text" placeholder="End Day" class="form-control example1">
                                          </div>
                                       </div>
                                       <div class="form-group">
                                          <button type="submit" class="btn btn-simple" name="add_slots">Submit</button>
                                       </div>
                                    </form>
                                 </div>
                              </div>
                           </div>
                        </div>
                  <?php 
                  $proplist = "SELECT * FROM tbl_freeslots WHERE lawyer_id='".$_SESSION['user_id']."'";
                  $proplist1= mysqli_query($conn,$proplist);
                  $propnum = mysqli_num_rows($proplist1);
                  if($propnum > 0) {
                  ?>
                  <div class=" table-responsive">
                  <table id="example" class="table table-bordered">
                     <thead>
                        <tr>
                           <th>Duration (Min.)</th>
                           <th>Start</th>
                           <th>End</th>
                           <th>Day</th>
                           <th>Start Date</th>
                           <th>End Date</th>
                           <th>Is Active</th>
                           <th>Type</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                           $sr=1;
                           
                           while($proprow=mysqli_fetch_array($proplist1))
                           {   
                           
                           $tmm2= strtotime($proprow['end_time']) + (60*60);
                           
                           // $tmm2= date("H:00:00",strtotime($proprow['end_time']));
                           $tmm1= strtotime($proprow['start_time']) + (60*60);
                           ?> 
                        <tr>
                           <td><?php echo ($tmm2 - $tmm1)/60; ?></td>
                           <td><?php echo date("g:i A",strtotime($proprow['start_time']));?></td>
                           <td><?php echo date("g:i A",strtotime($proprow['end_time']));?></td>
                           <td><?php echo $proprow['day_week'];?></td>
                           <td><?php echo date_format (new DateTime($proprow['start_date']), 'd-M-y');?></td>
                           <td><?php echo date_format (new DateTime($proprow['end_date']), 'd-M-y');?></td>
                     <form action="" method="post">
                           <input type="hidden" name="lid" value="<?php echo $proprow['id'];?>">
                           <input type="hidden" name="lstatus" value="<?php echo $proprow['status'];?>">      
                           <td class="yes_no"><button type="submit" name="status" class="btn btn-dark"><?php echo $proprow['status'];?></button></td>
                     </form>      
                           <td><?php echo $proprow['type'];?></td>
                           <td class="action">
                              <a href="#update_freeslots<?php echo $proprow['id'];?>" data-toggle="modal" class="btn btn_sm1" title="Edit"><i class="fa fa-edit"></i></a>
                              <a href="#delete_freeslots<?php echo $proprow['id'];?>" data-toggle="modal" class="btn btn_sm1" title="Delete"><i class="fa fa-trash"></i></a>
                           </td>
                        </tr>
                    
                        <!--update slots-->        
                        <div class="zind modal fade" id="update_freeslots<?php echo $proprow['id'];?>">
                           <div class="modal-dialog">
                              <div class="modal-content">
                                 <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                                 <div class="modal-header">
                                    <h3><i class="pe-7s-users"></i> Update Slot</h3>
                                 </div>
                                 <div class="modal-body">
                                    <form class="form" role="form" method="post" action="">
                                       <input type="hidden" name="eid" value="<?php echo $proprow['id'];?>"> 
                                       <div class="col-md-6">
                                          <div class="form-group">
                                             <select div class="add form-control" name="edit_type">
                                                <option <?php if($proprow['type'] == 'Weekly') { echo 'selected'; } ?> value="Weekly">Weekly</option>
                                                <option <?php if($proprow['type'] == 'Monthly') { echo 'selected'; } ?> value="Monthly">Monthly</option>
                                                <option <?php if($proprow['type'] == 'Yearly') { echo 'selected'; } ?> value="Yearly">Yearly</option>
                                             </select>
                                          </div>
                                       </div>
                                       <div class="col-md-6">
                                          <div class="form-group">
                                             <select div class="add form-control" name="edit_day_week">
                                                <option <?php if($proprow['day_week'] == 'Monday') { echo 'selected'; } ?> value="Monday">Monday</option>
                                                <option <?php if($proprow['day_week'] == 'Tuesday') { echo 'selected'; } ?> value="Tuesday">Tuesday</option>
                                                <option <?php if($proprow['day_week'] == 'Wednesday') { echo 'selected'; } ?> value="Wednesday">Wednesday</option>
                                                <option <?php if($proprow['day_week'] == 'Thursday') { echo 'selected'; } ?> value="Thursday">Thursday</option>
                                                <option <?php if($proprow['day_week'] == 'Friday') { echo 'selected'; } ?> value="Friday">Friday</option>
                                                <option <?php if($proprow['day_week'] == 'Saturday') { echo 'selected'; } ?> value="Saturday">Saturday</option>
                                             </select>
                                          </div>
                                       </div>
                                       <div class="col-md-6">
                                          <div class="form-group">
                                             <input name="edit_start_time" type="text" placeholder="Start Time" class="form-control scrollDefaultExample " value="<?php echo $proprow['start_time'];?>">
                                          </div>
                                       </div>
                                       <div class="col-md-6">
                                          <div class="form-group">
                                             <input name="edit_end_time" type="text" placeholder="End Time" class="form-control scrollDefaultExample " value="<?php echo $proprow['end_time'];?>">
                                          </div>
                                       </div>
                                       <div class="col-md-6">
                                          <div class="form-group">
                                             <input name="edit_start_date" type="text" placeholder="Start Day" class="form-control example1" value="<?php echo $proprow['start_date'];?>">
                                          </div>
                                       </div>
                                       <div class="col-md-6">
                                          <div class="form-group">
                                             <input name="edit_end_date" type="text" placeholder="End Day" class="form-control example1" value="<?php echo $proprow['end_date'];?>">
                                          </div>
                                       </div>
                                       <div class="form-group">
                                          <button type="submit" class="btn btn-simple" name="edit_slot">Update</button>
                                       </div>
                                    </form>
                                 </div>
                              </div>
                           </div>
                        </div>

                        <!--delete slots-->         
                        <div class="zind modal fade" id="delete_freeslots<?php echo $proprow['id'];?>">
                           <div class="modal-dialog">
                              <div class="modal-content">
                                 <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                                 <div class="modal-header">
                                    <h3><i class="pe-7s-users"></i> Delete Slot</h3>
                                 </div>
                                 <div class="modal-body1 modal-body">
                                    <p>Are you sure you want to delete this Free Slots... </p>
                                    <div class="del_btn">
                                    <form action="" method="post">
                                    <input type="hidden" name="did" value="<?php echo $proprow['id'];?>">
                                       <button type="submit" class="btn btn-simple" name="delete_slot">Yes</button>
                                    </form>
                                       <button data-dismiss="modal" class="btn btn-simple">No</button>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <?php
                           }
                           ?>
                     </tbody>
                  </table>
                  </div>
               </div>
               <?php } else { echo "No Slots Added"; } ?>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- manage free slot end -->
<?php 
   include("footer.php"); 
   ?>
<script src="js/bootstrap-datepicker.js"></script>
<script type="text/javascript">
   // When the document is ready
   $(document).ready(function () {
       $('.example1').datepicker({
           format: "yyyy-mm-dd",
            autoclose: true
       });  
   });
</script>
<script type="text/javascript" src="js/jquery.timepicker.js"></script>
<script>
   $(function() {
       $('.scrollDefaultExample').timepicker({  'minTime': '9:00am',
   'maxTime': '9:00pm' });
   });
   
</script>