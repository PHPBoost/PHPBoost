<section id="module-news">
	<header class="section-header">
		<div class="align-right controls">
			<a href="${relative_url(SyndicationUrlBuilder::rss('news', ID_CATEGORY))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-fw fa-rss warning" aria-hidden="true"></i></a>
			# IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF ## ENDIF #
		</div>
		<h1>
			# IF C_PENDING_ITEMS #
				{@news.pending.items}
			# ELSE #
				# IF C_MEMBER_ITEMS #
			 		{@my.items}
				# ELSE #
					{@module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
				# ENDIF #
			# ENDIF #
		</h1>
	</header>
	# IF C_NO_ITEM #
		<div class="sub-section">
			<div class="content">
				<div class="message-helper bgc notice align-center">
					${LangLoader::get_message('no_item_now', 'common')}
				</div>
			</div>
		</div>
	# ELSE #
		<div class="sub-section">
			<div class="# IF C_GRID_VIEW #cell-flex cell-columns-{ITEMS_PER_ROW}# ELSE #cell-row# ENDIF #">
				# START items #
					<article
						id="news-item-{items.ID}"
						class="news-item several-items category-{items.CATEGORY_ID} cell# IF items.C_TOP_LIST # top-list# ENDIF ## IF items.C_NEW_CONTENT # new-content# ENDIF #"
						itemscope="itemscope"
						itemtype="https://schema.org/CreativeWork">

						<header class="cell-header">
							<h2 class="cell-name"><a href="{items.U_ITEM}"><span itemprop="name">{items.TITLE}</span></a></h2>
						</header>

						<div class="cell-body">
							<div class="cell-infos">
								<div class="more">
									# IF NOT C_MEMBER_ITEMS #
										# IF items.C_AUTHOR_DISPLAYED #
											<span class="pinned {items.AUTHOR_LEVEL_CLASS}"# IF items.C_AUTHOR_GROUP_COLOR # style="color:{items.AUTHOR_GROUP_COLOR};border-color:{items.AUTHOR_GROUP_COLOR};"# ENDIF #>
												# IF items.C_AUTHOR_CUSTOM_NAME #
													<i class="far fa-user" aria-hidden="true"></i> <span class="custom-author">{items.AUTHOR_CUSTOM_NAME}</span>
												# ELSE #
													# IF items.C_AUTHOR_EXIST #
														<a itemprop="author" class="{items.AUTHOR_LEVEL_CLASS}" href="{items.U_AUTHOR}"# IF items.C_AUTHOR_GROUP_COLOR # style="color:{items.AUTHOR_GROUP_COLOR}"# ENDIF #>
															<i class="far fa-user" aria-hidden="true"></i> {items.AUTHOR_DISPLAY_NAME}
														</a>
													# ELSE #
														<i class="far fa-user" aria-hidden="true"></i> <span class="visitor">{items.AUTHOR_DISPLAY_NAME}</span>
													# ENDIF #
												# ENDIF #
											</span>
										# ENDIF #
									# ENDIF #
									<span class="pinned">
										<i class="far fa-calendar-alt" aria-hidden="true"></i> <time datetime="# IF NOT items.C_DIFFERED #{items.DATE_ISO8601}# ELSE #{items.DIFFERED_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT items.C_DIFFERED #{items.DATE}# ELSE #{items.DIFFERED_START_DATE}# ENDIF #</time>
									</span>
									<span class="pinned">
										<a itemprop="about" href="{items.U_CATEGORY}"><i class="far fa-folder" aria-hidden="true"></i> {items.CATEGORY_NAME}</a>
									</span>
									# IF C_COMMENTS_ENABLED #
										<span class="pinned">
											<i class="far fa-comments" aria-hidden="true"></i> # IF items.C_COMMENTS #{items.COMMENTS_NUMBER} # ENDIF # {items.L_COMMENTS}
										</span>
									# ENDIF #
									# IF items.C_VIEWS_NUMBER_ENABLED #
										<span class="pinned" role="contentinfo" aria-label="{items.VIEWS_NUMBER} # IF items.C_SEVERAL_VIEWS #{@views}# ELSE #{@view}# ENDIF #"><i class="far fa-eye" aria-hidden="true"></i> {items.VIEWS_NUMBER}</span>
									# ENDIF #
								</div>
								# IF items.C_CONTROLS #
									<div class="controls align-right">
										# IF items.C_EDIT #
										<a href="{items.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-fw fa-edit" aria-hidden="true"></i></a>
										# ENDIF #
										# IF items.C_DELETE #
										<a href="{items.U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="fa fa-fw fa-trash-alt" aria-hidden="true"></i></a>
										# ENDIF #
									</div>
								# ENDIF #
							</div>
							# IF NOT C_FULL_ITEM_DISPLAY #
								# IF items.C_HAS_THUMBNAIL #
									<div class="cell-thumbnail cell-landscape cell-center">
										<img itemprop="thumbnailUrl" src="{items.U_THUMBNAIL}" alt="{items.TITLE}" />
										<a class="cell-thumbnail-caption" href="{items.U_ITEM}">
											# IF items.C_READ_MORE #[${LangLoader::get_message('read-more', 'common')}]# ELSE #<i class="fa fa-eye"></i># ENDIF #
										</a>
									</div>
								# ENDIF #
							# ENDIF #
							<div class="cell-content" itemprop="text">
								# IF C_FULL_ITEM_DISPLAY #
									# IF items.C_HAS_THUMBNAIL #
										<img class="item-thumbnail" itemprop="thumbnailUrl" src="{items.U_THUMBNAIL}" alt="{items.TITLE}" />
									# ENDIF #
									{items.CONTENT}
								# ELSE #
									{items.SUMMARY}# IF items.C_READ_MORE # <a href="{items.U_ITEM}">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #
								# ENDIF #
							</div>
						</div>

						<footer class="cell-footer">
							# IF items.C_HAS_UPDATE_DATE #<span class="pinned notice small text-italic modified-date">${LangLoader::get_message('status.last.update', 'common')} <time datetime="{items.UPDATE_DATE_ISO8601}" itemprop="dateModified">{items.UPDATE_DATE_FULL}</time></span># ENDIF #
							# IF items.C_SOURCES #
								<div class="sources-container">
									<span class="text-strong">${LangLoader::get_message('form.sources', 'common')}</span> :
									# START items.sources #
										<a itemprop="isBasedOnUrl" href="{items.sources.URL}" class="pinned question" rel="nofollow">{items.sources.NAME}</a># IF items.sources.C_SEPARATOR ## ENDIF #
									# END items.sources #
								</div>
							# ENDIF #
							<meta itemprop="url" content="{items.U_ITEM}">
							<meta itemprop="description" content="${escape(items.DESCRIPTION)}"/>
							# IF C_COMMENTS_ENABLED #
								<meta itemprop="discussionUrl" content="{items.U_COMMENTS}">
								<meta itemprop="interactionCount" content="{items.COMMENTS_NUMBER} UserComments">
							# ENDIF #
						</footer>
					</article>
				# END items #
			</div>
		</div>
	# ENDIF #
	<footer># IF C_PAGINATION #<div class="sub-section"># INCLUDE PAGINATION #</div># ENDIF #</footer>
</section>
