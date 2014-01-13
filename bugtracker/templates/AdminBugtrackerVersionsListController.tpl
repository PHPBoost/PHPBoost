<script type="text/javascript">
<!--
var BugtrackerFormFieldVersions = Class.create({
	integer : {NEXT_ID},
	max_input : {MAX_INPUT},
	add_version : function () {
		if (this.integer <= this.max_input) {
			var id = this.integer;
			
			var tr = Builder.node('tr', {'id' : 'tr_' + id}, []);
			
			var td = Builder.node('td', {'id' : 'td1_' + id}, [
				Builder.node('input', {type : 'radio', id : 'default_version' + id, name : 'default_version', value : id, style : 'display:none;'}),
				' ',
			]);
			tr.insert(td);
			
			var td = Builder.node('td', {'id' : 'td2_' + id}, [
				Builder.node('input', {type : 'text', id : 'version_' + id, name : 'version_' + id, size : 40, maxlength : 100, placeholder : ${escapejs(LangLoader::get_message('name', 'main'))}}),
				' ',
			]);
			tr.insert(td);
			
			var td = Builder.node('td', {'id' : 'td3_' + id}, [
				Builder.node('input', {type : 'text', id : 'release_date_' + id, name : 'release_date_' + id, size : 11, maxlength : 10, placeholder : ${escapejs(@bugs.labels.fields.version_release_date.explain)}}),
				' ',
			]);
			tr.insert(td);
			
			var td = Builder.node('td', {'id' : 'td4_' + id}, [
				Builder.node('input', {type : 'checkbox', id : 'detected_in' + id, name : 'detected_in' + id, onclick : 'javascript:display_default_version_radio(' + id + ');'}),
				' ',
			]);
			tr.insert(td);
			
			var td = Builder.node('td', {'id' : 'td5_' + id}, [
				Builder.node('a', {id : 'delete_' + id, href : 'javascript:BugtrackerFormFieldVersions.delete_version(' + id + ');', title : ${escapejs(LangLoader::get_message('delete', 'main'))}, className: 'fa fa-delete'}),
				' ',
			]);
			tr.insert(td);
			
			$('versions_list').insert(tr);
			
			this.integer++;
		}
		if (this.integer == this.max_input) {
			$('add_version').hide();
		}
		if (this.integer == 2) {
			$('no_version').hide();
		}
	},
	delete_version : function (id) {
		$('tr_' + id).remove();
		this.integer--;
		if (this.integer == 1)
			$('no_version').style.display = "";
		
		$('add_version').show();
	},
});

var BugtrackerFormFieldVersions = new BugtrackerFormFieldVersions();

function display_default_version_radio(version_id)
{
	if (document.getElementById('detected_in' + version_id).checked)
		document.getElementById('default_version' + version_id).style.display = ""
	else
	{
		document.getElementById('default_version' + version_id).style.display = "none";
		document.getElementById('default_version' + version_id).checked = "";
	}
}
-->
</script>
<table>
	<thead>
		<tr>
			<th class="column_default">
				{@bugs.labels.default}
			</th>
			<th>
				{@bugs.labels.version_name}
			</th>
			<th>
				{@bugs.labels.fields.version_release_date}
			</th>
			<th class="column_version_detected">
				{@bugs.labels.fields.version_detected}
			</th>
			<th class="column_delete">
				${LangLoader::get_message('delete', 'main')}
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>
				<a href="javascript:BugtrackerFormFieldVersions.add_version();" class="fa fa-plus" title="{@bugs.titles.add_version}" id="add_version"></a>
			</th>
			<th colspan="4" style="text-align:right;">
				# IF C_DISPLAY_DEFAULT_DELETE_BUTTON #<a href="{LINK_DELETE_DEFAULT}" title="${LangLoader::get_message('delete', 'main')}" data-confirmation="{@bugs.actions.confirm.del_default_value}"><i class="fa fa-delete" ></i> {@bugs.labels.del_default_value}</a># ENDIF #
			</th>
		</tr>
	</tfoot>
	<tbody id="versions_list">
		# START versions #
		<tr>
			<td>
				<input type="radio" id="default_version{versions.ID}" name="default_version" value="{versions.ID}"# IF versions.C_IS_DEFAULT # checked="checked"# ENDIF ## IF NOT versions.C_DETECTED_IN # style="display:none"# ENDIF # />
			</td>
			<td>
				<input type="text" maxlength="100" size="40" name="version{versions.ID}" value="{versions.NAME}" />
			</td>
			<td>
				<input type="text" maxlength="10" size="11" id="release_date{versions.ID}" name="release_date{versions.ID}" value="{versions.RELEASE_DATE}" placeholder="{@bugs.labels.fields.version_release_date.explain}" />
			</td> 
			<td>
				<input type="checkbox" id="detected_in{versions.ID}" name="detected_in{versions.ID}" onclick="javascript:display_default_version_radio('{versions.ID}');"# IF versions.C_DETECTED_IN # checked="checked"# ENDIF # />
			</td> 
			<td>
				<a href="{versions.LINK_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="fa fa-delete" data-confirmation="{@bugs.actions.confirm.del_version}"></a>
			</td>
		</tr>
		# END versions #
		<tr id="no_version"# IF C_VERSIONS # style="display:none;"# ENDIF #>
			<td colspan="5">
				{@bugs.notice.no_version}
			</td>
		</tr>
	</tbody>
</table>
