<!-- tomorrow detail -->
<div class="tab-pane fade" id="tomorrow" role="tabpane2">
<div class="table-responsive">
<table id="attendenceDetailedTable1" class="display responsive nowrap" cellspacing="0" width="100%">
 
                              <thead>
                                 <tr>
                                    <th>Sr. No.</th>
                                    <th>Lawyer</th>
                                    <th>Type</th>
                                    <th>Date/Time</th>
                                    <th>Action</th>
                                    <th>Bill</th>
                                </tr>
                              </thead>
                       <tbody>
                       <?php 
                       $srl =1;
                       $counselcases = mysqli_query($conn,"SELECT t1.*, t2.* FROM tbl_counsel_cases t1, tbl_cases t2 WHERE cc_date = '".date('Y-m-d', strtotime('+1 day'))."' AND t1.cc_case_id=t2.case_id AND t1.cc_status='Accepted' AND t1.counsel_id = '".$_SESSION['user_id']."'");
                       while($counselcasesrow=mysqli_fetch_assoc($counselcases))
                       { 
                        
                        $counsellawyer = mysqli_query($conn,"SELECT * FROM tbl_lawyers WHERE lawyer_id='".$counselcasesrow['lawyer_id']."'");
                        $counsellawyerrow=mysqli_fetch_array($counsellawyer);
                    ?>    
                             <tr>
                                      <td><?php echo $srl;?></td>

                                      <td><u><a href="#court_detailss1<?php echo $srl;?>"  data-toggle="modal" title="View Lawyer/Court Details"><?php echo $counsellawyerrow['firm_name'];?></a></u></td>

                   <td>
                   
                      <?php if($counselcasesrow['cc_type'] == 'Conference'){?>
                        <u><a href="#conselordetail1<?php echo $srl;?>"  data-toggle="modal" title="View Conference Place"><?php echo $counselcasesrow['cc_type'];?></a></u>
                       <?php }else if($counselcasesrow['cc_type'] == 'Hearing'){?>
                           <p>Hearing</p>
                       <?php }else{ echo ""; }?>
                  
                       <div class="zind modal fade" id="conselordetail1<?php echo $srl;?>">
                         <div class="modal-dialog">
                            <div class="modal-content">
                               <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                               <div class="modal-header">
                                  <h3><i class="pe-7s-users"></i> <?php if($counselcasesrow['cc_type'] == 'Conference'){echo "Conference";}else{echo "Hearing";} ?> Details (<?php echo $counsellawyerrow['firm_name']; ?>)</h3>
                               </div>
                               <div class="modal-body">

                                  <?php if($counselcasesrow['cc_type'] == 'Conference'){ ?>

                                  <div class="table_box">
                                     <div class="heading">

                                           <div class="cell">
                                              <p>Conference Place</p>
                                           </div>
                                      </div>
                                      <div class="sub_heading">
                                          <div class="sub_cell">
                                              <?php echo $counselcasesrow['cc_place'];?>
                                          </div>
                                      </div>
                                  </div>
                                <?php }?>
                               </div>
                            </div>
                         </div>
                      </div> 

              </td>               
              <?php if($counselcasesrow['cc_type'] == 'Conference'){ ?>
                    <td><?php echo date_format (new DateTime($counselcasesrow['cc_date']), 'd-M-y');?>/<?php echo date_format(date_create($counselcasesrow['cc_time']), 'g:i A');?></td>
              <?php }else if($counselcasesrow['cc_type'] == 'Hearing'){?> 
                <td><?php echo date_format(new DateTime($counselcasesrow['cc_next_date']), 'd-M-y');?>/<?php echo date_format(date_create($counselcasesrow['cc_hearing_time']), 'g:i A');?></td>
              <?php }else{ echo "<td></td>"; }?> 


              <td>
                    <form>

                      <?php if($counselcasesrow['cc_bill_pdf'] == ""){?>
                      <!-- <div class="form-group"> -->
                   <?php 
                   $result1 = mysqli_query($conn,"SELECT cc_action FROM tbl_counsel_cases  WHERE cc_id=".$counselcasesrow['cc_id']);
                   $row = mysqli_fetch_array($result1);
                   ?>   
                       <div class="form-group">  
                        <select id="mydiv_2<?php echo $counselcasesrow['cc_id']; ?>" class="form-control" onchange="saveStatus2('<?php echo $counselcasesrow['cc_id'];?>',this.value)">
                          <?php if($row['cc_action'] == "Pending"){ ?><option value="">-Select Action-</option><?php } ?>
                          <option <?php if($row['cc_action']=="Attended") { echo "selected"; } ?>>Attended</option>
                          <option <?php if($row['cc_action']=="Not Attended") { echo "selected"; } ?>>Not Attended</option>
                        </select>     
                      </div>
                      <?php }else{ echo "Attended";} ?>
                    </form>
                 </td>
                 <td>
                   <?php if($counselcasesrow['cc_bill_pdf'] == ""){ ?>
                    <u><a href="#conselordetail522<?php echo $srl;?>"  data-toggle="modal" title="Court Details" <?php if($row['cc_action']=="Not Attended") { echo 'style="display: none;"'; } ?> id="visible_2<?php echo $counselcasesrow['cc_id'];?>">Generate Bill</a></u>

                     <div class="zind modal fade" id="conselordetail522<?php echo $srl;?>">
                       <div class="modal-dialog">
                          <div class="modal-content">
                             <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                             <div class="modal-header">
                                <h3><i class="pe-7s-users"></i> Bill Generate</h3>
                             </div>
                             <div class="modal-body">

                                <form id="bill_generate_form2<?php echo $srl;?>" method="post" action="" autocomplete="off">

                                  <input class="form-control" type="hidden" name="cc_id" value="<?php echo $counselcasesrow['cc_id']; ?>" placeholder="" required>
                                  <input class="form-control" type="hidden" name="lawyer_id" value="<?php echo $counsellawyerrow['lawyer_id']; ?>" placeholder="" required>
                                  <input type="hidden" name="bill_generate"></input>

                          <div class="col-md-6">
                            <div class="form-group">
                              <input class="form-control" id="val_22<?php echo $srl;?>" type="text" name="amt_2" value="" placeholder="GMS" oninput="add22(<?php echo $srl;?>)" required>
                            </div>
                          </div>
                          <div class="col-md-6" hidden>
                            <div class="form-group">
                              <!-- <label>Amount_1 :</label> -->
                              <input class="form-control" id="val_12<?php echo $srl;?>" type="text" name="amt_1" value="15" placeholder="Amount" oninput="add12(<?php echo $srl;?>)" required >
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <input class="form-control" id="val_32<?php echo $srl;?>" type="text" name="amt_3" value="" placeholder="Total" readonly required>
                            </div>
                          </div>
                          <div class="col-md-12">
                          <div class="form-group">
                            <button type="button" name="" class="btn btn-simple " onClick="bill_generate_btn2(<?php echo $srl;?>)">Generate</button>
                        </div>
                        </div>
                                </form>

                             </div>
                          </div>
                       </div>
                    </div>
                    <?php }else if($counselcasesrow['cc_bill_pdf'] != ""){ ?>
                        <a target="_blank" href="admin/uploads/<?php echo $counselcasesrow['cc_bill_pdf']; ?>.pdf" ><u>View</u></a>
                    <?php } ?>
                 </td>

                 </tr>

                               <div class="zind modal fade" id="court_detailss1<?php echo $srl;?>">
                                    <div class="modal-dialog">
                                       <div class="modal-content">
                                          <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                                          <div class="modal-header">
                                             <h3>Case Details</h3>
                                          </div>

                                  <div class="modal-body">
                                     <div class="">
                                        <div class="heading">
                                              <div >
                                                 <p><strong>Lawyer Contact</strong> : <?php echo $counsellawyerrow['phone'];?></p>
                                              </div>
                                              <div>
                                                 <p><strong>Lawyer Email</strong> : <?php echo $counsellawyerrow['email'];?></p>
                                              </div>
                                              <div >
                                                 <p><strong>Court Number</strong> : <?php echo $counselcasesrow['court_number'];?></p>
                                              </div>
                                              <div >
                                                 <p><strong>Court Name</strong> : <?php echo $counselcasesrow['court_name'];?></p>
                                              </div>
                                              <div>
                                                 <p><strong>Judge Name</strong> : <?php echo $counselcasesrow['judge_name']; ?></p>
                                              </div>
                                              <div >
                                                 <p><strong>Case Number</strong> : <?php echo $counselcasesrow['case_number']; ?></p>
                                              </div>
                                              <div>
                                                 <p><strong>Client</strong> : <?php echo $counselcasesrow['client_name']; ?></p>
                                              </div>
                                              <div>
                                                 <p><strong>Parties</strong> : <?php echo $counselcasesrow['party_a']; ?> vs <?php echo $counselcasesrow['party_b']; ?></p>
                                              </div>
                                              <div>
                                                 <p><strong>Court Hearing Date</strong> : <?php echo date_format (new DateTime($counselcasesrow['cc_next_date']), 'd-M-y');?></p>
                                              </div>
                                        </div>
                                     </div>

                                  </div>

                                       </div>
                                    </div>
                                 </div>    
                        <?php $srl++; } ?> 
                       </tbody>
                       </table>
                 </div>
               </div>

<script type="text/javascript">
 function saveStatus2(id,editableObj) 
 {  
   // alert(editableObj);
   // alert(id);

   if(editableObj != ""){
      $.ajax({
        url: "savestatus_counselor_cases_diary.php",
        type: "POST",
        data:'editval='+editableObj+'&id='+id,
        success: function(data){
            // alert(data);
            $('#mydiv_2'+id).html(data);
        }        
   });
   }
   if(editableObj == "Attended"){
      $("#visible_2"+id).show();
   }
   else{
      $("#visible_2"+id).hide();
   }
}
</script>
<script type="text/javascript">
  function bill_generate_btn2(i){
    val1 = Number($("#val_12"+i).val());
    val2 = Number($("#val_22"+i).val());
    val3 = Number($("#val_32"+i).val());

    if(isNaN(val1) || isNaN(val3) || isNaN(val3) || val1 == "" || val3 == "" || val2 == "")
    {
       alert('Incorrect value');
    }
    else{
       $('#bill_generate_form2'+i).submit();
    }
  }

  function add12(i){
      val1 = Number(document.getElementById("val_12"+i).value);
      val2 = Number(document.getElementById("val_22"+i).value);

      z = val1 * val2;
      document.getElementById("val_32"+i).value = z;
      
  }
  function add22(i){
      val1 = Number(document.getElementById("val_12"+i).value);
      val2 = Number(document.getElementById("val_22"+i).value);


      z = val1 * val2;
      document.getElementById("val_32"+i).value = z;
      
  }

</script>