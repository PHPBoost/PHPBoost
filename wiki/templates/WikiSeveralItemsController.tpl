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
			# IF C_PENDING #
				{@wiki.pending.items}
			# ELSE #
                # IF C_TRACKED_ITEMS #
                    {@wiki.my.tracked}
                # ENDIF #
				# IF C_MEMBER_ITEMS #
                    # IF C_MEMBERS_LIST #
                        {@contribution.members.list}
                    # ELSE #
                        # IF C_MY_ITEMS #{@wiki.my.items}# ELSE #{@wiki.member.items} {MEMBER_NAME}# ENDIF #
                    # ENDIF #
				# ELSE #
					# IF C_ROOT_CATEGORY #{MODULE_NAME}# ELSE ## IF C_TAG_ITEMS #<span class="smaller">{@common.keyword}: </span># ENDIF #{CATEGORY_NAME}# ENDIF #
				# ENDIF #
			# ENDIF #
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
                                <div class="cell-name"><a class="subcat-title offload" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a></div>
                                <span class="small pinned notice" role="contentinfo" aria-label="{sub_categories_list.ITEMS_NUMBER} # IF sub_categories_list.C_SEVERAL_ITEMS #${TextHelper::lcfirst(@items)}# ELSE #${TextHelper::lcfirst(@item)}# ENDIF #">
                                    {sub_categories_list.ITEMS_NUMBER}
                                </span>
                            </div>
                            # IF sub_categories_list.C_CATEGORY_THUMBNAIL #
                                <div class="cell-body">
                                    <div class="cell-thumbnail cell-landscape cell-center">
                                        <img itemprop="thumbnailUrl" src="{sub_categories_list.U_CATEGORY_THUMBNAIL}" alt="{sub_categories_list.CATEGORY_NAME}" />
                                        <a class="cell-thumbnail-caption offload" href="{sub_categories_list.U_CATEGORY}">
                                            {@category.see.category}
                                        </a>
                                    </div>
                                </div>
                            # ENDIF #
                            # IF sub_categories_list.C_DISPLAY_DESCRIPTION #
                                <div class="cell-body" itemprop="about">
                                    <div class="cell-content">{sub_categories_list.CATEGORY_DESCRIPTION}</div>
                                </div>
                            # ENDIF #
                        </div>
                    # END sub_categories_list #
                </div>
                # IF C_SUBCATEGORIES_PAGINATION #<div class="content align-center"># INCLUDE SUBCATEGORIES_PAGINATION #</div># ENDIF #
            </div>
        </div>
    # ENDIF #

    <div class="sub-section">
        <div class="content-container">
            # IF C_ITEMS #
                # IF C_TABLE_VIEW #
                    <div class="responsive-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{@common.title}</th>
                                    <th class="col-small" aria-label="{@common.creation.date}">
                                        <i class="far fa-fw fa-calendar-plus hidden-small-screens" aria-hidden="true"></i>
                                        <span class="hidden-large-screens">{@common.creation.date}</span>
                                    </th>
                                    # IF C_ENABLED_VIEWS_NUMBER #
                                        <th class="col-small" aria-label="{@common.views.number}">
                                            <i class="fa fa-fw fa-eye hidden-small-screens" aria-hidden="true"></i>
                                            <span class="hidden-large-screens">{@common.views.number}</span>
                                        </th>
                                    # ENDIF #
                                    # IF C_ENABLED_NOTATION #
                                        <th aria-label="{@common.note}">
                                            <i class="far fa-fw fa-star hidden-small-screens" aria-hidden="true"></i>
                                            <span class="hidden-large-screens">{@common.note}</span>
                                        </th>
                                    # ENDIF #
                                    # IF C_ENABLED_COMMENTS #
                                        <th aria-label="{@common.comments}">
                                            <i class="far fa-fw fa-comments hidden-small-screens" aria-hidden="true"></i>
                                            <span class="hidden-large-screens">{@common.comments}</span>
                                        </th>
                                    # ENDIF #
                                    # IF C_CONTROLS #
                                        <th class="col-small" aria-label="{@common.moderation}">
                                            <i class="fa fa-fw fa-gavel hidden-small-screens" aria-hidden="true"></i>
                                            <span class="hidden-large-screens">{@common.moderation}</span>
                                        </th>
                                    # ENDIF #
                                </tr>
                            </thead>
                            <tbody>
                                # START items #
                                    <tr class="category-{items.CATEGORY_ID}">
                                        <td>
                                            <a href="{items.U_ITEM}" itemprop="name" class="offload# IF items.C_NEW_CONTENT # new-content# ENDIF #">{items.TITLE}</a>
                                        </td>
                                        <td>
                                            <time datetime="# IF NOT items.C_DIFFERED #{items.DATE_ISO8601}# ELSE #{items.DIFFERED_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished">
                                                # IF NOT items.C_DIFFERED #{items.DATE}# ELSE #{items.DIFFERED_START_DATE}# ENDIF #
                                            </time>
                                            # IF items.C_HAS_UPDATE_DATE #
                                                <time class="pinned notice small text-italic" aria-label="{@common.last.update}" datetime="{items.UPDATE_DATE_ISO8601}" itemprop="datePublished">{items.UPDATE_DATE}</time>
                                            # ENDIF #
                                        </td>
                                        # IF C_ENABLED_VIEWS_NUMBER #
                                            <td>
                                                {items.VIEWS_NUMBER}
                                            </td>
                                        # ENDIF #
                                        # IF C_ENABLED_NOTATION #
                                            <td>
                                                {items.STATIC_NOTATION}
                                            </td>
                                        # ENDIF #
                                        # IF C_ENABLED_COMMENTS #
                                            <td>
                                                {items.COMMENTS_NUMBER}
                                            </td>
                                        # ENDIF #
                                        # IF C_CONTROLS #
                                            <td class="controls">
                                                # IF items.C_EDIT #
                                                    <a class="offload" href="{items.U_EDIT}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
                                                # ENDIF #
                                                # IF items.C_DELETE #
                                                    <a href="{items.U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
                                                # ENDIF #
                                                # IF C_TRACKED_ITEMS #<a class="offload" href="{items.U_UNTRACK}" aria-label="{@wiki.untrack}"><i class="fa fa-heart-crack error" aria-hidden="true"></i></a># ENDIF #
                                            </td>
                                        # ENDIF #
                                    </tr>
                                # END items #
                            </tbody>
                        </table>
                    </div>
                # ELSE #
                    <div class="cell-flex # IF C_GRID_VIEW #cell-columns-{ITEMS_PER_ROW}# ELSE #cell-row# ENDIF #">
                        # START items #
                            <article id="wiki-item-{items.ID}" class="wiki-item category-{items.CATEGORY_ID} cell# IF items.C_NEW_CONTENT # new-content# ENDIF #" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
                                <header class="cell-header">
                                    <h2 class="cell-name"><a class="offload" href="{items.U_ITEM}" itemprop="name">{items.TITLE}</a></h2>
                                </header>
                                <div class="cell-infos">
                                    <div class="more">
                                        # IF items.C_HAS_UPDATE_DATE #
                                            <span class="pinned item-modified-date" aria-label="{@common.last.update}">
                                                <i class="far fa-calendar-plus" aria-hidden="true"></i>
                                                <time datetime="{items.UPDATE_DATE_ISO8601}" itemprop="dateModified">{items.UPDATE_DATE}</time>
                                            </span>
                                        # ELSE #
                                            <span class="pinned item-creation-date" aria-label="{@common.creation.date}">
                                                <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                                <time datetime="# IF items.C_DEFFERED_PUBLISHING #{items.DEFFERED_PUBLISHING_START_DATE_ISO8601}# ELSE #{items.DATE_ISO8601}# ENDIF #" itemprop="datePublished">
                                                    # IF items.C_DEFFERED_PUBLISHING #{items.DEFFERED_PUBLISHING_START_DATE}# ELSE #{items.DATE}# ENDIF #
                                                </time>
                                            </span>
                                        # ENDIF #
                                        # IF C_ENABLED_VIEWS_NUMBER #<span class="pinned item-views-number" role="contentinfo" aria-label="{items.VIEWS_NUMBER} {@common.views.number}"><i class="fa fa-eye" aria-hidden="true"></i> {items.VIEWS_NUMBER}</span># ENDIF #
                                        # IF C_ENABLED_COMMENTS #
                                            <span class="pinned item-comments">
                                                <i class="fa fa-comments" aria-hidden="true"></i>
                                                # IF items.C_COMMENTS # {items.COMMENTS_NUMBER} # ENDIF # {items.L_COMMENTS}
                                            </span>
                                        # ENDIF #
                                        # IF C_ENABLED_NOTATION #
                                            <div class="pinned item-notation">{items.STATIC_NOTATION}</div>
                                        # ENDIF #
                                    </div>
                                    # IF items.C_CONTROLS #
                                        <div class="controls align-right">
                                            # IF items.C_EDIT #<a class="offload item-edit" href="{items.U_EDIT}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF #
                                            # IF items.C_DELETE #<a class="item-delete" href="{items.U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a># ENDIF #
                                            # IF C_TRACKED_ITEMS #<a class="offload" href="{items.U_UNTRACK}" aria-label="{@wiki.untrack}"><i class="fa fa-heart-crack error" aria-hidden="true"></i></a># ENDIF #
                                        </div>
                                    # ENDIF #
                                </div>
                                # IF NOT C_FULL_ITEM_DISPLAY #
                                    # IF items.C_HAS_THUMBNAIL #
                                        <div class="cell-thumbnail cell-landscape cell-center">
                                            <img src="{items.U_THUMBNAIL}" alt="{items.TITLE}" itemprop="image" />
                                            <a href="{items.U_ITEM}" class="cell-thumbnail-caption offload">
                                                {@common.see.details}
                                            </a>
                                        </div>
                                    # ENDIF #
                                # ENDIF #
                                <div class="cell-body">
                                    <div class="cell-content">
                                        <div itemprop="text">
                                            # IF C_FULL_ITEM_DISPLAY #
                                                # IF items.C_HAS_THUMBNAIL #
                                                    <a href="{items.U_ITEM}" class="item-thumbnail offload">
                                                        <img src="{items.U_THUMBNAIL}" alt="{items.TITLE}" itemprop="image" />
                                                    </a>
                                                # ENDIF #
                                                {items.CONTENT}
                                            # ELSE #
                                                {items.SUMMARY}# IF items.C_READ_MORE # <a href="{items.U_ITEM}" class="read-more offload">[{@common.read.more}]</a># ENDIF #
                                            # ENDIF #
                                        </div>
                                    </div>
                                </div>

                                <footer>
                                    <meta itemprop="url" content="{items.U_ITEM}">
                                    <meta itemprop="description" content="${escape(items.SUMMARY)}"/>
                                    # IF C_ENABLED_COMMENTS #
                                        <meta itemprop="discussionUrl" content="{items.U_COMMENTS}">
                                        <meta itemprop="interactionCount" content="{items.COMMENTS_NUMBER} UserComments">
                                    # ENDIF #
                                </footer>
                            </article>
                        # END items #
                    </div>
                # ENDIF #
            # ELSE #
                # IF C_MEMBERS_LIST #
                    # IF C_MEMBERS #
                        <div class="content">
                            # START users #
                                <a href="{users.U_USER}" class="offload pinned bgc-sub align-center"><img class="message-user-avatar" src="{users.U_AVATAR}" alt="{users.USER_NAME}"><span class="d-block">{users.USER_NAME}<span></a>
                            # END users #
                        </div>
                    # ELSE #
                        <div class="content">
                            <div class="message-helper bgc notice align-center">{@contribution.no.member}</div>
                        </div>
                    # ENDIF #
                # ELSE #
                    # IF NOT C_HIDE_NO_ITEM_MESSAGE #
                        <div class="content">
                            <div class="message-helper bgc notice align-center">
                                {@common.no.item.now}
                            </div>
                        </div>
                    # ENDIF #
                # ENDIF #
            # ENDIF #
        </div>
    </div>
	<footer>
		# IF C_PAGINATION #<div class="sub-section"><div class="content-container"># INCLUDE PAGINATION #</div></div># ENDIF #
	</footer>
</section>
<script src="{PATH_TO_ROOT}/wiki/templates/js/wiki# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
