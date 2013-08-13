<script type="text/javascript">
<!--
function Confirm_del_filter() {
	return confirm("{@bugs.actions.confirm.del_filter}");
}
-->
</script>

<table class="module_table">
	<tr>
		<th colspan="{FILTERS_NUMBER}">
			{L_FILTERS}
		</th>
	</tr>
	<tr class="text_center">
		# IF C_DISPLAY_TYPES #
		<th class="filter_title">
			{@bugs.labels.fields.type}
		</th>
		# ENDIF #
		# IF C_DISPLAY_CATEGORIES #
		<th class="filter_title">
			{@bugs.labels.fields.category}
		</th>
		# ENDIF #
		# IF C_DISPLAY_SEVERITIES #
		<th class="filter_title">
			{@bugs.labels.fields.severity}
		</th>
		# ENDIF #
		<th class="filter_title">
			{@bugs.labels.fields.status}
		</th>
		# IF C_DISPLAY_VERSIONS #
		<th class="filter_title">
			{@bugs.labels.fields.version}
		</th>
		# ENDIF #
		# IF C_DISPLAY_SAVE_BUTTON #
		<th>
		</th>
		# ENDIF #
	</tr>
	<tr>
		# IF C_DISPLAY_TYPES #
		<td class="row2 filter">
			# INCLUDE SELECT_TYPE #
		</td>
		# ENDIF #
		# IF C_DISPLAY_CATEGORIES #
		<td class="row2 filter">
			# INCLUDE SELECT_CATEGORY #
		</td>
		# ENDIF #
		# IF C_DISPLAY_SEVERITIES #
		<td class="row2 filter">
			# INCLUDE SELECT_SEVERITY #
		</td>
		# ENDIF #
		<td class="row2 filter">
			# INCLUDE SELECT_STATUS #
		</td>
		# IF C_DISPLAY_VERSIONS #
		<td class="row2 filter">
			# INCLUDE SELECT_VERSION #
		</td>
		# ENDIF #
		# IF C_DISPLAY_SAVE_BUTTON #
		<td class="row2 save_filter">
			<a href="{LINK_FILTER_SAVE}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/files_mini.png" alt="{@bugs.labels.save_filters}" title="{@bugs.labels.save_filters}" /></a>
		</td>
		# ENDIF #
	</tr>
	# IF C_SAVED_FILTERS #
	# START filters #
	<tr>
		<td colspan="{FILTERS_NUMBER}" class="saved_filter">
			<a href="{filters.LINK_FILTER_DELETE}" onclick="javascript:return Confirm_del_filter();"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a> <a href="{filters.LINK_FILTER}">{filters.FILTER}</a>
		</td>
	</tr>
	# END filters #
	# ENDIF #
	# IF BUGS_NUMBER #
	<tr>
		<td colspan="{FILTERS_NUMBER}" class="filter_nbr_bugs">{@bugs.labels.number} # IF C_FILTER #{@bugs.labels.matching_selected_filter} # ELSE ## IF C_FILTERS #{@bugs.labels.matching_selected_filters} # ENDIF ## ENDIF #: {BUGS_NUMBER}</td>
	</tr>
	# ENDIF #
</table>

<div class="spacer">&nbsp;</div>