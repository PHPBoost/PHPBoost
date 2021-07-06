<script>
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
</script>
<div class="bugtracker-filter">
	<a href="#" onclick="toggle_filters_table(); return false;" class="cssmenu-title"><i class="fa fa-filter" aria-hidden="true"></i> # IF C_SEVERAL_FILTERS #{@common.filters}# ELSE #{@common.filter}# ENDIF #</a>
</div>
<div class="responsive-table">
	<table id="table_filters" class="table table-form"# IF NOT C_HAS_SELECTED_FILTERS # style="display: none;"# ENDIF #>
		<thead>
			<tr>
				# IF C_DISPLAY_TYPES #
					<th>
						{@common.type}
					</th>
				# ENDIF #
				# IF C_DISPLAY_CATEGORIES #
					<th>
						{@common.category}
					</th>
				# ENDIF #
				# IF C_DISPLAY_SEVERITIES #
					<th>
						{@bugtracker.severity}
					</th>
				# ENDIF #
					<th>
						{@common.status}
					</th>
				# IF C_DISPLAY_VERSIONS #
					<th>
						{@common.version}
					</th>
				# ENDIF #
				# IF C_DISPLAY_SAVE_BUTTON #
					<th>
						<span aria-label="{@labels.save_filters}"><i class="fa fa-fw fa-save" aria-hidden="true"></i></span>
					</th>
				# ENDIF #
			</tr>
		</thead>
		<tbody>
			<tr>
				# IF C_DISPLAY_TYPES #
					<td>
						# INCLUDE SELECT_TYPE #
					</td>
				# ENDIF #
				# IF C_DISPLAY_CATEGORIES #
					<td>
						# INCLUDE SELECT_CATEGORY #
					</td>
				# ENDIF #
				# IF C_DISPLAY_SEVERITIES #
					<td>
						# INCLUDE SELECT_SEVERITY #
					</td>
				# ENDIF #
				<td>
					# INCLUDE SELECT_STATUS #
				</td>
				# IF C_DISPLAY_VERSIONS #
					<td>
						# INCLUDE SELECT_VERSION #
					</td>
				# ENDIF #
				# IF C_DISPLAY_SAVE_BUTTON #
					<td>
						<a class="offload" href="{LINK_FILTER_SAVE}" aria-label="{@labels.save_filters}"><i class="fa fa-fw fa-save" aria-hidden="true"></i></a>
					</td>
				# ENDIF #
			</tr>
			# IF C_SAVED_FILTERS #
				# START filters #
					<tr id="filter{filters.ID}">
						<td colspan="{FILTERS_NUMBER}" class="controls">
							<a class="offload" href="{filters.LINK_FILTER}">{filters.FILTER}</a>
							<a href="#" aria-label="{@common.delete}" onclick="delete_filter('{filters.ID}'); return false;"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
						</td>
					</tr>
				# END filters #
			# ENDIF #
		</tbody>
		<tfoot>
			<tr>
				<td colspan="{FILTERS_NUMBER}">{@bugtracker.items.number} : {BUGS_NUMBER}</td>
			</tr>
		</tfoot>
	</table>
</div>
