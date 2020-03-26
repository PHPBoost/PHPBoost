<section id="module-web">
	<header>
		<div class="align-right controls">
			<a href="${relative_url(SyndicationUrlBuilder::rss('web', ID_CAT))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			# IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF ## ENDIF #
		</div>
		<h1>
			# IF C_PENDING #{@web.pending}# ELSE #{@module_title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF #
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
						<span class="small pinned notice" role="contentinfo" aria-label="{sub_categories_list.ITEMS_NUMBER} # IF sub_categories_list.C_SEVERAL_ITEMS #${TextHelper::lcfirst(LangLoader::get_message('links', 'common', 'web'))}# ELSE #${TextHelper::lcfirst(LangLoader::get_message('link', 'common', 'web'))}# ENDIF #">
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
			# INCLUDE SORT_FORM #
			<div class="spacer"></div>
		# ENDIF #
		# IF C_TABLE_VIEW #
			<table class="table">
				<thead>
					<tr>
						<th>${LangLoader::get_message('form.name', 'common')} <span class="small more">(${LangLoader::get_message('see.details', 'common')})</span></th>
						<th class="col-small">{@visits_number}</th>
						# IF C_ENABLED_NOTATION #<th>${LangLoader::get_message('note', 'common')}</th># ENDIF #
						# IF C_ENABLED_COMMENTS #<th class="col-small">${LangLoader::get_message('comments', 'comments-common')}</th># ENDIF #
						# IF C_MODERATE #<th class="col-smaller"></th># ENDIF #
					</tr>
				</thead>
				<tbody>
					# START weblinks #
						<tr class="category-{weblinks.CATEGORY_ID}">
							<td>
								<a href="{weblinks.U_ITEM}" itemprop="name"# IF weblinks.C_NEW_CONTENT # class="new-content"# ENDIF#>{weblinks.TITLE}</a>
							</td>
							<td>
								{weblinks.VIEWS_NUMBER}
							</td>
							# IF C_ENABLED_NOTATION #
								<td>
									{weblinks.STATIC_NOTATION}
								</td>
							# ENDIF #
							# IF C_ENABLED_COMMENTS #
								<td>
									# IF weblinks.C_COMMENTS # {weblinks.COMENTS_NUMBER} # ENDIF # {weblinks.L_COMMENTS}
								</td>
							# ENDIF #
							# IF weblinks.C_CONTROLS #
								<td>
									# IF weblinks.C_EDIT #
									<a href="{weblinks.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
									# ENDIF #
									# IF weblinks.C_DELETE #
									<a href="{weblinks.U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
									# ENDIF #
								</td>
							# ENDIF #
						</tr>
					# END weblinks #
				</tbody>
			</table>
		# ELSE #
			<div class="# IF C_GRID_VIEW #cell-flex cell-columns-{ITEMS_PER_ROW}# ELSE #cell-row# ENDIF #">
				# START weblinks #
					<article id="web-item-{weblinks.ID}" class="web-item several-items category-{weblinks.CATEGORY_ID} cell# IF weblinks.C_IS_PARTNER # content-friends# ENDIF ## IF weblinks.C_IS_PRIVILEGED_PARTNER # content-privileged-friends# ENDIF ## IF weblinks.C_NEW_CONTENT # new-content# ENDIF#" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
						<header class="cell-header">
							<h2 class="cell-name"><a href="{weblinks.U_ITEM}" itemprop="name">{weblinks.TITLE}</a></h2>
						</header>
						<div class="cell-body">
							<div class="cell-infos">
								<div class="more">
									<span class="pinned"><i class="fa fa-eye" aria-hidden="true"></i> {weblinks.VIEWS_NUMBER}</span>
									# IF C_ENABLED_COMMENTS #
										<span class="pinned">
											<i class="fa fa-comments" aria-hidden="true"></i>
											# IF weblinks.C_COMMENTS # {weblinks.COMMENTS_NUMBER} # ENDIF # {weblinks.L_COMMENTS}
										</span>
									# ENDIF #
									# IF C_ENABLED_NOTATION #
										<div class="pinned">{weblinks.STATIC_NOTATION}</div>
									# ENDIF #
								</div>
								# IF weblinks.C_CONTROLS #
									<span class="controls align-right">
										# IF weblinks.C_EDIT #<a href="{weblinks.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF #
										# IF weblinks.C_DELETE #<a href="{weblinks.U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a># ENDIF #
									</span>
								# ENDIF #
							</div>

							# IF NOT C_FULL_ITEM_DISPLAY #
								# IF weblinks.C_IS_ADORNED #
									<div class="cell-thumbnail">
										# IF weblinks.C_IS_PARTNER #
											# IF weblinks.C_HAS_PARTNER_THUMBNAIL #
												<img src="{weblinks.U_PARTNER_THUMBNAIL}" alt="{weblinks.TITLE}" itemprop="image" />
											# ELSE #
												<img src="{weblinks.U_THUMBNAIL}" alt="{weblinks.TITLE}" itemprop="image" />
											# ENDIF #
										# ELSE #
											# IF weblinks.C_HAS_THUMBNAIL #
												<img src="{weblinks.U_THUMBNAIL}" alt="{weblinks.TITLE}" itemprop="image" />
											# ENDIF #
										# ENDIF #
										<a class="cell-thumbnail-caption" href="{weblinks.U_ITEM}">
											${LangLoader::get_message('see.details', 'common')}
										</a>
									</div>
								# ENDIF #
							# ENDIF #
							<div class="cell-content">
								<div class="cell-infos">
									<span></span>
									# IF weblinks.C_VISIBLE #
									<span>
										<a href="{weblinks.U_VISIT}" class="button submit small">
											<i class="fa fa-globe" aria-hidden="true"></i> {@visit}
										</a>
										# IF IS_USER_CONNECTED #
										<a href="{weblinks.U_DEADLINK}" data-confirmation="${LangLoader::get_message('deadlink.confirmation', 'common')}" class="button bgc-full warning small" aria-label="${LangLoader::get_message('deadlink', 'common')}">
											<i class="fa fa-unlink" aria-hidden="true"></i>
										</a>
										# ENDIF #
									</span>
									# ELSE #
										# IF C_PENDING #
											<a href="{weblinks.U_VISIT}" class="button submit small">
												<i class="fa fa-globe" aria-hidden="true"></i> {@visit}
											</a>
										# ENDIF #
									# ENDIF #
								</div>
								# IF C_FULL_ITEM_DISPLAY #
									# IF weblinks.C_IS_ADORNED #
										<a class="item-thumbnail" href="{weblinks.U_ITEM}">
											# IF weblinks.C_IS_PARTNER #
												# IF weblinks.C_HAS_PARTNER_THUMBNAIL #
													<img src="{weblinks.U_PARTNER_THUMBNAIL}" alt="{weblinks.TITLE}" itemprop="image" />
												# ELSE #
													<img src="{weblinks.U_THUMBNAIL}" alt="{weblinks.TITLE}" itemprop="image" />
												# ENDIF #
											# ELSE #
												# IF weblinks.C_HAS_THUMBNAIL #
													<img src="{weblinks.U_THUMBNAIL}" alt="{weblinks.TITLE}" itemprop="image" />
												# ENDIF #
											# ENDIF #
										</a>
									# ENDIF #
									<div itemprop="text">{weblinks.CONTENTS}</div>
								# ELSE #
									{weblinks.SUMMARY}# IF weblinks.C_READ_MORE #... <a href="{weblinks.U_ITEM}" class="read-more">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #
								# ENDIF #
							</div>
						</div>

						<footer>
							<meta itemprop="url" content="{weblinks.U_ITEM}">
							<meta itemprop="description" content="${escape(weblinks.SHORT_CONTENTS)}"/>
							# IF C_ENABLED_COMMENTS #
								<meta itemprop="discussionUrl" content="{weblinks.U_COMMENTS}">
								<meta itemprop="interactionCount" content="{weblinks.COMMENTS_NUMBER} UserComments">
							# ENDIF #
						</footer>
					</article>
				# END weblinks #
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
