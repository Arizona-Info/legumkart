<div class="col-md-12" style="margin-bottom: 50px"></div>
<footer class="small_footer">
    <div class="container-fluid">
        <div class="footer_link text-center">
            <ul class="left_links">
                <li><a href="#">Terms Of Use</a></li>
                <li><a href="#">Policy</a></li>
            </ul>
            <p class="copyright_line">© 2018, Legal All Rights Reserved <!-- <span>|</span> Designed By <a href="http://www.arizonamediaz.com" target="_blank"> Arizona Mediaz</a> --></p>
            <!-- <ul class="right_links">
                <li><a href="searchresultstype.php?type=free_consultation">Free Legal Advice</a></li>
                <li><a href="searchresultstype.php?type=discount">10% Discount on First Bill</a></li>
            </ul> -->
        </div>
    </div>
</footer>

<a href="#" class="scrollup"><i class="pe-7s-angle-up" aria-hidden="true"></i></a>
</div>


<!--============= jQuery ==============-->
<?php
  if((isset($page)) && ($page != "calendar")){
?>
<script type="text/javascript" src="js/jquery.min.js"></script>
<?php
  }
?>

<!-- Bootstrap Core JavaScript -->
<script type="text/javascript" src="js/bootstrap.min.js"></script>

<!-- all plugins and JavaScript -->
<script type="text/javascript" src="js/css3-animate-it.js"></script>
<script type="text/javascript" src="js/bootstrap-dropdownhover.min.js"></script>
<script type="text/javascript" src="js/owl.carousel.min.js"></script>
<script type="text/javascript" src="js/gallery.js"></script>
<script type="text/javascript" src="js/player.min.js"></script>
<!--<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>-->
<script src="js/jquery1.dataTables.min.js"></script>
    <script src="js/dataTables.responsive.min.js"></script>

<!-- Main Custom JS -->
<script type="text/javascript" src="js/script.js"></script>
 <!-- Resource jQuery -->
 <script src="js/Compare.js"></script>
<script src="js/tinyslide.js" /></script>
<!-- map -->
<!-- <script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script> -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_FvTlM37FvMkElZm_L4om4_tO1zgi10Y&libraries=places&callback=initAutocomplete" async defer></script>


<!-- news_ticker -->
<script>
    jQuery(function(){
        jQuery('#ticker').tickerme();
    });
</script>
<script src="js/news_ticker.js"></script>

<script>
      var tiny = $('#tiny').tiny().data('api_tiny');
</script>

<script>
$(document).ready(function(){
//script to remove google language translators top bar 
if(document.getElementsByClassName('goog-te-banner-frame skiptranslate')[0] !== undefined) {
  document.getElementsByClassName('goog-te-banner-frame skiptranslate')[0].style.display  = 'none';
  document.body.style.top = '0px';
}

//script to remove google language translators top bar ends


    $("#expand1").click(function(){
        // $("#care_info1").removeClass("collapse in");
        $("#care_info1").addClass("collapse");

         // $("#care_info2").removeClass("collapse in");
        $("#care_info2").addClass("collapse");
    });


});
</script>
<script type="text/javascript">
//script function called for language translators  
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'en,hi,mr,gu', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}
</script>

<!-- date picker -->
<script src="js/bootstrap-datepicker.js"></script>
<script type="text/javascript">
    // When the document is ready
    $(document).ready(function () {
        $('.example1').datepicker({
            format: "yyyy-mm-dd",
             autoclose: true
        });  
    });
</script>


<script type="text/javascript" charset="utf8" src="js/jquery.dataTables.min.js"></script>

  <script>
  $(function(){
    $("#example").dataTable();
  })
  </script>
<!-- datatable scripts ends here -->

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>


<?php
    if(isset($conn)){
        mysqli_close($conn);
    }

?> 
</body>
</html>

