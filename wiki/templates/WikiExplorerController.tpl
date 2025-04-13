<section id="module-wiki" class="several-items">
	<header class="section-header">
		<div class="controls align-right">
			<a class="offload" href="${relative_url(SyndicationUrlBuilder::rss('wiki', ID_CAT))}" aria-label="{@common.syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			# IF NOT C_ROOT_CATEGORY #{MODULE_NAME}# ENDIF #
			# IF C_DISPLAY_REORDER_LINK #
				<a class="offload" href="{U_REORDER_ITEMS}" aria-label="{@items.reorder}"><i class="fa fa-fw fa-exchange-alt" aria-hidden="true"></i></a>
			# ENDIF #
			# IF C_CATEGORY ## IF IS_ADMIN #<a class="offload" href="{U_EDIT_CATEGORY}" aria-label="{@common.edit}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF ## ENDIF #
		</div>
		<h1>
			{MODULE_NAME}
		</h1>
	</header>
    # IF C_HOMEPAGE #
        # IF C_ROOT_CATEGORY_DESCRIPTION #
            <div class="sub-section">
                <div class="content-container">{ROOT_CATEGORY_DESCRIPTION}</div>
            </div>
        # ENDIF #
    # ENDIF #
    <div class="sub-section">
        <div class="content-container">
            # IF C_ROOT_ITEMS #
                <ul class="root-items item-list">
                    # IF C_ROOT_CONTROLS #
                        # IF C_SEVERAL_ROOT_ITEMS #
                            <a class="offload reorder-items" href="{U_REORDER_ROOT_ITEMS}" aria-label="{@items.reorder}"><i class="fa fa-fw fa-exchange-alt"></i></a>
                        # ENDIF #
                    # ENDIF #
                    # START root_items #
                        <li class="flex-between">
                            <a class="d-block list-item offload" href="{root_items.U_ITEM}"><i class="fa fa-fw fa-file-alt"></i> {root_items.TITLE}</a>
                        </li>
                    # END root_items #
                </ul>
            # ENDIF #
            <nav id="category-nav" class="nav-items-list">
                <ul class="item-list">
                    # START categories #
                        <li
                                data_id="{categories.CATEGORY_ID}"
                                data_p_id="{categories.CATEGORY_PARENT_ID}"
                                data_order_id="{categories.CATEGORY_SUB_ORDER}">
                            <div class="flex-between toggle-menu-button-{categories.CATEGORY_ID}">
                                <div class="list-item flex-between">
                                    <span>
                                        <i class="far fa-fw fa-folder" aria-hidden="true"></i> 
                                        {categories.CATEGORY_NAME} 
                                    </span>
                                    <span class="small">({categories.ITEMS_NUMBER})</span>
                                </div>
                                <a class="offload" href="{categories.U_CATEGORY}" aria-label="{categories.CATEGORY_NAME}"><i class="fa fa-fw fa-caret-right" aria-hidden="true"></i></a>
                            </div>
                            # IF categories.C_ITEMS #
                                <ul class="items-list-{categories.CATEGORY_ID}">
                                    # IF categories.C_CONTROLS #
                                        # IF categories.C_SEVERAL_ITEMS #
                                            <a class="offload reorder-items" href="{categories.U_REORDER_ITEMS}" aria-label="{@items.reorder}"><i class="fa fa-fw fa-exchange-alt"></i></a>
                                        # ENDIF #
                                    # ENDIF #
                                    # START categories.items #
                                        <li class="flex-between">
                                            <a class="d-block list-item offload" href="{categories.items.U_ITEM}"><i class="fa fa-fw fa-file-alt"></i> {categories.items.TITLE}</a>
                                        </li>
                                    # END categories.items #
                                </ul>
                            # ENDIF #
                        </li>
                    # END categories #
                </ul>
            </nav>
        </div>
    </div>
	<footer>
		# IF C_PAGINATION #<div class="sub-section"><div class="content-container"># INCLUDE PAGINATION #</div></div># ENDIF #
	</footer>
</section>
<script src="{PATH_TO_ROOT}/wiki/templates/js/wiki# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
