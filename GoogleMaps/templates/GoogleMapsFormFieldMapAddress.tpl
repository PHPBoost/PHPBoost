<div class="field_${escape(HTML_ID)}">
	<input type="text" name="${escape(NAME)}" id="${escape(HTML_ID)}" value="${escape(ADDRESS)}" placeholder="{@form.marker.address}" class="# IF C_READONLY #low-opacity # ENDIF ## IF C_CLASS #${escape(CLASS)}# ENDIF #" # IF C_DISABLED # disabled="disabled" # ENDIF # />
	<input type="hidden" id="latitude_${escape(HTML_ID)}" name="latitude_${escape(HTML_ID)}" value="${escape(LATITUDE)}" />
	<input type="hidden" id="longitude_${escape(HTML_ID)}" name="longitude_${escape(HTML_ID)}" value="${escape(LONGITUDE)}" />
	<input type="hidden" id="zoom_${escape(HTML_ID)}" name="zoom_${escape(HTML_ID)}" value="${escape(ZOOM)}" />
</div>
<div class="map-canvas" id="map_${escape(HTML_ID)}"></div>

<script>
<!--
jQuery(function(){
	# IF C_ADDRESS #
	var address = "{ADDRESS}";
	# ELSE #
	var address = [{DEFAULT_LATITUDE}, {DEFAULT_LONGITUDE}];
	# ENDIF #
	
	jQuery("#${escape(HTML_ID)}").geocomplete({
		map: "#map_${escape(HTML_ID)}",
		location: # IF C_COORDONATES #[{LATITUDE}, {LONGITUDE}]# ELSE #address# ENDIF #,
		types: ["geocode", "establishment"],
		markerOptions: {
			draggable: true
		},
		mapOptions: {
			scrollwheel: true# IF C_ZOOM #,
			zoom: {ZOOM}
			# ENDIF #
		}
	});
	
	jQuery("#${escape(HTML_ID)}").bind("geocode:dragged", function(event, latLng){
		jQuery("input[name=latitude_${escape(HTML_ID)}]").val(latLng.lat());
		jQuery("input[name=longitude_${escape(HTML_ID)}]").val(latLng.lng());
	});
	
	jQuery("#${escape(HTML_ID)}").bind("geocode:idle", function(event, latLng){
		jQuery("input[name=latitude_${escape(HTML_ID)}]").val(latLng.lat());
		jQuery("input[name=longitude_${escape(HTML_ID)}]").val(latLng.lng());
	});
	
	jQuery("#${escape(HTML_ID)}").bind("geocode:zoom", function(event, value){
		jQuery("input[name=zoom_${escape(HTML_ID)}]").val(value);
	});
});
-->
</script>

# IF C_INCLUDE_API #
<script src="http://maps.googleapis.com/maps/api/js?key={API_KEY}&amp;libraries=places"></script>
<script src="{PATH_TO_ROOT}/GoogleMaps/templates/js/jquery.geocomplete.js"></script>
# ENDIF #
