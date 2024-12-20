<?php 
 include("header.php");
 $page = 'intern_password.php'; 

  if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'Intern')
   {
     echo  '<script>window.location="index.php"</script>';
   }
?>

<!-- manage free slot start -->
<section class="manage_freeslot">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <?php include("sidebar2.php"); ?>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <div class="right_panel">
                        <h3 class="right1">Change Password</h3>
                    </div>
                    <div class="col-md-12 col-sm-9 col-xs-12 padding-left-none">
                        <div class="right2">
                            <form role="form" name="form1" id="form1" method="post">
                              <input type="hidden" name="user_typee" value="intern">
                              <div class="form-group">
                                <label class="col-md-3" for="">Current Password :</label>
                                <div class="col-md-9">
                                    <input class="form-control" name="old" placeholder="Current Password" type="password" id="old" required>
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="col-md-3" for="">New Password :</label>
                                <div class="col-md-9">
                                    <input class="form-control" name="pass" placeholder="New Password" type="password" id="pass" required>
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="col-md-3" for="">Confirm password :</label>
                                <div class="col-md-9">
                                    <input class="form-control" name="cpass" placeholder="Confirm password" type="password" id="cpass" required>
                                </div>
                              </div>
                              <button type="submit" name="submit" class="btn btn-dark">Update</button>
                               <p class="text-center margin-top-20"><span class="text-danger" id="formchw"></span></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- manage free slot end -->


<!-- manage free slot end -->
<?php 
 include("footer.php"); 
?>

<script>
    $(document).ready(function() {
    $('#form1').on('submit',function(e)
    {
        var p=$('input#pass').val();
        var cp=$('input#cpass').val();
        
        if(p == ""){
            alert("Enter new password!");
        }
        else if(p!==cp)
        {
            alert("Passwords don't match");
        }
        else
        {
        $.post('ajaxpassword.php',$(this).serialize(),function(data){
            $('#formchw').html(data);
        });
        }
    e.preventDefault(); 
    });
    });
</script>