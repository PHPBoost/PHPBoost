<section id="module-news">
	<header>
		<div class="align-right controls">
			<a href="${relative_url(SyndicationUrlBuilder::rss('news', ID_CATEGORY))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-fw fa-rss warning" aria-hidden="true"></i></a>
			# IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF ## ENDIF #
		</div>
		<h1>
			# IF C_PENDING_NEWS #{@news.pending}# ELSE #{@news}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF #
		</h1>
	</header>
	<div class="# IF C_GRID_VIEW #cell-flex cell-columns-{ITEMS_PER_ROW}# ELSE #cell-row# ENDIF #">
		# IF C_NO_ITEM #
			<div class="message-helper bgc notice cell-4-4">
				${LangLoader::get_message('no_item_now', 'common')}
			</div>
		# ELSE #
			# START news #
				<article
					id="news-item-{news.ID}"
					class="news-items several-items category-{news.CATEGORY_ID} cell# IF news.C_TOP_LIST # top-list# ENDIF ## IF news.C_NEW_CONTENT # new-content# ENDIF #"
					itemscope="itemscope"
					itemtype="http://schema.org/CreativeWork">

					<header class="cell-header">
						<h2 class="cell-name"><a href="{news.U_ITEM}"><span itemprop="name">{news.TITLE}</span></a></h2>
					</header>

					<div class="cell-body">
						<div class="cell-infos">
							<div class="more">
								# IF news.C_AUTHOR_DISPLAYED #
									<span class="pinned {news.USER_LEVEL_CLASS}"# IF news.C_USER_GROUP_COLOR # style="color:{news.USER_GROUP_COLOR};border-color:{news.USER_GROUP_COLOR};"# ENDIF #>
										# IF news.C_AUTHOR_CUSTOM_NAME #
											<i class="far fa-user" aria-hidden="true"></i> <span class="custom-author">{news.AUTHOR_CUSTOM_NAME}</span>
										# ELSE #
											# IF news.C_AUTHOR_EXIST #
												<a itemprop="author" class="{news.USER_LEVEL_CLASS}" href="{news.U_AUTHOR_PROFILE}"# IF news.C_USER_GROUP_COLOR # style="color:{news.USER_GROUP_COLOR}"# ENDIF #>
													<i class="far fa-user" aria-hidden="true"></i> {news.PSEUDO}
												</a>
											# ELSE #
												<i class="far fa-user" aria-hidden="true"></i> <span class="visitor">{news.PSEUDO}</span>
											# ENDIF #
										# ENDIF #
									</span>
								# ENDIF #
								<span class="pinned">
									<i class="far fa-calendar-alt" aria-hidden="true"></i> <time datetime="# IF NOT news.C_DIFFERED #{news.DATE_ISO8601}# ELSE #{news.DIFFERED_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT news.C_DIFFERED #{news.DATE}# ELSE #{news.DIFFERED_START_DATE}# ENDIF #</time>
								</span>
								<span class="pinned">
									<a itemprop="about" href="{news.U_CATEGORY}"><i class="far fa-folder" aria-hidden="true"></i> {news.CATEGORY_NAME}</a>
								</span>
								# IF C_COMMENTS_ENABLED #
									<span class="pinned">
										<i class="far fa-comments" aria-hidden="true"></i> # IF news.C_COMMENTS #{news.COMMENTS_NUMBER} # ENDIF # {news.L_COMMENTS}
									</span>
								# ENDIF #
								# IF news.C_VIEWS_NUMBER #
									<span class="pinned" role="contentinfo" aria-label="{news.VIEWS_NUMBER} {@news.view}"><i class="far fa-eye" aria-hidden="true"></i> {news.VIEWS_NUMBER}</span>
								# ENDIF #
							</div>
							# IF news.C_CONTROLS #
								<div class="controls align-right">
									# IF news.C_EDIT #
									<a href="{news.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-fw fa-edit" aria-hidden="true"></i></a>
									# ENDIF #
									# IF news.C_DELETE #
									<a href="{news.U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="fa fa-fw fa-trash-alt" aria-hidden="true"></i></a>
									# ENDIF #
								</div>
							# ENDIF #
						</div>
						# IF NOT C_FULL_ITEM_DISPLAY #
							# IF news.C_HAS_THUMBNAIL #
								<div class="cell-thumbnail cell-landscape">
									<img itemprop="thumbnailUrl" src="{news.U_THUMBNAIL}" alt="{news.TITLE}" />
									<a class="cell-thumbnail-caption" href="{news.U_ITEM}">
										# IF news.C_READ_MORE #[${LangLoader::get_message('read-more', 'common')}]# ELSE #<i class="fa fa-eye"></i># ENDIF #
									</a>
								</div>
							# ENDIF #
						# ENDIF #
						<div class="cell-content" itemprop="text">
							# IF C_FULL_ITEM_DISPLAY #
								# IF news.C_HAS_THUMBNAIL #
									<img class="item-thumbnail" itemprop="thumbnailUrl" src="{news.U_THUMBNAIL}" alt="{news.TITLE}" />
								# ENDIF #
								{news.CONTENTS}
							# ELSE #
								{news.SUMMARY}# IF news.C_READ_MORE #... <a href="{news.U_ITEM}">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #
							# ENDIF #
						</div>
					</div>

					<footer class="cell-footer">
						# IF news.C_SOURCES #
							<div class="sources-container">
								<span class="text-strong">${LangLoader::get_message('form.sources', 'common')}</span> :
								# START news.sources #
									<a itemprop="isBasedOnUrl" href="{news.sources.URL}" class="pinned question" rel="nofollow">{news.sources.NAME}</a># IF news.sources.C_SEPARATOR ## ENDIF #
								# END news.sources #
							</div>
						# ENDIF #
						<meta itemprop="url" content="{news.U_ITEM}">
						<meta itemprop="description" content="${escape(news.DESCRIPTION)}"/>
						# IF C_COMMENTS_ENABLED #
							<meta itemprop="discussionUrl" content="{news.U_COMMENTS}">
							<meta itemprop="interactionCount" content="{news.COMMENTS_NUMBER} UserComments">
						# ENDIF #
					</footer>
				</article>
			# END news #
		# ENDIF #
	</div>
	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>
