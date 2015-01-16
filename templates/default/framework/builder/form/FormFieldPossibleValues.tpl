<script>
<!--
var ContactFormFieldPossibleValues = function(){
	this.integer = ${escapejs(NBR_FIELDS)};
	this.id_input = ${escapejs(HTML_ID)};
	this.max_input = ${escapejs(MAX_INPUT)};
};

ContactFormFieldPossibleValues.prototype = {
	add_field : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;
			
			jQuery('<div/>', {'id' : id}).appendTo('#input_fields_' + this.id_input);

			jQuery('<input/> ', {type : 'checkbox', id : 'field_is_default_' + id, name : 'field_is_default_' + id, value : '1', 'style' : 'margin: 0 25px 0 25px;'}).appendTo('#' + id);
			
			jQuery('<input/> ', {type : 'text', id : 'field_name_' + id, name : 'field_name_' + id, placeholder : '{@field.name}'}).appendTo('#' + id);

			jQuery('<a/> ', {href : 'javascript:ContactFormFieldPossibleValues.delete_field('+ this.integer +');', id : 'delete_' + id, 'title' : "${LangLoader::get_message('delete', 'common')}", class : 'fa fa-delete'}).appendTo('#' + id);
			
			this.integer++;
		}
		if (this.integer == this.max_input) {
			jQuery('#add_' + this.id_input).hide();
		}
	},
	delete_field : function (id) {
		var id = this.id_input + '_' + id;
		jQuery('#' + id).remove();
		this.integer--;
		jQuery('#add_' + this.id_input).show();
	},
};

var ContactFormFieldPossibleValues = new ContactFormFieldPossibleValues();
-->
</script>

<div id="input_fields_${escape(HTML_ID)}">
<span class="text-strong">{@field.possible_values.is_default}</span>
# START fieldelements #
	<div id="${escape(HTML_ID)}_{fieldelements.ID}">
		<input type="checkbox" name="field_is_default_${escape(HTML_ID)}_{fieldelements.ID}" id="field_is_default_${escape(HTML_ID)}_{fieldelements.ID}" value="1"# IF fieldelements.IS_DEFAULT # checked="checked"# ENDIF # style="margin: 0 25px 0 25px;" />
		<input type="text" name="field_name_${escape(HTML_ID)}_{fieldelements.ID}" id="field_name_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.NAME}" placeholder="{@field.name}"/>
		<a href="javascript:ContactFormFieldPossibleValues.delete_field({fieldelements.ID});" id="delete_${escape(HTML_ID)}_{fieldelements.ID}" title="${LangLoader::get_message('delete', 'common')}" class="fa fa-delete" data-confirmation="delete-element"></a>
	</div>
# END fieldelements #
</div>
<a href="javascript:ContactFormFieldPossibleValues.add_field();" class="fa fa-plus" id="add_${escape(HTML_ID)}"></a>
