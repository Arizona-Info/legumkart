<?php 
   include("header.php"); 
   $page = 'lawyer_counselor.php';
   if(!isset($_SESSION['user_id']))
   {
     echo  '<script>window.location="index.php"</script>';
   }

   if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] == "Counsel" || $_SESSION['user_type'] == "Intern")
   {
     echo  '<script>window.location="index.php"</script>';
   }

$emp_ids ='';

if(isset($_POST['search_value'])){
    $_SESSION['councel_search'] = $_POST['search_value'];
    $_SESSION['counsel_pagination_page'] = 1;
 }
 else if(!isset($_SESSION['councel_search'])){
    $_SESSION['councel_search'] = "";
    $_SESSION['counsel_pagination_page'] = 1;
 }


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
?>
<!-- manage free slot start -->
<section class="manage_freeslot">
   <div class="container-fluid">
      <div class="row">
         <div class="col-xs-12">
           <?php include("sidebar.php"); ?>
            <div class="col-md-9 col-sm-9 col-xs-12">
               <div class="right_panel">
                  <!-- <h3>Lawyer-Counsel Cases Diary</h3>
                  <b class="add_new total_count_view">Loading...</b> -->
                  <div class="row">
                     <div class="col-xs-12 title_stripe">
                        <h3>Lawyer-Counsel Cases Diary</h3>
                           <b class="add_new total_count_view">Loading...</b>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-xs-12">
                        <div class="table-responsive dataTables_wrapper">
                           <table id="" class="display dataTable responsive nowrap" cellspacing="0" width="100%">

                              <?php
                              $item_per_page = 10;
                              $pages_today = 0;
                              $string_query = "";

            if(isset($_SESSION['councel_search']) && $_SESSION['councel_search'] != ""){

            $search_result12 = strtoupper($_SESSION['councel_search']);

            if($search_result12 == "AVAILABLE"){
              $search_result12 = "Accepted";
            }

            set_error_handler (
                function($errno, $errstr, $errfile, $errline) {
                    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);     
                }
            );

            try {
              $date_query = date_create($search_result12);
              $date_query = "  OR t1.cc_next_date = '".date_format($date_query,"Y-m-d")."'";
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

            $string_query = " AND (t2.court_name like '%".$search_result12."%' OR t2.judge_name like '%".$search_result12."%' OR t1.cc_type like '%".$search_result12."%' OR t1.cc_status like '%".$search_result12."%' OR t2.party_b like '%".$date_vs2."%' OR t2.party_a like '%".$date_vs2."%' OR t2.party_b like '%".$date_vs1."%' OR t2.party_a like '%".$date_vs1."%' ".$date_query.")";
          }

                              if($emp_ids != '') 
                              {
                                $counselcases = mysqli_query($conn,"SELECT t1.*, t2.* FROM tbl_counsel_cases t1, tbl_cases t2 WHERE t1.cc_case_id=t2.case_id AND t2.lawyer_id IN ($emp_ids)".$string_query);
                              }
                              else
                              {
                                $counselcases = mysqli_query($conn,"SELECT t1.*, t2.* FROM tbl_counsel_cases t1, tbl_cases t2 WHERE t1.cc_case_id=t2.case_id  AND t2.lawyer_id='".$_SESSION['user_id']."'".$string_query);
                              }

                              $total_counsel_cases = mysqli_num_rows($counselcases);
                              $pages_today = ceil($total_counsel_cases/$item_per_page);
                             ?>
                             <input type="hidden" id="total_counsel_cases_available" value="<?php echo $total_counsel_cases; ?>"></input>


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
                                    <input type="search" name="search_value" value="<?php echo $_SESSION['councel_search']; ?>" placeholder="Search here" aria-controls="attendenceDetailedTable"></input>
                                    <input type="submit" value="Search" class="btn btn-dark btn-sm"></input>
                                  </form>
                                </div>

                              <thead>
                                 <tr>
                                    <th></th>
                                    <th>Counsel Name</th>
                                    <th>Court Name</th>
                                    <th>Judge Name</th>
                                    <th>Status</th>
                                    <!-- <th>Court Hearing Date</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Bill</th> -->
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

<marquee>Neither your receipt of information from this website, nor your use of this website. The content of this website is intended to convey general information. You should not send us any confidential information in response to this webpage. Such responses will not create a lawyer-client relationship, and whatever you disclose to us will not be privileged or confidential unless we have agreed to act as your legal counsel and you have executed a written engagement agreement.
</marquee> 


<!-- manage free slot end -->
<?php 
   include("footer.php"); 
?>

<script type="text/javascript" src="js/jquery.bootpag.min.js"></script>
<script type="text/javascript">
   $(document).ready(function() {

    var counsel = setInterval(function(){
        $("#results").load("pagination_fetch_pages_lawyer_counselor.php",{limit: 25}, 
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
        total: <?php echo $pages_today; ?>,
        page: <?php echo $_SESSION['counsel_pagination_page']; ?>,
        maxVisible: 10 
     }).on("page", function(e, num){
       e.preventDefault();
    //   $("#results").html('<div class="loading-indication"><b>Loading...</b></div>');
        $("#results").html('<div class="loading-indication" style="width: 25px;height: 25px"><img src="assets/img/ajax-loader.gif" /></div>');
       $("#results").load("pagination_fetch_pages_lawyer_counselor.php", {'page':num});
     });
   });
</script>


<script type="text/javascript">
  $(document).on("click",".row_click",function(){
    id = $(this).attr('id')
    id_number = id.split('change_color');
    color = $("#"+id+" .action").css( "color");

    if(color == "rgb(255, 0, 0)"){
      $.ajax({
        type: "post",
        url: "ajax_notification.php",
        data: { id : id_number[1] , field : "counsel_receiv"},
        success:function(rec){
          $("#"+id+" .action").css( "color" , "black" );
          $("#"+id).css( "cursor" , "context-menu" );
          
          pre_count = $("#councel_update").html();
          pre_count = pre_count.replace('(', '');
          pre_count = pre_count.replace(')', '');
          pre_count = Number(pre_count)-1;
          if(pre_count>0){
            $("#councel_update").html("("+pre_count+")");
          }
          else{
            $("#councel_update").html("");
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
        totalCases = $("#total_counsel_cases_available").val();

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