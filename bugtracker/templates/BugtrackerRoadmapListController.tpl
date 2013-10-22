<section class="block">
	<div class="contents">
		# INCLUDE SELECT_VERSION #
	</div>
</section>
<div class="spacer">&nbsp;</div>

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
				{@bugs.labels.fields.fix_date}
				<a href="{LINK_BUG_DATE_BOTTOM}" class="pbt-icon-table-sort-down"></a>
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
			<td class="align_left"# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
				{bug.TITLE}
			</td>
			<td class="align_left"# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #> 
				# IF bug.C_PROGRESS #<span class="progressBar progress{bug.PROGRESS}">{bug.PROGRESS}%</span><br/># ENDIF #
				<span>{@bugs.labels.fields.status} : {bug.STATUS}</span>
				# IF C_COMMENTS #<br /><a href="{bug.U_COMMENTS}">{bug.NUMBER_COMMENTS} # IF bug.C_MORE_THAN_ONE_COMMENT #${LangLoader::get_message('comments', 'comments-common')}# ELSE #${LangLoader::get_message('comment', 'comments-common')}# ENDIF #</a># ENDIF #
			</td>
			<td # IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
				# IF bug.C_FIX_DATE ## IF C_IS_DATE_FORM_SHORT #{bug.FIX_DATE_SHORT}# ELSE #{bug.FIX_DATE}# ENDIF ## ELSE #{@bugs.labels.not_yet_fixed}# ENDIF #
			</td>
		</tr>
		# END bug #
		# IF NOT C_BUGS #
		<tr> 
			<td colspan="{BUGS_COLSPAN}">
				# IF C_STATUS_IN_PROGRESS #{@bugs.notice.no_bug_in_progress}# ELSE #{@bugs.notice.no_bug_fixed}# ENDIF #
			</td>
		</tr>
		# ENDIF #
	</tbody>
</table>

# INCLUDE LEGEND #
