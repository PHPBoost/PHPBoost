		<script type="text/javascript">
		<!--
		function Confirm() {
		return confirm("{L_ALERT_DELETE_NEWS}");
		}
		-->
		</script>
		
		
		# START edito #
		<div class="news_container">
			<div class="msg_top_l"></div>			
			<div class="msg_top_r"></div>
			<div class="msg_top">
				<div style="float:left"><a href="rss.php" title="Rss"><img class="valign_middle" src="../templates/{THEME}/images/rss.gif" alt="Rss" title="Rss" /></a> <h3 class="title valign_middle">{edito.TITLE}</h3></div>
				<div style="float:right">{edito.EDIT}</div>
			</div>	
						
			<div class="news_content">
				{edito.CONTENTS}
			</div>
			
			<div class="news_bottom_l"></div>		
			<div class="news_bottom_r"></div>
			<div class="news_bottom"></div>
		</div>
		# END edito #
		
		
		# START no_news_available #
		<div class="news_container">
			<div class="msg_top_l"></div>			
			<div class="msg_top_r"></div>
			<div class="msg_top">
				<a href="rss.php" title="Rss"><img class="valign_middle" src="../templates/{THEME}/images/rss.gif" alt="Rss" title="Rss" /></a> <h3 class="title valign_middle">{L_LAST_NEWS}</h3>
			</div>	
			<div class="news_content">
				<p class="text_strong" style="text-align:center">{no_news_available.L_NO_NEWS_AVAILABLE}</p>
			</div>
			<div class="news_bottom_l"></div>		
			<div class="news_bottom_r"></div>
			<div class="news_bottom"></div>
		</div>
		# END no_news_available #
		
		
		{START_TABLE_NEWS}		
		# START news #
		
		{news.NEW_ROW}
		<div class="news_container">
			<div class="msg_top_l"></div>			
			<div class="msg_top_r"></div>
			<div class="msg_top">
				<div style="float:left"><a href="rss.php" title="Rss"><img class="valign_middle" src="../templates/{THEME}/images/rss.gif" alt="Rss" title="Rss" /></a> <a href="news{news.U_NEWS_LINK}" class="news_title">{news.TITLE}</a></div>
				<div style="float:right">{news.COM}{news.EDIT}{news.DEL}</div>
			</div>							
			<div class="news_content">
				{news.IMG}
				{news.ICON} 
				{news.CONTENTS}					
				<br /><br />
				{news.EXTEND_CONTENTS}	
			</div>			
			<div class="news_bottom_l"></div>		
			<div class="news_bottom_r"></div>
			<div class="news_bottom">
				<span style="float:left"><a class="small_link" href="../member/member{news.U_MEMBER_ID}">{news.PSEUDO}</a></span>
				<span style="float:right">{news.DATE}</span>
			</div>
		</div>
		
		# INCLUDE handle_com #
		
		# END news #			
		{END_TABLE_NEWS}
		
		
		# START news_link #
			
		<div class="news_container">
			<div class="msg_top_l"></div>			
			<div class="msg_top_r"></div>
			<div class="msg_top">
				<div style="float:left"><a href="rss.php" title="Rss"><img class="valign_middle" src="../templates/{THEME}/images/rss.gif" alt="Rss" title="Rss" /></a> <h3 class="title valign_middle">{L_LAST_NEWS}</h3></div>
				<div style="float:right">{news.COM}{news.EDIT}{news.DEL}</div>
			</div>	
						
			<div class="news_content">
				{news_link.START_TABLE_NEWS}
				# START news_link.list #
					{news_link.list.NEW_ROW}
						<li><img src="../templates/{THEME}/images/li.png" alt="" /> {news_link.list.ICON} <span style="font-weight:bold;font-size:11px;">{news_link.list.DATE}:</span> <a href="{news_link.list.U_NEWS}" style="font-size:11px;">{news_link.list.TITLE}</a></li>
				# END news_link.list #
				{news_link.END_TABLE_NEWS}	
			</div>
			
			<div class="news_bottom_l"></div>		
			<div class="news_bottom_r"></div>
			<div class="news_bottom"></div>
		</div>	
		
		# END news_link #
		
		<div style="text-align: center;">{PAGINATION}</div>
		