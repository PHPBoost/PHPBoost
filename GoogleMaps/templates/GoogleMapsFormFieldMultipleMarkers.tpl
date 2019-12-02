<script>
<!--
var GoogleMapsFormFieldMultipleMarkers = function(){
	this.integer = {NBR_FIELDS};
	this.id_input = ${escapejs(HTML_ID)};
	this.max_input = {MAX_INPUT};
};

GoogleMapsFormFieldMultipleMarkers.prototype = {
	add : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '-' + this.integer;

			jQuery('<div/>', {id : 'marker-' + id, class: 'marker-container'}).appendTo('#input-fields-' + this.id_input);

			jQuery('<div/>', {id : 'field-' + id, class: 'grouped-inputs map-input-container'}).appendTo('#marker-' + id);

			jQuery('<input/> ', {type : 'text', id : id, name : id, required : "required", placeholder : '{@form.marker.address}', class: 'marker-address-input grouped-element# IF C_CLASS # ${escape(CLASS)}# ENDIF #'}).appendTo('#field-' + id);
			jQuery('#field-' + id).append(' ');

			jQuery('<input/> ', {type : 'text', id : 'name-' + id, name : 'name-' + id, placeholder : '{@form.marker.name}', class: 'marker-desc-input grouped-element# IF C_CLASS # ${escape(CLASS)}# ENDIF #'}).appendTo('#field-' + id);
			jQuery('#field-' + id).append(' ');

			jQuery('<input/> ', {type : 'hidden', id : 'latitude-' + id, name : 'latitude-' + id}).appendTo('#field-' + id);
			jQuery('<input/> ', {type : 'hidden', id : 'longitude-' + id, name : 'longitude-' + id}).appendTo('#field-' + id);
			jQuery('<input/> ', {type : 'hidden', id : 'zoom-' + id, name : 'zoom-' + id}).appendTo('#field-' + id);

			jQuery('<a/> ', {href : 'javascript:GoogleMapsFormFieldMultipleMarkers.delete('+ this.integer +');', class : 'grouped-element', 'aria-label' : '{@form.del.marker}'}).html('<i class="fa fa-trash-alt" aria-hidden="true"></i>').appendTo('#field-' + id);

			jQuery('<div/>', {id : 'map-' + id, class: 'map-canvas'}).appendTo('#marker-' + id);

			jQuery('<script/>').html('jQuery("#' + id + '").on(\'blur\',function(){ var marker = jQuery("#' + id + '").geocomplete("marker"); if (jQuery("#' + id + '").val()) marker.setVisible(true); else marker.setVisible(false); }); jQuery(function(){ jQuery("#' + id + '").geocomplete({ map: "#map-' + id + '", location: [{DEFAULT_LATITUDE}, {DEFAULT_LONGITUDE}], types: ["geocode", "establishment"], markerOptions: { draggable: true, visible: false }, mapOptions: { scrollwheel: true } }); jQuery("#' + id + '").bind("geocode:dragged", function(event, latLng){ jQuery("input[name=latitude-' + id + ']").val(latLng.lat()); jQuery("input[name=longitude-' + id + ']").val(latLng.lng()); }); jQuery("#' + id + '").bind("geocode:idle", function(event, latLng){ jQuery("input[name=latitude-' + id + ']").val(latLng.lat()); jQuery("input[name=longitude-' + id + ']").val(latLng.lng()); }); jQuery("#' + id + '").bind("geocode:zoom", function(event, value){ jQuery("input[name=zoom-' + id + ']").val(value); }); });').appendTo('#marker-' + id);

			this.integer++;
		}
		if (this.integer == this.max_input) {
			jQuery('#add-' + this.id_input).hide();
		}
	},
	delete : function (id) {
		var id = 'marker-' + this.id_input + '-' + id;
		jQuery('#' + id).remove();
		this.integer--;
		jQuery('#add-' + this.id_input).show();
	},
};

var GoogleMapsFormFieldMultipleMarkers = new GoogleMapsFormFieldMultipleMarkers();
-->
</script>

<div id="input-fields-${escape(HTML_ID)}" class="multiple-markers-container">
# START fieldelements #
	<div id="marker-${escape(HTML_ID)}-{fieldelements.ID}" class="marker-container">
		<div class="grouped-inputs map-input-container" id="field-${escape(HTML_ID)}-{fieldelements.ID} map-input-container">
			<input type="text" name="${escape(HTML_ID)}-{fieldelements.ID}" id="${escape(HTML_ID)}-{fieldelements.ID}" value="{fieldelements.ADDRESS}" placeholder="{@form.marker.address}" class="marker-address-input grouped-element# IF C_READONLY # low-opacity# ENDIF ## IF C_CLASS # ${escape(CLASS)}# ENDIF #" # IF C_DISABLED # disabled="disabled" # ENDIF # />
			<input type="text" name="name-${escape(HTML_ID)}-{fieldelements.ID}" id="name-${escape(HTML_ID)}-{fieldelements.ID}" value="{fieldelements.MARKER_NAME}" placeholder="{@form.marker.name}" class="marker-desc-input grouped-element# IF C_READONLY # low-opacity# ENDIF ## IF C_CLASS # ${escape(CLASS)}# ENDIF #" # IF C_DISABLED # disabled="disabled" # ENDIF # />
			<input type="hidden" id="latitude-${escape(HTML_ID)}-{fieldelements.ID}" name="latitude-${escape(HTML_ID)}-{fieldelements.ID}" value="{fieldelements.LATITUDE}" />
			<input type="hidden" id="longitude-${escape(HTML_ID)}-{fieldelements.ID}" name="longitude-${escape(HTML_ID)}-{fieldelements.ID}" value="{fieldelements.LONGITUDE}" />
			<input type="hidden" id="zoom-${escape(HTML_ID)}-{fieldelements.ID}" name="zoom-${escape(HTML_ID)}-{fieldelements.ID}" value="{fieldelements.ZOOM}" />
			<a href="javascript:GoogleMapsFormFieldMultipleMarkers.delete({fieldelements.ID});" class="grouped-element" aria-label="{@form.del.marker}" data-confirmation="delete-element"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
		</div>
		<div class="map-canvas" id="map-${escape(HTML_ID)}-{fieldelements.ID}"></div>
		<script>
		<!--
		jQuery("#${escape(HTML_ID)}-{fieldelements.ID}").on('blur',function(){
			var marker = jQuery("#${escape(HTML_ID)}-{fieldelements.ID}").geocomplete("marker");
			if (jQuery("#${escape(HTML_ID)}-{fieldelements.ID}").val())
				marker.setVisible(true);
			else
				marker.setVisible(false);
		});

		jQuery(function(){
			# IF fieldelements.C_ADDRESS #
			var address = "{fieldelements.ADDRESS}";
			# ELSE #
			var address = [{DEFAULT_LATITUDE}, {DEFAULT_LONGITUDE}];
			# ENDIF #

			jQuery("#${escape(HTML_ID)}-{fieldelements.ID}").geocomplete({
				map: "#map-${escape(HTML_ID)}-{fieldelements.ID}",
				location: # IF fieldelements.C_COORDONATES #[{fieldelements.LATITUDE}, {fieldelements.LONGITUDE}]# ELSE #address# ENDIF #,
				types: ["geocode", "establishment"],
				markerOptions: {
					draggable: true# IF NOT fieldelements.C_ADDRESS #,
					visible: false# ENDIF #
				},
				mapOptions: {
					scrollwheel: true# IF fieldelements.C_ZOOM #,
					zoom: {fieldelements.ZOOM}# ENDIF #
				}
			});

			jQuery("#${escape(HTML_ID)}-{fieldelements.ID}").bind("geocode:dragged", function(event, latLng){
				jQuery("input[name=latitude-${escape(HTML_ID)}-{fieldelements.ID}]").val(latLng.lat());
				jQuery("input[name=longitude-${escape(HTML_ID)}-{fieldelements.ID}]").val(latLng.lng());
			});

			jQuery("#${escape(HTML_ID)}-{fieldelements.ID}").bind("geocode:idle", function(event, latLng){
				jQuery("input[name=latitude-${escape(HTML_ID)}-{fieldelements.ID}]").val(latLng.lat());
				jQuery("input[name=longitude-${escape(HTML_ID)}-{fieldelements.ID}]").val(latLng.lng());
			});

			jQuery("#${escape(HTML_ID)}-{fieldelements.ID}").bind("geocode:zoom", function(event, value){
				jQuery("input[name=zoom-${escape(HTML_ID)}-{fieldelements.ID}]").val(value);
			});
		});
		-->
		</script>
	</div>
# END fieldelements #
</div>
<a href="javascript:GoogleMapsFormFieldMultipleMarkers.add();" id="add-${escape(HTML_ID)}" class="form-field-checkbox-more" aria-label="{@form.add.marker}"><i class="fa fa-plus" aria-hidden="true"></i></a>

# IF C_INCLUDE_API #
<script src="//maps.googleapis.com/maps/api/js?key={API_KEY}&amp;libraries=places"></script>
<script src="{PATH_TO_ROOT}/GoogleMaps/templates/js/jquery.geocomplete.js"></script>
# ENDIF #
