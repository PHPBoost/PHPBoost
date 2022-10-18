<div class="field-${escape(HTML_ID)} map-input-container">
	<input type="text" name="${escape(NAME)}" id="${escape(HTML_ID)}" value="${escape(ADDRESS)}" placeholder="{@form.marker.address}" class="map-address-input# IF C_READONLY # low-opacity# ENDIF ## IF C_CLASS # ${escape(CLASS)}# ENDIF #" # IF C_DISABLED # disabled="disabled" # ENDIF # />
</div>

<script>
<!--
jQuery(function(){
	jQuery("#${escape(HTML_ID)}").geocomplete({
		# IF C_ADDRESS #location: "{ADDRESS}"# ENDIF #
	});
});
-->
</script>

# IF C_INCLUDE_API #
	<script src="//maps.googleapis.com/maps/api/js?key={API_KEY}&amp;libraries=places"></script>
	<script src="{PATH_TO_ROOT}/GoogleMaps/templates/js/jquery.geocomplete# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
# ENDIF #
