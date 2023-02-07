<div class="field-${escape(HTML_ID)} grouped-inputs map-input-container">
	<input type="text" name="${escape(NAME)}" id="${escape(HTML_ID)}" value="${escape(ADDRESS)}" placeholder="{@form.marker.address}" class="marker-address-input grouped-element# IF C_CLASS # ${escape(CLASS)}# ENDIF #" # IF C_DISABLED # disabled="disabled" # ENDIF # />
	<input type="hidden" id="latitude-${escape(HTML_ID)}" name="latitude-${escape(HTML_ID)}" value="${escape(LATITUDE)}" />
	<input type="hidden" id="longitude-${escape(HTML_ID)}" name="longitude-${escape(HTML_ID)}" value="${escape(LONGITUDE)}" />
	<input type="hidden" id="zoom-${escape(HTML_ID)}" name="zoom-${escape(HTML_ID)}" value="${escape(ZOOM)}" />
	<input type="text" name="name-${escape(NAME)}" id="name-${escape(HTML_ID)}" value="${escape(MARKER_NAME)}" placeholder="{@form.marker.name}" class="marker-desc-input grouped-element# IF C_READONLY # low-opacity# ENDIF ## IF C_CLASS # ${escape(CLASS)}# ENDIF #" # IF C_DISABLED # disabled="disabled" # ENDIF # />
</div>
<div class="map-canvas" id="map-${escape(HTML_ID)}"></div>

<script>
	jQuery("#${escape(HTML_ID)}").on('blur',function(){
		var marker = jQuery("#${escape(HTML_ID)}").geocomplete("marker");
		if (jQuery("#${escape(HTML_ID)}").val())
			marker.setVisible(true);
		else
			marker.setVisible(false);
	});

	jQuery(function(){
		# IF C_ADDRESS #
			var address = "{ADDRESS}";
		# ELSE #
			var address = [{DEFAULT_LATITUDE}, {DEFAULT_LONGITUDE}];
		# ENDIF #

		jQuery("#${escape(HTML_ID)}").geocomplete({
			map: "#map-${escape(HTML_ID)}",
			location: # IF C_COORDONATES #[{LATITUDE}, {LONGITUDE}]# ELSE #address# ENDIF #,
			types: ["geocode", "establishment"],
			markerOptions: {
				draggable: true# IF NOT C_ADDRESS #,
				visible: false# ENDIF #
			},
			mapOptions: {
				scrollwheel: true# IF C_ZOOM #,
				zoom: {ZOOM}# ENDIF #
			}
		});

		jQuery("#${escape(HTML_ID)}").bind("geocode:dragged", function(event, latLng){
			jQuery("input[name=latitude-${escape(HTML_ID)}]").val(latLng.lat());
			jQuery("input[name=longitude-${escape(HTML_ID)}]").val(latLng.lng());
		});

		jQuery("#${escape(HTML_ID)}").bind("geocode:idle", function(event, latLng){
			jQuery("input[name=latitude-${escape(HTML_ID)}]").val(latLng.lat());
			jQuery("input[name=longitude-${escape(HTML_ID)}]").val(latLng.lng());
		});

		jQuery("#${escape(HTML_ID)}").bind("geocode:zoom", function(event, value){
			jQuery("input[name=zoom-${escape(HTML_ID)}]").val(value);
		});
	});
</script>

# IF C_INCLUDE_API #
	<script src="//maps.googleapis.com/maps/api/js?key={API_KEY}&amp;libraries=places&callback=Function.prototype"></script>
	<script src="{PATH_TO_ROOT}/GoogleMaps/templates/js/jquery.geocomplete# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
# ENDIF #
