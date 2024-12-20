<?php 
   $page = 'legalQuestionDecussion';
   require_once("header.php"); 

   if(!isset($_SESSION['user_id']))
   {
     echo  '<script>window.location="index.php"</script>';
   }

   if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] == "Counsel" || $_SESSION['user_type'] == "Intern")
   {
     echo  '<script>window.location="index.php"</script>';
   }
?>

<section class="manage_freeslot">
   <div class="container-fluid">
      <div class="row">
         <div class="col-xs-12">
           <?php include("sidebar.php"); ?>
            <div class="col-sm-9 col-xs-12">
               <div class="right_panel">
                  <div class="row">
                     <div class="col-xs-12 title_stripe">
                        <h3>Legal Discussion</h3>
                     </div>
                  </div>
                  <div>
                     <section class="forum">
                        <div class=" table-responsive">
                           <table class="table">
                              <thead>
                                 <tr>
                                    <th></th>
                                    <th>Title</th>
                                    <th>Replies / Views</th>
                                    <th>Last Message</th>
                                 </tr>
                              </thead>
                           <?php
                              $item_per_page = 10;
                              $sql22 = mysqli_query($conn,"SELECT count(*) FROM tbl_forumquestion WHERE status = '1'");  
                              $get_total_rows2 = mysqli_fetch_array($sql22); 
                              //break total records into pages
                              $pages2 = ceil($get_total_rows2[0]/$item_per_page);
                           ?>
                              <tbody id="result_forumQuestion"></tbody>
                           </table>
                        </div>
                        <ul class="pagination_forumQuestion"></ul>
                     </section>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<?php 
   include("footer.php"); 
?>

<script type="text/javascript" src="js/jquery.bootpag.min.js"></script>
<script type="text/javascript">
   $(document).ready(function() {
     $("#result_forumQuestion").load("pagination_fetch_pages_forumQuestion.php");  //initial page number to load
     $(".pagination_forumQuestion").bootpag({
        total: <?php echo $pages2; ?>,
        page: 1,
        maxVisible: 5 
     }).on("page", function(e, num){
       e.preventDefault();
       $("#result_forumQuestion").prepend('<div class="loading-indication"><img src="ajax-loader.gif" /> Loading...</div>');
       $("#result_forumQuestion").load("pagination_fetch_pages_forumQuestion.php", {'page':num});
     });
   });
</script>
<script type="text/javascript">
  function viewHideDiv(currentStatus, id){
    var currentValue = $(currentStatus).text();
    if(currentValue == "...More"){
      $("#viewHideDiv"+id).removeAttr("style");
      $(currentStatus).html(" <b> (Less)<b>");
    }
    else{
      $("#viewHideDiv"+id).css("display","none");
      $(currentStatus).html("<b>...More<b>");
    }

    $("#viewHideDiv"+id).css("font-weight",0);
  }
</script>