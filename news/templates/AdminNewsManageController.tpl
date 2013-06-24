<table  class="module_table">
	<tr> 
		<th colspan="6">
			{@news}
		</th>
	</tr>
	<tr style="text-align:center;">
		<td class="row1" style="width:50px;text-align:center">
		</td>
		<td class="row1">
			{@news.form.name} 
		</td>
		<td class="row1">
			{@news.form.category}
		</td>
		<td class="row1">
			${LangLoader::get_message('pseudo', 'main')}
		</td>
		<td class="row1">
			{@news.form.date.creation}
		</td>
		<td class="row1">
			{@news.form.approbation}
		</td>
	</tr>
	# IF C_NEWS_EXISTS #
		# START news #
		<tr style="text-align:center;">
			<td class="row1"> 
				<a href="{news.EDIT_LINK}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" /></a>
				<a href="{news.DELETE_LINK}" onclick="javascript:Confirm();"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" /></a>
			</td>
			<td class="row2">
				{news.NAME}
			</td>
			<td class="row2">
				{news.CATEGORY}
			</td>
			<td class="row2">
				{news.PSEUDO}
			</td>
			<td class="row2">
				{news.DATE}
			</td>
			<td class="row2">
				{news.STATUS}
			</td>
		</tr>
		# END news #
	# ENDIF #
	<tr>
		<td colspan="6" class="row1">
			<span style="float:left;"># INCLUDE PAGINATION #</span>
		</td>
	</tr>
</table>