		<script type="text/javascript">
		<!--
		function Confirm() {
		return confirm("{L_ALERT_DELETE_NEWS}");
		}
		-->
		</script>
		
		
		# IF C_NEWS_EDITO #
		<div class="news_container">
			<div class="news_top_l"></div>			
			<div class="news_top_r"></div>
			<div class="news_top">
				<div style="float:left"><a href="syndication.php" title="Syndication"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" /></a> <h3 class="title valign_middle">{TITLE}</h3></div>
				<div style="float:right">{EDIT}</div>
			</div>	
			<div class="news_content">
				{CONTENTS}
			</div>
			<div class="news_bottom_l"></div>		
			<div class="news_bottom_r"></div>
			<div class="news_bottom"></div>
		</div>
		# ENDIF #
		
		
		# IF C_NEWS_NO_AVAILABLE #
		<div class="news_container">
			<div class="news_top_l"></div>			
			<div class="news_top_r"></div>
			<div class="news_top">
				<a href="syndication.php" title="Syndication"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" /></a> <h3 class="title valign_middle">{L_LAST_NEWS}</h3>
			</div>	
			<div class="news_content">
				<p class="text_strong text_center">{L_NO_NEWS_AVAILABLE}</p>
			</div>
			<div class="news_bottom_l"></div>		
			<div class="news_bottom_r"></div>
			<div class="news_bottom"></div>
		</div>
		# ENDIF #
		
		
		# IF C_NEWS_BLOCK #
		{START_TABLE_NEWS}		
		# START news #
		
		{news.NEW_ROW}
		<div class="news_container">
			<div class="news_top_l"></div>			
			<div class="news_top_r"></div>
			<div class="news_top">
				<div style="float:left"><a href="syndication.php" title="Syndication"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" /></a> <a href="news{news.U_NEWS_LINK}" class="news_title">{news.TITLE}</a></div>
				<div style="float:right">{news.COM}{news.EDIT}{news.DEL}</div>
			</div>							
			<div class="news_content">
				{news.IMG}
				{news.ICON} 
				{news.CONTENTS}					
				<br /><br />
				{news.EXTEND_CONTENTS}
				<div class="spacer"></div>
			</div>			
			<div class="news_bottom_l"></div>		
			<div class="news_bottom_r"></div>
			<div class="news_bottom">
				<span style="float:left"><a class="small_link" href="../member/member{news.U_USER_ID}">{news.PSEUDO}</a></span>
				<span style="float:right">{news.DATE}</span>
			</div>
		</div>		
		
		{COMMENTS}
		
		# END news #			
		{END_TABLE_NEWS}
		<div class="text_center">{PAGINATION}</div>
		<div class="text_center">{ARCHIVES}</div>
		# ENDIF #
		
		# IF C_NEWS_LINK #		
		<div class="news_container">
			<div class="news_top_l"></div>			
			<div class="news_top_r"></div>
			<div class="news_top">
				<div style="float:left"><a href="syndication.php" title="Syndication"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" /></a> <h3 class="title valign_middle">{L_LAST_NEWS}</h3></div>
				<div style="float:right">{news.COM}{news.EDIT}{news.DEL}</div>
			</div>	
			<div class="news_content">
				{START_TABLE_NEWS}
				# START list #
					{list.NEW_ROW}
						<li><img src="../templates/{THEME}/images/li.png" alt="" /> {list.ICON} <span class="text_small">{list.DATE} :</span> <a href="{list.U_NEWS}" class="small_link">{list.TITLE}</a></li>
				# END list #
				{END_TABLE_NEWS}
				<br />
				<div class="text_center">{PAGINATION}</div>
				<div class="text_center">{ARCHIVES}</div>				
			</div>
			<div class="news_bottom_l"></div>		
			<div class="news_bottom_r"></div>
			<div class="news_bottom"></div>
		</div>		
		# ENDIF #
		