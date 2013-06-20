<script type="text/javascript">
<!--
var NewsFormFieldSelectSources = Class.create({
	integer : ${escapejs(NBR_FIELDS)},
	id_input : ${escapejs(ID)},
	max_input : ${escapejs(MAX_INPUT)},
	add_field : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;
			
			var input = new Element('input', {'type' : 'text', 'id' : 'field_name_' + id, 'name' : 'field_name_' + id, 'class' : 'text', 'size' : '30'});
			$('input_fields_' + this.id_input).insert(input);

			var input = new Element('input', {'type' : 'text', 'id' : 'field_value_' + id, 'name' : 'field_value_' + id, 'class' : 'text', 'size' : '30'});
			$('input_fields_' + this.id_input).insert(input);
			
			var delete_input = new Element('a', {href : 'javascript:NewsFormFieldSelectSources.delete_field('+ this.integer +');', 'id' : 'delete_' + id}).update('<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="" class="valign_middle" />');
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

var NewsFormFieldSelectSources = new NewsFormFieldSelectSources();
-->
</script>

<div id="input_fields_${escape(ID)}">
# START fieldelements #
		<input type="text" name="field_name_${escape(ID)}_{fieldelements.ID}" id="field_name_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.NAME}" size="30" class="text"/>
		<input type="text" name="field_value_${escape(ID)}_{fieldelements.ID}" id="field_value_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.VALUE}" size="30" class="text"/>
		<a href="javascript:NewsFormFieldSelectSources.delete_field({fieldelements.ID});" id="delete_${escape(ID)}_{fieldelements.ID}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="" class="valign_middle" /></a>
		<br id="br_${escape(ID)}_{fieldelements.ID}">
# END fieldelements #
</div>
<img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/plus.png" id="add_${escape(ID)}" class="valign_middle" style="width:25px;"/>
<script type="text/javascript">
<!--
Event.observe(window, 'load', function() {		
	$('add_${escape(ID)}').observe('click',function(){
		NewsFormFieldSelectSources.add_field();
	});
});
-->
</script>