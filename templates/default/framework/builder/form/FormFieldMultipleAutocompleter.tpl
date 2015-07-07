<script>
<!--
var FormFieldMultipleAutocompleter = function(){
	this.integer = {NBR_FIELDS};
	this.id_input = ${escapejs(HTML_ID)};
	this.max_input = {MAX_INPUT};
};

FormFieldMultipleAutocompleter.prototype.add_field = function () {
	if (this.integer <= this.max_input) {
		var id = this.id_input + '_' + this.integer;
		
		jQuery('<div/>', {'id': id, 'class': 'form-autocompleter-container'}).appendTo('#input_fields_' + this.id_input);
		
		jQuery('<input/>', {'type': 'text', 'id': 'field_' + id, 'name': 'field_' + id, 'onfocus': 'javascript:FormFieldMultipleAutocompleter.load_autocompleter(\'' + id + '\');', 'autocomplete': 'off'}).attr('size', ${escapejs(SIZE)}).appendTo('#' + id);
		jQuery('#' + id).append(' ');
		
		this.load_autocompleter('field_' + id);
		
		jQuery('<a/>', {href : 'javascript:FormFieldMultipleAutocompleter.delete_field('+ this.integer +');'}).html('<i class="fa fa-delete"></i>').appendTo('#menu_element_' + id + '_actions');
		
		this.integer++;
	}
	if (this.integer == this.max_input) {
		jQuery('#add-' + this.id_input).hide();
	}
};

FormFieldMultipleAutocompleter.prototype.delete_field = function (id) {
	var id = this.id_input + '_' + id;
	jQuery('#' + id).remove();
	this.integer--;
	jQuery('#add-' + this.id_input).show();
};

FormFieldMultipleAutocompleter.prototype.load_autocompleter = function (id) {
	jQuery('#' + id).autocomplete({
		serviceUrl: ${escapejs(FILE)},
		paramName: ${escapejs(NAME_PARAMETER)},
		params: {'token': ${escapejs(TOKEN)}},
		minChars: 2
	});
};

var FormFieldMultipleAutocompleter = new FormFieldMultipleAutocompleter();
-->
</script>

<div id="input_fields_${escape(HTML_ID)}">
# START fieldelements #
	<div id="${escape(HTML_ID)}_{fieldelements.ID}" class="form-autocompleter-container">
		<input type="text" name="field_${escape(HTML_ID)}_{fieldelements.ID}" id="field_${escape(HTML_ID)}_{fieldelements.ID}" onfocus="javascript:FormFieldMultipleAutocompleter.load_autocompleter('field_${escape(HTML_ID)}_{fieldelements.ID}');" value="{fieldelements.VALUE}" size="{SIZE}" autocomplete="off"/>
		<a href="javascript:FormFieldMultipleAutocompleter.delete_field({fieldelements.ID});" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
	</div>
# END fieldelements #
</div>
<a href="javascript:FormFieldMultipleAutocompleter.add_field();" class="fa fa-plus" id="add-${escape(HTML_ID)}"></a>