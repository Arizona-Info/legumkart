<?php 
   $page = 'internship_request.php';
   include("header.php"); 
   $string_query = "";
   
   if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'Intern')
   {
     echo  '<script>window.location="index.php"</script>';
   }


   if(isset($_POST['search_value'])){
      $_SESSION['job_search'] = $_POST['search_value'];
      $_SESSION['jobs_pagination_page'] = 1;
   }
   else if(!isset($_SESSION['job_search'])){
      $_SESSION['job_search'] = "";
      $_SESSION['jobs_pagination_page'] = 1;
   }

   $sel_inter = mysqli_query($conn,"SELECT intern_location, intern_interest FROM tbl_intern WHERE intern_id = '".$_SESSION['user_id']."'");
   $sel_val = mysqli_fetch_assoc($sel_inter);

   $condition1 = "";
   $val_spe2 = explode(",",$sel_val['intern_location']);
   foreach ($val_spe2 as $key => $value) {
      $value = ltrim($value);
      $value = rtrim($value);
      if($value != ""){
        $condition1 .= " location LIKE '%".$value."%' OR";
      }
    }

    $condition1 = chop($condition1,"OR");

   $condition2 = "";
   $val_spe2 = explode(",",$sel_val['intern_interest']);
   foreach ($val_spe2 as $key => $value) {
      $value = ltrim($value);
      $value = rtrim($value);
      if($value != ""){
        $condition2 .= " specialization LIKE '%".$value."%' OR";
      }
    }
    $condition2 = chop($condition2,"OR");

    if($condition2 != "" && $condition1 != ""){
      $condition1 = $condition2." or ".$condition1;
    }
    else if($condition2 != ""){
      $condition1 = $condition2;
    }



    if($condition1 != ""){
      $condition1 = "(".$condition1.")";
    }
    else{
      $condition1 = "id = '-1'";
    }
    $_SESSION['condition1'] = $condition1;
   
if(isset($_SESSION['job_search']) && $_SESSION['job_search'] != ""){
    $search_result12 = strtoupper($_SESSION['job_search']);
    set_error_handler (
        function($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, $errno, 0, $errfile, $errline);     
        }
    );

    try {
      $date_query = date_create($search_result12);
      $date_query = "  OR date like '%".date_format($date_query,"Y-m-d")."%'";
    }
    catch(Exception $e) {
      $date_query = "";
    }
    
    $string_query = " AND (location like '%".$search_result12."%' OR specialization like '%".$search_result12."%' OR description like '%".$search_result12."%'".$date_query.")";
}
   ?>
<!-- manage free slot start -->
<section class="manage_freeslot">
   <div class="container-fluid">
      <div class="row">
         <div class="col-xs-12">
            <?php include("sidebar2.php"); ?>
            <div class="col-md-9 col-sm-9 col-xs-12">
               <div class="right_panel">
                 <div class="row">
                     <div class="col-xs-12 title_stripe">
                        <h3>Posted Job</h3>
                           <b class="add_new total_count_view">Loading...</b>
                     </div>
                  </div>
                  <hr>

                      <div class="row interactions">
                         <div class="col-md-12">
                            <div class="table-responsive dataTables_wrapper">
                               <?php

                                 $sr = 1;
                                 $item_per_page = 10;
                                 $str = "SELECT * FROM tbl_post_job WHERE ".$condition1.$string_query." ORDER BY date desc";

                                 $sel_jobs = mysqli_query($conn,$str);
                                 $total_count = mysqli_num_rows($sel_jobs);
                                $pages = ceil($total_count/$item_per_page);

                               ?>
                               <input type="hidden" id="total_cases_available" value="<?php echo $total_count; ?>"></input>

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
                                  <input type="search" name="search_value" value="<?php echo $_SESSION['job_search']; ?>" placeholder="Search here" aria-controls="attendenceDetailedTable"></input>
                                  <input type="submit" value="Search" class="btn btn-dark btn-sm"></input>
                                </form>
                              </div>
                                <thead>
                                   <tr>
                                      <th>Lawyer/Firm</th>
                                      <th>Jobs Description</th>
                                      <th>Posted Date</th>
                                   </tr>
                                </thead>
                                <tbody id="results">

                                </tbody>
                             </table>
                              <div class="dataTables_paginate paging_simple_numbers pagination" id=""></div>
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
</section>


<div class="zind modal fade" id="details1">
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
                      <th>Type</th>
                      <!-- <th>Phone</th> -->
                      <th>Email</th>
                   </tr>
                </thead>

                <tbody>
                   <tr>
                      <td id="lawyerType"></td>
                      <!-- <td id="lawyerPhone"></td> -->
                      <td id="lawyerEmail"></td>
                   </tr>                                                            
                </tbody>
             </table>
          </div>
       </div>
    </div>
 </div>


 <div class="zind modal fade" id="details2">
    <div class="modal-dialog">
       <div class="modal-content">
          <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
          <div class="modal-header">
             <h3><i class="pe-7s-users"></i> Jobs Description</h3>
          </div>
          <div class="modal-body">
             <table class="table table-bordered">
                <thead>
                   <tr>
                      <th>Location</th>
                      <th>Specialization</th>
                      <th>Description</th>
                   </tr>
                </thead>
                <tbody>
                   <tr>
                      <td id="jobsLocation"></td>
                      <td id="jobsSpec"></td>
                      <td id="jobDesc"></td>
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
     $("#results").load("pagination_fetch_pages_internship_request.php");  //initial page number to load
     $(".pagination").bootpag({
        total: <?php echo $pages; ?>,
        page: <?php echo $_SESSION['jobs_pagination_page']; ?>,
        maxVisible: 10 
     }).on("page", function(e, num){
       e.preventDefault();
    //   $("#results").html('<div class="loading-indication"><b>Loading...</b></div>');
        $("#results").html('<div class="loading-indication"><img src="assets/img/ajax-loader.gif" /></div>');
       $("#results").load("pagination_fetch_pages_internship_request.php", {'page':num});
     });
   });
</script>

<script type="text/javascript">
  function lawyerDetails(type,email){
    $("#lawyerType").text(type);
    // $("#lawyerPhone").text(phone);
    $("#lawyerEmail").text(email);
    $("#details1").modal('show');
  }

  function jobDetails(location, specialization, description){

    var substr = location.split(",");
    var arrayData = "";
    for (i = 0; i < substr.length; ++i) {
      arrayData = arrayData + "<li>" + substr[i] + "</li>";
    }
    $("#jobsLocation").html(arrayData);

    var substr2 = specialization.split(",");
    var arrayData2 = "";
    for (j = 0; j < substr2.length; ++j) {
        arrayData2 = arrayData2 + "<li>" + substr2[j] + "</li>";
    }
    $("#jobsSpec").html(arrayData2);
    $("#jobDesc").text(description);
    $("#details2").modal('show');
  }
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