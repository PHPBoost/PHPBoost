<section>
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('news', ID_CAT))}" class="fa fa-syndication" title="${LangLoader::get_message('syndication', 'main')}"></a>
			# IF C_PENDING_NEWS #{@news.pending}# ELSE #{@news}# ENDIF #
		</h1>
	</header>
	<div class="content">
	# IF C_NEWS_NO_AVAILABLE #
		<div class="center">
			{@news.message.no_items}
		</div>
	# ELSE #
		# START news #
			<article # IF C_DISPLAY_BLOCK_TYPE # class="block" # ENDIF # # IF C_SEVERAL_COLUMNS # style="display:inline-block;width:calc(98% / {NUMBER_COLUMNS})" # ENDIF # itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
				<header>
					<h1>
						<a href="{news.U_LINK}"><span itemprop="name">{news.NAME}</span></a>
						<span class="actions">
							# IF news.C_EDIT #
								<a href="{news.U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="fa fa-edit"></a>
							# ENDIF #
							# IF news.C_DELETE #
								<a href="{news.U_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="fa fa-delete" data-confirmation="delete-element"></a>
							# ENDIF #
						</span>
					</h1>
					
					<div class="more">
						${LangLoader::get_message('by', 'common')}
						# IF news.C_AUTHOR_EXIST #
						<a itemprop="author" class="{news.USER_LEVEL_CLASS}" href="{news.U_AUTHOR_PROFILE}" style="font-size: 12px;" # IF news.C_USER_GROUP_COLOR # style="color:{news.USER_GROUP_COLOR}" # ENDIF #>{news.PSEUDO}</a>, 
						# ELSE #
						{news.PSEUDO}
						# ENDIF # 
						${TextHelper::lowercase_first(LangLoader::get_message('the', 'common'))} <time datetime="{news.DATE_ISO8601}" itemprop="datePublished">{news.DATE}</time> 
						${TextHelper::lowercase_first(LangLoader::get_message('in', 'common'))} <a itemprop="about" href="{news.U_CATEGORY}">{news.CATEGORY_NAME}</a>
						- # IF news.C_COMMENTS # {news.NUMBER_COMMENTS} # ENDIF #	{news.L_COMMENTS}
					</div>
					
					<meta itemprop="url" content="{news.U_LINK}">
					<meta itemprop="description" content="${escape(news.DESCRIPTION)}"/>
					<meta itemprop="discussionUrl" content="{news.U_COMMENTS}">
					<meta itemprop="interactionCount" content="{news.NUMBER_COMMENTS} UserComments">
					
				</header>
				
				<div class="content">
					# IF news.C_PICTURE #<img itemprop="thumbnailUrl" src="{news.U_PICTURE}" alt="{news.NAME}" title="{news.NAME}" class="right" /># ENDIF #
					<span itemprop="text"># IF C_DISPLAY_CONDENSED_CONTENT # {news.DESCRIPTION}... <a href="{news.U_LINK}">[${LangLoader::get_message('read-more', 'common')}]</a># ELSE # {news.CONTENTS} # ENDIF #</span>
				</div>
				
				<footer></footer>
			</article>
		# END news #
	# ENDIF #
	</div>
	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>