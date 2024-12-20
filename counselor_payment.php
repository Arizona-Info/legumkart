<?php 
   include("header.php"); 
   $page = 'counselor123';
   if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'Counsel')
   {
     echo  '<script>window.location="index.php"</script>';
   }
   ?>
<!-- manage free slot start -->
<section class="manage_freeslot">
   <div class="container-fluid">
      <div class="row">
         <div class="col-xs-12">
           <?php include("sidebar1.php"); ?>
            <div class="col-sm-9 col-xs-12">
               <div class="right_panel">
                  <h3>Payments Details</h3>

                  <div class="row">
                     <div class="col-xs-12">
                        <div class="table-responsive">
                           <table id="example" class="table table-bordered">

                           <thead>
                                 <tr>
                                    <th>Sr. No.</th>
                                    <th>Lawyer</th>
                                    <th>Court Name</th>
                                    <th>Judge Name</th>
                                    <th>Parties</th>
                                    <th>Court Hearing Date</th>
                                    <th>Type</th>
                                    <th>Bill</th>
                                    <!-- <th>Conference Date</th>
                                    <th>Conference Time</th>
                                    <th>Conference Place</th> -->
                                 </tr>
                              </thead>
                              <tbody>
               <?php 
                     $srl =1;
                     $counselcases = mysqli_query($conn,"SELECT t1.*, t2.* FROM tbl_counsel_cases t1, tbl_cases t2 WHERE t1.cc_case_id=t2.case_id AND t1.cc_status='Accepted' AND t1.counsel_id = '".$_SESSION['user_id']."' AND t1.cc_date <= '".date('Y-m-d')."'");
                     while($counselcasesrow=mysqli_fetch_assoc($counselcases))
                     { 
                      
                      $counsellawyer = mysqli_query($conn,"SELECT * FROM tbl_lawyers WHERE lawyer_id='".$counselcasesrow['lawyer_id']."'");
                      $counsellawyerrow=mysqli_fetch_array($counsellawyer);
                  ?>    
                           <tr>
                                    <td><?php echo $srl;?></td>

                                    <td><u><a href="#court_detailss<?php echo $srl;?>"  data-toggle="modal" title="lawyer Details"><?php echo $counsellawyerrow['firm_name'];?></a></u></td>

                                    <td><?php echo $counselcasesrow['court_name'];?></td>
                                    <td><?php echo $counselcasesrow['judge_name']; ?></td>
                                    <td><?php echo $counselcasesrow['party_a']; ?> vs <?php echo $counselcasesrow['party_b']; ?></td>
                                    <td><?php echo date_format (new DateTime($counselcasesrow['next_date']), 'd-M-y');?></td>

                                    <!-- <td><?php echo date_format (new DateTime($counselcasesrow['cc_date']), 'd-M-y');?></td>
                                    <td><?php echo $counselcasesrow['cc_time'];?></td>
                                    <td><?php echo $counselcasesrow['cc_place'];?></td> -->
                                    <td>
                                       <u><a href="#conselordetail<?php echo $srl;?>"  data-toggle="modal" title="Court Details"><?php echo $counselcasesrow['cc_type'];?></a></u>

                                       <div class="zind modal fade" id="conselordetail<?php echo $srl;?>">
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
                                                              <p>Conference Date</p>
                                                           </div>
                                                           <div class="cell">
                                                              <p>Conference Time</p>
                                                           </div>
                                                           <div class="cell">
                                                              <p>Conference Place</p>
                                                           </div>
                                                      </div>
                                                      <div class="sub_heading">
                                                          <div class="sub_cell">
                                                              <?php echo date_format (new DateTime($counselcasesrow['cc_date']), 'd-M-y');?>
                                                          </div>
                                                          <div class="sub_cell">
                                                              <?php echo $counselcasesrow['cc_time'];?>
                                                          </div>
                                                          <div class="sub_cell">
                                                              <?php echo $counselcasesrow['cc_place'];?>
                                                          </div>
                                                      </div>
                                                  </div>
                                                <?php }
                                                else if($counselcasesrow['cc_type'] == 'Hearing'){?>

                                                    <div class="table_box">
                                                     <div class="heading">
                                                           <div class="cell">
                                                              <p>Hearing Date</p>
                                                           </div>
                                                           <div class="cell">
                                                              <p>Hearing Time</p>
                                                           </div>
                                                      </div>
                                                      <div class="sub_heading">
                                                          <div class="sub_cell">
                                                              <?php echo date_format(new DateTime($counselcasesrow['cc_next_date']), 'd-M-y');?>
                                                          </div>
                                                          <div class="sub_cell">
                                                              <?php echo date_format(date_create($counselcasesrow['cc_hearing_time']), 'g:i A');?>
                                                          </div>
                                                      </div>
                                                  </div>

                                                  <?php } ?>
                                               </div>
                                            </div>
                                         </div>
                                      </div> 

                                    </td>
                                    <td>
                                    	<?php if($counselcasesrow['cc_bill_pdf'] == ""){ ?>
                                    	<u><a href="#conselordetail52<?php echo $srl;?>"  data-toggle="modal" title="Court Details">Generate Bill</a></u>

                                       <div class="zind modal fade" id="conselordetail52<?php echo $srl;?>">
                                         <div class="modal-dialog">
                                            <div class="modal-content">
                                               <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                                               <div class="modal-header">
                                                  <h3><i class="pe-7s-users"></i> Bill Generate</h3>
                                               </div>
                                               <div class="modal-body">

                                               		<form id="bill_generate_form" method="post" action="bill_generate_form.php" autocomplete="off">

                                               			<input class="form-control" type="hidden" name="uni_id" value="<?php echo $counselcasesrow['cc_id']; ?>" placeholder="" required>
                                               			<input class="form-control" type="hidden" name="uni_id2" value="<?php echo $counsellawyerrow['lawyer_id'];; ?>" placeholder="" required>

                                               			<div class="col-md-6">
                              								<div class="form-group">
                              									<!-- <label>Amount_1 :</label> -->
                              									<input class="form-control" id="val_1" type="text" name="amt_1" value="" placeholder="Amount" oninput="add1()" required >
                              								</div>
                              							</div>
                              							<div class="col-md-6">
                              								<div class="form-group">
                              									<!-- <label>Amount_2 :</label> -->
                              									<input class="form-control" id="val_2" type="text" name="amt_2" value="" placeholder="GMS Amount" oninput="add2()" required>
                              								</div>
                              							</div>
                              							<div class="col-md-6">
                              								<div class="form-group">
                              									<!-- <label>Amount_3 :</label> -->
                              									<input class="form-control" id="val_3" type="text" name="amt_3" value="" placeholder="Total" readonly required>
                              								</div>
                              							</div>
                              							<div class="form-group">
							                              	<button type="button" name="" class="btn btn-simple " onClick="bill_generate_btn()">Generate</button>
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


                                 <div class="zind modal fade" id="court_detailss<?php echo $srl;?>">
                                    <div class="modal-dialog">
                                       <div class="modal-content">
                                          <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                                          <div class="modal-header">
                                             <h3>Court Details</h3>
                                          </div>
                                          <div class="modal-body">
                                             <div class="table_box">
                                                <div class="heading">
                                                      <div class="cell">
                                                         <p>Phone Number</p>
                                                      </div>
                                                      <div class="cell">
                                                         <p>Email</p>
                                                      </div>
                                                </div>
                                                <div class="sub_heading">
                                                     <div class="sub_cell">
                                                         <p><?php echo $counsellawyerrow['phone'];?></p>
                                                     </div>
                                                     <div class="sub_cell">
                                                         <p><?php echo $counsellawyerrow['email'];?></p>
                                                     </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <!-- <tr>
                                    <td>1</td>
                                    <td>Rahul</td>
                                    <td>Sunil</td>
                                    <td>High Court, Bandra</td>
                                    <td>22-May-18</td>
                                    <td>A vs B</td>
                                    <td>Office no-273 Satra Plaza Vashi</td>
                                    <td>20-May-18</td>
                                    <td>3.30 PM</td>
                                 </tr> -->
               <?php $srl++; } ?>                 

                              </tbody>

                           </table>
                        </div>
                    </div>
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
<script type="text/javascript">
	function bill_generate_btn(){
		val1 = Number($("#val_1").val());
		val2 = Number($("#val_2").val());
		val3 = Number($("#val_3").val());

		if(isNaN(val1) || isNaN(val3) || isNaN(val3) || val1 == "" || val3 == "" || val2 == "")
		{
		   alert('Incorrect value');
		}
		else{
		   $('#bill_generate_form').submit();
		}
	}

  function add1(){
      val1 = Number(document.getElementById("val_1").value);
      val2 = Number(document.getElementById("val_2").value);

      z = val1 + val2;
      document.getElementById("val_3").value = z;
      
  }
  function add2(){
      val1 = Number(document.getElementById("val_1").value);
      val2 = Number(document.getElementById("val_2").value);


      z = val1 + val2;
      document.getElementById("val_3").value = z;
      
  }

</script>
