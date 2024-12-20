<?php
include ('db_connection.php');
$string_query = "";
$item_per_page = 10;
if(isset($_SESSION['condition1'])){
   $condition1 = $_SESSION['condition1'];
}
else{
   $condition1 = "id = '-1'";
}

if(isset($_POST['page'])){
  $_SESSION['jobs_pagination_page'] = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
  $page_number = $_SESSION['jobs_pagination_page'];
  if(!is_numeric($page_number)){
    die('Invalid page number!');
    $_SESSION['jobs_pagination_page'] = 1;
  }
}
else
{
  $page_number = $_SESSION['jobs_pagination_page'];
}

$position = (($page_number-1) * $item_per_page);



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

   $sr = 0;
   $str = "SELECT * FROM tbl_post_job WHERE ".$condition1.$string_query." ORDER BY date desc LIMIT $position, $item_per_page";

   $sel_jobs = mysqli_query($conn,$str);

if(mysqli_num_rows($sel_jobs)>0){

   while($list_jobs = mysqli_fetch_assoc($sel_jobs))
   {
      $law_details = mysqli_query($conn,"SELECT firm_name, type, phone, email FROM tbl_lawyers WHERE lawyer_id = '".$list_jobs['lawyer_id']."'");
      $law_view = mysqli_fetch_assoc($law_details)
      
?>

   <tr>
      <td>
         <u><a href="#" onclick="lawyerDetails(<?php
            if($law_view['type'] == 'lawyer'){
               echo "'Lawyer',"; 
            }
            else{
               echo "'Lawyer Firm',"; 
            }
            echo "'".$law_view['email']."'";
         ?>)" title="Court Details"><?php echo ucwords($law_view['firm_name']); ?></a></u>
      </td>

      <td>
         <u><a href="#" onclick="jobDetails(<?php echo "'".$list_jobs['location']."','".$list_jobs['specialization']."','".$list_jobs['description']."'"; ?>)" title="Court Details">View</a></u>
      </td>
      <td><?php 
           $date=date_create($list_jobs['date']);
           echo date_format($date,"d M Y");
       ?></td>

   </tr>
   <?php $sr+=1;
      }
      echo "<input type='hidden' class='NumberOfCases' value = 'Showing ".((($page_number - 1)*10)+1)." To ".((($page_number - 1)*10)+$sr)." Of '>";
   }
   else{
      echo '<tr><td colspan="3"><div class="col-sm-7 col-xs-12 padding-right-none"><div class="no_result"><img src="img/about.png"><p>No Results Found.</p></div></div></td></tr>';
      echo "<input type='hidden' class='NumberOfCases' value = 'Showing 0 To 0 Of '>";
   }
    ?>