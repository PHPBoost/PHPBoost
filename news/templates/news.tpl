        <script type="text/javascript">
        <!--
        	function Confirm() 
			{
				return confirm("{L_ALERT_DELETE_NEWS}");
        	}
        -->
        </script>
        
        # IF C_NEWS_EDITO #
        <div class="news_container edito">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
                <div style="float:left;">
					<h3 class="title">{TITLE}</h3>
				</div>
                <div style="float:right;">
					# IF C_ADD #
					<a href="management.php?new=1" title="{L_ADD}">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/add.png" class="valign_middle" alt="{L_ADD}" />
					</a>
					# ENDIF #
					# IF C_IS_ADMIN #
					<a href="admin_news_config.php#preview_description" title="{L_EDIT}">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" class="valign_middle" alt="{L_EDIT}" />
					</a>
					# ENDIF #
				</div>
            </div>
            <div class="news_content">
                {CONTENTS}
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
		# ELSEIF C_ADD #
		<div style="text-align:center;margin:25px auto;">
			<a href="management.php?new=1" title="{L_ADD}">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/add.png" class="valign_middle" alt="{L_ADD}" />
			</a>
		</div>
        # ENDIF #

		# IF C_NEWS_BLOCK #
		# START news #
       		# IF news.C_NEWS_ROW #<div class="spacer"></div># ENDIF #
			# IF C_NEWS_BLOCK_COLUMN #<div class="news_container" style="float:left;width:{COLUMN_WIDTH}%"># ELSE #<div class="news_container"># ENDIF #
            	<div class="news_top_l"></div>
				<div class="news_top_r"></div>
            	<div class="news_top">
                	<span style="float:left;">
                		# IF news.IDCAT #
						<a href="../syndication.php?m=news&amp;cat={news.IDCAT}" title="Syndication">
						# ELSE #
						<a href="../syndication.php?m=news" title="Syndication">
						# ENDIF #
							<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="Rss" title="Rss" />
						</a>
                		<a class="news_title" href="{news.U_NEWS_LINK}">{news.TITLE}</a>
                	</span>
                	<span style="float:right;">
						# IF news.U_COM #
							<img src="{PATH_TO_ROOT}/news/templates/images/comments.png" alt="" class="valign_middle" /> {news.U_COM}
						# ENDIF #
						# IF news.C_EDIT #
							<a href="management.php?edit={news.ID}" title="{L_EDIT}">
								<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_EDIT}" />
							</a>
						# ENDIF #
						# IF news.C_DELETE #
							<a href="management.php?del={news.ID}&amp;token={TOKEN}" title="{L_DELETE}" onclick="javascript:return Confirm();">
								<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" />
							</a>
						# ENDIF #
					</span>
            	</div>
            	<div class="news_content">
                	# IF news.C_IMG #<img src="{news.IMG}" alt="{news.IMG_DESC}" title="{news.IMG_DESC}" class="img_right" /># ENDIF #
					# IF news.C_ICON #<a href="{news.U_CAT}"><img class="valign_middle" src="{news.ICON}" alt="" /></a># ENDIF #                
					{news.CONTENTS}
					<br /><br />
                	{news.EXTEND_CONTENTS}
					<br /><br />
					<div class="spacer"></div>
            	</div>
            	<div class="news_bottom_l"></div>
            	<div class="news_bottom_r"></div>
            	<div class="news_bottom">
                	<span style="float:left"># IF news.PSEUDO #<a href="{news.U_USER_ID}"{news.LEVEL}>{news.PSEUDO}</a># ENDIF #</span>
                	<span style="float:right">{news.DATE}</span>
            	</div>
       		</div>
		# END news #
		
       	# IF C_NEWS_NAVIGATION_LINKS #
       		<div style="width:90%;padding:20px;margin:auto;margin-top:-15px;">
           		# IF C_PREVIOUS_NEWS #
					<span style="float:left;">
						<a href="news{U_PREVIOUS_NEWS}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/left.png" alt="" class="valign_middle" /></a>
						<a href="news{U_PREVIOUS_NEWS}">{PREVIOUS_NEWS}</a>
					</span>
				# ENDIF #
          			# IF C_NEXT_NEWS #
						<span style="float:right;">
						<a href="news{U_NEXT_NEWS}">{NEXT_NEWS}</a>
						<a href="news{U_NEXT_NEWS}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/right.png" alt="" class="valign_middle" /></a>
					</span>
					# ENDIF #
       		</div>
       	# ENDIF #
			<div class="spacer"></div>
			# IF PAGINATION #<div class="text_center">{PAGINATION}</div># ENDIF #
			# IF ARCHIVES #<div class="text_center">{ARCHIVES}</div># ENDIF #
			<div class="spacer"></div>
			{COMMENTS}	
		# ELSEIF C_NEWS_LINK #		
		<div class="news_container">
			<div class="news_top_l"></div>			
			<div class="news_top_r"></div>
			<div class="news_top">
				<div style="float:left">
					# IF IDCAT #
					<a href="../syndication.php?m=news&amp;cat={IDCAT}" title="Syndication">
					# ELSE #
					<a href="../syndication.php?m=news" title="Syndication">
					# ENDIF #
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" />
					</a>
					<h3 class="title valign_middle">{NAME_NEWS}</h3>
				</div>
				<div style="float:right">
					# IF C_IS_ADMIN #
						# IF IDCAT #
						<a href="admin_news_cat.php?edit={IDCAT}" title="{L_EDIT}">
						# ELSE #
						<a href="admin_news_config.php" title="{L_EDIT}">
						# ENDIF #
							<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_EDIT}" />
						</a>
					# ENDIF #
				</div>
			</div>	
			<div class="news_content">
			# START list #
				# IF list.C_NEWS_ROW #<div class="spacer"></div># ENDIF #
				# IF C_NEWS_LINK_COLUMN # 
					<div style="float:left;width:{COLUMN_WIDTH}%">
				# ELSE #
					<div>
				# ENDIF #
						<ul style="margin:0;padding:0;list-style-type:none;">
							<li>
								<img src="{PATH_TO_ROOT}/templates/{THEME}/images/li.png" alt="" />
								# IF list.ICON #<a href="{list.U_CAT}"><img class="valign_middle" src="{list.ICON}" alt="" /></a># ENDIF #
								# IF list.DATE #<span class="text_small">{list.DATE} : </span># ENDIF #
								<a href="{list.U_NEWS}">{list.TITLE}</a>
								# IF list.C_COM #({list.COM})# ENDIF #
							</li>
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
		# ELSEIF C_NEWS_NO_AVAILABLE #
		<div class="news_container">
            <div class="news_top_l"></div>
            <div class="news_top_r"></div>
            <div class="news_top">
				<a href="../syndication.php?m=news" title="Syndication">
					<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="Syndication" title="Syndication" />
				</a>
				<h3 class="title valign_middle">{L_LAST_NEWS}</h3>
            </div>  
            <div class="news_content">
                <p class="text_strong text_center">{L_NO_NEWS_AVAILABLE}</p>
            </div>
            <div class="news_bottom_l"></div>
            <div class="news_bottom_r"></div>
            <div class="news_bottom"></div>
        </div>
		# ENDIF #
		