# IF C_INCLUDE_API #
<script src="//maps.googleapis.com/maps/api/js?key={API_KEY}&amp;libraries=places"></script>
# ENDIF #

<div id="{MAP_ID}" class="googlemap"></div>

<script>
	var locations = Array();
	# START markers #
		locations.push([${escapejs(markers.LABEL)}, ${escapejs(markers.ADDRESS)}, ${escapejs(markers.LATITUDE)}, ${escapejs(markers.LONGITUDE)}, {markers.ZOOM}]);
	# END markers #

	var map = new google.maps.Map(document.getElementById('{MAP_ID}'), {
		zoom: {DEFAULT_ZOOM},
		center: new google.maps.LatLng({DEFAULT_LATITUDE}, {DEFAULT_LONGITUDE}),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});

	var infowindow = new google.maps.InfoWindow();

	var marker, i;
	var markers = new Array();

	for (i = 0; i < locations.length; i++) {
		marker = new google.maps.Marker({
			position: new google.maps.LatLng(locations[i][2], locations[i][3]),
			map: map
		});

		markers.push(marker);

		google.maps.event.addListener(marker, 'click', (function(marker, i) {
			return function() {
				infowindow.setContent(locations[i][0]);
				infowindow.open(map, marker);
			}
		})(marker, i));

		# IF NOT C_MULTIPLE_MARKERS #google.maps.event.trigger(marker, 'click');# ENDIF #
	}

	function AutoCenter() {
		var bounds = new google.maps.LatLngBounds();
		jQuery.each(markers, function (index, marker) {
			bounds.extend(marker.position);
		});
		# IF C_MULTIPLE_MARKERS #map.fitBounds(bounds);# ELSE #map.setCenter(bounds.getCenter());# ENDIF #
	}

	AutoCenter();
</script>
