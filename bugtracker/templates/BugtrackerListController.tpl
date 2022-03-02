# INCLUDE MESSAGE_HELPER #
# INCLUDE FILTER_LIST #

# IF C_BUGS #
	<div class="responsive-table">
		<table class="table">
			<thead>
				<tr>
					<th>
						<span class="html-table-header-sortable">
							<a class="offload" href="{LINK_BUG_ID_TOP}" aria-label="{@common.sort.asc}"><i class="fa fa-caret-up" aria-hidden="true"></i></a>
						</span>
						{@common.id}
						<span class="html-table-header-sortable">
							<a class="offload" href="{LINK_BUG_ID_BOTTOM}" aria-label="{@common.sort.desc}"><i class="fa fa-caret-down" aria-hidden="true"></i></a>
						</span>
					</th>
					<th>
						<span class="html-table-header-sortable">
							<a class="offload" href="{LINK_BUG_TITLE_TOP}" aria-label="{@common.sort.asc}"><i class="fa fa-caret-up" aria-hidden="true"></i></a>
						</span>
						{@common.title}
						<span class="html-table-header-sortable">
							<a class="offload" href="{LINK_BUG_TITLE_BOTTOM}" aria-label="{@common.sort.desc}"><i class="fa fa-caret-down" aria-hidden="true"></i></a>
						</span>
					</th>
					# IF C_DISPLAY_TYPE_COLUMN #
						<th>
							{@common.type}
						</th>
					# ENDIF #
					# IF C_DISPLAY_CATEGORY_COLUMN #
						<th>
							{@common.category}
						</th>
					# ENDIF #
					# IF C_DISPLAY_PRIORITY_COLUMN #
						<th>
							{@bugtracker.priority}
						</th>
					# ENDIF #
					# IF C_DISPLAY_DETECTED_IN_COLUMN #
						<th>
							{@bugtracker.detected.in}
						</th>
					# ENDIF #
					<th>
						<span class="html-table-header-sortable">
							<a class="offload" href="{LINK_BUG_STATUS_TOP}" aria-label="{@common.sort.asc}"><i class="fa fa-caret-up" aria-hidden="true"></i></a>
						</span>
						{@common.informations}
						<span class="html-table-header-sortable">
							<a class="offload" href="{LINK_BUG_STATUS_BOTTOM}" aria-label="{@common.sort.desc}"><i class="fa fa-caret-down" aria-hidden="true"></i></a>
						</span>
					</th>
					<th>
						<span class="html-table-header-sortable">
							<a class="offload" href="{LINK_BUG_DATE_TOP}" aria-label="{@common.sort.asc}"><i class="fa fa-caret-up" aria-hidden="true"></i></a>
						</span>
						# IF C_UNSOLVED #{@bugtracker.detected.date}# ELSE #{@bugtracker.solved.date}# ENDIF #
						<span class="html-table-header-sortable">
							<a class="offload" href="{LINK_BUG_DATE_BOTTOM}" aria-label="{@common.sort.desc}"><i class="fa fa-caret-down" aria-hidden="true"></i></a>
						</span>
					</th>
					# IF C_IS_ADMIN #
						<th>
							{@common.moderation}
						</th>
					# ENDIF #
				</tr>
			</thead>
			<tbody>
				# START bug #
				<tr>
					<td# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
						<a class="offload" href="{bug.U_LINK}">\#{bug.ID}</a>
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
						<span class="d-block">{@common.status} : {bug.STATUS}</span>
						# IF bug.C_PROGRESS #
							<div class="progressbar-container" role="progressbar" aria-valuenow="{bug.PROGRESS}" aria-valuemin="0" aria-valuemax="100">
								<div class="progressbar-infos">{bug.PROGRESS}%</div>
								<div class="progressbar" style="width:{bug.PROGRESS}%"></div>
							</div>
						# ENDIF #
						<a class="offload" href="{bug.U_COMMENTS}">{bug.COMMENTS_NUMBER} # IF bug.C_MORE_THAN_ONE_COMMENT #{@common.comments}# ELSE #${@common.comment}# ENDIF #</a>
					</td>
					<td # IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
						# IF C_UNSOLVED #{bug.SUBMIT_DATE_FULL}# ELSE ## IF bug.C_FIX_DATE #{bug.FIX_DATE_FULL}# ELSE #{@bugtracker.not.solved}# ENDIF ## ENDIF #
						<div class="spacer"></div>
						# IF C_DISPLAY_AUTHOR #{@common.by} # IF bug.C_AUTHOR_EXISTS #<a itemprop="author" href="{bug.U_AUTHOR_PROFILE}" class="{bug.AUTHOR_LEVEL_CLASS} offload" # IF bug.C_AUTHOR_GROUP_COLOR # style="color:{bug.AUTHOR_GROUP_COLOR}" # ENDIF #>{bug.AUTHOR}</a># ELSE #{bug.AUTHOR}# ENDIF ## ENDIF #
					</td>
					# IF C_IS_ADMIN #
						<td class="bugtracker-actions controls" # IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
							<a class="offload" href="{bug.U_CHANGE_STATUS}" aria-label="{@bugtracker.change.status}"><i class="fa fa-fw fa-cogs" aria-hidden="true"></i></a>
							<a class="offload" href="{bug.U_HISTORY}" aria-label="{@bugtracker.history}"><i class="fa fa-fw fa-history" aria-hidden="true"></i></a>
							<div class="spacer"></div>
							<a href="{bug.U_EDIT}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
							<a href="{bug.U_DELETE}" aria-label="{@common.delete}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
						</td>
					# ENDIF #
				</tr>
				# END bug #
				<tr>
					<td colspan="{BUGS_COLSPAN}"># INCLUDE LEGEND #</td>
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

	</div>
# ELSE #
	<div class="message-helper bgc notice">{@common.no.item.now}</div>
# ENDIF #
