<!-- ******* weekly cases ******* -->  
<div class="tab-pane fade" id="all" role="tabpanel">
   <div class="table-responsive action_table dataTables_wrapper">
      <table id="" class="display dataTable responsive nowrap" cellspacing="0" width="100%">
         <thead>
            <tr>
            <th></th>
            <?php 
            if($_SESSION['user_type']=='firm' OR $_SESSION['user_firm_id']!=0)
            {   ?>
               <th>Lawyer</th>  
            <?php
            }   
            ?>
               <th>Prev Date</th>
               <th>Court Details</th>
               <th>Case No.</th>
               <th>Name of Parties</th>
               <!-- <th>Stage</th>
               <th>Next Date</th>
               <th>Client Name</th>
               <th>Category</th>
               <th>File No.</th>
               <th>Action</th> -->
            </tr>
            <?php

            $item_per_page = 10;
            $string_query = "";

            if(!isset($_SESSION['cases_weekly_pagination_page'])){
              $_SESSION['cases_weekly_pagination_page'] = 1;
            }

            if(isset($_POST['search_value'])){
              $_SESSION['cases_upcoming_search'] = $_POST['search_value'];
              $_SESSION['cases_weekly_pagination_page'] = 1;
            }
            else if(!isset($_SESSION['cases_upcoming_search'])){
              $_SESSION['cases_upcoming_search'] = "";
              $_SESSION['cases_weekly_pagination_page'] = 1;
            }

            $next_case_date = [];
            $next_stage = [];
            $next_judge = [];
            $prev_case_date = [];
            $next_lawyer_id = [];
            $next_date_id = [];
            $lawyer_id = "";

            if(isset($_SESSION['cases_upcoming_search']) && $_SESSION['cases_upcoming_search'] != ""){

                  $search_result12 = strtoupper($_SESSION['cases_upcoming_search']);
                  set_error_handler (
                      function($errno, $errstr, $errfile, $errline) {
                          throw new ErrorException($errstr, $errno, 0, $errfile, $errline);     
                      }
                  );

                  try {
                    $date_query = date_create($search_result12);
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
                    $date_vs1 = $search_result12;
                    $date_vs2 = $search_result12;
                  }

                  $string_query = " AND (court_name like '%".$search_result12."%' OR court_number like '%".$search_result12."%' OR judge_name like '%".$search_result12."%' OR case_number like '%".$search_result12."%' OR stage like '%".$search_result12."%' OR client_name like '%".$search_result12."%' OR client_phone like '%".$search_result12."%' OR client_email like '%".$search_result12."%' OR category like '%".$search_result12."%' OR party_b like '%".$date_vs2."%' OR party_a like '%".$date_vs2."%' OR party_b like '%".$date_vs1."%' OR party_a like '%".$date_vs1."%' ".$date_query.")";
                }

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

            //Get number of date in table_ next_date - Start
            $next_qry = "SELECT MAX(nextdt_id) AS nextdt_id, next_case_id, next_case_date, next_stage, next_judge, prev_case_date, lawyer_id FROM tbl_case_nextdt WHERE lawyer_id IN (".$lawyer_id.") AND (next_case_date BETWEEN '".date('Y-m-d', strtotime('+2 day'))."' AND '".date('Y-m-d', strtotime('+7 day'))."') GROUP BY next_case_id";
            $select_next = mysqli_query($conn, $next_qry);

            $cases_id = "";

            while ($select_next_result = mysqli_fetch_assoc($select_next)) {
              $cases_id .= $select_next_result['next_case_id'].',';
              $next_case_date[$select_next_result['next_case_id']] = $select_next_result['next_case_date'];
              $next_stage[$select_next_result['next_case_id']] = $select_next_result['next_stage'];
              $next_judge[$select_next_result['next_case_id']] = $select_next_result['next_judge'];
              $prev_case_date[$select_next_result['next_case_id']] = $select_next_result['prev_case_date'];
              $next_lawyer_id[$select_next_result['next_case_id']] = $select_next_result['lawyer_id'];
              $next_date_id[$select_next_result['next_case_id']] = $select_next_result['nextdt_id'];
            }
            if($cases_id != ""){
              $cases_id = "case_id IN (".chop($cases_id, ",").") OR ";
            }
            //Get number of date in table_ next_date - End

            // Get specific date cases - Start
            $qry_cases = "SELECT lawyer_id,case_id, next_date, judge_name, stage, court_number, case_number, party_a, party_b, court_name, client_name, category, file_no, client_phone, client_email FROM tbl_cases WHERE (".$cases_id."(next_date BETWEEN '".date('Y-m-d', strtotime('+2 day'))."' AND '".date('Y-m-d', strtotime('+7 day'))."')) AND lawyer_id  IN (".$lawyer_id.") ".$string_query." ORDER BY orderby_date ASC, CASE 
                                         WHEN court_name LIKE '%supreme%' THEN '1'
                                         WHEN court_name LIKE '%high%' THEN '2'
                                         WHEN court_name LIKE '%nclt%' THEN '3'
                                         WHEN court_name LIKE '%nclat%' THEN '4'
                                         WHEN court_name LIKE '%ncp%' THEN '5'
                                         WHEN court_name LIKE '%drt%' THEN '6'
                                         WHEN court_name LIKE '%drat%' THEN '7'
                                         WHEN court_name LIKE '%senior%' THEN '8'
                                         WHEN court_name LIKE '%civil%' THEN '9'
                                         WHEN court_name LIKE '%district%' THEN '10'
                                         WHEN court_name LIKE '%family%' THEN '11'
                                         WHEN court_name LIKE '%consumer%' THEN '12'
                                         WHEN court_name LIKE '%state%' THEN '13'
                                         WHEN court_name LIKE '%magistrate%' THEN '12'
                                         WHEN court_name LIKE '%jmfc%' THEN '13'
                                         ELSE court_name END ASC";

            $select_cases = mysqli_query($conn, $qry_cases);
            $get_total_rows_weekly = mysqli_num_rows($select_cases); 
            $pages_weekly = ceil($get_total_rows_weekly/$item_per_page);
            ?>
            <input type="hidden" id="total_cases_available_weekly" value="<?php echo $get_total_rows_weekly; ?>"></input>
 
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
                <input type="search" name="search_value" value="<?php echo $_SESSION['cases_upcoming_search']; ?>" placeholder="Search here" aria-controls="attendenceDetailedTable"></input>
                <input type="submit" value="Search" class="btn btn-dark btn-sm"></input>
              </form>
            </div>
         </thead>
         <tbody id="results_weekly">
            
         </tbody>
      </table>
      <div class="dataTables_paginate paging_simple_numbers pagination_weekly" id="">
      </div>
   </div>
</div>
<!-- ******* weekly cases  END ******* --> 

