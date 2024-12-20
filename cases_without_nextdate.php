<?php 
   $page = 'cases';
   require_once("header.php"); 

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
      $_SESSION['cases_all_search_next_date'] = $_POST['search_value'];
      $_SESSION['cases_all_pagination_page'] = 1;
   }
   else if(!isset($_SESSION['cases_all_search_next_date'])){
      $_SESSION['cases_all_search_next_date'] = "";
      $_SESSION['cases_all_pagination_page'] = 1;
   }

   if(isset($_POST['edit_case']))
   {
   $eid = $_REQUEST['eid'];
   if($_POST['edit_stage']=='other' && $_POST['edit_newstage'] !='')
   {
     $editstage = $_POST['edit_newstage'];
   }
   else
   {
     $editstage = $_POST['edit_stage'];
   }
  
   $query = "";
   if($_REQUEST['nextdt_id'] == ""){
    $query = "judge_name='".$_POST['edit_judge_name']."', next_date='".$_POST['edit_next_date']."',stage='".$editstage."',";
   }

   $valsdg = "";

   $stmt = mysqli_query($conn,"UPDATE tbl_cases SET court_name = '".$_POST['edit_court_name']."',court_number='".$_POST['edit_court_number']."', case_number = '".$_POST['edit_case_number']."', ".$query." category = '".$_POST['edit_category']."',client_name = '".$_POST['edit_client_name']."', client_phone='".$_POST['edit_client_phone']."', client_email='".$_POST['edit_client_email']."', party_a='".$_POST['edit_party_a']."', party_b='".$_POST['edit_party_b']."', file_no ='".$_POST['file_no']."' WHERE case_id='".$eid."'");

   if($_REQUEST['nextdt_id'] != "")
    {
      $new_id = mysqli_query($conn,"SELECT nextdt_id FROM tbl_case_nextdt WHERE next_case_id = '".$eid."' ORDER BY nextdt_id desc");
      $new_id_result = mysqli_fetch_assoc($new_id);
      $nextdt_id = $new_id_result['nextdt_id'];

      $stmt_dtandstage = mysqli_query($conn,"UPDATE tbl_case_nextdt SET next_judge = '".$_POST['edit_judge_name']."', next_stage = '".$editstage."', next_case_date = '".$_POST['edit_next_date']."' WHERE nextdt_id='".$nextdt_id."'");
    }
    $upd_orderby_date1 = mysqli_query($conn,"UPDATE tbl_cases SET orderby_date = '".$_POST['edit_next_date']."' where case_id = '".$eid."'");

   echo  '<script>alert("Case edited successfully")</script>';
   }


   if(isset($_POST['add_next_date']))
    {

      if($_POST['next_stage']=='other' && $_POST['add_newstage'] !='')
      {
        $stage_ad = $_POST['add_newstage'];
      }
      else
      {
        $stage_ad = $_POST['next_stage'];
      }

    $stmt = mysqli_query($conn,"INSERT INTO tbl_case_nextdt(next_case_id, prev_case_date, next_case_date, next_stage, lawyer_id, next_judge) VALUES('".$_POST['next_case_id']."', '".$_POST['prev_case_date']."','".$_POST['next_case_date']."','".$stage_ad."','".$_SESSION['user_id']."','".$_POST['judge_name']."')");

    $upd_orderby_date2 = mysqli_query($conn,"UPDATE tbl_cases SET orderby_date = '".$_POST['next_case_date']."' where case_id = '".$_POST['next_case_id']."'");
    
     echo  '<script>alert("Next Date added successfully")</script>';
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
                        <h3>ADD/EDIT ( NEXT-DATE / STAGE )</h3>
                           <a class="add_new">&nbsp</a>
                           <b class="add_new total_count_view">Loading...</b>
                     </div>
                  </div>
                  <hr>  
                  
   
   <div class="row interactions">
      <div class="col-md-12">
      <div class="tab-content">

<!--all-->
<div class="tab-pane fade in active" id="all" role="tabpanel">
   <div class="table-responsive action_table dataTables_wrapper">
        <?php    

        $item_per_page = 10;
        $string_query = "";
        $string_query2 = "";

        if(isset($_SESSION['cases_all_search_next_date']) && $_SESSION['cases_all_search_next_date'] != ""){

      $search_result12 = strtoupper($_SESSION['cases_all_search_next_date']);
        set_error_handler (
            function($errno, $errstr, $errfile, $errline) {
                throw new ErrorException($errstr, $errno, 0, $errfile, $errline);     
            }
        );

        try {
          $date_query = date_create($search_result12);
          // $date_query = "  OR next_case_date = '".date_format($date_query,"Y-m-d")."'";
          $date_query2 = "  OR orderby_date = '".date_format($date_query,"Y-m-d")."'";
        }
        catch(Exception $e) {
          // $date_query = "";
          $date_query2 = "";
        }

        $date_vs1 = "";
        $date_vs2 = "";
        
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

      $string_query = " AND (court_name like '%".$search_result12."%' OR case_number like '%".$search_result12."%' OR stage like '%".$search_result12."%' OR party_b like '%".$date_vs2."%' OR party_a like '%".$date_vs2."%' OR party_b like '%".$date_vs1."%' OR party_a like '%".$date_vs1."%' ".$date_query2.")";
      $string_query2 = " OR next_stage = '".$search_result12."'";
    }

        $next_case_date = [];
        $next_stage = [];
        $next_judge = [];
        $prev_case_date = [];
        $next_lawyer_id = [];
        $next_date_id = [];
        $total_count = 0;
        $lawyer_id = "";
      
        // Get Number of Lawyer ID - Start
        if($_SESSION['user_type']=='firm'){
          $qry_firm = "SELECT lawyer_id FROM tbl_lawyers WHERE firm_id = '".$_SESSION['user_id']."' OR lawyer_id = '".$_SESSION['user_id']."'";
          $select_qry = mysqli_query($conn, $qry_firm);
          while ($select_result = mysqli_fetch_assoc($select_qry)) {
            $lawyer_id .= $select_result['lawyer_id'].",";
          }
          $lawyer_id = chop($lawyer_id, ",");
        }
        else if($_SESSION['user_type'] == 'lawyer' && $_SESSION['user_firm_id'] != 0){
          $qry_firm = "SELECT lawyer_id FROM tbl_lawyers WHERE firm_id = '".$_SESSION['user_firm_id']."' OR lawyer_id = '".$_SESSION['user_firm_id']."'";
          $select_qry = mysqli_query($conn, $qry_firm);
          while ($select_result = mysqli_fetch_assoc($select_qry)) {
            $lawyer_id .= $select_result['lawyer_id'].",";
          }
          $lawyer_id = chop($lawyer_id, ",");
        }
        else{
          $lawyer_id = $_SESSION['user_id'];
        }
        // Get Number of Lawyer ID - End




        //Get all cases from given lawyer id under nextdate_table - start
        $nextdate_case_id = mysqli_query($conn,"SELECT MAX(nextdt_id) as nextdt_id,next_case_id FROM tbl_case_nextdt WHERE lawyer_id IN (".$lawyer_id.")  GROUP BY next_case_id");
        $next_date_id = "";
        $next_case_id2 = "";
        while ($nextdate_case_id_result = mysqli_fetch_assoc($nextdate_case_id)) {
          $next_date_id .= $nextdate_case_id_result['nextdt_id'].",";
          $next_case_id2 .= $nextdate_case_id_result['next_case_id'].",";
        }
        $next_date_id = chop($next_date_id, ",");
        $next_case_id2 = chop($next_case_id2, ",");
        //Get all cases from given lawyer id under nextdate_table - start
        
        if($next_date_id == ""){
            $next_date_value_condition = "";
        }
        else{
            $next_date_value_condition = " AND nextdt_id IN (".$next_date_id.")";
        }
        
        if($next_case_id2 == ""){
            $next_date_value_condition2 = "";
        }
        else{
            $next_date_value_condition2 = " AND case_id NOT IN (".$next_case_id2.")";
        }

        $last_record = mysqli_query($conn,"SELECT nextdt_id,lawyer_id,next_case_id,prev_case_date,next_case_date,next_stage,next_judge FROM tbl_case_nextdt WHERE next_case_date < '".date('Y-m-d')."'".$next_date_value_condition." AND (next_stage NOT LIKE '%Dismissed%' AND next_stage NOT LIKE '%Disposed%' AND next_stage NOT LIKE '%NOC%' AND next_stage NOT LIKE '%Withdrwan%' AND next_stage NOT LIKE '%Dismissal%' AND next_stage NOT LIKE '%Decree%') AND lawyer_id IN (".$lawyer_id.")".$string_query2);

        $last_record_id = "";
        while ($last_record_result = mysqli_fetch_assoc($last_record)) {
          $last_record_id .= $last_record_result['next_case_id'].",";
          $next_case_date[$last_record_result['next_case_id']] = $last_record_result['next_case_date'];
          $next_stage[$last_record_result['next_case_id']] = $last_record_result['next_stage'];
          $next_judge[$last_record_result['next_case_id']] = $last_record_result['next_judge'];
          $prev_case_date[$last_record_result['next_case_id']] = $last_record_result['prev_case_date'];
          $next_lawyer_id[$last_record_result['next_case_id']] = $last_record_result['lawyer_id'];
          $next_date_id[$last_record_result['next_case_id']] = $last_record_result['nextdt_id'];
        }
        $last_record_id = chop($last_record_id, ",");

        if($last_record_id == ""){
            $next_date_value_condition3 = "";
        }
        else{
            $next_date_value_condition3 = "case_id IN (".$last_record_id.") OR";
        }

        $cases_list = mysqli_query($conn,"SELECT * FROM tbl_cases WHERE (".$next_date_value_condition3." case_id IN (SELECT case_id FROM tbl_cases WHERE next_date < '".date("Y-m-d")."'".$next_date_value_condition2." AND lawyer_id IN (".$lawyer_id.")))  AND lawyer_id IN (".$lawyer_id.") AND (stage NOT LIKE '%Dismissed%' AND stage NOT LIKE '%Disposed%' AND stage NOT LIKE '%NOC%' AND stage NOT LIKE '%Withdrwan%' AND stage NOT LIKE '%Dismissal%' AND stage NOT LIKE '%Decree%') ".$string_query." ORDER BY orderby_date DESC");
        
        $total_cases = mysqli_num_rows($cases_list);
        
        if(!isset($_SESSION['total_cases_next_date'])){
          $_SESSION['total_cases_next_date'] = "";
        }

        if (isset($_SESSION['total_cases_next_date']) && empty($string_query) && empty($string_query2)){
          $_SESSION['total_cases_next_date'] = $total_cases;
        }

        $pages = ceil($total_cases/$item_per_page);


         ?>
         <input type="hidden" id="total_cases_available" value="<?php echo $total_cases; ?>"></input>

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
            <input type="search" name="search_value" value="<?php if (isset($_SESSION['cases_all_search_next_date'])) { echo $_SESSION['cases_all_search_next_date']; } ?>" placeholder="Search here" aria-controls="attendenceDetailedTable"></input>
            <input type="submit" value="Search" class="btn btn-dark btn-sm"></input>
          </form>
        </div>


          <thead>    
               <th></th>
               <?php if($_SESSION['user_type']=='firm' OR $_SESSION['user_firm_id']!=0){ ?>
               <th>Lawyer</th>  
               <?php } ?>    
               <th>Prev Date</th>
               <th>Court Details</th>
               <th>Case No.</th>
               <th>Name of Parties</th>
            </tr>
          </thead>
          <tbody id="results">
            
          </tbody>
      </table>

      <div class="dataTables_info" id="attendenceDetailedTable1_info" role="status" aria-live="polite">
        <!-- Showing 1 to 2 of 2 entries -->
      </div>
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


   <!-- Cases History Modal Start -->
    <div class="zind modal fade" id="case_historyd">
      <div class="modal-dialog">
         <div class="modal-content case_history_modal">
         </div>
      </div>
    </div>
    <!-- Cases History Modal End -->


    <!-- Modal Popup court details -->
    <div class="zind modal fade" id="court_detailsd">
      <div class="modal-dialog">
         <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
            <div class="modal-header">
               <h3><i class="pe-7s-users"></i> Court Details</h3>
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
                           <p id="court_detailsd_courtname"></p>
                       </div>
                       <div class="sub_cell">
                           <p id="court_detailsd_judgename"></p>
                       </div>
                   </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- Modal Popup court details -->


   <!-- Modal Popup edit cases -->
    <div class="zind modal fade" id="update_casesd">
      <div class="modal-dialog">
        <div class="modal-content">
          <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
          <div class="modal-header">
            <h3><i class="pe-7s-users"></i> Update Case</h3>
          </div>
          <div class="modal-body">
            <form class="form" role="form" method="post" action="">
            <input type="hidden" class="eid" name="eid" value="">
            <input type="hidden" class="nextdt_id" name="nextdt_id" value=""> 
            <div class="col-md-6">
            <div class="form-group">
            <label>Court Name :</label>
            <input class="form-control edit_court_name" name="edit_court_name" placeholder="Court Name" type="text" value="" required>
            </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
            <label>Court Number :</label>
            <input class="form-control edit_court_number" name="edit_court_number" placeholder="Court Number" type="text" value="">
            </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
            <label>Case No. :</label>
            <input class="form-control edit_case_number" name="edit_case_number" placeholder="Case Number" type="text" value="" required>
            </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
            <label>Judge Name :</label>
            <input class="form-control edit_judge_name" name="edit_judge_name" placeholder="Judge Name" type="text" value="">
            </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
            <label>Category :</label>
            <select class="form-control edit_category" name="edit_category" required>
            <option value="">-- Select Category --</option>
            <option value="Civil">Civil</option>
            <option value="Criminal">Criminal</option>
            </select>
            </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
            <label>Client Name :</label>
            <input class="form-control edit_client_name" name="edit_client_name" placeholder="Client Name" type="text" value="" required>
            </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
            <label>Phone :</label>
            <input class="form-control edit_client_phone" name="edit_client_phone" placeholder="Phone" type="text" value="">
            </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
            <label>Email :</label>
            <input class="form-control edit_client_email" name="edit_client_email" placeholder="Email" type="text" value="">
            </div>
            </div>  
            <div class="col-md-6">
            <div class="form-group">
            <label>Next Date :</label>   
            <input class="form-control example1 edit_next_date" name="edit_next_date" placeholder="Next Date" type="text" value=""  autocomplete="off"  required>
            </div>
            </div>

            <div class="col-md-6">
            <div class="form-group">
            <label>Stage :</label>   
            <select class="form-control" id="optt" name="edit_stage"  onchange="showDivone(this.value)" required>
            <option value="">-- Select Stage --</option>
            <?php

            $qry = "SELECT stage_name FROM tbl_stage WHERE status = '1'";
            $result = mysqli_query($conn, $qry);
            while ($result2 = mysqli_fetch_assoc($result)) {
            ?>
            <option><?php echo $result2['stage_name']; ?></option>
            <?php } ?>
            <option value="other" style="color:red"><strong>Other</strong></option>
            </select>
            </div>
            </div>
            <div id="hide_show_divone"  style="display: none">
            <div class="col-md-6 col-xs-12">
            <div class="form-group">
            <label>New Stage :</label>
            <input  class="form-control" name="edit_newstage" placeholder="Enter New Stage" type="text" style="background:yellow">
            </div>
            </div>
            </div>  
            <div class="col-md-5">
            <div class="form-group">
            <label>File Number :</label>
            <input class="form-control file_no" name="file_no" placeholder="File Number" type="text" value="">
            </div>
            </div>         
            
            <div class="col-md-3">
            <div class="form-group">
            <label>Party A :</label>
            <input class="form-control edit_party_a" name="edit_party_a" placeholder="Party A" type="text" value="" required>
            </div>
            </div>
            <div class="col-md-1" style="padding-top:7px;">
            <p>VS</p>
            </div>
            <div class="col-md-3">
            <div class="form-group">
            <label>Party B :</label>
            <input class="form-control edit_party_b" name="edit_party_b" placeholder="Party B" type="text" value="" required>
            </div>
            </div>
            
                                    


            <div class="form-group">
            <button type="submit" class="btn btn-simple" name="edit_case">Update</button>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
   <!-- Modal Popup edit cases -->



   <!-- Modal popup add next cases date - End -->
   <div class="zind modal fade" id="nextdate_casesb">
      <div class="modal-dialog">
        <div class="modal-content">
          <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
          <div class="modal-header">
            <h3><i class="pe-7s-users"></i> Next Date</h3>
          </div>
          <div class="modal-body">
            <form class="form" role="form" method="post" action="">
            <input type="hidden" name="next_case_id" id="next_case_id" value="">
            <input type="hidden" name="prev_case_date" id="prev_case_date" value=""> 
            <div class="col-md-6">
            <div class="form-group">
            <input class="form-control example1" value="" id="next_case_date" name="next_case_date" placeholder="Next Date" type="text" autocomplete="off" required>
            </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
            <input class="form-control" name="judge_name" id="judge_name" placeholder="Judge Name" type="text" value="" autocomplete="off">
            </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
            <select class="form-control" name="next_stage" id="next_stage" onchange="showDivtwo(this.value)" required>
            <option value="">-- Select Stage --</option>
            <?php

            $qry = "SELECT stage_name FROM tbl_stage WHERE status = '1'";
            $result = mysqli_query($conn, $qry);
            while ($result2 = mysqli_fetch_assoc($result)) { ?>
            <option><?php echo $result2['stage_name'];?></option>
            <?php }
            ?>
            <option value="other" style="color:red"><strong>Other</strong></option> 
            </select>
            </div>
            </div>
            <div id="hide_show_divtwo"  style="display: none">
            <div class="col-md-6">
            <div class="form-group">
            <input  class="form-control" name="add_newstage" placeholder="Enter New Stage" type="text" style="background:yellow">
            </div>
            </div>
            </div>

            <div class="col-md-12">
            <div class="form-group">
            <button type="submit" class="btn btn-simple" name="add_next_date">Add</button>
            </div>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal popup add next cases date - End  -->


</section>
<!-- manage free slot end -->
<?php 
   include("footer.php"); 
?>

<script type="text/javascript" src="js/jquery.bootpag.min.js"></script>
<script type="text/javascript">
   $(document).ready(function() {
     $("#results").load("pagination_fetch_pages_nextdate.php");  //initial page number to load
     $(".pagination").bootpag({
        total: <?php echo $pages; ?>,
        page: <?php echo $_SESSION['cases_all_pagination_page']; ?>,
        maxVisible: 10 
     }).on("page", function(e, num){
       e.preventDefault();
    //   $("#results").html('<div class="loading-indication"><b>Loading...</b></div>');
        $("#results").html('<div class="loading-indication"><img src="assets/img/ajax-loader.gif" /></div>');
       $("#results").load("pagination_fetch_pages_nextdate.php", {'page':num});
     });
   });
</script>


<script>
function myFunctione(elem)
{
   if(elem == 'other')
   {
    document.getElementById('hide_show_div').style.display = "block";
   }
   else
   {
     document.getElementById('hide_show_div').style.display = "none";
   }
}
</script>

<script>
function showDive(elem,id)
{
   if(elem == 'other')
   {
    document.getElementById('hide_show_divv'+id).style.display = "block";
   }
    else
   { 
     document.getElementById('hide_show_divv'+id).style.display = "none";
   } 
}  
</script>


<!-- Delete cases - Start -->
<script type="text/javascript">
  function deleteCases(id){
    $("#did").val(id);
    $("#delete_casesb").modal('show');
  }
</script>
<!-- Delete cases - End -->


<script type="text/javascript">
  $(document).ready(function(){
    setInterval(function(){

      val = $(".NumberOfCases").val();
      if(typeof val === "undefined"){

      }
      else{
        totalCases = $("#total_cases_available").val();
        
        var totalCasesSession = '<?php echo $_SESSION['total_cases_next_date']; ?>';
        if(totalCasesSession != ""){
          $(".total_count_data").text(totalCasesSession);
        }

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
<!-- History View - End -->

<!-- Court details - Start -->
<script type="text/javascript">
  function court_detailsd(courtNumber, judgeName){
    $("#court_detailsd_courtname").text(courtNumber);
    $("#court_detailsd_judgename").text(judgeName);
    $('#court_detailsd').modal('show');
  }
</script>
<!-- Court details - End -->

<script>
function showDive(elem)
{
   if(elem == 'other')
   {
      document.getElementById('hide_show_divv').style.display = "block";
   }
    else
   { 
      document.getElementById('hide_show_divv').style.display = "none";
   } 
}  
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

<!-- Cases fetch for update query - Start -->
<script type="text/javascript">
  function update_casesd(nextdt_id, case_id, court_name, court_number, case_number, judge_name, category, client_name, client_phone, client_email, file_no, party_a, party_b, next_date, next_stage){
    $('.nextdt_id').val(nextdt_id);
    $('.eid').val(case_id);
    $('.edit_court_name').val(court_name);
    $('.edit_court_number').val(court_number);
    $('.edit_case_number').val(case_number);
    $('.edit_judge_name').val(judge_name);
    $('.edit_next_date').val(next_date);

    index_val = 0;
    validate_index_selection = 0;

    $('select#optt').find('option').each(function() {
      if($(this).val() == next_stage){
        $("#optt").prop("selectedIndex", index_val);
        validate_index_selection = validate_index_selection + 1;
      }
      index_val = index_val + 1;
    });

    if(validate_index_selection == 0){
        $('#optt').append($("<option></option>").attr("value",next_stage).text(next_stage));
        $("#optt").prop("selectedIndex", index_val);
    }

    if(category == "Civil"){
      $(".edit_category").prop("selectedIndex", 1);
    }
    else if(category == "Criminal"){
      $(".edit_category").prop("selectedIndex", 2);
    }
    else{
      $(".edit_category").prop("selectedIndex", 0);
    }

    $('.edit_client_name').val(client_name);
    $('.edit_client_phone').val(client_phone);
    $('.edit_client_email').val(client_email);
    $('.file_no').val(file_no);
    $('.edit_party_a').val(party_a);
    $('.edit_party_b ').val(party_b);
    $("#hide_show_divone").css("display","none");
    $("#update_casesd").modal('show');
  }
</script>
<!-- Cases fetch for update query - End -->


<!-- Cases fetch for update next date - Start -->
<script type="text/javascript">
  function update_next_dt(case_id, next_date, judge, case_stage){
    $("#next_case_id").val(case_id);
    $("#prev_case_date").val(next_date);
    $("#next_case_date").val(next_date);
    $("#judge_name").val(judge);

    var index_val = 0;
    var validate_index_selection = 0;

    $('select#next_stage').find('option').each(function() {
      if($(this).val() == case_stage){
        $("#next_stage").prop("selectedIndex", index_val);
        validate_index_selection = validate_index_selection + 1;
      }
      index_val = index_val + 1;
    });

    if(validate_index_selection == 0){
        $('#next_stage').append($("<option></option>").attr("value",case_stage).text(case_stage));
        $("#next_stage").prop("selectedIndex", index_val);
    }

    // $("#next_stage").val(case_stage);
    $("#hide_show_divtwo").css("display","none");
    $("#nextdate_casesb").modal('show');
  }
</script>
<!-- Cases fetch for update next date - End -->


<script>
function showDivtwo(elem)
{
   if(elem == 'other')
      {
      document.getElementById('hide_show_divtwo').style.display = "block";
      }
   else
   { 
     document.getElementById('hide_show_divtwo').style.display = "none";
   } 
}
</script>

<script>
   function showDivone(elem)
   {
   if(elem == 'other')
      {
      document.getElementById('hide_show_divone').style.display = "block";
      }
      else
      { 
     document.getElementById('hide_show_divone').style.display = "none";
      }
   }
</script>