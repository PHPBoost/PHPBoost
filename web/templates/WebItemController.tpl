<section id="module-web" class="category-{CATEGORY_ID} single-item">
	<header class="section-header">
		<div class="controls align-right">
			<a class="offload" href="{U_SYNDICATION}" aria-label="{@common.syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			{@web.module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
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
			<article id="web-item-{ID}" itemscope="itemscope" itemtype="https://schema.org/CreativeWork" class="web-item# IF C_IS_PARTNER # content-friends# ENDIF ## IF C_IS_PRIVILEGED_PARTNER # content-privileged-friends# ENDIF ## IF C_NEW_CONTENT # new-content# ENDIF#">
				# IF C_CONTROLS #
					<div class="controls align-right">
						# IF C_EDIT #<a class="offload" href="{U_EDIT}" aria-label="{@common.edit}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF #
						# IF C_DELETE #<a href="{U_DELETE}" data-confirmation="delete-element" aria-label="{@common.delete}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a># ENDIF #
					</div>
				# ENDIF #

				<div class="content cell-tile">
					<div class="cell cell-options">
						<div class="cell-header">
							<h6 class="cell-name">{@web.item.infos}</h6>
						</div>
						# IF C_IS_ADORNED #
							<div class="cell-body">
								<div class="cell-thumbnail">
									# IF C_IS_PARTNER #
										# IF C_HAS_PARTNER_THUMBNAIL #
											<img src="{U_PARTNER_THUMBNAIL}" alt="{TITLE}" itemprop="image" />
										# ELSE #
											# IF C_HAS_THUMBNAIL #
												<img src="{U_THUMBNAIL}" alt="{TITLE}" itemprop="image" />
											# ENDIF #
										# ENDIF #
									# ELSE #
										# IF C_HAS_THUMBNAIL #
											<img src="{U_THUMBNAIL}" alt="{TITLE}" itemprop="image" />
										# ENDIF #
									# ENDIF #
								</div>
							</div>
						# ENDIF #
						<div class="cell-list small">
							<ul>
								<li class="li-stretch">
									<a href="{U_VISIT}" class="button submit offload">
										<i class="fa fa-globe" aria-hidden="true"></i> {@common.visit}
									</a>
									# IF C_VISIBLE #
										# IF IS_USER_CONNECTED #
											<a href="{U_DEADLINK}" data-confirmation="{@contribution.dead.link.confirmation}" class="button bgc-full warning" aria-label="{@contribution.report.dead.link}">
												<i class="fa fa-unlink" aria-hidden="true"></i>
											</a>
										# ENDIF #
									# ENDIF #
								</li>
								<li class="li-stretch">
									<span class="text-strong">{@common.visits.number} : </span>
									<span>{VIEWS_NUMBER}</span>
								</li>
								<li class="li-stretch">
									<span class="text-strong">{@common.category} : </span>
									<span><a class="offload" itemprop="about" href="{U_CATEGORY}">{CATEGORY_NAME}</a></span>
								</li>
								# IF C_ENABLED_COMMENTS #
									<li>
										<span># IF C_COMMENTS # {COMMENTS_NUMBER} # ENDIF # {L_COMMENTS}</span>
									</li>
								# ENDIF #
								# IF C_VISIBLE #
									# IF C_ENABLED_NOTATION #
										<li class="align-center">
											{NOTATION}
										</li>
									# ENDIF #
								# ENDIF #
							</ul>
						</div>
					</div>
					<div itemprop="text">{CONTENT}</div>
				</div>
				# IF C_HAS_UPDATE_DATE #<span class="pinned notice small text-italic item-modified-date">{@common.last.update} <time datetime="{UPDATE_DATE_ISO8601}" itemprop="dateModified">{UPDATE_DATE_FULL}</time></span># ENDIF #
				<aside>
					${ContentSharingActionsMenuService::display()}
				</aside>

				# IF C_KEYWORDS #
					<aside class="tags-container">
						<span class="text-strong"><i class="fa fa-tags" aria-hidden="true"></i> {@common.keywords} : </span>
						# START keywords #
							<a class="pinned link-color offload" href="{keywords.URL}" itemprop="keywords">{keywords.NAME}</a>
							# IF keywords.C_SEPARATOR ## ENDIF #
						# END keywords #
					</aside>
				# ENDIF #
				# IF C_ENABLED_COMMENTS #
					<aside>
						# INCLUDE COMMENTS #
					</aside>
				# ENDIF #
				<footer>
					<meta itemprop="url" content="{U_ITEM}">
					<meta itemprop="description" content="${escape(SHORT_CONTENT)}" />
					# IF C_ENABLED_COMMENTS #
						<meta itemprop="discussionUrl" content="{U_COMMENTS}">
						<meta itemprop="interactionCount" content="{COMMENTS_NUMBER} UserComments">
					# ENDIF #
				</footer>
			</article>
		</div>
	</div>
	<footer></footer>
</section>
