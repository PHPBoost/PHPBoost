<script>
<!--
var ContactFormFieldRecipientsPossibleValues = Class.create({
	integer : {NBR_FIELDS},
	id_input : ${escapejs(HTML_ID)},
	max_input : {MAX_INPUT},
	add_field : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;
			
			var div = Builder.node('div', {'id' : id}, [
				Builder.node('input', {type : 'checkbox', id : 'field_is_default_' + id, name : 'field_is_default_' + id, value : '1', 'class' : 'per-default'}),
				' ',
				Builder.node('input', {type : 'text', id : 'field_name_' + id, name : 'field_name_' + id, placeholder : '{@field.name}'}),
				' ',
				Builder.node('input', {type : 'text', id : 'field_email_' + id, name : 'field_email_' + id, class : 'field-large', placeholder : "${LangLoader::get_message('field.possible_values.email', 'common', 'contact')}"}),
				' ',
				Builder.node('a', {href : 'javascript:ContactFormFieldRecipientsPossibleValues.delete_field('+ this.integer +');', id : 'delete_' + id, 'title' : "${LangLoader::get_message('delete', 'main')}", class : 'fa fa-delete'}),
				' ',
			]);
			$('input_fields_' + this.id_input).insert(div);
			
			this.integer++;
		}
		if (this.integer == this.max_input) {
			$('add_' + this.id_input).hide();
		}
	},
	delete_field : function (id) {
		var id = this.id_input + '_' + id;
		$(id).remove();
		this.integer--;
		$('add_' + this.id_input).show();
	},
});

var ContactFormFieldRecipientsPossibleValues = new ContactFormFieldRecipientsPossibleValues();
-->
</script>

<div id="input_fields_${escape(HTML_ID)}">
<span class="text-strong">{@field.possible_values.is_default}</span>
# START fieldelements #
	<div id="${escape(HTML_ID)}_{fieldelements.ID}">
		<input type="checkbox" name="field_is_default_${escape(HTML_ID)}_{fieldelements.ID}" id="field_is_default_${escape(HTML_ID)}_{fieldelements.ID}" value="1"# IF fieldelements.IS_DEFAULT # checked="checked"# ENDIF # class="per-default" />
		<input type="text" name="field_name_${escape(HTML_ID)}_{fieldelements.ID}" id="field_name_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.NAME}" placeholder="{@field.name}"/>
		<input type="text" name="field_email_${escape(HTML_ID)}_{fieldelements.ID}" id="field_email_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.EMAIL}" placeholder="${LangLoader::get_message('field.possible_values.email', 'common', 'contact')}" class="field-large"# IF NOT fieldelements.C_DELETABLE #disabled="disabled"# ENDIF # />
		# IF fieldelements.C_DELETABLE #<a href="javascript:ContactFormFieldRecipientsPossibleValues.delete_field({fieldelements.ID});" id="delete_${escape(HTML_ID)}_{fieldelements.ID}" title="${LangLoader::get_message('delete', 'main')}" class="fa fa-delete" data-confirmation="delete-element"></a># ENDIF #
	</div>
# END fieldelements #
</div>
<a href="javascript:ContactFormFieldRecipientsPossibleValues.add_field();" class="fa fa-plus" id="add_${escape(HTML_ID)}"></a>
