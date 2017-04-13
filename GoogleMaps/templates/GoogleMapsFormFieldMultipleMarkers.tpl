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
			var id = this.id_input + '_' + this.integer;
			
			jQuery('<div/>', {id : 'marker_' + id}).appendTo('#input_fields_' + this.id_input);
			
			jQuery('<div/>', {id : 'field_' + id}).appendTo('#marker_' + id);
			
			jQuery('<input/> ', {type : 'text', id : id, name : id, required : "required", placeholder : '{@form.marker.address}', class: '# IF C_CLASS #${escape(CLASS)}# ENDIF #'}).appendTo('#field_' + id);
			jQuery('#field_' + id).append(' ');
			
			jQuery('<input/> ', {type : 'text', id : 'name_' + id, name : 'name_' + id, placeholder : '{@form.marker.name}', class: '# IF C_CLASS #${escape(CLASS)}# ENDIF #'}).appendTo('#field_' + id);
			jQuery('#field_' + id).append(' ');
			
			jQuery('<input/> ', {type : 'hidden', id : 'latitude_' + id, name : 'latitude_' + id}).appendTo('#field_' + id);
			jQuery('<input/> ', {type : 'hidden', id : 'longitude_' + id, name : 'longitude_' + id}).appendTo('#field_' + id);
			jQuery('<input/> ', {type : 'hidden', id : 'zoom_' + id, name : 'zoom_' + id}).appendTo('#field_' + id);
			
			jQuery('<a/> ', {href : 'javascript:GoogleMapsFormFieldMultipleMarkers.delete('+ this.integer +');', title : "${LangLoader::get_message('delete', 'common')}"}).html('<i class="fa fa-delete"></i>').appendTo('#field_' + id);
			
			jQuery('<div/>', {id : 'map_' + id, class: 'map-canvas'}).appendTo('#marker_' + id);
			
			jQuery('<script/>').html('jQuery(function(){ jQuery("#' + id + '").geocomplete({ map: "#map_' + id + '", location: [{DEFAULT_LATITUDE}, {DEFAULT_LONGITUDE}], types: ["geocode", "establishment"], markerOptions: { draggable: true }, mapOptions: { scrollwheel: true } }); jQuery("#' + id + '").bind("geocode:dragged", function(event, latLng){ jQuery("input[name=latitude_' + id + ']").val(latLng.lat()); jQuery("input[name=longitude_' + id + ']").val(latLng.lng()); }); jQuery("#' + id + '").bind("geocode:idle", function(event, latLng){ jQuery("input[name=latitude_' + id + ']").val(latLng.lat()); jQuery("input[name=longitude_' + id + ']").val(latLng.lng()); }); jQuery("#' + id + '").bind("geocode:zoom", function(event, value){ jQuery("input[name=zoom_' + id + ']").val(value); }); });').appendTo('#marker_' + id);
			
			this.integer++;
		}
		if (this.integer == this.max_input) {
			jQuery('#add-' + this.id_input).hide();
		}
	},
	delete : function (id) {
		var id = 'marker_' + this.id_input + '_' + id;
		jQuery('#' + id).remove();
		this.integer--;
		jQuery('#add-' + this.id_input).show();
	},
};

var GoogleMapsFormFieldMultipleMarkers = new GoogleMapsFormFieldMultipleMarkers();
-->
</script>

<div id="input_fields_${escape(HTML_ID)}">
# START fieldelements #
	<div id="marker_${escape(HTML_ID)}_{fieldelements.ID}">
		<div id="field_${escape(HTML_ID)}_{fieldelements.ID}">
			<input type="text" name="${escape(HTML_ID)}_{fieldelements.ID}" id="${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.ADDRESS}" placeholder="{@form.marker.address}" class="# IF C_READONLY #low-opacity # ENDIF ## IF C_CLASS #${escape(CLASS)}# ENDIF #" # IF C_DISABLED # disabled="disabled" # ENDIF # />
			<input type="text" name="name_${escape(HTML_ID)}_{fieldelements.ID}" id="name_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.MARKER_NAME}" placeholder="{@form.marker.name}" class="# IF C_READONLY #low-opacity # ENDIF ## IF C_CLASS #${escape(CLASS)}# ENDIF #" # IF C_DISABLED # disabled="disabled" # ENDIF # />
			<input type="hidden" id="latitude_${escape(HTML_ID)}_{fieldelements.ID}" name="latitude_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.LATITUDE}" />
			<input type="hidden" id="longitude_${escape(HTML_ID)}_{fieldelements.ID}" name="longitude_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.LONGITUDE}" />
			<input type="hidden" id="zoom_${escape(HTML_ID)}_{fieldelements.ID}" name="zoom_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.ZOOM}" />
			<a href="javascript:GoogleMapsFormFieldMultipleMarkers.delete({fieldelements.ID});" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
		</div>
		<div class="map-canvas" id="map_${escape(HTML_ID)}_{fieldelements.ID}"></div>
		<script>
		<!--
		jQuery(function(){
			# IF fieldelements.C_ADDRESS #
			var address = "{fieldelements.ADDRESS}";
			# ELSE #
			var address = [{DEFAULT_LATITUDE}, {DEFAULT_LONGITUDE}];
			# ENDIF #
			
			jQuery("#${escape(HTML_ID)}_{fieldelements.ID}").geocomplete({
				map: "#map_${escape(HTML_ID)}_{fieldelements.ID}",
				location: # IF fieldelements.C_COORDONATES #[{fieldelements.LATITUDE}, {fieldelements.LONGITUDE}]# ELSE #address# ENDIF #,
				types: ["geocode", "establishment"],
				markerOptions: {
					draggable: true
				},
				mapOptions: {
					scrollwheel: true# IF fieldelements.C_ZOOM #,
					zoom: {fieldelements.ZOOM}
					# ENDIF #
				}
			});
			
			jQuery("#${escape(HTML_ID)}_{fieldelements.ID}").bind("geocode:dragged", function(event, latLng){
				jQuery("input[name=latitude_${escape(HTML_ID)}_{fieldelements.ID}]").val(latLng.lat());
				jQuery("input[name=longitude_${escape(HTML_ID)}_{fieldelements.ID}]").val(latLng.lng());
			});
			
			jQuery("#${escape(HTML_ID)}_{fieldelements.ID}").bind("geocode:idle", function(event, latLng){
				jQuery("input[name=latitude_${escape(HTML_ID)}_{fieldelements.ID}]").val(latLng.lat());
				jQuery("input[name=longitude_${escape(HTML_ID)}_{fieldelements.ID}]").val(latLng.lng());
			});
			
			jQuery("#${escape(HTML_ID)}_{fieldelements.ID}").bind("geocode:zoom", function(event, value){
				jQuery("input[name=zoom_${escape(HTML_ID)}_{fieldelements.ID}]").val(value);
			});
		});
		-->
		</script>
	</div>
# END fieldelements #
</div>
<a href="javascript:GoogleMapsFormFieldMultipleMarkers.add();" id="add-${escape(HTML_ID)}" class="form-field-checkbox-more" title="${LangLoader::get_message('add', 'common')}"><i class="fa fa-plus"></i></a>

# IF C_INCLUDE_API #
<script src="http://maps.googleapis.com/maps/api/js?key={API_KEY}&amp;libraries=places"></script>
<script src="{PATH_TO_ROOT}/GoogleMaps/templates/js/jquery.geocomplete.js"></script>
# ENDIF #
