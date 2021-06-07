<script>
	var FormFieldMultipleAutocompleter = function(){
		this.integer = {FIELDS_NUMBER};
		this.id_input = ${escapejs(HTML_ID)};
		this.max_input = {MAX_INPUT};
	};

	FormFieldMultipleAutocompleter.prototype.add_field = function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;

			jQuery('<div/>', {'id': id, 'class': 'form-autocompleter-container grouped-inputs'}).appendTo('#input_fields_' + this.id_input);

			jQuery('<input/>', {'type': 'text', 'id': 'field_' + id, class : 'grouped-element', 'name': 'field_' + id, 'onfocus': 'javascript:FormFieldMultipleAutocompleter.load_autocompleter(\'' + id + '\');', 'autocomplete': 'off'}).attr('size', ${escapejs(SIZE)}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			this.load_autocompleter('field_' + id);

			jQuery('<a/>', {href : 'javascript:FormFieldMultipleAutocompleter.delete_field('+ this.integer +');', class : 'grouped-element bgc-full error', 'aria-label' : ${escapejs(@common.delete)}}).html('<i class="far fa-trash-alt" aria-hidden="true"></i>').appendTo('#' + id);

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
</script>

<div id="input_fields_${escape(HTML_ID)}">
# START fieldelements #
	<div id="${escape(HTML_ID)}_{fieldelements.ID}" class="form-autocompleter-container grouped-inputs">
		<input class="grouped-element" type="text" name="field_${escape(HTML_ID)}_{fieldelements.ID}" id="field_${escape(HTML_ID)}_{fieldelements.ID}" onfocus="javascript:FormFieldMultipleAutocompleter.load_autocompleter('field_${escape(HTML_ID)}_{fieldelements.ID}');" value="{fieldelements.VALUE}" size="{SIZE}" autocomplete="off"/>
		<a href="javascript:FormFieldMultipleAutocompleter.delete_field({fieldelements.ID});" class="grouped-element bgc-full error" data-confirmation="delete-element" aria-label="{@common.delete}"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
	</div>
# END fieldelements #
</div>
<a href="javascript:FormFieldMultipleAutocompleter.add_field();" id="add-${escape(HTML_ID)}" class="add-more-values" aria-label="{@common.add}"><i class="far fa-lg fa-plus-square" aria-hidden="true"></i></a>
