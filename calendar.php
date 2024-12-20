<?php 
   $page = 'calendar';
   include("header.php"); 
   if(!isset($_SESSION['user_id']))
   {
     echo  '<script>window.location="index.php"</script>';
   }

   if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] == "Counsel" || $_SESSION['user_type'] == "Intern")
   {
     echo  '<script>window.location="index.php"</script>';
   }

   // footer updated with it condition, procesed page included, and assets folder include
?>
<link href='assets/css/fullcalendar.css' rel='stylesheet' />
<link href='assets/css/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='assets/js/moment.min.js'></script>
<script src='assets/js/jquery.min.js'></script>
<script src='assets/js/jquery-ui.min.js'></script>
<script src='assets/js/fullcalendar.min.js'></script>
<script>

	$(document).ready(function() {

		var zone = "05:30";  //Change this to your timezone

	$.ajax({
		url: 'process.php',
        type: 'POST', // Send post data
        data: 'type=fetch',
        async: false,
        success: function(s){
        	json_events = s;
        }
	});


	var currentMousePos = {
	    x: -1,
	    y: -1
	};
		jQuery(document).on("mousemove", function (event) {
        currentMousePos.x = event.pageX;
        currentMousePos.y = event.pageY;
    });

		/* initialize the external events
		-----------------------------------------------------------------*/

		$('#external-events .fc-event').each(function() {

			// store data so the calendar knows to render an event upon drop
			$(this).data('event', {
				title: $.trim($(this).text()), // use the element's text as the event title
				stick: true // maintain when user navigates (see docs on the renderEvent method)
			});

			// make the event draggable using jQuery UI
			$(this).draggable({
				zIndex: 999,
				revert: true,      // will cause the event to go back to its
				revertDuration: 0  //  original position after the drag
			});

		});


		/* initialize the calendar
		-----------------------------------------------------------------*/

		$('#calendar').fullCalendar({
			events: JSON.parse(json_events),
			//events: [{"id":"14","title":"New Event","start":"2015-01-24T16:00:00+04:00","allDay":false}],
			utc: true,
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			editable: true,
			droppable: true, 
			slotDuration: '00:30:00',
		    eventClick: function(event, jsEvent, view) {
		    	console.log(event.id);
		          // var title = prompt('Event Title:', event.title, { buttons: { Ok: true, Cancel: false} });
		          var title = event.title;
		          if (title){
		              event.title = title;
		              // console.log('type=changetitle&title='+title+'&eventid='+event.id);
		              $.ajax({
				    		url: 'process.php',
				    		data: 'type=changetitle&title='+title+'&eventid='+event.id,
				    		type: 'POST',
				    		dataType: 'json',
				    		success: function(response){	
				    			$('#txt1').val(response['case_number']);
				    			$('#txt2').val(response['next_date']);
				    			$('#txt3').val(response['court_number']);
				    			$('#txt4').val(response['judge_name']);
				    			$('#txt5').val(response['next_date']);
				    			$('#txt6').val(response['category']);
				    			$('#txt7').val(response['client_name']);
				    			$('#txt8').val(response['client_phone']);
				    			parties = response['party_a'] + " vs " + response['party_b'];
				    			$('#txt9').val(parties);
				    			$('#txt10').val(response['stage']);
				    			$('#txt11').val(response['court_name']);
				    			// if(response.status == 'success')
				    			$('#calendar').fullCalendar('updateEvent',event);
				    		},
				    		error: function(e){
				    			alert('Error processing your request: '+e.responseText);
				    		}
				    	});
		          }
			}
		});

	function getFreshEvents(){
		$.ajax({
			url: 'process.php',
	        type: 'POST', // Send post data
	        data: 'type=fetch',
	        async: false,
	        success: function(s){
	        	freshevents = s;
	        }
		});
		$('#calendar').fullCalendar('addEventSource', JSON.parse(freshevents));
	}


	function isElemOverDiv() {
        var trashEl = jQuery('#trash');

        var ofs = trashEl.offset();

        var x1 = ofs.left;
        var x2 = ofs.left + trashEl.outerWidth(true);
        var y1 = ofs.top;
        var y2 = ofs.top + trashEl.outerHeight(true);

        if (currentMousePos.x >= x1 && currentMousePos.x <= x2 &&
            currentMousePos.y >= y1 && currentMousePos.y <= y2) {
            return true;
        }
        return false;
    }

	});

</script>
<style>

	#wrap {
		/*width: 1200px;*/
		/*margin: 0 auto;*/
	}
		
	#external-events {
		float: left;
		/*width: 200px;*/
		padding: 0 10px;
		text-align: left;
	}
		
	/*#external-events p input {
		margin: 0;
		vertical-align: middle;
	}*/

	#calendar {
		float: right;
		/*width: 900px;*/
	}

</style>
	<br>	
	<div id='wrap'>
	
		<div id='calendar' class="col-lg-9 col-md-9 col-sm-12 col-xs-12"></div>
	

		<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" id="external-events">
			<div id="form1">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<a href="cases.php" class="btn btn-dark" style="text-transform:initial;float: right;">Back</a>
				</div>
				
				
				<!-- <div class="col-lg-12 col-md-12 col-sm-6 col-xs-6"> -->
					<!-- <label class="col-lg-12 col-md-12 col-sm-6">Pre date:</label> -->
					<!-- <input class="col-lg-12 col-md-12 col-sm-6 form-control" type="text" id="txt2" readonly placeholder="Pre date"></input> -->
				<!-- </div> -->
				<div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
					<label class="col-lg-12 col-md-12 col-sm-6">Court Name:</label>
					<input class="col-lg-12 col-md-12 col-sm-6 form-control" type="text" id="txt11" readonly placeholder=""></input>
				</div>

				<div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
					<label class="col-lg-12 col-md-12 col-sm-6">Court Number:</label>
					<input class="col-lg-12 col-md-12 col-sm-6 form-control" type="text" id="txt3" readonly placeholder=""></input>
				</div>

				<div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
					<label class="col-lg-12 col-md-12 col-sm-6">Judge Name:</label>
					<input class="col-lg-12 col-md-12 col-sm-6 form-control" type="text" id="txt4" readonly placeholder=""></input>
				</div>

				<div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
					<label class="col-lg-12 col-md-12 col-sm-6">Case Number:</label>
					<input class="col-lg-12 col-md-12 col-sm-6 form-control" type="text" id="txt1" readonly placeholder=""></input>
				</div>

				<div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
					<label class="col-lg-12 col-md-12 col-sm-6">Name Of Parties:</label>
					<input class="col-lg-12 col-md-12 col-sm-6 form-control" type="text" id="txt9" readonly placeholder=""></input>
				</div>

				<div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
					<label class="col-lg-12 col-md-12 col-sm-6">Stage:</label>
					<input class="col-lg-12 col-md-12 col-sm-6 form-control" type="text" id="txt10" readonly placeholder=""></input>
				</div>
				<!-- <div class="col-lg-12 col-md-12 col-sm-6 col-xs-6"> -->
					<!-- <label class="col-lg-12 col-md-12 col-sm-6">Next Date:</label> -->
					<!-- <input class="col-lg-12 col-md-12 col-sm-6 form-control" type="text" id="txt5" readonly placeholder="Next Date"></input> -->
				<!-- </div> -->

				<div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
					<label class="col-lg-12 col-md-12 col-sm-6">Client Name:</label>
					<input class="col-lg-12 col-md-12 col-sm-6 form-control" type="text" id="txt7" readonly placeholder=""></input>
				</div>

				<div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
					<label class="col-lg-12 col-md-12 col-sm-6">Client Phone:</label>
					<input class="col-lg-12 col-md-12 col-sm-6 form-control" type="text" id="txt8" readonly placeholder=""></input>
				</div>

				<div class="col-lg-12 col-md-12 col-sm-6 col-xs-6" style="margin-bottom:40px">
					<label class="col-lg-12 col-md-12 col-sm-6">Category:</label>
					<input class="col-lg-12 col-md-12 col-sm-6 form-control" type="text" id="txt6" readonly placeholder=""></input>
				</div>
			</div>
		</div>


	</div>
<?php 
   include("footer.php"); 
?>