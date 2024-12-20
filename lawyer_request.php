<?php 
   include("header.php"); 
   $page = 'lawyer_request.php';
   $string_query = "";

   if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'Intern')
   {
     echo  '<script>window.location="index.php"</script>';
   }


    if(isset($_POST['delete_id']))
    {
    $did = $_REQUEST['did'];  
    $dellawyer = mysqli_query($conn,"DELETE FROM tbl_intern_lawyermsg where msg_id = '".$did."'");
    echo  '<script>alert("Message Deleted successfully")</script>';
    }


   if(isset($_POST['search_value'])){
      $_SESSION['request_search'] = $_POST['search_value'];
      $_SESSION['request_pagination_page'] = 1;
   }
   else if(!isset($_SESSION['request_search'])){
      $_SESSION['request_search'] = "";
      $_SESSION['request_pagination_page'] = 1;
   }


   if(isset($_SESSION['request_search']) && $_SESSION['request_search'] != ""){
        $search_result12 = strtoupper($_SESSION['request_search']);
        set_error_handler (
            function($errno, $errstr, $errfile, $errline) {
                throw new ErrorException($errstr, $errno, 0, $errfile, $errline);     
            }
        );

        try {
          $date_query = date_create($search_result12);
          $date_query = "  OR msg_date like '%".date_format($date_query,"Y-m-d")."%'";
        }
        catch(Exception $e) {
          $date_query = "";
        }
        
        $string_query = " AND (firm_name like '%".$search_result12."%' OR email like '%".$search_result12."%' OR phone like '%".$search_result12."%' OR lawyer_msg like '%".$search_result12."%'".$date_query.")";
    }

   ?>
<link rel="stylesheet" type="text/css" href="css/awesome-bootstrap-checkbox.css">
<link rel="stylesheet" href="css/multiple-select.css" />
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
                        <h3 class="right1">Request From Lawyer</h3>
                           <b class="add_new total_count_view">Loading...</b>
                     </div>
                  </div>
               </div>
               <div class="clearfix"></div>
               <hr>

                <div class="col-md-12">
                  <div class="table-responsive dataTables_wrapper">
                  
                  <?php

                  $item_per_page = 10;

                  $sql_search="SELECT t1.msg_id,t1.firm_id,t1.lawyer_id,t1.lawyer_msg,DATE_FORMAT(t1.msg_date, '%d %M %Y') as msg_date,t2.firm_name,t2.address,t2.phone,t2.email FROM tbl_intern_lawyermsg t1 INNER JOIN tbl_lawyers t2 
                      ON t1.firm_id = t2.firm_id AND t1.lawyer_id = t2.lawyer_id AND t1.intern_id='".$_SESSION['user_id']."'".$string_query; 

                    $search_result = mysqli_query($conn,$sql_search);                   
                    $total_count = mysqli_num_rows($search_result);
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
                        <input type="search" name="search_value" value="<?php echo $_SESSION['request_search']; ?>" placeholder="Search here" aria-controls="attendenceDetailedTable"></input>
                        <input type="submit" value="Search" class="btn btn-dark btn-sm"></input>
                      </form>
                    </div>

                        <thead>
                           <tr>
                              <th>Lawyer Name</th>
                              <th>Lawyer Email</th>
                              <th>Lawyer Phone</th>
                              <th>Lawyer Message</th>
                              <th>Message Date</th>
                              <th>Action</th>
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
         <hr>
      </div>
   </div>
</section>



<div class="zind modal fade" id="details1">
    <div class="modal-dialog">
       <div class="modal-content">
          <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
          <div class="modal-header">
             <h3><i class="pe-7s-users"></i> <span id="lawyerMsgFrom"></span></h3>
          </div>
          <div class="modal-body">
             <p id="viewLawyerMessage"></p>
          </div>
       </div>
    </div>
 </div>


<div class="zind modal fade" id="delete_msg">
    <div class="modal-dialog">
       <div class="modal-content">
          <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
          <div class="modal-header">
             <h3><i class="pe-7s-users"></i> Delete Message</h3>
          </div>
          <div class="modal-body1 modal-body">
             <p>Are you sure you want to delete this message... </p>
             <div class="del_btn">
                <form action="" method="post">
                   <input type="hidden" id="deleteUniqueId" name="did" value="">
                   <button type="submit" class="btn btn-simple" name="delete_id">Yes</button>
                </form>
                <button class="btn btn-simple" data-dismiss="modal">No</button>
             </div>
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
     $("#results").load("pagination_fetch_pages_lawyer_request.php");  //initial page number to load
     $(".pagination").bootpag({
        total: <?php echo $pages; ?>,
        page: <?php echo $_SESSION['request_pagination_page']; ?>,
        maxVisible: 10 
     }).on("page", function(e, num){
       e.preventDefault();
    //   $("#results").html('<div class="loading-indication"><b>Loading...</b></div>');
        $("#results").html('<div class="loading-indication"><img src="assets/img/ajax-loader.gif" /></div>');
       $("#results").load("pagination_fetch_pages_lawyer_request.php", {'page':num});
     });
   });
</script>

<script type="text/javascript">
  function viewLawyerMessage(msg, name){
    $('#lawyerMsgFrom').text("Lawyer Message ("+name+")");
    $("#viewLawyerMessage").text(msg);
    $("#details1").modal('show');
  }

  function deleteLawyerMessage(id){
    $("#deleteUniqueId").val(id);
    $("#delete_msg").modal('show');
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