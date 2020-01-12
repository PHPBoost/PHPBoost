<section id="module-{MODULE_ID}">
	<header>
		<div class="align-right controls">
			# IF C_SYNDICATION #<a href="${relative_url(SyndicationUrlBuilder::rss('{MODULE_ID}', ID_CAT))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden></i></a># ENDIF #
			# IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit" aria-hidden></i></a># ENDIF ## ENDIF #
		</div>
		<h1>
			# IF C_PENDING #
				{MODULE_NAME} {@pending.items}
			# ELSE #
				{MODULE_NAME}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
			# ENDIF #
		</h1>
	</header>
	# IF C_CATEGORY_DESCRIPTION #
		<div class="cat-description">
			{CATEGORY_DESCRIPTION}
		</div>
	# ENDIF #

	# IF C_ENABLED_CATEGORY #
		# IF C_SUB_CATEGORIES #
			<div class="cell-flex cell-tile cell-columns-{CATEGORIES_PER_ROW}">
				# START sub_categories_list #
					<div class="cell" itemscope>
						<div class="cell-header">
							<h5 class="cell-name" itemprop="about"><a href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a></h5>
							<span class="small pinned notice" aria-label="{sub_categories_list.ITEMS_NUMBER} # IF sub_categories_list.C_SEVERAL_ITEMS #{@module.items}# ELSE #{@module.item}# ENDIF #">
								{sub_categories_list.ITEMS_NUMBER}
							</span>
						</div>
						# IF C_ENABLED_CATEGORY_THUMBNAIL #
							# IF sub_categories_list.C_CATEGORY_THUMBNAIL #
								<div class="cell-body">
									<div class="cell-thumbnail">
										<img itemprop="thumbnailUrl" src="{sub_categories_list.U_CATEGORY_THUMBNAIL}" alt="{sub_categories_list.CATEGORY_NAME}" />
										<a class="cell-thumbnail-caption" itemprop="about" href="{sub_categories_list.U_CATEGORY}">
											${LangLoader::get_message('see.category', 'categories-common')}
										</a>
									</div>
								</div>
							# ENDIF #
						# ENDIF #
					</div>
				# END sub_categories_list #
			</div>
			# IF C_SUBCATEGORIES_PAGINATION #<div class="align-center"># INCLUDE SUBCATEGORIES_PAGINATION #</div># ENDIF #
		# ENDIF #
	# ENDIF #

	# IF C_ITEMS #
		# IF C_SEVERAL_ITEMS #
			# IF C_ENABLED_FILTERS #
				# INCLUDE SORT_FILTERS #
				<div class="spacer"></div>
			# ENDIF #
		# ENDIF #
		# IF C_TABLE_VIEW #
			<table class="table">
				<thead>
					<tr>
						<th>{@Title}</th>
						# IF C_ENABLED_AUTHOR #<th class="col-small">{@Author}</th># ENDIF #
						# IF C_ENABLED_DATE #<th class="col-small">{@Date}</th># ENDIF #
						# IF C_ENABLED_CATEGORY #<th class="col-small">{@Category}</th># ENDIF #
						# IF C_ENABLED_VIEWS #<th class="col-small">{@Views}</th># ENDIF #
						# IF C_ENABLED_VISITS #<th class="col-small">{@Visits}</th># ENDIF #
						# IF C_ENABLED_DOWNLOADS #<th class="col-small">{@Downloads}</th># ENDIF #
						# IF C_ENABLED_NOTATION #<th class="col-small">{@Note}</th># ENDIF #
						# IF C_ENABLED_COMMENTS #<th class="col-small">{@Comments}</th># ENDIF #
						# IF C_CONTROLS #<th class="col-smaller">{@Controls}</th># ENDIF #
					</tr>
				</thead>
				<tbody>
					# START module_items #
						<tr>
							<td>
								<a href="{module_items.U_ITEM}" itemprop="name"# IF module_items.C_NEW_CONTENT # class="new-content"# ENDIF#>{module_items.TITLE}</a>
							</td>
							# IF module_items.C_ENABLED_AUTHOR #
								<td>
									# IF module_items.C_AUTHOR_DISPLAYED #
										<i class="far fa-user"></i>
										# IF module_items.C_AUTHOR_CUSTOM_NAME #
											<span class="pinned">{module_items.AUTHOR_CUSTOM_NAME}</span>
										# ELSE #
											# IF module_items.C_AUTHOR_EXIST #
												<a itemprop="author" href="{module_items.U_AUTHOR}" class="pinned# IF C_USER_GROUP_COLOR # {module_items.USER_GROUP_COLOR}# ELSE # {module_items.USER_LEVEL_CLASS}# ENDIF #">
													{module_items.PSEUDO}
												</a>
											# ELSE #
												<span class="pinned">{module_items.PSEUDO}</span>
											# ENDIF #
										# ENDIF #
									# ENDIF #
								</td>
							# ENDIF #
							# IF module_items.C_ENABLED_DATE #
								<td>
									<time datetime="# IF NOT module_items.C_DIFFERED #{module_items.DATE_ISO8601}# ELSE #{module_items.PUBLISHING_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished">
										# IF NOT module_items.C_DIFFERED #
											{module_items.DATE}
										# ELSE #
											{module_items.PUBLISHING_START_DATE}
										# ENDIF #
									</time>
								</td>
							# ENDIF #
							# IF module_items.C_ENABLED_CATEGORY #
								<td>
									<a itemprop="about" href="{module_items.U_CATEGORY}"><i class="far fa-folder" aria-hidden></i> {module_items.CATEGORY_NAME}</a>
								</td>
							# ENDIF #
							# IF module_items.C_ENABLED_VIEWS #
								<td>
									{module_items.VIEWS_NUMBER} # IF C_SEVERAL_VIEWS #{@views}# ELSE #{@view}# ENDIF #
								</td>
							# ENDIF #
							# IF module_items.C_ENABLED_VISITS #
								<td class="col-small">
									{module_items.VISITS_NUMBER} # IF C_SEVERAL_VISITS #{@visits}# ELSE #{@visit}# ENDIF #
								</td>
							# ENDIF #
							# IF module_items.C_ENABLED_DOWNLOADS #
								<td class="col-small">
									{module_items.DOWNLOADS_NUMBER} # IF C_SEVERAL_DOWNLOADS #{@downloads}# ELSE #{@download}# ENDIF #
								</td>
							# ENDIF #
							# IF module_items.C_ENABLED_NOTATION #
								<td>
									{module_items.STATIC_NOTATION}
								</td>
							# ENDIF #
							# IF module_items.C_ENABLED_COMMENTS #
								<td>
									{module_items.COMMENTS_NUMBER} # IF C_SEVERAL_COMMENTS #{@comments}# ELSE #{@comment}# ENDIF #
								</td>
							# ENDIF #
							# IF module_items.C_CONTROLS #
								<td>
									# IF module_items.C_EDIT #
										<a href="{module_items.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden></i></a>
									# ENDIF #
									# IF module_items.C_DELETE #
										<a href="{module_items.U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="far fa-fw fa-trash-alt" aria-hidden></i></a>
									# ENDIF #
								</td>
							# ENDIF #
						</tr>
					# END module_items #
				</tbody>
			</table>
		# ELSE #
			<div class="# IF C_GRID_VIEW #cell-flex cell-columns-{ITEMS_PER_ROW}# ELSE #cell-row# ENDIF #">
				# START module_items #
					<article id="{MODULE_ID}-item-{module_items.ID}" class="{MODULE_ID}-item several-items cell# IF module_items.C_PRIME_ITEM # prime-item# ENDIF## IF module_items.C_NEW_CONTENT # new-content# ENDIF#" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
						<header class="cell-header">
							<h2 class="cell-name"><a href="{module_items.U_ITEM}" itemprop="name">{module_items.TITLE}</a></h2>
						</header>
						<div class="cell-body">
							<div class="cell-infos">
								<div class="more">
									# IF module_items.C_ENABLED_AUTHOR #
										# IF module_items.C_AUTHOR_DISPLAYED #
											<i class="far fa-user"></i>
											# IF module_items.C_AUTHOR_CUSTOM_NAME #
												<span class="pinned">{module_items.AUTHOR_CUSTOM_NAME}</span>
											# ELSE #
												# IF module_items.C_AUTHOR_EXIST #
													<a itemprop="author" href="{module_items.U_AUTHOR}" class="pinned# IF C_USER_GROUP_COLOR # {module_items.USER_GROUP_COLOR}# ELSE # {module_items.USER_LEVEL_CLASS}# ENDIF #">
														{module_items.PSEUDO}
													</a>
												# ELSE #
													<span class="pinned">{module_items.PSEUDO}</span>
												# ENDIF #
											# ENDIF #
										# ENDIF #
									# ENDIF #
									# IF module_items.C_ENABLED_DATE #
										<span class="pinned">
											<i class="far fa-calendar-alt"></i>
											<time datetime="# IF module_items.C_DIFFERED #{module_items.PUBLISHING_START_DATE_ISO8601}# ELSE #{module_items.DATE_ISO8601}# ENDIF #" itemprop="datePublished">
												# IF module_items.C_DIFFERED #
													{module_items.PUBLISHING_START_DATE}
												# ELSE #
													{module_items.DATE}
												# ENDIF #
											</time>
										</span>
									# ENDIF #
									# IF module_items.C_ENABLED_CATEGORY #
										<span class="pinned">
											<a itemprop="about" href="{module_items.U_CATEGORY}"><i class="far fa-folder" aria-hidden></i> {module_items.CATEGORY_NAME}</a>
										</span>
									# ENDIF #
									# IF module_items.C_ENABLED_VIEWS #
										<span class="pinned" aria-label="{module_items.VIEWS_NUMBER} {@views.number}"><i class="fa fa-eye" aria-hidden></i> {module_items.VIEWS_NUMBER}</span>
									# ENDIF #
									# IF module_items.C_ENABLED_VISITS #
										<span class="pinned">
											{module_items.VISITS_NUMBER} # IF C_SEVERAL_VISITS #{@visits}# ELSE #{@visit}# ENDIF #
										</span>
									# ENDIF #
									# IF module_items.C_ENABLED_DOWNLOADS #
										<span class="pinned">
											{module_items.DOWNLOADS_NUMBER} # IF C_SEVERAL_DOWNLOADS #{@downloads}# ELSE #{@download}# ENDIF #
										</span>
									# ENDIF #
									# IF module_items.C_ENABLED_NOTATION #
										<span class="pinned">{module_items.STATIC_NOTATION}</span>
									# ENDIF #
									# IF module_items.C_ENABLED_COMMENTS #
										<span class="pinned">
											<i class="fa fa-comments" aria-hidden></i>
											{module_items.COMMENTS_NUMBER}  # IF C_SEVERAL_COMMENTS #{@comments}# ELSE #{@comment}# ENDIF #
										</span>
									# ENDIF #
								</div>
								# IF module_items.C_CONTROLS #
									<div class="controls align-right">
										# IF module_items.C_EDIT #<a href="{module_items.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden></i></a># ENDIF #
										# IF module_items.C_DELETE #<a href="{module_items.U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="far fa-fw fa-trash-alt" aria-hidden></i></a># ENDIF #
									</div>
								# ENDIF #
							</div>
							# IF module_items.C_HAS_THUMBNAIL #
								<div class="cell-thumbnail">
									<img src="{module_items.U_THUMBNAIL}" alt="{module_items.TITLE}" itemprop="image" />
									<a class="cell-thumbnail-caption" href="{module_items.U_ITEM}">
										${LangLoader::get_message('see.details', 'common')}
									</a>
								</div>
							# ENDIF #
							<div class="cell-content">
								# IF module_items.C_ENABLED_VISIT #
									<div class="cell-infos">
										<span></span>
										<span>
											<a href="{module_items.U_VISIT}" class="button submit small">
												<i class="fa fa-globe" aria-hidden></i> {@go.website}
											</a>
											# IF IS_USER_CONNECTED #
												<a href="{module_items.U_DEADLINK}" data-confirmation="${LangLoader::get_message('deadlink.confirmation', 'common')}" class="button bgc-full warning small" aria-label="${LangLoader::get_message('deadlink', 'common')}">
													<i class="fa fa-unlink" aria-hidden></i>
												</a>
											# ENDIF #
										</span>
									</div>
								# ENDIF #
								# IF module_items.C_ENABLED_DOWNLOAD #
									<div class="cell-infos">
										<span></span>
										<span>
											<a href="{module_items.U_DOWNLOAD}" class="button submit small">
												<i class="fa fa-dowload" aria-hidden></i> {@go.download}
											</a>
											# IF IS_USER_CONNECTED #
												<a href="{module_items.U_DEADLINK}" data-confirmation="${LangLoader::get_message('deadlink.confirmation', 'common')}" class="button bgc-full warning small" aria-label="${LangLoader::get_message('deadlink', 'common')}">
													<i class="fa fa-unlink" aria-hidden></i>
												</a>
											# ENDIF #
										</span>
									</div>
								# ENDIF #
								<div itemprop="text">
									# IF C_FULL_ITEM_DISPLAY #
										{module_items.CONTENTS}
									# ELSE #
										{module_items.SHORT_CONTENTS}
										# IF module_items.C_READ_MORE #... <a href="{module_items.U_ITEM}" class="read-more">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #
									# ENDIF #
								</div>
							</div>
						</div>

						<footer>
							<meta itemprop="url" content="{module_items.U_ITEM}">
							<meta itemprop="description" content="${escape(module_items.SHORT_CONTENTS)}"/>
							# IF C_COMMENTS_ENABLED #
								<meta itemprop="discussionUrl" content="{module_items.U_COMMENTS}">
								<meta itemprop="interactionCount" content="{module_items.COMMENTS_NUMBER} UserComments">
							# ENDIF #
						</footer>
					</article>
				# END module_items #
			</div>
		# ENDIF #

	# ELSE #
		<div class="content">
			# IF NOT C_HIDE_NO_ITEM_MESSAGE #
				<div class="align-center">
					${LangLoader::get_message('no_item_now', 'common')}
				</div>
			# ENDIF #
		</div>
	# ENDIF #

	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>
