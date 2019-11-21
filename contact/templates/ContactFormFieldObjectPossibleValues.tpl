<script>
<!--
var ContactFormFieldObjectPossibleValues = function(){
	this.integer = {NBR_FIELDS};
	this.id_input = ${escapejs(HTML_ID)};
	this.max_input = {MAX_INPUT};
};

ContactFormFieldObjectPossibleValues.prototype = {
	add : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;

			jQuery('<div/>', {'id' : id, 'class' : 'possible-values'}).appendTo('#input_fields_' + this.id_input);

			jQuery('<div/>', {id : 'radio_' + this.integer, class: 'form-field-radio custom-radio'}).appendTo('#' + id);
			jQuery('<label/>', {class : 'radio',for : 'field_is_default_' + id}).appendTo('#radio_' + this.integer);
			jQuery('<input/>', {type : 'radio', id : 'field_is_default_' + id, name : 'field_is_default_' + this.id_input, value : this.integer}).appendTo('#radio_' + this.integer + ' label');
			jQuery('<span/>').appendTo('#radio_' + this.integer + ' label');
			jQuery('#radio_' + this.integer).after(' ');

			jQuery('<input/>', {type : 'text', id : 'field_name_' + id, name : 'field_name_' + id, required : "required", placeholder : '{@field.possible_values.subject}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<select/>', {'id' : 'field_recipient_' + id, 'name' : 'field_recipient_' + id}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			# START recipients_list #
			jQuery('<option/>', {'value' : ${escapejs(recipients_list.ID)}}).text(${escapejs(recipients_list.NAME)}).appendTo('#field_recipient_' + id);
			# END recipients_list #

			jQuery('<a/>', {href : 'javascript:ContactFormFieldObjectPossibleValues.delete('+ this.integer +');', 'aria-label' : "${LangLoader::get_message('delete', 'common')}"}).html('<i class="fa fa-trash-alt" aria-hidden="true"></i>').appendTo('#' + id);

			jQuery('<script/>').html('jQuery("#field_is_default_' + id + '").on(\'click\',function(){ jQuery("#uncheck_default_${escape(HTML_ID)}").show(); });').appendTo('#' + id);

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

var ContactFormFieldObjectPossibleValues = new ContactFormFieldObjectPossibleValues();
-->
</script>

<div id="input_fields_${escape(HTML_ID)}">
	<div class="text-strong">
		<span class="title-possible-value is-default-title">${LangLoader::get_message('field.possible_values.is_default', 'admin-user-common')}</span>
		<span class="title-possible-value name-title">{@field.possible_values.subject}</span>
		<span class="title-possible-value title-desc">${LangLoader::get_message('field.possible_values.recipient', 'common', 'contact')}</span>
	</div>
	# START fieldelements #
	<div id="${escape(HTML_ID)}_{fieldelements.ID}" class="possible_values custom-radio">
		<div class="form-field-radio possible-values">
			<label class="radio" for="field_is_default_${escape(HTML_ID)}_{fieldelements.ID}">
				<input type="radio" name="field_is_default_${escape(HTML_ID)}" id="field_is_default_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.ID}"# IF fieldelements.IS_DEFAULT # checked="checked"# ENDIF #>
				<span></span>
			</label>
		</div>
		<input type="text" name="field_name_${escape(HTML_ID)}_{fieldelements.ID}" id="field_name_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.NAME}" placeholder="{@field.possible_values.subject}">
		<select id="field_recipient_${escape(HTML_ID)}_{fieldelements.ID}" name="field_recipient_${escape(HTML_ID)}_{fieldelements.ID}">
			# START fieldelements.recipients_list #
			<option value="{fieldelements.recipients_list.ID}" # IF fieldelements.recipients_list.C_RECIPIENT_SELECTED #selected="selected"# ENDIF #>{fieldelements.recipients_list.NAME}</option>
			# END fieldelements.recipients_list #
		</select>
		<a href="javascript:ContactFormFieldObjectPossibleValues.delete({fieldelements.ID});" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
		<script>
		<!--
		jQuery("#field_is_default_${escape(HTML_ID)}_{fieldelements.ID}").on('click',function(){
			jQuery("#uncheck_default_${escape(HTML_ID)}").show();
		});
		-->
		</script>
	</div>
# END fieldelements #
</div>
<a href="javascript:ContactFormFieldObjectPossibleValues.add();" id="add-${escape(HTML_ID)}" class="form-field-more-values" aria-label="${LangLoader::get_message('add', 'common')}"><i class="fa fa-plus" aria-hidden="true"></i></a>
<a href="" onclick="return false;" id="uncheck_default_${escape(HTML_ID)}"# IF NOT C_HAS_DEFAULT_VALUE # style="display: none;"# ENDIF # class="small">${LangLoader::get_message('field.possible_values.delete_default', 'admin-user-common')}</a>
<script>
<!--
jQuery(document).ready(function() {
	jQuery("#uncheck_default_${escape(HTML_ID)}").on('click',function() {
		jQuery("input[name=field_is_default_${escape(HTML_ID)}]").prop("checked", false);
		jQuery("#uncheck_default_${escape(HTML_ID)}").hide();
	});
});
-->
</script>
