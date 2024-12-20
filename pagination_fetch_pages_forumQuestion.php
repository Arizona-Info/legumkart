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
   $results = mysqli_query($conn, "SELECT * FROM tbl_forumquestion WHERE status = '1' ORDER BY id DESC LIMIT $position, $item_per_page");

   if(mysqli_num_rows($results) > 0) 
   { 
    $i = 0;
      while($result = mysqli_fetch_array($results))
      {
        $totalAnwserCount = mysqli_query($conn, "SELECT count(id) AS cnt FROM tbl_forumanswer WHERE questionId = '".$result['id']."'");
        $totalAnwserCountResult = mysqli_fetch_array($totalAnwserCount)
   ?>

  <tr>
    <td class="" style="font-size: 16px"><i class="fa fa-quora" aria-hidden="true"></i></td>
    <td class="title_column">
        <h4><a href="legalDecussion.php?id=<?php echo $result['id']; ?>" target="_blank"><?php echo $result['question']; ?></a></h4>
        <p><strong>By <?php echo $result['questionAskBy']; ?></strong>, <?php echo date("M d, Y", strtotime($result['createdDate'])); ?></p>

      <?php if($totalAnwserCountResult['cnt'] > 0){ ?>
        <table>
        <?php
          $sqlReplyQuery = "SELECT t1.id, t1.answer, t1.createdDate, t2.firm_name, t2.firm_logo, t2.type FROM tbl_forumanswer t1 LEFT JOIN tbl_lawyers t2 ON t1.lawyerId = t2.lawyer_id WHERE t1.questionId = '".$result['id']."' AND t1.status = 1 ORDER BY createdDate DESC LIMIT 2";

          $resultAnswerQuery = mysqli_query($conn, $sqlReplyQuery);
          if(mysqli_num_rows($resultAnswerQuery) > 0){
            while ($resultAnswer = mysqli_fetch_assoc($resultAnswerQuery)) {
        ?>
          <tr>
            <td>
            <span style="font-size: 15px"><b><?php echo $resultAnswer['firm_name']; ?></b></span>
            <div><b>Reply : </b>
            <?php 
              $stringLength = strlen($resultAnswer['answer']);
              if($stringLength > 200){
                echo substr($resultAnswer['answer'], 0, 200).'<spaan id="viewHideDiv'.$resultAnswer['id'].'" style="display: none">'.substr($resultAnswer['answer'], 200, $stringLength).'</spaan>'.'<a href="#!" onclick="viewHideDiv(this, \''.$resultAnswer['id'].'\')"><b>...More</b></a><br>';
              }
              else{
                echo $resultAnswer['answer'];
              }
            ?>
            </div>
            </td>
          </tr>
        <?php } } ?>
        <?php if($totalAnwserCountResult['cnt'] > 2){ ?>
          <tr>
            <td><a href="legalDecussion.php?id=<?php echo $result['id']; ?>" target="_blank"><b>View More Reply</b></a></td>
          </tr>
        <?php } ?>
        </table>
      <?php } ?>
    </td>
    <td>
        <ul>
            <li>Replies: <?php echo $totalAnwserCountResult['cnt']; ?></li>
            <!-- <li><small>Views: <span class="pull-right">28</span></small></li> -->
        </ul>
    </td>
    <td class="last_message"><?php echo date("M d, Y", strtotime($result['createdDate'])); ?></td>
</tr>

<?php $i++; } ?>
</div> 
        
<?php } else {  echo '<td colspan="4">No data found.</td>'; } ?>