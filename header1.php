<?php 
include ('db_connection.php');

if(isset($_POST['signin']))
{
    $username=mysqli_real_escape_string($conn,$_POST['user_email']);
    $password=mysqli_real_escape_string($conn,$_POST['user_password']);

if($_POST['user_type'] =='lawyer')
{
    $sql_query="SELECT * FROM tbl_lawyers WHERE email = '".$username."' AND password='".$password."'";
    $result = mysqli_query($conn,$sql_query);
    $row = mysqli_fetch_array($result);
    $count = mysqli_num_rows($result);
if($count==1)
{    
    if($row['lawyer_status']=='InActive')
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
            
            echo  '<script>window.location="cases.php"</script>';
           
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


if($_POST['user_type'] =='counselor')
{
    $sql_query="SELECT * FROM tbl_counsel WHERE counsel_email = '".$username."' AND counsel_password='".$password."'";
    $result = mysqli_query($conn,$sql_query);
    $row = mysqli_fetch_array($result);
    $count = mysqli_num_rows($result);
    if($count==1)
    {    

        $_SESSION['user_email'] = $row['counsel_email'];
        $_SESSION['user_id']    = $row['counsel_id'];
        $_SESSION['user_type'] = 'Counsel';
        
        echo  '<script>window.location="counselor_cases.php"</script>';
    }
else
    {
    echo '<script>alert("Username/Password/Usertype is invalid");</script>';
    }
}

}

if(isset($_POST['signup']))
{   
    if($_POST['reg_user_type'] =='lawyer' || $_POST['reg_user_type'] =='firm')
  {
    

    $check_mail = mysqli_query($conn,"SELECT email from tbl_lawyers WHERE email='".$_POST['user_email2']."'");
    if(mysqli_num_rows($check_mail) > 0) 
    {
       echo '<script>alert("This Email Id is already Registered");</script>';  
    }
    else
    {
    $sql_query22="INSERT INTO tbl_lawyers(firm_name,phone,email,password,user_status, quick_quote,compare_quote, type, availability) VALUES('".$_POST['user_name2']."','".$_POST['user_number2']."','".$_POST['user_email2']."','".$_POST['user_password2']."','Approved','Yes','Yes','".$_POST['reg_user_type']."','No')";
    $result22 = mysqli_query($conn,$sql_query22);

    echo '<script>alert("Registered Successfully, please login now");</script>';
    }

  }

 if($_POST['reg_user_type'] =='counselor')
  { 
    $check_cmail = mysqli_query($conn,"SELECT counsel_email from tbl_counsel WHERE counsel_email='".$_POST['user_email2']."'");
    if(mysqli_num_rows($check_cmail) > 0) 
    {
       echo '<script>alert("This Email Id is already Registered");</script>';  
    }
    else
    {
    $sql_query22="INSERT INTO  tbl_counsel(counsel_name, counsel_phone , counsel_email, counsel_password, counsel_status) VALUES('".$_POST['user_name2']."','".$_POST['user_number2']."','".$_POST['user_email2']."','".$_POST['user_password2']."','Approved')";
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
<meta name="author" content="">

<title>Legal-India</title>

<!-- CSS -->
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/style1.css">
<link rel="stylesheet" type="text/css" href="css/responsive.css">

<!-- Resource style -->
<link href="css/w3.css" rel="stylesheet">
<!-- Favicon -->
<link href="img/favicon.png" rel="shortcut icon" type="image/png">
<link rel="stylesheet" type="text/css" href="css/datepicker.css">
<link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap-datetimepicker.min.css" />
<link href="css/jquery.dataTables.min.css" rel="stylesheet" media="screen">
<link href="css/responsive.dataTables.min.css" rel="stylesheet" media="screen"> 
<!-- Download this file and put in css folder for datatable search css -->
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
    <script src="http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"
        type="text/javascript"></script>
    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
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
                            <input type="radio" id="account-type-lawyer" name="user_type" value="lawyer" required>
                            <label for="account-type-lawyer">Lawyer/Law Firm</label>
                        </div>
                        <div class="radio_btn">
                            <input type="radio" id="account-type-counselor" name="user_type" value="counselor" required>
                            <label for="account-type-counselor">Counselor</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" id="email" placeholder="Email address" name="user_email" required="">
                    </div>
                    <div class="form-group">
                         <input type="password" class="form-control" id="password" placeholder="Password" name="user_password" required="">
                         <div class="help-block text-right">
                            <a href="#" id="remember">Forgot password?</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-simple" name="signin">Log In</button>
                    </div>
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
                            <label for="account-counselor">Counselor</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Name" name="user_name2" required="">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" pattern="[1-9]{1}[0-9]{9}" title="Enter 10 digit mobile number" placeholder="Mobile Number" name="user_number2" required="">
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Email Id" name="user_email2" required="">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" name="user_password2" required="">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Confirm Password" name="user_password2" required="">
                    </div>
                    <div class="form-group">
                        <select name="designation" class="form-control" id="firm_desig"  style="display:none">
                        <option value=""><label>---Select Designation---</label></option> 
                        <option><label>Solicitor</label></option>    
                        <option><label>Lawyers</label></option>
                        </select>
                    </div> 

                    <div class="form-group">
                        <select name="designation" class="form-control" id="counsel_desig"  style="display:none">
                        <option value=""><label>---Select Designation---</label></option> 
                        <option><label>Counsel</label></option>    
                        <option><label>Designated Senior Counsel</label></option>
                        </select>
                    </div> 

                    <div class="form-group">
                        <button type="submit" class="btn btn-simple" name="signup">Register</button>
                    </div>
                </form>
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
            <div class="col-md-12">
                <div class="top_content">
                    <div class="right_info">
                        <?php if(isset($_SESSION['user_type']))
                              { 

                            if($_SESSION['user_type']=='Counsel')
                                {

                                $counsel_name=mysqli_query($conn,"SELECT counsel_name from tbl_counsel WHERE counsel_id='".$_SESSION['user_id']."'"); 
                                $counsell_row=mysqli_fetch_assoc($counsel_name);

                                    ?>
                            <p>Welcome: <a href="counselor_cases.php"><b><?php echo $counsell_row['counsel_name'];?></b></a></p>   
                            <?php    }
                                else 
                                { 
                                
                                $lawyer_name=mysqli_query($conn,"SELECT firm_name from tbl_lawyers WHERE lawyer_id='".$_SESSION['user_id']."'"); 
                                $lawyerr_row=mysqli_fetch_assoc($lawyer_name);
                                    ?>
                            <p>Welcome: <a href="cases.php"><b><?php echo $lawyerr_row['firm_name'];?></b></a></p>
                             <?php } ?>
                            <ul class="link_btn">
                                <li><a href="logout.php"><i class="pe-7s-users"></i> Logout</a></li>
                            </ul>
                        <?php   } else { ?>
                        <!-- <p>Free Legal Advice &nbsp; | &nbsp; 10% Discount on First Bill</p> -->
                        <ul class="nav navbar-nav language">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">My LawMap <i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#login_form" data-toggle="modal"><i class="pe-7s-users"></i> Login</a></li>
                                    <li><a href="#registration_form" data-toggle="modal"><i class="pe-7s-pen"></i> Registration</a></li>
                                </ul>
                            </li>
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
                        <a class="navbar-brand" href="index.php">
                            <img src="img/logo-2.png" alt="legal">
                        </a>
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" data-hover="dropdown" data-animations="fadeInUp">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="index.php"><i class="pe-7s-home"></i></a></li>
                            <li><a href="causelist.php">Causelist</a></li>
                            <li><a href="causelist2.php">Causelist 2</a></li>
                            <li><a href="quick_quote.php">Legal Query</a></li>
                            <li><a href="compare_quote.php">Compare Legal Query</a></li>
                            <li><a href="interactions.php">Interactions</a></li>
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
<div class="news_updates">
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
</div>
<?php } ?>
<div class="clearfix"></div>

<script type="text/javascript">

function yesnoCheck() 
{
    
    if (document.getElementById('account-firm').checked) 
    {
        document.getElementById('firm_desig').style.display = 'block';
        document.getElementById('counsel_desig').style.display = 'none';
    }
    
    if (document.getElementById('account-counselor').checked) 
    {   
        document.getElementById('firm_desig').style.display = 'none';
        document.getElementById('counsel_desig').style.display = 'block';
    }

    if (document.getElementById('account-lawyer').checked) 
    {   
        document.getElementById('firm_desig').style.display = 'none';
        document.getElementById('counsel_desig').style.display = 'none';
    }
}
</script>

