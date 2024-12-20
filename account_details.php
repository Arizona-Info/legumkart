<?php 
 error_reporting(0);
 include("header.php"); 
 $page = 'account_details.php';
 
 $str_about = ""; 
 $latitude = "";
 $longitude = "";

 if(!isset($_SESSION['user_id']))
 {
   echo  '<script>window.location="index.php"</script>';
 }

 if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] == "Counsel" || $_SESSION['user_type'] == "Intern")
   {
     echo  '<script>window.location="index.php"</script>';
   }
 
 if(isset($_POST['saveprofile']))
{

  if(isset($_POST['quick']))
  {
    $quickk = 'Yes';
  }
  else
  {
    $quickk = 'No';
  }
 
   if(isset($_POST['compare']))
  {
    $comparee = 'Yes';
  }
  else
  {
    $comparee = 'No';
  }

if(isset($_POST['practicearea']))
{
   $str_about = implode(',', $_POST['practicearea']);  
}

if(isset($_POST['languages']))
{
   $str_languages = implode(',', $_POST['languages']);  
}


if(isset($_POST['address']) && $_POST['address']!='')
{
   $prepAddr = urlencode(str_replace(' ','+',$_POST['address']));

                $geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false&key=AIzaSyB_FvTlM37FvMkElZm_L4om4_tO1zgi10Y');
                $output= json_decode($geocode);
                if ($output->status == 'OK') 
                {
                $latitude = $output->results[0]->geometry->location->lat;
                $longitude = $output->results[0]->geometry->location->lng;
                }
} 

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
    $file_name=$_FILES["firm_logo"]["name"];
    $temp_name=$_FILES["firm_logo"]["tmp_name"];
    $imgtype=$_FILES["firm_logo"]["type"];
    $ext= GetImageExtension($imgtype);

    $string = str_replace(' ', '', $_FILES['firm_logo']['name']);
    $stringToArray = explode(".",$string);
    $imagename = date("Ymdhis").rand(1000,10000).".".end($stringToArray);


    $target_path = "img/firms/".$imagename;
    if(move_uploaded_file($temp_name, $target_path)) 
    {
     $update1 = mysqli_query($conn,"UPDATE tbl_lawyers SET firm_logo = '".$imagename."' WHERE lawyer_id = '".$_SESSION['user_id']."'");
    //  echo '<script>alert("Logo Updated Successfully");</script>'; 
    }
     else
     {
       $msg = "Error While uploading image on the server";
     } 
    }

    $check_mail = mysqli_query($conn,"SELECT email from tbl_lawyers WHERE email='".$_POST['email']."' AND lawyer_id != '".$_SESSION['user_id']."'");
    $check_phone = mysqli_query($conn,"SELECT phone from tbl_lawyers WHERE phone ='".$_POST['phone']."' AND lawyer_id != '".$_SESSION['user_id']."'");

    if(mysqli_num_rows($check_mail) > 0 && $_POST['email'] != "") 
    {
       echo '<script>alert("This Email Id is already Registered");</script>';  
    }
    else if(mysqli_num_rows($check_phone) > 0 && $_POST['phone'] != ""){
        echo '<script>alert("This Phone number is already Registered");</script>';     
    }
    else
    {
        $update = "UPDATE tbl_lawyers SET firm_name = '".$_POST['firm_name']."', address = '".mysqli_real_escape_string($conn,$_POST['address'])."', about ='".mysqli_real_escape_string($conn,$str_about)."', email ='".$_POST['email']."', phone ='".$_POST['phone']."', website ='".$_POST['website']."', languages='".$str_languages."',quick_quote ='".$quickk."', compare_quote='".$comparee."',lattitude='".$latitude."',longitude='".$longitude."',about_us='".mysqli_real_escape_string($conn,$_POST['about'])."',availability='".$_POST['availability']."',practice_courts='".trim($_POST['practice_courts'])."', address2 = '".mysqli_real_escape_string($conn,$_POST['address2'])."', address3 = '".mysqli_real_escape_string($conn,$_POST['address3'])."', enroll_number ='".$_POST['enroll_number']."', pan_number = '".$_POST['pan_number']."' WHERE lawyer_id= '".$_SESSION['user_id']."'";
        $uqry = mysqli_query($conn,$update);
        
        $_SESSION['user_email'] = $_POST['email'];
        
        if(($update1 && $update1 != "") || $update){
            echo '<script>alert("Updated Successfully");</script>';
            $_SESSION['user_email'] = $_POST['email'];
            $_SESSION['user_adddr'] = $_POST['address'];
            $_SESSION['user_namee'] = $_POST['firm_name'];
        }
        else{
            echo '<script>alert("Something went wrong, please try again");</script>';
        }
    }

     
}

$lawlist = "SELECT * FROM tbl_lawyers WHERE lawyer_id= '".$_SESSION['user_id']."'";
$lawquery= mysqli_query($conn,$lawlist);
$lawrow=mysqli_fetch_array($lawquery);

$firm_name = $lawrow['firm_name'];
$address = $lawrow['address'];
$address2 = $lawrow['address2'];
$address3 = $lawrow['address3'];

$about = explode(',', $lawrow['about']);
$aboutus = $lawrow['about_us'];
$email = $lawrow['email'];
$phone = $lawrow['phone'];
$website = $lawrow['website'];
$languages = explode(',', $lawrow['languages']);
$compare = $lawrow['compare_quote'];
$quick = $lawrow['quick_quote'];
// $counsel = $lawrow['counsel'];
// $reg_counsel = $lawrow['reg_counsel'];
$firm_logo = $lawrow['firm_logo'];
$availability = $lawrow['availability'];
$practice_courts = $lawrow['practice_courts'];
$enroll_number = $lawrow['enroll_number'];
$pan_number = $lawrow['pan_number'];


?>
<link rel="stylesheet" type="text/css" href="css/awesome-bootstrap-checkbox.css">
<link rel="stylesheet" href="css/multiple-select.css" /> 

<style type "text/css">
/* @group Blink */
.blink {
  -webkit-animation: blink .75s linear infinite;
  -moz-animation: blink .75s linear infinite;
  -ms-animation: blink .75s linear infinite;
  -o-animation: blink .75s linear infinite;
   animation: blink .75s linear infinite;
   color: #ff0808;
   margin: 4px 0 11px;
}
@-webkit-keyframes blink {
  0% { opacity: 1; }
  50% { opacity: 1; }
  50.01% { opacity: 0; }
  100% { opacity: 0; }
}
@-moz-keyframes blink {
  0% { opacity: 1; }
  50% { opacity: 1; }
  50.01% { opacity: 0; }
  100% { opacity: 0; }
}
@-ms-keyframes blink {
  0% { opacity: 1; }
  50% { opacity: 1; }
  50.01% { opacity: 0; }
  100% { opacity: 0; }
}
@-o-keyframes blink {
  0% { opacity: 1; }
  50% { opacity: 1; }
  50.01% { opacity: 0; }
  100% { opacity: 0; }
}
@keyframes blink {
  0% { opacity: 1; }
  50% { opacity: 1; }
  50.01% { opacity: 0; }
  100% { opacity: 0; }
}
/* @end */
</style>

<!-- manage free slot start -->
<section class="manage_freeslot">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
            <?php include("sidebar.php"); ?>

              <div class="col-sm-9 col-xs-12">
                  <div class="col-sm-6 col-xs-12">
                    <div class="right_panel">
                      <h3 class="right1">Account Details</h3>
                    </div>
                  </div>
              <?php  

              $accnt_check = mysqli_query($conn,"SELECT firm_name, email, phone, address, languages, about, enroll_number FROM tbl_lawyers WHERE lawyer_id= '".$_SESSION['user_id']."'");
              $accnt_check_row = mysqli_fetch_array($accnt_check);

              if($accnt_check_row['firm_name'] == "" || $accnt_check_row['email'] == "" || $accnt_check_row['phone'] == "" || $accnt_check_row['address'] == "" || $accnt_check_row['languages'] == "" || $accnt_check_row['about'] == "" || $accnt_check_row['enroll_number'] == "") 
                  {
              ?>
                  <div class="col-sm-6 col-xs-12">
                      <p class="text-right tab blink">Please fill Account Details to get full access</p>
                  </div>
              <?php } ?>
                  <form action="" method="post"  enctype="multipart/form-data">
                    <!-- checkbox_options -->
                    <div class="checkbox_options col-md-10 col-xs-12 padding-left-none">
                      <ul>
                          <li>
                              <input class="styled-checkbox" id="styled-checkbox-1" type="checkbox" name="compare" value="<?php echo $compare;?>" <?php if($compare=='Yes') { echo 'checked';}?>>
                              <label for="styled-checkbox-1">Compare Query</label>
                          </li>
                          <li>
                              <input class="styled-checkbox" id="styled-checkbox-2" type="checkbox" name="quick"  value="<?php echo $quick;?>" <?php if($quick=='Yes') { echo 'checked';}?>>
                              <label for="styled-checkbox-2">Quick Query</label>
                          </li>
                      </ul>
                    </div>
                    <div class="col-xs-12 padding-left-none">
                      <div class="right2 padding-top-15">
                        <div class="col-md-6 col-xs-12">
                          <div class="form-group">
                            <label class="col-md-4" for="">Firm Name * :</label>
                            <div class="col-md-8">
                                <input class="form-control" name="firm_name" placeholder="Firm Name" type="text" value="<?php echo $firm_name;?>" required>
                            </div>
                          </div>
                        </div>
                         <div class="col-md-6 col-xs-12">
                          <div class="form-group">
                            <label class="col-md-4" for="">Logo/Image :</label>
                            <div class="col-md-5">
                                <input type="file" class="form-control" name="firm_logo" placeholder="Firm Logo/Image"  value="<?php echo $firm_logo;?>">
                            </div>
                            
                            <!--<img class="img-responsive col-sm-2 col-xs-3" src="img/firms/<?php echo $firm_logo;?>">-->
                            <?php if($firm_logo != ""){ ?>
                            <img class="img-responsive col-sm-2 col-xs-3" src="img/firms/<?php echo $firm_logo;?>">
                            <?php }else{ ?>
                            <img class="img-responsive col-sm-2 col-xs-3" src="img/about.jpg">
                            <?php } ?>
                            
                          </div>
                        </div>
                          <div class="clearfix"></div>
                        <div class="col-md-6 col-xs-12">
                          <div class="form-group">
                            <label class="col-md-4" for="">Email * :</label>
                            <div class="col-md-8">
                                <input class="form-control" name="email" placeholder="Email" type="email" value="<?php echo $email;?>"  required>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                          <div class="form-group">
                            <label class="col-md-4" for="">Phone * :</label>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="phone" placeholder="Phone" value="<?php echo $phone;?>"  required>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                          <div class="form-group">
                            <label class="col-md-4" for="">Website :</label>
                            <div class="col-md-8">
                                <input class="form-control" name="website" placeholder="Website" value="<?php echo $website;?>">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                          <div class="form-group">
                            <label class="col-md-4" for="">Languages *:</label>
                            <div class="col-md-8">
                              <select  name="languages[]" class="" id="answer1"  multiple="multiple" data-placeholder="Languages Known" required>
                                <option <?php if (in_array("English", $languages)) { echo 'selected';}?>>English</option>
                                <option <?php if (in_array("Marathi", $languages)) { echo 'selected';}?>>Marathi</option>
                                <option <?php if (in_array("Hindi", $languages)) { echo 'selected';}?>>Hindi</option>
                                <option <?php if (in_array("Gujarati", $languages)) { echo 'selected';}?>>Gujarati</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                          <div class="form-group">
                            <label class="col-md-4" for="">About Us :</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="about" placeholder="About" type="text"><?php echo $aboutus;?></textarea> 
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                          <div class="form-group">
                            <label class="col-md-4" for="">Address * :</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="address" placeholder="Address" type="text" required><?php echo $address;?></textarea> 
                            </div>
                          </div>
                        </div>
                        
                        <div class="col-md-6 col-xs-12">
                          <div class="form-group">
                            <label class="col-md-4" for="">Address 2 :</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="address2" placeholder="Address" type="text"><?php echo $address2;?></textarea> 
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6 col-xs-12">
                          <div class="form-group">
                            <label class="col-md-4" for="">Address 3 :</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="address3" placeholder="Address" type="text"><?php echo $address3;?></textarea> 
                            </div>
                          </div>
                        </div>
                       
                        <div class="col-md-6 col-xs-12">
                          <div class="form-group">
                            <label class="col-md-4" for="">Practice Areas * :</label>
                            <div class="col-md-8">
                              <select  class="" id="answer2"  multiple="multiple" name="practicearea[]"  data-placeholder="Practice Areas" required>
                                <?php
                                    $query_for_spec = "SELECT pa_name FROM tbl_practice_areas";
                                    $result_of_spec = mysqli_query($conn, $query_for_spec);
                                    while ($result_of_spec2 = mysqli_fetch_assoc($result_of_spec)) {
                                  ?>
                                    <option <?php if (in_array($result_of_spec2['pa_name'], $about)) { echo 'selected';}?>><?php echo $result_of_spec2['pa_name']; ?></option>
                                  <?php
                                    }
                                  ?>
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6 col-xs-12">
                          <div class="form-group">
                            <label class="col-md-4" for="">Practice Courts * :</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="practice_courts" placeholder="Practice Courts (example : a,b,c)" type="text" title="Put Comma seperated Courts if more than one"><?php echo $practice_courts;?></textarea> 
                            </div>
                          </div>
                        </div>
                        
                        <div class="col-md-6 col-xs-12">
                          <div class="form-group">
                            <label class="col-md-4" for="">Enrollment No. :</label>
                            <div class="col-md-8">
                                <input class="form-control" name="enroll_number" placeholder="Enrollment Number" value="<?php echo $enroll_number;?>">
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6 col-xs-12">
                          <div class="form-group">
                            <label class="col-md-4" for="">PAN No. :</label>
                            <div class="col-md-8">
                                <input class="form-control" name="pan_number" placeholder="PAN Number" value="<?php echo $pan_number;?>">
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6 col-xs-12">
                        <div class=" rad_avai">
                          <div class="form-group">
                            <label class="col-md-4" for="">Available for other lawyer? :</label>
                            <div class="col-md-8">
                              <div class="radio_btn">
                                <input type="radio" id="avail_yes" name="availability" value="Yes" <?php if($availability=='Yes') { echo 'checked'; } ?>>
                                <label for="avail_yes">Yes</label>
                              </div>
                              <div class="radio_btn">
                                <input type="radio" id="avail_no" name="availability" value="No" <?php if($availability=='No') { echo 'checked'; } ?>>
                                <label for="avail_no">No</label>
                              </div>
                            </div>
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