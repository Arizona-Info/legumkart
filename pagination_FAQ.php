<?php
  include ('db_connection.php');

  $item_per_page = 10;
  
  //sanitize post value
   if(isset($_POST["page"]))
   {
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
    if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number
   }
   else
   {
    $page_number = 1;
   }
   
  //get current starting point of records
   $position = (($page_number-1) * $item_per_page);
  ?>

<div class="col-xs-12">
  <?php 
   //Limit our results within a specified range. 
   $results = mysqli_query($conn, "SELECT * FROM tbl_faq ORDER BY faq_id DESC LIMIT $position, $item_per_page");

   if(mysqli_num_rows($results) > 0) 
   { 
    $i = 0;
      while($result = mysqli_fetch_array($results))
      {
   ?>

   <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="heading_<?php echo $result['faq_id']; ?>">
          <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $result['faq_id']; ?>" aria-expanded="true" aria-controls="collapseOne"><?php echo $result['faq_ques']; ?></a>
          </h4>
      </div>
      <div id="collapse_<?php echo $result['faq_id']; ?>" class="panel-collapse collapse <?php if($i == 0){ echo 'in'; } ?>" role="tabpanel" aria-labelledby="heading_<?php echo $result['faq_id']; ?>">
          <div class="panel-body"><?php  echo $result['faq_answer']; ?></div>
      </div>
  </div>

<?php $i++; } ?>
</div> 
        
<?php } else {  echo '<div class="no_result"><img src="img/about.png"><p>No Results Found.</p></div>'; } ?>


