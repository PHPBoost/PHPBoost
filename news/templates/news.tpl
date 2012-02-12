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
                <div style="float:left;padding-left:30px;"><h3 class="title">{TITLE}</h3></div>
                <div style="float:right;"># IF C_IS_ADMIN # <a href="../news/admin_news_config.php" title="{L_EDIT}"><img src="../templates/{THEME}/images/{LANG}/edit.png" class="valign_middle" alt="{L_EDIT}" /></a> # ENDIF #</div>
            </div>
            <div class="news_content">
                &nbsp;&nbsp;{CONTENTS}
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
                <div style="float:left;padding-left:30px;"><a href="../syndication.php?m=news" title="Syndication"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" /></a></div>
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
		
        # START news #        
        # IF news.C_NEWS_ROW # <div class="spacer"></div> # ENDIF #
		# IF C_NEWS_BLOCK_COLUMN # 
        <div class="news_container" style="float:left;width:{COLUMN_WIDTH}%">
		# ELSE #
        <div class="news_container">
		# ENDIF #
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <span style="float:left;padding-left:5px;">
                    <a href="../syndication.php?m=news" title="Rss"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Rss" title="Rss" /></a>
                    <a class="news_title" href="../news/news{news.U_NEWS_LINK}">{news.TITLE}</a>
                </span>
                <span style="float:right;">
					<img src="{PATH_TO_ROOT}/news/templates/images/comments.png" alt="" class="valign_middle" /> {news.U_COM}
					# IF C_IS_ADMIN #
					<a href="../news/admin_news.php?id={news.ID}" title="{L_EDIT}"><img class="valign_middle" src="../templates/{THEME}/images/{LANG}/edit.png" alt="{L_EDIT}" /></a>
					<a href="../news/admin_news.php?delete=1&amp;id={news.ID}&amp;token={TOKEN}" title="{L_DELETE}" onclick="javascript:return Confirm();"><img class="valign_middle" src="../templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" /></a>
					# ENDIF #
				</span>
            </div>
            <div class="news_content">
                # IF news.IMG # <img src="{news.IMG}" alt="{news.IMG_DESC}" title="{news.IMG_DESC}" class="img_right" /> # ENDIF # 
				# IF news.C_ICON # <a href="news.php?cat={news.IDCAT}"><img class="valign_middle" src="{news.ICON}" alt="" /></a> # ENDIF # 
                
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
		
        # IF C_NEWS_NAVIGATION_LINKS #
        <div style="width:90%;padding:20px;margin:auto;margin-top:-15px;">
            # IF C_PREVIOUS_NEWS # <span style="float:left;"><a href="news{U_PREVIOUS_NEWS}"><img src="../templates/{THEME}/images/left.png" alt="" class="valign_middle" /></a> <a href="news{U_PREVIOUS_NEWS}">{PREVIOUS_NEWS}</a></span> # ENDIF #
           # IF C_NEXT_NEWS # <span style="float:right;"><a href="news{U_NEXT_NEWS}">{NEXT_NEWS}</a> <a href="news{U_NEXT_NEWS}"><img src="../templates/{THEME}/images/right.png" alt="" class="valign_middle" /></a></span> # ENDIF #
        </div>
        # ENDIF #
        
		<div class="spacer"></div>
        <div class="text_center">{PAGINATION}</div>
        <div class="text_center">{ARCHIVES}</div>
		<div class="spacer"></div>
        # ENDIF #
        
		
		
        # IF C_NEWS_LINK #		
		<div class="news_container">
			<div class="news_top_l"></div>			
			<div class="news_top_r"></div>
			<div class="news_top">
				<div style="float:left"><a href="../syndication.php?m=news" title="Syndication"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" /></a> <h3 class="title valign_middle">{L_LAST_NEWS}</h3></div>
				<div style="float:right"># IF C_IS_ADMIN # &nbsp;&nbsp;<a href="admin_news_cat.php?id={IDCAT}" title="{L_EDIT}"><img class="valign_middle" src="../templates/{THEME}/images/{LANG}/edit.png" /></a> # ENDIF #</div>
			</div>	
			<div class="news_content">
		
			# START list #
				# IF list.C_NEWS_ROW # 
				<div class="spacer"></div> 
				# ENDIF #
				
				# IF C_NEWS_LINK_COLUMN # 
				<div style="float:left;width:{COLUMN_WIDTH}%">
				# ELSE #
				<div>
				# ENDIF #
					<ul style="margin:0;padding:0;list-style-type:none;">
						<li><img src="../templates/{THEME}/images/li.png" alt="" /> {list.ICON} <span class="text_small">{list.DATE} :</span> <a href="{list.U_NEWS}">{list.TITLE}</a></li>
					</ul>
				</div>
			# END list #
			
				<div class="spacer">&nbsp;</div>
				<div class="text_center">{PAGINATION}</div>
				<div class="text_center">{ARCHIVES}</div>				
			</div>
			<div class="news_bottom_l"></div>		
			<div class="news_bottom_r"></div>
			<div class="news_bottom"></div>
		</div>		
		# ENDIF #
		