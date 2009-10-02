		{JAVA} 
		<script type="text/javascript">
		<!--
			function Confirm_del_article() {
			return confirm("{L_ALERT_DELETE_ARTICLE}");
			}
		-->
		</script>	
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<a href="{PATH_TO_ROOT}/syndication.php?m=articles&amp;cat={IDCAT}" title="Rss"><img style="vertical-align:middle;margin-top:-2px;" src="../templates/{THEME}/images/rss.png" alt="Rss" title="Rss" /></a>&nbsp;{U_ARTICLES_CAT_LINKS} 			
				&nbsp;
				# IF C_EDIT #			
				<a href="{U_EDIT}" title="{L_EDIT}">
					<img class="valign_middle" src="../templates/{THEME}/images/{LANG}/edit.png" alt="" />
				</a>
				# ENDIF #
				# IF C_ADD #
				<a href="{U_ADD}" title="{L_ADD}">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/add.png" class="valign_middle" alt="{L_ADD}" />
				</a>
				&nbsp;
					# IF NOT C_WAITING #
						<div style="display:inline;float:right;">{U_ARTICLES_WAITING}</div>
					# ENDIF #
				# ENDIF #
				
			</div>
			<div class="module_contents">
				# IF C_ARTICLES_CAT #
				<p style="text-align:center;" class="text_strong">
					{L_CATEGORIES}
					# IF C_IS_ADMIN # <a href="admin_articles_cat.php"><img class="valign_middle" src="../templates/{THEME}/images/{LANG}/edit.png" alt="" /></a> # ENDIF #
				</p>
				<hr style="margin-bottom:20px;" />
				# START cat_list #
				<div style="float:left;text-align:center;width:{COLUMN_WIDTH_CAT}%;margin-bottom:20px;">
					{cat_list.ICON_CAT}
					<a href="../articles/articles{cat_list.U_CAT}">{cat_list.CAT}</a> {cat_list.EDIT}
					<br />
					<span class="text_small">{cat_list.DESC}</span> 
					<br />
					<span class="text_small">{cat_list.L_NBR_ARTICLES}</span> 
				</div>
				# END cat_list #
				<div class="spacer">&nbsp;</div>				
				<p style="text-align:center;">{PAGINATION_CAT}</p>
				<hr />
				# ENDIF #
				
				# IF C_ARTICLES_LINK #
				<br /><br />
				<table class="module_table">
					<tr>
						<th style="text-align:center;">
							<a href="../articles/articles{U_ARTICLES_ALPHA_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
							{L_ARTICLES}
							<a href="../articles/articles{U_ARTICLES_ALPHA_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
						</th>
						<th style="text-align:center;">
							<a href="../articles/articles{U_ARTICLES_DATE_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
							{L_DATE}					
							<a href="../articles/articles{U_ARTICLES_DATE_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
						</th>
						<th style="text-align:center;">
							<a href="../articles/articles{U_ARTICLES_VIEW_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
							{L_VIEW}					
							<a href="../articles/articles{U_ARTICLES_VIEW_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
						</th>
						<th style="text-align:center;">
							<a href="../articles/articles{U_ARTICLES_NOTE_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
							{L_NOTE}					
							<a href="../articles/articles{U_ARTICLES_NOTE_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
						</th>
						<th style="text-align:center;">
							<a href="../articles/articles{U_ARTICLES_COM_TOP}"><img src="../templates/{THEME}/images/top.png" alt="" class="valign_middle" /></a>
							{L_COM}
							<a href="../articles/articles{U_ARTICLES_COM_BOTTOM}"><img src="../templates/{THEME}/images/bottom.png" alt="" class="valign_middle" /></a>
						</th>
					</tr>
					# START articles #
					<tr>	
						<td class="row2" style="padding-left:25px">
							{articles.ICON} &nbsp;&nbsp;<a href="../articles/articles{articles.U_ARTICLES_LINK}">{articles.NAME}</a>
							# IF C_WAITING #
							<div style="display:inline;float:right">
							&nbsp;&nbsp;<a href="../articles/management.php?edit={articles.ID}" title="{L_EDIT}"><img src="../templates/{THEME}/images/{LANG}/edit.png" class="valign_middle" alt="{L_EDIT}" /></a>
							&nbsp;&nbsp;<a href="../articles/management.php?del=1&amp;id={articles.ID}&amp;token={TOKEN}" title="{L_DELETE}" onclick="javascript:return Confirm_del_article();"><img src="../templates/{THEME}/images/{LANG}/delete.png" class="valign_middle" alt="{L_DELETE}" /></a>
							</div>
							# ENDIF #
						</td>
						<td class="row2" style="text-align: center;">
							{articles.DATE}
						</td>
						<td class="row2" style="text-align: center;">
							{articles.COMPT} 
						</td>
						<td class="row2" style="text-align: center;">
							{articles.NOTE}
						</td>
						<td class="row2" style="text-align: center;">
							<a href="../articles/articles{articles.U_ARTICLES_LINK_COM}">{articles.COM} </a>
						</td>
					</tr>
					# END articles #
					<tr>
						<td colspan="6" class="row3">
							{PAGINATION}
						</td>	
					</tr>
				</table>
				<br />
					# IF C_WAITING_ADMIN #
						<p style="text-align:center;padding-top:10px;"><a href="../articles/admin_articles{U_ALL_WAITING_ARTICLES}">{L_ALL_WAITING_ARTICLES}</a></p>
					# ENDIF #
				# ENDIF #
				
				<p style="text-align:center;padding-top:10px;" class="text_small">
					{L_NO_ARTICLES} {L_TOTAL_ARTICLE}
				</p>
				&nbsp;
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom text_strong">
				<a href="../articles/articles.php{SID}">{L_ARTICLES_INDEX}</a> &raquo; {U_ARTICLES_CAT_LINKS} {EDIT} {ADD_ARTICLES}
			</div>
		</div>
		