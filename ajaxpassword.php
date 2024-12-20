<?php 
session_start();
include "db_connection.php";

if(!isset($_SESSION['user_email']))
{
	//header("Location:index.php");	
	echo "<script>window.location='index.php'</script>";
}
else
{
	$user = $_SESSION["user_email"];
	$old = $_POST['old'];
	$new = $_POST['pass'];
    
    if($_POST['user_typee']=='lawyer')
    {
	$sql = mysqli_query($conn,"SELECT password FROM tbl_lawyers where email = '".$_SESSION['user_email']."'");
	$sqll = mysqli_fetch_array($sql);
	$p = $sqll['password'];

	if($old == $p)
	{
		$change=mysqli_query($conn,"UPDATE tbl_lawyers SET password='$new' where email = '".$_SESSION['user_email']."'");
		if($change)
		{
			echo "Password Updated Successfully";
			$msg = "Password Updated Successfully";
		}
	}
	else
	{ 
		echo "Wrong Password";
		$msg = "Wrong Password";
	}
    }

    if($_POST['user_typee']=='counselor')
    {
    $sql = mysqli_query($conn,"SELECT counsel_password FROM tbl_counsel where counsel_email = '".$_SESSION['user_email']."'");
    $sqll = mysqli_fetch_array($sql);
    $p = $sqll['counsel_password'];

    if($old == $p)
    {
        $change=mysqli_query($conn,"UPDATE tbl_counsel SET counsel_password='$new' where counsel_email = '".$_SESSION['user_email']."'");
        if($change)
        {
            echo "Password Updated Successfully";
            $msg = "Password Updated Successfully";
        }
    }
    else
    { 
        echo "Wrong Password";
        $msg = "Wrong Password";
    }
    }

   if($_POST['user_typee']=='intern')
    {
    $sql = mysqli_query($conn,"SELECT intern_password FROM tbl_intern where intern_email = '".$_SESSION['user_email']."'");
    $sqll1 = mysqli_fetch_array($sql);
    $p = $sqll1['intern_password'];

    if($old == $p)
    {
        $change=mysqli_query($conn,"UPDATE tbl_intern SET intern_password='$new' where intern_email = '".$_SESSION['user_email']."'");
        if($change)
        {
            echo "Password Updated Successfully";
            $msg = "Password Updated Successfully";
        }
    }
    else
    { 
        echo "Wrong Password";
        $msg = "Wrong Password";
    }
    }
}
?>

<script>
<?php if($msg == ''){}else{?>
 $(document).ready(function () {
        window.setTimeout(function () {
            addAlert("<?php echo $msg;?>");
        }, 800);
    });
<?php } ?>    
    function addAlert(message) {
        var id = createUUID();
        var JQueryId = "#" + id;

        $('#alerts').append(
            '<div style="display:none;" class="alert alert-success" id="' + id + '">' +
                '<button type="button" class="close" data-dismiss="alert">' +
                '×</button>' + message + '</div>');

        $(JQueryId).fadeIn(2000);
        window.setTimeout(function () {
            // closing the popup
            $(JQueryId).fadeTo(300, 0.5).slideUp(2000, function () {
                $(JQueryId).alert('close');
            });
        }, 5000);
    }

    function onError() {
        addAlert('Lost connection to server.');
    }

    function ViewModel()
    {
        var self = this;
        self.bookmarksArray = ko.observableArray();
    }

    function Tag(name)
    {
        var self = this;
        self.Name = name;
    }

    function createUUID() {
        // http://www.ietf.org/rfc/rfc4122.txt
        var s = [];
        var hexDigits = "0123456789abcdef";
        for (var i = 0; i < 36; i++) {
            s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1);
        }
        s[14] = "4";  // bits 12-15 of the time_hi_and_version field to 0010
        s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1);  // bits 6-7 of the clock_seq_hi_and_reserved to 01
        s[8] = s[13] = s[18] = s[23] = "-";

        var uuid = s.join("");
        return uuid;
    }

</script>