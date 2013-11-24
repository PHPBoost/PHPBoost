<menu class="dynamic_menu">
	<ul>
		<li>
			<a onclick="Effect.toggle('table_filters', 'appear'); return false;"><i class="icon-filter"></i> {L_FILTERS}</a> 
		</li>
	</ul>
</menu>
<table id="table_filters"# IF NOT C_HAS_SELECTED_FILTERS # style="display:none;"# ENDIF #>
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
				<a href="{LINK_FILTER_SAVE}" title="{@bugs.labels.save_filters}" class="icon-save"></a>
			</td>
			# ENDIF #
		</tr>
		# IF C_SAVED_FILTERS #
		# START filters #
		<tr>
			<td colspan="{FILTERS_NUMBER}">
				<a href="{filters.LINK_FILTER_DELETE}" title="{L_DELETE}" class="icon-delete" data-confirmation="{@bugs.actions.confirm.del_filter}"></a> <a href="{filters.LINK_FILTER}">{filters.FILTER}</a>
			</td>
		</tr>
		# END filters #
		# ENDIF #
	</tbody>
</table>

<div class="spacer">&nbsp;</div>