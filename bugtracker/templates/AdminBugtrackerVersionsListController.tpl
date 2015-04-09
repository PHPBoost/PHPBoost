<script src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/calendar.js"></script>
<script>
<!--
var BugtrackerFormFieldVersions = function(){
	this.integer = {NEXT_ID};
	this.max_input = {MAX_INPUT};
};

BugtrackerFormFieldVersions.prototype = {
	add_version : function () {
		if (this.integer <= this.max_input) {
			var id = this.integer;
			
			jQuery('<tr/>', {id : 'tr_' + id}).appendTo('#versions_list');
			
			jQuery('<td/>', {id : 'td1_' + id}).appendTo('#tr_' + id);
			
			jQuery('<input/> ', {type : 'radio', id : 'default_version' + id, name : 'default_version', value : id, style : 'display:none;'}).appendTo('#td1_' + id);
			
			jQuery('<td/>', {id : 'td2_' + id}).appendTo('#tr_' + id);
			
			jQuery('<input/> ', {type : 'text', id : 'version_' + id, name : 'version_' + id, class : 'field-large', maxlength : 100, placeholder : ${escapejs(LangLoader::get_message('form.name', 'common'))}}).appendTo('#td2_' + id);
			
			jQuery('<td/>', {id : 'td3_' + id}).appendTo('#tr_' + id);
			
			jQuery('<input/> ', {type : 'text', id : 'release_date_' + id, name : 'release_date_' + id, maxlength : 10, placeholder : ${escapejs(LangLoader::get_message('date_format', 'date-common'))}}).appendTo('#td3_' + id).attr('size', '11');
			jQuery('#release_date_' + id).after(' ');
			
			jQuery('<div/> ', {id : 'calendar' + id, style : 'position:absolute;z-index:100;display:none;'}).appendTo('#td3_' + id);
			
			jQuery('<div/> ', {id : 'release_date' + id + '_date', class : 'calendar-block', onmouseover : 'hide_calendar(' + id + ', 1);', onmouseout : 'hide_calendar(' + id + ', 0);'}).appendTo('#calendar' + id);
			
			jQuery('<a/> ', {style : 'cursor:pointer;', onclick : "xmlhttprequest_calendar('release_date" + id + "_date', '?input_field=release_date_" + id + "&amp;field=release_date" + id + "_date&amp;d={DAY}&amp;m={MONTH}&amp;y={YEAR}');display_calendar(" + id + ");", onmouseover : 'hide_calendar(' + id + ', 1);', onmouseout : 'hide_calendar(' + id + ', 0);'}).html('<i class="fa fa-calendar"></i>').appendTo('#td3_' + id);
			
			jQuery('<td/>', {id : 'td4_' + id}).appendTo('#tr_' + id);
			
			jQuery('<input/> ', {type : 'checkbox', id : 'detected_in' + id, name : 'detected_in' + id, onclick : 'javascript:display_default_version_radio(' + id + ');'}).appendTo('#td4_' + id);
			
			jQuery('<td/>', {id : 'td5_' + id}).appendTo('#tr_' + id);
			
			jQuery('<a/> ', {id : 'delete_' + id, href : 'javascript:BugtrackerFormFieldVersions.delete_version(' + id + ');', title : ${escapejs(LangLoader::get_message('delete', 'common'))}}).html('<i class="fa fa-delete"></i>').appendTo('#td5_' + id);
			
			this.integer++;
		}
		if (this.integer == this.max_input) {
			jQuery('#add-version').hide();
		}
		if (this.integer) {
			jQuery('#no-version').hide();
		}
	},
	delete_version : function (id) {
		jQuery('#tr_' + id).remove();
		this.integer--;
		if (this.integer >= 1)
			jQuery('#no-version').show();
		else
			jQuery('#no-version').show();
		
		jQuery('#add-version').show();
	}
};

var BugtrackerFormFieldVersions = new BugtrackerFormFieldVersions();

function display_default_version_radio(version_id)
{
	if (jQuery('#detected_in' + version_id).prop('checked'))
		jQuery('#default_version' + version_id).show();
	else
	{
		jQuery('#default_version' + version_id).prop('checked', false);
		jQuery('#default_version' + version_id).hide();
	}
}
-->
</script>

<table>
	<thead>
		<tr>
			<th class="small-column">
				{@labels.default}
			</th>
			<th>
				{@labels.version_name}
			</th>
			<th>
				{@labels.fields.version_release_date}
			</th>
			<th class="small-column">
				{@labels.fields.version_detected}
			</th>
			<th class="small-column">
				${LangLoader::get_message('delete', 'common')}
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>
				<a href="javascript:BugtrackerFormFieldVersions.add_version();" title="{@titles.add_version}" id="add-version"><i class="fa fa-plus"></i></a>
			</th>
			<th colspan="4" class="right">
				# IF C_DISPLAY_DEFAULT_DELETE_BUTTON #<a href="{LINK_DELETE_DEFAULT}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="{@actions.confirm.del_default_value}"><i class="fa fa-delete"></i> {@labels.del_default_value}</a># ENDIF #
			</th>
		</tr>
	</tfoot>
	<tbody id="versions_list">
		<tr id="no-version"# IF C_VERSIONS # style="display:none;"# ENDIF #>
			<td colspan="5">
				${LangLoader::get_message('no_item_now', 'common')}
			</td>
		</tr>
		# START versions #
		<tr>
			<td>
				<input type="radio" id="default_version{versions.ID}" name="default_version" value="{versions.ID}"# IF versions.C_IS_DEFAULT # checked="checked"# ENDIF ## IF NOT versions.C_DETECTED_IN # style="display:none"# ENDIF # />
			</td>
			<td>
				<input type="text" maxlength="100" class="field-large" name="version{versions.ID}" value="{versions.NAME}" />
			</td>
			<td>
				<input type="text" maxlength="10" size="11" id="release_date{versions.ID}" name="release_date{versions.ID}" value="{versions.RELEASE_DATE}" placeholder="${LangLoader::get_message('date_format', 'date-common')}" />
				<div style="position:absolute;z-index:100;display:none;" id="calendar{versions.ID}">
					<div id="release_date{versions.ID}_date" class="calendar-block" onmouseover="hide_calendar({versions.ID}, 1);" onmouseout="hide_calendar({versions.ID}, 0);">
					</div>
				</div>
				<a onclick="xmlhttprequest_calendar('release_date{versions.ID}_date', '?input_field=release_date{versions.ID}&amp;field=release_date{versions.ID}_date&amp;d={versions.DAY}&amp;m={versions.MONTH}&amp;y={versions.YEAR}');display_calendar({versions.ID});" onmouseover="hide_calendar({versions.ID}, 1);" onmouseout="hide_calendar({versions.ID}, 0);" style="cursor:pointer;"><i class="fa fa-calendar"></i></a>
			</td> 
			<td>
				<input type="checkbox" id="detected_in{versions.ID}" name="detected_in{versions.ID}" onclick="javascript:display_default_version_radio('{versions.ID}');"# IF versions.C_DETECTED_IN # checked="checked"# ENDIF # />
			</td> 
			<td>
				<a href="{versions.LINK_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
			</td>
		</tr>
		# END versions #
	</tbody>
</table>
