<?php

  require_once('db_connection.php');

  if(!isset($_POST['action'])){
    echo "Error";
    exit();
  }

  if($_POST['action'] == "payment_update"){
    $lawyer_lastpayment="SELECT * from tbl_payments WHERE id = '".$_POST['id']."' ORDER BY id DESC LIMIT 1";
        $lawyer_lastpayment_result = mysqli_query($conn,$lawyer_lastpayment);
        $lawyer_lastpayment_row=mysqli_fetch_assoc($lawyer_lastpayment_result);

        $details = array();
        if(mysqli_num_rows($lawyer_lastpayment_result) != 0){
          $details['paymt_dt'] = $lawyer_lastpayment_row['paymt_dt'];
          // $details['paymt_mode'] = $lawyer_lastpayment_row['paymt_mode'];
          $details['cnumber'] = $lawyer_lastpayment_row['cnumber'];
          $details['paymt_amt'] = $lawyer_lastpayment_row['paymt_amt'];
          $details['remarks'] = $lawyer_lastpayment_row['remarks'];

          echo json_encode($details);
          exit();
        }
        else{
          $data = 'No data';
          echo json_encode(array('Error' => $data));
          exit();
        }

  }

  if($_POST['action'] == "payment_details" && isset($_POST['case_number']) && isset($_POST['case_id']) && $_POST['case_number'] != "" && $_POST['case_id'] != ""){
    ?>
    <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
        <div class="modal-header">
           <h3>Case Payment (Case Number: <?php echo $_POST['case_number'];?>)</h3>
        </div>
        <div class="modal-body">
    <table class="table table-bordered">
                
          <thead>
             <tr>
                <th>Paymt Dt.</th>
                <th>Mode</th>
                <th>Paymt Amt.</th>
                <th>Remarks</th>
                <th>Bill</th>
             </tr>
          </thead>
          <tbody>
    <?php 
       $lawyerqry16 = "SELECT * FROM tbl_payments WHERE case_id_pym = '".$_POST['case_id']."'";

       $total_paymt = 0;
          $lawyerresults16 = mysqli_query($conn,$lawyerqry16);
          $noofrows= mysqli_num_rows($lawyerresults16);
          if($noofrows > 0)
           {       
           while($lawyerrow16=mysqli_fetch_assoc($lawyerresults16))
                { 
                ?>
             <tr>
                <td><?php echo date_format (new DateTime($lawyerrow16['paymt_dt']), 'd-M-y');?></td>
                <td><?php echo $lawyerrow16['paymt_mode'];
                    if($lawyerrow16['paymt_mode'] != "Cash"){
                        echo "<br>".$lawyerrow16['cnumber'];
                    }
                ?><br>
                <!--<?php echo $lawyerrow16['cnumber'];?>-->
                </td>
                <td><?php echo $lawyerrow16['paymt_amt'];?></td>
                <td><?php echo $lawyerrow16['remarks'];?></td>
                <td><?php if($lawyerrow16['bill_generate'] != ""){ ?> <a target="_blank" href="admin/uploads/<?php echo $lawyerrow16['bill_generate'];?>">View</a> <?php } ?></td>
             </tr>
             <?php  $total_paymt += $lawyerrow16['paymt_amt']; } ?>
               <tr>
                 <td colspan="2">Total Payment Received</td>
                 <td><?php echo $total_paymt;?></td>
                 <td></td>
                 <td></td>
               </tr>
          <?php }else{ ?>
                <tr>
                <td colspan="6">No Payment Received</td>
                </tr>
              <?php }?>    
          </tbody>
       </table>
      </div>
    <?php
    exit();
  }

  if($_POST['action'] == "case_all_edit"){
     
     if($_POST['nextdt_id'] != 0) 
        { 
       ?>     
        <input type="hidden" name="eid" value="<?php echo $_POST['case_id'];?>">
        <input type="hidden" name="nextdt_id" value="<?php echo $_POST['nextdt_id'];?>"> 
      <?php  }else{   ?> 
         <input type="hidden" name="eid" value="<?php echo $_POST['case_id'];?>"> 
      <?php  } ?> 
     <div class="col-md-6 col-xs-12">
     <div class="form-group">
     <label>Stage :</label>   
    <select class="form-control" id="optt<?php echo $_POST['case_id'];?>" name="edit_stage"  onchange="showDiv1(this,<?php echo $_POST['case_id'];?>)" required>
       <option value="">-- Select Stage --</option>
       <?php

          $qry = "SELECT stage_name FROM tbl_stage WHERE status = '1'";
          $result3 = mysqli_query($conn, $qry);
          while ($result2 = mysqli_fetch_assoc($result3)) 
          {
            ?>
            <option <?php if($_POST['case_stage'] == $result2['stage_name']) {echo 'selected';} ?>><?php echo $result2['stage_name']; ?></option>
      <?php
          }
          if($_POST['case_stage'] != $result2['stage_name']) 
             { ?>
               <option selected><?php echo $_POST['case_stage']; ?></option>
       <?php } ?>
         <option value="2" style="color:red">Enter New Stage ( if any )</option>                         
    </select>
       </div>
    </div>
    <div id="hide_show_div<?php echo $_POST['case_id'];?>"  style="display: none">
      <div class="col-md-6 col-xs-12">
         <div class="form-group">
            <input id="newopt<?php echo $_POST['case_id'];?>" class="form-control" name="newopt1" placeholder="New Stage" type="text">
         </div>
      </div>
      <div class="col-md-3 col-xs-12">
         <div class="form-group">
            <input onclick="sendValues(<?php echo $_POST['case_id'];?>)" name="addopt1" value="Add New Stage" type="button">
         </div>
      </div>
    </div> 
    <?php
    exit();
  }


  if($_POST['action'] == "case_all_history"){

    ?>
    <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
            <div class="modal-header">
            <h3><i class="pe-7s-users"></i> CASE HISTORY (Case Number: <?php echo $_POST['cases_number'];?>)</h3>
            </div>
            <div class="modal-body">
               <div class="table_box">
                  <div class="heading">
                       <div class="cell">
                           <p>Previous Date</p>
                       </div>
                       <div class="cell">
                           <p>Stage</p>
                       </div>
                       <div class="cell">
                           <p>Next Date</p>
                       </div>
                       <div class="cell">
                           <p>Attended By</p>
                        </div>
                        <div class="cell">
                           <p>Judge Name</p>
                        </div>
                   </div>
                   <?php 
                        $lawyerqry16 = "SELECT t2.prev_case_date,t2.next_stage,t2.next_case_date,t3.firm_name,t2.next_judge FROM tbl_cases t1, tbl_case_nextdt t2 ,tbl_lawyers t3 WHERE t2.next_case_id='".$_POST['case_id']."' AND t1.case_id=t2.next_case_id AND t2.lawyer_id=t3.lawyer_id";

                        $lawyerqry18 = "SELECT t1.next_date, t1.stage, t1.judge_name, t2.firm_name FROM tbl_cases t1, tbl_lawyers t2 WHERE t2.lawyer_id = t1.lawyer_id AND t1.case_id = '".$_POST['case_id']."'";

                        $lawyerresults18 = mysqli_query($conn,$lawyerqry18);
                        $lawyerrow18 = mysqli_fetch_assoc($lawyerresults18);
                    ?>

                    <div class="sub_heading">
                       <div class="sub_cell">
                           <p><?php echo date_format (new DateTime($lawyerrow18['next_date']), 'd-M-y');?></p>
                       </div>
                       <div class="sub_cell">
                           <p><?php echo $lawyerrow18['stage'];?></p>
                       </div>
                       <div class="sub_cell">
                           <p><?php echo date_format (new DateTime($lawyerrow18['next_date']), 'd-M-y');?></p>
                       </div>
                       <div class="sub_cell">
                           <p><?php echo $lawyerrow18['firm_name'];?></p>
                       </div>
                       <div class="sub_cell">
                           <p><?php echo $lawyerrow18['judge_name'];?></p>
                       </div>
                  </div>
                    
                    <?php 

                        $lawyerresults16 = mysqli_query($conn,$lawyerqry16);
                        while($lawyerrow16=mysqli_fetch_assoc($lawyerresults16))
                        { 
                   ?>
                   <div class="sub_heading">
                     <div class="sub_cell">
                           <p><?php echo date_format (new DateTime($lawyerrow16['prev_case_date']), 'd-M-y');?></p>
                       </div>
                       <div class="sub_cell">
                           <p><?php echo $lawyerrow16['next_stage'];?></p>
                       </div>
                       <div class="sub_cell">
                           <p><?php echo date_format (new DateTime($lawyerrow16['next_case_date']), 'd-M-y');?></p>
                       </div>
                       <div class="sub_cell">
                           <p><?php echo $lawyerrow16['firm_name'];?></p>
                       </div>
                       <div class="sub_cell">
                           <p><?php echo $lawyerrow16['next_judge'];?></p>
                       </div>
                   </div>
                  <?php } ?>
                   
               </div>
            </div>

    <?php
    exit();
  }




  //change payment status in cases_payment_new.php
  if($_POST['action'] == "update_payment_status"){

    if (!isset($_SESSION['user_id'])) {
      echo "Something went wrong, please try again";
      exit();
    }

    $select = "SELECT type FROM tbl_lawyers WHERE lawyer_id = '".$_SESSION['user_id']."' AND email = '".$_SESSION['user_email']."' AND type = '".$_SESSION['user_type']."'";
    $select_result = mysqli_query($conn, $select);
    $final_result = mysqli_fetch_assoc($select_result);

    if($final_result['type'] == ""){
      echo "Something went wrong, please try again";
      exit();
    }
    
    if(sha1($final_result['type'].$_SESSION['user_id']."dsfg21") != $_POST['uniqueid']){
       echo "Something went wrong, please try again";
      exit();
    }
      
        
    if(!isset($_POST['id']) || !isset($_POST['status'])){
      echo "Something went wrong, please try again";
      exit();
    }

    $status = "";
    if($_POST['status'] == "Pending"){
      $status = 'Paid';
    }
    else if($_POST['status'] == "Paid"){
      $status = 'Pending';
    }
    else{
      echo "Something went wrong, please try again";
      exit();
    }

    $update = "UPDATE tbl_cases SET paymt_status = '".$status."' WHERE case_id = '".$_POST['id']."'";
    $result = mysqli_query($conn, $update);
    if($result){
      echo "success";
      exit();
    }
    else{
      echo "Unable to update";
      exit();
    }

  }
  
  
  //update status in manager quotes
  if($_POST['action'] == "update_manage_quotes_status"){

    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || !isset($_SESSION['user_email'])) {
      echo "Something went wrong, please try again";
      exit();
    }

    if (!isset($_POST['id']) || !isset($_POST['status']) || !isset($_POST['uniqueid'])) {
      echo "Something went wrong, please try again";
      exit();
    }

    $select = "SELECT type FROM tbl_lawyers WHERE lawyer_id = '".$_SESSION['user_id']."' AND email = '".$_SESSION['user_email']."' AND type = '".$_SESSION['user_type']."'";
    $select_result = mysqli_query($conn, $select);
    $final_result = mysqli_fetch_assoc($select_result);

    if($final_result['type'] == ""){
      echo "Something went wrong, please try again";
      exit();
    }
    
    if(sha1($final_result['type'].$_SESSION['user_id']."dsfg21") != $_POST['uniqueid']){
       echo "Something went wrong, please try again";
      exit();
    }

    $validation_two = "SELECT status FROM tbl_quotes WHERE status = '".$_POST['status']."' AND quote_id = '".$_POST['id']."'";
    $result = mysqli_query($conn, $validation_two);
    $final_result = mysqli_fetch_assoc($result);

    if($final_result['status'] != ""){

      $newstatus = "";

      if($final_result['status'] == "New"){
        $newstatus = 'Pending';
      }
      else if($final_result['status'] == "Pending"){
        $newstatus = 'Accepted';
      }
      else if($final_result['status'] == "Accepted"){
        $newstatus = 'Declined';
      }
      else if($final_result['status'] == "Declined"){
        $newstatus = 'Closed';
      }
      else if($final_result['status'] == "Closed"){
        $newstatus = 'New';
      }

      if($newstatus != ""){
        $updstatus = mysqli_query($conn,"UPDATE tbl_quotes SET status   = '".$newstatus."' where quote_id = '".$_POST['id']."'");

        if(mysqli_affected_rows($conn)){
          echo "success";
          exit();
        }
        else{
          echo "Unable to update";
          exit();
        }

      }
      else{
        echo "Something went wrong, please try again";
        exit();
      }
    }
    else{
      echo "Something went wrong, please try again";
      exit();
    }


  }


  if($_POST['action'] == "addForumQuestion"){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $question = mysqli_real_escape_string($conn, $_POST['question']);
    $captcha = mysqli_real_escape_string($conn, $_POST['captcha']);

    if($name == ""){
      echo "Enter your name";
      exit();
    }
    else if($email == ""){
      echo "Enter your emails id";
      exit();
    }
    else if($question == ""){
      echo "Enter your question";
      exit();
    }
    else if($captcha == ""){
      echo "Enter your captcha";
      exit();
    }
    else if(!isset($_SESSION['captcha_code']) || $_SESSION['captcha_code'] != $captcha){
      echo "Incorrect captcha";
      exit();
    }
    else{
      $sql_Query = "INSERT INTO tbl_forumquestion(questionAskBy, enailAddress, question) VALUES('".$name."', '".$email."', '".$question."')";
      mysqli_query($conn, $sql_Query);
      if(mysqli_affected_rows($conn) == 1){
        echo "success";
      }
      else{
        echo "Unable to send your query";
      }
    }
  }

  if($_POST['action'] == "submitLawyerReview"){
    if(!isset($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])){
      echo "Something went wrong, please try again";
      exit();
    }
    else if(!isset($_POST['questionId']) || !is_numeric($_POST['questionId'])){
      echo "Something went wrong, please try again";
      exit();
    }
    else if(!isset($_POST['message']) || $_POST['message'] == ""){
      echo "Enter your message";
      exit();
    }
    else if(!isset($_SESSION['captcha_code']) || !isset($_POST['captcha']) || $_SESSION['captcha_code'] == "" || $_SESSION['captcha_code'] != $_POST['captcha']){
      echo "Incorrect captcha found";
      exit();
    }
    else{
      $lawyerId = mysqli_real_escape_string($conn, $_SESSION['user_id']);
      $questionId = mysqli_real_escape_string($conn, $_POST['questionId']);
      $answer = mysqli_real_escape_string($conn, $_POST['message']);

      $insertQuery = "INSERT INTO tbl_forumanswer(questionId, answer, lawyerId) VALUES('".$questionId."', '".$answer."', '".$lawyerId."')";
      $result = mysqli_query($conn, $insertQuery);


      if(mysqli_affected_rows($conn) == 1){
        echo "success";
        exit();
      }
      else{
        echo "Unable to submit your review";
        exit();
      }

    }
  }
?>

