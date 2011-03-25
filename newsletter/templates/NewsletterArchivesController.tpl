<table  class="module_table">
	<tr> 
		<th colspan="4">
			{@archives.list}
		</th>
	</tr>
	<tr style="text-align:center;">
		<td class="row1">
			<a href="{SORT_STREAM_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
			{@archives.stream_name}
			<a href="{SORT_STREAM_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
		</td>
		<td class="row1">
			<a href="{SORT_SUBJECT_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
			{@archives.name} 
			<a href="{SORT_SUBJECT_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
		</td>
		<td class="row1">
			<a href="{SORT_DATE_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
			{@archives.date} 
			<a href="{SORT_DATE_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
		</td>
		<td class="row1">
			<a href="{SORT_SUBSCRIBERS_TOP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
			{@archives.nbr_subscribers} 
			<a href="{SORT_SUBSCRIBERS_BOTTOM}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
		</td>
	</tr>
	# START archives_list #
	<tr style="text-align:center;">
		<td class="row2">
			{archives_list.STREAM_NAME}
		</td>
		<td class="row2">
			<a href="{archives_list.VIEW_ARCHIVE}">{archives_list.SUBJECT}</a>
		</td>
		<td class="row2">
			{archives_list.DATE}
		</td>
		<td class="row2">
			{archives_list.NBR_SUBSCRIBERS}
		</td>
	</tr>
	# END archives_list #
	<tr>
		<td colspan="4" class="row1">
			<span style="float:left;">{@newsletter.page} : {PAGINATION}</span>
		</td>
	</tr>
</table>