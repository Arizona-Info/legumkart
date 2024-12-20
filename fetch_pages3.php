<?php
   include ('db_connection.php');
   $item_per_page = 3;
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
     $type = $_SESSION['typesearch_result'];
   //Limit our results within a specified range. 
       $searchqry = "SELECT * FROM tbl_lawyers WHERE $type= 'Yes' AND user_status='Approved' LIMIT $position, $item_per_page";
   
                 
   $results = mysqli_query($conn,$searchqry);

   if(mysqli_num_rows($results) > 0) 
   { 
   $sr = 1;

   ?>

<article class="col-xs-12 search_result">
            <?php       while($row=mysqli_fetch_assoc($results))
                 
                 {   ?>  
              <div class="content" id="pop2_<?php echo str_replace(' ','',$row['firm_name'])?>">
                 <figure><img src="img/marker.svg" alt=""></figure>
                 <div class="details">
                    <h4><a href="lawyer_details.php?user_id=<?php echo $row['lawyer_id'];?>"><?php echo $row['firm_name']?></a></h4>
                    <p><span>Address:</span> <?php echo $row['address'];?></p>
                    <p><span>Practice Areas:</span> <?php echo $row['about'];?></p>
                    <p><span>Languages Known:</span> <?php echo $row['languages'];?></p>
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
                    <?php if($row['quick_quote']=='Yes')
                          { ?>    
                       <li><i class="fa fa-file-text-o"></i> <a href="#" data-toggle="modal" data-target="#myModal<?php echo $row['lawyer_id']?>">Quick Quote</a></li>
                    <?php } 
                          if($row['compare_quote']=='Yes')
                          { ?>
                       <li><i class="fa fa-exchange"></i> <a href="#">Compare Quote</a></li>
                    <?php } ?>
                    </ul>
                 </div>
                 <div class="modal fade margin-top-80" id="myModal<?php echo $row['lawyer_id']?>">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                            <div class="modal-header">
                                <h3><i class="pe-7s-note2"></i> Get Quote From <?php echo $row['firm_name']?></h3>
                            </div>
                            <div class="modal-body">
                                <form role="form" name="form1" action="" method="post">
                                    <input type="hidden" name="lawyer_id" value="<?php echo $row['lawyer_id']?>">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Name" name="user_name" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Contact" name="user_number">
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control" placeholder="Email Id" name="user_email" required>
                                    </div>
                                   
                                    <div class="form-group">
                                        <textarea class="form-control" placeholder="Enter Query" rows="3" name="query" maxlength="500" required></textarea>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Budget" name="budget">
                                    </div>
                                    <div class="form-group">
                                        <?php if(isset($msg)){?>
                                            <p><?php echo $msg;?></p>
                                        <?php } ?>
                                        
                                    </div>
                                    <div class="form-group">
                                        <button name="Submit" type="submit" class="btn btn-simple">Send</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            
              </div>
              <?php 
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

              } ?>
          </article>
        </div>
       
         <div class="col-sm-5 col-xs-12 search_map">
            <div id="dvMap" class="map"></div>
         </div>
         <?php } else {  echo '<div class="no_result"><img src="img/about.png"><p>No Results Found. <br> Please Search Again</p></div>'; } ?>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_FvTlM37FvMkElZm_L4om4_tO1zgi10Y&libraries=places&callback=initAutocomplete" async defer></script>
<script type="text/javascript">
    var locations = <?php echo json_encode($mapp);?>;
    
     function initAutocomplete() {
    var map = new google.maps.Map(document.getElementById('dvMap'), {
      zoom:4,
      center: new google.maps.LatLng(20.5937, 78.9629),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) {
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent('<div><strong>Firm Name: ' + locations[i][3]  + '<br>' + 'Location: '+ locations[i][0] + '<br></strong></div>');
          infowindow.open(map, marker);
        }
      })(marker, i));
       
       marker.addListener('click', function(marker, i) {
      return function() { 
        $('#pop2_'+locations[i][3]).toggleClass("highlightbg");
        
      }
        }(marker, i));

      // assuming you also want to hide the infowindow when user mouses-out
marker.addListener('mouseout', function() {
    infowindow.close();
});
    }
}
</script>