<section id="module-{MODULE_NAME}">
	<header>
		<div class="align-right controls">
			<a href="${relative_url(SyndicationUrlBuilder::rss('{MODULE_NAME}', ID_CAT))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			# IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF ## ENDIF #
		</div>
		<h1>
			# IF C_PENDING #{MODULE_NAME} {@pending.items}# ELSE #{MODULE_NAME}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF #
		</h1>
	</header>
	# IF C_CATEGORY_DESCRIPTION #
		<div class="cat-description">
			{CATEGORY_DESCRIPTION}
		</div>
	# ENDIF #

	# IF C_SUB_CATEGORIES #
		<div class="cell-flex cell-tile cell-columns-{CATEGORIES_NUMBER_PER_ROW}">
			# START sub_categories_list #
				<div class="cell" itemscope>
					<div class="cell-header">
						<h5 class="cell-name" itemprop="about"><a href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a></h5>
						<span class="small pinned notice" aria-label="{sub_categories_list.ITEMS_NUMBER} # IF sub_categories_list.C_SEVERAL_ITEMS #${TextHelper::lcfirst(LangLoader::get_message('links', 'common', 'web'))}# ELSE #${TextHelper::lcfirst(LangLoader::get_message('link', 'common', 'web'))}# ENDIF #">
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
						# IF C_NOTATION_ENABLED #<th>${LangLoader::get_message('note', 'common')}</th># ENDIF #
						# IF C_COMMENTS_ENABLED #<th class="col-small">${LangLoader::get_message('comments', 'comments-common')}</th># ENDIF #
						# IF C_MODERATE #<th class="col-smaller"></th># ENDIF #
					</tr>
				</thead>
				<tbody>
					# START module_items #
						<tr>
							<td>
								<a href="{module_items.U_ITEM}" itemprop="name"# IF module_items.C_NEW_CONTENT # class="new-content"# ENDIF#>{module_items.TITLE}</a>
							</td>
							<td>
								{module_items.VIEWS_NUMBER}
							</td>
							# IF C_NOTATION_ENABLED #
								<td>
									{module_items.STATIC_NOTATION}
								</td>
							# ENDIF #
							# IF C_COMMENTS_ENABLED #
								<td>
									# IF module_items.C_COMMENTS # {module_items.COMENTS_NUMBER} # ENDIF # {module_items.L_COMMENTS}
								</td>
							# ENDIF #
							# IF module_items.C_CONTROLS #
								<td>
									# IF module_items.C_EDIT #
									<a href="{module_items.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
									# ENDIF #
									# IF module_items.C_DELETE #
									<a href="{module_items.U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
									# ENDIF #
								</td>
							# ENDIF #
						</tr>
					# END module_items #
				</tbody>
			</table>
		# ELSE #
			<div class="# IF C_GRID_VIEW #cell-flex cell-columns-{ITEMS_NUMBER_PER_ROW}# ELSE #cell-row# ENDIF #">
				# START module_items #
					<article id="{MODUL_NAME}-item-{module_items.ID}" class="{MODUL_NAME}-item several-items cell# IF module_items.C_IS_PRIVILEGED_PARTNER # content-privileged-friends# ENDIF ## IF module_items.C_NEW_CONTENT # new-content# ENDIF#" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
						<header class="cell-header">
							<h2 class="cell-name"><a href="{module_items.U_ITEM}" itemprop="name">{module_items.TITLE}</a></h2>
						</header>
						<div class="cell-infos">
							<div class="more">
								<span class="pinned"><i class="fa fa-eye" aria-hidden="true"></i> {module_items.VIEWS_NUMBER}</span>
								# IF C_COMMENTS_ENABLED #
									<span class="pinned">
										<i class="fa fa-comments" aria-hidden="true"></i>
										# IF module_items.C_COMMENTS # {module_items.COMMENTS_NUMBER} # ENDIF # {module_items.L_COMMENTS}
									</span>
								# ENDIF #
								# IF C_NOTATION_ENABLED #
									<span class="pinned">{module_items.STATIC_NOTATION}</span>
								# ENDIF #
							</div>
							# IF module_items.C_CONTROLS #
								<span class="controls align-right">
									# IF module_items.C_EDIT #<a href="{module_items.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF #
									# IF module_items.C_DELETE #<a href="{module_items.U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a># ENDIF #
								</span>
							# ENDIF #
						</div>

						<div class="cell-body">
							# IF module_items.C_HAS_THUMBNAIL #
								<div class="cell-thumbnail">
									<img src="{module_items.U_THUMBNAIL}" alt="{module_items.TITLE}" itemprop="image" />
									<a class="cell-thumbnail-caption" href="{module_items.U_ITEM}">
										${LangLoader::get_message('see.details', 'common')}
									</a>
								</div>
							# ENDIF #
							<div class="cell-content">
								# IF module_items.C_VISIT #
									<div class="cell-infos">
										<span></span>
										<span>
											<a href="{module_items.U_VISIT}" class="button submit small">
												<i class="fa fa-globe" aria-hidden="true"></i> {@visit}
											</a>
											# IF IS_USER_CONNECTED #
												<a href="{module_items.U_DEADLINK}" data-confirmation="${LangLoader::get_message('deadlink.confirmation', 'common')}" class="button bgc-full warning small" aria-label="${LangLoader::get_message('deadlink', 'common')}">
													<i class="fa fa-unlink" aria-hidden="true"></i>
												</a>
											# ENDIF #
										</span>
									</div>
								# ENDIF #
								# IF module_items.C_DOWNLOAD #
									<div class="cell-infos">
										<span></span>
										<span>
											<a href="{module_items.U_DOWNLOAD}" class="button submit small">
												<i class="fa fa-dowload" aria-hidden="true"></i> {@visit}
											</a>
											# IF IS_USER_CONNECTED #
												<a href="{module_items.U_DEADLINK}" data-confirmation="${LangLoader::get_message('deadlink.confirmation', 'common')}" class="button bgc-full warning small" aria-label="${LangLoader::get_message('deadlink', 'common')}">
													<i class="fa fa-unlink" aria-hidden="true"></i>
												</a>
											# ENDIF #
										</span>
									</div>
								# ENDIF #
								<div itemprop="text">
									# IF C_FULL_ITEM_DISPLAY #
										{module_items.CONTENTS}
									# ELSE #
										{module_items.DESCRIPTION}
										# IF module_items.C_READ_MORE #... <a href="{module_items.U_ITEM}" class="read-more">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #
									# ENDIF #
								</div>
							</div>
						</div>

						<footer>
							<meta itemprop="url" content="{module_items.U_ITEM}">
							<meta itemprop="description" content="${escape(module_items.DESCRIPTION)}"/>
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
