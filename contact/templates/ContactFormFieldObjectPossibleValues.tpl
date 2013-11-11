<script type="text/javascript">
<!--
var ContactFormFieldObjectPossibleValues = Class.create({
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
			
			var input = new Element('input', {'type' : 'text', 'id' : 'field_name_' + id, 'name' : 'field_name_' + id, 'class' : 'text', 'size' : '20' });
			$(id).insert('&nbsp;');
			$(id).insert(input);
			
			var select = new Element('select', {'id' : 'field_recipient_' + id, 'name' : 'field_recipient_' + id});
			# START recipients_list #
			select.appendChild(new Option(${escapejs(recipients_list.NAME)}, ${escapejs(recipients_list.ID)}));
			# END recipients_list #
			$(id).insert('&nbsp;');
			$(id).insert(select);
			
			var delete_input = new Element('a', {href : 'javascript:ContactFormFieldObjectPossibleValues.delete_field('+ this.integer +');', 'id' : 'delete_' + id, 'title' : "${LangLoader::get_message('delete', 'main')}", 'class' : 'icon-delete'});
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

var ContactFormFieldObjectPossibleValues = new ContactFormFieldObjectPossibleValues();
-->
</script>

<div id="input_fields_${escape(ID)}">
<div class="text_strong"><span class="is_default_title">{@admin.field.possible_values.is_default}</span><span class="name_title">{@admin.field.name}</span><span>{@admin.field.possible_values.recipient}</span></div>
<div class="height_spacer"></div>
# START fieldelements #
	<div id="${escape(ID)}_{fieldelements.ID}">
		<input type="checkbox" name="field_is_default_${escape(ID)}_{fieldelements.ID}" id="field_is_default_${escape(ID)}_{fieldelements.ID}" value="1"# IF fieldelements.IS_DEFAULT # checked="checked"# ENDIF # class="per_default" />
		<input type="text" name="field_name_${escape(ID)}_{fieldelements.ID}" id="field_name_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.NAME}" size="20" class="text" />
		<select id="field_recipient_${escape(ID)}_{fieldelements.ID}" name="field_recipient_${escape(ID)}_{fieldelements.ID}">
			# START fieldelements.recipients_list #
			<option value="{fieldelements.recipients_list.ID}" # IF fieldelements.recipients_list.C_RECIPIENT_SELECTED #selected="selected"# ENDIF #>{fieldelements.recipients_list.NAME}</option>
			# END fieldelements.recipients_list #
		</select>
		<a href="javascript:ContactFormFieldObjectPossibleValues.delete_field({fieldelements.ID});" id="delete_${escape(ID)}_{fieldelements.ID}" title="${LangLoader::get_message('delete', 'main')}" class="icon-delete"></a>
	</div>
# END fieldelements #
</div>
<div class="height_spacer"></div>
<a href="javascript:ContactFormFieldObjectPossibleValues.add_field();" class="icon-plus" id="add_${escape(ID)}"></a>

