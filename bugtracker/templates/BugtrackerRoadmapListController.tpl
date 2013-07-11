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
		# IF C_DISPLAY_SEVERITIES #
		<th class="column_severity">
			<a href="{LINK_BUG_SEVERITY_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
			{@bugs.labels.fields.severity}
			<a href="{LINK_BUG_SEVERITY_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
		</th>
		# ENDIF #
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
		<th class="column_history">
			{@bugs.titles.history}
		</th>
	</tr>
	# START bug #
	<tr class="align_center"> 
		<td class="row2" {bug.LINE_COLOR}>
			<a href="{bug.LINK_BUG_DETAIL}" title="{bug.STATUS}"># IF bug.C_FIXED #<s># ENDIF #\#{bug.ID}# IF bug.C_FIXED #</s># ENDIF #</a>
		</td>
		<td class="row2 align_left" {bug.LINE_COLOR}>
			{bug.TITLE}
		</td>
		# IF C_DISPLAY_SEVERITIES #
		<td class="row2 text_strong" {bug.LINE_COLOR}> 
			{bug.SEVERITY}
		</td>
		# ENDIF #
		<td class="row2 align_left" {bug.LINE_COLOR}> 
			{bug.INFOS}
		</td>
		<td class="row2" {bug.LINE_COLOR}>
			{bug.DATE}
		</td>
		<td class="row2" {bug.LINE_COLOR}> 
			<a href="{bug.LINK_BUG_HISTORY}"><img src="{PATH_TO_ROOT}/bugtracker/templates/images/history.png" alt="{@bugs.actions.history}" title="{@bugs.actions.history}" /></a>
		</td>
	</tr>
	# END bug #
	# IF C_BUGS #
	<tr>
		<td colspan="{BUGS_COLSPAN}" class="row1">
			<span class="float_left">{@bugs.labels.page} : {PAGINATION}</span>
		</td>
	</tr>
	# ELSE #
	<tr class="text_center"> 
		<td colspan="{BUGS_COLSPAN}" class="row2">
			{L_NO_BUG}
		</td>
	</tr>
	# ENDIF #
</table>

# INCLUDE LEGEND #