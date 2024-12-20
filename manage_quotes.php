<?php   
include("header.php"); 
$page = 'manage_quotes.php';
$item_per_page = 10;
$pages_manage_quotes = 0;
$string_query = "";

if(!isset($_SESSION['user_id'])) 
{
   echo  '<script>window.location="index.php"</script>';
}


if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] == "Counsel" || $_SESSION['user_type'] == "Intern")
   {
     echo  '<script>window.location="index.php"</script>';
   }

$emp_ids ='';

if($_SESSION['user_firm_id'] != 0)
{
    $user_qry = mysqli_query($conn,"SELECT lawyer_id FROM tbl_lawyers WHERE firm_id='".$_SESSION['user_firm_id']."' OR lawyer_id='".$_SESSION['user_firm_id']."'"); 
    while($totuserrows = mysqli_fetch_array($user_qry))
    {
      $emp_ids.=$totuserrows['lawyer_id'].",";
    }
    $emp_ids = chop($emp_ids,",");
}

if($_SESSION['user_type'] == 'firm')
{
    
    $user_qry = mysqli_query($conn,"SELECT lawyer_id FROM tbl_lawyers WHERE firm_id='".$_SESSION['user_id']."' OR lawyer_id='".$_SESSION['user_id']."'"); 
    while($totuserrows = mysqli_fetch_array($user_qry))
    {
      $emp_ids.=$totuserrows['lawyer_id'].",";
    }
    $emp_ids = chop($emp_ids,",");
}


if(isset($_POST['search_value'])){
  $_SESSION['manage_quotes_search'] = $_POST['search_value'];
  $_SESSION['manage_quotes_pagination_page'] = 1;
}
else if(!isset($_SESSION['manage_quotes_search'])){
  $_SESSION['manage_quotes_search'] = "";
  $_SESSION['manage_quotes_pagination_page'] = 1;
}


if(isset($_SESSION['manage_quotes_search']) && $_SESSION['manage_quotes_search'] != ""){
    $search_result12 = strtoupper($_SESSION['manage_quotes_search']);
    set_error_handler (
        function($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, $errno, 0, $errfile, $errline);     
        }
    );

    try {
      $date_query = date_create($search_result12);
      $date_query = "  OR quote_date = '".date_format($date_query,"Y-m-d")."'";
    }
    catch(Exception $e) {
      $date_query = "";
    }
    
    $string_query = " AND (cust_name like '%".$search_result12."%' OR cust_phone like '%".$search_result12."%' OR cust_email like '%".$search_result12."%' OR quote_type like '%".$search_result12."%' OR status like '%".$search_result12."%' OR cust_query like '%".$search_result12."%' ".$date_query.")";
}


   
if(isset($_POST['searchfilter']))
{  
  $str1 = $str2 = $str3 = $str4 ="";

  if(isset($_POST['status']) && $_POST['status']!='')
  { 
    $statuss= "'".implode("','", $_POST['status'])."'";
    $str1 = "AND status IN (".$statuss.")";
  }

  if(isset($_POST['client_name']) && $_POST['client_name']!='')
  {
    $str2 = "AND cust_name='".$_POST['client_name']."'";
  }

  if((isset($_POST['start_date']) && isset($_POST['end_date']) && $_POST['start_date']!='' && $_POST['end_date']!=''))
  {
    $str3 = "AND quote_date BETWEEN '".date_format (new DateTime($_POST['start_date']),'Y-m-d')."' AND '".date_format (new DateTime($_POST['end_date']),'Y-m-d')."'";
  }

  if($emp_ids != '') 
    {
      $str4 = "lawyer_id IN ($emp_ids)";
    }
  else
    {
      $str4 = "lawyer_id = '".$_SESSION['user_id']."'";
    }

    $_SESSION['advance_search'] = $str4." ".$str2." ".$str3." ".$str1;

   $searchqry = "SELECT * FROM tbl_quotes WHERE ".$_SESSION['advance_search'].$string_query." ORDER BY quote_date DESC";

}
else
{
  if(isset($_GET['back'])){
    $_SESSION['advance_search'] = "";  
  }
  
  if($emp_ids != '') 
    {
      $searchqry = "SELECT * FROM tbl_quotes WHERE lawyer_id IN ($emp_ids) ".$string_query." ORDER BY quote_date DESC";
    }
  else
    {
      $searchqry = "SELECT * FROM tbl_quotes WHERE lawyer_id='".$_SESSION['user_id']."'".$string_query." ORDER BY quote_date DESC";
    }
}  

 if(isset($_POST['delete_quote']))
{
    $did = $_REQUEST['did'];  
    $dellawyer = mysqli_query($conn,"DELETE FROM tbl_quotes where quote_id = '".$did."'");
}


?>
<!-- manage free slot start -->
<section class="manage_freeslot">
   <div class="container-fluid">
   <div class="row">
   <div class="col-md-12 col-xs-12">
     <?php include("sidebar.php"); ?>
      <div class="col-md-9 col-sm-9 col-xs-12 manage">
         <div class="right_panel">
        <?php 
         if(!isset($_POST['searchfilter']) && isset($_SESSION['advance_search']) && $_SESSION['advance_search'] == "")
           { 
         ?>
            <h3>Manage Legal Queries  <a href="#search_lawyer" data-toggle="modal" class="btn btn-dark add_new" style="text-transform:initial;">Advance Search</a><b class="add_new total_count_view">Loading...</b></h3>
        <?php 
            }
         else
            {  ?>
             <h3>Manage Queries  <a href="manage_quotes.php?back" class="btn btn-dark add_new" style="text-transform:initial;">Go Back</a><b class="add_new total_count_view">Loading...</b></h3>
        <?php  
            }

            $searchresult2 = mysqli_query($conn,$searchqry);
            $resno = mysqli_num_rows($searchresult2);
            $pages_manage_quotes = ceil($resno/$item_per_page);
        ?>           


            <div class="zind modal fade" id="search_lawyer">
               <div class="modal-dialog">
                  <div class="modal-content">
                     <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                     <div class="modal-header">
                        <h3>Filter Result</h3>
                     </div>
                     <div class="modal-body">
                        <form class="form" role="form" method="post" action="manage_quotes.php">
                           <div class="col-md-12 col-xs-12 ">
                              <div class="form-group">
                                 <input class="form-control" name="client_name" placeholder="Search Client Name" type="text" value="">
                              </div>
                           </div>
                           <div class="col-md-12 col-xs-12">
                              <div class="form-group">
                                 <label class="col-md-12 col-xs-12" style="padding-left:0;">Status :</label>
                                 <div class="checkbox-inline">
                                    <input name="status[]" type="checkbox" id="styled-checkbox1" value="New">
                                    <label for="styled-checkbox1">New</label>
                                 </div>
                                 <div class="checkbox-inline">
                                    <input name="status[]" type="checkbox" id="styled-checkbox2" value="Pending">
                                    <label for="styled-checkbox2">Pending</label>
                                 </div>
                                 <div class="checkbox-inline">
                                    <input name="status[]" type="checkbox" id="styled-checkbox3" value="Accepted">
                                    <label for="styled-checkbox3">Accepted</label>
                                 </div>
                                 <div class="checkbox-inline">
                                    <input name="status[]" type="checkbox" id="styled-checkbox4" value="Declined">
                                    <label for="styled-checkbox4">Declined</label>
                                 </div>
                                 <div class="checkbox-inline">
                                    <input name="status[]" type="checkbox" id="styled-checkbox5" value="Closed">
                                    <label for="styled-checkbox5">Closed</label>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6 col-xs-6">
                              <div class="form-group">
                                 <input name="start_date" type="text" placeholder="Start Date" class="form-control example1" autocomplete="off">
                              </div>
                           </div>
                           <div class="col-md-6 col-xs-6">
                              <div class="form-group">
                                 <input name="end_date" type="text" placeholder="End Date" class="form-control example1" autocomplete="off">
                              </div>
                           </div>
                           <div class="form-group">
                              <button type="submit" class="btn btn-simple" name="searchfilter">Search</button>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
            <div class="table-responsive dataTables_wrapper">
               <table id="" class="display dataTable responsive nowrap" cellspacing="0" width="100%">

               <input type="hidden" id="total_matter_receive" value="<?php echo $resno; ?>"></input>

              <div class="dataTables_length">
                <label>Show 
                  <select name="attendenceDetailedTable_length" aria-controls="attendenceDetailedTable" class="">
                    <option value="10">10</option>
                  </select>
                  entries
                </label>
              </div>

              <div class="dataTables_filter">
                <form method="post" autocomplete="off" action="manage_quotes.php">
                  <input type="search" name="search_value" value="<?php echo $_SESSION['manage_quotes_search']; ?>" placeholder="Search here" aria-controls="attendenceDetailedTable"></input>
                  <input type="submit" value="Search" class="btn btn-dark btn-sm"></input>
                </form>
              </div>

                  <thead>
                     <tr>
                        <th>Date</th>
            <?php 
            if($_SESSION['user_firm_id'] != 0 || $_SESSION['user_type'] == 'firm')
                  {
            ?>
                        <th>Lawyer Name</th>
            <?php } ?>            
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Quote Type</th>
                        <th>Query</th>
                        <th>Status</th>
                        <th>Action</th>
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
</section>


<div class="zind modal fade" id="query">
    <div class="modal-dialog">
       <div class="modal-content">
          <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
          <div class="modal-header">
             <h3>Query</h3>
          </div>
          <div class="modal-body">
             <p id="query_from_client"></p>
          </div>
       </div>
    </div>
</div>


<div class="zind modal fade" id="delete_lawyer">
    <div class="modal-dialog">
       <div class="modal-content">
          <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
          <div class="modal-header">
             <h3>Delete Quote</h3>
          </div>
          <div class="modal-body1 modal-body">
             <p>Are you sure you want to delete this Quote Request... </p>
             <div class="del_btn">
              <form action="" method="post">
                <input type="hidden" name="did" id="delete_quotes" value="">
                <button type="submit" class="btn btn-simple" name="delete_quote">Yes</button>
                <button type="button" data-dismiss="modal" class="btn btn-simple">No</button>
              </form>
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

    var counsel = setInterval(function(){
        $("#results").load("pagination_fetch_pages_manage_quotes.php",{limit: 25}, 
            function (responseText, textStatus, req) {
                if (textStatus == "error") {
                  console.log('error');
                }
                else{
                    clearInterval(counsel);
                    console.log(textStatus);
                }
        });  //initial page number to load        
    }, 2000);

     $(".pagination").bootpag({
        total: <?php echo $pages_manage_quotes; ?>,
        page: <?php 

          if($_SESSION['manage_quotes_pagination_page']<=$pages_manage_quotes)
            { echo $_SESSION['manage_quotes_pagination_page']; }
          else
            { 
              echo (int)$_SESSION['manage_quotes_pagination_page']-1;
              $_SESSION['manage_quotes_pagination_page'] = $_SESSION['manage_quotes_pagination_page']-1;
            }
         ?>,
        maxVisible: 10 
     }).on("page", function(e, num){
       e.preventDefault();
    //   $("#results").html('<div class="loading-indication"><b>Loading...</b></div>');
        $("#results").html('<div class="loading-indication" style="width: 25px;height: 25px"><img src="assets/img/ajax-loader.gif" /></div>');
       $("#results").load("pagination_fetch_pages_manage_quotes.php", {'page':num});
     });
   });
</script>


<script type="text/javascript">
  $(document).on("click",".row_click",function(){
    id = $(this).attr('id')
    id_number = id.split('change_color');
    color = $("#"+id).css( "color");

    if(color == "rgb(255, 0, 0)"){
      $.ajax({
        type: "post",
        url: "ajax_notification.php",
        data: { id : id_number[1] , field : "quote_receiv"},
        success:function(rec){
          $("#"+id).css( "color" , "black" );
          $("#"+id).css( "cursor" , "context-menu" );

          pre_count = $("#query_update_12").html();
          pre_count = pre_count.replace('(', '');
          pre_count = pre_count.replace(')', '');
          pre_count = Number(pre_count)-1;

          if(pre_count>0){
            $("#query_update_12").html("("+pre_count+")");
          }
          else{
            $("#query_update_12").html("");
          }

        }
      })
    }
    
  })
</script>

<script type="text/javascript">
  function openQuery(msg){
    $("#query").modal('show');
    $("#query_from_client").text(msg);
  }

  function changeStatus(currId, id, status){
    // $(currId).text('Loading..');
    // status = $(".change_status"+id).text();
    $(currId).text('Updating..');
    $(currId).attr("disabled", true);  


    var object = new Object();
    object.id = id;
    object.status = status;
    object.action = 'update_manage_quotes_status';
    object.uniqueid = '<?php echo sha1($_SESSION['user_type'].$_SESSION['user_id']."dsfg21"); ?>';

    $.post("ajax_all.php", object, function(data){
      var str = data;
      var currentStatus = "";

      if(str.includes("success")){
        if(status == "New"){
          currentStatus = 'Pending';
          $(currId).text('Pending');
          $(currId).attr("disabled", false);
        }
        else if(status == "Pending"){
          currentStatus = 'Accepted';
          $(currId).text('Accepted');
          $(currId).attr("disabled", false);
        }
        else if(status == "Accepted"){
          currentStatus = 'Declined';
          $(currId).text('Declined');
          $(currId).attr("disabled", false);
        }
        else if(status == "Declined"){
          currentStatus = 'Closed';
          $(currId).text('Closed');
          $(currId).attr("disabled", false);
        }
        else if(status == "Closed"){
          currentStatus = 'New';
          $(currId).text('New');
          $(currId).attr("disabled", false);
        }
        $(currId).attr("onclick","changeStatus(this,'"+id+"','"+currentStatus+"')");
      }
      else{
        alert(data);
        $(currId).text(status);
        $(currId).attr("disabled", false); 
      }

    });
  }


  function clickOnDelete(id){
    $("#delete_lawyer").modal('show');
    $("#delete_quotes").val(id);
  }
</script>


<script type="text/javascript">
  $(document).ready(function(){
    setInterval(function(){

      val = $(".NumberOfCases").val();
      if(typeof val === "undefined"){

      }
      else{
        totalCases = $("#total_matter_receive").val();

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