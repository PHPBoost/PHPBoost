		<script type="text/javascript">
		<!--
			function Confirm()
			{
				return confirm("{L_ALERT_DELETE_NEWS}");
			}
		-->
		</script>
		
		# IF C_ADD_OR_WRITER #
		<div class="module_actions">
			# IF C_ADD #
			<a href="{U_ADD}" title="{L_ADD}" class="img_link">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/add.png" class="valign_middle" alt="{L_ADD}" />
			</a>
			# ENDIF #
			&nbsp;
			# IF C_WRITER #
			<a href="{PATH_TO_ROOT}/news/news.php?user=1" title="{L_NEWS_WAITING}">
				<img src="{PATH_TO_ROOT}/news/news_mini.png" class="valign_middle" alt="{L_NEWS_WAITING}" />
			</a>
			# ENDIF #
		</div>
		<div class="spacer"></div>
		# ENDIF #

		# IF C_EDITO #
		<div class="module_position edito">
			<div class="module_top_l"></div>
			<div class="module_top_r"></div>
			<div class="module_top">
        		<div class="module_top_title">
					<a href="{U_SYNDICATION}" title="{L_SYNDICATION}" class="img_link">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="{L_SYNDICATION}" />
					</a>
					{EDITO_NAME}
				</div>
        		<div class="module_top_com">
					# IF C_ADMIN #
					<a href="{U_ADMIN}" title="{L_ADMIN}" class="img_link">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_ADMIN}" />
					</a>
					# ENDIF #
				</div>
				<div class="spacer"></div>
    		</div>
			# IF EDITO_CONTENTS #
    		<div class="module_contents">
        		{EDITO_CONTENTS}
    		</div>
			# ENDIF #
			<div class="module_bottom_l"></div>
           	<div class="module_bottom_r"></div>
           	<div class="module_bottom"></div>
		</div>
		# ENDIF #

		# IF C_NEWS_NO_AVAILABLE #
		<div class="module_position edito">
			<div class="module_top_l"></div>
			<div class="module_top_r"></div>
			<div class="module_top">
               	<div class="module_top_title">
					<a href="{U_SYNDICATION}" title="{L_SYNDICATION}" class="img_link">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="{L_SYNDICATION}" />
					</a>
					{L_LAST_NEWS}
				</div>
        		<div class="module_top_com">
					# IF C_ADMIN #
					<a href="{U_ADMIN}" title="{L_ADMIN}" class="img_link">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_ADMIN}" />
					</a>
					# ENDIF #
				</div>
				<div class="spacer"></div>
    		</div>
    		<div class="module_contents" style="text-align:center;">
        		{L_NO_NEWS_AVAILABLE}
    		</div>
    		<div class="module_bottom_l"></div>
           	<div class="module_bottom_r"></div>
           	<div class="module_bottom"></div>
		</div>
		# ELSE #
			<div style="overflow:hidden;">
			# START news #
			# IF news.C_NEWS_ROW #
				<div class="spacer"></div>
			# ENDIF #
			# IF C_NEWS_BLOCK_COLUMN #
				<div class="module_position" style="float:left;width:{COLUMN_WIDTH}%"># ELSE #<div class="module_position">
			# ENDIF #
	    		<div class="module_top_l"></div>
				<div class="module_top_r"></div>
				<div class="module_top">
					<div class="module_top_title">
						<a href="{news.U_SYNDICATION}" title="{L_SYNDICATION}" class="img_link">
							<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="{L_SYNDICATION}" />
						</a>
	        			<a href="{news.U_LINK}">{news.TITLE}</a>
	        		</div>
	        		<div class="module_top_com">
						# IF news.C_COM #
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/com_mini.png" alt="" class="valign_middle" /> <a href="{news.U_COM}">{news.COM}</a>
						# ENDIF #
						# IF news.C_EDIT #
						<a href="{PATH_TO_ROOT}/news/management.php?edit={news.ID}" title="{L_EDIT}" class="img_link">
							<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_EDIT}" />
						</a>
						# ENDIF #
						# IF news.C_DELETE #
						<a href="{PATH_TO_ROOT}/news/management.php?del={news.ID}&amp;token={TOKEN}" title="{L_DELETE}" onclick="javascript:return Confirm();">
							<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" />
						</a>
						# ENDIF #
					</div>
					<div class="spacer"></div>
	    		</div>
	    		<div class="module_contents">
	        		# IF news.C_IMG #<img src="{news.IMG}" alt="{news.IMG_DESC}" title="{news.IMG_DESC}" class="img_right" /># ENDIF #
					# IF news.C_ICON #<a href="{news.U_CAT}"><img class="valign_middle" src="{news.ICON}" alt="" /></a># ENDIF #
					{news.CONTENTS}
					
					# IF news.C_EXTEND_CONTENTS #
						<br /><br />
		        		{news.EXTEND_CONTENTS}
	        		# ENDIF #
					<br /><br />
					<div class="spacer"></div>
	    		</div>
	    		<div class="module_bottom_l"></div>
				<div class="module_bottom_r"></div>
				<div class="module_bottom">
					<div style="float:left"># IF news.PSEUDO #<a class="small_link {news.LEVEL}" href="{news.U_USER_ID}">{news.PSEUDO}</a>, # ENDIF # # IF news.C_DATE # {news.DATE} # ENDIF #</div>
					<div class="spacer"></div>
	    		</div>
			</div>
			# END news #
			</div>
			# IF PAGINATION #<div class="text_center">{PAGINATION}</div># ENDIF #
		# ENDIF #