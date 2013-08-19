<script type="text/javascript">
<!--
function Confirm()
{
	return confirm(${i18njs('news.message.delete')});
}
-->
</script>

<div class="module_position" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
	<div class="module_top_l"></div>
	<div class="module_top_r"></div>
	<div class="module_top module_top_news">
	
		<ul class="module_top_options">
			<li>
				<a>
					<span class="options"></span><span class="caret"></span>
				</a>
				<ul>
					# IF C_EDIT #
					<li>
						<a href="{U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="img_link">${LangLoader::get_message('edit', 'main')}</a>
					</li>
					# ENDIF #
					# IF C_DELETE #
					<li>
						<a href="{U_DELETE}" title="${LangLoader::get_message('delete', 'main')}" onclick="javascript:return Confirm();">${LangLoader::get_message('delete', 'main')}</a>
					</li>
					# ENDIF #
				</ul>
			</li>
		</ul>

		<div class="module_top_title">
			<a href="{U_SYNDICATION}" title="${LangLoader::get_message('syndication', 'main')}" class="img_link">
				<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="${LangLoader::get_message('syndication', 'main')}"/>
			</a>
			<span id="name" itemprop="name">{NAME}</span>
		</div>
		
		<meta itemprop="url" content="{U_LINK}">
		<meta itemprop="description" content="{DESCRIPTION}">
		<meta itemprop="discussionUrl" content="{U_COMMENTS}">
		<meta itemprop="interactionCount" content="{NUMBER_COMMENTS} UserComments">
		
		<div class="news_author_container">
			Par # IF PSEUDO #<a itemprop="author" class="small_link {USER_LEVEL_CLASS}" href="{U_AUTHOR_PROFILE}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}" # ENDIF #>{PSEUDO}</a>, # ENDIF # 
			le <time datetime="{DATE_RFC3339_F}" itemprop="datePublished">{DATE}</time>,
			dans la catégorie <a itemprop="about" href="{U_CATEGORY}">{CATEGORY_NAME}</a>
		</div>
		
		<div class="spacer"></div>
	</div>
	<div class="module_contents">
		# IF C_PICTURE #<img itemprop="thumbnailUrl" src="{U_PICTURE}" alt="{NAME}" title="{NAME}" class="img_right" /># ENDIF #
		
		<span itemprop="text">{CONTENTS}</span>
		
		<div class="spacer" style="margin-bottom:40px;"></div>
	
		# IF C_SOURCES #
		<div id="news_sources_container">
			<span class="news_more_title">{@news.form.sources}</span> :
			# START sources #
			<a itemprop="isBasedOnUrl" href="{sources.URL}" class="small_link">{sources.NAME}</a># IF sources.C_SEPARATOR #, # ENDIF #
			# END sources #
		</div>
		# ENDIF #

		# IF C_KEYWORDS #
		<div id="news_tags_container">
			<span class="news_more_title">{@news.form.keywords}</span> :
				# START keywords #
					<a itemprop="keywords" href="{keywords.URL}">{keywords.NAME}</a># IF keywords.C_SEPARATOR #, # ENDIF #
				# END keywords #
		</div>
		# ENDIF #
							
		<div class="spacer"></div>
	</div>
	<div class="module_bottom_l"></div>
	<div class="module_bottom_r"></div>
	<div class="module_bottom">
		<div class="spacer"></div>
	</div>
		
		<!-- # IF C_NEWS_SUGGESTED # -->
		<div id="news_suggested_container">
			<div class="news_more_title">{L_NEWS_SUGGESTED}</div>
			<ul class="bb_ul" style="margin: 10px 30px 0;">
				# START suggested #
				<li class="bb_li"><a href="{suggested.URL}">{suggested.TITLE}</a></li>
				# END suggested #
			</ul>
		</div>
		<!-- # ENDIF # -->
	
	<hr style="width:70%;margin:0px auto 40px auto;">
	
	# IF C_NEWS_NAVIGATION_LINKS #
	<div class="navigation_link">
		# IF C_PREVIOUS_NEWS #
		<span style="float:left">
			<a href="{U_PREVIOUS_NEWS}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/left.png" alt="" class="valign_middle" /></a>
			<a href="{U_PREVIOUS_NEWS}">{PREVIOUS_NEWS}</a>
		</span>
		# ENDIF #
		# IF C_NEXT_NEWS #
		<span style="float:right">
			<a href="{U_NEXT_NEWS}">{NEXT_NEWS}</a>
			<a href="{U_NEXT_NEWS}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/right.png" alt="" class="valign_middle" /></a>
		</span>
		# ENDIF #
		<div class="spacer"></div>
	</div>
	# ENDIF #

	# INCLUDE COMMENTS #	
</div>