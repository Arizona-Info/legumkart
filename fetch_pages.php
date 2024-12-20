<?php
   include ('db_connection.php');
   $item_per_page = 5;
   //sanitize post value
   if(isset($_POST["page"])){
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
    if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number
   }else{
    $page_number = 1;
   }
   
   //get current starting point of records
   $position = (($page_number-1) * $item_per_page);
  ?>
<div class="col-sm-7 col-xs-12 padding-right-none">
  <?php 
   //Limit our results within a specified range. 
   $results = mysqli_query($conn, "SELECT * FROM tbl_lawyers WHERE (firm_name LIKE '%".$_SESSION['quicksearch_result']."%' OR address LIKE '%".$_SESSION['quicksearch_result']."%' OR practice_courts LIKE '%" . $_SESSION['quicksearch_result'] . "%')  AND quick_quote='Yes' AND user_status='Approved' LIMIT $position, $item_per_page");

   if(mysqli_num_rows($results) > 0) 
   { 
   $sr = 1;
   while($row = mysqli_fetch_array($results))
   {
     $namee= str_replace(' ','',$row['firm_name']);
     $address = $row['address'];
     $phone = $row['phone'];
     $latitude = $row['lattitude'];
     $longitude = $row['longitude'];
     $locations=array(array($address,$latitude,$longitude,$namee,$phone));
                   
     foreach ($locations as $element => $inner_array) 
      {
        $mapp[]= array($inner_array[0],$inner_array[1],$inner_array[2],$inner_array[3],$inner_array[4]);
      }
   ?>

<div class="relPos compare_txt" onmouseover="specificMapLocation('<?php echo  str_replace(' ', '', $row['firm_name']).$row['phone']; ?>')">
   <div class="selectProduct w3-padding" data-title="<?php echo str_replace(' ','',$row['firm_name']);?>" data-id="<?php echo str_replace(' ','',$row['firm_name']);?>" data-size="<?php echo $row['about'];?>" data-weight="<?php echo $row['website'];?>" data-processor="<?php echo $row['address'];?>" data-battery="<?php echo $row['phone'];?>">
      <h4><a href="lawyer_details.php?user_id=<?php echo $row['lawyer_id'];?>"><?php echo $row['firm_name']?></a></h4>
      <p><span>Address:</span> <?php echo $row['address'];?></p>
      <p><span>About Firm:</span>  <?php echo $row['about'];?></p>
      <ul>
        <?php if($row['phone'] != ""){ ?>
         <!-- <li><i class="fa fa-phone"></i> <?php //echo $row['phone'];?></li> -->
        <?php } ?>
        <?php if($row['website'] != ""){ ?>
         <li><i class="fa fa-globe"></i> <a href="#"><?php echo $row['website'];?></a></li>
        <?php } ?>
        <?php if($row['email'] != ""){ ?>
         <li><i class="fa fa-envelope"></i> <?php echo $row['email'];?></li>
        <?php } ?>
      </ul>
      <a class="w3-btn-floating w3-light-grey addButtonCircular addToCompare" data-toggle="modal" data-target="#myModal<?php echo $row['lawyer_id']?>">+ Legal Query</a>
   </div>
</div>
<div class="modal fade margin-top-80" id="myModal<?php echo $row['lawyer_id']?>">
<div class="modal-dialog">
   <div class="modal-content">
      <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
      <div class="modal-header">
         <h3><i class="pe-7s-note2"></i> Get Quote From <?php echo $row['firm_name']?></h3>
      </div>
      
      <form action="" method="post" id="quickform<?php echo $sr;?>">
      <div class="modal-body">
         <div id="frmContact">
            <input type="hidden" name="lawyer_id" value="<?php echo $row['lawyer_id']?>">
            <div class="form-group">
               <input type="text" id="name<?php echo $sr;?>" class="form-control" placeholder="Name" name="user_name" required>
               <span class="name-info"></span>
            </div>
            <div class="form-group">
               <input type="text" class="form-control"   pattern="[1-9]{1}[0-9]{9}" title="Enter 10 digit mobile number" placeholder="Contact" name="user_number">
            </div>
            <div class="form-group">
               <input type="email" id="email<?php echo $sr;?>" class="form-control" placeholder="Email Id" name="user_email" required>
               
            </div>
            <div class="form-group">
               <textarea class="form-control" placeholder="Enter Query (Max 500 characters)" rows="3" name="query" maxlength="500" required></textarea>
            </div>
            <!-- <div class="form-group">
               <input type="text" class="form-control" placeholder="Budget" name="budget">
            </div> -->
            <!-- <div class="mail-status"></div> -->
            <div class="captcha">
               <!-- <label>Enter Captcha</label> -->
               <input type="text" name="captcha" placeholder="Enter Captcha" id="captcha<?php echo $sr;?>" required>
               <img class="captcha_code" src="captcha_code.php"/>
               <button name="submit" type="button" class="btnRefresh" onClick="refreshCaptcha();" title="Refresh Captcha"><i class="fa fa-refresh"></i></button>
               <span class="captcha-info info"></span>
            </div>
            <div>     <button name="submit_quick" type="button" class="btnAction btn btn-simple" onClick="sendContact(<?php echo $sr;?>);">Send</button></div>
         </div>
      </div>
    </form>
   </div>
</div>
</div>
<?php
   $sr++;
   }
   ?>
        </div> 
         <div class="col-sm-5 col-xs-12 search_map">
            <div id="map" class="map"></div>
         </div>
         <?php } else {  echo '<div class="no_result"><img src="img/about.png"><p>No Results Found. <br> Please Search Again</p></div>'; } ?>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_FvTlM37FvMkElZm_L4om4_tO1zgi10Y&libraries=places&callback=initAutocomplete" async defer></script>
<script type="text/javascript">
   var locations = <?php echo json_encode($mapp);?>;
   
    function initAutocomplete() {
   gmarkers = [];

    var map = new google.maps.Map(document.getElementById('map'));

    var infowindow = new google.maps.InfoWindow();
    var LatLngList = new Array ();

    function createMarker(latlng, html) {
        var marker = new google.maps.Marker({
            position: latlng,
            map: map
        });

        google.maps.event.addListener(marker, 'click', function() {
            infowindow.setContent(html);
            infowindow.open(map, marker);
        });
        return marker;
    }

    for (var i = 0; i < locations.length; i++) {

      if(locations[i][1] != "" && locations[i][2]){

        LatLngList.push(new google.maps.LatLng (locations[i][1],locations[i][2]));

        gmarkers[$.trim(locations[i][3])+locations[i][4]] =
        createMarker(new google.maps.LatLng(locations[i][1], locations[i][2]), '<div><strong>Firm Name: ' + locations[i][3]  + '<br>' + 'Location: '+ locations[i][0] + '<br></strong></div>');
      }
    }

    // console.log(gmarkers);
    if(LatLngList.length === 0){
        LatLngList.push(new google.maps.LatLng (28.904802, 73.414845), new google.maps.LatLng (25.703413, 88.964684), new google.maps.LatLng (10.036471, 77.895307));
      }

    //  Create a new viewpoint bound
    var bounds = new google.maps.LatLngBounds ();
    //  Go through each...
    for (var i = 0, LtLgLen = LatLngList.length; i < LtLgLen; i++) {
      //  And increase the bounds to take this point
      bounds.extend (LatLngList[i]);
    }
    //  Fit these bounds to the map
    map.fitBounds (bounds);
   }

function specificMapLocation(id){
    google.maps.event.trigger(gmarkers[id],'click');
  }

</script>

<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script>

  function sendContact(i) 
    {
      if(!$("#name"+i).val()) 
       {
       $("#name"+i).attr("placeholder", "Please enter your Name");
       $("#name"+i).css('background-color','#FFFFDF');
       }

       if(!$("#email"+i).val()) 
       {
       $("#email"+i).attr("placeholder", "Please enter your Email Id");
       $("#email"+i).css('background-color','#FFFFDF');
       }

     var valid;  
     valid = validateContact(i);
     if(valid) {

      var paidmoney = $('#captcha'+i).val();
       jQuery.ajax({

      url: 'validate_quickquote.php',
      type: 'POST',
      data: $('#quickform'+i).serialize()  +'&'+'captcha=' + paidmoney,
  
       success:function(data){
        // alert(data);
        var str =data;
        if (str.includes("success"))
        {
          alert("Your Quote Enquiry has been successfully submitted");
          location.reload(true);
        }
        else if(str.includes("index.php")){
          alert("Something went wrong try again later");
          window.location.href = "index.php";
        }
        else
        { 
          alert("Wrong CAPTCHA");
          refreshCaptcha();
        }
       },
       error:function (){}
       });
      
     }
   }
 
   function validateContact(i) 
   {
    var valid = true; 
     $(".demoInputBox").css('background-color','');
     $(".info").html('');
     
     if(!$("#captcha"+i).val()) {
       $(".captcha-info").html("(required)");
       $("#captcha"+i).css('background-color','#FFFFDF');
       valid = false;
     }
     return valid;
   }
  
   function refreshCaptcha() 
   {
     $(".captcha_code").attr('src','captcha_code.php');
   }

</script>