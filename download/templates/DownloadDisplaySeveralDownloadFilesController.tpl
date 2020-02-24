<section id="module-download">
	<header>
		<div class="align-right controls">
			<a href="${relative_url(SyndicationUrlBuilder::rss('download', ID_CAT))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			# IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF ## ENDIF #
		</div>
		<h1>
			# IF C_PENDING #{@download.pending}# ELSE #{@module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF #
		</h1>
	</header>
	# IF C_DISPLAY_SUMMARY_TO_GUEST #
		<div class="cat-description">
			plop
		</div>
	# ENDIF #

	# IF C_CATEGORY_DESCRIPTION #
		<div class="cat-description">
			{CATEGORY_DESCRIPTION}
		</div>
	# ENDIF #

	# IF C_SUB_CATEGORIES #
		<div class="cell-flex cell-tile cell-columns-{CATEGORIES_PER_ROW}">
			# START sub_categories_list #
				<div class="cell {sub_categories_list.CATEGORY_ID}">
					<div class="cell-header">
						<div class="cell-name"><a class="subcat-title" itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a></div>
						<span class="small pinned notice" role="contentinfo" aria-label="{sub_categories_list.DOWNLOADFILES_NUMBER} # IF sub_categories_list.C_SEVERAL_ITEMS #${TextHelper::lcfirst(@items)}# ELSE #${TextHelper::lcfirst(@item)}# ENDIF #">
							{sub_categories_list.DOWNLOADFILES_NUMBER}
						</span>
					</div>
					# IF sub_categories_list.C_CATEGORY_THUMBNAIL #
						<div class="cell-body" itemprop="about">
							<div class="cell-thumbnail">
								<img itemprop="thumbnailUrl" src="{sub_categories_list.U_CATEGORY_THUMBNAIL}" alt="{sub_categories_list.CATEGORY_NAME}" />
								<a class="cell-thumbnail-caption" href="{sub_categories_list.U_CATEGORY}">
									${LangLoader::get_message('see.category', 'categories-common')}
								</a>
							</div>
						</div>
					# ENDIF #
				</div>
			# END sub_categories_list #
		</div>
		# IF C_SUBCATEGORIES_PAGINATION #<div class="align-center"># INCLUDE SUBCATEGORIES_PAGINATION #</div># ENDIF #
	# ENDIF #

	# IF C_SEVERAL_ITEMS #
		# INCLUDE SORT_FORM #
		<div class="spacer"></div>
	# ENDIF #

	# IF C_FILES #
		# IF C_TABLE_VIEW #
			<table class="table">
				<thead>
					<tr>
						<th>${LangLoader::get_message('form.name', 'common')}</th>
						<th class="col-small" aria-label="${LangLoader::get_message('form.date.creation', 'common')}">
							<i class="far fa-fw fa-calendar-plus hidden-small-screens" aria-hidden="true"></i>
							<span class="hidden-large-screens">${LangLoader::get_message('form.date.creation', 'common')}</span>
						</th>
						<th class="col-small" aria-label="${LangLoader::get_message('form.date.update', 'common')}">
							<i class="far fa-fw fa-calendar-check hidden-small-screens" aria-hidden="true"></i>
							<span class="hidden-large-screens">${LangLoader::get_message('form.date.update', 'common')}</span>
						</th>
						<th class="col-small" aria-label="{@downloads.number}">
							<i class="fa fa-fw fa-download hidden-small-screens" aria-hidden="true"></i>
							<span class="hidden-large-screens">{@downloads.number}</span>
						</th>
						# IF C_ENABLED_VIEWS_NUMBER #
							<th class="col-small" aria-label="{@download.views.number}">
								<i class="fa fa-fw fa-eye hidden-small-screens" aria-hidden="true"></i>
								<span class="hidden-large-screens">{@download.views.number}</span>
							</th>
						# ENDIF #
						# IF C_ENABLED_NOTATION #
							<th aria-label="${LangLoader::get_message('note', 'common')}">
								<i class="far fa-fw fa-star hidden-small-screens" aria-hidden="true"></i>
								<span class="hidden-large-screens">${LangLoader::get_message('note', 'common')}</span>
							</th>
						# ENDIF #
						# IF C_ENABLED_COMMENTS #
							<th aria-label="${LangLoader::get_message('comments', 'comments-common')}">
								<i class="far fa-fw fa-comments hidden-small-screens" aria-hidden="true"></i>
								<span class="hidden-large-screens">${LangLoader::get_message('comments', 'comments-common')}</span>
							</th>
						# ENDIF #
						# IF C_MODERATION #
							<th class="col-smaller" aria-label="${LangLoader::get_message('moderation', 'common')}">
								<i class="fa fa-fw fa-gavel hidden-small-screens" aria-hidden="true"></i>
								<span class="hidden-large-screens">${LangLoader::get_message('moderation', 'common')}</span>
							</th>
						# ENDIF #
					</tr>
				</thead>
				<tbody>
					# START downloadfiles #
						<tr class="category-{downloadfiles.CATEGORY_ID}">
							<td>
								<a href="{downloadfiles.U_ITEM}" itemprop="name"# IF downloadfiles.C_NEW_CONTENT # class="new-content"# ENDIF #>{downloadfiles.TITLE}</a>
							</td>
							<td>
								<time datetime="# IF NOT downloadfiles.C_DIFFERED #{downloadfiles.DATE_ISO8601}# ELSE #{downloadfiles.DIFFERED_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT downloadfiles.C_DIFFERED #{downloadfiles.DATE}# ELSE #{downloadfiles.DIFFERED_START_DATE}# ENDIF #</time>
							</td>
							<td>
								<time datetime="{downloadfiles.UPDATED_DATE_ISO8601}" itemprop="datePublished">{downloadfiles.UPDATED_DATE}</time>
							</td>
							<td>
								{downloadfiles.DOWNLOADS_NUMBER}
							</td>
							# IF C_ENABLED_VIEWS_NUMBER #
								<td>
									{downloadfiles.VIEWS_NUMBER}
								</td>
							# ENDIF #
							# IF C_ENABLED_NOTATION #
								<td>
									{downloadfiles.STATIC_NOTATION}
								</td>
							# ENDIF #
							# IF C_ENABLED_COMMENTS #
								<td>
									# IF downloadfiles.C_COMMENTS # {downloadfiles.COMMENTS_NUMBER} # ENDIF # {downloadfiles.L_COMMENTS}
								</td>
							# ENDIF #
							# IF C_MODERATION #
								<td>
									# IF downloadfiles.C_EDIT #
										<a href="{downloadfiles.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
									# ENDIF #
									# IF downloadfiles.C_DELETE #
										<a href="{downloadfiles.U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
									# ENDIF #
								</td>
							# ENDIF #
						</tr>
					# END downloadfiles #
				</tbody>
			</table>
		# ELSE #
			<div class="# IF C_GRID_VIEW #cell-flex cell-columns-{ITEMS_PER_ROW}# ELSE #cell-row# ENDIF #">
				# START downloadfiles #
					<article id="download-item-{downloadfiles.ID}" class="download-item several-items category-{downloadfiles.CATEGORY_ID} cell# IF downloadfiles.C_NEW_CONTENT # new-content# ENDIF #" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
						<header class="cell-header">
							<h2 class="cell-name"><a href="{downloadfiles.U_ITEM}" itemprop="name">{downloadfiles.TITLE}</a></h2>
						</header>
						<div class="cell-body">
							<div class="cell-infos">
								<div class="more">
									<span class="pinned" role="contentinfo" aria-label="{downloadfiles.L_DOWNLOADED_TIMES}"><i class="fa fa-download" aria-hidden="true"></i> {downloadfiles.DOWNLOADS_NUMBER}</span>
									# IF C_ENABLED_VIEWS_NUMBER #<span class="pinned" role="contentinfo" aria-label="{downloadfiles.VIEWS_NUMBER} {@download.view}"><i class="fa fa-eye" aria-hidden="true"></i> {downloadfiles.VIEWS_NUMBER}</span># ENDIF #
									# IF C_ENABLED_COMMENTS #
										<span class="pinned">
											<i class="fa fa-comments" aria-hidden="true"></i>
											# IF downloadfiles.C_COMMENTS # {downloadfiles.COMMENTS_NUMBER} # ENDIF # {downloadfiles.L_COMMENTS}
										</span>
									# ENDIF #
									<div class="spacer"></div>
									# IF C_ENABLED_NOTATION #
										<div class="pinned">{downloadfiles.STATIC_NOTATION}</div>
									# ENDIF #
								</div>
								<div class="controls align-right">
									# IF downloadfiles.C_EDIT #<a href="{downloadfiles.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF #
									# IF downloadfiles.C_DELETE #<a href="{downloadfiles.U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a># ENDIF #
								</div>
							</div>
							# IF NOT C_FULL_ITEM_DISPLAY #
								# IF downloadfiles.C_HAS_THUMBNAIL #
									<div class="cell-thumbnail">
										<img src="{downloadfiles.U_THUMBNAIL}" alt="{downloadfiles.TITLE}" itemprop="image" />
										<a href="{downloadfiles.U_ITEM}" class="cell-thumbnail-caption">
											${LangLoader::get_message('see.details', 'common')}
										</a>
									</div>
								# ENDIF #
							# ENDIF #
							<div class="cell-content">
								# IF downloadfiles.C_VISIBLE #
									<div class="cell-infos">
										<span></span>
										<span>
											<a href="{downloadfiles.U_DOWNLOAD}" class="button submit small">
												<i class="fa fa-download" aria-hidden="true"></i> {@download}
											</a>
											# IF IS_USER_CONNECTED #
												<a href="{downloadfiles.U_DEADLINK}" data-confirmation="${LangLoader::get_message('deadlink.confirmation', 'common')}" class="button bgc-full warning small" aria-label="${LangLoader::get_message('deadlink', 'common')}">
													<i class="fa fa-unlink" aria-hidden="true"></i>
												</a>
											# ENDIF #
										</span>
									</div>
								# ENDIF #
								<div itemprop="text">
									# IF C_FULL_ITEM_DISPLAY #
										# IF downloadfiles.C_HAS_THUMBNAIL #
											<a href="{downloadfiles.U_ITEM}" class="item-thumbnail">
												<img src="{downloadfiles.U_THUMBNAIL}" alt="{downloadfiles.TITLE}" itemprop="image" />
											</a>
										# ENDIF #
										{downloadfiles.CONTENTS}
									# ELSE #
										{downloadfiles.SUMMARY}# IF downloadfiles.C_READ_MORE #... <a href="{downloadfiles.U_ITEM}" class="read-more">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #
									# ENDIF #
								</div>
							</div>
						</div>

						<footer>
							<meta itemprop="url" content="{downloadfiles.U_ITEM}">
							<meta itemprop="description" content="${escape(downloadfiles.SUMMARY)}"/>
							# IF C_ENABLED_COMMENTS #
								<meta itemprop="discussionUrl" content="{downloadfiles.U_COMMENTS}">
								<meta itemprop="interactionCount" content="{downloadfiles.COMMENTS_NUMBER} UserComments">
							# ENDIF #
						</footer>
					</article>
				# END downloadfiles #
			</div>
		# ENDIF #
	# ELSE #
		# IF NOT C_HIDE_NO_ITEM_MESSAGE #
		<div class="align-center">
			${LangLoader::get_message('no_item_now', 'common')}
		</div>
		# ENDIF #
	# ENDIF #
	<footer>
		# IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #
	</footer>
</section>
