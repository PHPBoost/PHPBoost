<div class="spacer">&nbsp;</div>

# IF C_VERSIONS_AVAILABLE #
	<section class="block">
		<div class="content">
			# INCLUDE SELECT_VERSION #
		</div>
	</section>
	<div class="spacer">&nbsp;</div>

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
					{@labels.fields.fix_date}
					<a href="{LINK_BUG_DATE_BOTTOM}" class="fa fa-table-sort-down"></a>
				</th>
			</tr>
		</thead>
		# IF C_PAGINATION #
		<tfoot>
			<tr>
				<th colspan="4">
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
					{bug.PROGRESS}% 
					<div class="progressbar-container">
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
		</tbody>
	</table>

	# INCLUDE LEGEND #

	# ELSE #
	<div class="message-helper notice">
	<i class="fa fa-notice"></i>
	<div class="message-helper-content"># IF C_STATUS_IN_PROGRESS #{@notice.no_bug_in_progress}# ELSE #{@notice.no_bug_fixed}# ENDIF #</div>
	</div>
	# ENDIF #
# ELSE #
<div class="message-helper notice">
<i class="fa fa-notice"></i>
<div class="message-helper-content">{@notice.no_version_roadmap}</div>
</div>
# ENDIF #
