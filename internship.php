<?php 
 include("header.php");
 $page = 'internship.php'; 
 
 if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'Intern')
   {
     echo  '<script>window.location="index.php"</script>';
   }
 // function added to update multiple fields
  function update_multi_fields($conn,$table,$querystring,$condition)
  {
  
   $update1="UPDATE $table SET $querystring WHERE $condition";
   return $uqry1 = mysqli_query($conn,$update1);
  // print_r($update1);
  }

 if(!isset($_SESSION['user_id']))
  {
   echo  '<script>window.location="index.php"</script>';
  }
 

       $querystring='';
       $filter= array();
 if(isset($_POST['saveprofile']))
  {

        if(isset($_POST['languages']))
        {
         $str_languages = implode(',', $_POST['languages']);  
        }

        if(isset($_POST['intern_lookingfor']))
        {
         $str_intern_lookingfor = implode(',', $_POST['intern_lookingfor']);  
        }
    
        if(isset($_POST['intern_interest']))
        {
           $intern_interest = implode(',', $_POST['intern_interest']);  
        }

        $check_mail = mysqli_query($conn,"SELECT intern_email from tbl_intern WHERE intern_email='".$_POST['intern_email']."' AND intern_id != '".$_SESSION['user_id']."'");
        $check_phone = mysqli_query($conn,"SELECT intern_phone from tbl_intern WHERE intern_phone ='".$_POST['intern_phone']."' AND intern_id != '".$_SESSION['user_id']."'");

        if(mysqli_num_rows($check_mail) > 0 && $_POST['intern_email'] != "") 
        {
           echo '<script>alert("This Email Id is already Registered");</script>';  
        }
        else if(mysqli_num_rows($check_phone) > 0 && $_POST['intern_phone'] != ""){
            echo '<script>alert("This Phone number is already Registered");</script>';     
        }
        else{
            $update = "UPDATE tbl_intern SET intern_name = '".ucwords($_POST['intern_name'])."', intern_address = '".mysqli_real_escape_string($conn,ucwords($_POST['intern_address']))."', intern_email ='".$_POST['intern_email']."', intern_phone ='".$_POST['intern_phone']."',intern_languages ='".ucwords($str_languages)."',intern_about ='".ucwords($_POST['intern_about'])."', intern_interest = '".$intern_interest."',intern_lookingfor ='".ucwords($str_intern_lookingfor)."' WHERE intern_id= '".$_SESSION['user_id']."'";
            $uqry = mysqli_query($conn,$update);
            $msg = "Updated Successfully";
        }
  
     //**************************************************************

       $filter=$_POST['filter'];       
       $intern_id = $_SESSION['user_id'];
      
        foreach ($filter as $key => $value) {
          if ($value!='') {
                        $value = ucwords(trim($value));
                        $querystring.= $key." = "."'$value'".",";
                     }
          }

          $querystring = chop($querystring,","); 

         $upd_extra_fields=update_multi_fields($conn,"tbl_intern",$querystring,"intern_id='$intern_id'");

   //**************************************************************     
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

          $del_prev_fromfolder= mysqli_query($conn,"SELECT firm_logo,upload_resume FROM tbl_intern WHERE intern_id = '".$_SESSION['user_id']."'");
          $result=mysqli_fetch_array($del_prev_fromfolder);


         if($_FILES["firm_logo"]["name"]!='') 
         {
          if($result['firm_logo']!=""){
            unlink("img/interns/".$result['firm_logo']);
          }
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

          if($_FILES["intern_resume"]["name"]!='') 
         {
          if($result['upload_resume']!=""){
            unlink("img/intern_resumes/".$result['upload_resume']);
          }
          $id=$_SESSION['user_id'];
          $file_name=$_FILES["intern_resume"]["name"];
          $temp_name=$_FILES["intern_resume"]["tmp_name"];
          $imgtype=$_FILES["intern_resume"]["type"];
          $target_path = "img/intern_resumes/".$id.$file_name;
          if(move_uploaded_file($temp_name, $target_path)) 
          {
           $update1 = mysqli_query($conn,"UPDATE tbl_intern SET upload_resume = '".$id.$_FILES['intern_resume']['name']."' WHERE intern_id = '".$_SESSION['user_id']."'");
          }
           else
           {
             $msg = "Error While uploading resume on the server";
           } 
          }

          if($msg!=""){
            echo '<script>alert("'.$msg.'");</script>';  
          }
          
 }

$lawlist = "SELECT * FROM tbl_intern WHERE intern_id= '".$_SESSION['user_id']."'";
$lawquery= mysqli_query($conn,$lawlist);
$lawrow=mysqli_fetch_array($lawquery);
        
$firm_name = $lawrow['intern_name'];
$address = $lawrow['intern_address'];
$email = $lawrow['intern_email'];
$phone = $lawrow['intern_phone'];
$firm_logo = $lawrow['firm_logo'];
$view_resume = $lawrow['upload_resume'];
$languages = explode(',', $lawrow['intern_languages']);
$intern_lookingfor = explode(',',$lawrow['intern_lookingfor']);
$aboutus = $lawrow['intern_about'];

$about123 = explode(',', $lawrow['intern_interest']);

$intern_college = $lawrow['intern_college'];
$intern_degree = $lawrow['intern_degree'];
$intern_currentyear = $lawrow['intern_currentyear'];
$intern_expectedyear = $lawrow['intern_expectedyear'];
$itern_cgpa = $lawrow['itern_cgpa'];
$intern_preintership = $lawrow['intern_preintership'];
$intern_publication = $lawrow['intern_publication'];
$itern_allintership = $lawrow['itern_allintership'];
$itern_allpublication = $lawrow['itern_allpublication'];
$intern_work = $lawrow['intern_work'];
$intern_area = $lawrow['intern_area'];
$intern_type = $lawrow['intern_type'];
$intern_interest = $lawrow['intern_interest'];
$intern_location = $lawrow['intern_location'];
$intern_preferredduration = $lawrow['intern_preferredduration'];
$intern_allpublication = $lawrow['intern_allpublication'];
$intern_experience = $lawrow['intern_experience'];
$confirmation = $lawrow['confirmation'];
$intern_applyingas = $lawrow['intern_applyingas'];
// $intern_lookingfor = $lawrow['intern_lookingfor'];
$intern_enrollnumber = $lawrow['intern_enrollnumber'];
$intern_enrolldate = $lawrow['intern_enrolldate'];


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
                      <h3 class="right1">Internship Application Form</h3>
                  </div>

      <form action="" method="post"  enctype="multipart/form-data" autocomplete="off">

                    <div class="col-md-12 col-sm-12 col-xs-12 padding-left-none">
                      <div class="right2 padding-top-15">                       
                         <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Intern Name :</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="intern_name" placeholder="Intern Name"  value="<?php echo $firm_name;?>">
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Photo :</label>
                            <div class="col-md-5">
                                <input type="file" class="form-control" name="firm_logo" placeholder="Intern Image" accept="image/*" value="<?php echo $firm_logo;?>">
                            </div>
                            <?php
                              if($firm_logo!=""){
                            ?>
                            <img class="img-responsive col-sm-2 col-xs-3" src="img/interns/<?php echo $firm_logo;?>">
                            <?php } ?>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                        
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
                            <label class="col-md-4" for="">Contact No :</label>
                            <div class="col-md-8">
                                <input class="form-control" name="intern_phone" placeholder="Phone" value="<?php echo $phone;?>">
                            </div>
                          </div>
                        </div>
                        <div class="clearfix"></div>

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
                        <div class="clearfix"></div>
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
                            <div class="col-md-5">
                                <input type="file" class="form-control" name="intern_resume" placeholder="Upload Resume"  accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf" value="<?php echo $view_resume;?>">
                            </div>

                            <?php
                              if($view_resume!=""){
                            ?>
                            <a class="img-responsive col-sm-2 col-xs-3" href="img/intern_resumes/<?php echo $view_resume;?>">View</a>
                            <?php
                              }
                            ?>
                            
                          </div>
                        </div>                       
                        <div class="clearfix"></div>
                 
                       
                        <!-- <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Publication:</label>
                            <div class="col-md-8">
                              <select  name="filter[intern_publication]" class="form-control" data-placeholder="Publication">
                                <option value="<?php if ($intern_publication=='Yes') { echo 'selected';}?>">Yes</option>
                                <option value="<?php if ($intern_publication=='No') { echo 'selected';}?>">No</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Enlist Your previous legal internship :</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="filter[itern_allintership]" placeholder="Enter Previous intership" value="<?php echo $itern_allintership;?>">
                            </div>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Your Best three publiction title :</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="filter[itern_allpublication]" placeholder="Enter best three publication title" value="<?php echo $itern_allpublication;?>">
                            </div>
                          </div>
                        </div>
                        
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Brief description of work handled(100 words max) :</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="filter[intern_work]" placeholder="description of work" type="text"><?php echo $intern_work;?></textarea> 
                            </div>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Area of publications (50 words max) :</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="filter[intern_area]" placeholder="Area of publications" type="text"><?php echo $intern_area;?></textarea> 
                            </div>
                          </div>
                        </div>
                        
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Type of Publication :</label>
                            <div class="col-md-8">
                                <select name="filter[intern_type]" class="form-control">
                               <option <?php if ($intern_type=='Internal') { echo 'selected';}?>>Internal</option>
                                  <option <?php if ($intern_type=='National') { echo 'selected';}?>>National</option>
                                  <option <?php if ($intern_type=='International') { echo 'selected';}?>>International</option>
                                </select> 
                            </div>
                          </div>
                        </div> -->
                        <!-- <div class="clearfix"></div> -->
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Area of interest :</label>
                            <div class="col-md-8">
                                <select id="answer26"  multiple="multiple" name="intern_interest[]"  data-placeholder="Practice Areas" required>

                            <?php
                              $qry = "SELECT pa_name FROM tbl_practice_areas";
                              $result2 = mysqli_query($conn, $qry);
                              while ($result23 = mysqli_fetch_assoc($result2)) {
                                ?>
                                  
                                  <option <?php if (in_array($result23['pa_name'], $about123)) { echo 'selected';}?>><?php echo $result23['pa_name']; ?></option>

                                <?php
                              }

                            ?>

                                </select> 
                            </div>
                          </div>
                        </div>
                        

                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Preferred Location :</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="filter[intern_location]" placeholder="Eg:-Mumbai, Navi Mumbai" value="<?php echo $intern_location;?>">
                            </div>
                          </div>
                        </div>

                       <!-- <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Looking For:</label>
                            <div class="col-md-8">
                              <select  name="filter[intern_lookingfor]" class="form-control" data-placeholder="Looking For">
                              <option value="">---Select---</option>
                                <option value="Law Firm" <?php if ($intern_lookingfor=='Law Firm') { echo 'selected';}?> >Law Firm</option>
                                <option value="Lawyer" <?php if ($intern_lookingfor=='Lawyer') { echo 'selected';}?> >Lawyer</option>
                              </select>
                            </div>
                          </div>
                        </div> -->
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Looking For:</label>
                            <div class="col-md-8">
                              <select  name="intern_lookingfor[]" class="" id="answer3"  multiple="multiple" data-placeholder="Looking For">
                                <option <?php if (in_array("Lawyer", $intern_lookingfor)) { echo 'selected';}?>>Lawyer</option>
                                <option <?php if (in_array("Law Firm", $intern_lookingfor)) { echo 'selected';}?>>Law Firm</option>
                                <option <?php if (in_array("Association", $intern_lookingfor)) { echo 'selected';}?>>Association</option>
                                <option <?php if (in_array("Counsel", $intern_lookingfor)) { echo 'selected';}?>>Counsel</option>
                                <option <?php if (in_array("Solicitor", $intern_lookingfor)) { echo 'selected';}?>>Solicitor</option>
                              </select>
                            </div>
                          </div>
                        </div>

                         <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Applying as:</label>
                            <div class="col-md-8">
                              <select  name="filter[intern_applyingas]" class="form-control" data-placeholder="Applying as" onchange="myFunctione(this.value)">
                              <option value="">---Select---</option>
                                <option value="Junior Advocate" <?php if ($intern_applyingas=='Junior Advocate') { echo 'selected';}?>>Junior Advocate</option>
                                <option value="Intern" <?php if ($intern_applyingas=='Intern') { echo 'selected';}?>>Intern</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        
                        <div  id="smeeting" style="display: <?php if ($intern_applyingas=='Intern') { echo 'display';} else { echo "none";}?>;"> 
                               <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">College :</label>
                            <div class="col-md-8">
                                <select name="filter[intern_college]" class="form-control" required="" value="">
            <option value="">Select</option>
            <option <?php if ($intern_college=='Amity Law School, Delhi (IP)') { echo 'selected';}?>>Amity Law School, Delhi (IP)</option>
            <option <?php if ($intern_college=='Banaras Hindu University, Varanasi') { echo 'selected';}?>>Banaras Hindu University, Varanasi</option>
            <option <?php if ($intern_college=='Christ University, Bangalore') { echo 'selected';}?>>Christ University, Bangalore</option>
            <option <?php if ($intern_college=='College Of Legal Studies University Of Petroleum And Energy Studies, Dehradun') { echo 'selected';}?>>College Of Legal Studies University Of Petroleum And Energy Studies, Dehradun</option>
            <option <?php if ($intern_college=='Dr Ram  Manohar Lohiya National Law University, Lucknow') { echo 'selected';}?>>Dr Ram  Manohar Lohiya National Law University, Lucknow</option>
            <option <?php if ($intern_college=='Faculty Of Law, University Of Delhi') { echo 'selected';}?>>Faculty Of Law, University Of Delhi</option>
            <option <?php if ($intern_college=='Government Law College, Mumbai') { echo 'selected';}?>>Government Law College, Mumbai</option>
            <option <?php if ($intern_college=='Gujarat  National Law University, Gandhinagar') { echo 'selected';}?>>Gujarat  National Law University, Gandhinagar</option>
            <option <?php if ($intern_college=='Hidyadullah National Law University, Raipur') { echo 'selected';}?>>Hidyadullah National Law University, Raipur</option>
            <option <?php if ($intern_college=='Indian Law Institute, New Delhi') { echo 'selected';}?>>Indian Law Institute, New Delhi</option>
            <option <?php if ($intern_college=='Indian Law Society Law College, Pune') { echo 'selected';}?>>Indian Law Society Law College, Pune</option>
            <option <?php if ($intern_college=='Jindal Law School ,Sonipat') { echo 'selected';}?>>Jindal Law School ,Sonipat</option>
            <option <?php if ($intern_college=='KITT School Of Law, Bhubaneshwar') { echo 'selected';}?>>KITT School Of Law, Bhubaneshwar</option>
            <option <?php if ($intern_college=='MS Ramaiah College Of Law') { echo 'selected';}?>>MS Ramaiah College Of Law</option> 
            <option <?php if ($intern_college=='NALSAR University Of Law, Hyderabad') { echo 'selected';}?>>NALSAR University Of Law, Hyderabad</option>
            <option <?php if ($intern_college=='National Law Institute University, Bhopal') { echo 'selected';}?>>National Law Institute University, Bhopal</option>
            <option <?php if ($intern_college=='Amity Law School, Delhi (IP)') { echo 'selected';}?>value="National Law School Of India University, Bangalore">National Law School Of India University, Bangalore</option>
            <option <?php if ($intern_college=='National Law University , Cuttack') { echo 'selected';}?>>National Law University , Cuttack</option>
            <option <?php if ($intern_college=='National Law University, Jodhpur') { echo 'selected';}?>>National Law University, Jodhpur</option>
            <option <?php if ($intern_college=='National Law University, New Delhi') { echo 'selected';}?>>National Law University, New Delhi</option>
            <option <?php if ($intern_college=='Nirma University, Ahmedabad') { echo 'selected';}?>>Nirma University, Ahmedabad</option>
            <option <?php if ($intern_college=='Rajiv  Gandhi National University Of Law, Patiala') { echo 'selected';}?>>Rajiv  Gandhi National University Of Law, Patiala</option>
            <option <?php if ($intern_college=='Rajiv  Gandhi School Of Intellectual Property Law, Kharagpur') { echo 'selected';}?>>Rajiv  Gandhi School Of Intellectual Property Law, Kharagpur</option>
            <option <?php if ($intern_college=='School of Law, SASTRA University') { echo 'selected';}?>>School of Law, SASTRA University</option>
            <option <?php if ($intern_college=='Symboisis Law School, Pune') { echo 'selected';}?>>Symboisis Law School, Pune</option> 
            <option <?php if ($intern_college=='WB National University Of Juridical Sciences, Kolkata') { echo 'selected';}?>>WB National University Of Juridical Sciences, Kolkata</option>
            <option value="Other" <?php if ($intern_college=='Other') { echo 'selected';}?>>Other</option>
                                </select>
                            </div>
                          </div>
                        </div>
                       
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Degree Of Study :</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="filter[intern_degree]" placeholder="Enter Degree" value="<?php echo $intern_degree;?>">
                            </div>
                          </div>
                        </div>
                        <div class="clearfix"></div> 
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Current Year Of study :</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="filter[intern_currentyear]" placeholder="Enter Current year" value="<?php echo $intern_currentyear;?>">
                            </div>
                          </div>
                        </div>
                        
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Expected year Of study :</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="filter[intern_expectedyear]" placeholder="Enter Expected year" value="<?php echo $intern_expectedyear;?>">
                            </div>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                        

                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">CGPA/Percentage :</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="filter[itern_cgpa]" placeholder="Enter CGPA" value="<?php echo $itern_cgpa;?>">
                            </div>
                          </div>
                        </div>
                        
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Previous Internship:</label>
                            <div class="col-md-8">
                              <select  name="filter[intern_preintership]" class="form-control" data-placeholder="previous intership">
                                <option value="Yes" <?php if ($intern_preintership=='Yes') { echo 'selected';}?>>Yes</option>
                                <option value="No" <?php if ($intern_preintership=='No') { echo 'selected';}?>>No</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        </div>
<!--  <div class="clearfix"></div> -->

                  <div  id="scall" style="display: <?php if ($intern_applyingas=='Junior Advocate') { echo 'display';} else { echo "none";}?>;"> 
                   <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Enrollment Number :</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="filter[intern_enrollnumber]" placeholder="Enter Enrollment Number" value="<?php echo $intern_enrollnumber;?>">
                            </div>
                          </div>
                  </div>


                   <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Date of Enrollment :</label>
                            <div class="col-md-8">
                                <input type="date" class="form-control" name="filter[intern_enrolldate]" placeholder="Enter Date of Enrollment" value="<?php echo $intern_enrolldate;?>">
                            </div>
                          </div>
                        </div>
                  </div>

                        <div class="clearfix"></div> 
                       <!--  <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Preferred duration of internship :</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="filter[intern_preferredduration]" placeholder="Enter best three publication title" value="<?php echo $intern_preferredduration;?>"> 
                            </div>
                          </div>
                        </div> -->
                       

                        <!-- <div class="col-md-6"> -->
                          <!-- <div class="form-group"> -->
                            <!-- <label class="col-md-4" for="">Preferred duration of intership :</label> -->
                            <!-- <div class="col-md-8"> -->
                                <!-- <input type="text" class="form-control" name="filter[intern_allpublication]" placeholder="Enter best three publication title" value="<?php echo $intern_allpublication;?>">  -->
                            <!-- </div> -->
                          <!-- </div> -->
                         <!--  <div class="col-md-6">
                          <div class="form-group">
                            <label class="col-md-4" for="">Have you had any experience of interning at any S&P office before ? :</label>
                            <div class="col-md-8">
                                <select name="filter[intern_experience]" class="form-control">
                                  <option <?php if ($intern_experience=='Yes') { echo 'selected';}?>>Yes</option>
                                <option <?php if ($intern_experience=='No') { echo 'selected';}?>>No</option>
                                </select> 
                            </div>
                          </div>
                          </div> -->
                        </div>
                        <div class="clearfix"></div>
                       
                        <br>
                         <!-- <div class="col-md-6"> -->
                         <div class="col-md-12">
                          <div class="form-group">
                            <input required class="styled-checkbox" id="styled-checkbox-2" type="checkbox" name="filter[confirmation]" value="Yes" <?php if($confirmation=='Yes') { echo 'checked';}?>>
                            <label for="styled-checkbox-2"> I hereby confirm that all the information provided by me is accurate and true.</label>
                          </div>
                        </div>
                        <!-- </div> -->
                        
                       <div class="clearfix"></div>
                          <hr>
                          <button type="submit" name="saveprofile" class="btn btn-dark">Submit</button>
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
    $(function() {
        $('#answer26').multipleSelect({
            width: '100%'
        });
    });
</script>


<script>
function myFunctione(i) 
{
  if(i=='Intern')
  {
    
    document.getElementById('smeeting').style.display = 'block';
    document.getElementById('scall').style.display = 'none';
  }
  else
  if(i=='Junior Advocate')
  { 
   
    document.getElementById('scall').style.display = 'block';
    document.getElementById('smeeting').style.display = 'none';
  }
  else
  {
    document.getElementById('smeeting').style.display = 'none';
    document.getElementById('scall').style.display = 'none';
  }
}
</script>