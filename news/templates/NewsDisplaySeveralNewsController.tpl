<section id="module-news">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('news', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			# IF C_PENDING_NEWS #{@news.pending}# ELSE #{@news}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF # # IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF ## ENDIF #
		</h1>
	</header>
	<div class="content">
	# IF C_NEWS_NO_AVAILABLE #
		<div class="center">
			${LangLoader::get_message('no_item_now', 'common')}
		</div>
	# ELSE #
		# START news #
			<article id="article-news-{news.ID}" class="article-news article-several# IF C_DISPLAY_BLOCK_TYPE # block# ENDIF ## IF C_SEVERAL_COLUMNS # inline-block# ENDIF #" # IF C_SEVERAL_COLUMNS # style="width:calc(98% / {NUMBER_COLUMNS})" # ENDIF # itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
				<header>
					<h2>
						<a href="{news.U_LINK}"><span itemprop="name">{news.NAME}</span></a>
						<span class="actions">
							# IF news.C_EDIT #
								<a href="{news.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
							# ENDIF #
							# IF news.C_DELETE #
								<a href="{news.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
							# ENDIF #
						</span>
					</h2>

					<div class="more">
						# IF news.C_AUTHOR_DISPLAYED #
							${LangLoader::get_message('by', 'common')}
							# IF news.C_AUTHOR_EXIST #<a itemprop="author" class="{news.USER_LEVEL_CLASS}" href="{news.U_AUTHOR_PROFILE}"# IF news.C_USER_GROUP_COLOR # style="color:{news.USER_GROUP_COLOR}"# ENDIF #>{news.PSEUDO}</a>, # ELSE #{news.PSEUDO}# ENDIF #
						# ENDIF #
						${TextHelper::lowercase_first(LangLoader::get_message('the', 'common'))} <time datetime="{news.DATE_ISO8601}" itemprop="datePublished">{news.DATE}</time>
						${TextHelper::lowercase_first(LangLoader::get_message('in', 'common'))} <a itemprop="about" href="{news.U_CATEGORY}">{news.CATEGORY_NAME}</a>
						# IF C_COMMENTS_ENABLED #- # IF news.C_COMMENTS # {news.NUMBER_COMMENTS} # ENDIF #	{news.L_COMMENTS}# ENDIF #
					</div>

					<meta itemprop="url" content="{news.U_LINK}">
					<meta itemprop="description" content="${escape(news.DESCRIPTION)}"/>
					# IF C_COMMENTS_ENABLED #
					<meta itemprop="discussionUrl" content="{news.U_COMMENTS}">
					<meta itemprop="interactionCount" content="{news.NUMBER_COMMENTS} UserComments">
					# ENDIF #

				</header>

				<div class="content">
					# IF news.C_PICTURE #<img itemprop="thumbnailUrl" src="{news.U_PICTURE}" alt="{news.NAME}" title="{news.NAME}" class="right" /># ENDIF #
					<div itemprop="text"># IF C_DISPLAY_CONDENSED_CONTENT # {news.DESCRIPTION}# IF news.C_READ_MORE #... <a href="{news.U_LINK}">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF ## ELSE # {news.CONTENTS} # ENDIF #</div>
				</div>
				
				# IF news.C_SOURCES #
				<div class="spacer"></div>
				<aside>
				<div id="news-sources-container">
					<span>${LangLoader::get_message('form.sources', 'common')}</span> :
					# START news.sources #
					<a itemprop="isBasedOnUrl" href="{news.sources.URL}" class="small">{news.sources.NAME}</a># IF news.sources.C_SEPARATOR #, # ENDIF #
					# END news.sources #
				</div>
				</aside>
				# ENDIF #

				<footer></footer>
			</article>
		# END news #
	# ENDIF #
	</div>
	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>
