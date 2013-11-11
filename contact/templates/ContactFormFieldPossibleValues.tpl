<script type="text/javascript">
<!--
var ContactFormFieldPossibleValues = Class.create({
	integer : {NBR_FIELDS},
	id_input : ${escapejs(ID)},
	max_input : {MAX_INPUT},
	add_field : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;
			
			var div = new Element('div', {'id' : id});
			$('input_fields_' + this.id_input).insert(div);
			
			var input = new Element('input', {'type' : 'checkbox', 'id' : 'field_is_default_' + id, 'name' : 'field_is_default_' + id, 'value' : '1', 'class' : 'per_default' });
			$(id).insert(input);
			
			var input = new Element('input', {'type' : 'text', 'id' : 'field_name_' + id, 'name' : 'field_name_' + id, 'class' : 'text', 'size' : '25' });
			$(id).insert('&nbsp;');
			$(id).insert(input);
			
			var delete_input = new Element('a', {href : 'javascript:ContactFormFieldPossibleValues.delete_field('+ this.integer +');', 'id' : 'delete_' + id, 'title' : "${LangLoader::get_message('delete', 'main')}", 'class' : 'icon-delete'});
			$(id).insert('&nbsp;');
			$(id).insert(delete_input);
			
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

<div id="input_fields_${escape(ID)}">
<div class="text_strong"><span class="is_default_title">{@admin.field.possible_values.is_default}</span><span>{@admin.field.name}</span></div>
<div class="height_spacer"></div>
# START fieldelements #
	<div id="${escape(ID)}_{fieldelements.ID}">
		<input type="checkbox" name="field_is_default_${escape(ID)}_{fieldelements.ID}" id="field_is_default_${escape(ID)}_{fieldelements.ID}" value="1"# IF fieldelements.IS_DEFAULT # checked="checked"# ENDIF # class="per_default" />
		<input type="text" name="field_name_${escape(ID)}_{fieldelements.ID}" id="field_name_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.NAME}" size="25" class="text" />
		<a href="javascript:ContactFormFieldPossibleValues.delete_field({fieldelements.ID});" id="delete_${escape(ID)}_{fieldelements.ID}" title="${LangLoader::get_message('delete', 'main')}" class="icon-delete"></a>
	</div>
# END fieldelements #
</div>
<div class="height_spacer"></div>
<a href="javascript:ContactFormFieldPossibleValues.add_field();" class="icon-plus" id="add_${escape(ID)}"></a>
