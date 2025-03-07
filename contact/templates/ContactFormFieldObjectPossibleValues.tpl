<script>
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

				jQuery('<div/>', {id : 'inputs_' + this.integer, class: 'grouped-inputs'}).appendTo('#' + id);
				jQuery('<input/>', {class : 'grouped-element', type : 'text', id : 'field_name_' + id, name : 'field_name_' + id, required : "required", placeholder : '{@contact.possible.values.subject}'}).appendTo('#inputs_' + this.integer);
				jQuery('#' + id).append(' ');

				jQuery('<select/>', {class : 'grouped-element', 'id' : 'field_recipient_' + id, 'name' : 'field_recipient_' + id}).appendTo('#inputs_' + this.integer);
				jQuery('#' + id).append(' ');

				# START recipients_list #
				jQuery('<option/>', {'value' : ${escapejs(recipients_list.ID)}}).text(${escapejs(recipients_list.NAME)}).appendTo('#field_recipient_' + id);
				# END recipients_list #

				jQuery('<a/>', {class : 'grouped-element', href : 'javascript:ContactFormFieldObjectPossibleValues.delete('+ this.integer +');', 'aria-label' : "{@common.delete}"}).html('<i class="far fa-trash-alt" aria-hidden="true"></i>').appendTo('#inputs_' + this.integer);

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
</script>

<div id="input_fields_${escape(HTML_ID)}">
	<div class="text-strong">
		<span class="title-possible-value is-default-title">{@form.is.default}</span>
		<span class="title-possible-value name-title">{@contact.possible.values.subject}</span>
		<span class="title-possible-value title-desc">{@contact.possible.values.recipient}</span>
	</div>
	# START fieldelements #
		<div id="${escape(HTML_ID)}_{fieldelements.ID}" class="possible-values custom-radio">
			<div class="form-field-radio">
				<label class="radio" for="field_is_default_${escape(HTML_ID)}_{fieldelements.ID}">
					<input type="radio" name="field_is_default_${escape(HTML_ID)}" id="field_is_default_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.ID}"# IF fieldelements.IS_DEFAULT # checked="checked"# ENDIF #>
					<span></span>
				</label>
			</div>
			<div class="grouped-inputs">
				<input class="grouped-element" type="text" name="field_name_${escape(HTML_ID)}_{fieldelements.ID}" id="field_name_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.NAME}" placeholder="{@contact.possible.values.subject}">
				<select class="grouped-element" id="field_recipient_${escape(HTML_ID)}_{fieldelements.ID}" name="field_recipient_${escape(HTML_ID)}_{fieldelements.ID}">
					# START fieldelements.recipients_list #
					<option value="{fieldelements.recipients_list.ID}" # IF fieldelements.recipients_list.C_RECIPIENT_SELECTED #selected="selected"# ENDIF #>{fieldelements.recipients_list.NAME}</option>
					# END fieldelements.recipients_list #
				</select>
				<a class="grouped-element" href="javascript:ContactFormFieldObjectPossibleValues.delete({fieldelements.ID});" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
			</div>
			<script>
				jQuery("#field_is_default_${escape(HTML_ID)}_{fieldelements.ID}").on('click',function(){
					jQuery("#uncheck_default_${escape(HTML_ID)}").show();
				});
			</script>
		</div>
	# END fieldelements #
</div>
<div class="flex-between">
	<a href="javascript:ContactFormFieldObjectPossibleValues.add();" id="add-${escape(HTML_ID)}" class="form-field-more-values" aria-label="{@common.add}"><i class="far fa-lg fa-plus-square" aria-hidden="true"></i></a>
	<a href="#" onclick="return false;" id="uncheck_default_${escape(HTML_ID)}"# IF NOT C_HAS_DEFAULT_VALUE # style="display: none;"# ENDIF # class="small"><i class="fa fa-times" aria-hidden="true"></i> {@form.delete.default.value}</a>
</div>

<script>
	jQuery(document).ready(function() {
		jQuery("#uncheck_default_${escape(HTML_ID)}").on('click',function() {
			jQuery("input[name=field_is_default_${escape(HTML_ID)}]").prop("checked", false);
			jQuery("#uncheck_default_${escape(HTML_ID)}").hide();
		});
	});
</script>
