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
				<div style="float:left;padding-left:30px;"><h3 class="title">{edito.TITLE}</h3></div>
				<div style="float:right;">{edito.EDIT}</div>
			</div>
			<div class="news_content">
				<img src="../templates/phpboost/news/images/phpboost_box_v2_mini.jpg" alt="PHPBoost 2.0" class="float_right" />
				<img src="../templates/phpboost/news/images/phpboost_version.jpg" alt="PHPBoost 2.0" />
				<br />
				&nbsp;&nbsp;{edito.CONTENTS} 

				<div style="width:350px;height:98px;margin:auto;background:url(../templates/phpboost/news/images/phpboost_download.jpg) no-repeat;">
					<div style="position:relative;width:230px;left:90px;top:35px;text-indent:5px;text-align:left;">
						<a href="http://www.phpboost.com/download/download-2-52+phpboost-2-0.php" style="color:#F3F6FA;font-size:16px;" title="Télécharger PHPBoost">Téléchargement gratuit et immédiat</a>
						
						<p class="text_small" style="color:#F3F6FA">Version 2.0, Multilingue, 3.5Mo</p>
					</div>
				</div>
				<div style="width:350px;margin:auto;text-align:center"><a href="http://www.phpboost.com/wiki/installation" class="small_link" title="Aide installation">Aide installation</a> | <a href="http://www.phpboost.com/wiki/wiki.php" class="small_link" title="Documentation">Documentation</a> | <a href="http://www.phpboost.com/forum/index.php" class="small_link" title="Support PHPBoost">Support</a> | <a href="http://demo.phpboost.com" class="small_link" title="Démonstration">Démonstration</a></div>
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
				<div style="float:left;padding-left:30px;"><a href="rss.php" title="Rss"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Rss" title="Rss" /></a></div>
				<div style="float:right;"><h3 class="title valign_middle">{L_LAST_NEWS}</h3></div>
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
				<span style="float:left;padding-left:5px;"><a href="rss.php" title="Rss"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Rss" title="Rss" /></a> &nbsp;&nbsp;<a class="news_title" href="news{news.U_NEWS_LINK}">{news.TITLE}</a></span>
				<span style="float:right;">{news.COM}{news.EDIT}{news.DEL}</span>
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
		
		# IF C_NEWS_NAVIGATION_LINKS #
		<div style="width:90%;padding:20px;margin:auto;margin-top:-15px;">
			<span style="float:left;">{U_PREVIOUS_NEWS}</span>
			<span style="float:right;">{U_NEXT_NEWS}</span>
		</div>
		# ENDIF #
		
		<div class="text_center">{PAGINATION}</div>
		<div class="text_center">{ARCHIVES}</div>
		# ENDIF #
		
		
		# IF C_NEWS_LINK #			
		<div class="news_container" style="float:left;width:45%;margin-left:25px;">
			<div class="news_top_l"></div>			
			<div class="news_top_r"></div>
			<div class="news_top">
				<div style="float:left"><a href="rss.php" title="Rss"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Rss" title="Rss" /></a> <h3 class="title valign_middle">{L_LAST_NEWS}</h3></div>
				<div style="float:right">{news.COM}{news.EDIT}{news.DEL}</div>
			</div>	
			<div class="news_content">
				{START_TABLE_NEWS}
				# START list #
					{list.NEW_ROW}
						<li><img src="../templates/{THEME}/images/li.png" alt="" /> {list.ICON} <span class="text_small text_strong">{list.DATE}</span> <a href="{list.U_NEWS}">{list.TITLE}</a></li>
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
		
		<div class="news_container" style="float:left;width:45%;margin-left:25px;">
			<div class="news_top_l"></div>			
			<div class="news_top_r"></div>
			<div class="news_top">
				<h3 class="title valign_middle"><a href="../forum/rss.php" title="Rss"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Rss" title="Rss" /></a> Dossier</h3>
			</div>						
			<div class="news_content">
				<div style="float:left;width:73px"><img src="http://img.clubic.com/photo/0049003701299648.jpg" alt="Aventure au sommet, le Pic Rouge en Test" /></div>
				<div style="float:left;width:250px;padding-left:6px;">
					<a href="">Comment sécuriser votre site</a> 					
					<p class="text_small">Le 28/04/2008 - <a href="" class="small_link">Lire l'article</a></p>
				</div>
				<div class="spacer"></div>
				
				<div style="float:left;width:73px"><img src="http://img.clubic.com/photo/0049003701295144.jpg" alt="Aventure au sommet, le Pic Rouge en Test" /></div>
				<div style="float:left;width:250px;padding-left:6px;">
					<a href="">La programmation sous PHPBoost</a> 					
					<p class="text_small">Le 28/04/2008 - <a href="" class="small_link">Lire l'article</a></p>
				</div>
				<div class="spacer"></div>
				
				<div style="float:left;width:73px"><img src="http://img.clubic.com/photo/0049003701294702.jpg" alt="Aventure au sommet, le Pic Rouge en Test" /></div>
				<div style="float:left;width:250px;padding-left:6px;">
					<a href="">Installer un nouveau module</a> 					
					<p class="text_small">Le 28/04/2008 - <a href="" class="small_link">Lire l'article</a></p>
				</div>
				<div class="spacer"></div>
			</div>
			<div class="news_bottom_l"></div>		
			<div class="news_bottom_r"></div>
			<div class="news_bottom"></div>
		</div>
		
		<div class="news_container" style="float:left;width:45%;margin-left:25px;">
			<div class="news_top_l"></div>			
			<div class="news_top_r"></div>
			<div class="news_top">
				<h3 class="title valign_middle"><a href="../forum/rss.php" title="Rss"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Rss" title="Rss" /></a> Derniers sujets du forum</h3>
			</div>						
			<div class="news_content">
				<script type="text/javascript" src="../cache/rss_forum.html"></script>  
			</div>			
			<div class="news_bottom_l"></div>		
			<div class="news_bottom_r"></div>
			<div class="news_bottom"></div>
		</div>
		
		# ENDIF #	

		
		
		