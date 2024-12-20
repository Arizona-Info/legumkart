<?php 
 error_reporting(0);
 include("header.php"); 
 $page = 'cases_export.php';
 

 if(!isset($_SESSION['user_id']))
 {
   echo  '<script>window.location="index.php"</script>';
 }

 if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] == "Intern")
   {
     echo  '<script>window.location="index.php"</script>';
   }

?>
<link rel="stylesheet" type="text/css" href="css/awesome-bootstrap-checkbox.css">

<!-- manage free slot start -->
<section class="manage_freeslot">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
            <?php if($_SESSION['user_type'] == "Counsel") { include("sidebar1.php"); } else { include("sidebar.php"); } ?>

              <div class="col-sm-9 col-xs-12">
                  <div class="col-sm-6 col-xs-12">
                    <div class="right_panel">
                      <h3 class="right1">Export Cases</h3>
                    </div>
                  </div>
                  <form action="export_csv.php" method="post" autocomplete="off">
                    <div class="col-xs-12 padding-left-none">
                      <div class="right2 padding-top-15">


                        <div class="col-md-6 col-xs-12">
                          <div class="form-group">
                            <label>Export Type</label>
                            <select name="export_type" class="form-control"  onchange="myFunction(this.value)"  required>
                              <option value="tilldate">Till Date</option>
                              <option value="datewise">Date Wise</option>
                             </select>
                          </div>
                        </div>
                        
                        <div class="col-xs-12 padding-left-none">
                        <div class="col-md-6 col-xs-12"  id="chequemode1"  style="display: none;">
                          <div class="form-group">
                            <label>Start Date :</label>
                              <input name="start_case_date" type="text" class="form-control example1" placeholder="  Select Start Date" autocomplete="off">
                           </div>
                        </div>

                        <div class="col-md-6 col-xs-12"  id="chequemode2"  style="display: none;">
                          <div class="form-group">
                            <label>End Date :</label>
                              <input name="end_case_date" type="text" class="form-control example1" placeholder="  Select End Date" autocomplete="off">
                           </div>
                        </div>
                        </div>
                     
                       <div class="clearfix"></div>
                          <hr>
                          <?php if($_SESSION['user_type'] == "Counsel") { ?>
                          <button type="submit" name="export_counsel_cases" class="btn btn-dark">Export</button>
                          <?php } else { ?>
                          <button type="submit" name="export_cases" class="btn btn-dark">Export</button>
                          <?php } ?>
                       <!--  </form> -->
                      </div>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
</section>


<?php 
 include("footer.php"); 
?>

<script>
function myFunction(i) 
{
  if(i=='datewise')
  {
    document.getElementById('chequemode1').style.display = 'block';
    document.getElementById('chequemode2').style.display = 'block';
  }
  else
  {
    document.getElementById('chequemode1').style.display = 'none';
    document.getElementById('chequemode2').style.display = 'none';
  }
}
</script>