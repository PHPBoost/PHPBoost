<script type="text/javascript">
<!--
var ArticlesFormFieldSelectSources = Class.create({
	integer : ${escapejs(NBR_FIELDS)},
	id_input : ${escapejs(ID)},
	max_input : ${escapejs(MAX_INPUT)},
	add_field : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;
			
			var input = new Element('input', {'type' : 'text', 'id' : 'field_name_' + id, 'name' : 'field_name_' + id, 'class' : 'text', 'size' : '30'});
			$('input_fields_' + this.id_input).insert(input);

			var input = new Element('input', {'type' : 'text', 'id' : 'field_value_' + id, 'name' : 'field_value_' + id, 'class' : 'text', 'size' : '30'});
			$('input_fields_' + this.id_input).insert('&nbsp;');
			$('input_fields_' + this.id_input).insert(input);
			
			var delete_input = new Element('a', {href : 'javascript:ArticlesFormFieldSelectSources.delete_field('+ this.integer +');', 'id' : 'delete_' + id, 'class' : 'pbt-icon-delete'});
			$('input_fields_' + this.id_input).insert('&nbsp;');
			$('input_fields_' + this.id_input).insert(delete_input);
			
			var br = new Element('br', {'id' : 'br_' + id});
			$('input_fields_' + this.id_input).insert(br);

			this.integer++;
		}
		if (this.integer == this.max_input) {
			$('add_' + this.id_input).hide();
		}
	},
	delete_field : function (id) {
		var id = this.id_input + '_' + id;
		$('field_name_' + id).remove();
		$('field_value_' + id).remove();
		$('delete_' + id).remove();
		$('br_' + id).remove();
		this.integer--;
		$('add_' + this.id_input).show();
	},
});

var ArticlesFormFieldSelectSources = new ArticlesFormFieldSelectSources();
-->
</script>

<div id="input_fields_${escape(ID)}">
# START fieldelements #
	<div style="float:left;width:84%;display:inline;padding-bottom:5px;color:#334455;font-weight:bold;"><span style="width:50%;margin:0 75px 0 40px;">${i18n('articles.form.source_name')}</span><span style="width:50%;">${i18n('articles.form.source_url')}</span></div>
	<input type="text" name="field_name_${escape(ID)}_{fieldelements.ID}" id="field_name_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.NAME}" size="30" class="text">
	<input type="text" name="field_value_${escape(ID)}_{fieldelements.ID}" id="field_value_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.VALUE}" size="30" class="text"/>
	<a href="javascript:ArticlesFormFieldSelectSources.delete_field({fieldelements.ID});" id="delete_${escape(ID)}_{fieldelements.ID}" class="pbt-icon-delete" data-confirmation="delete-element"></a>
	<br id="br_${escape(ID)}_{fieldelements.ID}">
# END fieldelements #
</div>
<a href="javascript:ArticlesFormFieldSelectSources.add_field();" class="pbt-icon-plus" id="add_${escape(ID)}"></a>