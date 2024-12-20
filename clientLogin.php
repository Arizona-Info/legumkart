<?php 
   include("header.php"); 
?>

<div class="background-image">
  <div class="bg_overlay"></div>
  <div class="carousel-inner">
    <div class="item active" style="background-image:url(img/bg/banner-2.jpg)"></div>
  </div>
</div>

<section class="inner-bg over-layer-white" style="background-image: url('img/bg/4.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="mini-title">
                    <h3>Client Login</h3>
                    <p><a href="index.php">Home</a> <span class="fa fa-angle-right"></span> Client Login</p>
                </div>
            </div>
        </div>
    </div>
</section>
<div style="margin: 50px"></div>

<div class="col-md-1"></div>
<div class="col-md-5">
	<div class="container">
	    <div class="row">
	        <div class="comapre_search search_lawyer_2">
	            <div class="title col-xs-12">
	              <h4 class="text-center">Client Register</h4>
	            </div>
	        </div>
	    </div>
	</div>
	<form class="form" role="form" method="post" action="" autocomplete="off">
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Enter your Name" name="name" id="regClientName" required="">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" pattern="[1-9]{1}[0-9]{9}" title="Enter 10 digit mobile number" placeholder="Enter your mobile number" name="mobile" id="regClientMobile" required="">
        </div>
        <div class="form-group">
            <input type="email" class="form-control" placeholder="Enter your email id" name="email" id="regClientEmail">
        </div>
        <div class="form-group">
            <input type="password" class="form-control" placeholder="Enter your password" name="password" required="" id="regClientPassword">
        </div>
        <div class="form-group">
            <input type="password" class="form-control" placeholder="Enter your confirm password" name="cpassword" id="regClientCPassword" required="">
        </div>            
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Enter Captcha" name="captcha" required id="regClientCaptcha">
            <!-- Style.css added a class captcha_reg, will adding the captcha please add the captcha-->
            <img class="captcha_reg" src="captcha_reg.php"/>
            <button name="submit" type="button" class="btnRefresh" onClick="refreshCaptcha();" title="Refresh Captcha"><i class="fa fa-refresh"></i></button>
            <span class="captcha-info info"></span>
        </div>
        
        <div class="form-group">
            <input class="styled-checkbox" id="styled-checkbox-12" type="checkbox" name="condition" value="yes" required>
            <label for="styled-checkbox-12"><a href="#lawdisclaimer" data-toggle="modal">Accept terms and conditions</a></label>
        </div>

        <div class="form-group">
            <button type="button" class="btn btn-simple" onclick="clientRegister(this)" >Register</button>
        </div>
    </form>
</div>
<div class="col-md-1">
	<div class="container">
	    <div class="row">
	        <div class="comapre_search search_lawyer_2">
	            <div class="title col-xs-12">
	              <h6 class="text-center">OR</h6>
	            </div>
	        </div>
	    </div>
	</div>
</div>
<div class="col-md-4">
	<div class="container">
	    <div class="row">
	        <div class="comapre_search search_lawyer_2">
	            <div class="title col-xs-12">
	            	<h4 class="text-center">Client Login</h4>
	            </div>
	        </div>
	    </div>
	</div>
	<form class="form" method="post" id="register-account" autocomplete="off">
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Enter your email address" name="email" required="">
        </div>
        <div class="form-group">
            <input type="password" class="form-control" placeholder="Enter your password" name="password" required="">
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-simple" onclick="clientLogin(this)">Login</button>
        </div>
    </form>
</div>
<div class="col-md-1"></div>

<div class="col-md-12" style="margin-bottom: 50px"></div>

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

<?php 
   include("footer.php"); 
?>

<script type="text/javascript">
	function clientRegister(currentStatus){
		var name = $("#regClientName").val();
		var mobile = $("#regClientMobile").val();
		var email = $("#regClientEmail").val();
		var password = $("#regClientPassword").val();
		var cpassword = $("#regClientCPassword").val();
		var captcha = $("#regClientCaptcha").val();
		var condition = $("#styled-checkbox-12").is(":checked");

		var validateEmail = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		var validateAlphabet = /^[a-zA-Z ]{3,50}$/;
		var validateMobile = /^[1-9][0-9]{9}$/;
	}

	function clientLogin(currentStatus){
		window.location.href = 'clientPackage.php';
	}
</script>