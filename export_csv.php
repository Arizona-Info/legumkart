<?php 
if(isset($_POST['export_cases']))
{
  $filename = "cases.csv";
  $fp = fopen('php://output', 'w');

  header('Content-type: application/csv');
  header('Content-Disposition: attachment; filename='.$filename);

  $header= array('Date', 'Case Number', 'Category', 'Client Name', 'Client Phone', 'Court Name', 'Court Number', 'Judge Name', 'Party A', 'Party B', 'Stage', 'File Number');  
  
  fputcsv($fp, $header);

$query_join = "";
$query_join2 = "";

if(isset($_POST['export_type']) AND $_POST['export_type'] == 'datewise')
{
  $query_join = "AND next_date BETWEEN '".$_POST['start_case_date']."' AND '".$_POST['end_case_date']."'";

  $query_join2 = "AND next_case_date BETWEEN '".$_POST['start_case_date']."' AND '".$_POST['end_case_date']."'";
}

 include("db_connection.php");
 
  $query = mysqli_query($conn, "SELECT 	case_id,next_date,case_number,category,client_name,client_phone,court_name,court_number,judge_name,party_a,party_b,stage,file_no FROM tbl_cases WHERE  lawyer_id='".$_SESSION['user_id']."' ".$query_join."");


  while($fetch = mysqli_fetch_array($query,MYSQLI_ASSOC))
	{       
		    $e = array();
		    $e['case_date'] = $fetch['next_date'];
		    $e['case_number'] = $fetch['case_number'];
            $e['category'] = $fetch['category'];
            $e['client_name'] = $fetch['client_name'];
            $e['client_phone'] = $fetch['client_phone'];
            $e['court_name'] = $fetch['court_name'];
            $e['court_number'] = $fetch['court_number'];
            $e['judge_name'] = $fetch['judge_name'];
            $e['party_a'] = $fetch['party_a'];
            $e['party_b'] = $fetch['party_b'];
            $e['stage'] = $fetch['stage'];
            $e['file_no'] = $fetch['file_no'];

            fputcsv($fp, $e);

	$query2 = mysqli_query($conn, "SELECT next_case_date,next_stage FROM tbl_case_nextdt where next_case_id='".$fetch['case_id']."'  ".$query_join2."");
    $cnt = mysqli_num_rows($query2);
	if($cnt != 0)
	{
		while($fetch2 = mysqli_fetch_array($query2,MYSQLI_ASSOC))
		{
			
		    $e['case_date'] = $fetch2['next_case_date'];
		    $e['case_number'] = $fetch['case_number'];
            $e['category'] = $fetch['category'];
            $e['client_name'] = $fetch['client_name'];
            $e['client_phone'] = $fetch['client_phone'];
            $e['court_name'] = $fetch['court_name'];
            $e['court_number'] = $fetch['court_number'];
            $e['judge_name'] = $fetch['judge_name'];
            $e['party_a'] = $fetch['party_a'];
            $e['party_b'] = $fetch['party_b'];
            $e['stage'] = $fetch2['next_stage'];
            $e['file_no'] = $fetch['file_no'];

            fputcsv($fp, $e);
		}
	}

	   
	}	


exit;
}


if(isset($_POST['export_counsel_cases']))
{
  $filename = "cases.csv";
  $fp = fopen('php://output', 'w');

  header('Content-type: application/csv');
  header('Content-Disposition: attachment; filename='.$filename);

  $header= array('Date', 'Lawyer Name', 'Lawyer Phone', 'Lawyer EmaIL', 'Court Name','Court Number', 'Judge Name', 'Case Number', 'Client', 'Parties', 'Court Hearing Date', 'Case Type', 'Conference/Hearing Date', 'Conference/Hearing Time', 'Conference Place');  
  
  fputcsv($fp, $header);

$query_join = "";

if(isset($_POST['export_type']) AND $_POST['export_type'] == 'datewise')
{
  $query_join = "AND t1.cc_date BETWEEN '".$_POST['start_case_date']."' AND '".$_POST['end_case_date']."'";
}

 include("db_connection.php");
 
  $query = mysqli_query($conn, "SELECT t1.*, t2.* FROM tbl_counsel_cases t1, tbl_cases t2 WHERE t1.cc_case_id=t2.case_id AND t1.cc_status='Accepted' AND t1.counsel_id = '".$_SESSION['user_id']."' ".$query_join."");


  while($fetch = mysqli_fetch_array($query,MYSQLI_ASSOC))
	{       
		    $counsellawyer = mysqli_query($conn,"SELECT firm_name,phone,email FROM tbl_lawyers WHERE lawyer_id='".$fetch['lawyer_id']."'");
            $counsellawyerrow=mysqli_fetch_array($counsellawyer);

		    $e = array();
		    $e['case_date'] = $fetch['cc_date'];
		    $e['firm_name'] = $counsellawyerrow['firm_name'];
		    $e['phone'] = $counsellawyerrow['phone'];
		    $e['email'] = $counsellawyerrow['email'];
            $e['court_name'] = $fetch['court_name'];
            $e['court_number'] = $fetch['court_number'];
            $e['judge_name'] = $fetch['judge_name'];
		    $e['case_number'] = $fetch['case_number'];
            $e['client_name'] = $fetch['client_name'];
            $e['parties'] = $fetch['party_a']." VS ".$fetch['party_b'];
            $e['hearing date'] = $fetch['cc_next_date'];
            $e['type'] = $fetch['cc_type'];
            $e['cc_date'] = $fetch['cc_date'];
            if($fetch['cc_type']=='Hearing')
            {
               $e['cc_time'] = $fetch['cc_hearing_time'];
            }
            else
            {
               $e['cc_time'] = $fetch['cc_time'];
            }
            
            $e['con_place'] = $fetch['cc_place'];


            fputcsv($fp, $e);   
	}	


exit;
}
?>