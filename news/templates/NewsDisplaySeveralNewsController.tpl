<section id="module-news">
	<header>
		<div class="cat-actions">
			<a href="${relative_url(SyndicationUrlBuilder::rss('news', ID_CAT))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication" aria-hidden="true"></i></a>
			# IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit small" aria-hidden="true"></i></a># ENDIF ## ENDIF #
		</div>
		<h1>
			# IF C_PENDING_NEWS #{@news.pending}# ELSE #{@news}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF #
		</h1>
	</header>
	<div class="elements-container# IF C_SEVERAL_COLUMNS # columns-{NUMBER_COLUMNS}# ENDIF #">
	# IF C_NEWS_NO_AVAILABLE #
		<div class="center">
			${LangLoader::get_message('no_item_now', 'common')}
		</div>
	# ELSE #
		# START news #
			<article id="article-news-{news.ID}" class="article-news article-several# IF news.C_TOP_LIST # top-list# ENDIF ## IF C_DISPLAY_BLOCK_TYPE # block# ENDIF ## IF news.C_NEW_CONTENT # new-content# ENDIF #" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
				<header>
					<h2><a href="{news.U_LINK}"><span itemprop="name">{news.NAME}</span></a></h2>


				</header>
				<div class="actions">
					# IF news.C_EDIT #
					<a href="{news.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a>
					# ENDIF #
					# IF news.C_DELETE #
					<a href="{news.U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="fa fa-delete" aria-hidden="true"></i></a>
					# ENDIF #
				</div>
				<div class="more">
					# IF news.C_AUTHOR_DISPLAYED #
						<i class="fa fa-user-o" aria-hidden="true"></i>
						# IF news.C_AUTHOR_CUSTOM_NAME #
							{news.AUTHOR_CUSTOM_NAME}
						# ELSE #
							# IF news.C_AUTHOR_EXIST #<a itemprop="author" class="{news.USER_LEVEL_CLASS}" href="{news.U_AUTHOR_PROFILE}"# IF news.C_USER_GROUP_COLOR # style="color:{news.USER_GROUP_COLOR}"# ENDIF #>{news.PSEUDO}</a> | # ELSE #{news.PSEUDO} | # ENDIF #
						# ENDIF #
					# ENDIF #
					<i class="fa fa-calendar" aria-hidden="true"></i> <time datetime="# IF NOT news.C_DIFFERED #{news.DATE_ISO8601}# ELSE #{news.DIFFERED_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT news.C_DIFFERED #{news.DATE} | # ELSE #{news.DIFFERED_START_DATE} | # ENDIF #</time>
					<i class="fa fa-folder-o" aria-hidden="true"></i> <a itemprop="about" href="{news.U_CATEGORY}">{news.CATEGORY_NAME}</a>
					# IF C_COMMENTS_ENABLED #| <i class="fa fa-comments-o" aria-hidden="true"></i> # IF news.C_COMMENTS #{news.NUMBER_COMMENTS} # ENDIF # {news.L_COMMENTS}# ENDIF #
					# IF news.C_NB_VIEW_ENABLED #| <span aria-label="{news.NUMBER_VIEW} {@news.view}"><i class="fa fa-eye" aria-hidden="true"></i> {news.NUMBER_VIEW}</span> # ENDIF #
				</div>

				<meta itemprop="url" content="{news.U_LINK}">
				<meta itemprop="description" content="${escape(news.DESCRIPTION)}"/>
				# IF C_COMMENTS_ENABLED #
				<meta itemprop="discussionUrl" content="{news.U_COMMENTS}">
				<meta itemprop="interactionCount" content="{news.NUMBER_COMMENTS} UserComments">
				# ENDIF #

				<div class="content">
					# IF news.C_PICTURE #<a href="{news.U_LINK}" class="thumbnail-item"><img itemprop="thumbnailUrl" src="{news.U_PICTURE}" alt="{news.NAME}" /> </a># ENDIF #
					<div itemprop="text"># IF C_DISPLAY_CONDENSED_CONTENT # {news.DESCRIPTION}# IF news.C_READ_MORE #... <a href="{news.U_LINK}">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF ## ELSE # {news.CONTENTS} # ENDIF #</div>
				</div>

				# IF news.C_SOURCES #
				<div class="spacer"></div>
				<aside>
				<div id="news-sources-container">
					<span>${LangLoader::get_message('form.sources', 'common')}</span> :
					# START news.sources #
					<a itemprop="isBasedOnUrl" href="{news.sources.URL}" class="small" rel="nofollow">{news.sources.NAME}</a># IF news.sources.C_SEPARATOR #, # ENDIF #
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
