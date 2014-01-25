<div class="spacer">&nbsp;</div>

# IF C_VERSIONS_AVAILABLE #
<section class="block">
	<div class="content">
		# INCLUDE SELECT_VERSION #
	</div>
</section>
<div class="spacer">&nbsp;</div>

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
				{@bugs.labels.fields.fix_date}
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
				# IF bug.C_FIX_DATE ## IF C_IS_DATE_FORM_SHORT #{bug.FIX_DATE_SHORT}# ELSE #{bug.FIX_DATE}# ENDIF ## ELSE #{@bugs.labels.not_yet_fixed}# ENDIF #
			</td>
		</tr>
		# END bug #
		# IF NOT C_BUGS #
		<tr> 
			<td colspan="4">
				# IF C_STATUS_IN_PROGRESS #{@bugs.notice.no_bug_in_progress}# ELSE #{@bugs.notice.no_bug_fixed}# ENDIF #
			</td>
		</tr>
		# ENDIF #
	</tbody>
</table>

# INCLUDE LEGEND #

# ELSE #
<div class="message-helper notice">
<i class="fa fa-notice"></i>
<div class="message-helper-content">{@bugs.notice.no_version_roadmap}</div>
</div>
# ENDIF #
