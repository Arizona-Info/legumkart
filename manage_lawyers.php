<?php 
 include("header.php"); 
 $page = 'manage_lawyers.php';
  if(!isset($_SESSION['user_id']))
 {
   echo  '<script>window.location="index.php"</script>';
 }

 if($_SESSION['user_type']!='firm') 
{ 
    echo  '<script>window.location="freeslots.php"</script>';
}

if(isset($_POST['add_lawyer']))
{
    $check_mail = mysqli_query($conn,"SELECT email from tbl_lawyers WHERE email='".$_POST['email']."'");
    $check_phone = mysqli_query($conn,"SELECT phone from tbl_lawyers WHERE phone ='".$_POST['phone']."'");
    
    if(mysqli_num_rows($check_mail) > 0) 
    {
       echo '<script>alert("This Email Id is already Registered");</script>';  
    }
    else if(mysqli_num_rows($check_phone) > 0){
        echo '<script>alert("This Phone number is already Registered");</script>';
    }
    else
    {
    $stmt = mysqli_query($conn,"INSERT INTO tbl_lawyers(firm_id,firm_name,phone,email,password,user_status,type,lawyer_status) VALUES('".$_SESSION['user_id']."','".$_POST['firm_name']."','".$_POST['phone']."','".$_POST['email']."','".$_POST['password']."','Approved','lawyer','Active')");
    echo '<script>alert("Data added Successfully");</script>';
    }
}

if(isset($_POST['edit_lawyer']))
{  
    $eid = $_REQUEST['eid'];
    $check_mail = mysqli_query($conn,"SELECT email from tbl_lawyers WHERE email='".$_POST['edit_email']."' AND lawyer_id != '".$eid."' ");
    $check_phone = mysqli_query($conn,"SELECT phone from tbl_lawyers WHERE phone ='".$_POST['edit_phone']."' AND lawyer_id != '".$eid."'");
    
    if(mysqli_num_rows($check_mail) > 0) 
    {
       echo '<script>alert("This Email Id is already Registered");</script>';  
    }
    else if(mysqli_num_rows($check_phone) > 0){
        echo '<script>alert("This Phone number is already Registered");</script>';
    }
    else
    {
    $eid = $_REQUEST['eid'];
    $stmt = mysqli_query($conn,"UPDATE tbl_lawyers SET firm_name = '".$_POST['edit_firm_name']."',phone = '".$_POST['edit_phone']."',email = '".$_POST['edit_email']."', password ='".$_POST['edit_password']."' WHERE lawyer_id='".$eid."'");
    echo '<script>alert("Data updated Successfully");</script>';
    }
}

if(isset($_POST['status']))
    {
        $aid = $_REQUEST['lid']; 
        $val = $_REQUEST['lstatus'];
        $new_val = '';
        if($val == 'Active') { $new_val = 'InActive'; $nval = 'InActive';}  
        if($val == 'InActive') {$new_val = 'Active'; $nval = 'Active';} 
        $updstatus = mysqli_query($conn,"UPDATE tbl_lawyers SET lawyer_status   = '".$new_val."' where lawyer_id = '".$aid."'");
        // $msg = $nval." Successfully";
    }

if(isset($_POST['delete_id']))
    {
    $did = $_REQUEST['did'];  
    $dellawyer = mysqli_query($conn,"DELETE FROM tbl_lawyers where lawyer_id = '".$did."'");
    }
?>

<!-- manage free slot start -->
<section class="manage_freeslot">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <?php include("sidebar.php"); ?>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <div class="right_panel">
                    <div></div>
                        <h3>Manage Lawyers <a href="#add_lawyers" data-toggle="modal" class="btn btn-dark add_new" style="text-transform:initial;">Add</a></h3>
                        <div class="zind modal fade" id="add_lawyers">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                                    <div class="modal-header">
                                        <h3><i class="pe-7s-users"></i> Add Lawyer</h3>
                                    </div>
                                    <div class="modal-body">
                                        
                                        <form class="form" role="form" method="post" action="">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" name="firm_name" placeholder="Lawyer Name" type="text" value="" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" name="password" placeholder="Password" type="text" value="" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" name="email" placeholder="Email" type="email" value="" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" name="phone" placeholder="Phone" value="" required>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-simple" name="add_lawyer">ADD</button>
                                            </div>
                                        </form>
                            
                                    </div>
                                </div>
                            </div>
                        </div>
             <?php 
              $lawyerqry = "SELECT * FROM tbl_lawyers WHERE user_status='Approved' AND firm_id='".$_SESSION['user_id']."'";
            $lawyerresults = mysqli_query($conn,$lawyerqry);
            $lawyerno= mysqli_num_rows($lawyerresults);
            if($lawyerno > 0)
            {
            ?>
                    <div class="table-responsive">
                        <table id="" class="display dataTable responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Lawyer Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            <tbody>
            <?php 
            while($lawyerrow=mysqli_fetch_assoc($lawyerresults))
            { 
            ?>
                        <form action="" method="post">
                           <input type="hidden" name="lid" value="<?php echo $lawyerrow['lawyer_id'];?>">
                           <input type="hidden" name="lstatus" value="<?php echo $lawyerrow['lawyer_status'];?>">
                            <tr>
                                <td><?php echo $lawyerrow['firm_name'];?></td>
                                <td><?php echo $lawyerrow['email'];?></td>
                                <td><?php echo $lawyerrow['phone'];?></td>
                                <td class="yes_no"><button type="submit" name="status" class="btn btn-dark"><?php echo $lawyerrow['lawyer_status'];?></button></td>
                                <td class="action">
                            <a href="#update_lawyer<?php echo $lawyerrow['lawyer_id'];?>" class="btn btn_sm1" data-toggle="modal" title="Edit"><i class="fa fa-edit"></i></a>
                            <a href="#delete_lawyer<?php echo $lawyerrow['lawyer_id'];?>" class="btn btn_sm1" data-toggle="modal" title="Delete"><i class="fa fa-trash"></i></a>
                               </td>
                            </tr>
                        </form>
                        <div class="zind modal fade" id="update_lawyer<?php echo $lawyerrow['lawyer_id'];?>">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                                    <div class="modal-header">
                                        <h3><i class="pe-7s-users"></i> Update Lawyer Details</h3>
                                    </div>
                                    <div class="modal-body">
                                        <form class="form" role="form" method="post" action="">
                                           <input type="hidden" name="eid" value="<?php echo $lawyerrow['lawyer_id'];?>"> 
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" name="edit_firm_name" placeholder="Lawyer Name" type="text" value="<?php echo $lawyerrow['firm_name']?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" name="edit_password" placeholder="Password" type="text" value="<?php echo $lawyerrow['password']?>" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" name="edit_email" placeholder="Email" type="email" value="<?php echo $lawyerrow['email']?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" name="edit_phone" placeholder="Phone" value="<?php echo $lawyerrow['phone']?>" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-simple" name="edit_lawyer">Update</button>
                                            </div>
                                        </form>
                                      
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="zind modal fade" id="delete_lawyer<?php echo $lawyerrow['lawyer_id'];?>">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                                    <div class="modal-header">
                                        <h3><i class="pe-7s-users"></i> Delete Lawyer</h3>
                                    </div>
                                    <div class="modal-body1 modal-body">
                                      <p>Are you sure you want to delete this case... </p>
                                      <div class="del_btn">
                                    <form action="" method="post">
                                   <input type="hidden" name="did" value="<?php echo $lawyerrow['lawyer_id'];?>">
                                        <button type="submit" class="btn btn-simple" name="delete_id">Yes</button>
                                    </form>
                                        <button class="btn btn-simple" data-dismiss="modal">No</button>
                                      </div>
                                     
                                    </div>
                                </div>
                            </div>
                        </div>
                                
            <?php } ?>
                            </tbody>
                        </table>
                        </div>
            <?php } else { echo 'No Lawyers Found'; } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- manage free slot end -->

<?php 
 include("footer.php"); 
?>

