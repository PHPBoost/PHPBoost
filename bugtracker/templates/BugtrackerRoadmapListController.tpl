<div class="spacer"></div>

# IF C_VERSIONS_AVAILABLE #
	<section class="block">
		<div class="content">
			# INCLUDE SELECT_VERSION #
		</div>
	</section>
	<div class="spacer"></div>

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
					{@labels.fields.fix_date}
					<a href="{LINK_BUG_DATE_BOTTOM}" aria-label="${LangLoader::get_message('sort.desc', 'common')}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
				</th>
			</tr>
		</thead>
		<tbody>
			# START bug #
			<tr>
				<td # IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
					<a href="{bug.U_LINK}"># IF bug.C_FIXED #<s>\#{bug.ID}</s># ELSE #\#{bug.ID}# ENDIF #</a>
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
						<div class="progressbar-infos">{bug.PROGRESS}% </div>
						<div class="progressbar" style="width:{bug.PROGRESS}%"></div>
					</div>
					# ENDIF #
					<a href="{bug.U_COMMENTS}">{bug.COMMENTS_NUMBER} # IF bug.C_MORE_THAN_ONE_COMMENT #${LangLoader::get_message('comments', 'comments-common')}# ELSE #${LangLoader::get_message('comment', 'comments-common')}# ENDIF #</a>
				</td>
				<td # IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
					# IF bug.C_FIX_DATE #{bug.FIX_DATE_FULL}# ELSE #{@labels.not_yet_fixed}# ENDIF #
				</td>
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
	<div class="message-helper bgc notice"># IF C_STATUS_IN_PROGRESS #{@notice.no_bug_in_progress}# ELSE #{@notice.no_bug_fixed}# ENDIF #</div>
	# ENDIF #
# ELSE #
<div class="message-helper bgc notice">{@notice.no_version_roadmap}</div>
# ENDIF #
