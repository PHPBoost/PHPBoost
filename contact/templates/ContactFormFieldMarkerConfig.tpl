
<script>
<!--
var ContactFormFieldSelectMarker = function(){
	this.integer = ${escapejs(NBR_FIELDS)};
	this.id_input = ${escapejs(ID)};
	this.max_input = ${escapejs(MAX_INPUT)};
};

ContactFormFieldSelectMarker.prototype = {
	add_field : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;

			jQuery('<div/>', {'id' : id}).appendTo('#input_fields_' + this.id_input);

			jQuery('<input/> ', {type : 'text', id : 'field_popup_title_' + id, name : 'field_popup_title_' + id, class : 'contact-form-marker', placeholder : '{@form.popup.title}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<div/> ', {class : 'map-location_' + id}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<input/> ', {type : 'text', id : 'geocomplete_' + id, placeholder : '{@form.enter.address}'}).appendTo('.map-location_' + id);
			jQuery('#' + id).append(' ');

			jQuery('<div/> ', {class : 'location-datas_' + id}).appendTo('.map-location_' + id);
			jQuery('#' + id).append(' ');

			jQuery('<label/> ', {text : '{@form.gps.data}'}).appendTo('.location-datas_' + id);
			jQuery('#' + id).append(' ');

			jQuery('<input/> ', {type : 'text', id : 'field_latitude_' + id, name : 'field_latitude_' + id, class : 'input-location-latlng', pattern : '-?\d{2,16}\.\d+', placeholder : '{@form.latitude}'}).appendTo('.location-datas_' + id);
			jQuery('#' + id).append(' ');

			jQuery('<input/> ', {type : 'text', id : 'field_longitude_' + id, name : 'field_longitude_' + id, class : 'input-location-latlng', pattern : '-?\d{2,16}\.\d+', placeholder : '{@form.longitude}'}).appendTo('.location-datas_' + id);
			jQuery('#' + id).append(' ');

			jQuery('<label/> ', {text : '{@form.street.address}'}).appendTo('.location-datas_' + id);
			jQuery('#' + id).append(' ');

			jQuery('<input/> ', {type : 'number', id : 'field_street_number_' + id, name : 'field_street_number_' + id, class : 'input-location-number', placeholder : '{@form.street.number}'}).appendTo('.location-datas_' + id);
			jQuery('#' + id).append(' ');

			jQuery('<input/> ', {type : 'text', id : 'field_street_name_' + id, name : 'field_street_name_' + id, class : 'input-location-text', placeholder : '{@form.street.name}'}).appendTo('.location-datas_' + id);
			jQuery('#' + id).append(' ');

			jQuery('<input/> ', {type : 'number', id : 'field_postal_code_' + id, name : 'field_postal_code_' + id, class : 'input-location-number', placeholder : '{@form.postal.code}'}).appendTo('.location-datas_' + id);
			jQuery('#' + id).append(' ');

			jQuery('<input/> ', {type : 'text', id : 'field_locality_' + id, name : 'field_locality_' + id, class : 'input-location-text', placeholder : '{@form.locality}'}).appendTo('.location-datas_' + id);
			jQuery('#' + id).append(' ');

			jQuery('<a/> ', {href : 'javascript:ContactFormFieldSelectMarker.delete_field('+ this.integer +');'}).html('<i class="fa fa-delete"></i>').appendTo('#' + id);

			jQuery('<script/> ', {src : 'spacer'}).appendTo('#' + id);

			this.integer++;
		}
		if (this.integer == this.max_input) {
			jQuery('#add-' + this.id_input).hide();
		}
	},
	delete_field : function (id) {
		var id = this.id_input + '_' + id;
		jQuery('#' + id).remove();
		this.integer--;
		jQuery('#add-' + this.id_input).show();
	}
};

var ContactFormFieldSelectMarker = new ContactFormFieldSelectMarker();
-->
</script>

<div id="input_fields_${escape(ID)}">
	 # START fieldelements #
	 <input type="text" name="field_popup_title_${escape(ID)}_{fieldelements.ID}" id="field_popup_title_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.POPUP_TITLE}" placeholder="{@form.popup.title}"/>
	 <div class="map-location_${escape(ID)}_{fieldelements.ID}">
		<input id="geocomplete_${escape(ID)}_{fieldelements.ID}" placeholder="{@form.enter.address}" type="text"/>
	 	<div class="location-datas_${escape(ID)}_{fieldelements.ID}">
			<label>{@form.gps.data} : </label>
			<input data-location="lat" value="{fieldelements.MAP_LATITUDE}" name="field_latitude_${escape(ID)}_{fieldelements.ID}" id="field_latitude_${escape(ID)}_{fieldelements.ID}" placeholder="{@form.latitude}" type="text" class="input-location-latlng"/>
			<input data-location="lng" value="{fieldelements.MAP_LONGITUDE}" name="field_longitude_${escape(ID)}_{fieldelements.ID}" id="field_longitude_${escape(ID)}_{fieldelements.ID}" placeholder="{@form.longitude}" type="text" class="input-location-latlng"/>
	 		<label>{@form.street.address} : </label>
			<input data-location="street_number" value="{fieldelements.MAP_STREET_NUMBER}" name="field_street_number_${escape(ID)}_{fieldelements.ID}" id="field_street_number_${escape(ID)}_{fieldelements.ID}" placeholder="{@form.street.number}" type="number" class="input-location-number"/>
	 	    <input data-location="route" value="{fieldelements.MAP_STREET_NAME}" name="field_street_name_${escape(ID)}_{fieldelements.ID}" id="field_street_name_${escape(ID)}_{fieldelements.ID}" placeholder="{@form.street.name}" type="text" class="input-location-text"/>
	 	    <input data-location="postal_code" value="{fieldelements.MAP_POSTAL_CODE}" name="field_postal_code_${escape(ID)}_{fieldelements.ID}" id="field_location_postal_code_${escape(ID)}_{fieldelements.ID}" placeholder="{@form.postal.code}" type="number" class="input-location-number"/>
			<input data-location="locality" value="{fieldelements.MAP_LOCALITY}" name="field_locality_${escape(ID)}_{fieldelements.ID}" id="field_location_city_${escape(ID)}_{fieldelements.ID}" placeholder="{@form.locality}" type="text" class="input-location-text"/>
	 	</div>
	</div>

    <script>
      $(function(){
        $("#geocomplete_${escape(ID)}_{fieldelements.ID}").geocomplete({
          details: ".map-location_${escape(ID)}_{fieldelements.ID}",
	      detailsAttribute: "data-location",
          types: ["geocode", "establishment"],
        });
      });
    </script>

	<a href="javascript:AgendaFormFieldLocation.delete_field({fieldelements.ID});" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>

	# END fieldelements #
</div>
<a href="javascript:ContactFormFieldSelectMarker.add_field();" id="add-${escape(ID)}" class="contact-form-marker-more" title="${LangLoader::get_message('add', 'common')}" ><i class="fa fa-plus"></i></a>

<script src="http://maps.googleapis.com/maps/api/js?key={GMAP_API_KEY}&amp;libraries=places"></script>
<script src="{PATH_TO_ROOT}/contact/templates/js/jquery.geocomplete.js"></script>
