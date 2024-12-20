<?php 
   $page = 'counselor';
   include("header.php"); 

   if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'Counsel')
   {
     echo  '<script>window.location="index.php"</script>';
   }

   $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if(isset($_POST['bill_generate'])){
  $url = str_replace("counselor_cases_detail.php","api/v2/counsel_bill_generate.php",$actual_link)."?lawyer_id=".$_POST['lawyer_id']."&cc_id=".$_POST['cc_id']."&amount=".$_POST['amt_2']."&counsel_id=".$_SESSION['user_id'];

    //$json = file_get_contents($url);
    $data12 = callAPI('REQUEST', $url, false);
    if(isset($data12['data'][0]['pdf_file_name'])){
      echo "<script>alert('Invoice created successfully')</script>";
      echo "<script>$(document).ready(function(){
              window.setTimeout(function(){
                  window.open('admin/uploads/".$data12['data'][0]['pdf_file_name']."', '_blank','width=450, height=700');
              }, 500);
          });</script>";
    }
    else{
      echo "<script>alert(".$data12['message'].")</script>";
    }
}

if(isset($_POST['search_value'])){
  $_SESSION['upcoming_counsel_search'] = $_POST['search_value'];
  $_SESSION['upcoming_counsel_pagination_page1'] = 1;
}
else if(!isset($_SESSION['upcoming_counsel_search'])){
  $_SESSION['upcoming_counsel_search'] = "";
  $_SESSION['upcoming_counsel_pagination_page1'] = 1;
}
else if(!isset($_SESSION['upcoming_counsel_pagination_page1'])){
  $_SESSION['upcoming_counsel_pagination_page1'] = 1;
}


if(isset($_POST['search_value'])){
    $_SESSION['upcoming_counsel_search'] = $_POST['search_value'];
    $_SESSION['upcoming_counsel_pagination_page2'] = 1;
 }
 else if(!isset($_SESSION['upcoming_counsel_search'])){
    $_SESSION['upcoming_counsel_search'] = "";
    $_SESSION['upcoming_counsel_pagination_page2'] = 1;
 }
 else if(!isset($_SESSION['upcoming_counsel_pagination_page2'])){
  $_SESSION['upcoming_counsel_pagination_page2'] = 1;
 }

 if(isset($_POST['search_value'])){
    $_SESSION['upcoming_counsel_search'] = $_POST['search_value'];
    $_SESSION['upcoming_counsel_pagination_page3'] = 1;
 }
 else if(!isset($_SESSION['upcoming_counsel_search'])){
    $_SESSION['upcoming_counsel_search'] = "";
    $_SESSION['upcoming_counsel_pagination_page3'] = 1;
 }
 else if(!isset($_SESSION['upcoming_counsel_pagination_page3'])){
  $_SESSION['upcoming_counsel_pagination_page3'] = 1;
 }

?>
<!-- manage free slot start -->
<section class="manage_freeslot">
   <div class="container-fluid">
      <div class="row">
         <div class="col-xs-12">
           <?php include("sidebar1.php"); ?>
            <div class="col-sm-9 col-xs-12">

               <div class="right_panel">
                  <div class="row">
                    <div class="col-xs-12 title_stripe">
                      <!-- <h3 class="">Counsel Diary</h3> -->
                      <h3 class="">Case Diary</h3>
                      <!-- <a href="#add_cases" data-toggle="modal" class="btn btn-dark add_new" style="text-transform:initial;">Add</a> -->
                      <!-- <a class="add_new">&nbsp</a> -->
                      <b class="add_new total_count_view">Loading...</b>
                    </div>
                  </div>
                  <hr>

                  
         <div class="row interactions">
            <div class="col-md-12">
               <ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item active" onclick="cases_today()">
                     <a data-toggle="tab" href="#today" role="tab">Today (<?php echo date('d-M');?>)</a>
                  </li>
                  <li class="nav-item" onclick="cases_tomorrow()">
                     <a data-toggle="tab" href="#tomorrow" role="tab">Tomorrow (<?php echo date('d-M', strtotime('+1 day'));?>)</a>
                  </li>
                  <li class="nav-item" onclick="cases_weekly()">
                     <a data-toggle="tab" href="#all" role="tab">Weekly (<?php echo date('d-M', strtotime('+2 day')).' to '.date('d-M', strtotime('+7 day'));?>)</a>
                  </li>
               </ul>
               <div class="tab-content">
                  <?php include("counselor_account_today_new.php"); ?>

                  <?php include("counselor_account_tomorrow_new.php"); ?>

                  <?php include("counselor_account_weekly_new.php"); ?>
               </div>
            </div>
         </div>

   </div>
   </div>
   </div>
   </div>
   </div>
</section>



<div class="zind modal fade" id="court_detailss">
    <div class="modal-dialog">
       <div class="modal-content">
          <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
          <div class="modal-header">
             <h3>Case Details</h3>
          </div>

          <div class="modal-body">
             <div class="">
                <div class="heading">
                      <div >
                         <p><strong>Lawyer Contact</strong> : <span id="lawyerPhone"></span></p>
                      </div>
                      <div>
                         <p><strong>Lawyer Email</strong> : <span id="lawyerEmail"></span></p>
                      </div>
                      <div >
                         <p><strong>Court Number</strong> : <span id="lawyerCourtNumber"></span></p>
                      </div>
                      <div >
                         <p><strong>Court Name</strong> : <span id="lawyerCourtName"></span></p>
                      </div>
                      <div>
                         <p><strong>Judge Name</strong> : <span id="lawyerJudgeName"></span></p>
                      </div>
                      <div >
                         <p><strong>Case Number</strong> : <span id="lawyerCaseNumber"></span></p>
                      </div>
                      <div>
                         <p><strong>Client</strong> : <span id="lawyerClient"></span></p>
                      </div>
                      <div>
                         <p><strong>Parties</strong> : <span id="lawyerParties"></span></p>
                      </div>
                      <div>
                         <p><strong>Court Hearing Date</strong> : <span id="lawyerDate"></span></p>
                      </div>
                </div>
             </div>

          </div>

       </div>
    </div>
 </div>







 <div class="zind modal fade" id="conselordetail52">
   <div class="modal-dialog">
      <div class="modal-content">
         <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
         <div class="modal-header">
            <h3><i class="pe-7s-users"></i> Bill Generate <span id="lawyerNameAndType"></span></h3>
         </div>
         <div class="modal-body">

            <form id="bill_generate_form" method="post" action="" autocomplete="off">

              <input class="form-control" type="hidden" id="counselUniqueId" name="cc_id" value="" required>
              <input class="form-control" type="hidden" id="lawyerUniqueId" name="lawyer_id" value="" required>
              <input type="hidden" name="bill_generate"></input>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input class="form-control" id="val_2" type="text" name="amt_2" value="" placeholder="GMS" oninput="add2()" required>
                    </div>
                  </div>
                  <div class="col-md-6" hidden>
                    <div class="form-group">
                      <!-- <label>Amount_1 :</label> -->
                      <input class="form-control" id="val_1" type="text" name="amt_1" value="15" placeholder="Amount" oninput="add1()" required >
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <input class="form-control" id="val_3" type="text" name="amt_3" value="" placeholder="Total" readonly required>
                    </div>
                  </div>
                  <div class="col-md-12">
                  <div class="form-group">
                    <button type="button" class="btn btn-simple " onClick="bill_generate_btn()">Generate</button>
                </div>
                </div>
            </form>

         </div>
      </div>
   </div>
</div>



<div class="zind modal fade" id="conferenceDetails">
   <div class="modal-dialog">
      <div class="modal-content">
         <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
         <div class="modal-header">
            <h3><i class="pe-7s-users"></i> Conference Details <span id="conferenceName"></span></h3>
         </div>
         <div class="modal-body">
            <div class="table_box">
               <div class="heading">

                     <div class="cell">
                        <p>Conference Place</p>
                     </div>
                </div>
                <div class="sub_heading">
                    <div class="sub_cell" id="conferencePlace">
                    </div>
                </div>
            </div>
         </div>
      </div>
   </div>
</div>


<?php 
   include("footer.php"); 
?>


<script type="text/javascript" src="js/jquery.bootpag.min.js"></script>
<script type="text/javascript">
   $(document).ready(function() {

     var today = setInterval(function(){
        $("#results_today").load("pagination_fetch_pages_counsel_today.php",{limit: 25}, 
            function (responseText, textStatus, req) {
                if (textStatus == "error") {
                  console.log('error');
                }
                else{
                    clearInterval(today);
                    console.log(textStatus);
                }
        });  //initial page number to load        
    }, 3000);
    
     $(".pagination_today").bootpag({
        total: <?php echo $pages_today; ?>,
        page: <?php echo $_SESSION['upcoming_counsel_pagination_page1']; ?>,
        maxVisible: 10 
     }).on("page", function(e, num){
       e.preventDefault();
    //   $("#results").html('<div class="loading-indication"><b>Loading...</b></div>');
        $("#results_today").html('<div class="loading-indication"><img src="assets/img/ajax-loader.gif" /></div>');
       $("#results_today").load("pagination_fetch_pages_counsel_today.php", {'page':num});
     });


    var tomorrow = setInterval(function(){
        $("#results_tomorrow").load("pagination_fetch_pages_counsel_tomorrow.php",{limit: 25}, 
            function (responseText, textStatus, req) {
                if (textStatus == "error") {
                  console.log('error');
                }
                else{
                    clearInterval(tomorrow);
                    console.log(textStatus);
                }
        });  //initial page number to load        
    }, 3000);

     $(".pagination_tomorrow").bootpag({
        total: <?php echo $pages_tomorrow; ?>,
        page: <?php echo $_SESSION['upcoming_counsel_pagination_page2']; ?>,
        maxVisible: 10 
     }).on("page", function(e, num){
       e.preventDefault();
          $("#results_tomorrow").html('<div class="loading-indication"><img src="assets/img/ajax-loader.gif" /></div>');
       $("#results_tomorrow").load("pagination_fetch_pages_counsel_tomorrow.php", {'page':num});
     });


    var weekly = setInterval(function(){
        $("#results_weekly").load("pagination_fetch_pages_counsel_weekly.php",{limit: 25}, 
            function (responseText, textStatus, req) {
                if (textStatus == "error") {
                  console.log('error');
                }
                else{
                    clearInterval(weekly);
                    console.log(textStatus);
                }
        });  //initial page number to load        
    }, 3000);

     $(".pagination_weekly").bootpag({
        total: <?php echo $pages_weekly; ?>,
        page: <?php echo $_SESSION['upcoming_counsel_pagination_page3']; ?>,
        maxVisible: 10 
     }).on("page", function(e, num){
       e.preventDefault();
        $("#results_weekly").html('<div class="loading-indication"><img src="assets/img/ajax-loader.gif" /></div>');
       $("#results_weekly").load("pagination_fetch_pages_counsel_weekly.php", {'page':num});
     });


   });
</script>

<script>
   $(function () {
       $('#addopt').click(function () {
           var newopt = $('#newopt').val();
           if (newopt == '') {
               alert('Please enter something!');
               return;
           }
           //check if the option value is already in the select box
           $('#opt option').each(function (index) {
               if ($(this).val() == newopt) {
                   alert('Duplicate option, Please enter new!');
                   exit();
               }
           })
           //add the new option to the select box
           $("#opt")[0][0].remove();
           $('#opt').prepend('<option value=' + newopt + '>' + newopt + '</option>');
           // $('#opt').append('<option value=' + newopt + '>' + newopt + '</option>');

           //select the new option (particular value)
           $('#opt option[value="' + newopt + '"]').prop('selected', true);
       });
   });
</script>
<script>
function showDiv(elem)
{
      // alert('Hi');
   if(elem.value == 1)
   {
      document.getElementById('hide_show_div').style.display = "block";
   }
    else
   { 
     document.getElementById('hide_show_div').style.display = "none";
   } 
} 

function lawyerDetails(phone, email, court_number, court_name, judge_name, case_number, client_name, party_a, party_b, date){
  $("#lawyerPhone").text(phone);
  $("#lawyerEmail").text(email);
  $("#lawyerCourtNumber").text(court_number);
  $("#lawyerCourtName").text(court_name);
  $("#lawyerJudgeName").text(judge_name);
  $("#lawyerCaseNumber").text(case_number);
  $("#lawyerClient").text(client_name);
  $("#lawyerParties").text(party_a+" Vs "+party_b);
  $("#lawyerDate").text(date);
  $("#court_detailss").modal('show');
}

function generateBillBtn(id, lawyer_id, type, name){
  $("#lawyerNameAndType").text("(Name: "+name+" & Type: "+type+")")
  $("#counselUniqueId").val(id);
  $("#lawyerUniqueId").val(lawyer_id);
  $("#val_2").val("");
  $("#conselordetail52").modal('show');
}
  
function bill_generate_btn(){
  val1 = Number($("#val_1").val());
  val2 = Number($("#val_2").val());
  val3 = Number($("#val_3").val());

  if(isNaN(val1) || isNaN(val3) || isNaN(val3) || val1 == "" || val3 == "" || val2 == "")
  {
     alert('Incorrect value');
  }
  else{
     $('#bill_generate_form').submit();
  }
}

function add1(){
    val1 = Number(document.getElementById("val_1").value);
    val2 = Number(document.getElementById("val_2").value);

    z = val1 * val2;
    document.getElementById("val_3"+i).value = z;
    
}
function add2(){
    val1 = Number(document.getElementById("val_1").value);
    val2 = Number(document.getElementById("val_2").value);


    z = val1 * val2;
    document.getElementById("val_3").value = z;
    
}

function conferenceDetails(name, place){
  $("#conferenceName").text('('+name+')');
  $("#conferencePlace").text(place);
  $("#conferenceDetails").modal('show');
}
</script>


<script type="text/javascript">
  $(document).ready(function(){

    change_total_cases = setInterval(function(){

      val = $(".NumberOfCasesToday").val();
      if(typeof val === "undefined"){

      }
      else{
        totalCases = $("#total_cases_counsel_today").val();

        if(totalCases != 0){
          $(".total_count_view").text(val+ totalCases +" Entries");
        }
        else{
          $(".total_count_view").text("Showing 0 To 0 Of 0 Entries");
        }
      }

    }, 500);

  })
</script>

<script type="text/javascript">
  function cases_today(){
      clearInterval(change_total_cases);
      change_total_cases = setInterval(function(){

        val = $(".NumberOfCasesToday").val();
        if(typeof val === "undefined"){

        }
        else{
          totalCases = $("#total_cases_counsel_today").val();

          if(totalCases != 0){
            $(".total_count_view").text(val+ totalCases +" Entries");
          }
          else{
            $(".total_count_view").text("Showing 0 To 0 Of 0 Entries");
          }
        }

      }, 500);
    }

    function cases_tomorrow(){
      clearInterval(change_total_cases);
      change_total_cases = setInterval(function(){

        val = $(".NumberOfCasesTomorrow").val();
        if(typeof val === "undefined"){

        }
        else{
          totalCases = $("#total_cases_counsel_tomorrow").val();

          if(totalCases != 0){
            $(".total_count_view").text(val+ totalCases +" Entries");
          }
          else{
            $(".total_count_view").text("Showing 0 To 0 Of 0 Entries");
          }
        }
      }, 500);
    }


    function cases_weekly(){
      clearInterval(change_total_cases);
      change_total_cases = setInterval(function(){

        val = $(".NumberOfCasesWeekly").val();
        if(typeof val === "undefined"){

        }
        else{
          totalCases = $("#total_cases_counsel_weekly").val();

          if(totalCases != 0){
            $(".total_count_view").text(val+ totalCases +" Entries");
          }
          else{
            $(".total_count_view").text("Showing 0 To 0 Of 0 Entries");
          }
        }

      }, 500);
    }
</script>