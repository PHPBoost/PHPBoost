# IF C_CATEGORIES #
	<section id="module-media">
		<header>
			<div class="align-right controls">
				<a href="${relative_url(SyndicationUrlBuilder::rss('media', ID_CAT))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
				# IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a># ENDIF #
			</div>
			<h1>
				${LangLoader::get_message('module_title', 'common', 'media')}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
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
					<div class="cell category-{sub_categories_list.CATEGORY_ID}">
						<div class="cell-header">
							<h5 class="cell-name" itemprop="about">
								<a href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a>
							</h5>
							<span class="small pinned notice" role="contentinfo" aria-label="{sub_categories_list.ITEMS_NUMBER} {sub_categories_list.ITEMS_TEXT}">{sub_categories_list.ITEMS_NUMBER}</span>
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

		# IF C_FILES #
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

			<div class="# IF C_GRID_VIEW #cell-flex cell-columns-{ITEMS_NUMBER_PER_ROW}# ELSE #cell-row# ENDIF #">
				# START file #
					<article id="article-media-{file.ID}" class="media-item several-items category-{file.CATEGORY_ID} cell# IF file.C_NEW_CONTENT # new-content# ENDIF #">
						<header class="cell-header">
							<h2 class="cell-name">
								<a href="{file.U_MEDIA_LINK}">{file.NAME}</a>
							</h2>
						</header>
						<div class="cell-body">
							<div class="cell-infos">
								<div class="more">
									<span class="pinned"><i class="fa fa-user" aria-hidden="true"></i> {file.AUTHOR}</span>
									<span class="pinned" role="contentinfo" aria-label="{file.COUNT} ${LangLoader::get_message('sort_by.views.number', 'common')}"><i class="fa fa-eye" aria-hidden="true"></i> {file.COUNT}</span>
									# IF C_DISPLAY_COMMENTS #
											<span class="pinned"><i class="fa fa-comments" aria-hidden="true"></i> {file.U_COM_LINK}</span>
									# ENDIF #
									# IF C_DISPLAY_NOTATION #
										<span class="pinned">{L_NOTE} {file.NOTE}</span>
									# ENDIF #
								</div>
								# IF C_CONTROLS #
									<div class="controls align-right">
										<a href="{file.U_ADMIN_UNVISIBLE_MEDIA}" aria-label="{L_UNAPROBED}"><i class="fa fa-fw fa-eye-slash"></i></a>
										<a href="{file.U_ADMIN_EDIT_MEDIA}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit"></i></a>
										<a href="{file.U_ADMIN_DELETE_MEDIA}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="far fa-fw fa-trash-alt"></i></a>
									</div>
								# ENDIF #
							</div>
							# IF file.C_HAS_PICTURE #
								<div class="cell-thumbnail">
									<img itemprop="thumbnailUrl" src="{file.PICTURE}" alt="{file.NAME}" />
									<a class="cell-thumbnail-caption" href="{file.U_MEDIA_LINK}" aria-label="${LangLoader::get_message('see.details', 'common')}"><i class="fa fa-2x fa-play-circle"></i></a>
								</div>
							# ENDIF #
							# IF file.C_DESCRIPTION #
								<div itemprop="text" class="cell-content">
									{file.DESCRIPTION}
								</div>
							# ENDIF #
						</div>
					</article>
				# END file #
			</div>
		# ENDIF #

		# IF C_DISPLAY_NO_FILE_MSG #
		<div class="content">
			<div class="message-helper bgc notice">${LangLoader::get_message('no_item_now', 'common')}</div>
		</div>
		# ENDIF #

		<footer># IF C_PAGINATION #<span class="align-center"># INCLUDE PAGINATION #</span># ENDIF #</footer>
	</section>
# ENDIF #

# IF C_DISPLAY_MEDIA #
	<section id="module-media" class="category-{CATEGORY_ID}">
		<header>
			<div class="controls align-right">
				${LangLoader::get_message('module_title', 'common', 'media')}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF #
			</div>
			<h1>
				{NAME}
			</h1>
		</header>
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
				{CONTENTS}
				<div class="media-content">
					# INCLUDE media_format #
				</div>
			</div>
			<aside>${ContentSharingActionsMenuService::display()}</aside>
			# IF C_DISPLAY_COMMENTS #
				<aside>{COMMENTS}</aside>
			# ENDIF #

		</article>
		<footer></footer>
	</section>
# ENDIF #
