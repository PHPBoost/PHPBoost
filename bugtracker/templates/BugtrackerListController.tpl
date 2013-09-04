# INCLUDE PROGRESS_BAR #

# INCLUDE FILTER_LIST #

<script type="text/javascript">
<!--
function Confirm(action) {
	if (action == 'delete') {
		return confirm("{@bugs.actions.confirm.del_bug}");
	}
	else if (action == 'reopen') {
		return confirm("{@bugs.actions.confirm.reopen_bug}");
	}
	else if (action == 'reject') {
		return confirm("{@bugs.actions.confirm.reject_bug}");
	}
}
-->
</script>
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
		<th class="column_date">
			<a href="{LINK_BUG_DATE_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
			{L_DATE}
			<a href="{LINK_BUG_DATE_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
		</th>
		# IF C_IS_ADMIN #
		<th class="column_admin">
			{@bugs.actions}
		</th>
		# ENDIF #
	</tr>
	# START bug #
	<tr class="align_center"> 
		<td class="row2" {bug.LINE_COLOR}>
			<a href="{bug.LINK_BUG_DETAIL}">\#{bug.ID}</a>
		</td>
		<td class="row2 align_left" {bug.LINE_COLOR}>
			{bug.TITLE}
		</td>
		<td class="row2 align_left" {bug.LINE_COLOR}> 
			{bug.INFOS}
		</td>
		<td class="row2" {bug.LINE_COLOR}>
			# IF C_UNSOLVED #{L_ON}: # ENDIF #{bug.DATE}<br />
			# IF C_DISPLAY_AUTHOR #{L_BY}: # IF bug.AUTHOR #<a href="{bug.LINK_AUTHOR_PROFILE}" class="small_link {bug.AUTHOR_LEVEL_CLASS}" # IF bug.C_AUTHOR_GROUP_COLOR # style="color:{bug.AUTHOR_GROUP_COLOR}" # ENDIF #>{bug.AUTHOR}</a># ELSE #{L_GUEST}# ENDIF ## ENDIF #
		</td>
		# IF C_IS_ADMIN #
		<td class="row2" {bug.LINE_COLOR}> 
			<a href="{bug.LINK_BUG_REOPEN_REJECT}" onclick="javascript:return Confirm(${escapejs(REOPEN_REJECT_CONFIRM)});"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/{PICT_REOPEN_REJECT}" alt="{L_REOPEN_REJECT}" title="{L_REOPEN_REJECT}" /></a>
			<a href="{bug.LINK_BUG_EDIT}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" /></a>
			<a href="{bug.LINK_BUG_HISTORY}"><img src="{PATH_TO_ROOT}/bugtracker/templates/images/history.png" alt="{@bugs.actions.history}" title="{@bugs.actions.history}" /></a>
			<a href="{bug.LINK_BUG_DELETE}" onclick="javascript:return Confirm('delete');"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
		</td>
		# ENDIF #
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