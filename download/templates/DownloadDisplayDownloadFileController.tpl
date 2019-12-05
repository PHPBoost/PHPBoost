<section id="module-download">
	<header>
		<div class="align-right">
			<a href="{U_SYNDICATION}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss" aria-hidden="true"></i></a>
			{@module_title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
			# IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit small" aria-hidden="true"></i></a># ENDIF #
		</div>
		<h1><span id="name" itemprop="name">{NAME}</span></h1>
	</header>
	<div itemscope="itemscope" itemtype="http://schema.org/CreativeWork" id="article-download-{ID}" class="article-download# IF C_NEW_CONTENT # new-content# ENDIF #">

		# IF NOT C_VISIBLE #
			# INCLUDE NOT_VISIBLE_MESSAGE #
		# ENDIF #
		<div class="controls">
			# IF C_EDIT #
			<a href="{U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a>
			# ENDIF #
			# IF C_DELETE #
			<a href="{U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
			# ENDIF #
		</div>

		<meta itemprop="url" content="{U_LINK}">
		<meta itemprop="description" content="${escape(DESCRIPTION)}" />
		# IF C_COMMENTS_ENABLED #
		<meta itemprop="discussionUrl" content="{U_COMMENTS}">
		<meta itemprop="interactionCount" content="{COMMENTS_NUMBER} UserComments">
		# ENDIF #

		<div class="content">
			<div class="options infos">
				<div class="align-center">
					# IF C_PICTURE #
						<img src="{U_PICTURE}" alt="{NAME}" itemprop="image" />
						<div class="spacer"></div>
					# ENDIF #
					# IF C_VISIBLE #
						# IF C_DISPLAY_DOWNLOAD_LINK #
							<a href="{U_DOWNLOAD}" class="button alt-button">
								<i class="fa fa-download" aria-hidden="true"></i> {@download}
							</a>

							# IF IS_USER_CONNECTED #
							<a href="{U_DEADLINK}" data-confirmation="${LangLoader::get_message('deadlink.confirmation', 'common')}" class="button alt-button" aria-label="${LangLoader::get_message('deadlink', 'common')}">
								<i class="fa fa-unlink" aria-hidden="true"></i>
							</a>
							# ENDIF #
						# ELSE #
							# INCLUDE UNAUTHORIZED_TO_DOWNLOAD_MESSAGE #
						# ENDIF #
					# ENDIF #
				</div>
				<h6>{@file_infos}</h6>
				# IF C_SOFTWARE_VERSION #<span class="text-strong">{@software_version} : </span><span>{SOFTWARE_VERSION}</span><br/># ENDIF #
				<span class="infos-options"><span class="text-strong">${LangLoader::get_message('size', 'common')} : </span># IF C_SIZE #{SIZE}# ELSE #${LangLoader::get_message('unknown_size', 'common')}# ENDIF #</span>
				<span class="infos-options"><span class="text-strong">${LangLoader::get_message('form.date.creation', 'common')} : </span><time datetime="# IF NOT C_DIFFERED #{DATE_ISO8601}# ELSE #{DIFFERED_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT C_DIFFERED #{DATE}# ELSE #{DIFFERED_START_DATE}# ENDIF #</time></span>
				# IF C_UPDATED_DATE #<span class="infos-options"><span class="text-strong">${LangLoader::get_message('form.date.update', 'common')} : </span><time datetime="{UPDATED_DATE_ISO8601}" itemprop="dateModified">{UPDATED_DATE}</time></span># ENDIF #
				<span class="infos-options"><span class="text-strong">{@downloads_number} : </span>{NUMBER_DOWNLOADS}</span>
				# IF C_NB_VIEW_ENABLED #<span class="infos-options"><span class="text-strong">{@download.number.view} : </span>{NUMBER_VIEW}</span># ENDIF #
				<span class="infos-options"><span class="text-strong">${LangLoader::get_message('category', 'categories-common')} : </span><a itemprop="about" href="{U_CATEGORY}">{CATEGORY_NAME}</a></span>
				# IF C_KEYWORDS #
					<span class="infos-options">
						<span class="text-strong">${LangLoader::get_message('form.keywords', 'common')} : </span>
						# START keywords #
							<a itemprop="keywords" href="{keywords.URL}">{keywords.NAME}</a># IF keywords.C_SEPARATOR #, # ENDIF #
						# END keywords #
					</span>
				# ENDIF #
				# IF C_AUTHOR_DISPLAYED #

					<span class="infos-options">
						<span class="text-strong">${LangLoader::get_message('author', 'common')} : </span>
						# IF C_AUTHOR_CUSTOM_NAME #
							{AUTHOR_CUSTOM_NAME}
						# ELSE #
							# IF C_AUTHOR_EXIST #<a itemprop="author" rel="author" class="{USER_LEVEL_CLASS}" href="{U_AUTHOR_PROFILE}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}" # ENDIF #>{PSEUDO}</a># ELSE #{PSEUDO}# ENDIF #
						# ENDIF #
					</span>
				# ENDIF #
				# IF C_COMMENTS_ENABLED #
					<span class="infos-options"># IF C_COMMENTS # {COMMENTS_NUMBER} # ENDIF # {L_COMMENTS}</span>
				# ENDIF #
				# IF C_VISIBLE #
					# IF C_NOTATION_ENABLED #
						<div class="align-center">{NOTATION}</div>
					# ENDIF #
				# ENDIF #
			</div>

			<div itemprop="text">{CONTENTS}</div>
			<div class="spacer"></div>
			${ContentSharingActionsMenuService::display()}

			# IF C_SOURCES #
				<div id="download-sources-container">
					<span class="download-sources-title"><i class="fa fa-map-signs" aria-hidden="true"></i> ${LangLoader::get_message('form.sources', 'common')}</span> :
					# START sources #
					<a itemprop="isBasedOnUrl" href="{sources.URL}" class="small download-sources-item" rel="nofollow">{sources.NAME}</a># IF sources.C_SEPARATOR #, # ENDIF #
					# END sources #
				</div>
			# ENDIF #
		</div>
		<aside>
			# INCLUDE COMMENTS #
		</aside>
		<footer></footer>
	</div>
	<footer></footer>
</section>
