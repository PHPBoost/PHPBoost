<table  class="module_table">
	<tr> 
		<th colspan="4">
			{@newsletter.list_newsletters}
		</th>
	</tr>
	<tr style="text-align:center;">
		<td class="row1">
		</td>
		<td class="row1">
			{@streams.name} 
		</td>
		<td class="row1">
			{@streams.description}
		</td>
		<td class="row1">
			{@newsletter.view_archives}
		</td>
	</tr>
	# IF C_CATEGORIES #
		<tr style="text-align:center;">
			<td colspan="4" class="row3">
				<a href="{LINK_SUBSCRIBE}" style="margin-right:25px;">{@newsletter.subscribe_newsletters}</a>
				<a href="{LINK_UNSUBSCRIBE}" style="margin-left:25px;">{@newsletter.unsubscribe_newsletters}</a>
			</td>
		</tr>
		# START categories_list #
		<tr style="text-align:center;">
			<td class="row2"> 
				<img src="{categories_list.PICTURE}"></img>
			</td>
			<td class="row2">
				{categories_list.NAME}
			</td>
			<td class="row2">
				{categories_list.DESCRIPTION}
			</td>
			<td class="row2">
				{categories_list.VIEW_ARCHIVES}
			</td>
		</tr>
		# END categories_list #
	# ENDIF #
	# IF C_CATEGORIES #
	<tr>
		<td colspan="4" class="row1">
			<span style="float:left;">{@newsletter.page} : {PAGINATION}</span>
		</td>
	</tr>
	# ELSE #
	<tr style="text-align:center;">
		<td colspan="4" class="row2">
			<span style="margin-left:auto;margin-right:auto;" class="text_strong" >{@newsletter.no_newsletters}</span>
		</td>
	</tr>
	# ENDIF #
</table>