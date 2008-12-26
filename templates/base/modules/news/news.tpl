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
                <div style="float:right;">{EDIT}</div>
            </div>
            <div class="news_content">
                <img src="../templates/phpboost/news/images/phpboost_box_v2_mini.jpg" alt="PHPBoost 2.0" class="float_right" />
                <img src="../templates/phpboost/news/images/phpboost_version.jpg" alt="PHPBoost 2.0" />
                <br />
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
                <div style="float:left;padding-left:30px;"><a href="syndication.php" title="Syndication"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" /></a></div>
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
                <span style="float:left;padding-left:5px;">
                    <a href="syndication.php" title="Rss"><img class="valign_middle" src="../templates/{THEME}/images/rss.png" alt="Rss" title="Rss" /></a>
                    <a class="news_title" href="news{news.U_NEWS_LINK}">{news.TITLE}</a>
                </span>
                <span style="float:right;">{news.COM}{news.EDIT}{news.DEL}</span>
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
			<div class="msg_top_l"></div>			
			<div class="msg_top_r"></div>
			<div class="msg_top">
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
		