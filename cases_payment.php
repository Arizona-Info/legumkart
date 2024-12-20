<?php 
   $page = 'cases_payment.php';
   require_once("header.php"); 
   $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

   if(!isset($_SESSION['user_id']))
   {
     echo  '<script>window.location="index.php"</script>';
   }

   if(!isset($_SESSION['cases_all_pagination_page'])){
      $_SESSION['cases_all_pagination_page'] = 1;
   }

   if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] == "Counsel" || $_SESSION['user_type'] == "Intern")
   {
     echo  '<script>window.location="index.php"</script>';
   }

   if(isset($_POST['search_value'])){
      $_SESSION['cases_all_search_payment'] = $_POST['search_value'];
      $_SESSION['cases_all_pagination_page'] = 1;
   }
   else if(!isset($_SESSION['cases_all_search_payment'])){
      $_SESSION['cases_all_search_payment'] = "";
      $_SESSION['cases_all_pagination_page'] = 1;
   }

   if(isset($_POST['delete_id']))
    {
      $did = $_REQUEST['did'];  

      $qry_bill = "SELECT bill_generate FROM tbl_payments WHERE id = '".$did."'";
      $select_bill = mysqli_query($conn, $qry_bill);
      $select_bill = mysqli_fetch_assoc($select_bill);

      if($select_bill['bill_generate'] != ""){
        $path = "admin/uploads/".$select_bill['bill_generate'];
        if(file_exists($path)){
          unlink($path);
        }
      }

      $dellawyer = mysqli_query($conn,"DELETE FROM tbl_payments where id = '".$did."'");
      if($dellawyer){
        echo "<script>alert('Last invoice deleted successfully')</script>";
      }
      else{
        echo "<script>alert('Something went wrong please try again')</script>"; 
      }
    }


    if(isset($_POST['edit_payment']))
     {
       $eid = $_REQUEST['eid'];

       $url = str_replace("cases_payment.php","api/v2/client_bill_generate.php",$actual_link)."?lawyer_id=".$_SESSION['user_id']."&cases_no=".$_POST['ecase_number']."&paymt_dt=".$_POST['epaymt_date']."&paymt_mode=".$_POST['epaymtmode']."&cnumber=".$_POST['ecnumber']."&paymt_amt=".$_POST['epaymtamt']."&remarks=".$_POST['eremarks']."&case_id=".$_POST['case_id']."&update_id=".$eid.'&client_email='.$_POST['client_email'].'&payment_status='.$_POST['payment_status'];

        $json = file_get_contents($url);
        $data12 = json_decode($json, TRUE);
        if(isset($data12['data'][0]['pdf_file_name'])){
          echo "<script>alert('Updated invoice created successfully')</script>";
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

     if(isset($_POST['add_payment']))
     {
     
      $url = str_replace("cases_payment.php","api/v2/client_bill_generate.php",$actual_link)."?lawyer_id=".$_SESSION['user_id']."&cases_no=".$_POST['case_number']."&paymt_dt=".$_POST['paymt_date']."&paymt_mode=".$_POST['paymtmode']."&cnumber=".$_POST['cnumber']."&paymt_amt=".$_POST['paymtamt']."&remarks=".$_POST['remarks']."&case_id=".$_POST['case_id'].'&client_email='.$_POST['client_email'].'&payment_status='.$_POST['payment_status'];


      $json = file_get_contents($url);
      $data12 = json_decode($json, TRUE);
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

   ?>
<!-- manage free slot start -->
<section class="manage_freeslot">
   <div class="container-fluid">
      <div class="row">
         <div class="col-xs-12">
           <?php include("sidebar.php"); ?>
            <div class="col-sm-9 col-xs-12">
               <div class="right_panel">
                  <div class="row">
                     <div class="col-xs-12 title_stripe">
                        <h3>Case Payments</h3>
                           <b class="add_new total_count_view">Loading...</b>
                     </div>
                  </div>
                  <hr>  
                  
   
   <div class="row interactions">
      <div class="col-md-12">
         <!-- <ul class="nav nav-tabs" role="tablist">

            <li class="nav-item active">
               <a data-toggle="tab" href="#all" role="tab">Case Payment</a>
            </li>
         </ul> -->
      <div class="tab-content">

<!--all-->
<div class="tab-pane fade in active" id="all" role="tabpanel">
   <div class="table-responsive action_table dataTables_wrapper">
        
        <?php
          $item_per_page = 10;
          $string_query = "";
          if(isset($_SESSION['cases_all_search_payment']) && $_SESSION['cases_all_search_payment'] != ""){

            $search_result12 = strtoupper($_SESSION['cases_all_search_payment']);
            set_error_handler (
                function($errno, $errstr, $errfile, $errline) {
                    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);     
                }
            );

            try {
              $date_query = date_create($search_result12);
              //date query search not availble
              $date_query = "  OR orderby_date = '".date_format($date_query,"Y-m-d")."'";
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
              $date_vs1=  $search_result12;
              $date_vs2 = $search_result12;
            }

            $string_query = " AND (court_name like '%".$search_result12."%' OR case_number like '%".$search_result12."%' OR client_name like '%".$search_result12."%' OR client_phone like '%".$search_result12."%' OR category like '%".$search_result12."%' OR paymt_status like '%".$search_result12."%')";
          }

          $lawyerqry = "SELECT COUNT(case_id) FROM tbl_cases WHERE lawyer_id IN (SELECT lawyer_id FROM tbl_lawyers WHERE lawyer_id = '".$_SESSION['user_id']."' OR firm_id = '".$_SESSION['user_id']."') ".$string_query." ORDER BY case_id DESC";
          $lawyerresults = mysqli_query($conn,$lawyerqry);

          $get_total_rows = mysqli_fetch_array($lawyerresults); 
          $pages = ceil($get_total_rows[0]/$item_per_page);
        ?>
        <input type="hidden" id="total_cases_available" value="<?php echo $get_total_rows[0]; ?>"></input>

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
            <input type="search" name="search_value" value="<?php echo $_SESSION['cases_all_search_payment']; ?>" placeholder="Search here" aria-controls="attendenceDetailedTable"></input>
            <input type="submit" value="Search" class="btn btn-dark btn-sm"></input>
          </form>
        </div>
          <thead>    
               <th>Case Number</th>
               <th>Start Date</th>
               <th>Client Name</th>
               <th>Court Name</th>
               <th>Category</th>
               <th>Client Phone</th>
               <th>Payment Status</th>
               <th>Action</th>
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
   </div>
   </div>


<!-- History modal open -->
<div class="zind modal fade" id="case_history">
  <div class="modal-dialog">
     <div class="modal-content add_ajax_contain">
     </div>
  </div>
</div>
<!-- History modal open -->

<!-- update payment start -->
<div class="zind modal fade" id="updatepayment">
  <div class="modal-dialog">
     <div class="modal-content">
        <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
        <div class="modal-header">
           <h3>Update Case Payment</h3>
        </div>
        <div class="modal-body">
           <form class="form" role="form" id="update_case_payment1" method="post" action="">
            <div class="row">
              <input type="hidden" class="update_eid" name="eid" value="">
              <input type="hidden" class="update_email" name="client_email" value="">
              <input type="hidden" name="edit_payment"></input>
                <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                       <input class="form-control update_ecase_number" readonly name="ecase_number" placeholder="Case Number" type="text" value="">

                       <input name="case_id" class="update_case_id" type="hidden" value="">
                    </div>
                 </div>
                 <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                       <input class="form-control example1 update_epaymt_date" name="epaymt_date" placeholder="Payment Date" type="text" autocomplete="off" value="">
                       
                    </div>
                 </div>
                  <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                       <select class="form-control edit_paymtmode" name="epaymtmode">
                       <option>Cash</option>
                       <option>RTGS</option>
                       <option>Cheque</option>
                     </select>
                    </div>
                 </div>
                 <div class="col-sm-6 col-xs-12" style="display: none;">
                    <div class="form-group">
                        <select name="payment_status" class="form-control">
                          <option value="">-- Select payment status --</option>
                          <option>Pending</option>
                          <option>Paid</option>
                        </select>
                    </div>
                 </div>
                 <div class="col-sm-6 col-xs-12 edit_paymtmode_hide" style="display: none;">
                    <div class="form-group">
                       <input class="form-control update_ecnumber" name="ecnumber" placeholder="Cheque Number" type="text" value="">
                    </div>
                 </div>
                 <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                       <input class="form-control update_epaymtamt" name="epaymtamt" placeholder="Payment Amount" type="text" value="">
                    </div>
                 </div>
                 <div class="col-sm-6 col-xs-12">
                    <div class="form-group">
                        <textarea name="eremarks" placeholder="Particulars" class="form-control update_eremarks" rows="1"></textarea>
                    </div>
                 </div>
              <div class="form-group col-xs-12">
                 <button type="button" class="btn btn-simple update_to_loading" onclick="update_submit_btn()">Update</button>
              </div>
            </div>
           </form>
        </div>
     </div>
  </div>
</div>
<!-- update payment end -->

<!-- Add Payment -->
<div class="zind modal fade" id="addpayment">
   <div class="modal-dialog">
      <div class="modal-content">
         <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
         <div class="modal-header">
            <h3>Add Payment</h3>
         </div>
         <div class="modal-body">
          <form class="form" id="add_case_payment1" role="form" method="post" action="">
            <div class="row">
               <div class="col-sm-6 col-xs-12">
                <div class="form-group">
                   <input class="form-control case_number_add" name="case_number" placeholder="Case Number" type="text" readonly value="">
                   <input class="case_id_add" name="case_id" type="hidden" value="">
                   <input class="client_email_add" name="client_email" type="hidden" value="">
                   <input name="add_payment" type="hidden" value="">
                </div>
             </div>
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
               <div class="col-sm-6 col-xs-12" style="display: none;">
                  <div class="form-group">
                      <select name="payment_status" class="form-control">
                        <option value="">-- Select payment status --</option>
                        <option>Pending</option>
                        <option>Paid</option>
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
                  <button type="button" onclick="add_submit_btn()" class="btn btn-simple">Add</button>
               </div>

             </div>
            </form>
         </div>
      </div>
   </div>
</div>
<!-- Add Payment Ends-->


<!-- Delete Cases -->
 <div class="zind modal fade" id="delete_lastpayment">
    <div class="modal-dialog">
       <div class="modal-content">
          <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
          <div class="modal-header">
             <h3>Delete Case Payment</h3>
          </div>
          <div class="modal-body1 modal-body">
             <p>Are you sure you want to delete this case payment... </p>
             <div class="del_btn">
                <form action="" method="post">
                   <input class="delete_did" type="hidden" name="did" value="">
                   <button type="submit" class="btn btn-simple" name="delete_id">Yes</button>
                </form>
                <button class="btn btn-simple" data-dismiss="modal">No</button>
             </div>
          </div>
       </div>
    </div>
 </div>
<!-- delete cases end -->


<!-- Mail need to sent or not validation for add and update -->
<div class="zind modal fade" id="mail_conformation">
  <div class="modal-dialog">
     <div class="modal-content">
        <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
        <div class="modal-header">
           <h3>Mail Case Payment</h3>
        </div>
        <div class="modal-body1 modal-body">
           <p>Are you sure you want to send the mail</p>
           <div class="del_btn">
              <form action="" method="post">
                 <button type="button" onclick="submit_value('yes')" class="btn btn-simple">Send Mail</button>
                 <button type="button" onclick="submit_value('no')" class="btn btn-simple">Save Invoice</button>
              </form>
           </div>
        </div>
     </div>
  </div>
</div>

<div class="zind modal fade" id="mail_conformation2">
  <div class="modal-dialog">
     <div class="modal-content">
        <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
        <div class="modal-header">
           <h3>Mail Case Payment</h3>
        </div>
        <div class="modal-body1 modal-body">
           <p>No email address found</p>
           <div class="del_btn">
              <form action="" method="post">
                 <button type="button" onclick="submit_value('no')" class="btn btn-simple">Save Invoice</button>
              </form>
           </div>
        </div>
     </div>
  </div>
</div>


<div class="zind modal fade" id="mail_conformation3">
  <div class="modal-dialog">
     <div class="modal-content">
        <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
        <div class="modal-header">
           <h3>Mail Case Payment</h3>
        </div>
        <div class="modal-body1 modal-body">
           <p>Are you sure you want to send the mail</p>
           <div class="del_btn">
              <form action="" method="post">
                 <button type="button" onclick="submit_value2('yes')" class="btn btn-simple">Send Mail</button>
                 <button type="button" onclick="submit_value2('no')" class="btn btn-simple">Save Invoice</button>
              </form>
           </div>
        </div>
     </div>
  </div>
</div>

<div class="zind modal fade" id="mail_conformation4">
  <div class="modal-dialog">
     <div class="modal-content">
        <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
        <div class="modal-header">
           <h3>Mail Case Payment</h3>
        </div>
        <div class="modal-body1 modal-body">
           <p>No email address found</p>
           <div class="del_btn">
              <form action="" method="post">
                 <button type="button" onclick="submit_value2('no')" class="btn btn-simple">Save Invoice</button>
              </form>
           </div>
        </div>
     </div>
  </div>
</div>
<!-- Mail need to sent or not validation for add and update -->


</section>
<!-- manage free slot end -->
<?php 
   include("footer.php"); 
?>

<script type="text/javascript" src="js/jquery.bootpag.min.js"></script>
<script type="text/javascript">
   $(document).ready(function() {
     $("#results").load("pagination_fetch_pages_payment.php");  //initial page number to load
     $(".pagination").bootpag({
        total: <?php echo $pages; ?>,
        page: <?php echo $_SESSION['cases_all_pagination_page']; ?>,
        maxVisible: 10 
     }).on("page", function(e, num){
       e.preventDefault();
    //   $("#results").html('<div class="loading-indication"><b>Loading...</b></div>');
        $("#results").html('<div class="loading-indication" style="width: 25px;height: 25px"><img src="assets/img/ajax-loader.gif" /></div>');
       $("#results").load("pagination_fetch_pages_payment.php", {'page':num});
     });
   });
</script>

<script type="text/javascript">
  $(document).ready(function(){
    setInterval(function(){

      val = $(".NumberOfCases").val();
      if(typeof val === "undefined"){

      }
      else{
        totalCases = $("#total_cases_available").val();

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


<!-- History View - Start -->
<script type="text/javascript">
  function allcaseshistory(case_id,cases_number){
    $('.case_history_modal').html('<h1>Loading...</h1>');
    $.ajax({
      type:"post",
      data:{ case_id : case_id, cases_number : cases_number , action : 'case_all_history'},
      url:"ajax_all.php",
      success:function(rec){
        $('.case_history_modal').html(rec);
      }
    })
    $('#case_historyd').modal('show');
  }
</script>

<script type="text/javascript">
  function status_Change(id){

    status = $(".change_status"+id).text();
    $(".change_status"+id).text('Updating..');
    $(".change_status"+id).attr("disabled", true);  


    var object = new Object();
    object.id = id;
    object.status = status;
    object.action = 'update_payment_status';
    object.uniqueid = '<?php echo sha1($_SESSION['user_type'].$_SESSION['user_id']."dsfg21"); ?>';

    $.post("ajax_all.php", object, function(data){
      var str = data;
      if(str.includes("success")){
        if(status == 'Pending'){
          $(".change_status"+id).text('Paid');
          $(".change_action_visibility"+id).css("display", "none");
        }
        else if(status == 'Paid'){
          $(".change_status"+id).text('Pending');
          $(".change_action_visibility"+id).css("display", "block");
        }
        $(".change_status"+id).attr("disabled", false); 
      }
      else{
        alert(data);
        if(status == 'Pending'){
          $(".change_status"+id).text(status);
          $(".change_action_visibility"+id).css("display", "block");
        }
        else if(status == 'Paid'){
          $(".change_status"+id).text(status);
          $(".change_action_visibility"+id).css("display", "none");
        }
        $(".change_status"+id).attr("disabled", false); 
      }

    });

  }
</script>

<script type="text/javascript">
  function add_payment_button(){
    alert('test');
  }
</script>

<script type="text/javascript">
  function update_payment_button(){
    alert('test');
  }
</script>

<script type="text/javascript">
  function delete_payment_button(){
    alert('test');
  }
</script>

<script type="text/javascript">
function open_payment_history(case_id,case_number){
  $('.add_ajax_contain').html('<h1>Loading...</h1>');
    $.ajax({
      type:"post",
      data:{ case_number : case_number , case_id : case_id, action : 'payment_details'},
      url:"ajax_all.php",
      success:function(rec){
        $('.add_ajax_contain').html(rec);
      }
    })
    $('#case_history').modal('show');
}
</script>


<script type="text/javascript">
function update_payment_button(id, case_number, case_id, client_email){
  $(".update_eid").val(id);
  $(".update_ecase_number").val(case_number);
  $(".update_case_id").val(case_id);

  $(".update_epaymt_date").val('');
  $(".update_ecnumber").val('');
  $(".update_epaymtamt").val('');
  $(".update_email").val('');
  $(".update_eremarks").html('');
  $(".update_to_loading").html('Loading...');
  $(".update_to_loading").attr('type','button');

  $.ajax({
    type:"post",
    data:{ id : id, action : 'payment_update'},
    url:"ajax_all.php",
    success:function(rec){
      var obj = JSON.parse(rec);
      $(".update_epaymt_date").val(obj.paymt_dt);
      $(".update_ecnumber").val(obj.cnumber);
      $(".update_epaymtamt").val(obj.paymt_amt);
      $(".update_eremarks").html(obj.remarks);
      $(".update_to_loading").html('UPDATE');
      $(".update_to_loading").attr('type','button');
    }
  })
  $(".update_email").val(client_email);
  $('#updatepayment').modal('show');
}
</script>

<script type="text/javascript">

  function add_payment_button(case_number, case_id, client_email){
    $(".case_number").val('');
    $(".case_id_add").val('');
    $(".client_email_add").val('');
    $(".case_number_add").val(case_number);
    $(".case_id_add").val(case_id);
    $(".client_email_add").val(client_email);
    $('#addpayment').modal('show');
  }
</script>

<script type="text/javascript">
function delete_payment_button(did)
{
  $(".delete_did").val(did);
  $('#delete_lastpayment').modal('show');
}
</script>

<script type="text/javascript">
  function add_submit_btn(){
    val = $(".client_email_add").val();
    if(val != ""){
      $('#mail_conformation').modal('show');
    }
    else{
      $('#mail_conformation2').modal('show');
    }
  }

  function update_submit_btn(){
    val = $(".update_email").val();
    if($(".update_to_loading").html() == "UPDATE"){
      if(val != ""){
        $('#mail_conformation3').modal('show');
      }
      else{
        $('#mail_conformation4').modal('show');
      }
    }
  }

  function submit_value(val){
    if(val == "yes"){
      $("#add_case_payment1").submit();
    }
    else{
      $(".client_email_add").val('');
      $("#add_case_payment1").submit();
    }
  }

  function submit_value2(val){
    if(val == "yes"){
      $("#update_case_payment1").submit();
    }
    else{
      $(".update_email").val('');
      $("#update_case_payment1").submit();
    }
  }
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
  $(".edit_paymtmode").change(function(){
    val = $(".edit_paymtmode").val();
    if(val != "Cheque"){
      $(".edit_paymtmode_hide").hide();
    }
    else{
      $(".edit_paymtmode_hide").show();
    }
  })
</script>