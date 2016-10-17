# IF C_MARKER #
<!-- JavaScript -->
<script charset="utf-8">var PATH_TO_ROOT = "{PATH_TO_ROOT}";</script>

<script src="{PATH_TO_ROOT}/contact/templates/js/leaflet.js"></script>

<!-- leaflet spin-->
<script src="{PATH_TO_ROOT}/contact/templates/js/leaflet.spin.min.js"></script>
<script src="{PATH_TO_ROOT}/contact/templates/js/spin.min.js"></script>

<!-- Map Layers -->
<script src="https://maps.google.com/maps/api/js?key=AIzaSyDa_Ph-ORGTmXcYdNjw7MS5svx_6W7t_5A"></script>
<script src="{PATH_TO_ROOT}/contact/templates/js/Leaflet.GoogleMutant.js"></script>
 
	# IF C_ONE_MARKER #
	<script>
		var lat = # START places #{places.MAP_LATITUDE}# END places #;
		var lng = # START places #{places.MAP_LONGITUDE}# END places #;
		var popup = # START places #'{places.MAP_POPUP}'# END places #;
		
		var map = L.map('map').setView([lat, lng], 14);
		var marker = L.marker([lat, lng]).addTo(map);
		marker.bindPopup(popup);
		
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
		
		var error_spin={type:"Feature",properties:{popupContent:"This is the Auraria West Campus",style:{weight:2,color:"#999",opacity:1,fillColor:"#B0DE5C",fillOpacity:.8}},geometry:{type:"MultiPolygon",coordinates:[[[[-105.00432014465332,39.74732195489861],[-105.00715255737305,39.7462000683517],[-105.00921249389647,39.74468219277038],[-105.01067161560059,39.74362625960105],[-105.01195907592773,39.74290029616054],[-105.00989913940431,39.74078835902781],[-105.00758171081543,39.74059036160317],[-105.00346183776855,39.74059036160317],[-105.00097274780272,39.74059036160317],[-105.00062942504881,39.74072235994946],[-105.00020027160645,39.74191033368865],[-105.0007152557373,39.74276830198601],[-105.00097274780272,39.74369225589818],[-105.00097274780272,39.74461619742136],[-105.00123023986816,39.74534214278395],[-105.00183105468751,39.74613407445653],[-105.00432014465332,39.74732195489861]],[[-105.00361204147337,39.74354376414072],[-105.00301122665405,39.74278480127163],[-105.00221729278564,39.74316428375108],[-105.00283956527711,39.74390674342741],[-105.00361204147337,39.74354376414072]]],[[[-105.00942707061768,39.73989736613708],[-105.00942707061768,39.73910536278566],[-105.00685214996338,39.73923736397631],[-105.00384807586671,39.73910536278566],[-105.00174522399902,39.73903936209552],[-105.00041484832764,39.73910536278566],[-105.00041484832764,39.73979836621592],[-105.00535011291504,39.73986436617916],[-105.00942707061768,39.73989736613708]]]]}};
			
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
		

		
		var layer = L.geoJson().addTo(map);
			map.spin(true);
			// Simulate AJAX
			setTimeout(function () {
				layer.addData(error_spin);
				map.spin(false);
		}, 2000);
			
			// liste des markers
			var markers = new L.FeatureGroup();
			var marker = new Array();
			var markersData = [
				# START places #
				[
					'{places.MAP_POPUP}',
					{places.MAP_LATITUDE},
					{places.MAP_LONGITUDE},
				],
				# END places #
			];
			
			if(markersData.length > 0) {
				for (var i = 0; i < markersData.length; i++) {
					var lat = markersData[i][1];
					var lng = markersData[i][2];
					var popup = markersData[i][0];
					
					marker = L.marker([lat,lng])
					.bindPopup(popup);
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
