<section id="module-media">
	<header class="section-header">
		<div class="controls align-right">
			<a href="${relative_url(SyndicationUrlBuilder::rss('media', CATEGORY_ID))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			# IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a># ENDIF #
		</div>
		<h1>
			{@module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
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

	# IF C_SUB_CATEGORIES #
		<div class="sub-section">
			<div class="content-container">
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
		</div>
	# ENDIF #

	<div class="sub-section">
		<div class="content-container">
			# IF C_NO_ITEM #
				<div class="content">
					<div class="message-helper bgc notice align-center">${LangLoader::get_message('no_item_now', 'common')}</div>
				</div>
			# ELSE #
				# IF C_SEVERAL_ITEMS #
					<div class="content">
						<div class="options" id="form">
							<script>
								function change_order()
								{
									window.location = "{CHANGE_ORDER}sort=" + document.getElementById("sort").value + "&mode=" + document.getElementById("mode").value;
								}
							</script>
							<div class="horizontal-fieldset">
								<span class="horizontal-fieldset-desc horizontal-fieldset-element">${LangLoader::get_message('sort_by', 'common')}</span>
								<div class="horizontal-fieldset-element">
									<div class="form-element">
										<div class="form-field form-field-select">
											<select class="select-to-list " name="sort" id="sort" onchange="change_order()">
												<option data-option-icon="fa fa-sort-alpha-up" value="alpha"{SELECTED_ALPHA}>${LangLoader::get_message('sort_by.alphabetic', 'common')}</option>
												<option data-option-icon="far fa-calendar-alt" value="date"{SELECTED_DATE}>${LangLoader::get_message('date', 'date-common')}</option>
												<option data-option-icon="fa fa-eye" value="views"{SELECTED_VIEWS}>${LangLoader::get_message('sort_by.views.number', 'common')}</option>
												# IF C_ENABLED_NOTATION #<option data-option-icon="far fa-star" value="note"{SELECTED_NOTE}>${LangLoader::get_message('sort_by.best.note', 'common')}</option># ENDIF #
												# IF C_ENABLED_COMMENTS #<option data-option-icon="far fa-comments" value="com"{SELECTED_COM}>${LangLoader::get_message('sort_by.comments.number', 'common')}</option># ENDIF #
											</select>
										</div>
									</div>
								</div>
								<div class="horizontal-fieldset-element">
									<div class="form-element">
										<div class="form-field form-field-select">
											<select class="select-to-list " name="mode" id="mode" onchange="change_order()">
												<option data-option-icon="fa fa-arrow-up" value="asc"{SELECTED_ASC}>${LangLoader::get_message('sort.asc', 'common')}</option>
												<option data-option-icon="fa fa-arrow-down" value="desc"{SELECTED_DESC}>${LangLoader::get_message('sort.desc', 'common')}</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				# ENDIF #
				<div class="# IF C_GRID_VIEW #cell-flex cell-columns-{ITEMS_NUMBER_PER_ROW}# ELSE #cell-row# ENDIF #">
					# START items #
						<article id="article-media-{items.ID}" class="media-item several-items category-{items.CATEGORY_ID} cell# IF items.C_NEW_CONTENT # new-content# ENDIF #">
							<header class="cell-header">
								<h2 class="cell-name">
									<a href="{items.U_MEDIA_LINK}">{items.TITLE}</a>
								</h2>
							</header>
							<div class="cell-body">
								<div class="cell-infos">
									<div class="more">
										<span class="pinned"><i class="fa fa-user" aria-hidden="true"></i> {items.PSEUDO}</span>
										<span class="pinned"><i class="fa fa-calendar-alt" aria-hidden="true"></i> <time datetime="{items.DATE_ISO8601}" itemprop="datePublished">{items.DATE}</time></span>
										<span class="pinned" role="contentinfo" aria-label="${LangLoader::get_message('sort_by.views.number', 'common')}"><i class="fa fa-eye" aria-hidden="true"></i> {items.VIEWS_NUMBER}</span>
										# IF C_ENABLED_COMMENTS #
											<span class="pinned"><i class="fa fa-comments" aria-hidden="true"></i> {items.COMMENTS_NUMBER}</span>
										# ENDIF #
										# IF C_ENABLED_NOTATION #
											<div class="pinned">{items.KERNEL_NOTATION}</div>
										# ENDIF #
									</div>
									# IF C_CONTROLS #
										<div class="controls align-right">
											<a href="{items.U_ADMIN_INVISIBLE_MEDIA}" aria-label="{@media.hide.file}"><i class="fa fa-fw fa-eye-slash"></i></a>
											<a href="{items.U_ADMIN_EDIT_MEDIA}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit"></i></a>
											<a href="{items.U_ADMIN_DELETE_MEDIA}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="far fa-fw fa-trash-alt"></i></a>
										</div>
									# ENDIF #
								</div>
								# IF items.C_HAS_PICTURE #
									<div class="cell-thumbnail cell-landscape cell-center">
										<img itemprop="thumbnailUrl" src="{items.PICTURE}" alt="{items.TITLE}" />
										<a class="cell-thumbnail-caption" href="{items.U_MEDIA_LINK}" aria-label="${LangLoader::get_message('see.details', 'common')}"><i class="fa fa-2x fa-play-circle"></i></a>
									</div>
								# ENDIF #
								# IF items.C_CONTENT #
									<div itemprop="text" class="cell-content">
										{items.CONTENT}
									</div>
								# ENDIF #
							</div>
						</article>
					# END items #
				</div>
			# ENDIF #
		</div>
	</div>
	<footer># IF C_PAGINATION #<div class="sub-section"><div class="content-container"># INCLUDE PAGINATION #</div></div># ENDIF #</footer>
</section>
