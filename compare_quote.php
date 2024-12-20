<?php 
   error_reporting(0);
    include("header.php"); 
    
    if(isset($_SESSION['Lawyer_id'])){
      unset($_SESSION['Lawyer_id']);
    }
?>
<style>
   .demoInputBox{padding:5px; border:#F0F0F0 1px solid; border-radius:4px;}
   .captcha{}
   .captcha input{height:36px; border:1px solid #ccc; padding:6px 12px; margin-bottom:18px;}
   .captcha button{padding:4px 6px; background:none; font-size:16px;}
   img.captcha_code{width:auto!important; webkit-filter:grayscale(100%); -moz-filter:grayscale(100%); -o-filter:grayscale(100%); filter: -ms-grayscale(100%); filter:grayscale(100%); margin:0 0 0 4px;}
  
  #results .loading-indication{
   background: #FFFFFF;
   padding: 10px;
   margin-left: auto;
   margin-right: auto;
   position: absolute;
   }
</style>

<!-- main slider -->
<section class="inner-bg over-layer-white">
   <div class="container">
      <div class="row">
         <div class="col-xs-12">
            <div class="mini-title">
               <h3>Compare Legal Query</h3>
               <p><a href="index.php">Home</a> <span class="fa fa-angle-right"></span> <a href="#">Compare Legal Query</a></p>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- comapre search -->
<section id="comapre_main" style="background-image:url(img/bg/bg_1.jpg)">
   <div class="bg_overlay"></div>
   <?php
      if(!isset($_REQUEST['searchresult']))
      { 
      ?>
   <div class="comapre_search search_lawyer_1">
      <div class="title col-xs-12">
         <h2 class="text-center">Search Lawyers</h2>
      </div>
      <div class=" search_fields">
         <form method="post" action="">
            <div class="input_field">
               <input type="search" name="searchresult" value="" placeholder="Enter Court Name / Place / State" class="form-control" required>
               <button type="submit" class="btn"><i class="fa fa-search"></i></button>
            </div>
            <p class="eg">( eg. Bombay High Court / Mumbai / Maharashtra )</p>
         </form>
      </div>
   </div>
   <?php } ?>
</section>
<!-- compare quote start -->
<section class="compare_quote bg-ff">
   <div class="container">
   <div class="row">
      <div class="col-xs-12">
         <?php if(isset($_REQUEST['searchresult']))
            { 
            $_SESSION['comparesearch_result']=$_REQUEST['searchresult'];
            $item_per_page = 5; 
            
            $sql22 = "SELECT COUNT(lawyer_id) FROM tbl_lawyers WHERE (firm_name LIKE '%".$_SESSION['comparesearch_result']."%' OR address LIKE '%".$_SESSION['comparesearch_result']."%'  OR practice_courts LIKE '%" . $_SESSION['comparesearch_result'] . "%') AND compare_quote='Yes' AND user_status='Approved'";  
            
            $rs_result = mysqli_query($conn,$sql22);  
            $get_total_rows = mysqli_fetch_array($rs_result); 
            $pages = ceil($get_total_rows[0]/$item_per_page);
            }
            ?>
         <div class="col-xs-7 text-right padding-0">
            <div class="pagination"></div>
         </div>
         <div class="clearfix"></div>
         <div id="results"></div>
         <!--preview panel-->
         <div class="w3-container w3-center">
            <div class="w3-row w3-card-4 w3-grey w3-round-large w3-border comparePanle w3-margin-top">
               <div class="w3-row">
                  <div class="w3-col l9 m8 s6 text-left heading">
                     <h4>Legal Query Comparison</h4>
                  </div>
                  <div class="w3-col l3 m4 s6 disable_btn">
                     &nbsp;
                     <button class="w3-btn w3-round-small w3-white w3-border notActive cmprBtn" data-toggle="modal" data-target="#myModal" disabled>Compare</button>
                  </div>
               </div>
               <div class=" titleMargin w3-container comparePan text-left"></div>
            </div>
         </div>
      </div>
   </div>
</section>
<!-- fixed banner end -->
<?php 
   include("footer.php"); 
   ?>
<script type="text/javascript" src="js/jquery.bootpag.min.js"></script>
<script type="text/javascript">
   $(document).ready(function() {
     $("#results").load("fetch_pages1.php");  //initial page number to load
     $(".pagination").bootpag({
        total: <?php echo $pages; ?>,
        page: 1,
        maxVisible: 5 
     }).on("page", function(e, num){
       e.preventDefault();
       // $("#results").prepend('<div class="loading-indication"><img src="ajax-loader.gif" /> Loading...</div>');
       $("#results").load("fetch_pages1.php", {'page':num});
     });
   
   });
</script>