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
    # IF C_ROOT_CATEGORY_DESCRIPTION #
        <div class="sub-section">
            <div class="content-container">{ROOT_CATEGORY_DESCRIPTION}</div>
        </div>
    # ENDIF #

    # IF C_ROOT_ITEMS #
        <div class="root-items">
            # IF C_ROOT_CONTROLS #
                # IF C_SEVERAL_ROOT_ITEMS #
                    <a class="offload reorder-items" href="{U_REORDER_ROOT_ITEMS}" aria-label="{@items.reorder}"><i class="fa fa-fw fa-exchange-alt"></i></a>
                # ENDIF #
            # ENDIF #
            <div class="cell-flex cell-columns-{ITEMS_PER_ROW}">
                # START root_items #
                    <div class="cell">
                        <div class="cell-header">
                            <a class="cell-name offload" href="{root_items.U_ITEM}"><i class="fa fa-fw fa-file-alt"></i> {root_items.TITLE}</a>
                            <div class="controls">
                                <a href="{root_items.U_ITEM}" aria-label="<h5>{root_items.TITLE}</h5>{root_items.SUMMARY}"><i class="fa fa-eye"></i></a>
                                # IF root_items.C_CONTROLS #
                                    # IF root_items.C_EDIT #<a class="offload" href="{root_items.U_EDIT}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF #
                                    # IF root_items.C_DELETE #<a href="{root_items.U_DELETE}" data-confirmation="delete-element" aria-label="{@common.delete}" id="delete-{root_items.ID}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a># ENDIF #
                                # ENDIF #
                            </div>
                        </div>
                    </div>
                # END root_items #
            </div>
        </div>
    # ENDIF #
    <div class="sub-section">
        <div class="content-container">
            <div id="overview-nav" class="overview-list">
                # START categories #
                    <div
                            class="cell"
                            data-id="{categories.CATEGORY_ID}"
                            data-p-id="{categories.CATEGORY_PARENT_ID}"
                            data-order-id="{categories.CATEGORY_SUB_ORDER}">
                        <span>
                            <a class="offload" href="{categories.U_CATEGORY}">
                                {categories.CATEGORY_NAME}
                            </a>
                        </span>
                    </div>
                # END categories #
            </div>
        </div>
    </div>
	<footer></footer>
</section>
<script src="{PATH_TO_ROOT}/wiki/templates/js/wiki# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
