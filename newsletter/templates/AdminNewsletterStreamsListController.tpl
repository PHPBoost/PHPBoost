{@newsletter.streams}
<table>
	<thead>
		<tr>
			<th>
			</th>
			<th>
				<a href="{SORT_NAME_TOP}" class="sort-up"></a>
				{@streams.name} 
				<a href="{SORT_NAME_BOTTOM}" class="sort-down"></a>
			</th>
			<th>
				{@streams.description}
			</th>
			<th>
				<a href="{SORT_STATUS_TOP}" class="sort-up"></a>
				{@streams.visible}
				<a href="{SORT_STATUS_BOTTOM}" class="sort-down"></a>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th colspan="2">
				<a href="{C_ADD_STREAM}">{@streams.add}</a>
			</th>
			<th colspan="2">
				<span style="float:right;">{@newsletter.page} : {PAGINATION}</span>
			</th>
		</tr>
	</tfoot>
	<tbody>
	# IF C_STREAMS_EXIST #
		# START streams_list #
		<tr style="text-align:center;">
			<td> 
				<a href="{streams_list.EDIT_LINK}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" /></a>
				<a href="{streams_list.DELETE_LINK}" onclick="javascript:Confirm();"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" /></a>
			</td>
			<td>
				{streams_list.NAME}
			</td>
			<td>
				{streams_list.DESCRIPTION}
			</td>
			<td>
				{streams_list.STATUS}
			</td>
		</tr>
		# END streams_list #
	# ENDIF #
	</tbody>
</table>