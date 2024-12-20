<?php 
 // error_reporting(0);
 include("header.php"); 
 $page = 'counselor_account_details.php';

 if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'Counsel')
   {
     echo  '<script>window.location="index.php"</script>';
   }
   
 
  if(isset($_POST['saveprofile']))
{


    $check_mail = mysqli_query($conn,"SELECT counsel_email from tbl_counsel WHERE counsel_email='".$_POST['counsel_email']."' AND counsel_id != '".$_SESSION['user_id']."'");
    $check_phone = mysqli_query($conn,"SELECT counsel_phone from tbl_counsel WHERE counsel_phone ='".$_POST['counsel_phone']."' AND counsel_id != '".$_SESSION['user_id']."'");

    if(mysqli_num_rows($check_mail) > 0 && $_POST['counsel_email'] != "") 
    {
       echo '<script>alert("This Email Id is already Registered");</script>';  
    }
    else if(mysqli_num_rows($check_phone) > 0 && $_POST['counsel_phone'] != ""){
        echo '<script>alert("This Phone number is already Registered");</script>';     
    }
    else{

    $update = "UPDATE tbl_counsel SET counsel_name = '".$_POST['counsel_name']."', counsel_address = '".mysqli_real_escape_string($conn,$_POST['counsel_address'])."', counsel_email ='".$_POST['counsel_email']."', counsel_phone ='".$_POST['counsel_phone']."', counsel_areaofpractice = '".mysqli_real_escape_string($conn,$_POST['counsel_areaofpractice'])."', counsel_description = '".mysqli_real_escape_string($conn,$_POST['counsel_description'])."', counsel_gst = '".$_POST['counsel_gst']."', counsel_pan_no = '".strtoupper($_POST['counsel_pan_no'])."' WHERE counsel_id= '".$_SESSION['user_id']."'";
    $uqry = mysqli_query($conn,$update);
    echo '<script>alert("Updated Successfully");</script>';
  }
}

$lawlist = "SELECT * FROM tbl_counsel WHERE counsel_id= '".$_SESSION['user_id']."'";
$lawquery= mysqli_query($conn,$lawlist);
$lawrow=mysqli_fetch_array($lawquery);

$counsel_name = $lawrow['counsel_name'];
$counsel_address = $lawrow['counsel_address'];
$counsel_email = $lawrow['counsel_email'];
$counsel_phone = $lawrow['counsel_phone'];
$counsel_areaofpractice  = $lawrow['counsel_areaofpractice'];
$counsel_description = $lawrow['counsel_description'];
$counsel_designation = $lawrow['counsel_designation'];
$counsel_gst = $lawrow['counsel_gst'];
$counsel_pan_no = $lawrow['counsel_pan_no'];
?>


<!-- manage free slot start -->
<section class="manage_freeslot">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
            <?php include("sidebar1.php"); ?>
              <div class="col-sm-9 col-xs-12">
                  <div class="right_panel">
                      <h3 class="right1">My Profile</h3>
                  </div>
                  <form action="" method="post"  enctype="multipart/form-data">
                    <div class="col-xs-12 padding-left-none">
                      <div class="right2 padding-top-15">
                       
                        <div class="col-md-6 col-xs-12">
                          <div class="form-group">
                            <label class="col-md-4" for="">Name :</label>
                            <div class="col-md-8">
                              <input class="form-control" name="counsel_name" placeholder="Name" type="text" value="<?php echo $counsel_name;?>">
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6 col-xs-12">
                          <div class="form-group">
                            <label class="col-md-4" for="">Email :</label>
                            <div class="col-md-8">
                              <input class="form-control" name="counsel_email" placeholder="Email" type="email" value="<?php echo $counsel_email;?>">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                          <div class="form-group">
                            <label class="col-md-4" for="">Phone :</label>
                            <div class="col-md-8">
                                <input class="form-control" name="counsel_phone" placeholder="Phone" value="<?php echo $counsel_phone;?>">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                          <div class="form-group">
                            <label class="col-md-4" for="">Address :</label>
                            <div class="col-md-8">
                              <textarea class="form-control" name="counsel_address" placeholder="Address" type="text"><?php echo $counsel_address;?></textarea> 
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                          <div class="form-group">
                            <label class="col-md-4" for="">Area of Practice :</label>
                            <div class="col-md-8">
                              <textarea class="form-control" name="counsel_areaofpractice" placeholder="Area Of Practice" type="text"><?php echo $counsel_areaofpractice;?></textarea> 
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                          <div class="form-group">
                            <label class="col-md-4" for="">Description :</label>
                            <div class="col-md-8">
                              <textarea class="form-control" name="counsel_description" placeholder="Counsel Description" type="text"><?php echo $counsel_description;?></textarea> 
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                           <div class="form-group">
                           <label class="col-md-4" for="">Designation :</label>
                            <div class="col-md-8">
                             <select name="counsel_designation" class="form-control">
                                <option value=""><label>---Select Designation---</label></option> 
                                <option <?php if($counsel_designation == 'Counsel') { echo "selected"; } ?> value="Counsel">Counsel</option>    
                                <option <?php if($counsel_designation == 'Designated senior counsel') { echo "selected"; } ?> value="Designated senior counsel">Designated Senior Counsel</option>
                             </select>
                            </div>
                           </div> 
                        </div>
                        <div class="col-md-6 col-xs-12">
                          <div class="form-group">
                            <label class="col-md-4" for="">GST No. :</label>
                            <div class="col-md-8">
                              <textarea class="form-control" name="counsel_gst" placeholder="GST Number" type="text"><?php echo $counsel_gst;?></textarea> 
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                          <div class="form-group">
                            <label class="col-md-4" for="">PAN No. :</label>
                            <div class="col-md-8">
                              <textarea class="form-control" name="counsel_pan_no" placeholder="PAN Number" type="text"><?php echo $counsel_pan_no;?></textarea> 
                            </div>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                          <hr>
                          <button type="submit" name="saveprofile" class="btn btn-dark">Update</button>
                       <!--  </form> -->
                      </div>
                    </div>
                  </form>
                  
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