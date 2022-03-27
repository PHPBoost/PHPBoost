<section id="module-media" class="several-items">
	<header class="section-header">
		<div class="controls align-right">
			<a class="offload" href="${relative_url(SyndicationUrlBuilder::rss('media', CATEGORY_ID))}" aria-label="{@common.syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			# IF NOT C_ROOT_CATEGORY #{@media.module.title}# ENDIF #
			# IF IS_ADMIN #<a class="offload" href="{U_EDIT_CATEGORY}" aria-label="{@common.edit}"><i class="fa fa-edit" aria-hidden="true"></i></a># ENDIF #
		</div>
		<h1>
			# IF C_ROOT_CATEGORY #{@media.module.title}# ELSE #{CATEGORY_NAME}# ENDIF #
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
				<div class="cell-flex cell-tile cell-columns-{CATEGORIES_PER_ROW}">
					# START sub_categories_list #
						<div class="cell cell-category category-{sub_categories_list.CATEGORY_ID}">
							<div class="cell-header">
								<h5 class="cell-name" itemprop="about">
									<a class="offload" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a>
								</h5>
								<span class="small pinned notice" role="contentinfo" aria-label="{sub_categories_list.ITEMS_NUMBER} # IF sub_categories_list.C_SEVERAL_ITEMS #{@media.items}# ELSE #{@media.item}# ENDIF #">{sub_categories_list.ITEMS_NUMBER}</span>
							</div>
							# IF sub_categories_list.C_CATEGORY_THUMBNAIL #
								<div class="cell-body">
									<div class="cell-thumbnail cell-landscape cell-center">
										<img itemprop="thumbnailUrl" src="{sub_categories_list.U_CATEGORY_THUMBNAIL}" alt="{sub_categories_list.CATEGORY_NAME}" />
										<a class="cell-thumbnail-caption offload" itemprop="about" href="{sub_categories_list.U_CATEGORY}">
											{@common.see.category}
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

	# IF C_NO_ITEM #
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					<div class="message-helper bgc notice align-center">{@common.no.item.now}</div>
				</div>
			</div>
		</div>
	# ELSE #
		# IF C_ITEMS #
			<div class="sub-section">
				<div class="content-container">
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
									<span class="horizontal-fieldset-desc horizontal-fieldset-element">{@common.sort.by}</span>
									<div class="horizontal-fieldset-element">
										<div class="form-element">
											<div class="form-field form-field-select">
												<select class="select-to-list " name="sort" id="sort" onchange="change_order()">
													<option data-option-icon="fa fa-sort-alpha-up" value="alpha"{SELECTED_ALPHA}>{@common.sort.by.alphabetic}</option>
													<option data-option-icon="far fa-calendar-alt" value="date"{SELECTED_DATE}>{@common.sort.by.date}</option>
													<option data-option-icon="fa fa-eye" value="views"{SELECTED_VIEWS}>{@common.sort.by.views.number}</option>
													# IF C_ENABLED_NOTATION #<option data-option-icon="far fa-star" value="note"{SELECTED_NOTE}>{@common.sort.by.best.note}</option># ENDIF #
													# IF C_ENABLED_COMMENTS #<option data-option-icon="far fa-comments" value="com"{SELECTED_COM}>{@common.sort.by.comments.number}</option># ENDIF #
												</select>
											</div>
										</div>
									</div>
									<div class="horizontal-fieldset-element">
										<div class="form-element">
											<div class="form-field form-field-select">
												<select class="select-to-list " name="mode" id="mode" onchange="change_order()">
													<option data-option-icon="fa fa-arrow-up" value="asc"{SELECTED_ASC}>{@common.sort.asc}</option>
													<option data-option-icon="fa fa-arrow-down" value="desc"{SELECTED_DESC}>{@common.sort.desc}</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					# ENDIF #
					<div class="cell-flex # IF C_GRID_VIEW #cell-columns-{ITEMS_PER_ROW}# ELSE #cell-row# ENDIF #">
						# START items #
							<article id="article-media-{items.ID}" class="media-item category-{items.CATEGORY_ID} cell# IF items.C_NEW_CONTENT # new-content# ENDIF #">
								<header class="cell-header">
									<h2 class="cell-name">
										<a class="offload" href="{items.U_ITEM}">{items.TITLE}</a>
									</h2>
								</header>
								<div class="cell-infos">
									<div class="more">
										<span class="pinned item-author" aria-label="{@common.author}">
											# IF items.C_AUTHOR_DISPLAYED #
												<i class="far fa-user"></i>
												# IF items.C_AUTHOR_EXISTS #
													<a itemprop="author" class="{items.AUTHOR_LEVEL_CLASS} offload"# IF items.C_AUTHOR_GROUP_COLOR # style="color:{items.AUTHOR_GROUP_COLOR}"# ENDIF # href="{items.U_AUTHOR_PROFILE}">{items.AUTHOR_DISPLAY_NAME}</a>
												# ELSE #
													<span class="visitor">{@user.guest}</span>
												# ENDIF #
											# ENDIF #
										</span>
										<span class="pinned item-creation-date" aria-label="{@common.creation.date}"><i class="far fa-calendar-alt" aria-hidden="true"></i> <time datetime="{items.DATE_ISO8601}" itemprop="datePublished">{items.DATE}</time></span>
										<span class="pinned item-views-number" role="contentinfo" aria-label="{@common.views.number}"><i class="far fa-eye" aria-hidden="true"></i> {items.VIEWS_NUMBER}</span>
										# IF C_ENABLED_COMMENTS #
											<span class="pinned item-comments" aria-label="{@common.comments}"><i class="far fa-comments" aria-hidden="true"></i> {items.COMMENTS_NUMBER}</span>
										# ENDIF #
										# IF C_ENABLED_NOTATION #
											<div class="pinned item-category">{items.KERNEL_NOTATION}</div>
										# ENDIF #
									</div>
									# IF C_CONTROLS #
										<div class="controls align-right">
											<a class="offload item-status" href="{items.U_STATUS}" aria-label="{@media.hide.item}"><i class="fa fa-fw fa-eye-slash" aria-hidden="true"></i></a>
											<a class="offload item-edit" href="{items.U_EDIT}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
											<a class="item-delete" href="{items.U_DELETE}" data-confirmation="delete-element" aria-label="{@common.delete}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
										</div>
									# ENDIF #
								</div>
								# IF items.C_HAS_THUMBNAIL #
									<div class="cell-thumbnail cell-landscape cell-center">
										<img itemprop="thumbnailUrl" src="{items.U_THUMBNAIL}" alt="{items.TITLE}" />
										<a class="cell-thumbnail-caption offload" href="{items.U_ITEM}" aria-label="{@common.see.details}"><i class="fa fa-2x fa-play-circle" aria-hidden="true"></i></a>
									</div>
								# ENDIF #
								<div class="cell-body">
									# IF items.C_CONTENT #
										<div itemprop="text" class="cell-content">
											{items.SUMMARY}
										</div>
									# ENDIF #
								</div>
							</article>
						# END items #
					</div>
				</div>
			</div>
		# ENDIF #
	# ENDIF #
	<footer># IF C_PAGINATION #<div class="sub-section"><div class="content-container"># INCLUDE PAGINATION #</div></div># ENDIF #</footer>
</section>
