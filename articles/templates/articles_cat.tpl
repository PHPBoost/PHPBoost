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
			# IF C_WRITE #
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
		<div class="spacer">&nbsp;</div>
		<div style="float:right;" class="row3" id="form">
			<script type="text/javascript">
			<!--
			function change_order()
			{
				window.location = "{TARGET_ON_CHANGE_ORDER}sort=" + document.getElementById("sort").value + "&mode=" + document.getElementById("mode").value;
			}
			-->
			</script>
			{L_ORDER_BY}
			<select name="sort" id="sort" class="nav" onchange="change_order()">
				<option value="alpha"{SELECTED_ALPHA}>{L_TITLE}</option>
				<option value="view"{SELECTED_VIEW}>{L_VIEW}</option>
				<option value="date"{SELECTED_DATE}>{L_DATE}</option>
				<option value="com"{SELECTED_COM}>{L_COM}</option>
				<option value="note"{SELECTED_NOTE}>{L_NOTE}</option>
				<option value="author"{SELECTED_AUTHOR}>{L_AUTHOR}</option>
			</select>
			<select name="mode" id="mode" class="nav" onchange="change_order()">
				<option value="asc"{SELECTED_ASC}>{L_ASC}</option>
				<option value="desc"{SELECTED_DESC}>{L_DESC}</option>
			</select>
		</div>
		<div class="spacer">&nbsp;</div>
			# IF C_INVISIBLE #
				<h2>{L_WAITING_ARTICLES}</h2>
				<hr />
				# START articles_invisible #
				<div class="block_container" style="margin-bottom:20px;">
					<div class="block_contents">
						<div style="float:left;width:70%">
							<p style="margin-bottom:10px">
								<a href="../articles/articles{articles_invisible.U_ARTICLES_LINK}" class="big_link">{articles_invisible.NAME}</a>
								# IF C_WRITE #
									<a href="{articles_invisible.U_ADMIN_EDIT_ARTICLES}">
										<img class="valign_middle" src="../templates/{THEME}/images/{LANG}/edit.png" alt="" />
									</a>
									<a href="{articles_invisible.U_ADMIN_DELETE_ARTICLES}" onclick="return Confirm_del_article();">
										<img class="valign_middle" src="../templates/{THEME}/images/{LANG}/delete.png" alt="" />
									</a>
								# ENDIF #
							</p>
							<div class="text_small">
								{L_DATE} : {articles_invisible.DATE}
								<br />
								{L_VIEW} : {articles_invisible.COMPT}
								<br />
								{L_COM} : <a href="../articles/articles{articles_invisible.U_ARTICLES_LINK_COM}">{articles_invisible.COM} </a>
								<br />
								{L_NOTE} {articles_invisible.NOTE}
								<br />
							{L_WRITTEN} : {articles_invisible.U_ARTICLES_PSEUDO}
							</div>
						</div>
						<div class="float:right;">	
							<div style="text-align:right;margin-top:15px;">								
								<a href="../articles/articles{articles_invisible.U_ARTICLES_LINK}">
									{articles_invisible.ICON}
								</a>	
							</div>
						</div>
						<div class="spacer"></div>		
					</div>
				</div>
				# END articles_invisible #
			<p style="text-align:center;padding-top:10px;" class="text_small">	{L_NO_ARTICLES_WAITING}</p>
			<h2>Articles</h2>
			<hr />
			# ENDIF #
			# START articles #
				<div class="block_container" style="margin-bottom:20px;">
					<div class="block_contents">
						<div style="float:left;width:70%">
							<p style="margin-bottom:10px">
								<a href="../articles/articles{articles.U_ARTICLES_LINK}" class="big_link">{articles.NAME}</a>
								# IF C_WRITE #
									<a href="{articles.U_ADMIN_EDIT_ARTICLES}">
										<img class="valign_middle" src="../templates/{THEME}/images/{LANG}/edit.png" alt="" />
									</a>
									<a href="{articles.U_ADMIN_DELETE_ARTICLES}" onclick="return Confirm_del_article();">
										<img class="valign_middle" src="../templates/{THEME}/images/{LANG}/delete.png" alt="" />
									</a>
								# ENDIF #
							</p>
							<div class="text_small">
								{L_DATE} : {articles.DATE}
								<br />
								{L_VIEW} : {articles.COMPT}
								<br />
								{L_COM} : <a href="../articles/articles{articles.U_ARTICLES_LINK_COM}">{articles.COM} </a>
								<br />
								{L_NOTE} {articles.NOTE}
								<br />
							{L_WRITTEN} : {articles.U_ARTICLES_PSEUDO}
							</div>
						</div>
						<div class="float:right;">	
							<div style="text-align:right;margin-top:15px;">								
								<a href="../articles/articles{articles.U_ARTICLES_LINK}">
									{articles.ICON}
								</a>	
							</div>
						</div>
						<div class="spacer"></div>		
					</div>
				</div>
			# END articles #

			{PAGINATION}
			<br />
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
