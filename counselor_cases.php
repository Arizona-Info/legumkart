<?php 
   include("header.php"); 
   $page = 'counselor_cases.php';
   $item_per_page = 10;
   $string_query = "";

   if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'Counsel')
   {
     echo  '<script>window.location="index.php"</script>';
   }

   if(isset($_POST['save_conference']))
   {
   $stmt = mysqli_query($conn,"UPDATE tbl_counsel_cases SET cc_flag = '0',cc_date = '".$_POST['con_date']."',cc_time= '".$_POST['con_time']."',cc_place = '".$_POST['con_location']."',cc_status = 'Accepted' WHERE cc_id='".$_POST['casee_id']."'");
   }

   if(isset($_POST['update_flage2']))
   {
   $stmt = mysqli_query($conn,"UPDATE tbl_counsel_cases SET cc_flag = '0',cc_status = 'Accepted', cc_date = '".$_POST['date_update']."' WHERE cc_id='".$_POST['casee_id']."'");
   }

   if(isset($_POST['update_flage']))
   {
   $stmt = mysqli_query($conn,"UPDATE tbl_counsel_cases SET cc_flag = '0',cc_status = 'Not Available' WHERE cc_id='".$_POST['casee_id']."'");
   }

   if(isset($_POST['search_value'])){
      $_SESSION['counselor_cases_search'] = $_POST['search_value'];
      $_SESSION['counselor_cases_pagination_page'] = 1;
   }
   else if(!isset($_SESSION['counselor_cases_search'])){
      $_SESSION['counselor_cases_search'] = "";
      $_SESSION['counselor_cases_pagination_page'] = 1;
   }

   if(isset($_SESSION['counselor_cases_search']) && $_SESSION['counselor_cases_search'] != ""){
      $search_result12 = strtoupper($_SESSION['counselor_cases_search']);
      set_error_handler (
          function($errno, $errstr, $errfile, $errline) {
              throw new ErrorException($errstr, $errno, 0, $errfile, $errline);     
          }
      );

      try {
        $date_query = date_create($search_result12);
        $date_query = "  OR cc_next_date = '".date_format($date_query,"Y-m-d")."'";
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
      
      $data_1 = "";
      if(strpos("NO", $search_result12) !== false){
        $data_1 = " OR cc_status = 'Not Available'";
      }

      $string_query = " AND (judge_name like '%".$search_result12."%' OR court_name like '%".$search_result12."%' OR court_number like '%".$search_result12."%' OR party_b like '%".$date_vs2."%' OR party_a like '%".$date_vs2."%' OR party_b like '%".$date_vs1."%' OR party_a like '%".$date_vs1."%' OR cc_type like '%".$search_result12."%'".$date_query.$data_1.")";
  }

?>
<!-- manage free slot start -->
<section class="manage_freeslot">
   <div class="container-fluid">
      <div class="row">
         <div class="col-xs-12">
           <?php include("sidebar1.php"); ?>
            <div class="col-md-9 col-sm-9 col-xs-12">
               <div class="right_panel">
                  <div class="row">
                     <div class="col-xs-12 title_stripe">
                        <h3>Counsel Lawyer Cases</h3>
                           <b class="add_new total_count_view">Loading...</b>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-xs-12">
                        <div class="table-responsive dataTables_wrapper">

                        <?php 

                          $counselcases = mysqli_query($conn,"SELECT t1.cc_case_id FROM tbl_counsel_cases t1, tbl_cases t2 WHERE t1.cc_case_id=t2.case_id AND t1.cc_status!='Accepted' AND t1.counsel_id = '".$_SESSION['user_id']."'".$string_query);

                          $get_total_rows = mysqli_num_rows($counselcases); 
                          $pages = ceil($get_total_rows/$item_per_page);
                        ?>

                          <input type="hidden" id="total_cases_available" value="<?php echo $get_total_rows; ?>"></input>

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
                              <input type="search" name="search_value" value="<?php echo $_SESSION['counselor_cases_search']; ?>" placeholder="Search here" aria-controls="attendenceDetailedTable"></input>
                              <input type="submit" value="Search" class="btn btn-dark btn-sm"></input>
                            </form>
                          </div>
                              <thead>
                                 <tr>
                                    <!-- <th>Sr. No.</th> -->
                                    <th>Lawyer</th>
                                    <th>Judge Name</th>
                                    <th>Court Name</th>
                                    <th>Court Hearing Date/Time</th>
                                    <th>Parties</th>
                                    <th>Type</th>
                                    <th>Status</th>
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
</section>

<marquee>Neither your receipt of information from this website, nor your use of this website. The content of this website is intended to convey general information. You should not send us any confidential information in response to this webpage. Such responses will not create a lawyer-client relationship, and whatever you disclose to us will not be privileged or confidential unless we have agreed to act as your legal counsel and you have executed a written engagement agreement.
</marquee>  


<div class="zind modal fade" id="firm_detailss">
  <div class="modal-dialog">
    <div class="modal-content">
      <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
      <div class="modal-header">
        <h3>Lawyer Details</h3>
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
          </div>
      </div>

      </div>

    </div>
  </div>
</div>



<div class="zind modal fade" id="counselyes_conference">
  <div class="modal-dialog">
     <div class="modal-content">
        <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
        <div class="modal-header">
           <h3><i class="pe-7s-users"></i> Conference Request</h3>
        </div>
        <div class="modal-body">
           <form class="form" role="form" method="post" action=""  autocomplete="off">
             <input type="hidden" name="casee_id" id="confUniqueId" value="">
              <div class="col-md-12">
                 <div class="form-group">
                    <textarea class="form-control" id="confAddress" name="con_location" placeholder="Conference Location" type="text" required></textarea> 
                 </div>
              </div>

              <div class="col-md-6">
                 <div class="form-group">
                    <input class="form-control example1" name="con_date" placeholder="Conference Date" type="text" required>
                 </div>
              </div>

              <div class="col-md-6">
                 <div class="form-group">
                    <input class="form-control scrollDefaultExample" name="con_time" placeholder="Conference Time" type="text" required>
                 </div>
              </div>
              
              
              <div class="form-group">
                 <button type="submit" class="btn btn-simple" name="save_conference">Update</button>
              </div>
           </form>
        </div>
     </div>
  </div>
</div>




<div class="zind modal fade" id="counselyes_hearing">
  <div class="modal-dialog">
     <div class="modal-content">
        <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
        <div class="modal-header">
           <h3><i class="pe-7s-users"></i> Hearing Request</h3>
        </div>
        <div class="modal-body">
           <form class="form" role="form" method="post" action=""  autocomplete="off">
             <input type="hidden" name="casee_id" id="uniqueCaseId" value="">

             <input type="hidden" name="date_update" id="caseDate" value="">
              

              <div class="col-md-12">
                 <div class="form-group">
                    <p align="center">Are you sure you want to accept Firm/Lawyer request.</p>
                 </div>
              </div>
            
              
              <div class="form-group">
                 <button type="submit" class="btn btn-simple" name="update_flage2">Update</button>
              </div>
           </form>
        </div>
     </div>
  </div>
</div>


<div class="zind modal fade" id="counselyes_cancel">
  <div class="modal-dialog">
     <div class="modal-content">
        <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
        <div class="modal-header">
           <h3><i class="pe-7s-users"></i> <span id="cancelHeading"></span></h3>
        </div>
        <div class="modal-body">
           <form class="form" role="form" method="post" action=""  autocomplete="off">
             <input type="hidden" name="casee_id" id="cancelUniqueId" value="">
              
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


<!-- manage free slot end -->
<?php 
   include("footer.php"); 
?>

<script type="text/javascript" src="js/jquery.bootpag.min.js"></script>
<script type="text/javascript">
   $(document).ready(function() {
     $("#results").load("pagination_fetch_pages_counselor_cases.php");  //initial page number to load
     $(".pagination").bootpag({
        total: <?php echo $pages; ?>,
        page: <?php echo $_SESSION['counselor_cases_pagination_page']; ?>,
        maxVisible: 10 
     }).on("page", function(e, num){
       e.preventDefault();
    //   $("#results").html('<div class="loading-indication"><b>Loading...</b></div>');
        $("#results").html('<div class="loading-indication"><img src="assets/img/ajax-loader.gif" /></div>');
       $("#results").load("pagination_fetch_pages_counselor_cases.php", {'page':num});
     });
    })
</script>
<script type="text/javascript">

function yesno(id, uniqueId, type, address, date) 
{
    if (document.getElementById('toggle-on'+id).checked) 
    {
      if(type == "Conference"){
        $("#confUniqueId").val(uniqueId);
        $("#confAddress").val(address);
        $('#counselyes_conference').modal('show');
      }
      else{
        $("#uniqueCaseId").val(uniqueId);
        $("#caseDate").val(date);
        $('#counselyes_hearing').modal('show');
      }
    }
    else 
    {
      if(type == "Conference"){
        $("#confUniqueId").val("");
        $("#confAddress").val("");
        $('#counselyes_conference').modal('hide');
      }
      else{
        $("#uniqueCaseId").val("");
        $("#caseDate").val("");
        $('#counselyes_hearing').modal('hide');
      }
    }


    if (document.getElementById('toggle-off'+id).checked) 
    {
      $("#cancelHeading").text(type + " Request");
      $("#cancelUniqueId").val(uniqueId);
      $('#counselyes_cancel').modal('show');
    }
    else 
    {
      $("#cancelHeading").text("");
      $("#cancelUniqueId").val("");
      $('#counselyes_cancel').modal('hide');
    }
}

function viewLawyerDetails(phone, email){
  $("#lawyerPhone").text(phone);
  $("#lawyerEmail").text(email);
  $("#firm_detailss").modal('show');
}
</script>
<script type="text/javascript" src="js/jquery.timepicker.js"></script>
<script>
    $(function() {
        $('.scrollDefaultExample').timepicker({  'minTime': '9:00am',
    'maxTime': '9:00pm' });
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