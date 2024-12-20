<?php 
 include("header.php");
?>

<style type="text/css">
	@media screen and (max-width: 767px){
		.imageResponsive {
			width: 20%;
			margin-left: 40%
		}
	}

	@media screen and (max-width: 394px){
		.imageResponsive {
			width: 30%;
			margin-left: 0%
		}
	}
</style>

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
                    <h3>Legal Decussion</h3>
                    <p><a href="index.php">Home</a> <span class="fa fa-angle-right"></span> Legal Decussion</p>
                </div>
            </div>
        </div>
    </div>
</section>
<br>

<?php
	$id = 0;
	if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
		echo "<script>window.location.href = 'index.php';</script>";
	}
	else{
		$id = $_GET['id'];
	}

	$sql_query = "SELECT question FROM tbl_forumquestion WHERE id = '".$id."'";
	$sql_result = mysqli_query($conn, $sql_query);

	if(mysqli_num_rows($sql_result) > 0){
		$finalResult = mysqli_fetch_assoc($sql_result);
?>

<div class="col-sm-12 col-xs-12">
    <div class="right_panel">
	    <h3 class="right1"><?php echo $finalResult['question']; ?></h3>
    </div>
</div>
<section class="lawyers_details">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-12">
                <?php
                	$sqlAnswerQuery = "SELECT t1.id, t1.answer, t1.createdDate, t2.firm_name, t2.firm_logo, t2.type FROM tbl_forumanswer t1 LEFT JOIN tbl_lawyers t2 ON t1.lawyerId = t2.lawyer_id WHERE t1.questionId = '".$id."' AND t1.status = 1 ORDER BY createdDate DESC";

                	$resultAnswerQuery = mysqli_query($conn, $sqlAnswerQuery);
                	if(mysqli_num_rows($resultAnswerQuery) > 0){
                		while ($resultAnswer = mysqli_fetch_assoc($resultAnswerQuery)) {
                ?>
                    <div class="col-md-2 col-sm-2">
                    	<?php if($resultAnswer['firm_logo'] != ""){ ?>
                    		<img src="img/firms/<?php echo $resultAnswer['firm_logo']; ?>" class="imageResponsive">
                    	<?php }else{ ?>
                    		<img src="img/about.jpg" class="imageResponsive">
                    	<?php } ?>
                    </div>
                    <div class="col-md-10 col-sm-10">
                    	<h3 class="title"><?php echo $resultAnswer['firm_name']; ?></h3>
                    	<div class="detail_content">
                    		<p><span>Date : </span><?php echo date("M d, Y", strtotime($resultAnswer['createdDate'])); ?></p>
                    	</div>
                    	<div class="detail_content">
                    		<p><span>Type : </span><?php echo ucwords($resultAnswer['type']); ?></p>
                    	</div>
                        <div class="detail_content">
                            <p><span>Reply : </span> <?php 
                            $stringLength = strlen($resultAnswer['answer']);
                            if($stringLength > 200){
                            	echo substr($resultAnswer['answer'], 0, 200).'<spaan id="viewHideDiv'.$resultAnswer['id'].'" style="display: none">'.substr($resultAnswer['answer'], 200, $stringLength).'</spaan>'.'<a href="#!" onclick="viewHideDiv(this, \''.$resultAnswer['id'].'\')"><b>...More</b></a><br>';
                            }
                            else{
                            	echo $resultAnswer['answer'];
                            }
                            ?></p>
                        </div>
                    </div>
                	<div class="col-md-12 col-sm-12"><hr></div>
                <?php
                		}
                ?>
                <?php
                	}
                	else{
						echo '<div class="no_result"><img src="img/about.png"><p>No Reply.</p></div>';
                	}
                	if(isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])){
                ?>
                <form id="submitYourAnswer" autocomplete="off">
                	<input type="hidden" name="action" value="submitLawyerReview"></input>
                	<input type="hidden" name="questionId" value="<?php echo $_GET['id']; ?>"></input>
                	<div class="form-group">
                        <textarea name="message" id="lawyeranswer" class="form-control contact-error" cols="30" rows="4" placeholder="Write your reply"></textarea>
                        <!-- <div class="form-grad-border"></div> -->
                    </div>
                    <div class="captcha col-xs-12">
                        <input type="text" name="captcha" placeholder="Enter Captcha Code" id="captchaLawyerDecussion" required>
                        <div class="form-grad-border"></div>
                        <img class="captcha_code" src="captcha_code.php"/>
                        <button name="submit" type="button"  onClick="refreshCaptcha();" title="Refresh Captcha"><i class="fa fa-refresh"></i></button>
                        <span class="captcha-info info"></span>
                    </div>
                    <button type="button" name="submit" onClick="submitYourAnswer(this)" class="btn btn-simple" title="Click here to submit your reply!" style="margin-bottom: 30px">Submit</button>
                </form>
                <?php } ?>

                <?php if(mysqli_num_rows($resultAnswerQuery) > 0){ ?>
                <marquee>
                    Disclaimer: The above query and its response is NOT a legal opinion in any way whatsoever as this is based on the information shared by the person posting the query at legumkart.com and has been responded by one of the Family Lawyers at legumkart.com to address the specific facts and details. You may post your specific query based on your facts and details to get a response from one of the Lawyers at legumkart.com or contact a Lawyer of your choice to address your query in detail.
                </marquee>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
<?php
	}
	else{
		echo '<div class="no_result"><img src="img/about.png"><p>No Results Found.</p></div>';
	}
?>

<?php 
 include("footer.php"); 
?>

<script type="text/javascript">
	function viewHideDiv(currentStatus, id){
		var currentValue = $(currentStatus).text();
		if(currentValue == "...More"){
			$("#viewHideDiv"+id).removeAttr("style");
			$(currentStatus).html(" <b> (Less)<b>");
		}
		else{
			$("#viewHideDiv"+id).css("display","none");
			$(currentStatus).html("<b>...More<b>");
		}

		$("#viewHideDiv"+id).css("font-weight",0);
	}

	function refreshCaptcha() 
   	{
    	$(".captcha_code").attr('src','captcha_code.php');
   	}

	function submitYourAnswer(currentStatus){
		var message = $("#lawyeranswer").val();
		var captcha = $("#captchaLawyerDecussion").val();

		if(message == ""){
			alert("Please enter your reply");
			$("#lawyeranswer").focus();
		}
		else if(captcha == ""){
			alert("Please enter captcha");
			$("#captchaLawyerDecussion").focus();
		}
		else{
			$(currentStatus).text("Please Wait..");
			var formData = $("#submitYourAnswer").serialize();

			$.ajax({
				type:"POST",
				data:formData,
				url:"ajax_all.php",
				success:function(rec){
					var str = rec;
                    if(str.includes("success")){
                        alert("Your reply submited successfully");
                        location.reload();
                    }
                    else{
                        alert(str);
                        $(currentStatus).text("SUBMIT");
                    }
				},
				error:function(rec){

				}
			})
		}
	}
</script>