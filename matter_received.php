<?php 
   include("header.php"); 
   $page = 'lawyer_support_cases.php';
   if(!isset($_SESSION['user_id']))
   {
     echo  '<script>window.location="index.php"</script>';
   }

   if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] == "Counsel" || $_SESSION['user_type'] == "Intern")
   {
     echo  '<script>window.location="index.php"</script>';
   }

   if(isset($_POST['add_payment']))
   {

    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
   
    $url = str_replace("matter_received.php","api/v2/lawyer_bill_generate.php",$actual_link)."?lawyer_id=".$_SESSION['user_id']."&case_id=".$_POST['cases_id']."&another_lawyer_id=".$_POST['other_lawyer_id']."&paymt_dt=".$_POST['paymt_date']."&paymt_mode=".$_POST['paymtmode']."&cnumber=".$_POST['cnumber']."&paymt_amt=".$_POST['paymtamt']."&remarks=".$_POST['remarks']."&lc_id=".$_POST['lc_id'];
    // echo $url;
    // exit();

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

   if(isset($_POST['send_casee_idd']))
   {
    $stmt = mysqli_query($conn,"UPDATE tbl_lawyer_cases SET lc_status='Accepted', lc_flag='0' WHERE lc_id='".$_POST['send_case_id']."'");
    echo  '<script>alert("Case successfully Accepted")</script>';
   }

   if(isset($_POST['update_flage'])){
      $stmt = mysqli_query($conn,"UPDATE tbl_lawyer_cases SET lc_status='Not Available', lc_flag='0' WHERE lc_id='".$_POST['la_casee_id']."'");
      echo  '<script>alert("Data Updated successfully.")</script>';
   }

$emp_ids ='';

if($_SESSION['user_firm_id'] != 0)
{
    $user_qry = mysqli_query($conn,"SELECT lawyer_id FROM tbl_lawyers WHERE firm_id='".$_SESSION['user_firm_id']."' OR lawyer_id='".$_SESSION['user_firm_id']."'"); 
    while($totuserrows = mysqli_fetch_array($user_qry))
    {
      $emp_ids.=$totuserrows['lawyer_id'].",";
    }
    $emp_ids = chop($emp_ids,",");
}

if($_SESSION['user_type'] == 'firm')
{
    
    $user_qry = mysqli_query($conn,"SELECT lawyer_id FROM tbl_lawyers WHERE firm_id='".$_SESSION['user_id']."' OR lawyer_id='".$_SESSION['user_id']."'"); 
    while($totuserrows = mysqli_fetch_array($user_qry))
    {
      $emp_ids.=$totuserrows['lawyer_id'].",";
    }
    $emp_ids = chop($emp_ids,",");
}

if(isset($_POST['search_value'])){
    $_SESSION['matter_receive_search'] = $_POST['search_value'];
    $_SESSION['matter_receive_pagination_page'] = 1;
 }
 else if(!isset($_SESSION['matter_receive_search'])){
    $_SESSION['matter_receive_search'] = "";
    $_SESSION['matter_receive_pagination_page'] = 1;
 }

?>
<!-- manage free slot start -->
<section class="manage_freeslot">
   <div class="container-fluid">
      <div class="row">
         <div class="col-xs-12">
           <?php include("sidebar.php"); ?>
            <div class="col-md-9 col-sm-9 col-xs-12">
               <div class="right_panel">
                  <div class="row">
                     <div class="col-xs-12 title_stripe">
                        <h3>Matters Received</h3>
                        <b class="add_new total_count_view">Loading...</b>
                     </div>
                  </div>
   <div class="row">
      <div class="col-xs-12">
         <div class="table-responsive dataTables_wrapper">
            
        <table id="" class="display dataTable responsive nowrap" cellspacing="0" width="100%">
            <?php 
              $item_per_page = 10;
              $pages_matter_receive = 0;
              $string_query = "";

              if(isset($_SESSION['matter_receive_search']) && $_SESSION['matter_receive_search'] != ""){
                    $search_result12 = strtoupper($_SESSION['matter_receive_search']);
                    set_error_handler (
                        function($errno, $errstr, $errfile, $errline) {
                            throw new ErrorException($errstr, $errno, 0, $errfile, $errline);     
                        }
                    );

                    try {
                      $date_query = date_create($search_result12);
                      $date_query = "  OR t1.lc_next_date = '".date_format($date_query,"Y-m-d")."'";
                    }
                    catch(Exception $e) {
                      $date_query = "";
                    }
                    
                    $date_vs = explode(" VS",$search_result12);
                    if(isset($date_vs[0]) && isset($date_vs[1]) && $date_vs[0] != ""){
                      $date_vs1 = $date_vs[0];
                      if($date_vs[1] != ""){
                        $date_vs2 = $date_vs[1];
                      }
                      else{
                          $date_vs2 = 'data not available215';
                      }
                    }
                    else{
                      $date_vs1 = $search_result12;
                      $date_vs2 = $search_result12;
                    }

                    $data_vs1 = "";
                    if(strpos('GENERATE INVOICE', $search_result12 ) !== false){
                      $data_vs1 = " OR (lc_bill_generate = '' AND lc_status = 'Accepted')";
                    }
                    else if(strpos('VIEW', $search_result12 ) !== false){
                      $data_vs1 = " OR (lc_bill_generate != '' AND lc_status = 'Accepted')";
                    }

                    $string_query = " AND (t2.court_name like '%".$search_result12."%' OR t2.court_number like '%".$search_result12."%' OR t2.judge_name like '%".$search_result12."%' OR t2.case_number like '%".$search_result12."%' OR t2.stage like '%".$search_result12."%' OR t2.client_name like '%".$search_result12."%' OR t2.party_b like '%".$date_vs2."%' OR t2.party_a like '%".$date_vs2."%' OR t2.party_b like '%".$date_vs1."%' OR t2.party_a like '%".$date_vs1."%' ".$date_query.$data_vs1.")";
              }

              if($emp_ids != '') 
              {
                $lawyerqry = mysqli_query($conn,"SELECT t2.lawyer_id, t2.case_id, t2.court_name, t2.court_number, t2.judge_name, t2.client_name, t2.party_a, t2.party_b, t2.stage, t1.lc_next_date, t1.lc_status, t1.lc_flag, t1.lc_status, t1.lc_bill_generate, t1.lc_id FROM tbl_lawyer_cases t1, tbl_cases t2 WHERE t1.lc_case_id=t2.case_id AND t1.lc_lawyer_id IN ($emp_ids)".$string_query);
              }
              else
              {
                $lawyerqry = mysqli_query($conn,"SELECT t2.lawyer_id, t2.case_id, t2.court_name, t2.court_number, t2.judge_name, t2.client_name, t2.party_a, t2.party_b, t2.stage, t1.lc_next_date, t1.lc_status, t1.lc_flag, t1.lc_status, t1.lc_bill_generate, t1.lc_id FROM tbl_lawyer_cases t1, tbl_cases t2 WHERE t1.lc_case_id=t2.case_id AND t1.lc_lawyer_id='".$_SESSION['user_id']."'".$string_query);
              }
                 

              $lawyerno= mysqli_num_rows($lawyerqry);
              $pages_matter_receive = ceil($lawyerno/$item_per_page);
            ?>
            <input type="hidden" id="total_matter_receive" value="<?php echo $lawyerno; ?>"></input>

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
                <input type="search" name="search_value" value="<?php echo $_SESSION['matter_receive_search']; ?>" placeholder="Search here" aria-controls="attendenceDetailedTable"></input>
                <input type="submit" value="Search" class="btn btn-dark btn-sm"></input>
              </form>
            </div>

           <thead>
              <tr>
                 <!--<th>Sr. No.</th>-->
                 <th></th>
                 <th>Lawyer</th>
                 <th>Court Details</th>
                 <th>Client Name</th>
                 <th>Status</th>
                 <!-- <th>Name of Parties</th>
                 <th>Stage</th>
                 <th>Next Date</th>
                 <th>Invoice</th> -->
              </tr>
              </thead>
              <tbody id="results">
      
              </tbody>
            </table>
            <div class="dataTables_paginate paging_simple_numbers pagination" id="">
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



<div class="zind modal fade" id="counselyes">
         <div class="modal-dialog">
            <div class="modal-content">
               <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
               <div class="modal-header">
                  <h3><i class="pe-7s-users"></i> Accept Case</h3>
               </div>
               <div class="modal-body1 modal-body">
                     <p>Are you sure you want to Accept this case</p>
                     <div class="del_btn">
                        <form action="" method="post">
                           <input type="hidden" id="send_case_id" name="send_case_id" value="">
                           <button type="submit" class="btn btn-simple" id="" name="send_casee_idd">Yes</button>
                        </form>
                        <button class="btn btn-simple" data-dismiss="modal">No</button>
                     </div>
                  </div>
            </div>
         </div>
      </div>


    <div class="zind modal fade" id="counselyes2">
      <div class="modal-dialog">
         <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
            <div class="modal-header">
               <h3><i class="pe-7s-users"></i>Conference Details</h3>
            </div>
            <div class="modal-body">
               <form class="form" role="form" method="post" action=""  autocomplete="off">
                 <input type="hidden" name="la_casee_id" id="la_casee_id" value="">
                  

                  <div class="col-md-12">
                     <div class="form-group">
                        <p align="center">Are you sure you don't want to accept Firm/Lawyer request.</p>
                     </div>
                  </div>
                  
                  
                  <div class="form-group">
                     <button type="submit" class="btn btn-simple" name="update_flage">Update</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>


   <!-- Lawyer details view -->
   <div class="zind modal fade" id="lawyer_detailsd">
      <div class="modal-dialog">
         <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
            <div class="modal-header">
               <h3>Lawyer Details</h3>
            </div>
            <div class="modal-body">
               <div class="table_box">
                   <div class="heading">
                       <div class="cell">
                           <p>Phone Number</p>
                       </div>
                       <div class="cell">
                           <p>Email</p>
                       </div>
                   </div>
                   <div class="sub_heading">
                       <div class="sub_cell">
                           <p class="lawyer_details_phone"></p>
                       </div>
                       <div class="sub_cell">
                           <p class="lawyer_details_email"></p>
                       </div>
                   </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- Lawyer details view -->

   <!-- Lawyer details view -->
   <div class="zind modal fade" id="court_detailsd">
      <div class="modal-dialog">
         <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
            <div class="modal-header">
               <h3>Court Details</h3>
            </div>
            <div class="modal-body">
               <div class="table_box">
                   <div class="heading">
                       <div class="cell">
                           <p>Court Number</p>
                       </div>
                       <div class="cell">
                           <p>Judge Name</p>
                       </div>
                   </div>
                   <div class="sub_heading">
                       <div class="sub_cell">
                           <p class="cases_all_court_number"></p>
                       </div>
                       <div class="sub_cell">
                           <p class="cases_all_judge_name"></p>
                       </div>
                   </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- Lawyer details view -->


   <div class="zind modal fade" id="create_invoice">
      <div class="modal-dialog">
         <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
               <div class="modal-header">
                  <h3><i class="pe-7s-users"></i><span id="invoice_heading"></span></h3>
               </div>
               <div class="modal-body">
                  <form class="form" role="form" method="post" action="">
                    <div class="row">
                       <input name="cases_id" id="invoice_cases_id" type="hidden" value="">
                       <input name="lc_id" id="invoice_lc_id" type="hidden" value="">
                       <input name="other_lawyer_id" id="invoice_other_lawyer_id" type="hidden" value="">

                       <div class="col-sm-6 col-xs-12">
                          <div class="form-group">
                             <input class="form-control example1" name="paymt_date" placeholder="Payment Date" type="text" autocomplete="off" value="">
                          </div>
                       </div>
                        <div class="col-sm-6 col-xs-12">
                          <div class="form-group">
                             <select class="form-control add_paymtmode" name="paymtmode">
                             <option>Cash</option>
                             <option>RTGS</option>
                             <option>Cheque</option>
                           </select>
                          </div>
                       </div>
                       <div class="col-sm-6 col-xs-12 add_paymtmode_hide" style="display: none;">
                          <div class="form-group">
                             <input class="form-control" name="cnumber" placeholder="Cheque Number" type="text" value="">
                          </div>
                       </div>
                       <div class="col-sm-6 col-xs-12">
                          <div class="form-group">
                             <input class="form-control" name="paymtamt" placeholder="Payment Amount" type="text" value="">
                          </div>
                       </div>
                       <div class="col-sm-6 col-xs-12">
                          <div class="form-group">
                              <textarea name="remarks" class="form-control" rows="1" placeholder="Particulars"></textarea>
                          </div>
                       </div>
                       <div class="form-group col-xs-12">
                          <button type="submit" class="btn btn-simple" name="add_payment">Add</button>
                       </div>

                     </div>
                    </form>
               </div>
         </div>
      </div>
   </div>





<!-- manage free slot end -->
<!-- manage free slot end -->
<?php 
   include("footer.php"); 
?>

<script type="text/javascript" src="js/jquery.bootpag.min.js"></script>
<script type="text/javascript">
   $(document).ready(function() {

    var counsel = setInterval(function(){
        $("#results").load("pagination_fetch_pages_matter_received.php",{limit: 25}, 
            function (responseText, textStatus, req) {
                if (textStatus == "error") {
                  console.log('error');
                }
                else{
                    clearInterval(counsel);
                    console.log(textStatus);
                }
        });  //initial page number to load        
    }, 2000);

     
     $(".pagination").bootpag({
        total: <?php echo $pages_matter_receive; ?>,
        page: <?php echo $_SESSION['matter_receive_pagination_page']; ?>,
        maxVisible: 10 
     }).on("page", function(e, num){
       e.preventDefault();
    //   $("#results").html('<div class="loading-indication"><b>Loading...</b></div>');
        $("#results").html('<div class="loading-indication" style="width: 25px;height: 25px"><img src="assets/img/ajax-loader.gif" /></div>');
       $("#results").load("pagination_fetch_pages_matter_received.php", {'page':num});
     });
   });
</script>



<script type="text/javascript">
function yesno(i,y) 
{
  
    if (document.getElementById('toggle-on'+i).checked) 
    {
       $('#counselyes').modal('show');
       $("#send_case_id").val(y);
    }
    else 
    {
      $('#counselyes').modal('hide');
      $("#send_case_id").val("");
    }

    if (document.getElementById('toggle-off'+i).checked) 
    {
       $('#counselyes2').modal('show');
       $("#la_casee_id").val(y);
    }
    else 
    {
      $('#counselyes2').modal('hide');
      $("#la_casee_id").val("");
    }
}

function lawyerPopUp(phone, email){
  $("#lawyer_detailsd").modal('show');
  $(".lawyer_details_phone").text(phone);
  $(".lawyer_details_email").text(email);
}

function courtPopUp(court_number, judge_name){
  $("#court_detailsd").modal('show');
  $(".cases_all_court_number").text(court_number);
  $(".cases_all_judge_name").text(judge_name);
}

function generateInvoice(case_id, lawyer_case_id, other_lawyer_id, lawyer_name){
  $("#invoice_heading").text(" Generate Invoice for "+lawyer_name);
  $("#invoice_cases_id").val(case_id);
  $("#invoice_lc_id").val(lawyer_case_id);
  $("#invoice_other_lawyer_id").val(other_lawyer_id);
  $("#create_invoice").modal('show');
}

</script>


<script type="text/javascript">
  $(document).on("click",".row_click",function(){
    id = $(this).attr('id')
    id_number = id.split('change_color');
    color = $('.action').css("color");
  });
</script>

<script type="text/javascript">
  $(".add_paymtmode").change(function(){
    val = $(".add_paymtmode").val();
    if(val != "Cheque"){
      $(".add_paymtmode_hide").hide();
    }
    else{
      $(".add_paymtmode_hide").show();
    }
  })
</script>

<script type="text/javascript">
  function open_cases_details(val, id){
    $(".hide_data_on_click").hide();  //compulsory
    $(".open_cases_details").show();
    $(".close_cases_details").hide();

    $(".open_cases_details"+id).hide();
    $(".close_cases_details"+id).show();
    $(".show_data_on_click"+id).show();
  }

  function close_cases_details(val, id){
    $(".hide_data_on_click").hide();  //compulsory
    $(".open_cases_details").show();

    $(".close_cases_details").hide();
    $(".open_cases_details"+id).show();
  }

</script>

<script type="text/javascript">
  $(document).ready(function(){
    setInterval(function(){

      val = $(".NumberOfCases").val();
      if(typeof val === "undefined"){

      }
      else{
        totalCases = $("#total_matter_receive").val();

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