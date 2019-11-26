<section id="module-news">
	<header>
		<div class="cat-actions">
			<a href="${relative_url(SyndicationUrlBuilder::rss('news', ID_CAT))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss" aria-hidden="true"></i></a>
			# IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit small" aria-hidden="true"></i></a># ENDIF ## ENDIF #
		</div>
		<h1>
			# IF C_PENDING_NEWS #{@news.pending}# ELSE #{@news}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF #
		</h1>
	</header>
	<div class="# IF C_DISPLAY_GRID_VIEW #cell-flex cell-columns-{NUMBER_COLUMNS}# ELSE #cell-row# ENDIF #">
		# IF C_NEWS_NO_AVAILABLE #
			<div class="center">
				${LangLoader::get_message('no_item_now', 'common')}
			</div>
		# ELSE #
			# START news #
				<meta itemprop="url" content="{news.U_LINK}">
				<meta itemprop="description" content="${escape(news.DESCRIPTION)}"/>
				# IF C_COMMENTS_ENABLED #
					<meta itemprop="discussionUrl" content="{news.U_COMMENTS}">
					<meta itemprop="interactionCount" content="{news.NUMBER_COMMENTS} UserComments">
				# ENDIF #

				<article
					id="news-item-{news.ID}"
					class="news-item several-items cell# IF news.C_TOP_LIST # top-list# ENDIF ## IF news.C_NEW_CONTENT # new-content# ENDIF #"
					itemscope="itemscope"
					itemtype="http://schema.org/CreativeWork">

					<header class="cell-header">
						<h2 class="cell-name"><a href="{news.U_LINK}"><span itemprop="name">{news.NAME}</span></a></h2>
					</header>

					<div class="cell-body">
						<div class="cell-infos">
							<div class="more">
								# IF news.C_AUTHOR_DISPLAYED #
									<i class="fa fa-user" aria-hidden="true"></i>
									# IF news.C_AUTHOR_CUSTOM_NAME #
										{news.AUTHOR_CUSTOM_NAME}
									# ELSE #
										# IF news.C_AUTHOR_EXIST #<a itemprop="author" class="{news.USER_LEVEL_CLASS}" href="{news.U_AUTHOR_PROFILE}"# IF news.C_USER_GROUP_COLOR # style="color:{news.USER_GROUP_COLOR}"# ENDIF #>{news.PSEUDO}</a> | # ELSE #{news.PSEUDO} | # ENDIF #
									# ENDIF #
								# ENDIF #
								<i class="fa fa-calendar-alt" aria-hidden="true"></i> <time datetime="# IF NOT news.C_DIFFERED #{news.DATE_ISO8601}# ELSE #{news.DIFFERED_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT news.C_DIFFERED #{news.DATE} | # ELSE #{news.DIFFERED_START_DATE} | # ENDIF #</time>
								<i class="fa fa-folder" aria-hidden="true"></i> <a itemprop="about" href="{news.U_CATEGORY}">{news.CATEGORY_NAME}</a>
								# IF C_COMMENTS_ENABLED #| <i class="fa fa-comments" aria-hidden="true"></i> # IF news.C_COMMENTS #{news.NUMBER_COMMENTS} # ENDIF # {news.L_COMMENTS}# ENDIF #
								# IF news.C_NB_VIEW_ENABLED #| <span aria-label="{news.NUMBER_VIEW} {@news.view}"><i class="fa fa-eye" aria-hidden="true"></i> {news.NUMBER_VIEW}</span> # ENDIF #
							</div>
							# IF news.C_ACTIONS #
								<div class="cell-actions">
									# IF news.C_EDIT #
									<a href="{news.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a>
									# ENDIF #
									# IF news.C_DELETE #
									<a href="{news.U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
									# ENDIF #
								</div>
							# ENDIF #
						</div>
						# IF news.C_PICTURE #
							<div class="cell-thumbnail">
								<img itemprop="thumbnailUrl" src="{news.U_PICTURE}" alt="{news.NAME}" />
								# IF news.C_READ_MORE #
									<a class="cell-thumbnail-caption" href="{news.U_LINK}">[${LangLoader::get_message('read-more', 'common')}]</a>
								# ELSE #
									<a class="cell-thumbnail-caption" href="{news.U_LINK}"><i class="fa fa-eye"></i></a>
								# ENDIF #
							</div>
						# ENDIF #
						<div class="cell-content" itemprop="text">
							# IF C_DISPLAY_CONDENSED_CONTENT #
								{news.DESCRIPTION}# IF news.C_READ_MORE #... <a href="{news.U_LINK}">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #
							# ELSE #
								{news.CONTENTS}
							# ENDIF #
						</div>
					</div>

					<footer class="cell-footer">
						# IF news.C_SOURCES #
							<div id="news-sources-container">
								<span>${LangLoader::get_message('form.sources', 'common')}</span> :
								# START news.sources #
								<a itemprop="isBasedOnUrl" href="{news.sources.URL}" class="small" rel="nofollow">{news.sources.NAME}</a># IF news.sources.C_SEPARATOR #, # ENDIF #
								# END news.sources #
							</div>
						# ENDIF #
					</footer>
				</article>
			# END news #
		# ENDIF #
	</div>
	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>
