<?php include("header.php"); 
  session_destroy();
?>

<div id="background-carousel">
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
      <div class="item active" style="background-image:url(img/bg/bg_1.jpg)"></div>
    </div>
  </div>
</div>

<div class="fixed_banner main_banner">
  <div class="bg_overlay1"></div>
  <div class="search_lawyer">
    <div class="title col-xs-12">
        <h2 class="text-center">Forgot Password</h2>
        <!-- <p class="text-center">Please enter your Registered Email address</p> -->
    </div>
    <div class="contact_form col-xs-12">
      <form id="form_signin_forget"  class="form_wrap" method="post">
        <div class="user_type">
          <div class="radio_btn">
            <input type="radio" id="account-type-lawyerr" name="user_typeee" value="lawyer">
            <label for="account-type-lawyerr">Lawyer/Law Firm</label>
          </div>
          <div class="radio_btn">
            <input type="radio" id="account-type-counselorr" name="user_typeee" value="counselor">
            <label for="account-type-counselorr">Counsel</label>
          </div>
          <div class="radio_btn">
            <input type="radio" id="account-type-internr" name="user_typeee" value="intern">
            <label for="account-type-internr">Intern</label>
          </div>
        </div>
        <div class=" col-md-6 col-md-offset-3 col-xs-12 col-xs-offset-0">
          <div class="form-group">
            <input type="email" id="forget_email"  class="form-control" placeholder="Please enter your Registered Email address">
           <!--  <input type="text" id="forget_phone" class="form-control" placeholder="Enter Phone Number"> -->

            <p id="forgeterror"></p>
          </div>
        </div>

        <br><br><br>
        <div class="user_type">
          <div class="radio_btn">
            <input type="radio" id="mobile-verification" checked name="verification_type" value="mobile_verify">
            <label for="mobile-verification">Verify by Mobile</label>
          </div>
          <div class="radio_btn">
            <input type="radio" id="email-verification" name="verification_type" value="email_verify">
            <label for="email-verification">Verify by Email</label>
          </div>
        </div>

        <div class="col-xs-12 pass">
          <button type="submit" name="submit" class="btn btn-dark change_value">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- fixed banner end -->
<?php 
 include("footer.php"); 
?>


<script>
  $('#myCarousel').carousel({
    pause: 'none'
  })
</script>

<script>
$(document).ready(function(){
  $('#form_signin_forget').submit(function(e) {  
    e.preventDefault();

    $(".change_value").text("Please Wait..");
    
    var emailaddress = $("#forget_email").val();
    var usrtype = $("input[type='radio'][name='user_typeee']:checked").val();
    var verifytype = $("input[type='radio'][name='verification_type']:checked").val();

    // var phonenum = $("#forget_phone").val();
    
    if(emailaddress != '')
    {
      var type = 'email';
      var dataval = emailaddress;
    }

    // if(phonenum != '')
    // {
    //   var type = 'phone';
    //   var dataval = phonenum;
    // }

    if(document.querySelector('input[name="user_typeee"]:checked') == null) 
      {
       $("#forgeterror").text('Please select atleast one User Type');
       $(".change_value").text("Submit");
      }
    else if(document.querySelector('input[name="verification_type"]:checked') == null) 
      {
       $("#forgeterror").text('Please select verification Type');
       $(".change_value").text("Submit");
      }
    else if(emailaddress == '')
    {
      $("#forgeterror").text('Email Required');
      $(".change_value").text("Submit");
    }
    else
    {   
      $.ajax({
        type: "POST",
        url: 'forgotpassword.php',
        data: { dataval : dataval, type : type, usrtype : usrtype, verifytype : verifytype} 
      }).done(function(data){  

        if(verifytype == "mobile_verify"){
          var str = data;
          if(str.includes("okay")){
            var form = document.createElement("form");
            document.body.appendChild(form);
            form.method = 'post';
            form.action = 'verification.php';
            var input = document.createElement('input');
            input.type = "text";
            input.name = "email";
            input.value = emailaddress;
            form.appendChild(input);
            var input2 = document.createElement('input');
            input2.type = "text";
            input2.name = "user_type";
            input2.value = usrtype;
            form.appendChild(input2);
            form.submit();
          }
          else{
            alert(str); 
            $(".change_value").text("Submit");           
          }
        }
        else{
          $("#forgeterror").html(data);
          $(".change_value").text("Submit");
        }

      });
    }
  });
});
</script> 