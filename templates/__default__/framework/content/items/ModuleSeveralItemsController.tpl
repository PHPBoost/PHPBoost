<section id="module-{MODULE_ID}">
	<header class="section-header">
		<div class="controls align-right">
			# IF C_SYNDICATION #<a href="{U_SYNDICATION}" aria-label="{@syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a># ENDIF #
			# IF NOT C_ROOT_CATEGORY #{MODULE_NAME}# ENDIF #
			# IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="{@edit}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF ## ENDIF #
		</div>
		<h1>
			# IF C_PENDING #
				{@pending.items}
			# ELSE #
				# IF C_MEMBER_ITEMS #
					# IF C_MY_ITEMS #{@my.items}# ELSE #{@member.items} {MEMBER_NAME}# ENDIF #
				# ELSE #
					# IF C_ROOT_CATEGORY #{MODULE_NAME}# ELSE ## IF C_CATEGORY #{CATEGORY_NAME}# ENDIF ## ENDIF #
				# ENDIF #
			# ENDIF #
		</h1>
	</header>
	# IF C_CATEGORY_DESCRIPTION #
		<div class="sub-section">
			<div class="content-container">
				<div class="cat-description">
					{CATEGORY_DESCRIPTION}
				</div>
			</div>
		</div>
	# ENDIF #

	# IF C_DISPLAY_SUB_CATEGORIES #
		# IF C_SUB_CATEGORIES #
			<div class="sub-section">
				<div class="content-container">
					<div class="cell-flex cell-tile cell-columns-{CATEGORIES_PER_ROW}">
						# START sub_categories_list #
							<div class="cell category-{sub_categories_list.CATEGORY_ID}" itemscope>
								<div class="cell-header">
									<h5 class="cell-name" itemprop="about"><a href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a></h5>
									<span class="small pinned notice" aria-label="{sub_categories_list.ITEMS_NUMBER} # IF sub_categories_list.C_SEVERAL_ITEMS #{@items}# ELSE #{@item}# ENDIF #">
										{sub_categories_list.ITEMS_NUMBER}
									</span>
								</div>
								# IF sub_categories_list.C_CATEGORY_THUMBNAIL #
									<div class="cell-body">
										<div class="cell-thumbnail cell-landscape cell-center">
											<img itemprop="thumbnailUrl" src="{sub_categories_list.U_CATEGORY_THUMBNAIL}" alt="{sub_categories_list.CATEGORY_NAME}" />
											<a class="cell-thumbnail-caption" itemprop="about" href="{sub_categories_list.U_CATEGORY}">
												${LangLoader::get_message('see.category', 'categories-common')}
											</a>
										</div>
									</div>
								# ENDIF #
							</div>
						# END sub_categories_list #
					</div>
					# IF C_SUBCATEGORIES_PAGINATION #<div class="align-center"># INCLUDE SUBCATEGORIES_PAGINATION #</div># ENDIF #
				</div>
			</div>
		# ENDIF #
	# ENDIF #

	<div class="sub-section">
		<div class="content-container">
			# IF C_ITEMS #
				# IF C_ENABLED_SORTING_FORM #
					# IF C_SEVERAL_ITEMS #
						<div class="content">
							# INCLUDE SORTING_FORM #
							<div class="spacer"></div>
						</div>
					# ENDIF #
				# ENDIF #
				# IF C_TABLE_VIEW #
					<div class="responsive-table">
						<table class="table">
							<thead>
								<tr>
									<th>${TextHelper::ucfirst(@form.title)}</th>
									# IF NOT C_MEMBER_ITEMS #
										# IF C_AUTHOR_DISPLAYED #
											<th aria-label="${TextHelper::ucfirst(@author)}">
												<i class="far fa-fw fa-user hidden-small-screens" aria-hidden="true"></i>
												<span class="hidden-large-screens">${TextHelper::ucfirst(@author)}</span>
											</th>
										# ENDIF #
									# ENDIF #
									# IF C_ENABLED_DATE #
										<th class="col-small" aria-label="{@form.date.creation}">
											<i class="far fa-fw fa-calendar-check hidden-small-screens" aria-hidden="true"></i>
											<span class="hidden-large-screens">{@form.date.creation}</span>
										</th>
									# ENDIF #
									# IF C_ENABLED_CATEGORIES #
										<th class="col-small" arai-label="${LangLoader::get_message('category', 'categories-common')}">
											<i class="far fa-fw fa-folder hidden-small-screens" aria-hidden="true"></i>
											<span class="hidden-large-screens">${LangLoader::get_message('category', 'categories-common')}</span>
										</th>
									# ENDIF #
									# IF NOT C_PENDING #
										# IF C_ENABLED_VIEWS #
											<th class="col-small" aria-label="${TextHelper::ucfirst(@views)}">
												<i class="fa fa-fw fa-eye hidden-small-screens" aria-hidden="true"></i>
												<span class="hidden-large-screens">${TextHelper::ucfirst(@views)}</span>
											</th>
										# ENDIF #
										# IF C_ENABLED_VISITS #
											<th class="col-small" aria-label="${TextHelper::ucfirst(@visits)}">
												<i class="fa fa-fw fa-share-square hidden-small-screens" aria-hidden="true"></i>
												<span class="hidden-large-screens">${TextHelper::ucfirst(@visits)}</span>
											</th>
										# ENDIF #
										# IF C_ENABLED_DOWNLOADS #
											<th class="col-small" aria-label="${TextHelper::ucfirst(@downloads)}">
												<i class="fa fa-fw fa-dowload hidden-small-screens" aria-hidden="true"></i>
												<span class="hidden-large-screens">${TextHelper::ucfirst(@downloads)}</span>
											</th>
										# ENDIF #
										# IF C_ENABLED_NOTATION #
											<th class="col-small" aria-label="${TextHelper::ucfirst(@note)}">
												<i class="far fa-fw fa-star hidden-small-screens" aria-hidden="true"></i>
												<span class="hidden-large-screens">${TextHelper::ucfirst(@note)}</span>
											</th>
										# ENDIF #
										# IF C_ENABLED_COMMENTS #
											<th class="col-small" aria-label="${LangLoader::get_message('comments', 'comments-common')}">
												<i class="far fa-fw fa-comments hidden-small-screens" aria-hidden="true"></i>
												<span class="hidden-large-screens">${LangLoader::get_message('comments', 'comments-common')}</span>
											</th>
										# ENDIF #
									# ENDIF #
									# IF C_CONTROLS #
										<th class="col-small" aria-label="{@moderation}">
											<i class="fa fa-fw fa-gavel hidden-small-screens" aria-hidden="true"></i>
											<span class="hidden-large-screens">{@moderation}</span>
										</th>
									# ENDIF #
								</tr>
							</thead>
							<tbody>
								# START items #
									<tr class="category-{items.CATEGORY_ID}# IF items.C_PRIME_ITEM # prime-item# ENDIF #">
										<td>
											<a href="{items.U_ITEM}" itemprop="name"# IF items.C_NEW_CONTENT # class="new-content"# ENDIF#>{items.TITLE}</a>
										</td>
										# IF NOT C_MEMBER_ITEMS #
											# IF C_AUTHOR_DISPLAYED #
												<td>
													# IF items.C_AUTHOR_CUSTOM_NAME #
														<span class="pinned">{items.AUTHOR_CUSTOM_NAME}</span>
													# ELSE #
														# IF items.C_AUTHOR_EXIST #
															<a itemprop="author" href="{items.U_AUTHOR}" class="pinned# IF C_AUTHOR_GROUP_COLOR # {items.AUTHOR_GROUP_COLOR}# ELSE # {items.AUTHOR_LEVEL_CLASS}# ENDIF #">
																{items.AUTHOR_DISPLAY_NAME}
															</a>
														# ELSE #
															<span class="pinned">{items.AUTHOR_DISPLAY_NAME}</span>
														# ENDIF #
													# ENDIF #
												</td>
											# ENDIF #
										# ENDIF #
										# IF C_ENABLED_DATE #
											<td>
												<time datetime="# IF items.C_DEFFERED_PUBLISHING #{items.DEFFERED_PUBLISHING_START_DATE_ISO8601}# ELSE #{items.DATE_ISO8601}# ENDIF #" itemprop="datePublished">
													# IF items.C_DEFFERED_PUBLISHING #{items.DEFFERED_PUBLISHING_START_DATE}# ELSE #{items.DATE}# ENDIF #
												</time>
												# IF items.C_HAS_UPDATE_DATE #
													<time class="pinned notice small text-italic" aria-label="{@form.date.update}"datetime="{items.UPDATE_DATE_ISO8601}" itemprop="dateModified">
														{items.UPDATE_DATE}
													</time>
												# ENDIF #
											</td>
										# ENDIF #
										# IF C_ENABLED_CATEGORIES #
											<td>
												<a itemprop="about" href="{items.U_CATEGORY}">{items.CATEGORY_NAME}</a>
											</td>
										# ENDIF #
										# IF NOT C_PENDING #
											# IF C_ENABLED_VIEWS #
												<td>
													{items.VIEWS_NUMBER}
												</td>
											# ENDIF #
											# IF C_ENABLED_VISITS #
												<td class="col-small">
													{items.VISITS_NUMBER}
												</td>
											# ENDIF #
											# IF C_ENABLED_DOWNLOADS #
												<td class="col-small">
													{items.DOWNLOADS_NUMBER} # IF items.C_SEVERAL_DOWNLOADS #{@downloads}# ELSE #{@download}# ENDIF #
												</td>
											# ENDIF #
											# IF C_ENABLED_NOTATION #
												<td>
													{items.STATIC_NOTATION}
												</td>
											# ENDIF #
											# IF C_ENABLED_COMMENTS #
												<td>
													<a href="{items.U_COMMENTS}">{items.COMMENTS_NUMBER}</a>
												</td>
											# ENDIF #
										# ENDIF #
										# IF items.C_CONTROLS #
											<td class="controls">
												# IF items.C_EDIT #<a href="{items.U_EDIT}" aria-label="{@edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF #
												# IF items.C_DELETE #<a href="{items.U_DELETE}" data-confirmation="delete-element" aria-label="{@delete}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a># ENDIF #
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
							<article id="{MODULE_ID}-item-{items.ID}" class="{MODULE_ID}-item several-items category-{items.CATEGORY_ID} cell# IF items.C_PRIME_ITEM # prime-item# ENDIF ## IF items.C_NEW_CONTENT # new-content# ENDIF#" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
								<header class="cell-header">
									<h2 class="cell-name"><a href="{items.U_ITEM}" itemprop="name">{items.TITLE}</a></h2>
								</header>
								<div class="cell-body">
									<div class="cell-infos">
										<div class="more">
											# IF NOT C_MEMBER_ITEMS #
												# IF C_AUTHOR_DISPLAYED #
													<i class="far fa-user"></i>
													# IF items.C_AUTHOR_CUSTOM_NAME #
														<span class="pinned">{items.AUTHOR_CUSTOM_NAME}</span>
													# ELSE #
														# IF items.C_AUTHOR_EXIST #
															<a itemprop="author" href="{items.U_AUTHOR}" class="pinned# IF C_AUTHOR_GROUP_COLOR # {items.AUTHOR_GROUP_COLOR}# ELSE # {items.AUTHOR_LEVEL_CLASS}# ENDIF #">
																{items.AUTHOR_DISPLAY_NAME}
															</a>
														# ELSE #
															<span class="pinned">{items.AUTHOR_DISPLAY_NAME}</span>
														# ENDIF #
													# ENDIF #
												# ENDIF #
											# ENDIF #
											# IF C_ENABLED_DATE #
												<span class="pinned">
													<i class="far fa-calendar-alt" aria-hidden="true"></i>
													<time datetime="# IF items.C_DEFFERED_PUBLISHING #{items.DEFFERED_PUBLISHING_START_DATE_ISO8601}# ELSE #{items.DATE_ISO8601}# ENDIF #" itemprop="datePublished">
														# IF items.C_DEFFERED_PUBLISHING #
															{items.DEFFERED_PUBLISHING_START_DATE}
														# ELSE #
															{items.DATE}
														# ENDIF #
													</time>
												</span>
											# ENDIF #
											# IF C_ENABLED_UPDATE_DATE #
												# IF items.C_HAS_UPDATE_DATE #
												<span class="pinned">
													<i class="far fa-calendar-plus" aria-hidden="true"></i>
													<time datetime="{items.UPDATE_DATE_ISO8601}" itemprop="dateModified">
														{items.UPDATE_DATE}
													</time>
												</span>
												# ENDIF #
											# ENDIF #
											# IF C_ENABLED_CATEGORIES #
												# IF items.C_HAS_CATEGORY #
													# IF NOT items.C_ROOT_CATEGORY #
														<span class="pinned">
															<a itemprop="about" href="{items.U_CATEGORY}"><i class="far fa-folder" aria-hidden="true"></i> {items.CATEGORY_NAME}</a>
														</span>
													# ENDIF #
												# ENDIF #
											# ENDIF #
											# IF NOT C_PENDING #
												# IF C_ENABLED_VIEWS #
													<span class="pinned" role="contentinfo" aria-label="{items.VIEWS_NUMBER} # IF items.C_SEVERAL_VIEWS #{@views}# ELSE #{@view}# ENDIF #"><i class="fa fa-eye" aria-hidden="true"></i> {items.VIEWS_NUMBER}</span>
												# ENDIF #
												# IF C_ENABLED_VISITS #
													<span class="pinned">
														{items.VISITS_NUMBER} # IF C_SEVERAL_VISITS #{@visits}# ELSE #{@visit}# ENDIF #
													</span>
												# ENDIF #
												# IF C_ENABLED_DOWNLOADS #
													<span class="pinned">
														{items.DOWNLOADS_NUMBER} # IF C_SEVERAL_DOWNLOADS #{@downloads}# ELSE #{@download}# ENDIF #
													</span>
												# ENDIF #
												# IF C_ENABLED_NOTATION #
													<div class="pinned">{items.STATIC_NOTATION}</div>
												# ENDIF #
												# IF C_ENABLED_COMMENTS #
													<span class="pinned">
														<a href="{items.U_COMMENTS}"><i class="fa fa-comments" aria-hidden="true"></i> {items.COMMENTS_LABEL}</a>
													</span>
												# ENDIF #
											# ENDIF #
										</div>
										# IF items.C_CONTROLS #
											<div class="controls align-right">
												# IF items.C_EDIT #<a href="{items.U_EDIT}" aria-label="{@edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF #
												# IF items.C_DELETE #<a href="{items.U_DELETE}" data-confirmation="delete-element" aria-label="{@delete}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a># ENDIF #
											</div>
										# ENDIF #
									</div>
									# IF NOT C_FULL_ITEM_DISPLAY #
										# IF items.C_HAS_THUMBNAIL #
											<div class="cell-thumbnail cell-landscape cell-center">
												<img src="{items.U_THUMBNAIL}" alt="{items.TITLE}" itemprop="image" />
												<a class="cell-thumbnail-caption" href="{items.U_ITEM}">
													{@see.details}
												</a>
											</div>
										# ENDIF #
									# ENDIF #
									<div class="cell-content">
										# IF C_ENABLED_VISIT #
											<div class="cell-infos">
												<span></span>
												<span>
													<a href="{items.U_VISIT}" class="button submit small">
														<i class="fa fa-globe" aria-hidden="true"></i> {@go.website}
													</a>
													# IF IS_USER_CONNECTED #
														<a href="{items.U_DEADLINK}" data-confirmation="{@deadlink.confirmation}" class="button bgc-full warning small" aria-label="{@deadlink}">
															<i class="fa fa-unlink" aria-hidden="true"></i>
														</a>
													# ENDIF #
												</span>
											</div>
										# ENDIF #
										# IF C_ENABLED_DOWNLOAD #
											<div class="cell-infos">
												<span></span>
												<span>
													<a href="{items.U_DOWNLOAD}" class="button submit small">
														<i class="fa fa-dowload" aria-hidden="true"></i> {@go.download}
													</a>
													# IF IS_USER_CONNECTED #
														<a href="{items.U_DEADLINK}" data-confirmation="{@deadlink.confirmation}" class="button bgc-full warning small" aria-label="{@deadlink}">
															<i class="fa fa-unlink" aria-hidden="true"></i>
														</a>
													# ENDIF #
												</span>
											</div>
										# ENDIF #
										<div itemprop="text">
											# IF C_FULL_ITEM_DISPLAY #
												# IF items.C_HAS_THUMBNAIL #
													<a class="item-thumbnail" href="{items.U_ITEM}">
														<img src="{items.U_THUMBNAIL}" alt="{items.TITLE}" itemprop="image" />
													</a>
												# ENDIF #
												{items.CONTENT}
											# ELSE #
												{items.SUMMARY}
												# IF items.C_READ_MORE # <a href="{items.U_ITEM}" class="read-more">[{@read.more}]</a># ENDIF #
											# ENDIF #
										</div>
									</div>
								</div>

								<footer>
									# IF items.C_KEYWORDS #
										<div class="tags-container">
											<span class="text-strong"><i class="fa fa-tags" aria-hidden="true"></i> {@form.keywords}</span> :
											# START items.keywords #
												<a itemprop="keywords" href="{items.keywords.URL}" class="pinned question">{items.keywords.NAME}</a>
											# END items.keywords #
										</div>
									# ENDIF #
									# IF items.C_SOURCES #
										<div class="sources-container">
											<span class="text-strong"><i class="fa fa-map-signs" aria-hidden="true"></i> {@form.sources}</span> :
											# START items.sources #
												<a itemprop="isBasedOnUrl" href="{items.sources.URL}" class="pinned question" rel="nofollow">{items.sources.NAME}</a># IF items.sources.C_SEPARATOR ## ENDIF #
											# END items.sources #
										</div>
									# ENDIF #
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
					<div class="content">
						<div class="message-helper bgc notice align-center">
							{@items.no.element}
						</div>
					</div>
				# ENDIF #
			# ENDIF #
		</div>
	</div>
	<footer>
		# IF C_PAGINATION #
			<div class="sub-section">
				<div class="content-container">
					<div class="content align-center"># INCLUDE PAGINATION #</div>
				</div>
			</div>
		# ENDIF #
	</footer>
</section>
