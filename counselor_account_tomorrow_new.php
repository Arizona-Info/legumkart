<!-- tomorrow detail -->
<div class="tab-pane fade" id="tomorrow" role="tabpane2">
  <div class="table-responsive dataTables_wrapper">
  <?php 
    if(isset($_SESSION['upcoming_counsel_search']) && $_SESSION['upcoming_counsel_search'] != ""){

      $string_query = "";

        $search_result12 = strtoupper($_SESSION['upcoming_counsel_search']);
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


     $counselcases = mysqli_query($conn,"SELECT t1.*, t2.* FROM tbl_counsel_cases t1, tbl_cases t2 WHERE cc_date = '".date('Y-m-d', strtotime('+1 day'))."' AND t1.cc_case_id=t2.case_id AND t1.cc_status='Accepted' AND t1.counsel_id = '".$_SESSION['user_id']."'".$string_query);
      $get_tomorrow_rows = mysqli_num_rows($counselcases); 
      $pages_tomorrow = ceil($get_tomorrow_rows/$item_per_page);
     ?> 

     <input type="hidden" id="total_cases_counsel_tomorrow" value="<?php echo $get_tomorrow_rows; ?>"></input>

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
            <input type="search" name="search_value" value="<?php echo $_SESSION['upcoming_counsel_search']; ?>" placeholder="Search here" aria-controls="attendenceDetailedTable"></input>
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
         <tbody id="results_tomorrow">
         </tbody>
         </table>
         <div class="dataTables_paginate paging_simple_numbers pagination_tomorrow" id="">
          </div>
   </div>
 </div>

<script type="text/javascript">
 function saveStatus2(id,editableObj) 
 {  

   if(editableObj != ""){
      $.ajax({
        url: "savestatus_counselor_cases_diary.php",
        type: "POST",
        data:'editval='+editableObj+'&id='+id,
        success: function(data){
            // alert(data);
            $('#mydiv_2'+id).html(data);
        }        
   });
   }
   if(editableObj == "Attended"){
      $("#visible_2"+id).show();
   }
   else{
      $("#visible_2"+id).hide();
   }
}
</script>
