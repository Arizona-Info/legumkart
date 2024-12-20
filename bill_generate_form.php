<?php

	error_reporting(0);
	include ('db_connection.php');

	if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'Counsel')
   {
     echo  '<script>window.location="index.php"</script>';
   }

    $invoice_id = $_POST['uni_id'];
	$sql_query3 = "SELECT cc_bill_pdf, cc_case_id, cc_date, cc_next_date, cc_type FROM tbl_counsel_cases WHERE cc_id = '".$_POST['uni_id']."'";
    $result3 = mysqli_query($conn,$sql_query3);
    $row3 = mysqli_fetch_array($result3);
    if($row3['cc_bill_pdf']!="")
    {
        echo  '<script>window.location="counselor_payment.php"</script>';
    }

	$sql_query="SELECT counsel_name, counsel_address, counsel_pan_no, counsel_gst FROM tbl_counsel WHERE counsel_id = '".$_SESSION['user_id']."'";
    $result = mysqli_query($conn,$sql_query);
    $row = mysqli_fetch_array($result);

    $sql_query2 = "SELECT firm_id, firm_name, address, email, phone FROM tbl_lawyers WHERE lawyer_id = '".$_POST['uni_id2']."'";
    $result2 = mysqli_query($conn,$sql_query2);
    $row2 = mysqli_fetch_array($result2);

    if($row2['firm_id'] != 0){
        $sql_query2 = "SELECT firm_name, address, email, phone FROM tbl_lawyers WHERE lawyer_id = '".$row2['firm_id']."'";
        $result2 = mysqli_query($conn,$sql_query2);
        $row2 = mysqli_fetch_array($result2);
    }

    $sql_query5 = "SELECT party_a, party_b, case_number, judge_name, court_name, court_number FROM tbl_cases WHERE case_id = '".$row3['cc_case_id']."'";
    $result5 = mysqli_query($conn,$sql_query5);
    $row5 = mysqli_fetch_array($result5);    

?>
<html>
<head>
    <meta charset="utf-8">
    <title>Legal-India</title>
    
    <style>
    .invoice-box {
        width: 715px;
        height: 958px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
        background-color: white;
    }
    
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }
    
    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }
    
    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }
    
    .invoice-box table tr.top table td {
        padding-bottom: 10px;
    }
    
    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }
    
    .invoice-box table tr.information table td {
        padding-bottom: 10px;
    }
    
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    
    .invoice-box table tr.details td {
        padding-bottom: 10px;
    }
    
    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }
    
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    
    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }

    .float_left{
        float: left;
    }
    
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }
    
    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }
    
    .rtl table {
        text-align: right;
    }
    
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div id="invoice-box" class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <!-- <img src="img/logo-2.png" style="width:100%; max-width:150px;background-color: black;border-radius: 5px"> -->
                            </td>
                            
                            <td>
                                <!-- Invoice #: C<?php echo $invoice_id; ?><br> -->
                                Created on <?php echo date('d M Y'); ?><br>
                                Invoice created by <u><?php echo $row['counsel_name']; ?></u><br>
                                <?php echo $row['counsel_address']; ?><br>
                                <?php echo $_SESSION['user_email']; ?><br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information" style="padding-bottom: 0px">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                To,<br>
                                <?php echo $row2['firm_name']; ?><br>
                                Advocate<br>
                                <?php echo $row2['address']; ?><br>
                                <?php echo $row2['phone']; ?><br><br>
                                <table  style="border:  2px solid #eee">
                                    <tr class="heading">
                                        <td style="padding-bottom: 0px;text-align: center;" colspan="2"><b>Case Details</b></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 25%"><b>Case Number:</b></td>
                                        <td class="float_left"><?php echo $row5['case_number']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Court Number:</b></td>
                                        <td class="float_left"><?php echo $row5['court_number']; ?></td>
                                    </tr>
                                    <tr style="padding-bottom: 0px">
                                        <td><b>Court Name:</b></td>
                                        <td class="float_left" class="float_left"><?php echo $row5['court_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Court Judge:</b></td>
                                        <td class="float_left"><?php echo $row5['judge_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Parties:</b></td>
                                        <td class="float_left"><?php echo $row5['party_a']; ?> vs <?php echo $row5['party_b']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><b><?php if($row3['cc_type'] == "Conference" ){ echo "Conference"; }else{ echo "Hearing";} ?> Date:</b></td>
                                        <td class="float_left"><?php 
                                            if($row3['cc_type'] == "Conference" ){ $date = date_create($row3['cc_date']); }else{ $date = date_create($row3['cc_next_date']);}
                                            echo date_format($date,"d M Y");
                                        ?></td>
                                    </tr>
                                </table>
                            </td>                            
                        </tr>
                    </table>
                </td>
            </tr>
          
            <tr class="heading">
                <td>
                    Item
                </td>
                
                <td>
                    Price
                </td>
            </tr>
            
            <tr class="item">
                <td>
                    Amount
                </td>
                
                <td>
                    <i class="fa fa-rupee"></i> <?php echo $_POST['amt_1']; ?>
                </td>
            </tr>
            
            <tr class="item last">
                <td>
                    GMS
                </td>
                
                <td>
                    <?php echo $_POST['amt_2']; ?>
                </td>
            </tr>
            
            <tr class="total">
                <td></td>
                
                <td>
                   Total: <i class="fa fa-rupee"></i> <?php echo $_POST['amt_3']; ?>
                </td>
            </tr>
            <tr><td></td><td></td></tr>
            <tr>
                <td>
                    PAN No.: <?php echo $row['counsel_pan_no']; ?>
                </td>
                <td style="float: left;">
                    GST No.: <?php echo $row['counsel_gst']; ?>
                </td>
            </tr>
            <tr><td></td><td></td></tr>
            <tr>
               <td colspan="2">
                   This is a computer generated invoice no signature is required.
               </td> 
            </tr>
        </table>

        <?php
        	$uid = $_POST['uni_id'].date("ymdhis");
        ?>
        <form method="POST" enctype="multipart/form-data" action="save.php" id="myForm" target="_blank">
			<input type="hidden" name="img_val" id="img_val" value="" />
            <input type="hidden" name="export_no" id="pdf_name123" value="<?php echo $uid; ?>" />
			<input type="hidden" name="export_no12" id="project_id12" value="<?php echo $_POST['uni_id']; ?>" />
		</form>	

        <div id="print_html" style="display: none">
            <a type="submit" onclick="capture();" style="cursor: pointer;">Save & print <i class="fa fa-floppy-o"></i></a>
        </div>
    </div>
</body>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(window).on('load', function(){
            $("#print_html").slideDown();
        })
    })
</script>
<script type="text/javascript">
	function capture() 
	{
        $("#print_html").hide();
        unique_id = $("#pdf_name123").val();
        id = $("#project_id12").val();

        $.ajax({
            type: "POST",
            data: { 'id' : id , 'id2' : unique_id },
            url: "on_click_invoice.php",
            success:function(rec){
            }
        })

        return html2canvas($('#invoice-box'), {
			background: "#ffffff",
			onrendered: function(canvas) {
				var myImage = canvas.toDataURL("image/jpeg");
				var pdf = new jsPDF('portrait', 'pt','a4');
                var width = pdf.internal.pageSize.width;    
                var height = pdf.internal.pageSize.height;
                pdf.addImage(myImage, 'JPEG', 0, 0, width, height); // 2: 19
				pdfdata = pdf.output("datauristring");
				$('#img_val').val(pdfdata);
				document.getElementById("myForm").submit();
			}
		});

	}
</script>
</html>