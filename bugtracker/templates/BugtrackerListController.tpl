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
				<a href="{LINK_BUG_ID_TOP}" class="pbt-icon-table-sort-up"></a>
				{@bugs.labels.fields.id}
				<a href="{LINK_BUG_ID_BOTTOM}" class="pbt-icon-table-sort-down"></a>
			</th>
			<th>
				<a href="{LINK_BUG_TITLE_TOP}" class="pbt-icon-table-sort-up"></a>
				{@bugs.labels.fields.title}
				<a href="{LINK_BUG_TITLE_BOTTOM}" class="pbt-icon-table-sort-down"></a>
			</th>
			<th class="column_informations">
				<a href="{LINK_BUG_STATUS_TOP}" class="pbt-icon-table-sort-up"></a>
				{@bugs.titles.informations}
				<a href="{LINK_BUG_STATUS_BOTTOM}" class="pbt-icon-table-sort-down"></a>
			</th>
			<th class="column_date">
				<a href="{LINK_BUG_DATE_TOP}" class="pbt-icon-table-sort-up"></a>
				# IF C_UNSOLVED #{@bugs.labels.detected}# ELSE #{@bugs.labels.fields.fix_date}# ENDIF #
				<a href="{LINK_BUG_DATE_BOTTOM}" class="pbt-icon-table-sort-down"></a>
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
			<td# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
				<a href="{bug.U_LINK}">\#{bug.ID}</a>
			</td>
			<td class="align_left"# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
				{bug.TITLE}
			</td>
			<td class="align_left"# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #> 
				# IF bug.C_PROGRESS #
				{bug.PROGRESS}%
				<div class="progressbar-container">
					<div class="progressbar" style="width:{bug.PROGRESS}%"></div>
				</div>
				# ENDIF #
				<span>{@bugs.labels.fields.status} : {bug.STATUS}</span>
				# IF C_COMMENTS #<br /><a href="{bug.U_COMMENTS}">{bug.NUMBER_COMMENTS} # IF bug.C_MORE_THAN_ONE_COMMENT #${LangLoader::get_message('comments', 'comments-common')}# ELSE #${LangLoader::get_message('comment', 'comments-common')}# ENDIF #</a># ENDIF #
			</td>
			<td # IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
				# IF C_UNSOLVED #${LangLoader::get_message('on', 'main')} # IF C_IS_DATE_FORM_SHORT #{bug.SUBMIT_DATE_SHORT}# ELSE #{bug.SUBMIT_DATE}# ENDIF ## ELSE ## IF bug.C_FIX_DATE ## IF C_IS_DATE_FORM_SHORT #{bug.FIX_DATE_SHORT}# ELSE #{bug.FIX_DATE}# ENDIF ## ELSE #{@bugs.labels.not_yet_fixed}# ENDIF ## ENDIF #<br />
				# IF C_DISPLAY_AUTHOR #${LangLoader::get_message('by', 'main')}: # IF bug.AUTHOR #<a href="{bug.U_AUTHOR_PROFILE}" class="small {bug.AUTHOR_LEVEL_CLASS}" # IF bug.C_AUTHOR_GROUP_COLOR # style="color:{bug.AUTHOR_GROUP_COLOR}" # ENDIF #>{bug.AUTHOR}</a># ELSE #${LangLoader::get_message('guest', 'main')}# ENDIF ## ENDIF #
			</td>
			# IF C_IS_ADMIN #
			<td # IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #> 
				<a href="{bug.U_REOPEN_REJECT}" onclick="javascript:return Confirm('# IF C_UNSOLVED #reject# ELSE #reopen# ENDIF #');" # IF C_UNSOLVED #class="pbt-icon-bugtracker-opened" title="{@bugs.actions.reject}"# ELSE #class="pbt-icon-bugtracker-rejected" title="{@bugs.actions.reopen}"# ENDIF #></a>
				<a href="{bug.U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="pbt-icon-edit"></a>
				<a href="{bug.U_HISTORY}" class="pbt-icon-bugtracker-history" title="{@bugs.actions.history}"></a>
				<a href="{bug.U_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="pbt-icon-delete" data-confirmation="delete-element"></a>
			</td>
			# ENDIF #
		</tr>
		# END bug #
		# IF NOT C_BUGS #
		<tr> 
			<td colspan="{BUGS_COLSPAN}">
				{L_NO_BUG}
			</td>
		</tr>
		# ENDIF #
	</tbody>
</table>

# INCLUDE LEGEND #
