        <script type="text/javascript">
        <!--
        function Confirm() {
            return confirm("{L_ALERT_DELETE_NEWS}");
        }
        -->
        </script>
        
        # IF C_NEWS_EDITO #
        <div class="edito_container">
            <div class="edito_top"></div>
            <div class="edito_content">
            
                <div style="padding-left:15px;"><h3 class="title">{TITLE}</h3></div>
                <div style="float:right;padding-right:15px;">{EDIT}</div>
                <div style="padding:10px">&nbsp;&nbsp;{CONTENTS}</div>
            </div>
            <div class="edito_bottom"></div>
        </div>
        # ENDIF #
        
        # IF C_NEWS_NO_AVAILABLE #
        <div class="news_container">
  
            <div class="news_top">
                <div style="float:left;padding-left:30px;"><a href="syndication.php" title="Syndication">
                <img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" /></a></div>
                <div style="float:right;"><h3 class="title valign_middle">{L_LAST_NEWS}</h3></div>
                <div style="clear:both"></div>
            </div>  
            <div class="news_content">
                <p class="text_strong text_center">{L_NO_NEWS_AVAILABLE}</p>
            </div>
            <div class="news_bottom"></div>
        </div>
        # ENDIF #
        
        # IF C_NEWS_BLOCK #
        {START_TABLE_NEWS}
        # START news #
        
        {news.NEW_ROW}
        <div class="news_container">
            <div class="news_top">
                <span style="float:left;padding-left:5px;">
                    <a href="syndication.php" title="Rss"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Rss" title="Rss" /></a>
                    <a class="news_title" href="news{news.U_NEWS_LINK}">{news.TITLE}</a>
                </span>
                <span style="float:right;padding-right:15px">{news.EDIT}<br />{news.DEL}</span>
                <span style="clear:both"></span>
            </div>
            <div class="news_content">
            <br />
                {news.IMG}
                {news.ICON}
                {news.CONTENTS}
                <br /><br />
                {news.EXTEND_CONTENTS}
				<div class="spacer"></div>
				
		<span style="float:left"><a class="small_link" href="../member/member{news.U_USER_ID}">{news.PSEUDO}</a>&nbsp;{news.DATE}</span>
                <span style="float:right">{news.COM}</span>
                <span style="clear:both"></span>
            </div>

            <div class="news_bottom"></div>
        </div>
		
       {COMMENTS}
	   
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
		<div class="news_container">
			
			<div class="news_top">
				<div style="float:left"><a href="syndication.php" title="Syndication"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" /></a> <h3 class="title valign_middle">{L_LAST_NEWS}</h3></div>
				<div style="float:right">{news.COM}{news.EDIT}{news.DEL}</div>
				<div style="clear:both"></div>
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
			
			<div class="news_bottom"></div>
		</div>		
		# ENDIF #
		
