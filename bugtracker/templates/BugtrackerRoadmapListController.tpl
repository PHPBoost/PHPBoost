# INCLUDE PROGRESS_BAR #

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
				<a href="{LINK_BUG_ID_TOP}" class="sort-up"></a>
				{@bugs.labels.fields.id}
				<a href="{LINK_BUG_ID_BOTTOM}" class="sort-down"></a>
			</th>
			<th>
				<a href="{LINK_BUG_TITLE_TOP}" class="sort-up"></a>
				{@bugs.labels.fields.title}
				<a href="{LINK_BUG_TITLE_BOTTOM}" class="sort-down"></a>
			</th>
			<th class="column_informations">
				<a href="{LINK_BUG_STATUS_TOP}" class="sort-up"></a>
				{@bugs.titles.informations}
				<a href="{LINK_BUG_STATUS_BOTTOM}" class="sort-down"></a>
			</th>
			<th class="column_date_roadmap">
				<a href="{LINK_BUG_DATE_TOP}" class="sort-up"></a>
				{@bugs.labels.fields.fix_date}
				<a href="{LINK_BUG_DATE_BOTTOM}" class="sort-down"></a>
			</th>
		</tr>
	</thead>
	<tbody>
		# START bug #
		<tr> 
			<td # IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
				<a href="{bug.LINK_BUG_DETAIL}" title="{bug.STATUS}"># IF bug.C_FIXED #<s># ENDIF #\#{bug.ID}# IF bug.C_FIXED #</s># ENDIF #</a>
			</td>
			<td class="align_left"# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
				{bug.TITLE}
			</td>
			<td class="align_left"# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #> 
				# IF bug.C_PROGRESS #<span class="progressBar progress{bug.PROGRESS}">{bug.PROGRESS}%</span><br/># ENDIF #
				<span>{bug.STATUS}</span>
				# IF C_COMMENTS #<br /><a href="{bug.LINK_BUG_COMMENTS}">{bug.NUMBER_COMMENTS} {bug.L_COMMENTS}</a># ENDIF #
			</td>
			<td # IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
				{bug.DATE}
			</td>
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

# IF C_PAGINATION #<div class="center"># INCLUDE PAGINATION #</div># ENDIF #