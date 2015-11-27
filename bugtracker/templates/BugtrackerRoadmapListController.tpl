<div class="spacer"></div>

# IF C_VERSIONS_AVAILABLE #
	<section class="block">
		<div class="content">
			# INCLUDE SELECT_VERSION #
		</div>
	</section>
	<div class="spacer"></div>

	# IF C_BUGS #
	<table id="table">
		<thead>
			<tr>
				<th>
					<a href="{LINK_BUG_ID_TOP}"><i class="fa fa-table-sort-up"></i></a>
					{@labels.fields.id}
					<a href="{LINK_BUG_ID_BOTTOM}"><i class="fa fa-table-sort-down"></i></a>
				</th>
				<th class="title-column">
					<a href="{LINK_BUG_TITLE_TOP}"><i class="fa fa-table-sort-up"></i></a>
					${LangLoader::get_message('form.title', 'common')}
					<a href="{LINK_BUG_TITLE_BOTTOM}"><i class="fa fa-table-sort-down"></i></a>
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
					<a href="{LINK_BUG_STATUS_TOP}"><i class="fa fa-table-sort-up"></i></a>
					{@titles.informations}
					<a href="{LINK_BUG_STATUS_BOTTOM}"><i class="fa fa-table-sort-down"></i></a>
				</th>
				<th>
					<a href="{LINK_BUG_DATE_TOP}"><i class="fa fa-table-sort-up"></i></a>
					{@labels.fields.fix_date}
					<a href="{LINK_BUG_DATE_BOTTOM}"><i class="fa fa-table-sort-down"></i></a>
				</th>
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
					<a href="{bug.U_LINK}" title="{bug.STATUS}"># IF bug.C_FIXED #<s>\#{bug.ID}</s># ELSE #\#{bug.ID}# ENDIF #</a>
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
					<div class="progressbar-container">
						<div class="progressbar-infos">{bug.PROGRESS}% </div>
						<div class="progressbar" style="width:{bug.PROGRESS}%"></div>
					</div>
					# ENDIF #
					<a href="{bug.U_COMMENTS}">{bug.NUMBER_COMMENTS} # IF bug.C_MORE_THAN_ONE_COMMENT #${LangLoader::get_message('comments', 'comments-common')}# ELSE #${LangLoader::get_message('comment', 'comments-common')}# ENDIF #</a>
				</td>
				<td # IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
					# IF bug.C_FIX_DATE #{bug.FIX_DATE}# ELSE #{@labels.not_yet_fixed}# ENDIF #
				</td>
			</tr>
			# END bug #
			<tr>
				<td colspan="{BUGS_COLSPAN}" class="legend-line"># INCLUDE LEGEND #</td>
			</tr>
		</tbody>
	</table>

	# ELSE #
	<div class="notice"># IF C_STATUS_IN_PROGRESS #{@notice.no_bug_in_progress}# ELSE #{@notice.no_bug_fixed}# ENDIF #</div>
	# ENDIF #
# ELSE #
<div class="notice">{@notice.no_version_roadmap}</div>
# ENDIF #
