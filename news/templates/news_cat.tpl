		<div class="news_container">
			<div class="news_top_l"></div>			
			<div class="news_top_r"></div>
			<div class="news_top">
				<a href="{PATH_TO_ROOT}/syndication.php?m=news" title="Syndication"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" /></a> <h3 class="title valign_middle">{L_CATEGORY} :: {CAT_NAME} # IF C_IS_ADMIN # &nbsp;&nbsp;<a href="admin_news_cat.php?id={IDCAT}" title="{L_EDIT}"><img class="valign_middle" src="../templates/{THEME}/images/{LANG}/edit.png" /></a> # ENDIF #</h3>
			</div>	
			<div class="news_content" style="border-bottom:none;">
				<ul style="margin:20px;">
				# START list #						
					<li>
						{list.ICON} <a href="{list.U_NEWS}">{list.TITLE}</a>	({list.COM})
					</li>		
				# END list #
				</ul>	
			</div>
			<div class="news_bottom_l"></div>		
			<div class="news_bottom_r"></div>
			<div class="news_bottom"></div>
		</div>
		