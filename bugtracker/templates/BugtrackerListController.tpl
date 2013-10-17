# INCLUDE PROGRESS_BAR #

# INCLUDE FILTER_LIST #

<script type="text/javascript">
<!--
function Confirm(action) {
	if (action == 'reopen') {
		return confirm("{@bugs.actions.confirm.reopen_bug}");
	}
	else if (action == 'reject') {
		return confirm("{@bugs.actions.confirm.reject_bug}");
	}
}
-->
</script>
<table>
	<thead>
		<tr>
			<th class="column_id">
				<a href="{LINK_BUG_ID_TOP}" class="sort-up"></a>
				{@bugs.labels.fields.id}
				<a href="{LINK_BUG_ID_BOTTOM}" class="sort-down"></a>
			</th>
			<th>
				<a href="{LINK_BUG_TITLE_TOP}" class="sort-up"></a>
				{@bugs.labels.fields.title}
				<a href="{LINK_BUG_TITLE_BOTTOM}" class="sort-down"></a>
			</th>
			<th class="column_informations">
				<a href="{LINK_BUG_STATUS_TOP}" class="sort-up"></a>
				{@bugs.titles.informations}
				<a href="{LINK_BUG_STATUS_BOTTOM}" class="sort-down"></a>
			</th>
			<th class="column_date">
				<a href="{LINK_BUG_DATE_TOP}" class="sort-up"></a>
				{L_DATE}
				<a href="{LINK_BUG_DATE_BOTTOM}" class="sort-down"></a>
			</th>
			# IF C_IS_ADMIN #
			<th class="column_admin">
				{@bugs.actions}
			</th>
			# ENDIF #
		</tr>
	</thead>
	# IF C_PAGINATION #
		<tfoot>
			<tr>
				<th colspan="{BUGS_COLSPAN}">
					# INCLUDE PAGINATION #
				</th>
			</tr>
		</tfoot>
	# ENDIF #
	<tbody>
	# START bug #
	<tr> 
		<td # IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
			<a href="{bug.LINK_BUG_DETAIL}">\#{bug.ID}</a>
		</td>
		<td class="align_left"# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
			{bug.TITLE}
		</td>
		<td class="align_left"# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #> 
			# IF bug.C_PROGRESS #<span class="progressBar progress{bug.PROGRESS}">{bug.PROGRESS}%</span><br/># ENDIF #
			<span>{bug.STATUS}</span>
			# IF C_COMMENTS #<br /><a href="{bug.LINK_BUG_COMMENTS}">{bug.NUMBER_COMMENTS} {bug.L_COMMENTS}</a># ENDIF #
		</td>
		<td # IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
			# IF C_UNSOLVED #${LangLoader::get_message('on', 'main')} # ENDIF #{bug.DATE}<br />
			# IF C_DISPLAY_AUTHOR #${LangLoader::get_message('by', 'main')}: # IF bug.AUTHOR #<a href="{bug.LINK_AUTHOR_PROFILE}" class="small {bug.AUTHOR_LEVEL_CLASS}" # IF bug.C_AUTHOR_GROUP_COLOR # style="color:{bug.AUTHOR_GROUP_COLOR}" # ENDIF #>{bug.AUTHOR}</a># ELSE #${LangLoader::get_message('guest', 'main')}# ENDIF ## ENDIF #
		</td>
		# IF C_IS_ADMIN #
		<td # IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #> 
			<a href="{bug.LINK_BUG_REOPEN_REJECT}" onclick="javascript:return Confirm(${escapejs(REOPEN_REJECT_CONFIRM)});" class="pbt-icon-bugtracker-opened" title="{@bugs.actions.reopen}"></a>
			<a href="{bug.LINK_BUG_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="edit"></a>
			<a href="{bug.LINK_BUG_HISTORY}" class="pbt-icon-bugtracker-history" title="{@bugs.actions.history}"></a>
			<a href="{bug.LINK_BUG_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="delete"></a>
		</td>
		# ENDIF #
	</tr>
	# END bug #
	</tbody>
	# IF NOT C_BUGS #
	<tr> 
		<td colspan="{BUGS_COLSPAN}">
			{L_NO_BUG}
		</td>
	</tr>
	# ENDIF #
</table>

# INCLUDE LEGEND #

