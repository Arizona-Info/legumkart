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
      $_SESSION['cases_all_search'] = $_POST['search_value'];
      $_SESSION['cases_all_pagination_page'] = 1;
   }
   else if(!isset($_SESSION['cases_all_search'])){
      $_SESSION['cases_all_search'] = "";
      $_SESSION['cases_all_pagination_page'] = 1;
   }


      /***CODE FOR Calculating total bytes of uploaded files and assigned memory to firm,firm_lawyers i.e equivalent to firm or single lawyers  **************/
   $flag=''; 

   if($_SESSION['user_type']=='firm')
   {  
      $calc_tot = mysqli_query($conn,"SELECT SUM(t1.files_tot_size_bytes) AS tot_files_size FROM tbl_cases t1,  tbl_lawyers t2 WHERE (t1.lawyer_id=t2.lawyer_id AND t2.firm_id='".$_SESSION['user_id']."') OR (t1.lawyer_id=t2.lawyer_id AND  t1.lawyer_id='".$_SESSION['user_id']."')");
   
      // query to get package size allocated to firm
      $showqry = mysqli_query($conn,"SELECT t3.pack_size FROM tbl_lawyer_package t1 INNER JOIN tbl_lawyers t2 ON t1.lawyer_id=t2.lawyer_id INNER JOIN tbl_package t3 ON t1.package_id=t3.pack_id WHERE t1.lawyer_id='".$_SESSION['user_id']."'");

      $flag='firm_owner';
   }
   else
   {
    if(isset($_SESSION['user_firm_id']) && $_SESSION['user_firm_id'] == 0)
    {
         $calc_tot = mysqli_query($conn,"SELECT SUM(t1.files_tot_size_bytes) AS tot_files_size FROM tbl_cases t1, tbl_lawyers t2  WHERE t1.lawyer_id=t2.lawyer_id AND (t1.lawyer_id='".$_SESSION['user_id']."')");

         // query to get package size allocated to a single lawyer
         $showqry = mysqli_query($conn,"SELECT t3.pack_size FROM tbl_lawyer_package t1 INNER JOIN tbl_lawyers t2 ON t1.lawyer_id=t2.lawyer_id INNER JOIN tbl_package t3 ON t1.package_id=t3.pack_id WHERE t1.lawyer_id='".$_SESSION['user_id']."'");

         $flag='single_lawyer';  
    }
    if(isset($_SESSION['user_firm_id']) && $_SESSION['user_firm_id'] != 0)
    {
         $calc_tot = mysqli_query($conn,"SELECT SUM(t1.files_tot_size_bytes) AS tot_files_size FROM tbl_cases t1, tbl_lawyers t2  WHERE t1.lawyer_id=t2.lawyer_id AND t1.lawyer_id IN (SELECT lawyer_id FROM tbl_lawyers WHERE lawyer_id = '".$_SESSION['user_firm_id']."' OR firm_id = '".$_SESSION['user_firm_id']."') ");

         $showqry = mysqli_query($conn,"SELECT t3.pack_size FROM tbl_lawyer_package t1 INNER JOIN tbl_lawyers t2 ON t1.lawyer_id=t2.lawyer_id INNER JOIN tbl_package t3 ON t1.package_id=t3.pack_id WHERE t1.lawyer_id IN (SELECT lawyer_id FROM tbl_lawyers WHERE lawyer_id = '".$_SESSION['user_firm_id']."' OR firm_id = '".$_SESSION['user_firm_id']."')");
          
          $flag='firm_lawyers';
    }
   }
   $row_tot_files_size=mysqli_fetch_assoc($calc_tot);

   $row_space_allocated=mysqli_fetch_assoc($showqry);
   $allocated_space_inbytes=$row_space_allocated['pack_size']*1024*1024*1024;

   $one_gb_demo_space = 1*1024*1024*1024;
   
   //check if that lawyer/firm has package
   $rows_package = mysqli_num_rows($showqry);
 /********************** ENDS  **************************/  

   
   if(isset($_POST['add_case']))
   {
   
   if($_POST['stage']=='other' && $_POST['newstage'] !='')
   {
     $stage = $_POST['newstage'];
   }
   else
   {
    $stage = $_POST['stage'];
   }

   $stmt = mysqli_query($conn,"INSERT INTO tbl_cases(court_name, lawyer_id, case_number, category, client_name, status, next_date, client_phone,client_email,paymt_status, counsel_paymt_status ,court_number, judge_name, party_a, party_b, stage, file_no, orderby_date) VALUES('".$_POST['court_name']."', '".$_SESSION['user_id']."', '".$_POST['case_number']."','".$_POST['category']."','".$_POST['client_name']."','Active','".$_POST['next_date']."','".$_POST['client_phone']."','".$_POST['client_email']."','Pending', 'Pending','".$_POST['court_number']."','".$_POST['judge_name']."','".$_POST['party_a']."','".$_POST['party_b']."','".$stage."', '".$_POST['file_no']."','".$_POST['next_date']."')");
   echo  '<script>alert("Case added successfully")</script>';
   }
   
   
   if(isset($_POST['edit_case']))
   {
   $eid = $_REQUEST['eid'];

  
   $query = "";
   if($_REQUEST['nextdt_id'] == ""){
    $query = "judge_name='".$_POST['edit_judge_name']."', next_date='".$_POST['edit_next_date']."',stage='".$_POST['edit_stage']."',";
   }

   $valsdg = "";

   $stmt = mysqli_query($conn,"UPDATE tbl_cases SET court_name = '".$_POST['edit_court_name']."',court_number='".$_POST['edit_court_number']."', case_number = '".$_POST['edit_case_number']."', ".$query." category = '".$_POST['edit_category']."',client_name = '".$_POST['edit_client_name']."', client_phone='".$_POST['edit_client_phone']."', client_email='".$_POST['edit_client_email']."', party_a='".$_POST['edit_party_a']."', party_b='".$_POST['edit_party_b']."', file_no ='".$_POST['file_no']."', orderby_date = '".$_POST['edit_next_date']."' WHERE case_id='".$eid."'");

   if($_REQUEST['nextdt_id'] != "")
    {
      $new_id = mysqli_query($conn,"SELECT nextdt_id FROM tbl_case_nextdt WHERE next_case_id = '".$eid."' ORDER BY nextdt_id desc");
      $new_id_result = mysqli_fetch_assoc($new_id);
      $nextdt_id = $new_id_result['nextdt_id'];
      $stmt_dtandstage = mysqli_query($conn,"UPDATE tbl_case_nextdt SET next_judge = '".$_POST['edit_judge_name']."', next_stage = '".$_POST['edit_stage']."', next_case_date = '".$_POST['edit_next_date']."' WHERE nextdt_id='".$nextdt_id."'");
    }


   echo  '<script>alert("Case edited successfully")</script>';
   }
   
   if(isset($_POST['status']))
      {
          $aid = $_REQUEST['lid']; 
          $val = $_REQUEST['lstatus'];
          $new_val = '';
          if($val == 'Active') { $new_val = 'InActive'; $nval = 'InActive';}  
          if($val == 'InActive') {$new_val = 'Active'; $nval = 'Active';} 
          $updstatus = mysqli_query($conn,"UPDATE tbl_cases SET status   = '".$new_val."' where case_id = '".$aid."'");
      }
   
   if(isset($_POST['delete_id']))
      {
          $did = $_REQUEST['did']; 
          $stmt_dtandstage = mysqli_query($conn,"DELETE FROM tbl_cases WHERE case_id = '".$did."'");
          $qry_find_nextdate_bef_deletion = mysqli_query($conn,"DELETE FROM tbl_case_nextdt WHERE next_case_id='".$did."'");
          echo  '<script>alert("Case Deleted successfully")</script>';
        //   $did = $_REQUEST['did']; 
        //   $ans = "" ;
          
        //   // here we r fetching previous date of the record beeing deleted from child table bcoz this previous date was actually the next date in the record above the deleted record.We will pick this date and update column orderbydate in master table...this will solve our sorting purpose
        //   $qry_find_nextdate_bef_deletion = mysqli_query($conn,"SELECT nextdt_id, prev_case_date FROM tbl_case_nextdt WHERE next_case_id='".$did."' ORDER BY next_case_date DESC LIMIT 1");
        //   $number= mysqli_num_rows($qry_find_nextdate_bef_deletion);
        //   $row=mysqli_fetch_assoc($qry_find_nextdate_bef_deletion);
        //     if($number > 0){
        //     //   echo  '<script>alert('.$number.')</script>';
        //       $upd_orderby_date_aft_deletion = mysqli_query($conn,"UPDATE tbl_cases SET orderby_date = '".$row['prev_case_date']."' where case_id = '".$did."'");
        //     }

          
        //   $stmt_dtandstage = mysqli_query($conn,"DELETE FROM tbl_case_nextdt WHERE next_case_id ='".$did."' ORDER BY nextdt_id DESC limit 1");          
            
        //   if(mysqli_affected_rows($conn) > 0)
        //   {
           
        //     echo  '<script>alert("Case Deleted successfully")</script>';
        //   }
        //   else{
        //     $ans = "next";
           
        //   }

        //   if($ans == "next")
        //   {
        //     $stmt_dtandstage = mysqli_query($conn,"DELETE FROM tbl_cases WHERE case_id = '".$did."'");

        //       if(mysqli_affected_rows($conn) > 0)
        //     {
        //       echo  '<script>alert("Case Deleted successfully")</script>';
        //     }
        //     else
        //     {
        //       echo  '<script>alert("Unable to delete")</script>';
        //     }

        //   }
      }
   
    if(isset($_POST['send_to_counsel']))
      {

        if($_POST['type_selection'] == 'conference'){
            $stmt = mysqli_query($conn,"INSERT INTO tbl_counsel_cases(counsel_id, cc_case_id, cc_next_date, cc_status,cc_flag,cc_type) VALUES('".$_POST['counselor_id']."', '".$_POST['caseeid']."', '".$_POST['case_datee']."', 'Pending','2','Conference')");

            echo  '<script>alert("Case successfully sent to Counselor")</script>';
        }
        else if($_POST['type_selection'] == 'hearing'){
            $stmt = mysqli_query($conn,"INSERT INTO tbl_counsel_cases(counsel_id, cc_case_id, cc_next_date, cc_status,cc_flag,cc_type,cc_hearing_time ) VALUES('".$_POST['counselor_id']."', '".$_POST['caseeid']."', '".$_POST['case_datee']."', 'Pending','2','Hearing','".$_POST['usr_time']."')");

            echo  '<script>alert("Case successfully sent to Counselor")</script>';
        }
        else{
            echo  '<script>alert("Something went wrong, please try again")</script>';
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
                        <h3>Case Diary</h3>
                           <a href="#add_cases" data-toggle="modal" class="btn btn-dark add_new" style="text-transform:initial;">Add</a>
                           <a class="add_new">&nbsp</a>
                           <b class="add_new total_count_view">Loading...</b>
                     </div>
                  </div>
                  <hr>  
                  

                  <!-- Add Cases -->
                  <div class="zind modal fade" id="add_cases">
                     <div class="modal-dialog">
                        <div class="modal-content">
                           <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                           <div class="modal-header">
                              <h3>Add Case</h3>
                           </div>
                           <div class="modal-body">
                              <form class="form" role="form" method="post" action=""  autocomplete="off">
                                 <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                       <div class="form-group">
                                          <input class="form-control" name="court_name" placeholder="Court Name" type="text" value="" required>
                                       </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                       <div class="form-group">
                                          <input class="form-control" name="court_number" placeholder="Court Number" type="text" value="">
                                       </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                       <div class="form-group">
                                          <input class="form-control" name="case_number" placeholder="Case Number" type="text" value=""  required>
                                       </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                       <div class="form-group">
                                          <input class="form-control" name="judge_name" placeholder="Judge Name" type="text" value="">
                                       </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                       <div class="form-group">
                                          <input class="form-control example1" name="next_date" placeholder="Next Date" type="text" value=""  autocomplete="off"  required>
                                       </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                       <div class="form-group">
                                          <select class="form-control" name="category"  required>
                                          <option value="">-- Select Category --</option>
                                          <option>Civil</option>
                                          <option>Criminal</option>
                                       </select>
                                       </div>
                                    </div>
                                    <div class="col-md-5 col-xs-12">
                                       <div class="form-group">
                                          <input class="form-control" name="client_name" placeholder="Client Name" type="text" value=""  required>
                                       </div>
                                    </div>
                                    <div class="col-md-3 col-sm-5 col-xs-12">
                                       <div class="form-group">
                                          <input class="form-control" name="party_a" placeholder="Party A" type="text" value=""  required>
                                       </div>
                                    </div>
                                    <div class="col-md-1 col-sm-2 col-xs-12" style="padding-top:7px;">
                                       <p class="text-center">VS</p>
                                    </div>
                                    <div class="col-md-3 col-sm-5 col-xs-12">
                                       <div class="form-group">
                                          <input class="form-control" name="party_b" placeholder="Party B" type="text" value=""  required>
                                       </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                       <div class="form-group">
                                          <input class="form-control" name="client_phone" placeholder="Client Phone" type="text" value="">
                                       </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                       <div class="form-group">
                                          <input class="form-control" name="client_email" placeholder="Client Email" type="text" value="">
                                       </div>
                                    </div>
                                     <div class="col-md-6 col-xs-12">
                                       <div class="form-group">
                                          <select class="form-control"  name="stage"   onchange="myFunctione(this.value)" required>
                                             <option value="">-- Select Stage --</option>
                                             <?php

                                                $qry = "SELECT stage_name FROM tbl_stage WHERE status = '1'";
                                                $result = mysqli_query($conn, $qry);
                                                while ($result2 = mysqli_fetch_assoc($result)) {
                                                    echo "<option>".$result2['stage_name']."</option>";
                                                }

                                              ?>               
                                            <option value="other" style="color:red"><strong>Other</strong></option>
                                          </select>
                                       </div>
                                    </div> 
                                     <div id="hide_show_div" style="display:none">
                                       <div class="col-md-6 col-xs-12">
                                          <div class="form-group">
                                             <input class="form-control" name="newstage" placeholder="Enter New Stage" type="text" style="background:yellow">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                      <div class="form-group">
                                       <input class="form-control" name="file_no" placeholder="File Number" type="text">
                                      </div>
                                    </div>
                                    <div class="form-group col-xs-12">
                                       <button type="submit" class="btn btn-simple" name="add_case">Add</button>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        </div>
                     </div>
                  </div>
   
   <div class="row interactions">
      <div class="col-md-12">
         <ul class="nav nav-tabs" role="tablist">

            <li class="nav-item active">
               <a data-toggle="tab" href="#all" role="tab">All Cases</a>
            </li>
         </ul>
      <div class="tab-content">

<!--all-->
<div class="tab-pane fade in active" id="all" role="tabpanel">
   <div class="table-responsive action_table dataTables_wrapper">
        <?php    
          $item_per_page = 10;
          $string_query = "";

          if(isset($_SESSION['cases_all_search']) && $_SESSION['cases_all_search'] != ""){

            $search_result12 = strtoupper($_SESSION['cases_all_search']);
            set_error_handler (
                function($errno, $errstr, $errfile, $errline) {
                    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);     
                }
            );

            try {
              $date_query = date_create($search_result12);
              $date_query = "  OR t1.orderby_date = '".date_format($date_query,"Y-m-d")."'";
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

            $string_query = " AND (t2.firm_name like '%".$search_result12."%' OR t1.court_name like '%".$search_result12."%' OR t1.court_number like '%".$search_result12."%' OR t1.judge_name like '%".$search_result12."%' OR t1.case_number like '%".$search_result12."%' OR t1.stage like '%".$search_result12."%' OR t1.client_name like '%".$search_result12."%' OR t1.client_phone like '%".$search_result12."%' OR t1.client_email like '%".$search_result12."%' OR t1.category like '%".$search_result12."%' OR t1.party_b like '%".$date_vs2."%' OR t1.party_a like '%".$date_vs2."%' OR t1.party_b like '%".$date_vs1."%' OR t1.party_a like '%".$date_vs1."%' ".$date_query.")";
          }

          // $sql22 = mysqli_query($conn,"SELECT COUNT(product_id) FROM products");  
          if($_SESSION['user_type']=='firm' && $_SESSION['user_id'] != "")
          {
            $lawyerqry = mysqli_query($conn,"SELECT COUNT(t1.case_id) FROM tbl_cases t1,  tbl_lawyers t2 WHERE ((t1.lawyer_id=t2.lawyer_id AND t2.firm_id='".$_SESSION['user_id']."') OR (t1.lawyer_id=t2.lawyer_id AND  t2.lawyer_id='".$_SESSION['user_id']."')) ".$string_query);
            
            // $lawyerqry2 = mysqli_query($conn,"SELECT COUNT(t1.case_id) AS cnt FROM tbl_cases t1,  tbl_lawyers t2 WHERE ((t1.lawyer_id=t2.lawyer_id AND t2.firm_id='".$_SESSION['user_id']."') OR (t1.lawyer_id=t2.lawyer_id AND  t2.lawyer_id='".$_SESSION['user_id']."'))".$string_query);
          }
          else if($_SESSION['user_type']=='lawyer' && $_SESSION['user_id'] != "")
          {
              
            $qry19 = mysqli_query($conn,"SELECT firm_id FROM tbl_lawyers WHERE lawyer_id='".$_SESSION['user_id']."'");
                $result19 = mysqli_fetch_assoc($qry19);
                // echo "<script>alert('".$result19['firm_id']."');</script>";

            if(isset($result19['firm_id']) && $result19['firm_id'] == 0){
              $lawyerqry = mysqli_query($conn,"SELECT COUNT(t1.case_id) FROM tbl_cases t1, tbl_lawyers t2  WHERE t1.lawyer_id=t2.lawyer_id AND (t1.lawyer_id='".$_SESSION['user_id']."') ".$string_query);
              
              // $lawyerqry2 = mysqli_query($conn,"SELECT COUNT(t1.case_id) AS cnt FROM tbl_cases t1, tbl_lawyers t2  WHERE t1.lawyer_id=t2.lawyer_id AND (t1.lawyer_id='".$_SESSION['user_id']."')".$string_query);
            }
            else
            {
              $lawyerqry = mysqli_query($conn,"SELECT COUNT(t1.case_id) FROM tbl_cases t1, tbl_lawyers t2  WHERE t1.lawyer_id=t2.lawyer_id AND t1.lawyer_id in (SELECT lawyer_id FROM tbl_lawyers WHERE lawyer_id = '".$result19['firm_id']."' OR firm_id = '".$result19['firm_id']."') ".$string_query);
              
              // $lawyerqry2 = mysqli_query($conn,"SELECT COUNT(t1.case_id) AS cnt FROM tbl_cases t1, tbl_lawyers t2  WHERE t1.lawyer_id=t2.lawyer_id AND t1.lawyer_id in (SELECT lawyer_id FROM tbl_lawyers WHERE lawyer_id = '".$result19['firm_id']."' OR firm_id = '".$result19['firm_id']."')".$string_query);
            }

          }

          $get_total_rows = mysqli_fetch_array($lawyerqry); 
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
            <input type="search" name="search_value" value="<?php echo $_SESSION['cases_all_search']; ?>" placeholder="Search here" aria-controls="attendenceDetailedTable"></input>
            <input type="submit" value="Search" class="btn btn-dark btn-sm"></input>
          </form>
        </div>


          <thead>    
               <th></th>
               <?php if($_SESSION['user_type'] != 'lawyer'){ ?>
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

      <div class="dataTables_info" id="attendenceDetailedTable1_info" role="status" aria-live="polite" style='display:none'>
        Showing 1 to 2 of 2 entries
      </div>
      <div class="dataTables_paginate paging_simple_numbers pagination" id="">
      </div>

      <!-- <div class="dataTables_paginate paging_simple_numbers pagination" id="">
        <a class="paginate_button previous disabled" aria-controls="" data-dt-idx="0" tabindex="0" id="attendenceDetailedTable1_previous"><i class="fa fa-angle-left"></i></a>
        <span><a class="paginate_button current" aria-controls="" data-dt-idx="1" tabindex="0">1</a></span>
        <a class="paginate_button next disabled" aria-controls="" data-dt-idx="2" tabindex="0" id="attendenceDetailedTable1_next"><i class="fa fa-angle-right"></i></a>
      </div> -->

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

   <!-- court details - start -->
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
   <!-- court details - End -->


   <!-- client details - start -->
   <div class="zind modal fade" id="client_detailsd">
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
                           <p>Client Phone</p>
                       </div>
                       <div class="cell">
                           <p>Client Email</p>
                       </div>
                   </div>
                   <div class="sub_heading">
                       <div class="sub_cell">
                           <p class="cases_all_client_phone"></p>
                       </div>
                       <div class="sub_cell">
                           <p class="cases_all_client_email"></p>
                       </div>
                   </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- client details - End -->

   <!-- Update cases modal - Start -->
<div class="zind modal fade" id="update_cases">
 <div class="modal-dialog">
    <div class="modal-content">
       <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
       <div class="modal-header">
          <h3><i class="pe-7s-users"></i> Update Case</h3>
       </div>
       <div class="modal-body">
          <form class="form" role="form" method="post" action="">

              
             <input type="hidden" class="nextdt_id" name="nextdt_id" value=""> 

             <input type="hidden" class="eid" name="eid" value=""> 
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
                      <option value="Civil" >Civil</option>
                      <option value="Criminal" >Criminal</option>
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
                   <input class="form-control edit_client_phone" name="edit_client_phone" placeholder="Client Phone" type="text" value="">
                </div>
             </div>
             <div class="col-md-6">
                <div class="form-group">
                   <label>Email :</label>
                   <input class="form-control edit_client_email" name="edit_client_email" placeholder="Client Email" type="text" value="">
                </div>
             </div>


             <div class="col-md-6 col-xs-12">
                 <div class="form-group">
                 <label>Next Date :</label>   
                    <input class="form-control example1 edit_next_date" name="edit_next_date" placeholder="Next Date" type="text" value=""  autocomplete="off"  required>
                 </div>
              </div>
               <div class="col-md-6 col-xs-12">
                 <div class="form-group">
                 <label>Stage :</label>   
              <select class="form-control edit_stage" id="optt" name="edit_stage"  onchange="showDive(this.value)" required>
                 <option value="">-- Select Stage --</option>
                 <?php

                    $qry = "SELECT stage_name FROM tbl_stage WHERE status = '1'";
                    $result = mysqli_query($conn, $qry);
                    while ($result2 = mysqli_fetch_assoc($result)) 
                    {
                      ?>
                      <option><?php echo $result2['stage_name']; ?></option>
                <?php
                    } ?>
                   <option value="other" style="color:red"><strong>Other</strong></option>                          
              </select>
                </div>
            </div>

              <div id="hide_show_divv"  style="display: none">
                  <div class="col-md-6 col-xs-12">
                     <div class="form-group">
                     <label>New Stage :</label>
                        <input class="form-control" name="edit_newstage" placeholder="Enter New Stage" type="text"  style="background:yellow">
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
<!-- Update cases modal - End -->



  <!-- delete the cases - start -->
  <div class="zind modal fade" id="delete_casesb">
     <div class="modal-dialog">
        <div class="modal-content">
           <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
           <div class="modal-header">
              <h3><i class="pe-7s-users"></i> Delete Case</h3>
           </div>
           <div class="modal-body1 modal-body">
              <p>Are you sure you want to delete this case... </p>
              <div class="del_btn">
                 <form action="" method="post">
                    <input type="hidden" id="did" name="did" value="">
                    <button type="submit" class="btn btn-simple" name="delete_id">Yes</button>
                 </form>
                 <button class="btn btn-simple" data-dismiss="modal">No</button>
              </div>
           </div>
        </div>
     </div>
  </div>
  <!-- delete the cases - End -->



  <!-- Upload Files Against A Particular Case Selected -->  
<div class="zind modal fade" id="upload_files_cases">
    <div class="modal-dialog">
       <div class="modal-content">
          <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
          <div class="modal-header">
             <h3 class="add_title_in_upload_files"></h3>
          </div>
          <div class="modal-body">
               <form method="POST" action="upload.php" enctype="multipart/form-data">
                  <div class="form-group">
                      <input type="file" class="form-control" name="upload[]" id="file" multiple onchange="javascript:updateList()">
                   </div>
                   <input type="hidden" name="case_id" class="input_value_in_upload_files" value="">
                   <div class="form-group">   
                      <input type="submit" class="btn btn-simple" value="Upload">
                   </div>    
               </form>
               <br/>Selected files:
              <div id="fileList"></div>
          </div>
       </div>
    </div>
 </div>


 <div class="zind modal fade" id="restrict_upload_cases">
    <div class="modal-dialog">
       <div class="modal-content">
          <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
          <div class="modal-header">
             <h3 class="add_title_in_restrict_files"></h3>
          </div>
          <div class="modal-body1 modal-body">
             <p>Package Limit Crossed.Pl.contact Admin... </p>
             <div class="del_btn">
                <button class="btn btn-simple" data-dismiss="modal">Close</button>
             </div>
          </div>
       </div>
    </div>
 </div>
 <!-- Upload Files Against A Particular Case Selected --> 

 <!-- Cases History Modal Start -->
  <div class="zind modal fade" id="case_historyd">
    <div class="modal-dialog">
       <div class="modal-content case_history_modal">
       </div>
    </div>
  </div>


</section>
<!-- manage free slot end -->
<?php 
   include("footer.php"); 
?>

<script type="text/javascript" src="js/jquery.bootpag.min.js"></script>
<script type="text/javascript">
   $(document).ready(function() {
     $("#results").load("pagination_fetch_pages.php");  //initial page number to load
     $(".pagination").bootpag({
        total: <?php echo $pages; ?>,
        page: <?php echo $_SESSION['cases_all_pagination_page']; ?>,
        maxVisible: 10 
     }).on("page", function(e, num){
       e.preventDefault();
    //   $("#results").html('<div class="loading-indication"><b>Loading...</b></div>');
        $("#results").html('<div class="loading-indication"><img src="assets/img/ajax-loader.gif" /></div>');
       $("#results").load("pagination_fetch_pages.php", {'page':num});
     });
   });
</script>

<script type="text/javascript">
updateList = function() {
  // alert('hi');
  var input = document.getElementById('file');
  var output = document.getElementById('fileList');

  output.innerHTML = '<ul>';
  for (var i = 0; i < input.files.length; ++i) {
    output.innerHTML += '<li>' + input.files.item(i).name + '</li>';
  }
  output.innerHTML += '</ul>';
}
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

<script type="text/javascript" src="js/jquery.bootpag.min.js"></script>
<script type="text/javascript">
   $(document).ready(function() {
     $("#results").load("pagination_fetch_pages.php");  //initial page number to load
     $(".pagination").bootpag({
        total: <?php echo $pages; ?>,
        page: <?php echo $_SESSION['cases_all_pagination_page']; ?>,
        maxVisible: 10 
     }).on("page", function(e, num){
       e.preventDefault();
    //   $("#results").html('<div class="loading-indication"><b>Loading...</b></div>');
        $("#results").html('<div class="loading-indication"><img src="assets/img/ajax-loader.gif" /></div>');
       $("#results").load("pagination_fetch_pages.php", {'page':num});
     });
   });
</script>

<script type="text/javascript">
  function court_detailsd(court_number, judge_name){
    $("#court_detailsd").modal('show');
    $(".cases_all_court_number").text(court_number);
    $(".cases_all_judge_name").text(judge_name);
  }
</script>


<script type="text/javascript">
  function client_detailsd(client_phone, client_email){
    $("#client_detailsd").modal('show');
    $(".cases_all_client_phone").text(client_phone);
    $(".cases_all_client_email").text(client_email);
  }
</script>

<!-- Client case update - Start -->   
   <script type="text/javascript">
  function update_cases(nextdt_id, case_id, court_name, court_number, case_number, judge_name, category, client_name, client_phone, client_email, file_no, party_a, party_b, next_date, next_stage){
   
   document.getElementById('hide_show_divv').style.display = "none";

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
   $('#update_cases').modal('show');
  }
</script>
<!-- Client case update - End -->


<!-- Delete cases - Start -->
<script type="text/javascript">
  function deleteCases(id){
    $("#did").val(id);
    $("#delete_casesb").modal('show');
  }
</script>
<!-- Delete cases - End -->


<script type="text/javascript">
  function upload_files(case_id,cases_number){
    $('.add_title_in_upload_files').text("Upload Files For Case "+cases_number);
    $('.input_value_in_upload_files').val(case_id);
    $('#fileList').text("");
    $("#file").val(null);
    $('#upload_files_cases').modal('show');
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