<script>
<!--
var FormFieldPossibleValues = function(){
	this.integer = {NBR_FIELDS};
	this.id_input = ${escapejs(HTML_ID)};
	this.max_input = {MAX_INPUT};
};

FormFieldPossibleValues.prototype = {
	add_field : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;
			
			jQuery('<div/>', {id : id}).appendTo('#input_fields_' + this.id_input);
			
			jQuery('<div/>', {id : id + '_radio', class: 'form-field-radio'}).appendTo('#' + id);
			jQuery('<input/> ', {type : 'radio', id : 'field_is_default_' + this.id_input + '_' + this.integer, name : 'field_is_default_' + this.id_input, value : this.integer}).appendTo('#' + id + '_radio');
			jQuery('<label/> ', {for : 'field_is_default_' + this.id_input + '_' + this.integer}).appendTo('#' + id + '_radio');
			jQuery('#' + id + '_radio').after(' ');

			jQuery('<input/> ', {type : 'text', id : 'field_name_' + id, name : 'field_name_' + id, placeholder : '{@field.name}'}).appendTo('#' + id);
			jQuery('#field_name_' + id).after(' ');
			
			jQuery('<a/> ', {href : 'javascript:FormFieldPossibleValues.delete_field('+ this.integer +');', id : 'delete_' + id, 'title' : "${LangLoader::get_message('delete', 'common')}", class : 'fa fa-delete'}).appendTo('#' + id);
			
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

var FormFieldPossibleValues = new FormFieldPossibleValues();
-->
</script>

<div id="input_fields_${escape(HTML_ID)}" class="form-field-values">
<span class="text-strong is-default-title">{@field.possible_values.is_default}</span>
# START fieldelements #
	<div id="${escape(HTML_ID)}_{fieldelements.ID}">
		<div class="form-field-radio">
			<input type="radio" name="field_is_default_${escape(HTML_ID)}" id="field_is_default_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.ID}"# IF fieldelements.IS_DEFAULT # checked="checked"# ENDIF #>
			<label for="field_is_default_${escape(HTML_ID)}_{fieldelements.ID}"></label>
		</div>
		<input type="text" name="field_name_${escape(HTML_ID)}_{fieldelements.ID}" id="field_name_${escape(HTML_ID)}_{fieldelements.ID}" value="{fieldelements.TITLE}" placeholder="{@field.name}"/>
		<a href="javascript:FormFieldPossibleValues.delete_field({fieldelements.ID});" id="delete_${escape(HTML_ID)}_{fieldelements.ID}" title="${LangLoader::get_message('delete', 'common')}" class="fa fa-delete" data-confirmation="delete-element"></a>
	</div>
# END fieldelements #
</div>
<a href="javascript:FormFieldPossibleValues.add_field();" id="add_${escape(HTML_ID)}" class="form-field-more-values" title="${LangLoader::get_message('add', 'common')}"><i class="fa fa-plus"></i></a>
<a href="" onclick="return false;" id="uncheck_default_${escape(HTML_ID)}"# IF NOT C_HAS_DEFAULT_VALUE # style="display: none;"# ENDIF # title="${LangLoader::get_message('field.possible_values.delete_default', 'admin-user-common')}" class="small">${LangLoader::get_message('field.possible_values.delete_default', 'admin-user-common')}</a>
<script>
<!--
jQuery(document).ready(function() {
	jQuery("input[name=field_is_default_${escape(HTML_ID)}]").on('click',function(){
		jQuery("#uncheck_default_${escape(HTML_ID)}").show();
	});
	
	jQuery("#uncheck_default_${escape(HTML_ID)}").click(function() {
		jQuery("input[name=field_is_default_${escape(HTML_ID)}]").prop("checked", false);
		jQuery("#uncheck_default_${escape(HTML_ID)}").hide();
	});
});
-->
</script>
