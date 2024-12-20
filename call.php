<?php 
   include("header.php"); 
?>

<style type="text/css"></style>

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
                    <h3>Talk to Lawyers</h3>
                    <p><a href="index.php">Home</a> <span class="fa fa-angle-right"></span> Talk to Lawyers</p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <div class="row">
        <div class="comapre_search search_lawyer_2">
            <div class="title col-xs-12">
              <h2 class="text-center">Talk to Lawyers</h2>
            </div>
    <!-- <div class="container">
    	<form class="form" role="form" method="post" action="">
        <div class="row">
        	<div class="col-xs-12 col-md-6">
    			<div class="form-group">
      				<input type="text" class="form-control" placeholder="Name" name="user_name2" required="">
    			</div>
    		</div>
           	<div class="col-xs-12 col-md-6">
    			<div class="form-group">
      				<input type="email" class="form-control" placeholder="Email Id" name="user_email2">
    			</div>
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-xs-12 col-md-6">
				<div class="form-group">
				  	<input type="password" class="form-control" placeholder="Password" name="user_password1" required="">
				</div>
           	</div>
        	<div class="col-xs-12 col-md-6">
    			<div class="form-group">
      				<input type="text" class="form-control" pattern="[1-9]{1}[0-9]{9}" title="Enter 10 digit mobile number" placeholder="Mobile Number" name="user_number2" required="">
    			</div>
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-xs-12 col-md-6">
				<div class="form-group">
                    <input class="styled-checkbox" id="styled-checkbox-11" type="checkbox" name="compare" value="yes" required="">
                    <label for="styled-checkbox-11"><a href="#" id="termcondition" data-toggle="modal">Terms and Conditions</a></label>
                </div>
           	</div>
    	</div>
    	<div class="form-group">
            <button type="submit" class="btn btn-simple" name="signup">Submit</button>
        </div>
    </form>
    </div> -->
        </div>
    </div>
</div>

<!-- Cards -->
<div class="container head">
<div class="row">
<?php
    $sqli_query = "SELECT firm_name, firm_logo, callCharges, joiningDate FROM tbl_lawyers WHERE lawyer_id IN (14, 19, 20, 22, 27, 33, 46, 47, 48, 57, 59, 61, 17)";
    $sqli_result = mysqli_query($conn, $sqli_query);
    if(mysqli_num_rows($sqli_result) > 0){
        while ($mysqli_finalResult = mysqli_fetch_assoc($sqli_result)) {
?>

    <div class="col-sm-6 col-md-3">
        <div class="thumbnail text-left astroBg thumbnailNew">
            <div class="text-left">
                <div class="profile">
                    <a href="clientLogin.php">
                        <?php if($mysqli_finalResult['firm_logo'] != ""){ ?>
                        <img class="b-lazy pull-left img-circle margin_Rgt10 b-loaded" alt="<?php echo $mysqli_finalResult['firm_name']; ?>" title="<?php echo $mysqli_finalResult['firm_name']; ?>" src="img/firms/<?php echo $mysqli_finalResult['firm_logo']; ?>">
                        <?php }else{ ?>
                        <img class="b-lazy pull-left img-circle margin_Rgt10 b-loaded" alt="<?php echo $mysqli_finalResult['firm_name']; ?>" title="<?php echo $mysqli_finalResult['firm_name']; ?>" src="img/about.jpg">
                        <?php } ?>
                    </a>
                    <a href="clientLogin.php" class=" top0 profile_link top5 font14 profile_link">
                        <span><?php echo $mysqli_finalResult['firm_name']; ?></span>
                    </a>

                    <div class="star pull-left top0 h6">
                        <div class="star_rating_a">
                            4.59
                            <span class="glyphicon glyphicon-star glyphicon_gray_star " aria-hidden="true"></span>
                        </div>
                    </div>
				</div>
                <div class="clearfix"></div>
                <div class=" call_action">
                    <div class="col-md-7  col-xs-9">
                        <div class="row ">
                            <p class="top10 margin0 LightGrayColor "><span class="sprite_talk_astrologer sprite_experience "></span>Exp : <?php 
                                if($mysqli_finalResult['joiningDate'] != "0000-00-00"){
                                    $d1 = new DateTime(date('Y-m-d'));
                                    $d2 = new DateTime($mysqli_finalResult['joiningDate']);

                                    $diff = $d2->diff($d1);

                                    echo $diff->y."+ years";
                                }
                                else{
                                    echo 'NA';
                                }
                            ?>
                            </p>
                            <p class="margin0 LightGrayColor top5"><span class="sprite_talk_astrologer sprite_rupee"></span><?php echo $mysqli_finalResult['callCharges']; ?>/Min</p>
                        </div>
                    </div>
                    <div class="col-md-5 col-xs-3  text-center ">
                        <a href="clientLogin.php" class=" onlineaction" rel="nofollow">
                            <span class="sprite_talk_astrologer sprite_call_width sprite_online "></span>

                            <div class="clearfix"></div>
                            Online
                        </a>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

<?php
        }
    }
?>
</div>
</div>


<?php 
   include("footer.php"); 
?>