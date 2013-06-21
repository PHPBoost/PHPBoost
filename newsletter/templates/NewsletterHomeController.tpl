<table class="module_table">
	<tr> 
		<th colspan="5">
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
			{@newsletter.archives}
		</td>
		<td class="row1">
			{@newsletter.subscribers}
		</td>
	</tr>
	# IF C_STREAMS #
		<tr style="text-align:center;">
			<td colspan="5" class="row3">
				<a href="{LINK_SUBSCRIBE}" style="margin-right:25px;">{@newsletter.subscribe_newsletters}</a>
				<a href="{LINK_UNSUBSCRIBE}" style="margin-left:25px;">{@newsletter.unsubscribe_newsletters}</a>
			</td>
		</tr>
		# START streams_list #
		<tr style="text-align:center;">
			<td class="row2"> 
				<img src="{streams_list.PICTURE}"></img>
			</td>
			<td class="row2">
				{streams_list.NAME}
			</td>
			<td class="row2">
				{streams_list.DESCRIPTION}
			</td>
			<td class="row2">
				{streams_list.VIEW_ARCHIVES}
			</td>
			<td class="row2">
				{streams_list.VIEW_SUBSCRIBERS}
			</td>
		</tr>
		# END streams_list #
	# ENDIF #
	# IF C_STREAMS #
	<tr>
		<td colspan="5" class="row1">
			<span style="float:left;">{@newsletter.page} : {PAGINATION}</span>
		</td>
	</tr>
	# ELSE #
	<tr style="text-align:center;">
		<td colspan="5" class="row2">
			<span style="margin-left:auto;margin-right:auto;" class="text_strong" >{@newsletter.no_newsletters}</span>
		</td>
	</tr>
	# ENDIF #
</table>