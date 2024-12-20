<html>
<head>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Directions service</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #floating-panel {
        position: absolute;
        top: 10px;
        left: 25%;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }
    </style>
  </head>
  <body>
    <div id="floating-panel">
    <b>Start: </b>
    <select id="start">
      <option value="Mumbai, in">Mumbai</option>    
      <option value="New Delhi, in">New Delhi</option>    
      <option value="Kolkata, in">Kolkata</option>    
      <option value="Chennai, in">Chennai</option>    
      <option value="Indore, in">Indore</option>    
      <option value="Ahemdabad, in">Ahemdabad</option>    
      <option value="Jaipur, in">Jaipur</option>    
      <option value="Bengluru, in">Bengluru</option> 
      <option value="Hyderabad, in">Hyderabad</option>    
      <option value="Pune, in">Pune</option>    
      <option value="Bhopal, in">Bhopal</option>  
    </select>
    <b>End: </b>
    <select id="end">
      <option value="Mumbai, in">Mumbai</option>    
      <option value="New Delhi, in">New Delhi</option>    
      <option value="Kolkata, in">Kolkata</option>    
      <option value="Chennai, in">Chennai</option>    
      <option value="Indore, in">Indore</option>    
      <option value="Ahemdabad, in">Ahemdabad</option>    
      <option value="Jaipur, in">Jaipur</option>    
      <option value="Bengluru, in">Bengluru</option> 
      <option value="Hyderabad, in">Hyderabad</option>    
      <option value="Pune, in">Pune</option>    
      <option value="Bhopal, in">Bhopal</option>  
    </select>
    </div>
    <div id="map"></div>
    <script>
      function initMap() {
        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer;
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 7,
          center: {lat: 22.08672, lng: 79.42444}
        });
        directionsDisplay.setMap(map);

        var onChangeHandler = function() {
          calculateAndDisplayRoute(directionsService, directionsDisplay);
        };
        document.getElementById('start').addEventListener('change', onChangeHandler);
        document.getElementById('end').addEventListener('change', onChangeHandler);
      }

      function calculateAndDisplayRoute(directionsService, directionsDisplay) {
        directionsService.route({
          origin: document.getElementById('start').value,
          destination: document.getElementById('end').value,
          travelMode: 'DRIVING'
        }, function(response, status) {
          if (status === 'OK') {
            directionsDisplay.setDirections(response);
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        });
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_FvTlM37FvMkElZm_L4om4_tO1zgi10Y&callback=initMap">
    </script>
  </body>
</html>
</head>
<body>

</body>
<html>