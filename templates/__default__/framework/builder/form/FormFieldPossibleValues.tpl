<script>
	var FormFieldPossibleValues = function(){
		this.fields_number = {FIELDS_NUMBER};
		this.integer = {FIELDS_NUMBER};
		this.id_input = ${escapejs(HTML_ID)};
		this.min_input = {MIN_INPUT};
		this.max_input = {MAX_INPUT};
	};

	FormFieldPossibleValues.prototype = {
		add_field : function () {
			if (this.fields_number <= this.max_input) {
				var id = this.id_input + '_' + this.integer;

				jQuery('<div/>', {id : id, class: 'possible-values custom-radio'}).appendTo('#input_fields_' + this.id_input);

				# IF C_DISPLAY_DEFAULT_RADIO #
					jQuery('<div/>', {id : id + '_radio', class: 'form-field-radio'}).appendTo('#' + id);
					jQuery('<label/>', {class : 'radio', for : 'field_is_default_' + this.id_input + '_' + this.integer}).appendTo('#' + id + '_radio');
					jQuery('<input/>', {type : 'radio', id : 'field_is_default_' + this.id_input + '_' + this.integer, name : 'field_is_default_' + this.id_input, value : this.integer}).appendTo('#' + id + '_radio label');
					jQuery('<span/>', {class : 'is-default-title'}).html('&nbsp;').appendTo('#' + id + '_radio label');
					jQuery('#' + id + '_radio').after(' ');
				# ENDIF #

				jQuery('<div/>', {id : id + '_inputs', class: 'grouped-inputs'}).appendTo('#' + id);
				jQuery('<input/>', {class : 'grouped-element', type : 'text', id : 'field_name_' + id, name : 'field_name_' + id, placeholder : '{PLACEHOLDER}'}).appendTo('#' + id + '_inputs');
				jQuery('#field_name_' + id).after(' ');
				jQuery('<a/>', {class : 'grouped-element bgc-full error', href : 'javascript:FormFieldPossibleValues.delete_field('+ this.integer +');', id : 'delete_' + id, 'aria-label' : "${LangLoader::get_message('common.delete', 'common-lang')}"}).html('<i class="far fa-trash-alt" aria-hidden="true"></i>').appendTo('#' + id + '_inputs');

				# IF C_DISPLAY_DEFAULT_RADIO #
					jQuery('<script/>').html('jQuery("#field_is_default_' + id + '").on(\'click\',function(){ jQuery("#uncheck_default_${escape(HTML_ID)}").show(); });').appendTo('#' + id);
				# ENDIF #

				this.integer++;
				this.fields_number++;
				if (this.fields_number > this.min_input)
					jQuery('#input_fields_${escape(HTML_ID)}').find('a[id*="delete_"]').removeClass('icon-disabled');
			}
			if (this.fields_number == this.max_input) {
				jQuery('#add_' + this.id_input).hide();
			}
		},
		delete_field : function (id) {
			if (this.fields_number > this.min_input) {
				var id = this.id_input + '_' + id;

				# IF C_DISPLAY_DEFAULT_RADIO #
				if (jQuery("#field_is_default_" + id).is(':checked'))
					jQuery("#uncheck_default_${escape(HTML_ID)}").hide();
				# ENDIF #

				jQuery('#' + id).remove();
				this.fields_number--;
				if (this.fields_number <= this.min_input)
					jQuery('#input_fields_${escape(HTML_ID)}').find('a[id*="delete_"]').addClass('icon-disabled');
			}
			jQuery('#add_' + this.id_input).show();
		},
	};

	var FormFieldPossibleValues = new FormFieldPossibleValues();

	jQuery("#${escape(HTML_ID)}_field").on("focusout", function() {
		jQuery("input[id^=field_name_${escape(HTML_ID)}]").each(function() {
			var html_id = "${escape(HTML_ID)}";
			var message = "error";
			var validation = [];

			# IF C_REQUIRED #
			if ({MIN_INPUT})
				validation.push(MinPossibleValuesFormFieldValidator(html_id, {MIN_INPUT}, message));
			if ({MAX_INPUT})
				validation.push(MaxPossibleValuesFormFieldValidator(html_id, {MAX_INPUT}, message));
			# ENDIF #

			# IF C_UNIQUE_INPUT_VALUE #
			validation.push(UniquePossibleValuesFormFieldValidator(html_id, message));
			# ENDIF #

			if (!jQuery.isEmptyObject(validation)) {
			if (validation.indexOf(message) !== -1) {
				jQuery('#'+html_id+'_field').removeClass('constraint-status-right').addClass('constraint-status-error');
			}
			else {
				jQuery('#'+html_id+'_field').removeClass('constraint-status-error').addClass('constraint-status-right');
				jQuery("#onblurMessageResponse" +html_id).hide();
			}
			}
		});
	});
</script>

<div id="input_fields_${escape(HTML_ID)}" class="form-field-values">
	<span class="text-strong is-default-title hidden-small-screens">{@form.is.default}</span>
	# START fieldelements #
		<div id="${escape(HTML_ID)}_{fieldelements.ID}" class="possible-values custom-radio">
			# IF C_DISPLAY_DEFAULT_RADIO #
				<div class="form-field-radio">
					<label class="radio" for="field_is_default_${escape(HTML_ID)}_{fieldelements.ID}">
						<input type="radio" name="field_is_default_${escape(HTML_ID)}" id="field_is_default_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.ID}"# IF fieldelements.IS_DEFAULT # checked="checked"# ENDIF #>
						<span class="is-default-title">&nbsp;</span>
					</label>
				</div>
			# ENDIF #

			<div class="grouped-inputs">
				<input class="grouped-element" type="text" name="field_name_${escape(HTML_ID)}_{fieldelements.ID}" id="field_name_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.TITLE}" placeholder="{PLACEHOLDER}"/>
				<a class="grouped-element bgc-full error# IF NOT C_DELETE # icon-disabled# ENDIF #" href="javascript:FormFieldPossibleValues.delete_field({fieldelements.ID});" id="delete_${escape(HTML_ID)}_{fieldelements.ID}" aria-label="${LangLoader::get_message('common.delete', 'common-lang')}"# IF C_DELETE # data-confirmation="delete-element"# ENDIF #><i class="far fa-trash-alt" aria-hidden="true"></i></a>
			</div>

			# IF C_DISPLAY_DEFAULT_RADIO #
				<script>
					jQuery("#field_is_default_${escape(HTML_ID)}_{fieldelements.ID}").on('click',function(){
						jQuery("#uncheck_default_${escape(HTML_ID)}").show();
					});
				</script>
			# ENDIF #
		</div>
	# END fieldelements #
</div>
<div class="flex-between">
	<a href="javascript:FormFieldPossibleValues.add_field();" id="add_${escape(HTML_ID)}" class="add-more-values" aria-label="${LangLoader::get_message('common.add', 'common-lang')}"><i class="far fa-lg fa-plus-square" aria-hidden="true"></i></a>
	# IF C_DISPLAY_DEFAULT_RADIO #<a href="#" onclick="return false;" id="uncheck_default_${escape(HTML_ID)}"# IF NOT C_HAS_DEFAULT_VALUE # style="display: none;"# ENDIF # class="small"> <i class="fa fa-times" aria-hidden="true"></i> {@form.delete.default.values}</a># ENDIF #
</div>

# IF C_DISPLAY_DEFAULT_RADIO #
	<script>
		jQuery(document).ready(function() {
			jQuery("#uncheck_default_${escape(HTML_ID)}").on('click',function() {
				jQuery("input[name=field_is_default_${escape(HTML_ID)}]").prop("checked", false);
				jQuery("#uncheck_default_${escape(HTML_ID)}").hide();
			});
		});
	</script>
# ENDIF #
