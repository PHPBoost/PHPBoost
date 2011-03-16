<script type="text/javascript">
<!--
function Confirm(level) {
	return confirm("{@subscribers.delete}");
}
-->
</script>
<table  class="module_table">
	<tr> 
		<th colspan="4">
			{@subscribers.list}
		</th>
	</tr>
	<tr style="text-align:center;">
		<td class="row1">
		</td>
		<td class="row1">
			<a href="{SORT_PSEUDO_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
			{@subscribers.pseudo} 
			<a href="{SORT_PSEUDO_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
		</td>
		<td class="row1">
			<a href="{SORT_NEWSLETTER_NAME_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
			{@newsletters_names}
			<a href="{SORT_NEWSLETTER_NAME_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
		</td>
		<td class="row1">
			{@subscribers.mail}
		</td>
	</tr>
	<tr style="text-align:center;">
		<td colspan="4" class="row1">
			<a href="{C_SUBSCRIPTION}">{@newsletter.subscribe_newsletters}</a>
		</td>
	</tr>
	# IF C_SUBSCRIBERS #
		# START subscribers_list #
		<tr style="text-align:center;">
			<td class="row1"> 
				<a href="{subscribers_list.EDIT_LINK}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" /></a>
				<a href="{subscribers_list.DELETE_LINK}" onclick="javascript:Confirm();"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" /></a>
			</td>
			<td class="row2">
				{subscribers_list.PSEUDO}
			</td>
			<td class="row2">
				{subscribers_list.NEWSLETTER_NAME}
			</td>
			<td class="row2">
				{subscribers_list.MAIL}
			</td>
		</tr>
		# END subscribers_list #
	# ENDIF #
	# IF C_SUBSCRIBERS #
	<tr>
		<td colspan="4" class="row1">
			<span style="float:left;">{@newsletter.page} : {PAGINATION}</span>
		</td>
	</tr>
	# ELSE #
	<tr style="text-align:center;">
		<td colspan="4" class="row2">
			<span style="margin-left:auto;margin-right:auto;" class="text_strong" >{@subscribers.no_users}</span>
		</td>
	</tr>
	# ENDIF #
</table>