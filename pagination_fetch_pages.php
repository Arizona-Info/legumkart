<?php
  include ('db_connection.php');

  $string_query = "";
  $item_per_page = 10;
  
  //sanitize post value

  if(isset($_POST['page'])){
    $_SESSION['cases_all_pagination_page'] = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
    $page_number = $_SESSION['cases_all_pagination_page'];
    if(!is_numeric($page_number)){
      die('Invalid page number!');
      $_SESSION['cases_all_pagination_page'] = 1;
    }
  }
  else
   {
    $page_number = $_SESSION['cases_all_pagination_page'];
   }

   
  //get current starting point of records
   $position = (($page_number-1) * $item_per_page);
  ?>

  <?php 
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

  $flag_delete = mysqli_query($conn,"SELECT firm_id FROM tbl_lawyers WHERE lawyer_id='".$_SESSION['user_id']."' AND type = '".$_SESSION['user_type']."' ");
      $flag_delete = mysqli_fetch_array($flag_delete);
       
       
      if($_SESSION['user_type']=='firm' && $_SESSION['user_id'] != "")
      {
        $lawyerqry = mysqli_query($conn,"SELECT t1.file_no,t1.case_id,t2.firm_name,t1.court_name,t1.court_number,t1.judge_name,t1.case_number,t1.client_email,t1.next_date,t1.party_a,t1.party_b,t1.stage,t1.client_name,t1.category,t1.client_phone FROM tbl_cases t1,  tbl_lawyers t2 WHERE ((t1.lawyer_id=t2.lawyer_id AND t2.firm_id='".$_SESSION['user_id']."') OR (t1.lawyer_id=t2.lawyer_id AND  t2.lawyer_id='".$_SESSION['user_id']."')) ".$string_query." ORDER BY t1.orderby_date DESC LIMIT $position, $item_per_page");
        
        $lawyerqry2 = mysqli_query($conn,"SELECT COUNT(t1.case_id) AS cnt FROM tbl_cases t1,  tbl_lawyers t2 WHERE ((t1.lawyer_id=t2.lawyer_id AND t2.firm_id='".$_SESSION['user_id']."') OR (t1.lawyer_id=t2.lawyer_id AND  t2.lawyer_id='".$_SESSION['user_id']."'))".$string_query);

        $calc_tot = mysqli_query($conn,"SELECT SUM(t1.files_tot_size_bytes) AS tot_files_size FROM tbl_cases t1,  tbl_lawyers t2 WHERE (t1.lawyer_id=t2.lawyer_id AND t2.firm_id='".$_SESSION['user_id']."') OR (t1.lawyer_id=t2.lawyer_id AND  t1.lawyer_id='".$_SESSION['user_id']."')");

          // query to get package size allocated to firm
          $showqry = mysqli_query($conn,"SELECT t3.pack_size FROM tbl_lawyer_package t1 INNER JOIN tbl_lawyers t2 ON t1.lawyer_id=t2.lawyer_id INNER JOIN tbl_package t3 ON t1.package_id=t3.pack_id WHERE t1.lawyer_id='".$_SESSION['user_id']."'");

          $flag='firm_owner';
      }
      else if($_SESSION['user_type']=='lawyer' && $_SESSION['user_id'] != "")
      {
        $qry19 = mysqli_query($conn,"SELECT firm_id FROM tbl_lawyers WHERE lawyer_id='".$_SESSION['user_id']."'");
            $result19 = mysqli_fetch_assoc($qry19);

        if(isset($result19['firm_id']) && $result19['firm_id'] == 0){
          $lawyerqry = mysqli_query($conn,"SELECT t1.*,t2.firm_name FROM tbl_cases t1, tbl_lawyers t2  WHERE t1.lawyer_id=t2.lawyer_id AND (t1.lawyer_id='".$_SESSION['user_id']."') ".$string_query." ORDER BY t1.orderby_date DESC LIMIT $position, $item_per_page");
          
          $lawyerqry2 = mysqli_query($conn,"SELECT COUNT(t1.case_id) AS cnt FROM tbl_cases t1, tbl_lawyers t2  WHERE t1.lawyer_id=t2.lawyer_id AND (t1.lawyer_id='".$_SESSION['user_id']."')".$string_query);

         $calc_tot = mysqli_query($conn,"SELECT SUM(t1.files_tot_size_bytes) AS tot_files_size FROM tbl_cases t1, tbl_lawyers t2  WHERE t1.lawyer_id=t2.lawyer_id AND (t1.lawyer_id='".$_SESSION['user_id']."')");


            // query to get package size allocated to a single lawyer
           $showqry = mysqli_query($conn,"SELECT t3.pack_size FROM tbl_lawyer_package t1 INNER JOIN tbl_lawyers t2 ON t1.lawyer_id=t2.lawyer_id INNER JOIN tbl_package t3 ON t1.package_id=t3.pack_id WHERE t1.lawyer_id='".$_SESSION['user_id']."'");

           $flag='single_lawyer'; 

        }
        else
        {
          $lawyerqry = mysqli_query($conn,"SELECT t1.*,t2.firm_name FROM tbl_cases t1, tbl_lawyers t2  WHERE t1.lawyer_id=t2.lawyer_id AND t1.lawyer_id in (SELECT lawyer_id FROM tbl_lawyers WHERE lawyer_id = '".$result19['firm_id']."' OR firm_id = '".$result19['firm_id']."') ".$string_query." ORDER BY t1.orderby_date DESC LIMIT $position, $item_per_page");
          
          $lawyerqry2 = mysqli_query($conn,"SELECT COUNT(t1.case_id) AS cnt FROM tbl_cases t1, tbl_lawyers t2  WHERE t1.lawyer_id=t2.lawyer_id AND t1.lawyer_id in (SELECT lawyer_id FROM tbl_lawyers WHERE lawyer_id = '".$result19['firm_id']."' OR firm_id = '".$result19['firm_id']."')".$string_query);

         $calc_tot = mysqli_query($conn,"SELECT SUM(t1.files_tot_size_bytes) AS tot_files_size FROM tbl_cases t1, tbl_lawyers t2  WHERE t1.lawyer_id=t2.lawyer_id AND t1.lawyer_id IN (SELECT lawyer_id FROM tbl_lawyers WHERE lawyer_id = '".$_SESSION['user_firm_id']."' OR firm_id = '".$_SESSION['user_firm_id']."') ");


          $showqry = mysqli_query($conn,"SELECT t3.pack_size FROM tbl_lawyer_package t1 INNER JOIN tbl_lawyers t2 ON t1.lawyer_id=t2.lawyer_id INNER JOIN tbl_package t3 ON t1.package_id=t3.pack_id WHERE t1.lawyer_id IN (SELECT lawyer_id FROM tbl_lawyers WHERE lawyer_id = '".$_SESSION['user_firm_id']."' OR firm_id = '".$_SESSION['user_firm_id']."')");
          
          $flag='firm_lawyers';

        }

      }
      else
      {
       echo '<tr><td colspan="6"><div class="col-sm-7 col-xs-12 padding-right-none"><div class="no_result"><img src="img/about.png"><p>No Results Found.</p></div></div></td></tr>';
       echo "<input type='hidden' class='NumberOfCases' value = 'Showing 0 To 0 Of '>";
       exit();
      }

      $row_tot_files_size=mysqli_fetch_assoc($calc_tot);

     $row_space_allocated=mysqli_fetch_assoc($showqry);
     $allocated_space_inbytes=$row_space_allocated['pack_size']*1024*1024*1024;

     $one_gb_demo_space = 1*1024*1024*1024;
     
     //check if that lawyer/firm has package
     $rows_package = mysqli_num_rows($showqry);

      $result = array();
      $i = 0;
      $j = 0;
      $nextdt_id = "";
      $next_page_number = 0;

      $lawyerqry2 = mysqli_fetch_assoc($lawyerqry2);
      $total_cases = $lawyerqry2['cnt'];

      if(mysqli_num_rows($lawyerqry)>0){
        while($lawyerrow=mysqli_fetch_assoc($lawyerqry))
        {
          if($j<10){ 

            echo "<tr>";

            $caseqry = mysqli_query($conn,"SELECT t1.next_judge,t1.nextdt_id,t1.next_case_date as MaxDate, t1.next_stage, t1.prev_case_date as PrevMaxDate,t2.lawyer_id,t2.firm_name FROM tbl_case_nextdt t1 INNER JOIN tbl_lawyers t2 ON t1.lawyer_id=t2.lawyer_id AND t1.next_case_id='".$lawyerrow['case_id']."' ORDER BY t1.next_case_date DESC LIMIT 1");

            $caserow=mysqli_fetch_assoc($caseqry);
            $countchild= mysqli_num_rows($caseqry);

            if($caserow['MaxDate'] !='') 
                { 
                  $max_date= $caserow['MaxDate'];
                  $lawyer_name=$caserow['firm_name'];
                  $judge_name=$caserow['next_judge'];
                } 
            else { 
                  $max_date=$lawyerrow['next_date']; 
                  $lawyer_name=$lawyerrow['firm_name']; 
                  $judge_name=$lawyerrow['judge_name']; 
                }

            if($caserow['next_stage'] !='') 
                { $case_stage= $caserow['next_stage'];} 
            else { $case_stage=$lawyerrow['stage']; }

            if($caserow['PrevMaxDate'] !='') 
                { $previous_date= $caserow['PrevMaxDate'];} 
            else { $previous_date='New Case'; }

            echo "<td>
                  <a href='#' class='open_cases_details".$lawyerrow['case_id']." open_cases_details' onclick='open_cases_details(this,".$lawyerrow['case_id'].")' ><i class='fa fa-plus-circle' aria-hidden='true'></i></a>
                  <a href='#' class='close_cases_details".$lawyerrow['case_id']." close_cases_details' onclick='close_cases_details(this,".$lawyerrow['case_id'].")' style='display:none' ><i class='fa fa-minus-circle' aria-hidden='true'></i></a></td>";

            if($_SESSION['user_type'] != 'lawyer'){ 
              echo "<td>".$lawyer_name."</td>";
            }
            
            if($previous_date == 'New Case'){
                echo "<td>New Case</td>";
            }
            else{
                echo "<td>".date_format(new DateTime($previous_date), 'd-M-y')."</td>";   
            }

            $court_details_list = "'".$lawyerrow['court_number']."','".$judge_name."'";
            echo '<td><u><a href="#" onclick="court_detailsd('.$court_details_list.')" data-toggle="modal" title="Court Details">'.$lawyerrow['court_name'].'</a></u></td>';
            
            if($flag_delete['firm_id'] == 0){
              $result[$i]['delete_flag'] = 1;
            }
            else{
              $result[$i]['delete_flag'] = 0;
            }

            $lawyerqry16 = "SELECT * FROM tbl_cases t1, tbl_case_nextdt t2 ,tbl_lawyers t3 WHERE t2.next_case_id='".$lawyerrow['case_id']."' AND t1.case_id=t2.next_case_id AND t2.lawyer_id=t3.lawyer_id";

            $lawyerqry18 = "SELECT t1.next_date, t1.stage, t1.judge_name, t2.firm_name FROM tbl_cases t1, tbl_lawyers t2 WHERE t2.lawyer_id = t1.lawyer_id AND t1.case_id = '".$lawyerrow['case_id']."'";

            $lawyerresults18 = mysqli_query($conn,$lawyerqry18);
            $lawyerrow18 = mysqli_fetch_assoc($lawyerresults18);

            $lawyerresults16 = mysqli_query($conn,$lawyerqry16);

            if(mysqli_num_rows($lawyerresults16)>0){

              $history_value = $lawyerrow['case_id'].",'".$lawyerrow['case_number']."'";
              echo '<td><u><a href="#" onclick="allcaseshistory('.$history_value.')" title="Case History">'.$lawyerrow['case_number'].'</a></u></td>';

        
              while($lawyerrow16=mysqli_fetch_assoc($lawyerresults16))
              {
                $nextdt_id = $lawyerrow16['nextdt_id'];
              }

            }
            else{
              echo "<td>".$lawyerrow['case_number']."</td>";
            }
            echo "<td>".$lawyerrow['party_a']." <strong>Vs</strong> ".$lawyerrow['party_b']."</td>";

            if($_SESSION['user_type'] != 'lawyer'){ 
              echo "</tr>
                    <tr class='show_data_on_click".$lawyerrow['case_id']." hide_data_on_click' style='display:none'>
                    <td colspan='6'>
                    <table>";
            }
            else{
              echo "</tr>
                    <tr class='show_data_on_click".$lawyerrow['case_id']." hide_data_on_click' style='display:none'>
                    <td colspan='5'>
                    <table>";
            }

            //get stage color 
            $stage_color_select = mysqli_query($conn,"SELECT color FROM tbl_stage WHERE stage_name = '".$case_stage."'");
            $stage_color_select = mysqli_fetch_array($stage_color_select);
            if($stage_color_select['color'] != "black"){
                echo "<tr><td><b>Stage:</b></td>
                      <td><span style='color:red'>".$case_stage."</span></td></tr>";
            }
            else{
                echo "<tr><td><b>Stage:</b></td><td>".$case_stage."</td></tr>";
            }
            
            echo "<tr><td><b>Next Date:</b></td>
                  <td>".date_format(new DateTime($max_date), 'd-M-y')."</td></tr>";

            $client_details_list = "'".$lawyerrow['client_phone']."','".$lawyerrow['client_email']."'";
            echo '<tr><td><b>Client Name:</b></td>
                  <td><u><a href="#" onclick="client_detailsd('.$client_details_list.')" data-toggle="modal" title="Court Details">'.$lawyerrow['client_name'].'</a></u></td></tr>';
            echo "<tr><td><b>Category:</b></td>
                  <td>".$lawyerrow['category']."</td></tr>";
            echo "<tr><td><b>File No.:</b></td>
                  <td>".$lawyerrow['file_no']."</td></tr>";
            echo "<tr><td><b>Action:</td><td> "
            ?>

            <div class="action_btn">
              <button class="btn btn_sm dropdown-toggle" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-tasks"></i></button>
              <ul class="dropdown-menu" aria-labelledby="actionDropdown">
                 <li>
                    <form action="lawyer_availability.php" method="post">
                        <input type="hidden" name="send_case_id" value="<?php echo $lawyerrow['case_id'];?>">
                        <button type="submit" class="btn btn_sm1" title="Send To Lawyer" name="send_to_lawyer"><i class="fa fa-upload"></i> Send To Lawyer</button>
                    </form> 
                 </li>
                 <li>
                    <form action="counsel_availability.php" method="post">
                      <input type="hidden" name="send_case_id" value="<?php echo $lawyerrow['case_id'];?>">
                      <input type="hidden" name="case_datee" value="<?php echo $max_date;?>">
                      <button type="submit" class="btn btn_sm1" title="Send To Counsel" name="send_to_counsell"><i class="fa fa-upload"></i> Send To Counsel</button>
                   </form>
                 </li>

                 <?php if($rows_package > 0)
               { ?>
                 <li>
                    <?php   
                     // For Package Users With 1 GB 
                   if($row_tot_files_size['tot_files_size'] < $allocated_space_inbytes)
                    {  ?>
                    <a href="#" class="btn btn_sm1" onclick="upload_files('<?php echo $lawyerrow['case_id']; ?>','<?php echo $lawyerrow['case_number']; ?>')" title="Upload Files"><i class="fa fa-upload"></i> Upload Files</a>
                    <?php    } else { ?>
                        <a href="#" class="btn btn_sm1" onclick="restrict_upload('<?php echo $lawyerrow['case_number']; ?>')" title="Upload Files"><i class="fa fa-upload"></i> Upload Files</a>
                     <?php    } ?>
                 </li>
                 <li>
                   <form action="upload.php" method="post">
                        <input type="hidden" name="view_case_id" value="<?php echo $lawyerrow['case_id'];?>">
                        <button type="submit" class="btn btn_sm1" title="View Uploaded case files" name="viewcasefiles"><i class="fa fa-eye" aria-hidden="true"></i> View Uploaded Files</button>
                     </form>
                 </li>
                 <?php 
               } else 
               {  ?>
                 
                 <li>
                    <?php    
                    // For Demo Users With 1 GB
                   if($row_tot_files_size['tot_files_size'] < $one_gb_demo_space)
                    {  ?>
                    <a href="#" class="btn btn_sm1" onclick="upload_files('<?php echo $lawyerrow['case_id']; ?>','<?php echo $lawyerrow['case_number']; ?>')" data-toggle="modal" title="Upload Files"><i class="fa fa-upload"></i> Upload Files</a>
                    <?php    } else { ?>
                        <a href="#" class="btn btn_sm1" onclick="restrict_upload('<?php echo $lawyerrow['case_number']; ?>')" data-toggle="modal" title="Upload Files"><i class="fa fa-upload"></i> Upload Files</a>
                     <?php   } ?>
                 </li>
                 <li>
                   <form action="upload.php" method="post">
                        <input type="hidden" name="view_case_id" value="<?php echo $lawyerrow['case_id'];?>">
                        <button type="submit" class="btn btn_sm1" title="View Uploaded case files" name="viewcasefiles"><i class="fa fa-eye" aria-hidden="true"></i> View Uploaded Files</button>
                     </form>
                 </li>
               <?php 
             } ?>

                 <li>
                    <a href="#" class="btn_sm1" onclick="update_cases(<?php echo "'".$nextdt_id."',".$lawyerrow['case_id'].",'".$lawyerrow['court_name']."','".$lawyerrow['court_number']."','".$lawyerrow['case_number']."','".$judge_name."','".$lawyerrow['category']."','".$lawyerrow['client_name']."','".$lawyerrow['client_phone']."','".$lawyerrow['client_email']."','".$lawyerrow['file_no']."','".$lawyerrow['party_a']."','".$lawyerrow['party_b']."','".$max_date."','".$case_stage."'"; ?>)" title="Edit"><i class="fa fa-edit"></i> Edit</a>
                 </li>
                 <?php
                    if(isset($_SESSION['user_firm_id']) && $_SESSION['user_firm_id'] == 0)
                       {
                    ?>
                 <li>
                    <a href="#" class="btn btn_sm1" onclick="deleteCases('<?php echo $lawyerrow['case_id']; ?>')" title="Delete"><i class="fa fa-trash"></i> Delete</a>
                 </li>
                 <?php } ?>
              </ul>
           </div>

            <?php
            echo "</td>
                  </tr>
                  </table>
                  </td>";

            $next_page_number = 0;
            $i += 1;
          }
          else{
            // $next_page_number = $_REQUEST['page_number'] + 1;
          }
          $j+=1;

          echo "</td></tr>";
        }

        echo "<input type='hidden' class='NumberOfCases' value = 'Showing ".((($page_number - 1)*10)+1)." To ".((($page_number - 1)*10)+$i)." Of '>"; 
      }
      else 
        {  
          echo '<tr><td colspan="6"><div class="col-sm-7 col-xs-12 padding-right-none"><div class="no_result"><img src="img/about.png"><p>No Results Found.</p></div></div></td></tr>';
          echo "<input type='hidden' class='NumberOfCases' value = 'Showing 0 To 0 Of '>";
        } ?>


