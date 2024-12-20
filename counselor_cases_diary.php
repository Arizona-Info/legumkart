<?php 
   include("header.php"); 
   $page = 'counselor';
   $string_query = "";
   if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'Counsel')
   {
     echo  '<script>window.location="index.php"</script>';
   }

   $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if(isset($_POST['bill_generate'])){
  $url = str_replace("counselor_cases_diary.php","api/v2/counsel_bill_generate.php",$actual_link)."?lawyer_id=".$_POST['lawyer_id']."&cc_id=".$_POST['cc_id']."&amount=".$_POST['amt_2']."&counsel_id=".$_SESSION['user_id'];

    //$json = file_get_contents($url);
    //$data12 = json_decode($json, TRUE);
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
  $_SESSION['all_counsel_search'] = $_POST['search_value'];
  $_SESSION['all_counsel_pagination_page'] = 1;
}
else if(!isset($_SESSION['all_counsel_search'])){
  $_SESSION['all_counsel_search'] = "";
  $_SESSION['all_counsel_pagination_page'] = 1;
}
else if(!isset($_SESSION['all_counsel_pagination_page'])){
  $_SESSION['all_counsel_pagination_page'] = 1;
}


if(isset($_SESSION['all_counsel_search']) && $_SESSION['all_counsel_search'] != ""){
    $search_result12 = strtoupper($_SESSION['all_counsel_search']);
    set_error_handler (
        function($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, $errno, 0, $errfile, $errline);     
        }
    );

    try {
      $date_query = date_create($search_result12);
      $date_query = " OR cc_date = '".date_format($date_query,"Y-m-d")."' OR cc_next_date = '".date_format($date_query,"Y-m-d")."'";
    }
    catch(Exception $e) {
      $date_query = "";
    }

    
    $data_1 = "";
    if(strpos("ATTENDED", $search_result12) !== false){
      $data_1 = " OR cc_action LIKE '%Attended%'";
    }
    else if(strpos("NOT ATTENDED", $search_result12) !== false){
      $data_1 = " OR (cc_bill_pdf = '' AND (cc_action LIKE '%Not Attended%'  OR cc_action LIKE '%Pending%'))";
    }
    else if(strpos("GENERATE BILL", $search_result12) !== false){
      $data_1 = " OR cc_bill_pdf = ''";
    }
    else if(strpos("VIEW", $search_result12) !== false || strpos("BILL", $search_result12) !== false || strpos("INVOICE", $search_result12) !== false){
      $data_1 = " OR cc_bill_pdf != ''";
    }

    $string_query = " AND (judge_name like '%".$search_result12."%' OR court_name like '%".$search_result12."%' OR court_number like '%".$search_result12."%' OR cc_type like '%".$search_result12."%'".$date_query.$data_1.")";
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
                      <h3>Counsel Cases Diary</h3>
                      <b class="add_new total_count_view">Loading...</b>
                    </div>
                  </div>
                  <div class="row">
                     <div class="col-xs-12">
                        <div class="table-responsive dataTables_wrapper">

                        <?php 
                         $item_per_page = 10;
                          $counselcases = mysqli_query($conn,"SELECT cc_date FROM tbl_counsel_cases t1, tbl_cases t2 WHERE t1.cc_case_id=t2.case_id AND t1.cc_status='Accepted' AND t1.counsel_id = '".$_SESSION['user_id']."'".$string_query);

                          $get_total_rows = mysqli_num_rows($counselcases); 
                          $pages = ceil($get_total_rows/$item_per_page);
                         ?>

                         <input type="hidden" id="total_cases_counsel" value="<?php echo $get_total_rows; ?>"></input>

                         <table id="" class="display dataTable responsive nowrap" cellspacing="0" width="100%">
                            <div class="dataTables_length">
                              <label>Show 
                                <select name="attendenceDetailedTable_length" aria-controls="attendenceDetailedTable" class="">
                                  <option value="10">10</option>
                                </select>
                                entries
                              </label>
                            </div>

                            <div class="dataTables_filter">
                              <form method="post" autocomplete="off" >
                                <input type="search" name="search_value" value="<?php echo $_SESSION['all_counsel_search']; ?>" placeholder="Search here" aria-controls="attendenceDetailedTable"></input>
                                <input type="submit" value="Search" class="btn btn-dark btn-sm"></input>
                              </form>
                            </div>

                              <thead>
                                 <tr>
                                    <!-- <th>Sr. No.</th> -->
                                    <th>Lawyer</th>
                                    <th>Type</th>
                                    <th>Date/Time</th>
                                    <th>Action</th>
                                    <th>Bill</th>
                                </tr>
                              </thead>
                              <tbody id="results_all">
                                
                              </tbody>
                           </table>
                            <div class="dataTables_paginate paging_simple_numbers pagination_all" id="">
                            </div>
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
        $("#results_all").load("pagination_fetch_pages_counsel_all.php",{limit: 25}, 
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

     $(".pagination_all").bootpag({
        total: <?php echo $pages; ?>,
        page: <?php echo $_SESSION['all_counsel_pagination_page']; ?>,
        maxVisible: 10 
     }).on("page", function(e, num){
       e.preventDefault();
    //   $("#results").html('<div class="loading-indication"><b>Loading...</b></div>');
        $("#results_all").html('<div class="loading-indication" style="width: 25px"><img src="assets/img/ajax-loader.gif" /></div>');
       $("#results_all").load("pagination_fetch_pages_counsel_all.php", {'page':num});
     });


  })
</script>



<script type="text/javascript">
  function bill_generate_btn(i){
    val1 = Number($("#val_1"+i).val());
    val2 = Number($("#val_2"+i).val());
    val3 = Number($("#val_3"+i).val());

    if(isNaN(val1) || isNaN(val3) || isNaN(val3) || val1 == "" || val3 == "" || val2 == "")
    {
       alert('Incorrect value');
    }
    else{
       $('#bill_generate_form'+i).submit();
    }
  }

  function add1(i){
      val1 = Number(document.getElementById("val_1"+i).value);
      val2 = Number(document.getElementById("val_2"+i).value);

      z = val1 * val2;
      document.getElementById("val_3"+i).value = z;
      
  }
  function add2(i){
      val1 = Number(document.getElementById("val_1"+i).value);
      val2 = Number(document.getElementById("val_2"+i).value);


      z = val1 * val2;
      document.getElementById("val_3"+i).value = z;
      
  }

</script>

<script type="text/javascript">
 function saveStatus(id,editableObj) 
 {  
   // alert(editableObj);
   // alert(id);

   if(editableObj != ""){
      $.ajax({
        url: "savestatus_counselor_cases_diary.php",
        type: "POST",
        data:'editval='+editableObj+'&id='+id,
        success: function(data){
            // alert(data);
            $('#mydiv_'+id).html(data);
        }        
   });
   }
   if(editableObj == "Attended"){
      $("#visible_"+id).show();
   }
   else{
      $("#visible_"+id).hide();
   }
}
</script>


<script type="text/javascript">
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

      val = $(".NumberOfCasesAll").val();
      if(typeof val === "undefined"){

      }
      else{
        totalCases = $("#total_cases_counsel").val();

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