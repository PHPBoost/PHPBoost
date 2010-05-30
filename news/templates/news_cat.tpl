		<script type="text/javascript">
		<!--
			function Confirm()
			{
				return confirm("{L_ALERT_DELETE_NEWS}");
			}
		-->
		</script>
		
		# IF C_ADD_OR_WRITER #
		<div style="float:right;margin:0 10px 15px;margin-top:-25px;">
			# IF C_ADD #
			<a href="{U_ADD}" title="{L_ADD}">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/add.png" class="valign_middle" alt="{L_ADD}" />
			</a>
			# ENDIF #
			&nbsp;
			# IF C_WRITER #
			<a href="news.php?user=1" title="{L_WRITER}">
				<img src="news_mini.png" class="valign_middle" alt="{L_WRITER}" />
			</a>
			# ENDIF #
		</div>
		<div class="spacer"></div>
		# ENDIF #

		# IF C_EDITO #
		<div class="news_container edito">
    		<div class="news_top_l"></div>
    		<div class="news_top_r"></div>
    		<div class="news_top">
        		<div style="float:left">
					<a href="{U_SYNDICATION}" title="{L_SYNDICATION}">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="{L_SYNDICATION}" />
					</a>
					<h3 class="title">{EDITO_NAME}</h3>
				</div>
        		<div style="float:right">
					# IF C_ADMIN #
					<a href="{U_ADMIN}" title="{L_ADMIN}">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_ADMIN}" />
					</a>
					# ENDIF #
				</div>
				<div class="spacer"></div>
    		</div>
			# IF EDITO_CONTENTS #
    		<div class="news_content">
        		{EDITO_CONTENTS}
    		</div>
			# ENDIF #
    		<div class="news_bottom_l"></div>
    		<div class="news_bottom_r"></div>
    		<div class="news_bottom"></div>
		</div>
		# ENDIF #

		# IF C_NEWS_NO_AVAILABLE #
		<div class="news_container edito">
    		<div class="news_top_l"></div>
    		<div class="news_top_r"></div>
    		<div class="news_top">
        		<div style="float:left">
					<a href="{U_SYNDICATION}" title="{L_SYNDICATION}">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="{L_SYNDICATION}" />
					</a>
					<h3 class="title">{L_LAST_NEWS}</h3>
				</div>
        		<div style="float:right">
					# IF C_ADMIN #
					<a href="{U_ADMIN}" title="{L_ADMIN}">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_ADMIN}" />
					</a>
					# ENDIF #
				</div>
				<div class="spacer"></div>
    		</div>
    		<div class="news_content" style="text-align:center;">
        		{L_NO_NEWS_AVAILABLE}
    		</div>
    		<div class="news_bottom_l"></div>
    		<div class="news_bottom_r"></div>
    		<div class="news_bottom"></div>
		</div>
		# ELSE #
			# START news #
			# IF news.C_NEWS_ROW #<div class="spacer"></div># ENDIF #
			# IF C_NEWS_BLOCK_COLUMN #<div class="news_container" style="float:left;width:{COLUMN_WIDTH}%"># ELSE #<div class="news_container"># ENDIF #
	    		<div class="news_top_l"></div>
				<div class="news_top_r"></div>
	    		<div class="news_top">
	        		<div style="float:left;">
						<a href="{news.U_SYNDICATION}" title="{L_SYNDICATION}">
							<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="{L_SYNDICATION}" />
						</a>
	        			<a href="{news.U_LINK} class="big_link" onclick="document.location = 'count.php?id={news.ID}';">{news.TITLE}</a>
	        		</div>
	        		<div style="float:right">
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
					</div>
					<div class="spacer"></div>
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
					<div style="float:left"># IF news.PSEUDO #<a class="small_link{news.LEVEL}" href="{news.U_USER_ID}">{news.PSEUDO}</a>, # ENDIF # {news.DATE}</div>
					<div style="float:right"># IF news.U_COM #<img src="{PATH_TO_ROOT}/news/templates/images/comments.png" alt="" class="valign_middle" /> {news.U_COM}# ENDIF #</div>
					<div class="spacer"></div>
	    		</div>
			</div>
			# END news #

			# IF PAGINATION #<div class="text_center">{PAGINATION}</div># ENDIF #
		# ENDIF #