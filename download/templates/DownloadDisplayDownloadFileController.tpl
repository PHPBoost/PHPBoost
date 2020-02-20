<section id="module-download" class="category-{CATEGORY_ID}">
	<header>
		<div class="controls align-right">
			<a href="{U_SYNDICATION}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			{@module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
			# IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF #
		</div>
		<h1><span id="name" itemprop="name">{TITLE}</span></h1>
	</header>
	# IF NOT C_VISIBLE #
		# INCLUDE NOT_VISIBLE_MESSAGE #
	# ENDIF #
	<article itemscope="itemscope" itemtype="http://schema.org/CreativeWork" id="download-item-{ID}" class="download-item single-item# IF C_NEW_CONTENT # new-content# ENDIF #">
		# IF C_CONTROLS #
			<div class="controls align-right">
				# IF C_EDIT #<a href="{U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a># ENDIF #
				# IF C_DELETE #<a href="{U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a># ENDIF #
			</div>
		# ENDIF #

		<div class="content cell-tile">
			<div class="cell cell-options">
				<div class="cell-header">
					<h6 class="cell-name">{@file.infos}</h6>
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
									<a href="{U_DOWNLOAD}" class="button submit">
										<i class="fa fa-download" aria-hidden="true"></i> {@download}
									</a>
									# IF IS_USER_CONNECTED #
										<a href="{U_DEADLINK}" data-confirmation="${LangLoader::get_message('deadlink.confirmation', 'common')}" class="button bgc-full warning" aria-label="${LangLoader::get_message('deadlink', 'common')}">
											<i class="fa fa-unlink" aria-hidden="true"></i>
										</a>
									# ENDIF #
								</li>
							# ELSE #
								<li># INCLUDE UNAUTHORIZED_TO_DOWNLOAD_MESSAGE #</li>
							# ENDIF #
						# ENDIF #
						# IF C_SOFTWARE_VERSION #<li class="li-stretch"><span class="text-strong">{@software.version} : </span><span>{SOFTWARE_VERSION}</span></li># ENDIF #
						<li class="li-stretch"><span class="text-strong">${LangLoader::get_message('size', 'common')} : </span><span># IF C_SIZE #{SIZE}# ELSE #${LangLoader::get_message('unknown_size', 'common')}# ENDIF #</span></li>
						<li class="li-stretch"><span class="text-strong">${LangLoader::get_message('form.date.creation', 'common')} : </span><time datetime="# IF NOT C_DIFFERED #{DATE_ISO8601}# ELSE #{DIFFERED_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT C_DIFFERED #{DATE}# ELSE #{DIFFERED_START_DATE}# ENDIF #</time></li>
						# IF C_UPDATED_DATE #<li class="li-stretch"><span class="text-strong">${LangLoader::get_message('form.date.update', 'common')} : </span><time datetime="{UPDATED_DATE_ISO8601}" itemprop="dateModified">{UPDATED_DATE}</time></li># ENDIF #
						<li class="li-stretch"><span class="text-strong">{@downloads.number} : </span><span>{DOWNLOADS_NUMBER}</span></li>
						# IF C_ENABLED_VIEWS_NUMBER #<li class="li-stretch"><span class="text-strong">{@download.views.number} : </span><span>{VIEWS_NUMBER}</span></li># ENDIF #
						<li class="li-stretch"><span class="text-strong">${LangLoader::get_message('category', 'categories-common')} : </span><a itemprop="about" href="{U_CATEGORY}">{CATEGORY_NAME}</a></li>
						# IF C_AUTHOR_DISPLAYED #
							<li class="li-stretch">
								<span class="text-strong">${LangLoader::get_message('author', 'common')} : </span>
								<span>
									# IF C_AUTHOR_CUSTOM_NAME #
										<span class="custom-author">{AUTHOR_CUSTOM_NAME}</span>
									# ELSE #
										# IF C_AUTHOR_EXIST #<a itemprop="author" rel="author" class="{USER_LEVEL_CLASS}" href="{U_AUTHOR_PROFILE}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}" # ENDIF #>{PSEUDO}</a># ELSE #<span class="visitor">{PSEUDO}</span># ENDIF #
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

			<div itemprop="text">{CONTENTS}</div>
		</div>

		<aside>${ContentSharingActionsMenuService::display()}</aside>

		# IF C_SOURCES #
			<aside class="sources-container">
				<span class="text-strong"><i class="fa fa-map-signs" aria-hidden="true"></i> ${LangLoader::get_message('form.sources', 'common')}</span> :
				# START sources #
					<a itemprop="isBasedOnUrl" href="{sources.URL}" class="pinned link-color" rel="nofollow">{sources.NAME}</a># IF sources.C_SEPARATOR ## ENDIF #
				# END sources #
			</aside>
		# ENDIF #
		# IF C_KEYWORDS #
			<aside class="tags-container">
				<span class="text-strong"><i class="fa fa-tags" aria-hidden="true"></i> ${LangLoader::get_message('form.keywords', 'common')} : </span>
				# START keywords #
					<a itemprop="pinned link-color" href="{keywords.URL}">{keywords.NAME}</a># IF keywords.C_SEPARATOR #, # ENDIF #
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
			<meta itemprop="description" content="${escape(SUMMARY)}" />
			# IF C_ENABLED_COMMENTS #
			<meta itemprop="discussionUrl" content="{U_COMMENTS}">
			<meta itemprop="interactionCount" content="{COMMENTS_NUMBER} UserComments">
			# ENDIF #
		</footer>
	</article>
	<footer></footer>
</section>
