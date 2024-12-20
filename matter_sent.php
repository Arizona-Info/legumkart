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
    $_SESSION['matter_sent_search'] = $_POST['search_value'];
    $_SESSION['matter_sent_pagination_page'] = 1;
 }
 else if(!isset($_SESSION['matter_sent_search'])){
    $_SESSION['matter_sent_search'] = "";
    $_SESSION['matter_sent_pagination_page'] = 1;
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
                        <h3>Matters Sent</h3>
                        <b class="add_new total_count_view">Loading...</b>
                     </div>
                  </div>

                  <div class="table-responsive dataTables_wrapper">
                     <table id="" class="display dataTable responsive nowrap" cellspacing="0" width="100%">
                        <?php 
                        $item_per_page = 10;
                        $pages_matter_receive = 0;
                        $string_query = "";


                        if(isset($_SESSION['matter_sent_search']) && $_SESSION['matter_sent_search'] != ""){
                              $search_result12 = strtoupper($_SESSION['matter_sent_search']);
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
                              if(strpos('VIEW', $search_result12 ) !== false){
                                $data_vs1 = " OR (t1.lc_bill_generate != '' AND t1.lc_status = 'Accepted')";
                              }

                              $string_query = " AND (t1.lc_status like '%".$search_result12."%' OR t3.email like '%".$search_result12."%' OR t3.phone like '%".$search_result12."%' OR t3.firm_name like '%".$search_result12."%' OR t2.court_name like '%".$search_result12."%' OR t2.court_number like '%".$search_result12."%' OR t2.judge_name like '%".$search_result12."%' OR t2.case_number like '%".$search_result12."%' OR t2.stage like '%".$search_result12."%' OR t2.client_name like '%".$search_result12."%' OR t2.party_b like '%".$date_vs2."%' OR t2.party_a like '%".$date_vs2."%' OR t2.party_b like '%".$date_vs1."%' OR t2.party_a like '%".$date_vs1."%' ".$date_query.$data_vs1.")";
                        }

                         if($emp_ids != '') 
                          {
                               $lawyerqry = mysqli_query($conn,"SELECT t3.email,t3.phone,t3.firm_name,t1.lc_status, t1.lc_bill_generate, t1.lc_lawyer_id, t1.lc_flag, t1.lc_id, t1.lc_next_date, t2.case_number, t2.case_id, t2.court_name, t2.court_number, t2.judge_name, t2.client_name, t2.party_a, t2.party_b, t2.stage FROM tbl_lawyer_cases t1, tbl_cases t2, tbl_lawyers t3 WHERE t1.lc_case_id=t2.case_id AND t2.lawyer_id IN ($emp_ids) AND t3.lawyer_id=t1.lc_lawyer_id ".$string_query);
                          }
                        else
                          {               
                               $lawyerqry = mysqli_query($conn,"SELECT t3.email,t3.phone,t3.firm_name,t1.lc_status, t1.lc_bill_generate, t1.lc_lawyer_id, t1.lc_flag, t1.lc_id, t1.lc_next_date, t2.case_number, t2.case_id, t2.court_name, t2.court_number, t2.judge_name, t2.client_name, t2.party_a, t2.party_b, t2.stage FROM tbl_lawyer_cases t1, tbl_cases t2, tbl_lawyers t3 WHERE t1.lc_case_id=t2.case_id AND t2.lawyer_id='".$_SESSION['user_id']."' AND t3.lawyer_id=t1.lc_lawyer_id ".$string_query);
                          }

                        $total_cases = mysqli_num_rows($lawyerqry);
                        $pages_matter_sent = ceil($total_cases/$item_per_page);
                      ?>
                      <input type="hidden" id="total_matter_sent" value="<?php echo $total_cases; ?>"></input>

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
                          <input type="search" name="search_value" value="<?php echo $_SESSION['matter_sent_search']; ?>" placeholder="Search here" aria-controls="attendenceDetailedTable"></input>
                          <input type="submit" value="Search" class="btn btn-dark btn-sm"></input>
                        </form>
                      </div>

                        <thead>
                           <tr>
                              <!-- <th>Prev Date</th> -->
                              <th></th>
                              <th>Case No.</th>
                              <th>Court Details</th>
                              <th>Client Name</th>
                              <th>Status</th>
                              <!-- <th>Name of Parties</th>
                              <th>Stage</th>
                              <th>Next Date</th>
                              <th>Sent To</th>
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
</section>

<div class="zind modal fade" id="rlawyer_details">
  <div class="modal-dialog">
     <div class="modal-content">
        <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
           <div class="modal-header">
              <h3><i class="pe-7s-users"></i> Lawyer Details</h3>
           </div>
           <div class="modal-body">
              <table class="table table-bordered">
                 <thead>
                    <tr>
                       <th>Phone Number</th>
                       <th>Email Id</th>
                    </tr>
                 </thead>
                 <tbody>
                    <tr>
                       <td id="lawyer_details_phone"></td>
                       <td id="lawyer_details_email"></td>
                    </tr>
                 </tbody>
              </table>
           </div>
     </div>
  </div>
</div>


<div class="zind modal fade" id="court_details">
      <div class="modal-dialog">
         <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
               <div class="modal-header">
                  <h3><i class="pe-7s-users"></i> Court Details</h3>
               </div>
               <div class="modal-body">
                  <table class="table table-bordered">
                     <thead>
                        <tr>
                           <th>Court Number</th>
                           <th>Judge Name</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                            <td id="court_details_phone"></td>
                            <td id="court_details_email"></td>
                        </tr>
                     </tbody>
                  </table>
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

    var counsel = setInterval(function(){
        $("#results").load("pagination_fetch_pages_matter_sent.php",{limit: 25}, 
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
        total: <?php echo $pages_matter_sent; ?>,
        page: <?php echo $_SESSION['matter_sent_pagination_page']; ?>,
        maxVisible: 10 
     }).on("page", function(e, num){
       e.preventDefault();
    //   $("#results").html('<div class="loading-indication"><b>Loading...</b></div>');
        $("#results").html('<div class="loading-indication" style="width: 25px;height: 25px"><img src="assets/img/ajax-loader.gif" /></div>');
       $("#results").load("pagination_fetch_pages_matter_sent.php", {'page':num});
     });
   });
</script>

<script type="text/javascript">
  function lawyerDetails(phone, email){
    $("#lawyer_details_phone").text(phone);
    $("#lawyer_details_email").text(email);
    $("#rlawyer_details").modal('show');
  }
</script>

<script type="text/javascript">
  function courtDetails(court_number, judge_name){
    $("#court_details_phone").text(court_number);
    $("#court_details_email").text(judge_name);
    $("#court_details").modal('show');
  }
</script>

<script type="text/javascript">
  $(document).on('click','.row_click',function(){
    id = $(this).attr('id')
    id_number = id.split('change_color');
    color = $("#"+id+" .action").css( "color");

    if(color == "rgb(255, 0, 0)"){
      $.ajax({
        type: "post",
        url: "ajax_notification.php",
        data: { id : id_number[1] , field : "matter_send"},
        success:function(rec){
          $("#"+id+" .action").css( "color" , "black" );
          $("#"+id).css( "cursor" , "context-menu" );
          
          pre_count = $("#matter_sent_update").html();
          pre_count = pre_count.replace('(', '');
          pre_count = pre_count.replace(')', '');
          pre_count = Number(pre_count)-1;
          if(pre_count>0){
            $("#matter_sent_update").html("("+pre_count+")");
          }
          else{
            $("#matter_sent_update").html("");
          }

          pre_count = $("#matter_all_update").html();
          pre_count = pre_count.replace('(', '');
          pre_count = pre_count.replace(')', '');
          pre_count = Number(pre_count)-1;
          if(pre_count>0){
            $("#matter_all_update").html("("+pre_count+")");
          }
          else{
            $("#matter_all_update").html("");
          }
        }
      })
    }
    
  })
</script>

<script type="text/javascript">
  $(document).ready(function(){
    setInterval(function(){

      val = $(".NumberOfCases").val();
      if(typeof val === "undefined"){

      }
      else{
        totalCases = $("#total_matter_sent").val();

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