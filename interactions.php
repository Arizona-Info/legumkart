<?php 
 include("header.php");

  if(isset($_POST['Submit']))
{
    $quotesql= mysqli_query($conn,"INSERT INTO tbl_quotes(cust_name, cust_phone, cust_email, cust_query, quote_type, quote_date, lawyer_id, status) VALUES ('".$_POST['user_name']."','".$_POST['user_number']."','".$_POST['user_email']."','".$_POST['query']."','Quick','".date("Y-m-d")."', '".$_POST['lawyer_id']."', 'New')");

    echo '<script>alert("Your Quote enquiry has been successfully submitted");</script>';
}  
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
                    <h3>Interactions</h3>
                    <p><a href="index.php">Home</a> <span class="fa fa-angle-right"></span> Interactions</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- interactions start -->
<section class="interactions">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item active">
                        <a data-toggle="tab" href="#forum" role="tab">Forum</a>
                    </li>
                    <li class="nav-item">
                        <a data-toggle="tab" href="#news" role="tab">News</a>
                    </li>
                    <li class="nav-item">
                        <a data-toggle="tab" href="#faq" role="tab">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a data-toggle="tab" href="#contact" role="tab">Contact</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade" id="news" role="tabpanel">
                        <article class="news_content">
                            <?php
                                $item_per_page = 5;
                                $sql22 = mysqli_query($conn,"SELECT COUNT(id) FROM tbl_legalnews WHERE status = '1'");  
                                $get_total_rows3 = mysqli_fetch_array($sql22); 
                                //break total records into pages
                                $pages3 = ceil($get_total_rows3[0]/$item_per_page);
                            ?>
                            <div class="col-xs-12">
                                <div id="result_legalNews"></div>
                            </div>
                            <div class="col-xs-7">
                               <div class="pagination_legalNews"></div>
                            </div>
                        </article>
                    </div>
                    <div class="tab-pane fade" id="faq" role="tabpanel">
                    <?php
                        $item_per_page = 10;
                        $sql22 = mysqli_query($conn,"SELECT count(*) FROM tbl_faq ORDER BY faq_id DESC");  
                        $get_total_rows = mysqli_fetch_array($sql22); 
                        //break total records into pages
                        $pages = ceil($get_total_rows[0]/$item_per_page);
                    ?>
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="col-xs-12">
                                <div id="results_FAQ"></div>
                            </div>
                            <div class="col-xs-7">
                               <div class="pagination_faq"></div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="contact" role="tabpanel">
                        <section class="contact_us">
                            <div class="contact_info">
                                <div class="section-content">
                                    <div class="service-item">
                                        <div class="content">
                                            <h5><i class="pe-7s-map"></i> Location</h5>
                                            <p>Address: Mumbai</p>
                                        </div>
                                    </div>
                                    <div class="service-item">
                                        <div class="content">
                                            <h5><i class="pe-7s-clock"></i> Business Hours</h5>
                                            <p>Monday-Friday: 10am to 8pm <br>Saturday: 11am to 3pm</p>
                                        </div>
                                    </div>
                                    <div class="service-item">
                                        <div class="content">
                                            <h5><i class="pe-7s-mail"></i> Contact Info</h5>
                                            <p>info@legumkart.com</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>

                            <div class="container contact_form">
                                <div class="section-content">
                                    <div class="row">
                                        <div class="col-md-7 col-xs-12">
                                            <div class="mini-title">
                                                <h3>Contact Form</h3>
                                            </div>
                                            <!-- <div id="mydiv"></div> -->

                                       <form class="contact-form" action="" method="post" id="quickform1">     
                                            <div class="col-md-12 col-xs-12">
                                                <div class="form-group">
                                                    <input type="text" name="name" id="name1" class="form-control contact-error" placeholder="Your Full Name*" required>
                                                    <div class="form-grad-border"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="form-group">
                                                    <input type="text" name="phone" id="phone1" class="form-control contact-error" placeholder="Your Phone*" required>
                                                    <div class="form-grad-border"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                <div class="form-group">
                                                    <input type="email" name="email" id="email1" class="form-control contact-error" id="email" placeholder="Email Address*" required>
                                                    <div class="form-grad-border"></div>
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-12 col-xs-12">
                                                <div class="form-group">
                                                    <select name="reason" class="form-control contact-error">
                                                        <option>Select reason for contact</option>
                                                        <option value="Professional advice for solicitors (members only)">Professional advice for solicitors (members only)</option>
                                                        <option value="Library queries (members only)">Library queries (members only)</option>
                                                        <option value="Find a Solicitor profiles (members only)">Find a Solicitor profiles (members only)</option>
                                                        <option value="Find a Solicitor help for consumers">Find a Solicitor help for consumers</option>
                                                        <option value="TheLawMap membership">TheLawMap membership</option>
                                                        <option value="Web content or technical issues">Web content or technical issues</option>
                                                    </select>
                                                </div>
                                            </div> -->
                                            <div class="col-md-12 col-xs-12">
                                                <div class="form-group">
                                                    <textarea name="message" id="description1" class="form-control contact-error" id="message" cols="30" rows="4" placeholder="Write Message"></textarea>
                                                    <div class="form-grad-border"></div>
                                                </div>
                                            </div>

                                            <div class="captcha col-xs-12">
                                                <input type="text" name="captcha" placeholder="Enter Captcha Code" id="captcha1" required>
                                                <div class="form-grad-border"></div>
                                                <img class="captcha_code" src="captcha_code.php"/>
                                                <button name="submit" type="button"  onClick="refreshCaptcha();" title="Refresh Captcha"><i class="fa fa-refresh"></i></button>
                                                <span class="captcha-info info"></span>
                                            </div> 

                                            <div class="col-xs-12">
                                                <button type="button" name="submit"  id="submitButton" onClick="sendContact(1);" class="btn btn-simple" title="Click here to submit your message!">Send Message </button>               
                                                <!-- <button type="submit" class="btn btn-simple" data-text="Send Message"><span>Send Message</span><i class="ion-arrow-right-c"></i></button>  -->
                                            </div>
                                        </form>

                                        </div>
                                        <div class="col-md-5 col-xs-12">
                                            <div class="mini-title">
                                                <h3>Location</h3>
                                            </div>
                                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d241317.11609614608!2d72.74110185300258!3d19.082197841014377!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7c6306644edc1%3A0x5da4ed8f8d648c69!2sMumbai%2C+Maharashtra!5e0!3m2!1sen!2sin!4v1527842290322" width="100%" height="290" frameborder="0" style="border:0" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="tab-pane fade in active" id="forum" role="tabpanel">
                        <div class="col-sm-12 col-xs-12">
                           <div class="right_panel">
                              <div class="row">
                                 <div class="col-xs-12 title_stripe">
                                    <h3>Legal Discussion</h3>
                                       <a data-toggle="modal" data-target="#askQuestionModal" class="btn btn-dark add_new" style="text-transform:initial;">Ask Question</a>
                                 </div>
                              </div>
                              <hr>
                            </div>
                        </div>
                        <div class="col-sm-12 col-xs-12">
                            <section class="forum">
                            <div class=" table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Title</th>
                                            <th>Replies</th>
                                            <th>Last Message</th>
                                        </tr>
                                    </thead>
                                <?php
                                    $item_per_page = 10;
                                    $sql22 = mysqli_query($conn,"SELECT count(*) FROM tbl_forumquestion WHERE status = '1'");  
                                    $get_total_rows2 = mysqli_fetch_array($sql22); 
                                    //break total records into pages
                                    $pages2 = ceil($get_total_rows2[0]/$item_per_page);
                                ?>
                                <tbody id="result_forumQuestion"></tbody>
                                </table>
                                </div>
                                <ul class="pagination_forumQuestion">
                                </ul>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Ask Question Modal -->
<div class="zind modal fade" id="askQuestionModal">
    <div class="modal-dialog">
        <div class="modal-content">
           <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
           <div class="modal-header">
              <h3>Ask Question</h3>
           </div>
           <div class="modal-body">
              <form id="addForumQuestion" class="form" role="form" autocomplete="off">
                <input type="hidden" name="action" value="addForumQuestion"></input>
                 <div class="row">
                    <div class="col-md-6 col-xs-12">
                       <div class="form-group">
                          <input class="form-control" name="name" id="nameForum" placeholder="Enter your name" type="text" value="">
                       </div>
                    </div>
                    <div class="col-md-6 col-xs-12">
                       <div class="form-group">
                          <input class="form-control" id="emailForum" name="email" placeholder="Enter your email" type="text" value="">
                       </div>
                    </div>
                    <div class="col-md-12 col-xs-12">
                       <div class="form-group">
                          <textarea class="form-control" id="questionForum" name="question" rows="4" placeholder="Enter your question"></textarea>
                       </div>
                    </div>
                    <div class="captcha col-md-12 col-xs-12">
                        <input type="text" name="captcha" placeholder="Enter Captcha Code" id="captchaForum">
                        <div class="form-grad-border"></div>
                        <img class="captcha_code" src="captcha_code.php"/>
                        <button name="submit" type="button"  onClick="refreshCaptcha();" title="Refresh Captcha"><i class="fa fa-refresh"></i></button>
                        <span class="captcha-info info"></span>
                    </div>
                    <div class="form-group col-xs-12">
                       <button type="button" class="btn btn-simple" onclick="sendForumQuestion(this)">Send</button>
                    </div>
                 </div>
              </form>
           </div>
        </div>
    </div>
</div>

<?php 
 include("footer.php"); 
?>

<script type='text/javascript'>
function refreshCaptcha(){
    var img = document.images['captchaimg'];
    img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}
</script>

<!-- ADDED BY NAVEEN this code is used to see serialized data result without submit in mydiv declared above -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


<!-- end of this code used to see serialized data result without submit in mydiv declared above --> 

 <script>
  function sendContact(i) 
    {
      
      if(!$("#name"+i).val()) 
       {
       $("#name"+i).attr("placeholder", "Please enter your Name");
       $("#name"+i).css('background-color','#FFFFDF');
       }

       if(!$("#email"+i).val()) 
       {
       $("#email"+i).attr("placeholder", "Please enter your Email Id");
       $("#email"+i).css('background-color','#FFFFDF');
       }

       if(!$("#phone"+i).val()) 
       {
       $("#phone"+i).attr("placeholder", "Please enter your Mobile Number");
       $("#phone"+i).css('background-color','#FFFFDF');
       }
       
      if(!$("#description"+i).val()) 
       {
       $("#description"+i).attr("placeholder", "Please enter your Message");
       $("#description"+i).css('background-color','#FFFFDF');
       }


     var valid;  
     valid = validateContact(i);

     var pattern;
     pattern = validatePhone();

     if(valid && pattern) {

      var paidmoney = $('#captcha'+i).val();
       jQuery.ajax({

      url: 'validate.php',
      type: 'POST',
      data: $('#quickform'+i).serialize()  +'&'+'captcha=' + paidmoney,
  
       success:function(data){
 
        var str =data;
        // alert(str);
        if (str.includes("success"))
        {
          alert("Form has been successfully submitted");
          location.reload(true);
        }
        else
        { 
          alert("Wrong CAPTCHA");
          refreshCaptcha();
        }
       },
       error:function (){}
       });
      
     }
   }
 
   function validateContact(i) 
   {
    var valid = true; 
     $(".demoInputBox").css('background-color','');
     $(".info").html('');
     
     if(!$("#captcha"+i).val()) {
       $(".captcha-info").html("(required)");
       $("#captcha"+i).css('background-color','#FFFFDF');
       valid = false;
     }
     return valid;
   }
  
   function refreshCaptcha() 
   {
     $(".captcha_code").attr('src','captcha_code.php');
   }

   function validatePhone()
    {
        var pattern = true; 
        var x = $('#phone1').val();
        if (x.length > 1)
        {
           if(isNaN(x)||x.indexOf(" ")!=-1)
           {
              alert("Enter numeric value")
              pattern = false; 
           }
           if (x.length<8)
           {
                alert("Please Enter a Valid Phone Number");
                pattern = false; 
           }
           return pattern;
        }
    }


    function sendForumQuestion(currentStatus){
        var name = $("#nameForum").val();
        var email = $("#emailForum").val();
        var question = $("#questionForum").val();
        var captcha = $("#captchaForum").val();

        var validateMailId = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        var validateName = /^[a-zA-Z ]{2,225}$/;

        if(name == ""){
            alert("Enter your name");
            $("#nameForum").focus();
        }
        else if(!validateName.test(name)){
            alert("Incorrect name found");
            $("#nameForum").focus();
        }
        else if(email == ""){
            alert("Enter your email id");
            $("#emailForum").focus();
        }
        else if(!validateMailId.test(email)){
            alert("Incorrect email id found");
            $("#emailForum").focus();
        }
        else if(question == ""){
            alert("Enter your question");
            $("#questionForum").focus();
        }
        else if(captcha == ""){
            alert("Enter captcha");
            $("#captchaForum").focus();
        }
        else{
            $(currentStatus).text("Please Wait..");

            var formData = $("#addForumQuestion").serialize();
            $.ajax({
                type:"post",
                data:formData,
                url:"ajax_all.php",
                success:function(rec){
                    var str = rec;
                    if(str.includes("success")){
                        alert("Your question sent to lawyer");
                        location.reload();
                    }
                    else{
                        alert(str);
                    }
                },
                error:function(rec){

                }
            })
        }
    }

</script>


<script type="text/javascript" src="js/jquery.bootpag.min.js"></script>
<script type="text/javascript">
   $(document).ready(function() {

    var FAQupdate = setInterval(function(){
        $("#results_FAQ").load("pagination_FAQ.php",{limit: 25}, 
            function (responseText, textStatus, req) {
                if (textStatus == "error") {
                  console.log('error');
                }
                else{
                    clearInterval(FAQupdate);
                    console.log(textStatus);
                }
        });  //initial page number to load        
    }, 5000);

     // $("#results_FAQ").load("pagination_FAQ.php");  //initial page number to load
     $(".pagination_faq").bootpag({
        total: <?php echo $pages; ?>,
        page: 1,
        maxVisible: 5 
     }).on("page", function(e, num){
       e.preventDefault();
       $("#results_FAQ").prepend('<div class="loading-indication"  style="width: 25px;height: 25px"><img src="ajax-loader.gif" /> Loading...</div>');
       $("#results_FAQ").load("pagination_FAQ.php", {'page':num});
     });


   });
</script>

<script type="text/javascript">
   $(document).ready(function() {

    var forumQuestionupdate = setInterval(function(){
        $("#result_forumQuestion").load("pagination_fetch_pages_forumQuestion.php",{limit: 25}, 
            function (responseText, textStatus, req) {
                if (textStatus == "error") {
                  console.log('error');
                }
                else{
                    clearInterval(forumQuestionupdate);
                    console.log(textStatus);
                }
        });  //initial page number to load        
    }, 5000);


     // $("#result_forumQuestion").load("pagination_fetch_pages_forumQuestion.php");  //initial page number to load
     $(".pagination_forumQuestion").bootpag({
        total: <?php echo $pages2; ?>,
        page: 1,
        maxVisible: 5 
     }).on("page", function(e, num){
       e.preventDefault();
       $("#result_forumQuestion").prepend('<div class="loading-indication"  style="width: 25px;height: 25px"><img src="ajax-loader.gif" /> Loading...</div>');
       $("#result_forumQuestion").load("pagination_fetch_pages_forumQuestion.php", {'page':num});
     });
   });
</script>

<script type="text/javascript">
   $(document).ready(function() {


    var legalNewsUpdate = setInterval(function(){
        $("#result_legalNews").load("pagination_fetch_pages_LegalNews.php",{limit: 25}, 
            function (responseText, textStatus, req) {
                if (textStatus == "error") {
                  console.log('error');
                }
                else{
                    clearInterval(legalNewsUpdate);
                    console.log(textStatus);
                }
        });  //initial page number to load        
    }, 5000);

     // $("#result_legalNews").load("pagination_fetch_pages_LegalNews.php");  //initial page number to load
     $(".pagination_legalNews").bootpag({
        total: <?php echo $pages3; ?>,
        page: 1,
        maxVisible: 5 
     }).on("page", function(e, num){
       e.preventDefault();
       $("#result_legalNews").prepend('<div class="loading-indication" style="width: 25px;height: 25px"><img src="ajax-loader.gif" /> Loading...</div>');
       $("#result_legalNews").load("pagination_fetch_pages_LegalNews.php", {'page':num});
     });
   });
</script>

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

  function viewHideDiv1(currentStatus, id){
    var currentValue = $(currentStatus).text();
    if(currentValue == "...More"){
      $("#viewHideDiv1"+id).removeAttr("style");
      $(currentStatus).html(" <b> (Less)<b>");
    }
    else{
      $("#viewHideDiv1"+id).css("display","none");
      $(currentStatus).html("<b>...More<b>");
    }

    $("#viewHideDiv1"+id).css("font-weight",0);
  }
</script>