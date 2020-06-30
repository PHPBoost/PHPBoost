<section id="module-pages">
	<header>
		<div class="align-right controls">
			<a href="${relative_url(SyndicationUrlBuilder::rss('pages', ID_CAT))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			# IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF ## ENDIF #
		</div>
		<h1>
			{@module.title}
		</h1>
	</header>

	# IF C_CATEGORY_DESCRIPTION #
		<div class="cat-description">
			{CATEGORY_DESCRIPTION}
		</div>
	# ENDIF #
		<ul>
			# START root_items #
				<li class="flex-between">
					<a class="d-block" href="{root_items.U_ITEM}"><i class="fa fa-fw fa-file-alt"></i> {root_items.TITLE}</a>
					# IF root_items.C_CONTROLS #
						<div class="controls">
							# IF root_items.C_EDIT #<a href="{root_items.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF #
							# IF root_items.C_DELETE #<a href="{root_items.U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}" id="delete-{root_items.ID}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a># ENDIF #
						</div>
					# ENDIF #
				</li>
			# END root_items #
		</ul>

    <nav id="category-nav">
        <ul>
			# START categories #
            	<li
					data_id="{categories.CATEGORY_ID}"
					data_p_id="{categories.CATEGORY_PARENT_ID}"
					data_order_id="{categories.CATEGORY_SUB_ORDER}">
					<span class="d-block flex-between toggle-menu-button-{categories.CATEGORY_ID}">
						<span class="categories-item"><i class="far fa-fw fa-folder" aria-hidden="true"></i> {categories.CATEGORY_NAME}</span>
						<a href="{categories.U_CATEGORY}" aria-label="{categories.CATEGORY_NAME}"><i class="fa fa-fw fa-caret-right" aria-hidden="true"></i></a>
					</span>
					# IF categories.C_ITEMS #
						<ul class="items-list-{categories.CATEGORY_ID}">
							# IF categories.C_CONTROLS #
								# IF categories.C_SEVERAL_ITEMS #
									<a class="reorder-items" href="{categories.U_REORDER_ITEMS}" aria-label="${LangLoader::get_message('reorder', 'common')}"><i class="fa fa-fw fa-exchange-alt"></i></a>
								# ENDIF #
							# ENDIF #
							# START categories.items #
								<li class="flex-between">
									<a class="d-block categories-item" href="{categories.items.U_ITEM}"><i class="fa fa-fw fa-file-alt"></i> {categories.items.TITLE}</a>
									# IF categories.items.C_CONTROLS #
										<div class="controls">
											# IF categories.items.C_EDIT #<a href="{categories.items.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF #
											# IF categories.items.C_DELETE #<a href="{categories.items.U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}" id="delete-{categories.items.ID}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a># ENDIF #
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
	<footer>
	</footer>
</section>
<script src="{PATH_TO_ROOT}/pages/templates/js/pages.js" defer></script>
