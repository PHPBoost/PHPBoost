<script>
<!--
function delete_filter(id) {
	if (confirm(${escapejs(@actions.confirm.del_filter)})) {
		jQuery.ajax({
			url: '${relative_url(BugtrackerUrlBuilder::delete_filter())}',
			type: "post",
			data: {'id' : id, 'token' : '{TOKEN}'},
			success: function(returnData){
				if (returnData.code > 0) {
					jQuery("#filter" + returnData.code).remove();
				}
			}
		});
	}
}

function toggle_filters_table() {
	jQuery("#table_filters").fadeToggle();
}
-->
</script>
<div class="bugtracker-filter">
	<a href="" onclick="toggle_filters_table(); return false;" class="cssmenu-title"><i class="fa fa-filter" aria-hidden="true"></i> {L_FILTERS}</a>
</div>
<table class="table" id="table_filters"# IF NOT C_HAS_SELECTED_FILTERS # style="display: none;"# ENDIF #>
	<thead>
		<tr>
			# IF C_DISPLAY_TYPES #
				<th>
					{@labels.fields.type}
				</th>
			# ENDIF #
			# IF C_DISPLAY_CATEGORIES #
				<th>
					{@labels.fields.category}
				</th>
			# ENDIF #
			# IF C_DISPLAY_SEVERITIES #
				<th>
					{@labels.fields.severity}
				</th>
			# ENDIF #
				<th>
					{@labels.fields.status}
				</th>
			# IF C_DISPLAY_VERSIONS #
				<th>
					{@labels.fields.version}
				</th>
			# ENDIF #
			# IF C_DISPLAY_SAVE_BUTTON #
				<th>
				</th>
			# ENDIF #
		</tr>
	</thead>
	<tbody>
		<tr>
			# IF C_DISPLAY_TYPES #
				<td class="no-separator">
					# INCLUDE SELECT_TYPE #
				</td>
			# ENDIF #
			# IF C_DISPLAY_CATEGORIES #
				<td class="no-separator">
					# INCLUDE SELECT_CATEGORY #
				</td>
			# ENDIF #
			# IF C_DISPLAY_SEVERITIES #
				<td class="no-separator">
					# INCLUDE SELECT_SEVERITY #
				</td>
			# ENDIF #
				<td class="no-separator">
					# INCLUDE SELECT_STATUS #
				</td>
			# IF C_DISPLAY_VERSIONS #
				<td class="no-separator">
					# INCLUDE SELECT_VERSION #
				</td>
			# ENDIF #
			# IF C_DISPLAY_SAVE_BUTTON #
				<td class="no-separator">
					<a href="{LINK_FILTER_SAVE}" aria-label="{@labels.save_filters}"><i class="fa fa-save" aria-hidden="true"></i></a>
				</td>
			# ENDIF #
		</tr>
		# IF C_SAVED_FILTERS #
		# START filters #
			<tr id="filter{filters.ID}">
				<td colspan="{FILTERS_NUMBER}">
					<a href="" aria-label="${LangLoader::get_message('delete', 'common')}" onclick="delete_filter('{filters.ID}'); return false;"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
					<a href="{filters.LINK_FILTER}">{filters.FILTER}</a>
				</td>
			</tr>
		# END filters #
		# ENDIF #
	</tbody>
	<tfoot>
		<tr>
			<td colspan="{FILTERS_NUMBER}">${LangLoader::get_message('items_number', 'common')} : {BUGS_NUMBER}</td>
		</tr>
	</tfoot>
</table>

<div class="spacer"></div>
<script>
	jQuery('#table_filters').basictable();
</script>
