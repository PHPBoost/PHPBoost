<script type="text/javascript">
<!--
var BugtrackerFormFieldTypes = Class.create({
	integer : {NEXT_ID},
	max_input : {MAX_INPUT},
	add_type : function () {
		if (this.integer <= this.max_input) {
			var id = this.integer;
			
			var tr = Builder.node('tr', {'id' : 'tr_' + id}, []);
			
			var td = Builder.node('td', {'id' : 'td1_' + id}, [
				Builder.node('input', {type : 'radio', name : 'default_type', value : id}),
				' ',
			]);
			tr.insert(td);
			
			var td = Builder.node('td', {'id' : 'td2_' + id}, [
				Builder.node('input', {type : 'text', id : 'type_' + id, name : 'type_' + id, size : 40, maxlength : 100, placeholder : ${escapejs(LangLoader::get_message('name', 'main'))}}),
				' ',
			]);
			tr.insert(td);
			
			var td = Builder.node('td', {'id' : 'td3_' + id}, [
				Builder.node('a', {id : 'delete_' + id, href : 'javascript:BugtrackerFormFieldTypes.delete_type(' + id + ');', title : ${escapejs(LangLoader::get_message('delete', 'main'))}, className: 'fa fa-delete'}),
				' ',
			]);
			tr.insert(td);
			
			$('types_list').insert(tr);
			
			this.integer++;
		}
		if (this.integer == this.max_input) {
			$('add_type').hide();
		}
		if (this.integer) {
			$('no_type').hide();
		}
	},
	delete_type : function (id) {
		$('tr_' + id).remove();
		this.integer--;
		if (this.integer == 1)
			$('no_type').style.display = "";
		
		$('add_type').show();
	},
});

var BugtrackerFormFieldTypes = new BugtrackerFormFieldTypes();
-->
</script>

<table>
	<thead>
		<tr>
			<th class="column_default">
				{@bugs.labels.default}
			</th>
			<th>
				${LangLoader::get_message('name', 'main')}
			</th>
			<th class="column_delete">
				${LangLoader::get_message('delete', 'main')}
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>
				<a href="javascript:BugtrackerFormFieldTypes.add_type();" class="fa fa-plus" title="{@bugs.titles.add_type}" id="add_type"></a>
			</th>
			<th colspan="2" style="text-align:right;">
				# IF C_DISPLAY_DEFAULT_DELETE_BUTTON #<a href="{LINK_DELETE_DEFAULT}" title="${LangLoader::get_message('delete', 'main')}" data-confirmation="{@bugs.actions.confirm.del_default_value}"><i class="fa fa-delete" ></i> {@bugs.labels.del_default_value}</a># ENDIF #
			</th>
		</tr>
	</tfoot>
	<tbody id="types_list">
		<tr id="no_type"# IF C_TYPES # style="display:none;"# ENDIF #>
			<td colspan="3">
				{@bugs.notice.no_type}
			</td>
		</tr>
		# START types #
		<tr>
			<td>
				<input type="radio" name="default_type" value="{types.ID}"# IF types.C_IS_DEFAULT # checked="checked"# ENDIF # />
			</td>
			<td>
				<input type="text" maxlength="100" size="40" name="type{types.ID}" value="{types.NAME}" />
			</td>
			<td>
				<a href="{types.LINK_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="fa fa-delete" data-confirmation="{@bugs.actions.confirm.del_type}"></a>
			</td>
		</tr>
		# END types #
	</tbody>
</table>
