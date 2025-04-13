<section id="module-wiki" class="category-{CATEGORY_ID} single-item">
	<header class="section-header">
		<div class="controls align-right">
			<a class="offload" href="{U_SYNDICATION}" aria-label="{@common.syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			{MODULE_NAME}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
			# IF IS_ADMIN #<a class="offload" href="{U_EDIT_CATEGORY}" aria-label="{@common.edit}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF #
		</div>
		<h1><span id="name" itemprop="name">{TITLE}</span></h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			# IF NOT C_VISIBLE #
				<div class="content">
					# INCLUDE NOT_VISIBLE_MESSAGE #
				</div>
			# ENDIF #
			# IF C_ARCHIVE #
				<div class="content">
					# INCLUDE ARCHIVED_CONTENT #
				</div>
			# ENDIF #
			# INCLUDE LEVEL_MESSAGE #
			<article itemscope="itemscope" itemtype="https://schema.org/CreativeWork" id="wiki-item-{ID}" class="wiki-item# IF C_NEW_CONTENT # new-content# ENDIF #">
				<div class="flex-between">
					<div class="more">
						# IF C_AUTHOR_DISPLAYED #
							<span class="pinned" aria-label="{@common.author}">
								<i class="fa fa-user" aria-hidden="true"></i>
								# IF C_AUTHOR_CUSTOM_NAME #
									<span class="custom-author">{AUTHOR_CUSTOM_NAME}</span>
								# ELSE #
									# IF C_AUTHOR_EXISTS #
										<a itemprop="author" rel="author" class="{AUTHOR_LEVEL_CLASS} offload" href="{U_AUTHOR_PROFILE}" # IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}" # ENDIF #>{AUTHOR_DISPLAY_NAME}</a>
									# ELSE #
										{AUTHOR_DISPLAY_NAME}
									# ENDIF #
								# ENDIF #
							</span>
						# ENDIF #
						<div class="pinned">
							<i class="fa fa-calendar-alt" aria-hidden="true"></i>
							<time aria-label="{@common.creation.date}" datetime="# IF C_DIFFERED #{DIFFERED_START_DATE_ISO8601}# ELSE #{DATE_ISO8601}# ENDIF #" itemprop="datePublished"> # IF C_DIFFERED #{DIFFERED_START_DATE}# ELSE #{DATE}# ENDIF #</time>
						</div>
						<span class="pinned" aria-label="{@common.category}">
							<i class="fa fa-folder" aria-hidden="true"></i>
							<a class="offload" itemprop="about" href="{U_CATEGORY}">{CATEGORY_NAME}</a>
						</span>
						# IF C_ENABLED_VIEWS_NUMBER #<span class="pinned" aria-label="{@common.views.number}"><i class="fa fa-eye" aria-hidden="true"></i> {VIEWS_NUMBER}</span># ENDIF #
						# IF C_ENABLED_COMMENTS #<span class="pinned" aria-label="{@common.comments}"><i class="fa fa-comment" aria-hidden="true"></i> # IF C_COMMENTS # {COMMENTS_NUMBER} # ENDIF # {L_COMMENTS}</span># ENDIF #
						# IF C_VISIBLE #
							# IF C_ENABLED_NOTATION #
								<div class="pinned">{NOTATION}</div>
							# ENDIF #
						# ENDIF #
					</div>
                    <div class="controls align-right">
                        # IF IS_USER_CONNECTED #
                            # IF C_IS_TRACKED #
                                <a class="offload" href="{U_UNTRACK}" aria-label="{@wiki.untrack}"><i class="fa fa-heart-crack error" aria-hidden="true"></i></a>
                            # ELSE #
                                <a class="offload" href="{U_TRACK}" aria-label="{@wiki.track}"><i class="fa fa-heart error" aria-hidden="true"></i></a>
                            # ENDIF #
                        # ENDIF #
                        <a class="offload" href="{U_HISTORY}" aria-label="{@wiki.item.history}"><i class="fa fa-fw fa-clock-rotate-left" aria-hidden="true"></i></a>
                        # IF C_ARCHIVE #
                            # IF C_CONTROLS #
                                # IF C_RESTORE #<a class="offload" href="{U_RESTORE}" aria-label="{@wiki.restore.item}"><i class="fa fa-fw fa-undo" aria-hidden="true"></i></a># ENDIF #
                                # IF C_DELETE #<a href="{U_DELETE_CONTENT}" aria-label="{@wiki.delete.version}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a># ENDIF #
                            # ENDIF #
                        # ELSE #
                            # IF C_CONTROLS #
                                # IF C_DUPLICATE #<a class="offload" href="{U_DUPLICATE}" aria-label="{@common.duplicate}"><i class="far fa-fw fa-clone" aria-hidden="true"></i></a># ENDIF #
                                # IF C_EDIT #<a class="offload" href="{U_EDIT}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF #
                                # IF C_DELETE #<a href="{U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a># ENDIF #
                            # ENDIF #
                        # ENDIF #
                    </div>
				</div>
				# IF C_HAS_UPDATE_DATE #
                    <span class="pinned notice small text-italic item-modified-date">
                        {@common.last.update}: <time datetime="{UPDATE_DATE_ISO8601}" itemprop="dateModified">{UPDATE_DATE_FULL}</time>
                        ${TextHelper::lcfirst(@common.by)} <a itemprop="author" rel="author" class="{CONTRIBUTOR_LEVEL_CLASS} offload" href="{U_CONTRIBUTOR_PROFILE}" # IF C_CONTRIBUTOR_GROUP_COLOR # style="color:{CONTRIBUTOR_GROUP_COLOR}" # ENDIF #>{CONTRIBUTOR_DISPLAY_NAME}</a>
                    </span>
                # ENDIF #
				<div id="sheet-summary" class="cell-tile">
					<div class="cell-summary cell">
						<div class="cell-header">
							<h5 class="cell-name# IF C_STICKY_SUMMARY # summary-sticky# ENDIF #">{@wiki.contents.table}</h5>
						</div>
						<div class="cell-list# IF C_STICKY_SUMMARY # summary-sticky# ENDIF #">
							<ul id="summary-list"></ul>
						</div>
					</div>
				</div>

				<div class="content">
					# IF C_HAS_THUMBNAIL #
						<div class="item-thumbnail">
							<img src="{U_THUMBNAIL}" alt="{NAME}" itemprop="image" />
						</div>
					# ENDIF #
					<div itemprop="text">{CONTENT}</div>
				</div>

				<aside>${ContentSharingActionsMenuService::display()}</aside>

				# IF C_SOURCES #
					<aside class="sources-container">
						<span class="text-strong"><i class="fa fa-map-signs" aria-hidden="true"></i> {@common.sources}</span> :
						# START sources #
							<a itemprop="isBasedOnUrl" href="{sources.URL}" class="pinned link-color offload" rel="nofollow">{sources.NAME}</a># IF sources.C_SEPARATOR ## ENDIF #
						# END sources #
					</aside>
				# ENDIF #
				# IF C_KEYWORDS #
					<aside class="tags-container">
						<span class="text-strong"><i class="fa fa-tags" aria-hidden="true"></i> {@common.keywords} : </span>
						# START keywords #
							<a class="pinned link-color offload" href="{keywords.URL}">{keywords.NAME}</a># IF keywords.C_SEPARATOR #, # ENDIF #
						# END keywords #
					</aside>
                # ENDIF #
                # IF C_SUGGESTED_ITEMS #
                    <aside class="suggested-links">
                        <span><i class="fa fa-fw fa-lightbulb" aria-hidden="true"></i> {@common.suggestions} :</span>
                        <div class="cell-flex cell-row">
                            # START suggested #
                                <div class="flex-between flex-between-large cell">
                                    <div class="cell-body">
                                        <div class="cell-content">
                                            <a href="{suggested.U_ITEM}# IF suggested.C_COMPLETED # error# ENDIF #" class="suggested-item offload">
                                                <h6>{suggested.TITLE}</h6>
                                            </a>
                                            <span class="more">{suggested.DATE}</span>
                                        </div>
                                    </div>
                                    # IF suggested.C_HAS_THUMBNAIL #
                                        <div class="cell-thumbnail cell-landscape cell-center">
                                            <img src="{suggested.U_THUMBNAIL}" alt="{suggested.TITLE}" />
                                        </div>
                                    # ENDIF #
                                </div>
                            # END suggested #
                        </div>

                    </aside>
                # ENDIF #

                # IF C_RELATED_LINKS #
                    <aside>
                        <div class="flex-between flex-between-large">
                            # IF C_PREVIOUS_ITEM #
                                <a class="related-item previous-item offload# IF C_PREVIOUS_COMPLETED # error# ENDIF #" href="{U_PREVIOUS_ITEM}">
                                    <i class="fa fa-chevron-left"></i>
                                    # IF C_PREVIOUS_HAS_THUMBNAIL #<img src="{U_PREVIOUS_THUMBNAIL}" alt="{PREVIOUS_ITEM}"># ENDIF #
                                    {PREVIOUS_ITEM}
                                </a>
                            # ELSE #
                                <span></span>
                            # ENDIF #
                            # IF C_NEXT_ITEM #
                                <a class="related-item next-item offload# IF C_NEXT_COMPLETED # error# ENDIF #" href="{U_NEXT_ITEM}">
                                    {NEXT_ITEM}
                                    # IF C_NEXT_HAS_THUMBNAIL #<img src="{U_NEXT_THUMBNAIL}" alt="{NEXT_ITEM}"># ENDIF #
                                    <i class="fa fa-chevron-right"></i>
                                </a>
                            # ENDIF #
                        </div>
                    </aside>
                # ENDIF #

				# IF C_ENABLED_COMMENTS #
					<aside>
						# INCLUDE COMMENTS #
					</aside>
				# ENDIF #
			</article>
		</div>
	</div>
	<footer>
		<meta itemprop="url" content="{U_ITEM}">
		<meta itemprop="description" content="${escape(SUMMARY)}" />
		# IF C_ENABLED_COMMENTS #
			<meta itemprop="discussionUrl" content="{U_COMMENTS}">
			<meta itemprop="interactionCount" content="{COMMENTS_NUMBER} UserComments">
		# ENDIF #
	</footer>
</section>
<div class="show-anchor">
    <div class="message-helper bgc success"></div>
</div>
<script src="{PATH_TO_ROOT}/wiki/templates/js/wiki# IF C_CSS_CACHE_ENABLED #.min# ENDIF #.js"></script>
<script>
    # IF C_STICKY_SUMMARY #
        setSummarySticky();
    # ELSE #
        if (window.matchMedia("(min-width: 769px)").matches) {
            sendSummaryMenu();
        }
    # ENDIF #
</script>

