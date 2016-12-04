
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
			
			jQuery('<input/> ', {type : 'text', id : 'field_popup_' + id, name : 'field_popup_' + id, class : 'contact-form-marker', placeholder : '{@form.marker_text}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');
			
			jQuery('<input/> ', {type : 'text', id : 'field_latitude_' + id, name : 'field_latitude_' + id, class : 'contact-form-marker', pattern : '-?\d{2,16}\.\d+', placeholder : '{@form.latitude}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');
			
			jQuery('<input/> ', {type : 'text', id : 'field_longitude_' + id, name : 'field_longitude_' + id, class : 'contact-form-marker', pattern : '-?\d{2,16}\.\d+', placeholder : '{@form.longitude}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');
			
			jQuery('<a/> ', {href : 'javascript:ContactFormFieldSelectMarker.delete_field('+ this.integer +');'}).html('<i class="fa fa-delete"></i>').appendTo('#' + id);
			
			jQuery('<div/> ', {class : 'spacer'}).appendTo('#' + id);
			
			jQuery('<div/> ', {id : 'gps_' + id}).appendTo('#' + id);
			
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
	<div id="${escape(ID)}_{fieldelements.ID}">
		<input class="contact-form-marker" type="text" name="field_popup_${escape(ID)}_{fieldelements.ID}" id="field_popup_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.POPUP}" placeholder="{@form.marker_text}"/>
		<input class="contact-form-marker" type="text" name="field_latitude_${escape(ID)}_{fieldelements.ID}" id="field_latitude_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.LATITUDE}" pattern="-?\d{1,3}\.\d+" placeholder="{@form.latitude}"/>
		<input class="contact-form-marker" type="text" name="field_longitude_${escape(ID)}_{fieldelements.ID}" id="field_longitude_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.LONGITUDE}" pattern="-?\d{1,3}\.\d+" placeholder="{@form.longitude}"/>
		<a href="javascript:ContactFormFieldSelectMarker.delete_field({fieldelements.ID});" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
		<div class="spacer"></div>
		
		<div class="gmap-form-controller">
			{@form.gps.add.desc}		
			<div id="gps_${escape(ID)}_{fieldelements.ID}"></div>
		</div>
		<script src="https://maps.google.com/maps/api/js?key={GMAP_API_KEY}"></script>
		<script>
			<!--
			var map = new google.maps.Map(document.getElementById('gps_${escape(ID)}_{fieldelements.ID}'), {
				center: {@form.gps.center.map},
				scrollwheel: true,
				zoom: 5
			});
		
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
				document.getElementById("field_latitude_${escape(ID)}_{fieldelements.ID}").value=location.lat(); 
				document.getElementById("field_longitude_${escape(ID)}_{fieldelements.ID}").value=location.lng();
			}
			-->
		</script>
	</div>
# END fieldelements #
</div>
<a href="javascript:ContactFormFieldSelectMarker.add_field();" id="add-${escape(ID)}"><i class="fa fa-plus"></i></a> 
