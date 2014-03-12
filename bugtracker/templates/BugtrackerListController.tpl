# INCLUDE FILTER_LIST #

# IF C_BUGS #
	<table>
		<thead>
			<tr>
				<th>
					<a href="{LINK_BUG_ID_TOP}" class="fa fa-table-sort-up"></a>
					{@labels.fields.id}
					<a href="{LINK_BUG_ID_BOTTOM}" class="fa fa-table-sort-down"></a>
				</th>
				<th class="title_column">
					<a href="{LINK_BUG_TITLE_TOP}" class="fa fa-table-sort-up"></a>
					{@labels.fields.title}
					<a href="{LINK_BUG_TITLE_BOTTOM}" class="fa fa-table-sort-down"></a>
				</th>
				# IF C_DISPLAY_TYPE_COLUMN #
				<th>
					{@labels.fields.type}
				</th>
				# ENDIF #
				# IF C_DISPLAY_CATEGORY_COLUMN #
				<th>
					{@labels.fields.category}
				</th>
				# ENDIF #
				# IF C_DISPLAY_PRIORITY_COLUMN #
				<th>
					{@labels.fields.priority}
				</th>
				# ENDIF #
				# IF C_DISPLAY_DETECTED_IN_COLUMN #
				<th>
					{@labels.detected_in}
				</th>
				# ENDIF #
				<th>
					<a href="{LINK_BUG_STATUS_TOP}" class="fa fa-table-sort-up"></a>
					{@titles.informations}
					<a href="{LINK_BUG_STATUS_BOTTOM}" class="fa fa-table-sort-down"></a>
				</th>
				<th>
					<a href="{LINK_BUG_DATE_TOP}" class="fa fa-table-sort-up"></a>
					# IF C_UNSOLVED #{@labels.detected}# ELSE #{@labels.fields.fix_date}# ENDIF #
					<a href="{LINK_BUG_DATE_BOTTOM}" class="fa fa-table-sort-down"></a>
				</th>
				# IF C_IS_ADMIN #
				<th>
					{@actions}
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
				<td class="left"# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
					{bug.TITLE}
				</td>
				# IF C_DISPLAY_TYPE_COLUMN #
				<td# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
					{bug.TYPE}
				</td>
				# ENDIF #
				# IF C_DISPLAY_CATEGORY_COLUMN #
				<td# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
					{bug.CATEGORY}
				</td>
				# ENDIF #
				# IF C_DISPLAY_PRIORITY_COLUMN #
				<td# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
					{bug.PRIORITY}
				</td>
				# ENDIF #
				# IF C_DISPLAY_DETECTED_IN_COLUMN #
				<td# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
					{bug.DETECTED_IN}
				</td>
				# ENDIF #
				<td class="left"# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #> 
					<span>{@labels.fields.status} : {bug.STATUS}</span>
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
					# IF C_UNSOLVED #${LangLoader::get_message('on', 'main')} {bug.SUBMIT_DATE}# ELSE ## IF bug.C_FIX_DATE #{bug.FIX_DATE}# ELSE #{@labels.not_yet_fixed}# ENDIF ## ENDIF #
					<div class="spacer"></div>
					# IF C_DISPLAY_AUTHOR #${LangLoader::get_message('by', 'main')} # IF bug.C_AUTHOR_EXIST #<a href="{bug.U_AUTHOR_PROFILE}" class="{bug.AUTHOR_LEVEL_CLASS}" # IF bug.C_AUTHOR_GROUP_COLOR # style="color:{bug.AUTHOR_GROUP_COLOR}" # ENDIF #>{bug.AUTHOR}</a># ELSE #{bug.AUTHOR}# ENDIF ## ENDIF #
				</td>
				# IF C_IS_ADMIN #
				<td # IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
					<a href="{bug.U_CHANGE_STATUS}" class="fa fa-gears" title="{@actions.change_status}"></a>
					<a href="{bug.U_HISTORY}" class="fa fa-info" title="{@actions.history}"></a>
					<a href="{bug.U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="fa fa-edit"></a>
					<a href="{bug.U_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="fa fa-delete"></a>
				</td>
				# ENDIF #
			</tr>
			# END bug #
		</tbody>
	</table>

	# INCLUDE LEGEND #
# ELSE #
<div class="spacer">&nbsp;</div>

<div class="notice">{L_NO_BUG}</div>
# ENDIF #
