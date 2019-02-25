<section id="module-web">
	<header>
		<div class="cat-actions">
			<a href="{U_SYNDICATION}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication" aria-hidden="true" title="${LangLoader::get_message('syndication', 'common')}"></i></a>
			{@module_title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
			# IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit small" aria-hidden="true" title="${LangLoader::get_message('edit', 'common')}"></i></a># ENDIF #
		</div>
		<h1><span id="name" itemprop="name">{NAME}</span></h1>
	</header>
	# IF NOT C_VISIBLE #
		# INCLUDE NOT_VISIBLE_MESSAGE #
	# ENDIF #
	<article id="article-web-{ID}" itemscope="itemscope" itemtype="http://schema.org/CreativeWork" class="article-web# IF C_IS_PARTNER # content-friends# ENDIF ## IF C_IS_PRIVILEGED_PARTNER # content-privileged-friends# ENDIF ## IF C_NEW_CONTENT # new-content# ENDIF#">
		<span class="actions">
			# IF C_EDIT #<a href="{U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true" title="${LangLoader::get_message('edit', 'common')}"></i></a># ENDIF #
			# IF C_DELETE #<a href="{U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="fa fa-delete" aria-hidden="true" title="${LangLoader::get_message('delete', 'common')}"></i></a># ENDIF #
		</span>

		<meta itemprop="url" content="{U_LINK}">
		<meta itemprop="description" content="${escape(DESCRIPTION)}" />
		# IF C_COMMENTS_ENABLED #
		<meta itemprop="discussionUrl" content="{U_COMMENTS}">
		<meta itemprop="interactionCount" content="{NUMBER_COMMENTS} UserComments">
		# ENDIF #

		<div class="content">
			<div class="options infos">
				<div class="center">
					# IF C_IS_PARTNER #
						# IF C_HAS_PARTNER_PICTURE #
							<img src="{U_PARTNER_PICTURE}" alt="{NAME}" title="{NAME}" itemprop="image" />
						# ELSE #
							# IF C_PICTURE #
								<img src="{U_PICTURE}" alt="{NAME}" title="{NAME}" itemprop="image" />
							# ENDIF #
						# ENDIF #
						<div class="spacer"></div>
					# ELSE #
						# IF C_PICTURE #
							<img src="{U_PICTURE}" alt="{NAME}" title="{NAME}" itemprop="image" />
						# ENDIF #
					# ENDIF #
						<a href="{U_VISIT}" class="basic-button">
							<i class="fa fa-globe" aria-hidden="true"></i> {@visit}
						</a>
					# IF C_VISIBLE #
						# IF IS_USER_CONNECTED #
						<a href="{U_DEADLINK}" data-confirmation="${LangLoader::get_message('deadlink.confirmation', 'common')}" class="basic-button alt" aria-label="${LangLoader::get_message('deadlink', 'common')}">
							<i class="fa fa-unlink" aria-hidden="true" title="${LangLoader::get_message('deadlink', 'common')}"></i>
						</a>
						# ENDIF #
					# ENDIF #
				</div>
				<h6>{@link_infos}</h6>
				<span class="infos-options"><span class="text-strong">{@visits_number} : </span>{NUMBER_VIEWS}</span>
				<span class="infos-options"><span class="text-strong">${LangLoader::get_message('category', 'categories-common')} : </span><a itemprop="about" class="small" href="{U_CATEGORY}">{CATEGORY_NAME}</a></span>
				# IF C_KEYWORDS #
					<span class="infos-options">
						<span class="text-strong">${LangLoader::get_message('form.keywords', 'common')} : </span>
						# START keywords #
							<a itemprop="keywords" class="small" href="{keywords.URL}">{keywords.NAME}</a># IF keywords.C_SEPARATOR #, # ENDIF #
						# END keywords #
					</span>
				# ENDIF #
				# IF C_COMMENTS_ENABLED #
					<span class="infos-options"># IF C_COMMENTS # {NUMBER_COMMENTS} # ENDIF # {L_COMMENTS}</span>
				# ENDIF #
				# IF C_VISIBLE #
					# IF C_NOTATION_ENABLED #
						<div class="center">{NOTATION}</div>
					# ENDIF #
				# ENDIF #
			</div>
			<div itemprop="text">{CONTENTS}</div>
			<div class="spacer"></div>
			${ContentSharingActionsMenuService::display()}
		</div>
		<aside>
			# INCLUDE COMMENTS #
		</aside>
		<footer></footer>
	</article>
	<footer></footer>
</section>
