<script type="text/javascript">
<!--
	function Confirm() {
		return confirm("{@calendar.actions.confirm.del_event}");
	}
-->
</script>

<div class="admin_add_link">
	<a href="{U_ADD}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/add.png" alt="{@calendar.titles.add_event}" title="{@calendar.titles.add_event}" class="valign_middle" /></a>
</div>

<div class="spacer">&nbsp;</div>

<div class="admin_form">
	# INCLUDE FORM #
</div>

<table class="module_table">
	<tr class="text_center">
		<th class="column_title">
			{@calendar.labels.title}
		</th>
		<th>
			{@calendar.labels.category}
		</th>
		<th>
			{@calendar.labels.created_by}
		</th>
		<th>
			{@calendar.labels.date}
		</th>
		<th>
			{@calendar.labels.approved}
		</th>
		<th>
			{L_EDIT}
		</th>
		<th>
			{L_DELETE}
		</th>
	</tr>

	# START events #
	<tr class="text_center"> 
		<td class="row2 text_left">
			<a href="{events.U_LINK}">{events.SHORT_TITLE}</a>
		</td>
		<td class="row2"> 
			<a href="{events.U_CATEGORY}"# IF events.CATEGORY_COLOR # style="color:{events.CATEGORY_COLOR}"# ENDIF #>{events.CATEGORY_NAME}</a>
		</td>
		<td class="row2"> 
			<a href="{events.U_AUTHOR_PROFILE}" class="small_link {events.AUTHOR_LEVEL_CLASS}" # IF events.C_AUTHOR_GROUP_COLOR # style="color:{events.AUTHOR_GROUP_COLOR}"# ENDIF #>{events.AUTHOR}</a>
		</td>
		<td class="row2">
			{events.DATE}
		</td>
		<td class="row2">
			# IF events.C_APPROVED #{L_YES}# ELSE #{L_NO}# ENDIF #
		</td>
		<td class="row2"> 
			<a href="{events.U_EDIT}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_EDIT}" title="{L_EDIT}" /></a>
		</td>
		<td class="row2">
			<a href="{events.U_DELETE}"# IF NOT events.C_BELONGS_TO_A_SERIE # onclick="javascript:return Confirm();"# ENDIF #><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
		</td>
	</tr>
	# END events #
	# IF NOT C_EVENTS #
	<tr class="text_center"> 
		<td colspan="7" class="row2">
			{@calendar.notice.no_event}
		</td>
	</tr>
	# ENDIF #
</table>
# IF C_PAGINATION #
<div class="spacer">&nbsp;</div>
<div class="text_center"># INCLUDE PAGINATION #</div>
# ENDIF #
