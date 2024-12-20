


<?php

include ('db_connection.php');
$string_query = "";
$item_per_page = 10;
if(isset($_POST['page'])){
	$_SESSION['intern_pagination_page'] = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
	$page_number = $_SESSION['intern_pagination_page'];
	if(!is_numeric($page_number)){
	 	die('Invalid page number!');
	 	$_SESSION['intern_pagination_page'] = 1;
	}
}
else
{
	$page_number = $_SESSION['intern_pagination_page'];
}


$position = (($page_number-1) * $item_per_page);


if(isset($_SESSION['intern_search']) && $_SESSION['intern_search'] != ""){
	$search_result12 = strtoupper($_SESSION['intern_search']);

    set_error_handler (
        function($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, $errno, 0, $errfile, $errline);     
        }
    );

    try {
      $date_query = date_create($search_result12);
      $date_query = "  OR intern_enrolldate = '".date_format($date_query,"Y-m-d")."'";
    }
    catch(Exception $e) {
      $date_query = "";
    }

    $string_query = " AND (intern_enrollnumber like '%".$search_result12."%' OR intern_location like '%".$search_result12."%' OR intern_about LIKE '%".$search_result12."%' OR intern_email LIKE '%".$search_result12."%' OR intern_phone LIKE '%".$search_result12."%' OR intern_address LIKE '%".$search_result12."%' OR intern_applyingas LIKE '%".$search_result12."%' OR intern_name LIKE '%".$search_result12."%'".$date_query.")";
}


	if(isset($_SESSION['query_str']) && isset($_SESSION['query_str2'])){
      
      $query_str = $_SESSION['query_str'];
      $query_str2 = $_SESSION['query_str2'];
      $query_str = test_input($query_str); // function called to check unnecessary entries i.e. security check

      if($query_str != "" && $query_str2 != ""){
          $sql_search="SELECT * FROM tbl_intern
                WHERE ((intern_location LIKE '%".$query_str."%' OR intern_address LIKE '%".$query_str."%') AND intern_interest LIKE '%".$query_str2."%')".$string_query." LIMIT $position, $item_per_page";     
      }
      else if($query_str != ""){
          $sql_search="SELECT * FROM tbl_intern
                WHERE (intern_location LIKE '%".$query_str."%' OR intern_address LIKE '%".$query_str."%')".$string_query." LIMIT $position, $item_per_page"; 
      }
      else if($query_str2 != ""){
          $sql_search="SELECT * FROM tbl_intern
                WHERE (intern_interest LIKE '%".$query_str2."%')".$string_query." LIMIT $position, $item_per_page"; 
      }
      else{
          $sql_search="SELECT * FROM tbl_intern WHERE intern_interest = 'NA' LIMIT $position, $item_per_page";
      }          

    }
    else{
    	echo '<div class="no_result"><img src="img/about.png"><p>Something went wrong, please try again.</p></div>'; 
        // echo "<input type='hidden' class='NumberOfCases' value = 'Showing 0 To 0 Of '>";
    	exit();
    }

   $search_result = mysqli_query($conn,$sql_search);

     function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    } 
    $i = 0;

         if(mysqli_num_rows($search_result)>0){  
        while($results = mysqli_fetch_assoc($search_result)){         
        	$i+=1;
      ?>                         
         <tr>
         	<?php
         		echo "<td><a href='#' class='open_cases_details".$i." open_cases_details' onclick='open_cases_details(this,".$i.")' ><i class='fa fa-plus-circle' aria-hidden='true'></i></a>
    				<a href='#' class='close_cases_details".$i." close_cases_details' onclick='close_cases_details(this,".$i.")' style='display:none' ><i class='fa fa-minus-circle' aria-hidden='true'></i></a>
  					</td>";
         	?>
            <td><?php echo $results['intern_name'];?></td>
            <td><?php echo $results['intern_applyingas'];?></td>
            <td><u>
            	<a href="#" onclick="viewAddress(<?php echo "'".$results['intern_name']."','".$results['intern_address']."'";?>)" data-toggle="modal" title="Address view">View</a>
            </u></td>
            <td><?php echo $results['intern_phone'];?></td>
            <td><?php echo $results['intern_email'];?></td>

            <?php
			   echo "</tr>
			    <tr class='show_data_on_click".$i." hide_data_on_click' style='display:none'>
			    <td colspan='6'>
			    <table>";
			?>

			<tr>
				<td>Languages Known</td>
            	<td><?php echo $results['intern_languages'];?></td>
            </tr>

            <tr>
            	<td>Short Description</td>
            	<td><?php echo $results['intern_about'];?></td>
            </tr>

            <tr>
            	<td>Preferred Location</td>
            	<td><?php echo $results['intern_location'];?></td>
            </tr>

           
            <?php
              if(isset($results['intern_applyingas']) && $results['intern_applyingas'] == "Intern"){
            ?>
            	<tr>
            		<td>Enrollment Number</td>
              		<td>NA</td>
              	</tr>

              	<tr>
              		<td>Date of Enrollment</td>
              		<td>NA</td>
              	</tr>
            <?php 
              }else if(isset($results['intern_applyingas']) && $results['intern_applyingas'] == "Junior Advocate"){ 
            ?>
            	<tr>
            		<td>Enrollment Number</td>
              		<td><?php echo $results['intern_enrollnumber'];?></td>
              	</tr>

              	<tr>
            		<td>Date of Enrollment</td>
              		<td><?php 
                  		$date=date_create($results['intern_enrolldate']);
                  		echo date_format($date,"d M Y");
                  		?></td>
                </tr>

            <?php } else { ?>
            	<tr>
            		<td>Enrollment Number</td>
              		<td>NA</td>
              	</tr>

              	<tr>
              		<td>Date of Enrollment</td>
              		<td>NA</td>
              	</tr>
            <?php 
              } ?>

            <tr>
            	<td>Action</td>
            	<td class="action">
                
                	<?php if($results['upload_resume'] != ""){ ?>
               		<a target="_blank" href="img/intern_resumes/<?php echo $results['upload_resume'];?>" class="btn btn_sm1" title="View Resume"><i class="fa fa-eye" aria-hidden="true"></i></a>
               		<?php } ?>

               		<a href="#" onclick="messageModal(<?php echo "'".$results['intern_id']."','".$results['intern_email']."'"; ?>)" class="btn btn_sm1" title="Send Mail"><i class="fa fa-envelope" aria-hidden="true"></i></a>
                  <!-- Add Cases -->
               	</td>
            </tr>
   	</table>
   	</td>
       </tr>
   <?php }
   echo "<input type='hidden' class='NumberOfCases' value = 'Showing ".((($page_number - 1)*10)+1)." To ".((($page_number - 1)*10)+$i)." Of '>";

}
else{
	echo '<tr><td colspan="6"><div class="col-sm-7 col-xs-12 padding-right-none"><div class="no_result"><img src="img/about.png"><p>No Results Found.</p></div></div></td></tr>';
    echo "<input type='hidden' class='NumberOfCases' value = 'Showing 0 To 0 Of '>";

}



   ?>