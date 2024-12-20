<?php 
 include("header.php"); 

  if(!isset($_SESSION['user_id']))
 {
   echo  '<script>window.location="index.php"</script>';
 }

if(isset($_POST['add']))
{

$stmt = mysqli_query($conn,"INSERT INTO tbl_freeslots(lawyer_id, start_time, end_time, day_week, start_date, end_date, status, type) VALUES('".$_SESSION['user_id']."', '".date("H:i:s",strtotime($_POST['start_time']))."', '".date("H:i:s",strtotime($_POST['end_time']))."', '".$_POST['day_week']."', '".$_POST['start_date']."', '".$_POST['end_date']."', 'Yes', '".$_POST['type']."')");
}
?>

<!-- manage free slot start -->
<section class="manage_freeslot">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-3">
                    <div class="sidepanel">
                        <h3>Manage Account</h3>
                        <ul>
                            <li class="active"><a href="freeslots.php">Manage Free Slots</a></li>
                            <li><a href="manage_quotes.php">Manage Quotes</a></li>
                            <li><a href="account_details.php">Account Details</a></li>
                            <?php if($_SESSION['user_type']=='firm') { ?>
                            <li><a href="manage_lawyers.php">Manage Lawyers</a></li>
                            <?php } ?>
                            <li><a href="cases.php">Cases</a></li>
                            <li><a href="password.php">Password Reset</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-9">
                    <?php 
                     $proplist = "SELECT * FROM tbl_freeslots WHERE lawyer_id='".$_SESSION['user_id']."'";
                     $proplist1= mysqli_query($conn,$proplist);
                     $propnum = mysqli_num_rows($proplist1);
                     if($propnum > 0) {
                    ?>
                    <div class="right_panel">
                        <h3>Manage Free Slot</h3>
                        <table class="table table-bordered table-responsive">
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
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <form>
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
                                        <td class="yes_no"><button type="submit" name="add" class="btn btn-dark"><?php echo $proprow['status'];?></button></td>
                                        <td><?php echo $proprow['type'];?></td>
                                        <td class="action">
                                            <a href="#category_edit" class="btn btn_sm1" title="Edit"><i class="fa fa-edit"></i></a>
                                            <a href="#category_delete" class="btn btn_sm1" title="Delete"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                  
                                </form>
                            </tbody>
                        </table>
                    </div>
                    <?php } else { ?>
                    <div class="right_panel">
                        <h3>Manage Free Slot</h3>
                    </div>
                    <?php } ?>
                    <div class="add_more">
                        <div class="well well-sm">
                          <h6>Add More Free Slots</h6>
                          <form class="" action="" method="post">
                            <div class="form-group">
                                <div class="col-md-6">
                                  <label class="col-md-3 control-label" for="name">Type</label>
                                  <div class="col-md-9">
                                    <select div class="add form-control" name="type">
                                        <option value="Weekly">Weekly</option>
                                        <option value="Monthly">Monthly</option>
                                        <option value="Yearly">Yearly</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <label class="col-md-3 control-label" for="name">Day</label>
                                  <div class="col-md-9">
                                    <select div class="add form-control" name="day_week">
                                        <option value="Monday">Monday</option>
                                        <option value="Tuesday">Tuesday</option>
                                        <option value="Wednesday">Wednesday</option>
                                        <option value="Thursday">Thursday</option>
                                        <option value="Friday">Friday</option>
                                        <option value="Saturday">Saturday</option>
                                    </select>
                                  </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6">
                                  <label class="col-md-3 control-label" for="email">Start Time</label>
                                  <div class="col-md-9">
                                    <input name="start_time" type="text" placeholder="" class="form-control scrollDefaultExample ">
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <label class="col-md-3 control-label" for="email">End Time</label>
                                  <div class="col-md-9">
                                    <input name="end_time" type="text" placeholder="" class="form-control scrollDefaultExample ">
                                  </div>
                                </div>
                            </div>
                    
                            <!-- Email input-->
                            <div class="form-group">
                                <div class="col-md-6">
                                  <label class="col-md-3 control-label" for="email">Start Date</label>
                                  <div class="col-md-9">
                                    <input name="start_date" type="text" placeholder="" class="form-control example1">
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <label class="col-md-3 control-label" for="email">End Date</label>
                                  <div class="col-md-9">
                                    <input name="end_date" type="text" placeholder="" class="form-control example1">
                                  </div>
                                </div>
                            </div>
                    
                            <!-- Form actions -->
                            <div class="form-group add_manage">
                              <div class="col-md-12">
                                <button type="submit" name="add" class="btn btn-dark">Add Free Slot</button>
                              </div>
                            </div>
                          </form>
                        </div>
                    </div>
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
