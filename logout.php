<?php
session_start();
if(session_destroy())
{
	session_unset();
	echo "<script>window.location='index.php'</script>";
}
?>