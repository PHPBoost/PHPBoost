<section id="module-{MODULE_ID}">
	<header>
		<div class="align-right controls">
			# IF C_SYNDICATION #<a href="{U_SYNDICATION}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a># ENDIF #
			# IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF ## ENDIF #
		</div>
		<h1>
			# IF C_PENDING #
				{@items.pending}
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

	# IF C_SUB_CATEGORIES #
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
							<div class="cell-thumbnail">
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
	# ENDIF #

	# IF C_ITEMS #
		# IF C_SEVERAL_ITEMS #
			# INCLUDE SORTING_FORM #
			<div class="spacer"></div>
		# ENDIF #
		# IF C_TABLE_VIEW #
			<table class="table">
				<thead>
					<tr>
						<th>${TextHelper::ucfirst(@title)}</th>
						# IF C_ENABLED_AUTHOR #<th class="col-small">${TextHelper::ucfirst(@author)}</th># ENDIF #
						# IF C_ENABLED_DATE #<th class="col-small">${TextHelper::ucfirst(@date)}</th># ENDIF #
						# IF C_ENABLED_CATEGORY #<th class="col-small">${TextHelper::ucfirst(@category)}</th># ENDIF #
						# IF C_ENABLED_VIEWS #<th class="col-small">${TextHelper::ucfirst(@views)}</th># ENDIF #
						# IF C_ENABLED_VISITS #<th class="col-small">${TextHelper::ucfirst(@visits)}</th># ENDIF #
						# IF C_ENABLED_DOWNLOADS #<th class="col-small">${TextHelper::ucfirst(@downloads)}</th># ENDIF #
						# IF C_ENABLED_NOTATION #<th class="col-small">${TextHelper::ucfirst(@note)}</th># ENDIF #
						# IF C_ENABLED_COMMENTS #<th class="col-small">${LangLoader::get_message('comments', 'comments-common')}</th># ENDIF #
						# IF C_CONTROLS #<th class="col-smaller">${TextHelper::ucfirst(@controls)}</th># ENDIF #
					</tr>
				</thead>
				<tbody>
					# START items #
						<tr class="category-{items.CATEGORY_ID}">
							<td>
								<a href="{items.U_ITEM}" itemprop="name"# IF items.C_NEW_CONTENT # class="new-content"# ENDIF#>{items.TITLE}</a>
							</td>
							# IF C_ENABLED_AUTHOR #
								<td>
									# IF items.C_AUTHOR_DISPLAYED #
										<i class="far fa-user"></i>
										# IF items.C_AUTHOR_CUSTOM_NAME #
											<span class="pinned">{items.AUTHOR_CUSTOM_NAME}</span>
										# ELSE #
											# IF items.C_AUTHOR_EXIST #
												<a itemprop="author" href="{items.U_AUTHOR}" class="pinned# IF C_AUTHOR_GROUP_COLOR # {items.AUTHOR_GROUP_COLOR}# ELSE # {items.AUTHOR_LEVEL_CLASS}# ENDIF #">
													{items.PSEUDO}
												</a>
											# ELSE #
												<span class="pinned">{items.PSEUDO}</span>
											# ENDIF #
										# ENDIF #
									# ENDIF #
								</td>
							# ENDIF #
							# IF C_ENABLED_DATE #
								<td>
									<time datetime="# IF NOT items.C_DIFFERED #{items.DATE_ISO8601}# ELSE #{items.PUBLISHING_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished">
										# IF NOT items.C_DIFFERED #
											{items.DATE}
										# ELSE #
											{items.PUBLISHING_START_DATE}
										# ENDIF #
									</time>
								</td>
							# ENDIF #
							# IF C_ENABLED_CATEGORY #
								<td>
									<a itemprop="about" href="{items.U_CATEGORY}"><i class="far fa-folder" aria-hidden="true"></i> {items.CATEGORY_NAME}</a>
								</td>
							# ENDIF #
							# IF C_ENABLED_VIEWS #
								<td>
									{items.VIEWS_NUMBER} # IF items.C_SEVERAL_VIEWS #{@views}# ELSE #{@view}# ENDIF #
								</td>
							# ENDIF #
							# IF C_ENABLED_VISITS #
								<td class="col-small">
									{items.VISITS_NUMBER} # IF items.C_SEVERAL_VISITS #{@visits}# ELSE #{@visit}# ENDIF #
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
									{items.COMMENTS_NUMBER} # IF items.C_SEVERAL_COMMENTS #${TextHelper::lcfirst(LangLoader::get_message('comments', 'comments-common'))}# ELSE #${TextHelper::lcfirst(LangLoader::get_message('comment', 'comments-common'))}# ENDIF #
								</td>
							# ENDIF #
							# IF items.C_CONTROLS #
								<td>
									<a href="{items.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
									<a href="{items.U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
								</td>
							# ENDIF #
						</tr>
					# END items #
				</tbody>
			</table>
		# ELSE #
			<div class="# IF C_GRID_VIEW #cell-flex cell-columns-{ITEMS_PER_ROW}# ELSE #cell-row# ENDIF #">
				# START items #
					<article id="{MODULE_ID}-item-{items.ID}" class="{MODULE_ID}-item several-items category-{items.CATEGORY_ID} cell# IF items.C_PRIME_ITEM # prime-item# ENDIF ## IF items.C_NEW_CONTENT # new-content# ENDIF#" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
						<header class="cell-header">
							<h2 class="cell-name"><a href="{items.U_ITEM}" itemprop="name">{items.TITLE}</a></h2>
						</header>
						<div class="cell-body">
							<div class="cell-infos">
								<div class="more">
									# IF C_ENABLED_AUTHOR #
										# IF items.C_AUTHOR_DISPLAYED #
											<i class="far fa-user"></i>
											# IF items.C_AUTHOR_CUSTOM_NAME #
												<span class="pinned">{items.AUTHOR_CUSTOM_NAME}</span>
											# ELSE #
												# IF items.C_AUTHOR_EXIST #
													<a itemprop="author" href="{items.U_AUTHOR}" class="pinned# IF C_AUTHOR_GROUP_COLOR # {items.AUTHOR_GROUP_COLOR}# ELSE # {items.AUTHOR_LEVEL_CLASS}# ENDIF #">
														{items.PSEUDO}
													</a>
												# ELSE #
													<span class="pinned">{items.PSEUDO}</span>
												# ENDIF #
											# ENDIF #
										# ENDIF #
									# ENDIF #
									# IF C_ENABLED_DATE #
										<span class="pinned">
											<i class="far fa-calendar-alt" aria-hidden="true"></i>
											<time datetime="# IF items.C_DIFFERED #{items.PUBLISHING_START_DATE_ISO8601}# ELSE #{items.DATE_ISO8601}# ENDIF #" itemprop="datePublished">
												# IF items.C_DIFFERED #
													{items.PUBLISHING_START_DATE}
												# ELSE #
													{items.DATE}
												# ENDIF #
											</time>
										</span>
									# ENDIF #
									# IF C_ENABLED_CATEGORY #
										<span class="pinned">
											<a itemprop="about" href="{items.U_CATEGORY}"><i class="far fa-folder" aria-hidden="true"></i> {items.CATEGORY_NAME}</a>
										</span>
									# ENDIF #
									# IF C_ENABLED_VIEWS #
										<span class="pinned" role="contentinfo" aria-label="{items.VIEWS_NUMBER} {@views.number}"><i class="fa fa-eye" aria-hidden="true"></i> {items.VIEWS_NUMBER}</span>
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
											<i class="fa fa-comments" aria-hidden="true"></i>
											{items.COMMENTS_NUMBER} # IF items.C_SEVERAL_COMMENTS #${TextHelper::lcfirst(LangLoader::get_message('comments', 'comments-common'))}# ELSE #${TextHelper::lcfirst(LangLoader::get_message('comment', 'comments-common'))}# ENDIF #
										</span>
									# ENDIF #
								</div>
								# IF items.C_CONTROLS #
									<div class="controls align-right">
										<a href="{items.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
										<a href="{items.U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
									</div>
								# ENDIF #
							</div>
							# IF NOT C_FULL_ITEM_DISPLAY #
								# IF items.C_HAS_THUMBNAIL #
									<div class="cell-thumbnail">
										<img src="{items.U_THUMBNAIL}" alt="{items.TITLE}" itemprop="image" />
										<a class="cell-thumbnail-caption" href="{items.U_ITEM}">
											${LangLoader::get_message('see.details', 'common')}
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
												<a href="{items.U_DEADLINK}" data-confirmation="${LangLoader::get_message('deadlink.confirmation', 'common')}" class="button bgc-full warning small" aria-label="${LangLoader::get_message('deadlink', 'common')}">
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
											<a class="item-thumbnail" href="{items.U_ITEM}">
												<img src="{items.U_THUMBNAIL}" alt="{items.TITLE}" itemprop="image" />
											</a>
										# ENDIF #
										{items.CONTENT}
									# ELSE #
										{items.SUMMARY}
										# IF items.C_READ_MORE #... <a href="{items.U_ITEM}" class="read-more">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #
									# ENDIF #
								</div>
							</div>
						</div>

						<footer>
							<meta itemprop="url" content="{items.U_ITEM}">
							<meta itemprop="description" content="${escape(items.SUMMARY)}"/>
							# IF C_COMMENTS_ENABLED #
								<meta itemprop="discussionUrl" content="{items.U_COMMENTS}">
								<meta itemprop="interactionCount" content="{items.COMMENTS_NUMBER} UserComments">
							# ENDIF #
						</footer>
					</article>
				# END items #
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
