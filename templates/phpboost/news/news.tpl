		<script type="text/javascript">
		<!--
		function Confirm() {
		return confirm("{L_ALERT_DELETE_NEWS}");
		}
		-->
		</script>
		
		
		# START edito #
		<div class="edito_top">
			<div style="float:left;padding-left:30px;padding-top:5px;"><h3 class="title">{edito.TITLE}</h3></div>
			<div style="float:right">{edito.EDIT}</div>
		</div>
		<div class="news_container">
			<div class="news_content">
				<img src="../templates/phpboost/news/images/phpboost_box_v2_mini.jpg" alt="PHPBoost 2.0" class="float_right" />
				<img src="../templates/phpboost/news/images/phpboost_version.jpg" alt="PHPBoost 2.0" />
				<br />
				&nbsp;&nbsp;{edito.CONTENTS} 

				<div style="width:350px;height:98px;margin:auto;background:url(../templates/phpboost/news/images/phpboost_download.jpg) no-repeat;">
					<div style="position:relative;width:230px;left:90px;top:35px;text-indent:5px;">
						<a href="http://www.phpboost.com/download/download-2-52+phpboost-2-0.php" style="color:#2A3B6C;font-size:16px;" title="Télécharger PHPBoost">Téléchargement gratuit et immédiat</a>
						
						<p class="text_small">Version 2.0, Multilingue, 3.5Mo</p>
					</div>
				</div>
				<div style="width:350px;margin:auto;text-align:center"><a href="http://www.phpboost.com/wiki/installation" class="small_link" title="Aide installation">Aide installation</a> | <a href="http://www.phpboost.com/wiki/wiki.php" class="small_link" title="Documentation">Documentation</a> | <a href="http://www.phpboost.com/forum/index.php" class="small_link" title="Support PHPBoost">Support</a> | <a href="http://demo.phpboost.com" class="small_link" title="Démonstration">Démonstration</a></div>
			</div>
			
			<div class="news_bottom_l"></div>		
			<div class="news_bottom_r"></div>
			<div class="news_bottom"></div>
		</div>
		# END edito #
		
		
		# START no_news_available #
		<div class="news_container">
			<div class="news_top_l"><a href="rss.php" title="Rss"><img class="valign_middle" src="../templates/{THEME}/images/rss.gif" alt="Rss" title="Rss" /></a></div>			
			<div class="news_top_r"></div>
			<div class="news_top">
				<h3 class="title valign_middle">{L_LAST_NEWS}</h3>
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
			<div class="news_top_l"><a href="rss.php" title="Rss"><img class="valign_middle" src="../templates/{THEME}/images/rss.gif" alt="Rss" title="Rss" /></a></div>			
			<div class="news_top_r"></div>
			<div class="news_top">
				<div style="float:left"><h3 class="title valign_middle">{news.TITLE}</h3></div>
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
				<span style="float:right">{L_ON}: {news.DATE}</span>
			</div>
		</div>
		
		# INCLUDE handle_com #
		
		# END news #			
		{END_TABLE_NEWS}
		
		
		# START news_link #
			
		<div class="news_container">
			<div class="news_top_l"></div>			
			<div class="news_top_r"></div>
			<div class="news_top">
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
		<div style="text-align: center;">{ARCHIVES}</div>
		
		<div class="news_container" style="width:500px;margin-top:20px;">
			<div class="news_top_l"><a href="../forum/rss.php" title="Rss"><img class="valign_middle" src="../templates/{THEME}/images/rss.gif" alt="Rss" title="Rss" /></a></div>			
			<div class="news_top_r"></div>
			<div class="news_top">
				<h3 class="title valign_middle">Derniers sujets du forum</h3>
			</div>						
			<div class="news_content" style="text-align:center">
				<script type="text/javascript" src="../cache/rss_forum.html"></script>  
			</div>			
			<div class="news_bottom_l"></div>		
			<div class="news_bottom_r"></div>
			<div class="news_bottom"></div>
		</div>
		