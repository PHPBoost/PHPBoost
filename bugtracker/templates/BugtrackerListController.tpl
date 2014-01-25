# INCLUDE FILTER_LIST #

<table>
	<thead>
		<tr>
			<th class="column_id">
				<a href="{LINK_BUG_ID_TOP}" class="fa fa-table-sort-up"></a>
				{@bugs.labels.fields.id}
				<a href="{LINK_BUG_ID_BOTTOM}" class="fa fa-table-sort-down"></a>
			</th>
			<th>
				<a href="{LINK_BUG_TITLE_TOP}" class="fa fa-table-sort-up"></a>
				{@bugs.labels.fields.title}
				<a href="{LINK_BUG_TITLE_BOTTOM}" class="fa fa-table-sort-down"></a>
			</th>
			<th class="column_informations">
				<a href="{LINK_BUG_STATUS_TOP}" class="fa fa-table-sort-up"></a>
				{@bugs.titles.informations}
				<a href="{LINK_BUG_STATUS_BOTTOM}" class="fa fa-table-sort-down"></a>
			</th>
			<th class="column_date">
				<a href="{LINK_BUG_DATE_TOP}" class="fa fa-table-sort-up"></a>
				# IF C_UNSOLVED #{@bugs.labels.detected}# ELSE #{@bugs.labels.fields.fix_date}# ENDIF #
				<a href="{LINK_BUG_DATE_BOTTOM}" class="fa fa-table-sort-down"></a>
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
			<td class="align-left"# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
				{bug.TITLE}
			</td>
			<td class="align-left"# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #> 
				<span>{@bugs.labels.fields.status} : {bug.STATUS}</span>
				<div class="spacer"></div>
				# IF bug.C_PROGRESS #
				{bug.PROGRESS}%
				<div class="progressbar-container">
					<div class="progressbar" style="width:{bug.PROGRESS}%"></div>
				</div>
				# ENDIF #
				<a href="{bug.U_COMMENTS}">{bug.NUMBER_COMMENTS} # IF bug.C_MORE_THAN_ONE_COMMENT #${LangLoader::get_message('comments', 'comments-common')}# ELSE #${LangLoader::get_message('comment', 'comments-common')}# ENDIF #</a>
			</td>
			<td # IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
				# IF C_UNSOLVED #${LangLoader::get_message('on', 'main')} # IF C_IS_DATE_FORM_SHORT #{bug.SUBMIT_DATE_SHORT}# ELSE #{bug.SUBMIT_DATE}# ENDIF ## ELSE ## IF bug.C_FIX_DATE ## IF C_IS_DATE_FORM_SHORT #{bug.FIX_DATE_SHORT}# ELSE #{bug.FIX_DATE}# ENDIF ## ELSE #{@bugs.labels.not_yet_fixed}# ENDIF ## ENDIF #
				<div class="spacer"></div>
				# IF C_DISPLAY_AUTHOR #${LangLoader::get_message('by', 'main')}: # IF bug.AUTHOR #<a href="{bug.U_AUTHOR_PROFILE}" class="small {bug.AUTHOR_LEVEL_CLASS}" # IF bug.C_AUTHOR_GROUP_COLOR # style="color:{bug.AUTHOR_GROUP_COLOR}" # ENDIF #>{bug.AUTHOR}</a># ELSE #${LangLoader::get_message('guest', 'main')}# ENDIF ## ENDIF #
			</td>
			# IF C_IS_ADMIN #
			<td # IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
				# IF C_UNSOLVED #
				<a href="{bug.U_FIX}" class="fa fa-wrench" title="{@bugs.actions.fix}"></a>
				<a href="{bug.U_ASSIGN}" class="fa fa-user" title="{@bugs.actions.assign}"></a>
				<a href="{bug.U_REOPEN_REJECT}" class="fa fa-eye-slash" title="{@bugs.actions.reject}"></a>
				# ELSE #
				<a href="{bug.U_REOPEN_REJECT}" class="fa fa-eye" title="{@bugs.actions.reopen}"></a>
				# ENDIF #
				<a href="{bug.U_HISTORY}" class="fa fa-info" title="{@bugs.actions.history}"></a>
				<a href="{bug.U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="fa fa-edit"></a>
				<a href="{bug.U_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="fa fa-delete"></a>
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
