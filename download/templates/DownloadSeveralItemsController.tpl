<section id="module-download">
	<header class="section-header">
		<div class="controls align-right">
			<a href="${relative_url(SyndicationUrlBuilder::rss('download', ID_CAT))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			# IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF ## ENDIF #
		</div>
		<h1>
			# IF C_PENDING #
				{@download.pending.items}
			# ELSE #
				# IF C_MEMBER_ITEMS #
					# IF C_MY_ITEMS #{@my.items}# ELSE #{@member.items} {MEMBER_NAME}# ENDIF #
				# ELSE #
					{@module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
				# ENDIF #
			# ENDIF #
		</h1>
	</header>

	# IF C_CATEGORY_DESCRIPTION #
		<div class="sub-section">
			<div class="cat-description">
				{CATEGORY_DESCRIPTION}
			</div>
		</div>
	# ENDIF #

	# IF C_SUB_CATEGORIES #
		<div class="sub-section">
			<div class="content-container">
				<div class="cell-flex cell-tile cell-columns-{CATEGORIES_PER_ROW}">
					# START sub_categories_list #
						<div class="cell {sub_categories_list.CATEGORY_ID}">
							<div class="cell-header">
								<div class="cell-name"><a class="subcat-title" itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a></div>
								<span class="small pinned notice" role="contentinfo" aria-label="{sub_categories_list.ITEMS_NUMBER} # IF sub_categories_list.C_SEVERAL_ITEMS #${TextHelper::lcfirst(@items)}# ELSE #${TextHelper::lcfirst(@item)}# ENDIF #">
									{sub_categories_list.ITEMS_NUMBER}
								</span>
							</div>
							# IF sub_categories_list.C_CATEGORY_THUMBNAIL #
								<div class="cell-body" itemprop="about">
									<div class="cell-thumbnail cell-landscape cell-center">
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
				# IF C_SUBCATEGORIES_PAGINATION #<div class="content align-center"># INCLUDE SUBCATEGORIES_PAGINATION #</div># ENDIF #
			</div>
		</div>
	# ENDIF #

	<div class="sub-section">
		<div class="content-container">
			# IF C_ITEMS #
				# IF C_SEVERAL_ITEMS #
					# IF NOT C_MEMBER_ITEMS #
						<div class="content">
							# INCLUDE SORT_FORM #
							<div class="spacer"></div>
						</div>
					# ENDIF #
				# ENDIF #
				# IF C_TABLE_VIEW #
					<div class="responsive-table">
						<table class="table">
							<thead>
								<tr>
									<th>${LangLoader::get_message('form.name', 'common')}</th>
									<th class="col-small" aria-label="${LangLoader::get_message('form.date.creation', 'common')}">
										<i class="far fa-fw fa-calendar-plus hidden-small-screens" aria-hidden="true"></i>
										<span class="hidden-large-screens">${LangLoader::get_message('form.date.creation', 'common')}</span>
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
									# IF C_CONTROLS #
										<th class="col-small" aria-label="${LangLoader::get_message('moderation', 'common')}">
											<i class="fa fa-fw fa-gavel hidden-small-screens" aria-hidden="true"></i>
											<span class="hidden-large-screens">${LangLoader::get_message('moderation', 'common')}</span>
										</th>
									# ENDIF #
								</tr>
							</thead>
							<tbody>
								# START items #
									<tr class="category-{items.CATEGORY_ID}">
										<td>
											<a href="{items.U_ITEM}" itemprop="name"# IF items.C_NEW_CONTENT # class="new-content"# ENDIF #>{items.TITLE}</a>
										</td>
										<td>
											<time datetime="# IF NOT items.C_DIFFERED #{items.DATE_ISO8601}# ELSE #{items.DIFFERED_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished">
												# IF NOT items.C_DIFFERED #{items.DATE}# ELSE #{items.DIFFERED_START_DATE}# ENDIF #
											</time>
											# IF items.C_HAS_UPDATE_DATE #
												<time class="pinned notice small text-italic" aria-label="${LangLoader::get_message('form.date.update', 'common')}" datetime="{items.UPDATE_DATE_ISO8601}" itemprop="datePublished">{items.UPDATE_DATE}</time>
											# ENDIF #
										</td>
										<td>
											{items.DOWNLOADS_NUMBER}
										</td>
										# IF C_ENABLED_VIEWS_NUMBER #
											<td>
												{items.VIEWS_NUMBER}
											</td>
										# ENDIF #
										# IF C_ENABLED_NOTATION #
											<td>
												{items.STATIC_NOTATION}
											</td>
										# ENDIF #
										# IF C_ENABLED_COMMENTS #
											<td>
												{items.COMMENTS_NUMBER}
											</td>
										# ENDIF #
										# IF C_CONTROLS #
											<td class="controls">
												# IF items.C_EDIT #
													<a href="{items.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
												# ENDIF #
												# IF items.C_DELETE #
													<a href="{items.U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
												# ENDIF #
											</td>
										# ENDIF #
									</tr>
								# END items #
							</tbody>
						</table>
					</div>
				# ELSE #
					<div class="# IF C_GRID_VIEW #cell-flex cell-columns-{ITEMS_PER_ROW}# ELSE #cell-row# ENDIF #">
						# START items #
							<article id="download-item-{items.ID}" class="download-item several-items category-{items.CATEGORY_ID} cell# IF items.C_NEW_CONTENT # new-content# ENDIF #" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
								<header class="cell-header">
									<h2 class="cell-name"><a href="{items.U_ITEM}" itemprop="name">{items.TITLE}</a></h2>
								</header>
								<div class="cell-body">
									<div class="cell-infos">
										<div class="more">
											<span class="pinned" role="contentinfo" aria-label="{items.L_DOWNLOADED_TIMES}"><i class="fa fa-download" aria-hidden="true"></i> {items.DOWNLOADS_NUMBER}</span>
											# IF C_ENABLED_VIEWS_NUMBER #<span class="pinned" role="contentinfo" aria-label="{items.VIEWS_NUMBER} {@download.view}"><i class="fa fa-eye" aria-hidden="true"></i> {items.VIEWS_NUMBER}</span># ENDIF #
											# IF C_ENABLED_COMMENTS #
												<span class="pinned">
													<i class="fa fa-comments" aria-hidden="true"></i>
													# IF items.C_COMMENTS # {items.COMMENTS_NUMBER} # ENDIF # {items.L_COMMENTS}
												</span>
											# ENDIF #
											<div class="spacer"></div>
											# IF C_ENABLED_NOTATION #
												<div class="pinned">{items.STATIC_NOTATION}</div>
											# ENDIF #
										</div>
										<div class="controls align-right">
											# IF items.C_EDIT #<a href="{items.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF #
											# IF items.C_DELETE #<a href="{items.U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a># ENDIF #
										</div>
									</div>
									# IF NOT C_FULL_ITEM_DISPLAY #
										# IF items.C_HAS_THUMBNAIL #
											<div class="cell-thumbnail cell-landscape cell-center">
												<img src="{items.U_THUMBNAIL}" alt="{items.TITLE}" itemprop="image" />
												<a href="{items.U_ITEM}" class="cell-thumbnail-caption">
													${LangLoader::get_message('see.details', 'common')}
												</a>
											</div>
										# ENDIF #
									# ENDIF #
									<div class="cell-content">
										# IF items.C_VISIBLE #
											<div class="cell-infos">
												<span></span>
												<span>
													<a href="{items.U_DOWNLOAD}" class="button submit small">
														<i class="fa fa-download" aria-hidden="true"></i> {@download.download}
													</a>
													# IF IS_USER_CONNECTED #
														<a href="{items.U_DEADLINK}" data-confirmation="${LangLoader::get_message('deadlink.confirmation', 'common')}" class="button bgc-full warning small" aria-label="${LangLoader::get_message('deadlink', 'common')}">
															<i class="fa fa-unlink" aria-hidden="true"></i>
														</a>
													# ENDIF #
												</span>
											</div>
										# ENDIF #
										<div itemprop="text">
											# IF C_FULL_ITEM_DISPLAY #
												# IF items.C_HAS_THUMBNAIL #
													<a href="{items.U_ITEM}" class="item-thumbnail">
														<img src="{items.U_THUMBNAIL}" alt="{items.TITLE}" itemprop="image" />
													</a>
												# ENDIF #
												{items.CONTENT}
											# ELSE #
												{items.SUMMARY}# IF items.C_READ_MORE # <a href="{items.U_ITEM}" class="read-more">[${LangLoader::get_message('read.more', 'common')}]</a># ENDIF #
											# ENDIF #
										</div>
									</div>
									# IF items.C_HAS_UPDATE_DATE #
										<div class="cell-footer">
											<span class="pinned notice small text-italic modified-date">${LangLoader::get_message('status.last.update', 'common')} <time datetime="{items.UPDATE_DATE_ISO8601}" itemprop="dateModified">{items.UPDATE_DATE_FULL}</time></span>
										</div>
									# ENDIF #
								</div>

								<footer>
									<meta itemprop="url" content="{items.U_ITEM}">
									<meta itemprop="description" content="${escape(items.SUMMARY)}"/>
									# IF C_ENABLED_COMMENTS #
										<meta itemprop="discussionUrl" content="{items.U_COMMENTS}">
										<meta itemprop="interactionCount" content="{items.COMMENTS_NUMBER} UserComments">
									# ENDIF #
								</footer>
							</article>
						# END items #
					</div>
				# ENDIF #
			# ELSE #
				# IF NOT C_HIDE_NO_ITEM_MESSAGE #
					<div class="sub-section">
						<div class="content-container">
							<div class="content">
								<div class="message-helper bgc notice align-center">
									${LangLoader::get_message('no_item_now', 'common')}
								</div>
							</div>
						</div>
					</div>
				# ENDIF #
			# ENDIF #
		</div>
	</div>
	<footer>
		# IF C_PAGINATION #<div class="sub-section"><div class="content-container"># INCLUDE PAGINATION #</div></div># ENDIF #
	</footer>
</section>
