# IF C_MARKER #
<!-- JavaScript -->
<script charset="utf-8">var PATH_TO_ROOT = "{PATH_TO_ROOT}";</script>

<script src="{PATH_TO_ROOT}/contact/templates/js/leaflet.js"></script>

<!-- leaflet spin-->
<script src="{PATH_TO_ROOT}/contact/templates/js/leaflet.spin.min.js"></script>
<script src="{PATH_TO_ROOT}/contact/templates/js/spin.min.js"></script>

<!-- Map Layers -->
<script src="https://maps.google.com/maps/api/js?key={GMAP_API_KEY}"></script>
<script src="{PATH_TO_ROOT}/contact/templates/js/Leaflet.GoogleMutant.js"></script>

	# IF C_ONE_MARKER #
	<script>
		var lat = # START places #{places.MAP_LATITUDE}# END places #;
		var lng = # START places #{places.MAP_LONGITUDE}# END places #;
		var popup_title = # START places #'<strong>{places.MAP_POPUP_TITLE}</strong><br />{places.MAP_STREET_NUMBER} {places.MAP_STREET_NAME}<br />{places.MAP_POSTAL_CODE} {places.MAP_LOCALITY}'# END places #;

		var map = L.map('map').setView([lat, lng], 14);
		var marker = L.marker([lat, lng]).addTo(map);
		marker.bindPopup(popup_title);

		var osm = new L.TileLayer('http://\{s\}.tile.openstreetmap.org/\{z\}/\{x\}/\{y\}.png');
		var ocm = new L.TileLayer('http://\{s\}.tile.thunderforest.com/cycle/\{z\}/\{x\}/\{y\}.png');
		var olm = new L.TileLayer('http://\{s\}.tile.thunderforest.com/landscape/\{z\}/\{x\}/\{y\}.png');
		var otm = new L.TileLayer('http://\{s\}.tile.thunderforest.com/transport/\{z\}/\{x\}/\{y\}.png');
		var oom = new L.TileLayer('http://\{s\}.tile.thunderforest.com/outdoors/\{z\}/\{x\}/\{y\}.png');
		var gglt = new L.gridLayer.googleMutant({type: 'terrain'}) ;
		var ggls = new L.gridLayer.googleMutant({type: 'satellite'}) ;
		var gglr = new L.gridLayer.googleMutant({type: 'roadmap'}) ;
		var gglh = new L.gridLayer.googleMutant({type: 'hybrid'}) ;

		var multiplelayers = {
			'OpenStreetMap':osm,
			'OpenCycleMap':ocm,
			'OpenLandingMap':olm,
			'OpenTransportMap':otm,
			'OpenOutdoorsMap':oom,
			'Google terrain':gglt,
			'Google satellite':ggls,
			'Google roadmap':gglr,
			'Google hybrid':gglh,
			};

		map.addLayer(osm);
		L.control.layers(multiplelayers).addTo(map);
	</script>

	# ELSE #
	<script>
	<!--
	jQuery(document).ready(function() {
		L.Icon.Default.imagePath = PATH_TO_ROOT + '/contact/templates/images/';

		var map = new L.Map('map');
		var osm = new L.TileLayer('http://\{s\}.tile.openstreetmap.org/\{z\}/\{x\}/\{y\}.png');
		var ocm = new L.TileLayer('http://\{s\}.tile.thunderforest.com/cycle/\{z\}/\{x\}/\{y\}.png');
		var olm = new L.TileLayer('http://\{s\}.tile.thunderforest.com/landscape/\{z\}/\{x\}/\{y\}.png');
		var otm = new L.TileLayer('http://\{s\}.tile.thunderforest.com/transport/\{z\}/\{x\}/\{y\}.png');
		var oom = new L.TileLayer('http://\{s\}.tile.thunderforest.com/outdoors/\{z\}/\{x\}/\{y\}.png');
		var gglt = new L.gridLayer.googleMutant({type: 'terrain'}) ;
		var ggls = new L.gridLayer.googleMutant({type: 'satellite'}) ;
		var gglr = new L.gridLayer.googleMutant({type: 'roadmap'}) ;
		var gglh = new L.gridLayer.googleMutant({type: 'hybrid'}) ;

			// liste des markers
			var markers = new L.FeatureGroup();
			var marker = new Array();
			var markersData = [
				# START places #
				[
					'<strong>{places.MAP_POPUP_TITLE}</strong><br />{places.MAP_STREET_NUMBER} {places.MAP_STREET_NAME}<br />{places.MAP_POSTAL_CODE} {places.MAP_LOCALITY}',
					{places.MAP_LATITUDE},
					{places.MAP_LONGITUDE},
				],
				# END places #
			];

			if(markersData.length > 0) {
				for (var i = 0; i < markersData.length; i++) {
					var lat = markersData[i][1];
					var lng = markersData[i][2];
					var popup_title = markersData[i][0];

					marker = L.marker([lat,lng])
					.bindPopup(popup_title);
					markers.addLayer(marker);
				}

				var bounds = markers.getBounds();
				map.fitBounds(bounds);
				map.addLayer(markers)
			}
		//add on the map
		map.addLayer(osm);
		map.addControl(new L.Control.Layers( {
			'OpenStreetMap':osm,
			'OpenCycleMap':ocm,
			'OpenLandingMap':olm,
			'OpenTransportMap':otm,
			'OpenOutdoorsMap':oom,
			'Google terrain':gglt,
			'Google satellite':ggls,
			'Google roadmap':gglr,
			'Google hybrid':gglh,
			},
		{})
		);
	});
	-->
	</script>
	# ENDIF #
	<div id="map-container">
		<div id="map"></div>
	</div>
# ENDIF #
