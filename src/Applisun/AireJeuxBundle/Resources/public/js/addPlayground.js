var map;
      var geocoder;
      var mapOptions = { center: new google.maps.LatLng(0.0, 0.0), zoom: 2,
        mapTypeId: google.maps.MapTypeId.ROADMAP };

      function initialize() {
var myOptions = {
                center: new google.maps.LatLng(48.84664340683584, 2.35382080078125 ),
                zoom: 5,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            geocoder = new google.maps.Geocoder();
            var map = new google.maps.Map(document.getElementById("map-canvas"),
            myOptions);
            google.maps.event.addListener(map, 'click', function(event) {
                placeMarker(event.latLng);
            });

            var marker;
            function placeMarker(location) {
                if(marker){ //on vérifie si le marqueur existe
                    marker.setPosition(location); //on change sa position
                }else{
                    marker = new google.maps.Marker({ //on créé le marqueur
                        position: location, 
                        map: map
                    });
                }
                document.getElementById('applisun_aire_form_latitude').value=location.lat();
                document.getElementById('applisun_aire_form_longitude').value=location.lng();
                document.getElementById("applisun_aire_form_ville").value = "";
                getAddress(location);
            }

      function getAddress(latLng) {
        geocoder.geocode( {'latLng': latLng},
          function(results, status) {
            if(status == google.maps.GeocoderStatus.OK) {
              if(results[0]) {
                 if (results[0].address_components[6] != undefined){ 
                    document.getElementById("applisun_aire_form_ville").value = results[0].address_components[2].short_name+'|'+results[0].address_components[6].short_name;
                 }
              }
              else {
                document.getElementById("applisun_aire_form_ville").value = "No results";
              }
            }
            else {
              document.getElementById("applisun_aire_form_ville").value = status;
            }
          });
        }
      }
      google.maps.event.addDomListener(window, 'load', initialize);


