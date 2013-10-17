# IF C_ARTICLES_FILTERS #
<div style="float:right;width:300px;margin-right:10px;">
	# INCLUDE FORM #
</div>
# ENDIF #
<div class="spacer">&nbsp;</div>
<hr />
<table>
	<thead>
		<tr>
			<th>
				{L_TITLE}
			</th>
			<th>
				{L_CATEGORY}
			</th>
			<th>
				{L_AUTHOR}
			</th>
			<th>
				{L_DATE}	
			</th>
			<th>
				{L_PUBLISHED}	
			</th>
			<th>
				{L_UPDATE}
			</th>
			<th>
				{L_DELETE}
			</th>
		</tr>
	</thead>
	# IF PAGINATION #
	<tfoot>
		<tr>
			<th colspan="7">
				{PAGINATION}
			</th>
		</tr>
	</tfoot>
	# ENDIF #
	<tbody>
		# START articles #
		<tr> 
			<td>
				<a href="{articles.U_ARTICLE}">{articles.L_TITLE}</a>
			</td>
			<td> 
				<a href="{articles.U_CATEGORY}">{articles.L_CATEGORY}</a>
			</td>
			<td> 
				<a href="{articles.U_AUTHOR}" class="small_link {articles.USER_LEVEL_CLASS}" # IF articles.C_USER_GROUP_COLOR # style="color:{articles.USER_GROUP_COLOR}"# ENDIF #>{articles.PSEUDO}</a>
			</td>		
			<td>
				{articles.DATE}
			</td>
			<td>
				{articles.PUBLISHED} 
				<br />
				<span class="smaller">{articles.PUBLISHED_DATE}</span>
			</td>
			<td> 
				<a href="{articles.U_EDIT_ARTICLE}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{articles.L_EDIT_ARTICLE}" title="{articles.L_EDIT_ARTICLE}" /></a>
			</td>
			<td>
				<a href="{articles.U_DELETE_ARTICLE}" onclick="javascript:return Confirm();"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{articles.L_DELETE_ARTICLE}" title="{articles.L_DELETE_ARTICLE}" /></a>
			</td>
		</tr>
		# END articles #
	</tbody>
</table>