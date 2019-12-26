<script>
<!--
var ContactFormFieldRecipientsPossibleValues = function(){
	this.integer = {NBR_FIELDS};
	this.id_input = ${escapejs(HTML_ID)};
	this.max_input = {MAX_INPUT};
};

ContactFormFieldRecipientsPossibleValues.prototype = {
	add : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;

			jQuery('<div/>', {'id' : id, class: 'mini-checkbox'}).appendTo('#input_fields_' + this.id_input);

			jQuery('<div/>', {id : 'checkbox_' + this.integer, class : 'form-field-checkbox possible-values'}).appendTo('#' + id);
			jQuery('<label/>', {class : 'checkbox', for : 'field_is_default_' + this.id_input + this.integer}).appendTo('#checkbox_' + this.integer);
			jQuery('<input/>', {type : 'checkbox', id : 'field_is_default_' + this.id_input + this.integer, name : 'field_is_default_' + this.id_input, value : '1', 'class' : 'per-default'}).appendTo('#checkbox_' + this.integer + ' label');
			jQuery('<span/>').appendTo('#checkbox_' + this.integer + ' label');
			jQuery('#checkbox_' + this.integer).after(' ');

			jQuery('<input/>', {type : 'text', id : 'field_name_' + id, name : 'field_name_' + id, required : "required", placeholder : '{@field.name}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<input/>', {type : 'email', id : 'field_email_' + id, name : 'field_email_' + id, placeholder : "${LangLoader::get_message('field.possible_values.email', 'common', 'contact')}", required : "required", multiple : "multiple"}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<a/>', {href : 'javascript:ContactFormFieldRecipientsPossibleValues.delete('+ this.integer +');', 'aria-label' : "${LangLoader::get_message('delete', 'common')}"}).html('<i class="far fa-trash-alt" aria-hidden="true"></i>').appendTo('#' + id);

			this.integer++;
		}
		if (this.integer == this.max_input) {
			jQuery('#add-' + this.id_input).hide();
		}
	},
	delete : function (id) {
		var id = this.id_input + '_' + id;
		jQuery('#' + id).remove();
		this.integer--;
		jQuery('#add-' + this.id_input).show();
	},
};

var ContactFormFieldRecipientsPossibleValues = new ContactFormFieldRecipientsPossibleValues();
-->
</script>

<div id="input_fields_${escape(HTML_ID)}">
<span class="text-strong is-default-title">{@field.possible_values.is_default}</span>
# START fieldelements #
	<div id="${escape(HTML_ID)}_{fieldelements.ID}" class="mini-checkbox">
		<div class="form-field-checkbox possible-values">
			<label class="checkbox" for="field_is_default_${escape(HTML_ID)}_{fieldelements.ID}">
				<input type="checkbox" name="field_is_default_${escape(HTML_ID)}_{fieldelements.ID}" id="field_is_default_${escape(HTML_ID)}_{fieldelements.ID}" value="1"# IF fieldelements.IS_DEFAULT # checked="checked"# ENDIF # class="per-default">
				<span>&nbsp;</span>
			</label>
		</div>
		<input type="text" name="field_name_${escape(HTML_ID)}_{fieldelements.ID}" id="field_name_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.NAME}" placeholder="{@field.name}">
		<input type="email" name="field_email_${escape(HTML_ID)}_{fieldelements.ID}" id="field_email_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.EMAIL}" placeholder="${LangLoader::get_message('field.possible_values.email', 'common', 'contact')}" multiple="multiple"# IF NOT fieldelements.C_DELETABLE # disabled="disabled"# ENDIF #>
		# IF fieldelements.C_DELETABLE #<a href="javascript:ContactFormFieldRecipientsPossibleValues.delete({fieldelements.ID});" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="far fa-trash-alt" aria-hidden="true"></i></a># ENDIF #
	</div>
# END fieldelements #
</div>
<a href="javascript:ContactFormFieldRecipientsPossibleValues.add();" id="add-${escape(HTML_ID)}" class="form-field-checkbox-more-value" aria-label="${LangLoader::get_message('add', 'common')}"><i class="fa fa-plus" aria-hidden="true"></i></a>
