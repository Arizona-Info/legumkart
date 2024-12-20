      <?php 
   //error_reporting(0);
   include("header.php"); 
   $page = 'find_intern.php';
   $item_per_page = 10;
   $string_query = "";

   if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] == "Counsel" || $_SESSION['user_type'] == "Intern")
   {
     echo  '<script>window.location="index.php"</script>';
   }

   if(isset($_POST['search_value'])){
      $_SESSION['intern_search'] = $_POST['search_value'];
      $_SESSION['intern_pagination_page'] = 1;
   }
   else if(!isset($_SESSION['intern_search'])){
      $_SESSION['intern_search'] = "";
      $_SESSION['intern_pagination_page'] = 1;
   }

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

   if(isset($_POST['send_msg'])) {
     
     $client_email = 'admin@legumkart.com';
     $intern_id=$_POST['intern_id'];
     $lawyer_message=$_POST['lawyer_message'];    
     // first we will insert data in child table 

     $stmt = mysqli_query($conn,"INSERT INTO tbl_intern_lawyermsg(intern_id, firm_id, lawyer_id, lawyer_msg, msg_date) VALUES('".$intern_id."','".$_SESSION['user_firm_id']."','".$_SESSION['user_id']."','".mysqli_real_escape_string($conn, $lawyer_message)."','".date('Y-m-d')."')");
      echo  '<script>alert("Message successfully sent to Intern")</script>';
    
    // by below query we will fetch all details related to firm or lawyer
    $sql="SELECT firm_name,email,phone,address FROM tbl_lawyers
                WHERE firm_id='".$_SESSION['user_firm_id']."' AND lawyer_id ='".$_SESSION['user_id']."'";
    $run_sql = mysqli_query($conn,$sql);
    $result=mysqli_fetch_assoc($run_sql);            
     
     include("PHPMailer/PHPMailerAutoload.php");
                
                $to    = $_POST['intern_email'];
            
                $mails = new PHPMailer;
                $mails->isSMTP();                                       // Set mailer to use SMTP
                $mails->Host = 'legumkart.com';                  // Specify main and backup SMTP servers
                $mails->SMTPAuth = true;                               // Enable SMTP authentication
                $mails->Username = 'admin@legumkart.com';                                // SMTP username
                $mails->Password = 'admin@123908';                         // SMTP password
                $mails->SMTPSecure = 'ssl';                             // Enable TLS encryption, `ssl` also accepted
                $mails->Port =  465;                                    // TCP port to connect to
                
                $mails->setFrom ($client_email);
                $mails->addAddress($to); 
                //$mails->addReplyTo($client_email);
    
                $mails->Subject = "Contact";

    


               $message = 'Name: '.$result['firm_name']."\n\n".
                       'Email: '.$result['email']."\n\n".
                           'Phone: '.$result['phone']."\n\n".
                           'Address: '.$result['address']."\n\n".
                     'Message: '.$lawyer_message;                                              
            $mails->Body  = $message;


            if($mails->send()){
                echo '<script>alert("Your message has been successfully sent");</script>';     
            }else{
                 echo '<script>alert("Error! Please Try Again.");</script>';     
            }
        }
         



   if(isset($_POST['query_str']) || isset($_POST['query_str2'])){
      
      $query_str = $_POST['query_str'];
      $query_str2 = $_POST['query_str2'];
      $query_str = test_input($query_str); // function called to check unnecessary entries i.e. security check
      $_SESSION['query_str'] = $query_str;
      $_SESSION['query_str2'] = $query_str2;

      if($query_str != "" && $query_str2 != ""){
          $sql_search="SELECT * FROM tbl_intern
                WHERE ((intern_location LIKE '%".$query_str."%' OR intern_address LIKE '%".$query_str."%') AND intern_interest LIKE '%".$query_str2."%')".$string_query;     
      }
      else if($query_str != ""){
          $sql_search="SELECT * FROM tbl_intern
                WHERE (intern_location LIKE '%".$query_str."%' OR intern_address LIKE '%".$query_str."%')".$string_query; 
      }
      else if($query_str2 != ""){
          $sql_search="SELECT * FROM tbl_intern
                WHERE (intern_interest LIKE '%".$query_str2."%')".$string_query; 
      }
      else{
          $sql_search="SELECT * FROM tbl_intern WHERE intern_interest = 'NA'";
      }
          

      }
      else if(isset($_SESSION['query_str']) && isset($_SESSION['query_str2']) && ($_SESSION['query_str'] != "" || $_SESSION['query_str2'] != "")){
          $query_str = $_SESSION['query_str'];
          $query_str2 = $_SESSION['query_str2'];

          if($query_str != "" && $query_str2 != ""){
              $sql_search="SELECT * FROM tbl_intern
                    WHERE (intern_location LIKE '%".$query_str."%' OR intern_address LIKE '%".$query_str."%') AND intern_interest LIKE '%".$query_str2."%'".$string_query;     
          }
          else if($query_str != ""){
              $sql_search="SELECT * FROM tbl_intern
                    WHERE intern_location LIKE '%".$query_str."%' OR intern_address LIKE '%".$query_str."%'".$string_query; 
          }
          else if($query_str2 != ""){
              $sql_search="SELECT * FROM tbl_intern
                    WHERE intern_interest LIKE '%".$query_str2."%'".$string_query; 
          }
          else{
              $sql_search="SELECT * FROM tbl_intern WHERE intern_interest = 'NA'";
          }
      }
      else{
        $_SESSION['query_str'] = "";
        $_SESSION['query_str2'] = "";
       $sql_search="SELECT * FROM tbl_intern WHERE intern_interest = 'NA'";
      }

   $search_result = mysqli_query($conn,$sql_search);
   
     function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }  
   

   ?>
<link rel="stylesheet" type="text/css" href="css/awesome-bootstrap-checkbox.css">
<link rel="stylesheet" href="css/multiple-select.css" />
<!-- manage free slot start -->
<section class="manage_freeslot">
   <div class="container-fluid">
      <div class="row">
         <div class="col-xs-12">
            <?php include("sidebar.php"); ?>
            <div class="col-sm-9 col-xs-12">
               <div class="right_panel">
                  <h3 class="right1">Find Interns</h3>
               </div>
               <form action="" method="post"  enctype="multipart/form-data">
                  <div class="col-xs-12 padding-left-none">
                     <div class="right3 padding-top-15">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label class="col-md-12" for="">Location :</label>
                              <div class="col-md-12">
                                 <input class="form-control" name="query_str" placeholder="Location" type="text" value="<?php 
                                 if(isset($_POST['query_str']))
                                  {
                                    echo $_POST['query_str'];
                                  }
                                  else if(isset($_SESSION['query_str'])){
                                    echo $_SESSION['query_str'];
                                  }
                                ?>">
                              </div>
                           </div>
                        </div>

                        <div class="col-md-6">
                           <div class="form-group">
                              <label class="col-md-12" for="">Area of interest :</label>
                              <select class="form-control col-md-12" name="query_str2">
                                  <option value="">Select Specialization</option>
                                  <?php
                                    $query_for_spec = "SELECT pa_name FROM tbl_practice_areas";
                                    $result_of_spec = mysqli_query($conn, $query_for_spec);
                                    while ($result_of_spec2 = mysqli_fetch_assoc($result_of_spec)) {
                                  ?>
                                    <option value="<?php echo $result_of_spec2['pa_name']; ?>" <?php 
                                    if(isset($_POST['query_str2']) && $_POST['query_str2'] == $result_of_spec2['pa_name'])
                                      {
                                        echo "selected";
                                      }
                                      else if(isset($_SESSION['query_str2']) && $_SESSION['query_str2'] == $result_of_spec2['pa_name']){
                                        echo "selected";
                                      }
                                    ?>><?php echo $result_of_spec2['pa_name']; ?></option>
                                  <?php
                                    }
                                  ?>
                              </select>
                           </div>
                        </div>

                        <button type="submit" name="seach_intern" class="btn btn-dark">Search</button>
                     </div>
                  </div>
               </form>
               <div class="clearfix"></div>
               <hr>

                <div class="col-xs-12">
                  <div class="table-responsive dataTables_wrapper">

                      <?php
                        $count_intern_available = mysqli_num_rows($search_result);
                        $pages = ceil($count_intern_available/$item_per_page);
                      ?>
                      <input type="hidden" id="total_intern_available" value="<?php echo $count_intern_available; ?>"></input>

                     <table id="" class="display dataTable responsive nowrap" cellspacing="0" width="100%">
                        <thead>

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
                          <input type="search" name="search_value" value="<?php echo $_SESSION['intern_search']; ?>" placeholder="Search here" aria-controls="attendenceDetailedTable"></input>
                          <input type="submit" value="Search" class="btn btn-dark btn-sm"></input>
                        </form>
                      </div>
                           <tr>
                              <th></th>
                              <th>Intern/Junior Name</th>
                              <th>Applying as</th>
                              <th>Address</th>
                              <th>Phone</th>
                              <th>Email</th>
                              <!-- <th>Languages Known</th>
                              <th>Short Description</th>
                              <th>Preferred Location</th>
                              <th>Enrollment Number</th>
                              <th>Date of Enrollment</th>
                              <th>Action</th> -->
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
         <hr>
      </div>
   </div>
</section>

<div class="zind modal fade" id="view_intern_address">
      <div class="modal-dialog">
         <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
            <div class="modal-header">
               <h3 id="contain_inter_name"></h3>
            </div>
            <div class="modal-body">
              <p id="contain_intern_address"></p>
            </div>
         </div>
      </div>
   </div>



   <div class="zind modal fade" id="send_mail">
          <div class="modal-dialog">
             <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                <div class="modal-header">
                   <h3>Compose Message For Intern</h3>
                </div>
                <div class="modal-body">
                   <form class="form" role="form" method="post" action=""  autocomplete="off">
                    <div class="row">
                      <input type="hidden" class="form-control" id="intern_id" name="intern_id" value="">  
                      <input type="hidden" class="form-control" id="intern_email" name="intern_email" value="">   
                      <div class="col-xs-12">
                         <div class="form-group">
                          <textarea class="form-control" name="lawyer_message" placeholder="Enter Message For Intern" rows="3"></textarea>
                         </div>
                      </div>

                      <div class="form-group col-xs-12">
                         <button type="submit" class="btn btn-simple" name="send_msg">Send</button>
                      </div>
                    </div>
                   </form>
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
     $("#results").load("pagination_fetch_pages_find_intern.php");  //initial page number to load
     $(".pagination").bootpag({
        total: <?php echo $pages; ?>,
        page: <?php echo $_SESSION['intern_pagination_page']; ?>,
        maxVisible: 10 
     }).on("page", function(e, num){
       e.preventDefault();
    //   $("#results").html('<div class="loading-indication"><b>Loading...</b></div>');
        $("#results").html('<div class="loading-indication"><img src="assets/img/ajax-loader.gif" /></div>');
       $("#results").load("pagination_fetch_pages_find_intern.php", {'page':num});
     });
   });
</script>


<script src="js/multiple-select.js"></script> 
<script>
   $(function() {
           $('#answer1').multipleSelect({
               width: '100%'
           });
       });
       $(function() {
           $('#answer2').multipleSelect({
               width: '100%'
           });
       });
       $(function() {
           $('#answer3').multipleSelect({
               width: '100%'
           });
       });
        $(function() {
           $('#answer4').multipleSelect({
               width: '100%'
           });
       });
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

  function viewAddress(name, address){
    $("#contain_inter_name").text("Address ("+name+")");
    $("#contain_intern_address").text(address);
    $("#view_intern_address").modal('show');
  }

  function messageModal(id, email){
    $("#intern_id").val(id);
    $("#intern_email").val(email);
    $("#send_mail").modal('show');
  }
</script>