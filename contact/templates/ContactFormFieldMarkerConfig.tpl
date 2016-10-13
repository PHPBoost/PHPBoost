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
			
			jQuery('<input/> ', {type : 'text', id : 'field_latitude_' + id, class : 'contact-form-marker', name : 'field_latitude_' + id, placeholder : '{@form.latitude}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');
			
			jQuery('<input/> ', {type : 'text', id : 'field_longitude_' + id, class : 'contact-form-marker', name : 'field_longitude_' + id, placeholder : '{@form.longitude}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');
			
			jQuery('<input/> ', {type : 'text', id : 'field_popup_' + id, class : 'contact-form-marker', name : 'field_popup_' + id, placeholder : '{@form.popup}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');
			
			jQuery('<a/> ', {href : 'javascript:ContactFormFieldSelectMarker.delete_field('+ this.integer +');'}).html('<i class="fa fa-delete"></i>').appendTo('#' + id);
			
			jQuery('<div/> ', {class : 'spacer'}).appendTo('#' + id);
			
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
		<input class="contact-form-marker" type="text" name="field_latitude_${escape(ID)}_{fieldelements.ID}" id="field_latitude_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.LATITUDE}" placeholder="{@form.latitude}"/>
		<input class="contact-form-marker" type="text" name="field_longitude_${escape(ID)}_{fieldelements.ID}" id="field_longitude_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.LONGITUDE}" placeholder="{@form.longitude}"/>
		<input class="contact-form-marker" type="text" name="field_popup_${escape(ID)}_{fieldelements.ID}" id="field_popup_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.POPUP}" placeholder="{@form.popup}"/>
		<a href="javascript:ContactFormFieldSelectMarker.delete_field({fieldelements.ID});" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
		<div class="spacer"></div>
	</div>
# END fieldelements #
</div>
<a href="javascript:ContactFormFieldSelectMarker.add_field();" id="add-${escape(ID)}"><i class="fa fa-plus"></i></a> 
