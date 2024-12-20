<?php 

include ('db_connection.php');

// echo date('d-m-Y H:i:s');

//SESSION EXPIRED AFTER 5 MIN CODE
if(isset($_SESSION['user_id'])){
    if(!isset($_SESSION['current_time'])){
        $_SESSION['current_time'] = date('His');
    }

    $previous_time = (int)$_SESSION['current_time'];
    $current_time = (int)date('His');

    if(($current_time - $previous_time) > 500){
        echo "<script>alert('Session expire, Please login again')</script>";
        unset($_SESSION['user_id']);
        session_destroy();
        echo "<script>window.location.href = 'index.php'</script>";
        exit();
    }
    else{
        $_SESSION['current_time'] = date('His');
    }
    // echo $_SESSION['current_time'];
} 

// LOGIN

if(isset($_POST['signin']))
{
    $username=mysqli_real_escape_string($conn,$_POST['user_email']);
    $password=mysqli_real_escape_string($conn,$_POST['user_password']);

if(isset($_POST['user_type']) && $_POST['user_type'] =='lawyer')
{
    $sql_query="SELECT loginStatus, email,lawyer_id,type,firm_id,lawyer_status,user_status,address,firm_name FROM tbl_lawyers WHERE (email = '".$username."' OR phone = '".$username."') AND password='".$password."'";
    $result = mysqli_query($conn,$sql_query);
    $row = mysqli_fetch_array($result);
    $count = mysqli_num_rows($result);
if($count==1)
{    
    if($row['loginStatus'] != 1){
        echo  '<script>alert("Your subscription is ended.")</script>';
    }
    else if($row['lawyer_status']=='InActive')
    {
        echo '<script>alert("Your account has been Deactivated. Please contact your Firm Admin");</script>'; 
    }
    else
    {

        if($row['user_status']=='Approved')
        {
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_id']    = $row['lawyer_id'];
            $_SESSION['user_type'] =  $row['type'];
            $_SESSION['user_firm_id'] =  $row['firm_id'];
            $_SESSION['user_adddr'] = $row['address'];
            $_SESSION['user_namee'] = $row['firm_name'];
          
        // $accnt_checkk = mysqli_query($conn,"SELECT address FROM tbl_lawyers WHERE lawyer_id= '".$_SESSION['user_id']."'");
        // $accnt_check_rowk = mysqli_fetch_array($accnt_checkk);
         
         if($row['address'] !='')
            {  
            echo  '<script>window.location="cases.php"</script>';
            }
         else
            {
            echo  '<script>window.location="account_details.php"</script>';
            }
        }
        else
        {
            echo '<script>alert("Your account is not yet activated");</script>';
        }

    }
}
    else
    {
      echo '<script>alert("Username/Password is invalid");</script>';
    }

}


if(isset($_POST['user_type']) && $_POST['user_type'] =='counselor')
{
    $sql_query="SELECT * FROM tbl_counsel WHERE (counsel_email = '".$username."' OR counsel_phone = '".$username."') AND counsel_password='".$password."'";
    $result = mysqli_query($conn,$sql_query);
    $row = mysqli_fetch_array($result);
    $count = mysqli_num_rows($result);
    if($count==1)
    {    

        $_SESSION['user_email'] = $row['counsel_email'];
        $_SESSION['user_id']    = $row['counsel_id'];
        $_SESSION['user_type'] = 'Counsel';
        
        echo  '<script>window.location="counselor_cases_detail.php"</script>';
    }
else
    {
    echo '<script>alert("Username/Password/Usertype is invalid");</script>';
    }
}

if(isset($_POST['user_type']) &&  $_POST['user_type'] =='intern')
{
    $sql_query="SELECT * FROM tbl_intern WHERE (intern_email = '".$username."' OR intern_phone = '".$username."') AND intern_password='".$password."'";
    $result = mysqli_query($conn,$sql_query);
    $row = mysqli_fetch_array($result);
    $count = mysqli_num_rows($result);
    if($count==1)
    {    

        $_SESSION['user_email'] = $row['intern_email'];
        $_SESSION['user_id']    = $row['intern_id'];
        $_SESSION['user_type'] = 'Intern';
        
        echo  '<script>window.location="internship_request.php"</script>';
    }
else
    {
    echo '<script>alert("Username/Password/Usertype is invalid");</script>';
    }
}


}



// REGISTRATION
if(isset($_POST['signup']))
{   
    if($_POST['reg_user_type'] =='lawyer' || $_POST['reg_user_type'] =='firm')
  {
    
    $check_mail = mysqli_query($conn,"SELECT email from tbl_lawyers WHERE email='".$_POST['user_email2']."'");
    $check_phone = mysqli_query($conn,"SELECT phone from tbl_lawyers WHERE phone ='".$_POST['user_number2']."'");

    if(mysqli_num_rows($check_mail) > 0) 
    {
       echo '<script>alert("This Email Id is already Registered");</script>';  
    }
    else if(mysqli_num_rows($check_phone) > 0){
        echo '<script>alert("This Phone number is already Registered");</script>';     
    }
    else if($_POST['enrol_number'] == "" && $_POST['reg_user_type'] =='lawyer'){
        echo '<script>alert("Provide enrollment number");</script>';
    }
    else if($_POST['firm_enrol_number'] == "" && $_POST['reg_user_type'] =='firm'){
        echo '<script>alert("Provide enrollment number");</script>';
    }
    else if($_POST['user_password1'] != $_POST['user_password2']){
       echo '<script>alert("Password does not match");</script>';  
    }
    else if($_POST['captcha_reg'] != $_SESSION['captcha_reg']){
            echo '<script>alert("Incorrect Captcha.");</script>';     
    }
    else
    {
            if($_POST['firm_type']=='Proprietor' || $_POST['firm_type']=='Partner' || $_POST['firm_type']=='Association' ){
                $enroll_number=$_POST['firm_enrol_number'];
            }elseif($_POST['firm_type']=='Company'){
                $enroll_number=$_POST['firm_reg_number'];
            }
            else{
                $enroll_number=$_POST['enrol_number'];
            }
    $sql_query22="INSERT INTO tbl_lawyers(firm_name,phone,email,password,user_status, quick_quote,compare_quote, type, availability,enroll_number,firm_type) VALUES('".$_POST['user_name2']."','".$_POST['user_number2']."','".$_POST['user_email2']."','".$_POST['user_password2']."','Approved','Yes','Yes','".$_POST['reg_user_type']."','No','".$enroll_number."','".$_POST['firm_type']."')";
    $result22 = mysqli_query($conn,$sql_query22);

    echo '<script>alert("Registered Successfully, please login now");</script>';
    }

  }

 if($_POST['reg_user_type'] =='counselor')
  { 
    $check_cmail = mysqli_query($conn,"SELECT counsel_email from tbl_counsel WHERE counsel_email='".$_POST['user_email2']."'");
    $check_cphone = mysqli_query($conn,"SELECT counsel_phone from tbl_counsel WHERE counsel_phone='".$_POST['user_number2']."'");

    if(mysqli_num_rows($check_cmail) > 0) 
    {
       echo '<script>alert("This Email Id is already Registered");</script>';  
    }
    else if(mysqli_num_rows($check_cphone) > 0){
        echo '<script>alert("This Phone number is already Registered");</script>';     
    }
    else if($_POST['enrol_number'] == ""){
        echo '<script>alert("Provide enrollment number");</script>';
    }
    else if($_POST['user_password1'] != $_POST['user_password2']){
       echo '<script>alert("Password does not match");</script>';  
    }
    else if($_POST['captcha_reg'] != $_SESSION['captcha_reg']){
            echo '<script>alert("Incorrect Captcha.");</script>';     
    }
    else
    {
    $sql_query22="INSERT INTO  tbl_counsel(counsel_name, counsel_phone , counsel_email, counsel_password, counsel_status, counsel_designation,enroll_number) VALUES('".$_POST['user_name2']."','".$_POST['user_number2']."','".$_POST['user_email2']."','".$_POST['user_password2']."','Approved', '".$_POST['counsel_designation']."','".$_POST['enrol_number']."')";
    $result22 = mysqli_query($conn,$sql_query22);

    echo '<script>alert("Registered Successfully, please login now");</script>';
    }
  }

  if($_POST['reg_user_type'] =='intern')
  { 
    $check_cmail = mysqli_query($conn,"SELECT intern_email from tbl_intern WHERE intern_email='".$_POST['user_email2']."'");
    if(mysqli_num_rows($check_cmail) > 0) 
    {
       echo '<script>alert("This Email Id is already Registered");</script>';  
    }
    else if($_POST['user_password1'] != $_POST['user_password2']){
       echo '<script>alert("Password does not match");</script>';  
    }
    else if($_POST['captcha_reg'] != $_SESSION['captcha_reg']){
            echo '<script>alert("Incorrect Captcha.");</script>';     
    }
    else
    {
    $sql_query22="INSERT INTO  tbl_intern(intern_name, intern_phone , intern_email, intern_password, intern_status) VALUES('".$_POST['user_name2']."','".$_POST['user_number2']."','".$_POST['user_email2']."','".$_POST['user_password2']."','Approved')";
    $result22 = mysqli_query($conn,$sql_query22);

    echo '<script>alert("Registered Successfully, please login now");</script>';
    }
  }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0" user-scalable="no">
<meta name="description" content="">
<meta name="keywords" content="">
<meta property="og:image" content="https://legumkart.com/assets/img/shareLogo.png" />
<!-- <meta name="author" content=""> -->

<title>Legal-India</title>

<!-- Favicon -->
<link href="img/favicon.png" rel="shortcut icon" type="image/png">
<!-- CSS -->
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/datepicker.css">
<link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css" />
<link rel="stylesheet" type="text/css" href="css/style.css?version=1.2">
<link rel="stylesheet" type="text/css" href="css/responsive.css">

<!-- Resource style -->
<link href="css/w3.css" rel="stylesheet">

<!-- <link rel="stylesheet" type="text/css" href="css/bootstrap-datetimepicker.min.css" /> -->
<link href="css/jquery.dataTables.min.css" rel="stylesheet" media="screen">
<link href="css/responsive.dataTables.min.css" rel="stylesheet" media="screen"> 


<!--  datatable search css -->
 <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
<!--  <link rel="stylesheet" href="css/multiple-select.css" />  -->

 <style type="text/css">
        .goog-te-banner-frame.skiptranslate {
    display: none !important;
    } 
body {
    top: 0px !important; 
    }
    
    </style>
</head>


<body>

<div id="google_translate_element" style="display: none"></div>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({ pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, autoDisplay: false }, 'google_translate_element');
        }
    </script>
    <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"
        type="text/javascript"></script>
    <script src="js/jquery-1.11.3.min.js"></script>
    <script>
        function translateLanguage(lang) {

            var $frame = $('.goog-te-menu-frame:first');
            if (!$frame.size()) {
                alert("Error: Could not find Google translate frame.");
                return false;
            }
            $frame.contents().find('.goog-te-menu2-item span.text:contains(' + lang + ')').get(0).click();
            return false;
        }
    </script>
    
<!-- <div class="wrapper bg-f8"> -->
<div class="wrapper">
<!-- <div id="google_translate_element"></div> -->
<!-- <div class="preloader"></div> -->
<!-- pop form -->
<div class="zind modal fade" id="login_form">
    <div class="modal-dialog">
        <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
            <div class="modal-header">
                <h3><i class="pe-7s-users"></i> Lawyer/Firm Login</h3>
            </div>
            <div class="modal-body">
                <form class="form" role="form" method="post" action="">
                    <div class="user_type" id="create-account-user">
                        <div class="radio_btn">
                            <input type="radio" id="account-type-lawyer" name="user_type" value="lawyer" required checked>
                            <label for="account-type-lawyer">Lawyer/Law Firm</label>
                        </div>
                        <div class="radio_btn">
                            <input type="radio" id="account-type-counselor" name="user_type" value="counselor" required>
                            <label for="account-type-counselor">Counsel</label>
                        </div>
                        <div class="radio_btn">
                            <input type="radio" id="account-type-intern" name="user_type" value="intern" required>
                            <label for="account-type-intern">Law Intern</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="email" placeholder="Email address or Mobile number" name="user_email" required="">
                    </div>
                    <div class="form-group">
                         <input type="password" class="form-control" id="password" placeholder="Password" name="user_password" required="">
                         <div class="help-block text-right">
                            <a href="forgot_password.php" id="remember">Forgot password?</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-simple" name="signin">Log In</button>
                    </div>
                    <p class="text-center">Don't have an account? <a href="#" data-toggle="modal" data-target="#registration_form" data-dismiss="modal">Sign up here!</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="zind modal fade" id="registration_form">
    <div class="modal-dialog">
        <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
            <div class="modal-header">
                <h3><i class="pe-7s-note2"></i> Registration</h3>
            </div>
            <div class="modal-body">
                <form class="form" role="form" method="post" action="" id="register-account">
                    <div class="user_type" id="create-account-user">
                        <div class="radio_btn">
                            <input type="radio" id="account-lawyer" name="reg_user_type" value="lawyer" onclick="javascript:yesnoCheck();" checked="">
                            <label for="account-lawyer">Lawyer</label>
                        </div>
                        <div class="radio_btn">
                            <input type="radio" id="account-firm" name="reg_user_type" value="firm" onclick="javascript:yesnoCheck();">
                            <label for="account-firm">Law Firm</label>
                        </div>
                        <div class="radio_btn">
                            <input type="radio" id="account-counselor" name="reg_user_type" value="counselor" onclick="javascript:yesnoCheck();">
                            <label for="account-counselor">Counsel</label>
                        </div>
                        <div class="radio_btn">
                            <input type="radio" id="account-intern" name="reg_user_type" value="intern" onclick="javascript:yesnoCheck();">
                            <label for="account-intern">Law Intern</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Name" name="user_name2" required="">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" pattern="[1-9]{1}[0-9]{9}" title="Enter 10 digit mobile number" placeholder="Mobile Number" name="user_number2" required="">
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Email Id" name="user_email2">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" name="user_password1" required="">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Confirm Password" name="user_password2" required="">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Enrollment Number" name="enrol_number"  id="enrol_number">
                    </div>
                    <div class="form-group">
                        <select name="firm_type" class="form-control" id="firm_type"  style="display:none" onchange="saveStatus(this.value)">
                        <option value=""><label>---Select Firm Type---</label></option> 
                        <option>Proprietor</option>    
                        <option>Partner</option>
                        <option>Association</option>    
                        <option>Company</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Enrollment Number" name="firm_enrol_number"  id="firm_enrol_number" style="display:none">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Registration Number" name="firm_reg_number"  id="firm_reg_number" style="display:none">
                    </div>
                    <div class="form-group">
                        <select name="lawyer_designation" class="form-control" id="firm_desig"  style="display:none">
                        <option value=""><label>---Select Designation---</label></option> 
                        <option>Solicitor</option>    
                        <option>Lawyers</option>
                        </select>
                    </div> 

                    <div class="form-group">
                        <select name="counsel_designation" class="form-control" id="counsel_desig"  style="display:none">
                        <option value=""><label>---Select Designation---</label></option> 
                        <option value="Counsel">Counsel</option>    
                        <option value="Designated Senior Counsel">Designated Senior Counsel</option>
                        </select>
                    </div> 
                        
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Enter Captcha" name="captcha_reg" required autocomplete="off">
                        <!-- Style.css added a class captcha_reg, will adding the captcha please add the captcha-->
                        <img class="captcha_reg" src="captcha_reg.php"/>
                        <button name="submit" type="button" class="btnRefresh" onClick="refreshCaptcha();" title="Refresh Captcha"><i class="fa fa-refresh"></i></button>
                        <span class="captcha-info info"></span>
                    </div>
                    
                    <div class="form-group">
                        <input class="styled-checkbox" id="styled-checkbox-11" type="checkbox" name="compare" value="yes" required>
                        <label for="styled-checkbox-11"><a href="#lawdisclaimer" id="termcondition" data-toggle="modal">Accept terms and conditions</a></label>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-simple" name="signup">Register</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- laywer -->
<div class="zind modal fade" id="lawdisclaimer">
    <div class="modal-dialog">
        <div class="modal-content">
        <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
        <div class="modal-header">
                <h3><!-- <i class="pe-7s-note2"></i> -->Disclaimer</h3>
            </div>
            <div class="modal-body">
                <!-- Law disclaimer -->
                Neither your receipt of information from this website, nor your use of this website. The content of this website is intended to convey general information. You should not send us any confidential information in response to this webpage. Such responses will not create a lawyer-client relationship, and whatever you disclose to us will not be privileged or confidential unless we have agreed to act as your legal counsel and you have executed a written engagement agreement.
            </div>
        </div>
    </div>
</div>

<div class="zind modal fade" id="firmdisclaimer">
    <div class="modal-dialog">
        <div class="modal-content">
        <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
        <div class="modal-header">
                <h3><!-- <i class="pe-7s-note2"></i> -->Disclaimer</h3>
            </div>
            <div class="modal-body">
                <!-- Firm disclaimer -->
                Neither your receipt of information from this website, nor your use of this website. The content of this website is intended to convey general information. You should not send us any confidential information in response to this webpage. Such responses will not create a lawyer-client relationship, and whatever you disclose to us will not be privileged or confidential unless we have agreed to act as your legal counsel and you have executed a written engagement agreement.
            </div>
        </div>
    </div>
</div>

<div class="zind modal fade" id="counseldisclaimer">
    <div class="modal-dialog">
        <div class="modal-content">
        <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
        <div class="modal-header">
                <h3><!-- <i class="pe-7s-note2"></i> -->Disclaimer</h3>
            </div>
            <div class="modal-body">
                <!-- Counsel disclaimer -->
                Neither your receipt of information from this website, nor your use of this website. The content of this website is intended to convey general information. You should not send us any confidential information in response to this webpage. Such responses will not create a lawyer-client relationship, and whatever you disclose to us will not be privileged or confidential unless we have agreed to act as your legal counsel and you have executed a written engagement agreement.
            </div>
        </div>
    </div>
</div>

<div class="zind modal fade" id="interndisclaimer">
    <div class="modal-dialog">
        <div class="modal-content">
        <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
        <div class="modal-header">
                <h3><!-- <i class="pe-7s-note2"></i> -->Disclaimer</h3>
            </div>
            <div class="modal-body">
                <!-- Intern disclaimer -->
                Neither your receipt of information from this website, nor your use of this website. The content of this website is intended to convey general information. You should not send us any confidential information in response to this webpage. Such responses will not create a lawyer-client relationship, and whatever you disclose to us will not be privileged or confidential unless we have agreed to act as your legal counsel and you have executed a written engagement agreement.
            </div>
        </div>
    </div>
</div>

<?php  if(!isset($_SESSION['user_id']))
   { ?>
   <div class="main-navbar">  
 <?php  } else {
?>


<div class="main-navbar login_navbar">
<?php } ?>
    <div class="container-fluid padding-none">
        <div class="row">
            <div class="col-xs-12">
                <div class="top_content">
                    <div class="right_info">
                        <?php if(isset($_SESSION['user_type']))
                              { 

                            if($_SESSION['user_type']=='Counsel')
                                {

                                $counsel_name=mysqli_query($conn,"SELECT counsel_name from tbl_counsel WHERE counsel_id='".$_SESSION['user_id']."'"); 
                                $counsell_row=mysqli_fetch_assoc($counsel_name);

                                    ?>
                            <p>Welcome: <a href="counselor_cases_detail.php"><b><?php echo $counsell_row['counsel_name'];?></b></a></p>   
                            <?php }elseif ($_SESSION['user_type']=='Intern') {
                                $intern_name=mysqli_query($conn,"SELECT intern_name from tbl_intern WHERE intern_id='".$_SESSION['user_id']."'"); 
                                 $intern_row=mysqli_fetch_assoc($intern_name);

                            ?>
                            <p>Welcome: <a href="internship_request.php"><b><?php echo $intern_row['intern_name'];?></b></a></p>   
                            <?php  }                          
                                else 
                                { 
                                
                                // $lawyer_name=mysqli_query($conn,"SELECT firm_name from tbl_lawyers WHERE lawyer_id='".$_SESSION['user_id']."'"); 
                                // $lawyerr_row=mysqli_fetch_assoc($lawyer_name);
                                    ?>
                            <p>Welcome: <a href="<?php if($_SESSION['user_adddr'] !='') { echo 'cases.php'; } else { echo 'account_details.php'; } ?>"><b><?php echo $_SESSION['user_namee'];?></b></a></p>
                             <?php } ?>
                            <ul class="link_btn">
                 <?php 
                   if(isset($_SESSION['user_type']) && $_SESSION['user_type'] =='lawyer' || $_SESSION['user_type'] =='firm'){
        // *** QUERY TO SHOW COUNT OF NO. OF CASES WHOSE NEXT DATE IS NOT ADDED *****
        //  if($_SESSION['user_type']=='firm')
        //     {
        //      $lawyerqry = mysqli_query($conn,"SELECT t1.case_id,t1.next_date, t1.stage FROM tbl_cases t1,  tbl_lawyers t2 WHERE (t1.lawyer_id=t2.lawyer_id AND t2.firm_id='".$_SESSION['user_id']."') OR (t1.lawyer_id=t2.lawyer_id AND  t1.lawyer_id='".$_SESSION['user_id']."')");
        //     }
        //     else
        //      {
        //          if(isset($_SESSION['user_firm_id']) && $_SESSION['user_firm_id'] == 0)
        //           {
        //             $lawyerqry = mysqli_query($conn,"SELECT t1.case_id,t1.next_date, t1.stage FROM tbl_cases t1, tbl_lawyers t2  WHERE t1.lawyer_id=t2.lawyer_id AND (t1.lawyer_id='".$_SESSION['user_id']."')");
        //           }
        //           if(isset($_SESSION['user_firm_id']) && $_SESSION['user_firm_id'] != 0)
        //           {
        //             $lawyerqry = mysqli_query($conn,"SELECT t1.case_id,t1.next_date, t1.stage FROM tbl_cases t1, tbl_lawyers t2  WHERE t1.lawyer_id=t2.lawyer_id AND t1.lawyer_id in (SELECT lawyer_id FROM tbl_lawyers WHERE lawyer_id = '".$_SESSION['user_firm_id']."' OR firm_id = '".$_SESSION['user_firm_id']."')");
        //           }
        //       } 
        //     // else
        //     // {
        //     //  $lawyerqry = mysqli_query($conn,"SELECT t1.case_id,t1.next_date, t1.stage FROM tbl_cases t1, tbl_lawyers t2  WHERE t1.lawyer_id=t2.lawyer_id AND (t1.lawyer_id='".$_SESSION['user_id']."' OR t1.lawyer_id='".$_SESSION['user_firm_id']."')");
        //     // }
        //     $count_unadded_nxt_dt=0;
        //     while($lawyerrow=mysqli_fetch_assoc($lawyerqry))
        //     { 
        //     $caseqry = mysqli_query($conn,"SELECT nextdt_id,next_case_date as MaxDate, next_stage, prev_case_date as PrevMaxDate FROM tbl_case_nextdt  WHERE next_case_id='".$lawyerrow['case_id']."' ORDER BY next_case_date DESC LIMIT 1"); 
        //     $caserow=mysqli_fetch_assoc($caseqry);

        //     if($caserow['MaxDate'] !='' AND $caserow['MaxDate'] < date('Y-m-d') && $caserow['next_stage'] != "Dismissed/Disposed"  && $caserow['next_stage'] != "Disposed" && $caserow['next_stage'] != "Disposed" && $caserow['next_stage'] != "NOC" && $caserow['next_stage'] != "Dismissal" && $caserow['next_stage'] != "Withdrwan" && $caserow['next_stage'] != "Decree") 
        //         { 
        //             $count_unadded_nxt_dt=$count_unadded_nxt_dt+1;
        //         } 
        //     elseif($caserow['MaxDate'] =='')
        //       {
        //         if($lawyerrow['next_date'] < date('Y-m-d') && $lawyerrow['stage'] != "Dismissed/Disposed" && $lawyerrow['stage'] != "Disposed" && $lawyerrow['stage'] != "Dismissed" && $lawyerrow['stage'] != "Dismissal" && $lawyerrow['stage'] != "NOC" && $lawyerrow['stage'] != "Withdrwan"  && $lawyerrow['stage'] != "Decree") 
        //         { 
        //             $count_unadded_nxt_dt=$count_unadded_nxt_dt+1; 
        //         }

        //       } 
        //     }
        
        
        $next_case_date = [];
        $next_stage = [];
        $next_judge = [];
        $prev_case_date = [];
        $next_lawyer_id = [];
        $next_date_id = [];
        $total_count = 0;
        $lawyer_id = "";
      
        // Get Number of Lawyer ID - Start
        if($_SESSION['user_type']=='firm'){
          $qry_firm = "SELECT lawyer_id FROM tbl_lawyers WHERE firm_id = '".$_SESSION['user_id']."' OR lawyer_id = '".$_SESSION['user_id']."'";
          $select_qry = mysqli_query($conn, $qry_firm);
          while ($select_result = mysqli_fetch_assoc($select_qry)) {
            $lawyer_id .= $select_result['lawyer_id'].",";
          }
          $lawyer_id = chop($lawyer_id, ",");
        }
        else if($_SESSION['user_type'] == 'lawyer' && $_SESSION['user_firm_id'] != 0){
          $qry_firm = "SELECT lawyer_id FROM tbl_lawyers WHERE firm_id = '".$_SESSION['user_firm_id']."' OR lawyer_id = '".$_SESSION['user_firm_id']."'";
          $select_qry = mysqli_query($conn, $qry_firm);
          while ($select_result = mysqli_fetch_assoc($select_qry)) {
            $lawyer_id .= $select_result['lawyer_id'].",";
          }
          $lawyer_id = chop($lawyer_id, ",");
        }
        else{
          $lawyer_id = $_SESSION['user_id'];
        }
        // Get Number of Lawyer ID - End


        //Get all cases from given lawyer id under nextdate_table - start
        $nextdate_case_id = mysqli_query($conn,"SELECT MAX(nextdt_id) as nextdt_id,next_case_id FROM tbl_case_nextdt WHERE lawyer_id IN (".$lawyer_id.")  GROUP BY next_case_id");
        $next_date_id = "";
        $next_case_id2 = "";
        while ($nextdate_case_id_result = mysqli_fetch_assoc($nextdate_case_id)) {
          $next_date_id .= $nextdate_case_id_result['nextdt_id'].",";
          $next_case_id2 .= $nextdate_case_id_result['next_case_id'].",";
        }
        $next_date_id = chop($next_date_id, ",");
        $next_case_id2 = chop($next_case_id2, ",");
        //Get all cases from given lawyer id under nextdate_table - start
        
        if($next_date_id == ""){
            $next_date_value_condition = "";
        }
        else{
            $next_date_value_condition = " AND nextdt_id IN (".$next_date_id.")";
        }
        
        if($next_case_id2 == ""){
            $next_date_value_condition2 = "";
        }
        else{
            $next_date_value_condition2 = " AND case_id NOT IN (".$next_case_id2.")";
        }

        $last_record = mysqli_query($conn,"SELECT next_case_id FROM tbl_case_nextdt WHERE next_case_date < '".date('Y-m-d')."'".$next_date_value_condition." AND (next_stage NOT LIKE '%Dismissed%' AND next_stage NOT LIKE '%Disposed%' AND next_stage NOT LIKE '%NOC%' AND next_stage NOT LIKE '%Withdrwan%' AND next_stage NOT LIKE '%Dismissal%' AND next_stage NOT LIKE '%Decree%') AND lawyer_id IN (".$lawyer_id.")");

        $last_record_id = "";
        while ($last_record_result = mysqli_fetch_assoc($last_record)) {
          $last_record_id .= $last_record_result['next_case_id'].",";
        }
        $last_record_id = chop($last_record_id, ",");

        if($last_record_id == ""){
            $next_date_value_condition3 = "";
        }
        else{
            $next_date_value_condition3 = "case_id IN (".$last_record_id.") OR";
        }

        $cases_list = mysqli_query($conn,"SELECT count(case_id) as cnt_cases FROM tbl_cases WHERE (".$next_date_value_condition3." case_id IN (SELECT case_id FROM tbl_cases WHERE next_date < '".date("Y-m-d")."'".$next_date_value_condition2." AND lawyer_id IN (".$lawyer_id.")))  AND lawyer_id IN (".$lawyer_id.") AND (stage NOT LIKE '%Dismissed%' AND stage NOT LIKE '%Disposed%' AND stage NOT LIKE '%NOC%' AND stage NOT LIKE '%Withdrwan%' AND stage NOT LIKE '%Dismissal%' AND stage NOT LIKE '%Decree%') ORDER BY orderby_date DESC");

        $cases_list = mysqli_fetch_assoc($cases_list);
        $count_unadded_nxt_dt = $cases_list['cnt_cases'];
          
// **** QUERY TO SHOW COUNT NO. OF CASES WHOSE NEXT DATE IS NOT ADDED ENDS ****                 
                    ?>                    
                            <li><a href="cases_without_nextdate.php">Add next-dates <span class="label label-danger total_count_data"><?php echo $count_unadded_nxt_dt;?></span></a></li>
                    <?php } ?>        
                                <li><a href="logout.php"><i class="pe-7s-users"></i> Logout</a></li>
                            </ul>
                        <?php   } else { ?>

                        <!-- <p>Free Legal Advice &nbsp; | &nbsp; 10% Discount on First Bill</p> -->
                        <ul class="nav navbar-nav language">
                            <!-- <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">My LawMap <i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#login_form" data-toggle="modal"><i class="pe-7s-users"></i> Login</a></li>
                                    <li><a href="#registration_form" data-toggle="modal"><i class="pe-7s-pen"></i> Registration</a></li>
                                </ul>
                            </li> -->
                            <li class="dropdown"><a href="#login_form" data-toggle="modal" class="btn"><i class="pe-7s-users"></i> Login</a></li>
                        </ul>
                        <?php } ?>
                        <ul class="nav navbar-nav navbar-right language">
                        <select name="tech" class="dropdown-toggle" id="tech" onchange="translateLanguage(this.value);">
                            <option  value="English" id="english">English</option>
                            <option  value="Gujarati" id="gujarati">Gujarati</option>
                            <option  value="Hindi" id="hindi">Hindi</option>
                            <option  value="Marathi" id="marathi">Marathi</option>
                        </select>
                      </ul>
                    </div>
                </div>
                <nav class="navbar navbar-default style-1">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="<?php
                            if(isset($_SESSION['user_type'])){
                                if($_SESSION['user_type'] == "Counsel"){
                                    echo "counselor_cases.php";
                                }
                                else if($_SESSION['user_type'] == "Intern"){
                                    echo "internship_request.php";
                                }
                                else if($_SESSION['user_type'] == "firm"){
                                    echo "cases.php";
                                }
                                else if($_SESSION['user_type'] == "lawyer"){
                                    echo "cases.php";
                                }

                            }
                            else{
                                echo "index.php";
                            }
                            ?>">
                            <img src="img/logo-2.png" alt="legal">
                        </a>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" data-hover="dropdown" data-animations="fadeInUp">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="<?php
                            if(isset($_SESSION['user_type'])){
                                if($_SESSION['user_type'] == "Counsel"){
                                    echo "counselor_cases.php";
                                }
                                else if($_SESSION['user_type'] == "Intern"){
                                    echo "internship_request.php";
                                }
                                else if($_SESSION['user_type'] == "firm"){
                                    echo "cases.php";
                                }
                                else if($_SESSION['user_type'] == "lawyer"){
                                    echo "cases.php";
                                }

                            }
                            else{
                                echo "index.php";
                            }
                            ?>"><i class="pe-7s-home"></i></a></li>
                            <!-- <li><a href="causelist.php">Causelist</a></li>
                            <li><a href="causelist2.php">Causelist 2</a></li> -->

                            <?php
        
                                if(!isset($_SESSION['user_type']) || !isset($_SESSION['user_type']) || !isset($_SESSION['user_type']) || !isset($_SESSION['user_type'])){
                            ?>
                            <li><a href="quick_quote.php">Legal Query</a></li>
                            <li><a href="compare_quote.php">Compare Legal Query</a></li>
                            <li><a href="interactions.php">Interactions</a></li>
                            <li><a href="call.php">Talk to lawyers</a></li>
                            <?php
                                }
                            ?>
                            <!-- <li><a href="#login_form" data-toggle="modal"><i class="pe-7s-users"></i> Login</a></li> -->
                            <!-- <li><a href="internship.php">Internships</a></li> -->
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>

<?php if(!isset($_SESSION['user_id']))
   {
?>
<!-- <div class="news_updates">
        <div class="title">
            <h4><i class="pe-7s-news-paper"></i> News Of The Day</h4>
        </div>
        <div class="sidebar-content about">
            <div class="latest-news">
                <div class="item">
                    <p>This briefing outlines the views of the Law Society in relation to the Financial Guidance and Claims Bill.</p>
                </div>
                <div class="item">
                    <p>A new law to prevent the criminal facilitation of tax evasion has prompted the Law Society to unveil new guidance for solicitors.</p>
                </div>
            </div>
        </div>
</div> -->

<?php } ?>
<div class="clearfix"></div>

<script type="text/javascript">

function yesnoCheck() 
{
    
    if (document.getElementById('account-firm').checked) 
    {
        document.getElementById('enrol_number').style.display = 'none';
        document.getElementById('firm_type').style.display = 'block';
        document.getElementById('firm_desig').style.display = 'block';
        document.getElementById('firm_enrol_number').style.display = 'block';
        document.getElementById('counsel_desig').style.display = 'none';
        $("#termcondition").attr("href","#firmdisclaimer");
    }
    
    if (document.getElementById('account-counselor').checked) 
    {   
        document.getElementById('firm_reg_number').style.display = 'none';
        document.getElementById('enrol_number').style.display = 'block';
        document.getElementById('firm_type').style.display = 'none';
        document.getElementById('firm_desig').style.display = 'none';
        document.getElementById('firm_enrol_number').style.display = 'none';
        document.getElementById('counsel_desig').style.display = 'block';
        $("#termcondition").attr("href","#counseldisclaimer");
    }

    if (document.getElementById('account-lawyer').checked) 
    {   
        document.getElementById('firm_reg_number').style.display = 'none';
        document.getElementById('enrol_number').style.display = 'block';
        document.getElementById('firm_type').style.display = 'none';
        document.getElementById('firm_desig').style.display = 'none';
        document.getElementById('firm_enrol_number').style.display = 'none';
        document.getElementById('counsel_desig').style.display = 'none';
        $("#termcondition").attr("href","#lawdisclaimer");
    }

    if (document.getElementById('account-intern').checked) 
    {   
        document.getElementById('firm_reg_number').style.display = 'none';
        document.getElementById('enrol_number').style.display = 'none';
        document.getElementById('firm_type').style.display = 'none';
        document.getElementById('firm_desig').style.display = 'none';
        document.getElementById('firm_enrol_number').style.display = 'none';
        document.getElementById('counsel_desig').style.display = 'none';
        $("#termcondition").attr("href","#interndisclaimer");
    }
}

</script>

<script type="text/javascript">
 function saveStatus(editableObj) 
 {  
//   alert(editableObj);
   if (editableObj == 'Proprietor' || editableObj == 'Partner' || editableObj == 'Association') {
       document.getElementById('firm_enrol_number').style.display = 'block';
       document.getElementById('firm_reg_number').style.display = 'none';
          if (editableObj == 'Partner' || editableObj == 'Association') {
              $("#firm_enrol_number").attr("placeholder", "Enrollment No. Of any one Partner");
          }else{
             $("#firm_enrol_number").attr("placeholder", "Enrollment No.");
          }
    } else {
        document.getElementById('firm_reg_number').style.display = 'block';
        document.getElementById('firm_enrol_number').style.display = 'none';
    }

}
</script>

<script type="text/javascript">
    function refreshCaptcha() 
   {
     $(".captcha_reg").attr('src','captcha_reg.php');
   }
</script>