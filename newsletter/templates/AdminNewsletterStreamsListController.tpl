<table  class="module_table">
	<tr> 
		<th colspan="4">
			{@subscribers.list}
		</th>
	</tr>
	<tr style="text-align:center;">
		<td class="row1" style="width:50px;text-align:center">
		</td>
		<td class="row1">
			<a href="{SORT_NAME_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
			{@streams.name} 
			<a href="{SORT_NAME_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
		</td>
		<td class="row1">
			{@streams.description}
		</td>
		<td class="row1">
			<a href="{SORT_STATUS_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
			{@streams.visible}
			<a href="{SORT_STATUS_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
		</td>
	</tr>
	<tr style="text-align:center;">
		<td colspan="4" class="row1">
			<a href="{C_ADD_CATEGORIE}">{@streams.add}</a>
		</td>
	</tr>
	# IF C_CATEGORIES_EXIST #
		# START categories_list #
		<tr style="text-align:center;">
			<td class="row1"> 
				<a href="{categories_list.EDIT_LINK}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" /></a>
				<a href="{categories_list.DELETE_LINK}" onclick="javascript:Confirm();"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" /></a>
			</td>
			<td class="row2">
				{categories_list.NAME}
			</td>
			<td class="row2">
				{categories_list.DESCRIPTION}
			</td>
			<td class="row2">
				{categories_list.STATUS}
			</td>
		</tr>
		# END categories_list #
	# ENDIF #
	<tr>
		<td colspan="4" class="row1">
			<span style="float:left;">{@newsletter.page} : {PAGINATION}</span>
		</td>
	</tr>
</table>