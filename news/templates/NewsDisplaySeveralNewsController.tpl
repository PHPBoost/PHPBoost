<script type="text/javascript">
<!--
	function Confirm()
	{
		return confirm("${i18n('news.message.delete')}");
	}
-->
</script>
		
<div class="module_actions">
	# IF C_ADD #
	<a href="{U_ADD}" title="${i18n('news.add')}" class="img_link">
		<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/add.png" class="valign_middle" alt="${i18n('news.add')}" />
	</a>
	# ENDIF #
	&nbsp;
	# IF C_PENDING_NEWS #
	<a href="{U_PENDING_NEWS}" title="${i18n('news.pending')}">
		<img src="{PATH_TO_ROOT}/news/news_mini.png" class="valign_middle" alt="${i18n('news.pending')}" />
	</a>
	# ENDIF #
</div>
<div class="spacer"></div>

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
			</div>
			<div class="spacer"></div>
    	</div>
    	<div class="module_contents" style="text-align:center;">
        	${i18n('news.message.no_items')}
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
		<div class="module_position" style="float:left;width:{COLUMN_WIDTH}%">
    		<div class="module_top_l"></div>
			<div class="module_top_r"></div>
			<div class="module_top">
				<div class="module_top_title">
					<a href="{news.U_SYNDICATION}" title="{L_SYNDICATION}" class="img_link">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="{L_SYNDICATION}" />
					</a>
        			<a href="{news.U_LINK}">{news.NAME}</a>
        		</div>
        		<div class="module_top_com">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/com_mini.png" alt="" class="valign_middle" /> <a href="{news.U_COMMENTS}">{news.L_COMMENTS}</a>
					# IF news.C_EDIT #
					<a href="{news.U_EDIT}" title="{L_EDIT}" class="img_link">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_EDIT}" />
					</a>
					# ENDIF #
					# IF news.C_DELETE #
					<a href="{news.U_DELETE}" title="{L_DELETE}" onclick="javascript:return Confirm();">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" />
					</a>
					# ENDIF #
				</div>
				<div class="spacer"></div>
    		</div>
    		<div class="module_contents">
        		# IF news.C_PICTURE #<img src="{news.U_PICTURE}" alt="{news.NAME}" title="{news.NAME}" class="img_right" /># ENDIF #
				{news.CONTENTS}
				<div class="spacer"></div>
    		</div>
    		<div class="module_bottom_l"></div>
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<div style="float:left"># IF news.PSEUDO #<a href="{news.U_AUTHOR_PROFILE}" class="small_link {news.USER_LEVEL_CLASS}" # IF news.C_USER_GROUP_COLOR # style="color:{news.USER_GROUP_COLOR}" # ENDIF #>{news.PSEUDO}</a>, # ENDIF # {news.DATE}</div>
				<div class="spacer"></div>
    		</div>
		</div>
	# END news #
	</div>
	<div class="text_center"># INCLUDE PAGINATION #</div>
# ENDIF #