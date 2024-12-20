<?php 

error_reporting(0);
session_start();
if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == "Intern"){
    echo  '<script>window.location="internship_request.php"</script>';
 }
 else if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == "Counsel"){
    echo  '<script>window.location="counselor_cases_detail.php"</script>';
 }
 else if(isset($_SESSION['user_type']) && ($_SESSION['user_type'] =='lawyer' || $_SESSION['user_type'] =='firm')){
    echo  '<script>window.location="cases.php"</script>';
 }

 include("header.php"); 
 $page='index';

 if(isset($_SESSION['language']))
 {
    unset($_SESSION['language']);
 }
 
if(isset($_SESSION['specialization']))
 {
    unset($_SESSION['specialization']);
 }
 
if(isset($_SESSION['Lawyer_id'])){
  unset($_SESSION['Lawyer_id']);
}

?>

<div id="background-carousel">
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
      <div class="item active" style="background-image:url(img/bg/bg_1.jpg)"></div>
    </div>
  </div>
</div>

<div class="fixed_banner main_banner">
  <div class="bg_overlay1"></div>
  <div class="search_lawyer">
      <div class="title col-xs-12">
          <h2 class="text-center">Search Lawyers</h2>
      </div>
      <div class="search_fields">
          <form method="post" action="searchresults.php">
              <div class="input_field">
                <input type="search" name="searchresult" value="" placeholder="Enter Court Name/ Place / Firm Name / Lawyer Name" class="form-control" required>
                <button type="submit" class="btn"><i class="fa fa-search"></i></button>
              </div>
              <p class="eg">( eg. Bombay High Court / Mumbai / Firm / Lawyer )</p>

              <a data-toggle="collapse" data-parent="#accordion" id="expand1" href="#care_info2"> Advanced Search +</a>

              <div id="care_info2" class="collapse col-xs-12">
                  <div class="row checkk">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                      <select class="form-control" name="specialization">
                            <option value="">Select Specialization</option>
                            <?php
                              $query_for_spec = "SELECT pa_name FROM tbl_practice_areas";
                              $result_of_spec = mysqli_query($conn, $query_for_spec);
                              while ($result_of_spec2 = mysqli_fetch_assoc($result_of_spec)) {
                            ?>
                              <option value="<?php echo $result_of_spec2['pa_name']; ?>"><?php echo $result_of_spec2['pa_name']; ?></option>
                            <?php
                              }
                            ?>
                      </select>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <select class="form-control" name="language">
                            <option value="">Select Language</option>
                            <option value="English">English</option>
                            <option value="Hindi">Hindi</option>
                            <option value="Marathi">Marathi</option>
                            <option value="Gujarati">Gujarati</option>
                        </select>
                    </div>
                  </div>
                </div>
          </form>
      </div>
  </div>
</div>
<!-- fixed banner end -->
<?php 
 include("footer.php"); 
?>

<script type="text/javascript">
    var locations = [
      ['London, UK ....', 51.5285582, -0.2416798, 1],
      ['Maidstone, UK ....', 51.2627753, 0.4850587, 2],
      ['Canterbury, UK ....', 51.2785387, 1.0488316, 3],
      ['Oxford, UK ....', 51.7503955, -1.3176271, 4]
    ];
    
     function initAutocomplete() {
    var map = new google.maps.Map(document.getElementById('dvMap'), {
      zoom:7,
      center: new google.maps.LatLng(51.5040222, -0.0854421),
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
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
}
</script>
<script>
  $('#myCarousel').carousel({
    pause: 'none'
  })
</script>

 