# INCLUDE MSG #
# INCLUDE FILTER_LIST #

# IF C_BUGS #
	<table class="table">
		<thead>
			<tr>
				<th>
					<a href="{LINK_BUG_ID_TOP}" aria-label="${LangLoader::get_message('sort.asc', 'common')}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
					{@labels.fields.id}
					<a href="{LINK_BUG_ID_BOTTOM}" aria-label="${LangLoader::get_message('sort.desc', 'common')}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
				</th>
				<th class="title-column">
					<a href="{LINK_BUG_TITLE_TOP}" aria-label="${LangLoader::get_message('sort.asc', 'common')}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
					${LangLoader::get_message('form.title', 'common')}
					<a href="{LINK_BUG_TITLE_BOTTOM}" aria-label="${LangLoader::get_message('sort.desc', 'common')}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
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
					<a href="{LINK_BUG_STATUS_TOP}" aria-label="${LangLoader::get_message('sort.asc', 'common')}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
					{@titles.informations}
					<a href="{LINK_BUG_STATUS_BOTTOM}" aria-label="${LangLoader::get_message('sort.desc', 'common')}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
				</th>
				<th>
					<a href="{LINK_BUG_DATE_TOP}" aria-label="${LangLoader::get_message('sort.asc', 'common')}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
					# IF C_UNSOLVED #{@labels.detected}# ELSE #{@labels.fields.fix_date}# ENDIF #
					<a href="{LINK_BUG_DATE_BOTTOM}" aria-label="${LangLoader::get_message('sort.desc', 'common')}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
				</th>
				# IF C_IS_ADMIN #
				<th>
					{@actions}
				</th>
				# ENDIF #
			</tr>
		</thead>
		<tbody>
			# START bug #
			<tr>
				<td# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
					<a href="{bug.U_LINK}">\#{bug.ID}</a>
				</td>
				<td class="align-left"# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
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
				<td class="align-left"# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
					<span>{@labels.fields.status} : {bug.STATUS}</span>
					<div class="spacer"></div>
					# IF bug.C_PROGRESS #
					<div class="progressbar-container">
						<div class="progressbar-infos">{bug.PROGRESS}%</div>
						<div class="progressbar" style="width:{bug.PROGRESS}%"></div>
					</div>
					# ENDIF #
					<a href="{bug.U_COMMENTS}">{bug.COMMENTS_NUMBER} # IF bug.C_MORE_THAN_ONE_COMMENT #${LangLoader::get_message('comments', 'comments-common')}# ELSE #${LangLoader::get_message('comment', 'comments-common')}# ENDIF #</a>
				</td>
				<td # IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
					# IF C_UNSOLVED #${LangLoader::get_message('the', 'common')} {bug.SUBMIT_DATE_FULL}# ELSE ## IF bug.C_FIX_DATE #{bug.FIX_DATE_FULL}# ELSE #{@labels.not_yet_fixed}# ENDIF ## ENDIF #
					<div class="spacer"></div>
					# IF C_DISPLAY_AUTHOR #${LangLoader::get_message('by', 'common')} # IF bug.C_AUTHOR_EXIST #<a href="{bug.U_AUTHOR_PROFILE}" class="{bug.AUTHOR_LEVEL_CLASS}" # IF bug.C_AUTHOR_GROUP_COLOR # style="color:{bug.AUTHOR_GROUP_COLOR}" # ENDIF #>{bug.AUTHOR}</a># ELSE #{bug.AUTHOR}# ENDIF ## ENDIF #
				</td>
				# IF C_IS_ADMIN #
				<td class="bugtracker-actions" # IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
					<a href="{bug.U_CHANGE_STATUS}" aria-label="{@actions.change_status}"><i class="fa fa-cogs" aria-hidden="true"></i></a>
					<a href="{bug.U_HISTORY}" aria-label="{@actions.history}"><i class="fa fa-history" aria-hidden="true"></i></a>
					<a href="{bug.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a>
					<a href="{bug.U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
				</td>
				# ENDIF #
			</tr>
			# END bug #
			<tr>
				<td colspan="{BUGS_COLSPAN}" class="legend-line"># INCLUDE LEGEND #</td>
			</tr>
		</tbody>
		# IF C_PAGINATION #
		<tfoot>
			<tr>
				<td colspan="{BUGS_COLSPAN}">
					# INCLUDE PAGINATION #
				</td>
			</tr>
		</tfoot>
		# ENDIF #
	</table>
# ELSE #
<div class="message-helper bgc notice">{L_NO_BUG}</div>
# ENDIF #
