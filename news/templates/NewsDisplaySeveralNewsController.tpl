<script type="text/javascript">
<!--
	function Confirm()
	{
		return confirm("${i18n('news.message.delete')}");
	}
-->
</script>

<div class="module_actions">
	<ul class="module_top_options">
		<li>
			<a><span class="options"></span><span class="caret"></span></a>
			<ul style="width: 150px;">
				# IF C_ADD #
				<li>
					<a href="${relative_url(NewsUrlBuilder::add_news())}" title="${i18n('news.add')}" class="img_link">${i18n('news.add')}</a>
				</li>
				# ENDIF #
				# IF C_PENDING_NEWS #
				<li>
					<a href="${relative_url(NewsUrlBuilder::display_pending_news())}" title="${i18n('news.pending')}">${i18n('news.pending')}</a>
				</li>
				# ENDIF #
			</ul>
		</li>
	</ul>
</div>
<div class="spacer"></div>

# IF C_NEWS_NO_AVAILABLE #
	<div class="module_position edito">
		<div class="module_top_l"></div>
		<div class="module_top_r"></div>
		<div class="module_top">
			<div class="module_top_title module_top_news">
				<a href="${relative_url(SyndicationUrlBuilder::rss('news'))}" title="${i18n('syndication')}" class="img_link">
					<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="${i18n('syndication')}" />
				</a>
				{L_NEWS_NO_AVAILABLE_TITLE}
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
			<div class="module_top module_top_news">
				# IF C_NEWS_BLOCK_COLUMN #
				<ul class="module_top_options block_hidden">
				# ELSE #
				<ul class="module_top_options">
				# ENDIF #
					<li>
						<a class="news_comments" href="{news.U_COMMENTS}">1</a>
					</li>
					<li>
						<a><span class="options"></span><span class="caret"></span></a>
						<ul>
							# IF news.C_EDIT #
							<li>
								<a href="{news.U_EDIT}" title="{L_EDIT}" class="img_link">Editer</a>
							</li>
							# ENDIF #
							# IF news.C_DELETE #
							<li>
								<a href="{news.U_DELETE}" title="{L_DELETE}" onclick="javascript:return Confirm();">Supprimer</a>
							</li>
							# ENDIF #
						</ul>
					</li>
				</ul>
				<div class="module_top_title">
					<a href="{news.U_SYNDICATION}" title="{L_SYNDICATION}" class="img_link">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="{L_SYNDICATION}" />
					</a>
        			<a href="{news.U_LINK}">{news.NAME}</a>
        		</div>
        		
        		<div class="news_author_container">
					Posté par 
					# IF news.PSEUDO #
					<a class="small_link {news.USER_LEVEL_CLASS}" href="{news.U_AUTHOR_PROFILE}" style="font-size: 12px;" # IF news.C_USER_GROUP_COLOR # style="color:{news.USER_GROUP_COLOR}" # ENDIF #>{news.PSEUDO}</a>, 
					# ENDIF # 
					le {news.DATE}, 
					dans la catégorie <a href="{news.U_CATEGORY}">{news.CATEGORY_NAME}</a>
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
				<div class="spacer"></div>
    		</div>
		</div>
	# END news #
	</div>
	<div class="text_center"># INCLUDE PAGINATION #</div>
# ENDIF #