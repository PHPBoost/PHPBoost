<script>
<!--
var ContactFormFieldPossibleValues = Class.create({
	integer : {NBR_FIELDS},
	id_input : ${escapejs(HTML_ID)},
	max_input : {MAX_INPUT},
	add_field : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;
			
			var div = Builder.node('div', {'id' : id}, [
				Builder.node('input', {type : 'checkbox', id : 'field_is_default_' + id, name : 'field_is_default_' + id, value : '1', 'style' : 'margin: 0 25px 0 25px;'}),
				' ',
				Builder.node('input', {type : 'text', id : 'field_name_' + id, name : 'field_name_' + id, placeholder : '{@field.name}'}),
				' ',
				Builder.node('a', {href : 'javascript:ContactFormFieldPossibleValues.delete_field('+ this.integer +');', id : 'delete_' + id, 'title' : "${LangLoader::get_message('delete', 'main')}", class : 'fa fa-delete'}),
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

var ContactFormFieldPossibleValues = new ContactFormFieldPossibleValues();
-->
</script>

<div id="input_fields_${escape(HTML_ID)}">
<span class="text-strong">{@field.possible_values.is_default}</span>
# START fieldelements #
	<div id="${escape(HTML_ID)}_{fieldelements.ID}">
		<input type="checkbox" name="field_is_default_${escape(HTML_ID)}_{fieldelements.ID}" id="field_is_default_${escape(HTML_ID)}_{fieldelements.ID}" value="1"# IF fieldelements.IS_DEFAULT # checked="checked"# ENDIF # style="margin: 0 25px 0 25px;" />
		<input type="text" name="field_name_${escape(HTML_ID)}_{fieldelements.ID}" id="field_name_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.NAME}" placeholder="{@field.name}"/>
		<a href="javascript:ContactFormFieldPossibleValues.delete_field({fieldelements.ID});" id="delete_${escape(HTML_ID)}_{fieldelements.ID}" title="${LangLoader::get_message('delete', 'main')}" class="fa fa-delete" data-confirmation="delete-element"></a>
	</div>
# END fieldelements #
</div>
<a href="javascript:ContactFormFieldPossibleValues.add_field();" class="fa fa-plus" id="add_${escape(HTML_ID)}"></a>
