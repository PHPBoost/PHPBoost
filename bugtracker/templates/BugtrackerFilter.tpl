<script type="text/javascript">
<!--
function Confirm_del_filter() {
	return confirm("{@bugs.actions.confirm.del_filter}");
}
-->
</script>

<menu class="dynamic_menu">
	<ul>
		<li>
			<a onclick="Effect.toggle('table_filters', 'appear'); return false;"><i class="icon-filter"></i> Filtres</a> 
		</li>
	</ul>
</menu>
<table id="table_filters" style="display:none;">
	<caption>
		{L_FILTERS}
	</caption>
	<thead>
		<tr>
			# IF C_DISPLAY_TYPES #
			<th>
				{@bugs.labels.fields.type}
			</th>
			# ENDIF #
			# IF C_DISPLAY_CATEGORIES #
			<th>
				{@bugs.labels.fields.category}
			</th>
			# ENDIF #
			# IF C_DISPLAY_SEVERITIES #
			<th>
				{@bugs.labels.fields.severity}
			</th>
			# ENDIF #
			<th>
				{@bugs.labels.fields.status}
			</th>
			# IF C_DISPLAY_VERSIONS #
			<th>
				{@bugs.labels.fields.version}
			</th>
			# ENDIF #
			# IF C_DISPLAY_SAVE_BUTTON #
			<th>
			</th>
			# ENDIF #
		</tr>
	</thead>
	# IF BUGS_NUMBER #
	<tfoot>
		<tr>
			<th colspan="{FILTERS_NUMBER}">{@bugs.labels.number} # IF C_FILTER #{@bugs.labels.matching_selected_filter} # ELSE ## IF C_FILTERS #{@bugs.labels.matching_selected_filters} # ENDIF ## ENDIF #: {BUGS_NUMBER}</th>
		</tr>
	</tfoot>
	# ENDIF #
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
				<a href="{LINK_FILTER_SAVE}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/admin/files_mini.png" alt="{@bugs.labels.save_filters}" title="{@bugs.labels.save_filters}" /></a>
			</td>
			# ENDIF #
		</tr>
		# IF C_SAVED_FILTERS #
		# START filters #
		<tr>
			<td colspan="{FILTERS_NUMBER}">
				<a href="{filters.LINK_FILTER_DELETE}" onclick="javascript:return Confirm_del_filter();"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a> <a href="{filters.LINK_FILTER}">{filters.FILTER}</a>
			</td>
		</tr>
		# END filters #
		# ENDIF #
	</tbody>
</table>

<div class="spacer">&nbsp;</div>