<script type="text/javascript">
<!--
	function Confirm() {
	return confirm("{L_ALERT_DELETE_ARTICLE}");
}
-->
</script>
# IF C_ARTICLES_FILTERS #
<div style="float:right;width:240px;margin-right:10px;" class="row3" id="form">
	{L_ARTICLES_FILTERS_TITLE}# INCLUDE FORM #
</div>
# ENDIF #
<div class="spacer">&nbsp;</div>
<hr />
<table  class="module_table">
	<tr style="text-align:center;">
		<th style="width:28%;text-align:center">
			{L_TITLE}
		</th>
		<th style="text-align:center;">
			{L_CATEGORY}
		</th>
		<th style="text-align:center;">
			{L_AUTHOR}
		</th>
		<th style="text-align:center;">
			{L_DATE}	
		</th>
		<th style="text-align:center;">
			{L_PUBLISHED}	
		</th>
		<th style="text-align:center;">
			{L_UPDATE}
		</th>
		<th style="text-align:center;">
			{L_DELETE}
		</th>
	</tr>

	# START articles #
	<tr style="text-align:center;"> 
		<td style="text-align:left;" class="row2">
			<a href="{articles.U_ARTICLE}">{articles.L_TITLE}</a>
		</td>
		<td class="row2"> 
			<a href="{articles.U_CATEGORY}">{articles.L_CATEGORY}</a>
		</td>
		<td class="row2"> 
			<a href="{articles.U_AUTHOR}" class="small_link {articles.USER_LEVEL_CLASS}" # IF articles.C_USER_GROUP_COLOR # style="color:{articles.USER_GROUP_COLOR}"# ENDIF #>{articles.PSEUDO}</a>
		</td>		
		<td class="row2">
			{articles.DATE}
		</td>
		<td class="row2">
			{articles.PUBLISHED} 
			<br />
			<span class="text_small">{articles.PUBLISHED_DATE}</span>
		</td>
		<td class="row2"> 
			<a href="{articles.U_EDIT_ARTICLE}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{articles.L_EDIT_ARTICLE}" title="{articles.L_EDIT_ARTICLE}" /></a>
		</td>
		<td class="row2">
			<a href="{articles.U_DELETE_ARTICLE}" onclick="javascript:return Confirm();"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{articles.L_DELETE_ARTICLE}" title="{articles.L_DELETE_ARTICLE}" /></a>
		</td>
	</tr>
	# END articles #
</table>
<br /><br />
<p style="text-align: center;">{PAGINATION}</p>



			