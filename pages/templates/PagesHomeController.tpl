<section id="module-pages" class="several-items">
	<header class="section-header">
		<div class="controls align-right">
			<a class="offload" href="${relative_url(SyndicationUrlBuilder::rss('pages', CATEGORY_ID))}" aria-label="{@common.syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			# IF C_CATEGORY ## IF IS_ADMIN #<a class="offload" href="{U_EDIT_CATEGORY}" aria-label="{@common.edit}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF ## ENDIF #
		</div>
		<h1 class="flex-between">
			{MODULE_NAME}
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

	# IF C_NO_ITEM #
		<div class="sub-section">
			<div class="content-container">
				<div class="content">
					<div class="message-helper bgc notice">
						{@common.no.item.now}
					</div>
				</div>
			</div>
		</div>
	# ELSE #
		<div class="sub-section">
			<div class="content-container">
				<article class="pages-item">
					<div class="content">
						<ul class="root-list # IF C_CONTROLS ## IF C_ROOT_SEVERAL_ITEMS # root-ul# ENDIF ## ENDIF #">
							# IF C_CONTROLS #
								# IF C_ROOT_SEVERAL_ITEMS #
									<a class="offload" class="reorder-items" href="{U_ROOT_REORDER_ITEMS}" aria-label="{@items.reorder}"><i class="fa fa-fw fa-exchange-alt"></i></a>
								# ENDIF #
							# ENDIF #
							# START root_items #
								<li class="flex-between">
									<a class="offload" class="categories-item d-block" href="{root_items.U_ITEM}"><i class="fa fa-fw fa-file-alt"></i> {root_items.TITLE}</a>
									# IF root_items.C_CONTROLS #
										<div class="controls">
											# IF root_items.C_EDIT #<a class="offload" href="{root_items.U_EDIT}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF #
											# IF root_items.C_DELETE #<a href="{root_items.U_DELETE}" data-confirmation="delete-element" aria-label="{@common.delete}" id="delete-{root_items.ID}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a># ENDIF #
										</div>
									# ENDIF #
								</li>
							# END root_items #
						</ul>

						<nav id="category-nav" class="pages-list">
							<ul>
								# START categories #
									<li
											data_id="{categories.CATEGORY_ID}"
											data_p_id="{categories.CATEGORY_PARENT_ID}"
											data_order_id="{categories.CATEGORY_SUB_ORDER}">
										<div class="flex-between toggle-menu-button-{categories.CATEGORY_ID}">
											<div class="categories-item flex-between">
												<span><i class="far fa-fw fa-folder" aria-hidden="true"></i> {categories.CATEGORY_NAME}</span>
												<span class="small" aria-label="{@items.number}">({categories.ITEMS_NUMBER})</span>
											</div>
											<a class="offload" href="{categories.U_CATEGORY}" aria-label="{categories.CATEGORY_NAME}"><i class="fa fa-fw fa-caret-right" aria-hidden="true"></i></a>
										</div>
										# IF categories.C_ITEMS #
											<ul class="items-list-{categories.CATEGORY_ID}">
												# IF C_CONTROLS #
													# IF categories.C_SEVERAL_ITEMS #
														<a class="offload" class="reorder-items" href="{categories.U_REORDER_ITEMS}" aria-label="{@items.reorder}"><i class="fa fa-fw fa-exchange-alt"></i></a>
													# ENDIF #
												# ENDIF #
												# START categories.items #
													<li class="flex-between">
														<a class="d-block categories-item offload" href="{categories.items.U_ITEM}"><i class="fa fa-fw fa-file-alt"></i> {categories.items.TITLE}</a>
														# IF categories.items.C_CONTROLS #
															<div class="controls">
																# IF categories.items.C_EDIT #<a class="offload" href="{categories.items.U_EDIT}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF #
																# IF categories.items.C_DELETE #<a href="{categories.items.U_DELETE}" data-confirmation="delete-element" aria-label="{@common.delete}" id="delete-{categories.items.ID}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a># ENDIF #
															</div>
														# ENDIF #
													</li>
												# END categories.items #
											</ul>
										# ENDIF #
									</li>
								# END categories #
							</ul>
						</nav>
					</div>
				</article>

			</div>
		</div>
	# ENDIF #
	<footer></footer>
</section>
<script src="{PATH_TO_ROOT}/pages/templates/js/pages# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js" defer></script>
