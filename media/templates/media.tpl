# IF C_CATEGORIES #
	<section id="module-media">
		<header class="section-header">
			<div class="align-right controls">
				<a href="${relative_url(SyndicationUrlBuilder::rss('media', ID_CAT))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
				# IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a># ENDIF #
			</div>
			<h1>
				{@module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
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
				<div class="cell-flex cell-tile cell-columns-{CATEGORIES_NUMBER_PER_ROW}">
					# START sub_categories_list #
						<div class="cell category-{sub_categories_list.CATEGORY_ID}">
							<div class="cell-header">
								<h5 class="cell-name" itemprop="about">
									<a href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a>
								</h5>
								<span class="small pinned notice" role="contentinfo" aria-label="{sub_categories_list.ITEMS_NUMBER} {sub_categories_list.ITEMS_TEXT}">{sub_categories_list.ITEMS_NUMBER}</span>
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
		# ENDIF #

		# IF C_FILES #
			<div class="sub-section">
				<div class="options" id="form">
					<script>
						function change_order()
						{
							window.location = "{TARGET_ON_CHANGE_ORDER}sort=" + document.getElementById("sort").value + "&mode=" + document.getElementById("mode").value;
						}
					</script>
					<div class="horizontal-fieldset">
						<span class="horizontal-fieldset-desc horizontal-fieldset-element">{L_ORDER_BY}</span>
						<div class="horizontal-fieldset-element">
							<div class="form-element">
								<div class="form-field form-field-select">
									<select class="select-to-list " name="sort" id="sort" onchange="change_order()">
										<option data-option-icon="fa fa-sort-alpha-up" value="alpha"{SELECTED_ALPHA}>{L_ALPHA}</option>
										<option data-option-icon="far fa-calendar-alt" value="date"{SELECTED_DATE}>{L_DATE}</option>
										<option data-option-icon="fa fa-eye" value="nbr"{SELECTED_NBR}>{L_NBR}</option>
										# IF C_DISPLAY_NOTATION #<option data-option-icon="far fa-star" value="note"{SELECTED_NOTE}>{L_NOTE}</option># ENDIF #
										# IF C_DISPLAY_COMMENTS #<option data-option-icon="far fa-comments" value="com"{SELECTED_COM}>{L_COM}</option># ENDIF #
									</select>
								</div>
							</div>
						</div>
						<div class="horizontal-fieldset-element">
							<div class="form-element">
								<div class="form-field form-field-select">
									<select class="select-to-list " name="mode" id="mode" onchange="change_order()">
										<option data-option-icon="fa fa-arrow-up" value="asc"{SELECTED_ASC}>{L_ASC}</option>
										<option data-option-icon="fa fa-arrow-down" value="desc"{SELECTED_DESC}>{L_DESC}</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="spacer"></div>
			</div>
			<div class="sub-section">
				<div class="# IF C_GRID_VIEW #cell-flex cell-columns-{ITEMS_NUMBER_PER_ROW}# ELSE #cell-row# ENDIF #">
					# START items #
						<article id="article-media-{items.ID}" class="media-item several-items category-{items.CATEGORY_ID} cell# IF items.C_NEW_CONTENT # new-content# ENDIF #">
							<header class="cell-header">
								<h2 class="cell-name">
									<a href="{items.U_MEDIA_LINK}">{items.NAME}</a>
								</h2>
							</header>
							<div class="cell-body">
								<div class="cell-infos">
									<div class="more">
										<span class="pinned"><i class="fa fa-user" aria-hidden="true"></i> {items.AUTHOR}</span>
										<span class="pinned" aria-label="{@add.on.date}"><i class="fa fa-calendar-alt" aria-hidden="true"></i> <time datetime="{items.DATE_ISO8601}" itemprop="datePublished">{items.DATE}</time></span>
										<span class="pinned" role="contentinfo" aria-label="${LangLoader::get_message('sort_by.views.number', 'common')}: {items.COUNT} "><i class="fa fa-eye" aria-hidden="true"></i> {items.COUNT}</span>
										# IF C_DISPLAY_COMMENTS #
											<span class="pinned"><i class="fa fa-comments" aria-hidden="true"></i> {items.U_COM_LINK}</span>
										# ENDIF #
										# IF C_DISPLAY_NOTATION #
											<div class="pinned">{L_NOTE} {items.NOTE}</div>
										# ENDIF #
									</div>
									# IF C_CONTROLS #
										<div class="controls align-right">
											<a href="{items.U_ADMIN_UNVISIBLE_MEDIA}" aria-label="{L_UNAPROBED}"><i class="fa fa-fw fa-eye-slash"></i></a>
											<a href="{items.U_ADMIN_EDIT_MEDIA}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit"></i></a>
											<a href="{items.U_ADMIN_DELETE_MEDIA}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="far fa-fw fa-trash-alt"></i></a>
										</div>
									# ENDIF #
								</div>
								# IF items.C_HAS_PICTURE #
									<div class="cell-thumbnail cell-landscape cell-center">
										<img itemprop="thumbnailUrl" src="{items.PICTURE}" alt="{items.NAME}" />
										<a class="cell-thumbnail-caption" href="{items.U_MEDIA_LINK}" aria-label="${LangLoader::get_message('see.details', 'common')}"><i class="fa fa-2x fa-play-circle"></i></a>
									</div>
								# ENDIF #
								# IF items.C_DESCRIPTION #
									<div itemprop="text" class="cell-content">
										{items.DESCRIPTION}
									</div>
								# ENDIF #
							</div>
						</article>
					# END items #
				</div>
			</div>
		# ELSE #
			# IF C_DISPLAY_NO_FILE_MSG #
				<div class="sub-section">
					<div class="content">
						<div class="message-helper bgc notice align-center">${LangLoader::get_message('no_item_now', 'common')}</div>
					</div>
				</div>
			# ENDIF #
		# ENDIF #

		<footer># IF C_PAGINATION #<span class="sub-section align-center"># INCLUDE PAGINATION #</span># ENDIF #</footer>
	</section>
# ENDIF #

# IF C_DISPLAY_MEDIA #
	<section id="module-media" class="category-{CATEGORY_ID}">
		<header class="setion-header">
			<div class="controls align-right">
				{@module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF #
			</div>
			<h1>
				{NAME}
			</h1>
		</header>
		<div class="sub-section">
			<article id="media-item-{ID}" class="media-item single-item# IF C_NEW_CONTENT # new-content# ENDIF #">
				<div class="flex-between">
					<div class="more">
						<span class="pinned"><i class="fa fa-user"></i> {AUTHOR_NAME}</span>
						<span class="pinned"><i class="far fa-calendar"></i> {DATE}</span>
						<span class="pinned" aria-label="{HITS} {L_VIEWED}"><i class="fa fa-eye"></i> {HITS}</span>
						# IF C_DISPLAY_COMMENTS #
							<span class="pinned"><a href="{U_COM}"><i class="fa fa-comments"></i> {L_COM}</a></span>
						# ENDIF #
						<div class="spacer"></div>
						# IF C_DISPLAY_NOTATION #
							<div class="pinned">{KERNEL_NOTATION}</div>
						# ENDIF #
					</div>
					# IF C_CONTROLS #
						<div class="controls">
							<a href="{U_UNVISIBLE_MEDIA}" aria-label="{L_UNAPROBED}"><i class="fa fa-eye-slash"></i></a>
							<a href="{U_EDIT_MEDIA}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit"></i></a>
							<a href="{U_DELETE_MEDIA}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="far fa-trash-alt"></i></a>
						</div>
					# ENDIF #
				</div>
				<div class="content" itemprop="text">
					{CONTENT}
					# INCLUDE media_format #
				</div>
				<aside>${ContentSharingActionsMenuService::display()}</aside>
				# IF C_DISPLAY_COMMENTS #
					<aside>{COMMENTS}</aside>
				# ENDIF #
			</article>

		</div>
		<footer></footer>
	</section>
# ENDIF #
