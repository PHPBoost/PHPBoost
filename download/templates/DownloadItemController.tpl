<section id="module-download" class="category-{CATEGORY_ID} single-item">
	<header class="section-header">
		<div class="controls align-right">
			<a class="offload" href="{U_SYNDICATION}" aria-label="{@common.syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			{@download.module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
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
			<article itemscope="itemscope" itemtype="https://schema.org/CreativeWork" id="download-item-{ID}" class="download-item# IF C_NEW_CONTENT # new-content# ENDIF #">
				# IF C_CONTROLS #
					<div class="controls align-right">
						# IF C_EDIT #<a class="offload" href="{U_EDIT}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF #
						# IF C_DELETE #<a href="{U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a># ENDIF #
					</div>
				# ENDIF #

				<div class="content cell-tile">
					<div class="cell cell-options">
						<div class="cell-header">
							<h6 class="cell-name">{@download.item.infos}</h6>
						</div>
						# IF C_HAS_THUMBNAIL #
							<div class="cell-body">
								<div class="cell-thumbnail">
									<img src="{U_THUMBNAIL}" alt="{NAME}" itemprop="image" />
								</div>
							</div>
						# ENDIF #
						<div class="cell-list small">
							<ul>
								# IF C_VISIBLE #
									# IF C_DISPLAY_DOWNLOAD_LINK #
										<li class="li-stretch">
											<a href="{U_DOWNLOAD}" class="button submit offload" download>
												<i class="fa fa-download" aria-hidden="true"></i> {@download.download}
											</a>
											# IF IS_USER_CONNECTED #
												<a href="{U_DEADLINK}" data-confirmation="{@contribution.dead.link.confirmation}" class="button bgc-full warning" aria-label="{@contribution.report.dead.link}">
													<i class="fa fa-unlink" aria-hidden="true"></i>
												</a>
											# ENDIF #
										</li>
									# ELSE #
										<li># INCLUDE UNAUTHORIZED_TO_DOWNLOAD_MESSAGE #</li>
									# ENDIF #
								# ENDIF #
								# IF C_VERSION_NUMBER #<li class="li-stretch"><span class="text-strong">{@download.version} : </span><span>{VERSION_NUMBER}</span></li># ENDIF #
								<li class="li-stretch"><span class="text-strong">{@common.size} : </span><span># IF C_SIZE #{SIZE}# ELSE #{@common.unknown.size}# ENDIF #</span></li>
								<li class="li-stretch"><span class="text-strong">{@common.creation.date} : </span><time datetime="# IF NOT C_DIFFERED #{DATE_ISO8601}# ELSE #{DIFFERED_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT C_DIFFERED #{DATE}# ELSE #{DIFFERED_START_DATE}# ENDIF #</time></li>
								# IF C_HAS_UPDATE_DATE #<li class="li-stretch"><span class="text-strong">{@common.status.last.update} : </span><time datetime="{UPDATED_DATE_ISO8601}" itemprop="dateModified">{UPDATE_DATE}</time></li># ENDIF #
								<li class="li-stretch"><span class="text-strong">{@download.downloads.number} : </span><span>{DOWNLOADS_NUMBER}</span></li>
								# IF C_ENABLED_VIEWS_NUMBER #<li class="li-stretch"><span class="text-strong">{@common.views.number} : </span><span>{VIEWS_NUMBER}</span></li># ENDIF #
								<li class="li-stretch"><span class="text-strong">{@common.category} : </span><a class="offload" itemprop="about" href="{U_CATEGORY}">{CATEGORY_NAME}</a></li>
								# IF C_AUTHOR_DISPLAYED #
									<li class="li-stretch">
										<span class="text-strong">{@common.author} : </span>
										<span>
											# IF C_AUTHOR_CUSTOM_NAME #
												<span class="custom-author">{AUTHOR_CUSTOM_NAME}</span>
											# ELSE #
												# IF C_AUTHOR_EXISTS #<a itemprop="author" rel="author" class="{AUTHOR_LEVEL_CLASS} offload" href="{U_AUTHOR_PROFILE}" # IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}" # ENDIF #>{AUTHOR_DISPLAY_NAME}</a># ELSE #<span class="visitor">{AUTHOR_DISPLAY_NAME}</span># ENDIF #
											# ENDIF #
										</span>
									</li>
								# ENDIF #
								# IF C_ENABLED_COMMENTS #
									<li class="li-stretch"># IF C_COMMENTS # {COMMENTS_NUMBER} # ENDIF # {L_COMMENTS}</li>
								# ENDIF #
								# IF C_VISIBLE #
									# IF C_ENABLED_NOTATION #
										<li class="align-center">{NOTATION}</li>
									# ENDIF #
								# ENDIF #
							</ul>
						</div>
					</div>
					<div itemprop="text">{CONTENT}</div>
				</div>

				<aside class="sharing-container">${ContentSharingActionsMenuService::display()}</aside>

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
