<?php 
 include("header.php"); 

$lawlist = "SELECT * FROM tbl_lawyers WHERE lawyer_id= '".$_REQUEST['user_id']."'";
$lawquery= mysqli_query($conn,$lawlist);
$lawrow=mysqli_fetch_array($lawquery);
$practice= explode(',', $lawrow['about']);


  if(isset($_POST['Submit']))
{
    $quotesql= mysqli_query($conn,"INSERT INTO tbl_quotes(cust_name, cust_phone, cust_email, cust_query, quote_type, quote_date, lawyer_id, status) VALUES ('".$_POST['user_name']."','".$_POST['user_number']."','".$_POST['user_email']."','".$_POST['query']."','Quick','".date("Y-m-d")."', '".$_POST['lawyer_id']."', 'New')");

    echo '<script>alert("Your Quote enquiry has been successfully submitted");</script>'; 
}  
?>

<!-- inner banner -->
<section class="inner-bg over-layer-white" style="background-image: url('img/bg/4.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="mini-title">
                    <h3>Firm Profile</h3>
                    <p><a href="index.php">Home</a> <span class="fa fa-angle-right"></span> <a href="#">Firm Profile</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- lawyers_details start -->
<section class="lawyers_details">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-8">
                    <div class="profile_box" onmouseover="specificMapLocation('<?php echo  str_replace(' ', '', $lawrow['firm_name']).$lawrow['phone']; ?>')">

                    <?php 
                $namee= str_replace(' ','',$lawrow['firm_name']);
                $address = $lawrow['address'];
                $phone = $lawrow['phone'];
                $latitude = $lawrow['lattitude'];
                $longitude = $lawrow['longitude'];
                $locations=array(array($address,$latitude,$longitude,$namee,$phone));
                
                foreach ($locations as $element => $inner_array) 
                        {
                   $mapp[]= array($inner_array[0],$inner_array[1],$inner_array[2],$inner_array[3],$inner_array[4]);
                        }  
                ?>

                        <?php if($lawrow['firm_logo']!='')
                        { ?>
                        <figure style="background: url(img/firms/<?php echo $lawrow['firm_logo'];?>) center center no-repeat; background-size:cover;">
                        <?php } else { ?>
                        <figure style="background: url(img/about.jpg) center center no-repeat; background-size:cover;">
                        <?php } ?>
                            <span></span>
                        </figure>
                        <h3 class="title"><?php echo $lawrow['firm_name'];?></h3>
                        <div class="detail_content">
                            <p><span>Address : </span> <?php echo $lawrow['address'];?></p>
                        </div>
                        <div class="lawyers_contact">
                            
                            <ul class="list">
                                <li><i class="fa fa-envelope"></i> <?php echo $lawrow['email'];?></li>
                                <li><i class="fa fa-phone"></i> <?php echo $lawrow['phone'];?></li>
                                <li><i class="fa fa-globe"></i> <a href="#"><?php echo $lawrow['website'];?></a></li>
                            </ul>
                        </div>
                        
                        <div class="more_details">
                            <ul class="list">
                                <li><span>Opening Hours :</span> Monday - Friday: 9:00 - 7:00</li>
                                <li><span>Languages :</span> <?php echo $lawrow['languages'];?></li>
                            </ul>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <!-- <hr> -->
                    <div class="practices">
                        <h4 class="subtitle">Practice Areas :</h4>
                        <ul class="list">
                     <?php

                        for($i=0;$i<count($practice);$i++) 
                        {  ?>
                            <li><i class="fa fa-check-circle-o"></i> <?php echo $practice[$i];?></li>
                        <?php }
                        ?>
                        </ul>
                    </div>
                    <hr>
                    <?php if($lawrow['about_us']!='') { ?>
                    <div class="awards">
                        <h4 class="subtitle">About-us :</h4>
                        <p class="points"><?php echo $lawrow['about_us'];?></p>
                    </div>
                    <hr>

            <?php }
            $lawyerqry = "SELECT * FROM tbl_lawyers WHERE user_status='Approved' AND firm_id='".$_REQUEST['user_id']."' AND lawyer_status='Active'";
            $lawyerresults = mysqli_query($conn,$lawyerqry);
            $lawyerno= mysqli_num_rows($lawyerresults);
            if($lawyerno > 0)
            {
            ?>
            <div class="practitioners">
                <h4 class="subtitle">Our Practitioners : </h4>
                <div class="row">
            <?php
            if($lawyerno < 3 )
            {   ?>
                    <div class="practitioner1">
                        <?php 
                         while($lawyerrow=mysqli_fetch_assoc($lawyerresults))
                          { ?>
                        <div class="item">
                            <!-- <img src="img/firms/1.jpg" alt=""> -->
                            <h5><a href="lawyer_details.php?user_id=<?php echo $lawyerrow['lawyer_id'];?>"><?php echo $lawyerrow['firm_name'];?></a></h5>
                            <p><span>Email : </span> <?php echo $lawyerrow['email'];?></p>
                            <p><span>Practice Areas : </span> <?php echo $lawyerrow['about'];?></p>
                        </div>
                    
                    <?php } 
            } 
             else 
            { ?>
                    <div class="practitioner">
                         <?php 
                          while($lawyerrow=mysqli_fetch_assoc($lawyerresults))
                          { ?>
                        <div class="item">
                            <!-- <img src="img/firms/1.jpg" alt=""> -->
                            <h5><a href="lawyer_details.php?user_id=<?php echo $lawyerrow['lawyer_id'];?>"><?php echo $lawyerrow['firm_name'];?></a></h5>
                            <p><span>Email : </span> <?php echo $lawyerrow['email'];?></p>
                            <p><span>Practice Areas : </span> <?php echo $lawyerrow['about'];?></p>
                        </div>
                    
                    <?php } 
            }
            ?>  </div>
                </div>
            </div>
                    
            <?php } ?>
                </div>

                <aside class="col-md-4">
                    <div class="col-md-12 quote_section">
                        <!-- <figure><img src="img/firms/2.jpg" alt=""></figure> -->
                        <ul>
                            <li class="text-center">
                                <a href="#myModall" data-toggle="modal">Legal Query</a>
                                <div class="modal fade margin-top-30" id="myModall">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <a href="#" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></a>
                                            <div class="modal-header">
                                                <h3><i class="pe-7s-note2"></i> Get Legal Query From <?php echo $lawrow['firm_name']?></h3>
                                            </div>
                                            <div class="modal-body">
                                                <form role="form" name="form1" action="" method="post">
                                                   <input type="hidden" name="lawyer_id" value="<?php echo $lawrow['lawyer_id']?>">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="" placeholder="Name" name="user_name" >
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="" placeholder="Contact" name="user_number">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="email" class="form-control" id="" placeholder="Email Id" name="user_email" >
                                                    </div>
                                                   
                                                    <div class="form-group">
                                                        <textarea class="form-control" placeholder="Enter Query" rows="3" name="query"></textarea>
                                                    </div>

                                                    <!-- <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="Budget" name="budget">
                                                    </div> -->

                                                   <div class="form-group">
                                                    <button name="Submit" type="submit" class="btn btn-simple">Send</button>
                                                   </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!-- <li class="text-center">Add to Compare Quote</li> -->
                        </ul>
                    </div>
                    <div class="col-md-12 margin-top-30">
                        <h4 class="subtitle">Location Map :</h4>
                        <div id="dvMap" class="map" width="100%" height="150"></div>
                    </div>
                    <div class="col-md-12 margin-top-30 awards">
                        <h4 class="subtitle">Awards :</h4>
                        <ul class="points">
                            <li><span>Dec 2012:</span> Best law firm of the year 2012</li>
                            <li><span>Dec 2011:</span> Best customer services of the year 2011</li>
                            <li><span>Jul 2013:</span> Best firm of the year 2013</li>
                            <li><span>Dec 2013:</span> New award</li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>
<?php 
 include("footer.php"); 
?>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_FvTlM37FvMkElZm_L4om4_tO1zgi10Y&libraries=places&callback=initAutocomplete" async defer></script>
<script type="text/javascript">
var locations = <?php echo json_encode($mapp);?>;

function initAutocomplete() {

    if(locations[0][1] != "" && locations[0][2] != ""){

        var mapOptions = {
           center:new google.maps.LatLng(locations[0][1], locations[0][2]),
           zoom:10
        }
            
        var map = new google.maps.Map(document.getElementById("dvMap"),mapOptions);
        
        var marker = new google.maps.Marker({
           position: new google.maps.LatLng(locations[0][1], locations[0][2]),
           map: map,
        });
    }
    else{
        var mapOptions = {
           center:new google.maps.LatLng(22.900213, 78.774905),
           zoom:4
        }
            
        var map = new google.maps.Map(document.getElementById("dvMap"),mapOptions);
    }
}

function specificMapLocation(id){
    google.maps.event.trigger(gmarkers[id],'click');
  }
</script>