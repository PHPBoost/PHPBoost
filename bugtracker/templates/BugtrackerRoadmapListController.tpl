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
	<tr class="align_center"> 
		<td class="row2" {bug.LINE_COLOR}>
			<a href="{bug.LINK_BUG_DETAIL}" title="{bug.STATUS}"># IF bug.C_FIXED #<s># ENDIF #\#{bug.ID}# IF bug.C_FIXED #</s># ENDIF #</a>
		</td>
		<td class="row2 align_left" {bug.LINE_COLOR}>
			{bug.TITLE}
		</td>
		<td class="row2 align_left" {bug.LINE_COLOR}> 
			{bug.INFOS}
		</td>
		<td class="row2" {bug.LINE_COLOR}>
			{bug.DATE}
		</td>
	</tr>
	# END bug #
	# IF NOT C_BUGS #
	<tr class="text_center"> 
		<td colspan="{BUGS_COLSPAN}" class="row2">
			{L_NO_BUG}
		</td>
	</tr>
	# ENDIF #
</table>

# INCLUDE LEGEND #

# IF C_PAGINATION #<div class="text_center"># INCLUDE PAGINATION #</div># ENDIF #