<script>
<!--
var NewsFormFieldSelectSources = Class.create({
	integer : ${escapejs(NBR_FIELDS)},
	id_input : ${escapejs(ID)},
	max_input : ${escapejs(MAX_INPUT)},
	add_field : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;

			var div = Builder.node('div', {'id' : id}, [
				Builder.node('input', {type : 'text', id : 'field_name_' + id, name : 'field_name_' + id, placeholder : '{@form.source.name}'}),
				' ',
				Builder.node('input', {type : 'text', id : 'field_value_' + id, name : 'field_value_' + id, class : 'field-large', placeholder : '{@form.source.url}'}),
				' ',
				Builder.node('a', {href : 'javascript:NewsFormFieldSelectSources.delete_field('+ this.integer +');', id : 'delete_' + id, class : 'fa fa-delete'}),
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

var NewsFormFieldSelectSources = new NewsFormFieldSelectSources();
-->
</script>

<div id="input_fields_${escape(ID)}">
# START fieldelements #
	<div id="${escape(ID)}_{fieldelements.ID}">
		<input type="text" name="field_name_${escape(ID)}_{fieldelements.ID}" id="field_name_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.NAME}" placeholder="{@form.source.name}"/>
		<input type="text" name="field_value_${escape(ID)}_{fieldelements.ID}" id="field_value_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.VALUE}" placeholder="{@form.source.url}" class="field-large"/>
		<a href="javascript:NewsFormFieldSelectSources.delete_field({fieldelements.ID});" id="delete_${escape(ID)}_{fieldelements.ID}" class="fa fa-delete" data-confirmation="delete-element"></a>
	</div>
# END fieldelements #
</div>
<a href="javascript:NewsFormFieldSelectSources.add_field();" class="fa fa-plus" id="add_${escape(ID)}"></a> 