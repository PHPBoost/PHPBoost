# INCLUDE PROGRESS_BAR #

<table class="module_table">
	<tr>
		<td class="vertical_top" class="row2">
			# INCLUDE SELECT_VERSION #
		</td>
	</tr>
</table>

<div class="spacer">&nbsp;</div>

<table class="module_table">
	<tr class="text_center">
		<th class="column_id">
			<a href="{LINK_BUG_ID_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
			{@bugs.labels.fields.id}
			<a href="{LINK_BUG_ID_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
		</th>
		<th>
			<a href="{LINK_BUG_TITLE_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
			{@bugs.labels.fields.title}
			<a href="{LINK_BUG_TITLE_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
		</th>
		<th class="column_informations">
			<a href="{LINK_BUG_STATUS_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
			{@bugs.titles.informations}
			<a href="{LINK_BUG_STATUS_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
		</th>
		<th class="column_date_roadmap">
			<a href="{LINK_BUG_DATE_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
			{@bugs.labels.fields.fix_date}
			<a href="{LINK_BUG_DATE_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
		</th>
	</tr>
	# START bug #
	<tr class="text_center"> 
		<td class="row2"# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
			<a href="{bug.LINK_BUG_DETAIL}" title="{bug.STATUS}"># IF bug.C_FIXED #<s># ENDIF #\#{bug.ID}# IF bug.C_FIXED #</s># ENDIF #</a>
		</td>
		<td class="row2 align_left"# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
			{bug.TITLE}
		</td>
		<td class="row2 align_left"# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #> 
			# IF bug.C_PROGRESS #<span class="progressBar progress{bug.PROGRESS}">{bug.PROGRESS}%</span><br/># ENDIF #
			<span>{bug.STATUS}</span>
			# IF C_COMMENTS #<br /><a href="{bug.LINK_BUG_COMMENTS}">{bug.NUMBER_COMMENTS} {bug.L_COMMENTS}</a># ENDIF #
		</td>
		<td class="row2"# IF bug.C_LINE_COLOR # style="background-color:{bug.LINE_COLOR};"# ENDIF #>
			{bug.DATE}
		</td>
	</tr>
	# END bug #
	# IF NOT C_BUGS #
	<tr> 
		<td colspan="{BUGS_COLSPAN}" class="row2 text_center">
			{L_NO_BUG}
		</td>
	</tr>
	# ENDIF #
</table>

# INCLUDE LEGEND #

# IF C_PAGINATION #<div class="center"># INCLUDE PAGINATION #</div># ENDIF #