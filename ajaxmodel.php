<?php
include ('db_connection.php');
$make_id = $_POST["makerr_id"];
?>
 <input type="hidden" value="<?php echo $make_id;?>" name="make_id" id="make_id" />

<select name="model_id" id="model_id">
	<option value="">Select Regulators</option>
    <?php 
     $modelqry = "SELECT * FROM tbl_regulators WHERE profession = '".$make_id."'";
        $mdlres = mysqli_query($conn,$modelqry);
        $modelrow = mysqli_fetch_array($mdlres);
        
        if($modelrow['approved_regulators']!='')
           {
    ?>
    <option value="<?php echo $modelrow['approved_regulators'];?>"><?php echo $modelrow['approved_regulators'];?></option>
    <?php } ?>
    <option value="<?php echo $modelrow['independent_regulators'];?>"><?php echo $modelrow['independent_regulators'];?></option>
    
</select>

