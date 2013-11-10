<script type="text/javascript">
<!--
var ArticlesFormFieldSelectSources = Class.create({
	integer : ${escapejs(NBR_FIELDS)},
	id_input : ${escapejs(ID)},
	max_input : ${escapejs(MAX_INPUT)},
	add_field : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;

			var div = new Element('div', {'id' : id});
			$('input_fields_' + this.id_input).insert(div);
			
			var input = new Element('input', {'type' : 'text', 'id' : 'field_name_' + id, 'name' : 'field_name_' + id, 'class' : 'text', 'size' : '30'});
			$(id).insert(input);

			var input = new Element('input', {'type' : 'text', 'id' : 'field_value_' + id, 'name' : 'field_value_' + id, 'class' : 'text', 'size' : '30'});
			$(id).insert('&nbsp;');
			$(id).insert(input);
			
			var delete_input = new Element('a', {href : 'javascript:ArticlesFormFieldSelectSources.delete_field('+ this.integer +');', 'id' : 'delete_' + id, 'class' : 'icon-delete'});
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

var ArticlesFormFieldSelectSources = new ArticlesFormFieldSelectSources();
-->
</script>

<div id="input_fields_${escape(ID)}">
# START fieldelements #
		<div id="${escape(ID)}_{fieldelements.ID}">
			<input type="text" name="field_name_${escape(ID)}_{fieldelements.ID}" id="field_name_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.NAME}" size="30" class="text"/>
			<input type="text" name="field_value_${escape(ID)}_{fieldelements.ID}" id="field_value_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.VALUE}" size="30" class="text"/>
			<a href="javascript:ArticlesFormFieldSelectSources.delete_field({fieldelements.ID});" id="delete_${escape(ID)}_{fieldelements.ID}" class="icon-delete" data-confirmation="delete-element"></a>
		</div>
# END fieldelements #
</div>
<a href="javascript:ArticlesFormFieldSelectSources.add_field();" class="icon-plus" id="add_${escape(ID)}"></a> 