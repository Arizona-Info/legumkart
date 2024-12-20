<?php 
 include("header.php"); 
 $page = 'intern_account_details.php';

 if(!isset($_SESSION['user_id']))
  {
   echo  '<script>window.location="index.php"</script>';
  }
 

 if(isset($_POST['saveprofile']))
  {

        if(isset($_POST['languages']))
        {
         $str_languages = implode(',', $_POST['languages']);  
        }

        $update = "UPDATE tbl_intern SET intern_name = '".$_POST['intern_name']."', intern_address = '".mysqli_real_escape_string($conn,$_POST['intern_address'])."', intern_email ='".$_POST['intern_email']."', intern_phone ='".$_POST['intern_phone']."',intern_languages ='".$str_languages."',intern_about ='".$_POST['intern_about']."' WHERE intern_id= '".$_SESSION['user_id']."'";
        $uqry = mysqli_query($conn,$update);
       
          //image Upload
        function GetImageExtension($imagetype)
         {
             if(empty($imagetype)) return false;
             switch($imagetype)
             {
                 case 'image/bmp': return '.bmp';
                 case 'image/gif': return '.gif';
                 case 'image/jpeg': return '.jpg';
                 case 'image/png': return '.png';
                 default: return false;
             }
          }

         if($_FILES["firm_logo"]["name"]!='') 
         {
          $id=$_SESSION['user_id'];
          $file_name=$_FILES["firm_logo"]["name"];
          $temp_name=$_FILES["firm_logo"]["tmp_name"];
          $imgtype=$_FILES["firm_logo"]["type"];
          $ext= GetImageExtension($imgtype);
          $imagename = $_FILES['firm_logo']['name'];
          $target_path = "img/interns/".$id.$imagename;
          if(move_uploaded_file($temp_name, $target_path)) 
          {
           $update1 = mysqli_query($conn,"UPDATE tbl_intern SET firm_logo = '".$id.$_FILES['firm_logo']['name']."' WHERE intern_id = '".$_SESSION['user_id']."'");
          }
           else
           {
             $msg = "Error While uploading image on the server";
           } 
          }

         //  if($_FILES["intern_resume"]["name"]!='') 
         // {
         //  $id=$_SESSION['user_id'];
         //  $file_name=$_FILES["intern_resume"]["name"];
         //  $temp_name=$_FILES["intern_resume"]["tmp_name"];
         //  $imgtype=$_FILES["intern_resume"]["type"];
         //  // $ext= GetImageExtension($imgtype);
         //  $imagename = $_FILES['intern_resume']['name'];
         //  $target_path = "img/intern_resumes/".$imagename;
         //  if(move_uploaded_file($temp_name, $target_path)) 
         //  {
         //   $update1 = mysqli_query($conn,"UPDATE tbl_intern SET firm_logo = '".$_FILES['firm_logo']['name']."' WHERE intern_id = '".$_SESSION['user_id']."'");
         //  }
         //   else
         //   {
         //     $msg = "Error While uploading image on the server";
         //   } 
         //  }

          echo '<script>alert("Updated Successfully");</script>';      
 }

$lawlist = "SELECT * FROM tbl_intern WHERE intern_id= '".$_SESSION['user_id']."'";
$lawquery= mysqli_query($conn,$lawlist);
$lawrow=mysqli_fetch_array($lawquery);
        
$firm_name = $lawrow['intern_name'];
$address = $lawrow['intern_address'];
$email = $lawrow['intern_email'];
$phone = $lawrow['intern_phone'];
$firm_logo = $lawrow['firm_logo'];
$languages = explode(',', $lawrow['intern_languages']);
$aboutus = $lawrow['intern_about'];

?>
<link rel="stylesheet" type="text/css" href="css/awesome-bootstrap-checkbox.css">
<link rel="stylesheet" href="css/multiple-select.css" /> 

<!-- manage free slot start -->
<section class="manage_freeslot">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
            <?php include("sidebar2.php"); ?>

              <div class="col-md-9 col-sm-9 col-xs-12">
                  <div class="right_panel">
                      <h3 class="right1">Account Details</h3>
                  </div>
                  <form action="" method="post"  enctype="multipart/form-data">

                    <div class="col-md-12 col-sm-12 col-xs-12 padding-left-none">
                      <div class="right2 padding-top-15">
                       
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Intern Name :</label>
                            <div class="col-md-8">
                                <input class="form-control" name="intern_name" placeholder="Intern Name" type="text" value="<?php echo $firm_name;?>">
                            </div>
                          </div>
                        </div>

                         <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Image :</label>
                            <div class="col-md-5">
                                <input type="file" class="form-control" name="firm_logo" placeholder="Intern Image"  value="<?php echo $firm_logo;?>">
                            </div>
                            <img class="img-responsive col-sm-2 col-xs-3" src="img/interns/<?php echo $firm_logo;?>">
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Email :</label>
                            <div class="col-md-8">
                                <input class="form-control" name="intern_email" placeholder="Email" type="email" value="<?php echo $email;?>">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Phone :</label>
                            <div class="col-md-8">
                                <input class="form-control" name="intern_phone" placeholder="Phone" value="<?php echo $phone;?>">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Languages Known:</label>
                            <div class="col-md-8">
                              <select  name="languages[]" class="" id="answer1"  multiple="multiple" data-placeholder="Languages Known">
                                <option <?php if (in_array("English", $languages)) { echo 'selected';}?>>English</option>
                                <option <?php if (in_array("Marathi", $languages)) { echo 'selected';}?>>Marathi</option>
                                <option <?php if (in_array("Hindi", $languages)) { echo 'selected';}?>>Hindi</option>
                                <option <?php if (in_array("Gujarati", $languages)) { echo 'selected';}?>>Gujarati</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">About Me :</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="intern_about" placeholder="About" type="text"><?php echo $aboutus;?></textarea> 
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Address :</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="intern_address" placeholder="Address" type="text"><?php echo $address;?></textarea> 
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Upload Resume:</label>
                            <div class="col-md-8">
                                <input type="file" class="form-control" name="intern_resume" placeholder="Upload Resume"  value="<?php echo $firm_logo;?>">
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

<script src="js/multiple-select.js"></script> 
<script>
$(function() {
        $('#answer1').multipleSelect({
            width: '100%'
        });
    });
    $(function() {
        $('#answer2').multipleSelect({
            width: '100%'
        });
    });
    $(function() {
        $('#answer3').multipleSelect({
            width: '100%'
        });
    });
     $(function() {
        $('#answer4').multipleSelect({
            width: '100%'
        });
    });
</script>