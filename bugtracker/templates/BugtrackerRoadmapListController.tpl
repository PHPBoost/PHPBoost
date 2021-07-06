# IF C_VERSIONS_AVAILABLE #
	# INCLUDE SELECT_VERSION #

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
						<th class="col-30P">
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
							{@bugtracker.solved.date}
							<span class="html-table-header-sortable">
								<a class="offload" href="{LINK_BUG_DATE_BOTTOM}" aria-label="{@common.sort.desc}"><i class="fa fa-caret-down" aria-hidden="true"></i></a>
							</span>
						</th>
					</tr>
				</thead>
				<tbody>
					# START bug #
						<tr>
							<td # IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
								<a class="offload" href="{bug.U_LINK}"># IF bug.C_FIXED #<s>\#{bug.ID}</s># ELSE #\#{bug.ID}# ENDIF #</a>
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
										<div class="progressbar-infos">{bug.PROGRESS}% </div>
										<div class="progressbar" style="width:{bug.PROGRESS}%"></div>
									</div>
								# ENDIF #
								<a class="offload" href="{bug.U_COMMENTS}">{bug.COMMENTS_NUMBER} # IF bug.C_MORE_THAN_ONE_COMMENT #{@common.comments}# ELSE #{@common.comment}# ENDIF #</a>
							</td>
							<td # IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
								# IF bug.C_FIX_DATE #{bug.FIX_DATE_FULL}# ELSE #{@bugtracker.not.solved}# ENDIF #
							</td>
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
# ELSE #
	<div class="message-helper bgc notice">{@bugtracker.notice.version}</div>
# ENDIF #
