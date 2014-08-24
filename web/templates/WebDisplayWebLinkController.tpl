<article itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
	<header>
		<h1>
			<span id="name" itemprop="name">{NAME}</span>
			<span class="actions">
				# IF C_EDIT #
					<a href="{U_EDIT}" title="${LangLoader::get_message('edit', 'common')}" class="fa fa-edit"></a>
				# ENDIF #
				# IF C_DELETE #
					<a href="{U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" class="fa fa-delete" data-confirmation="delete-element"></a>
				# ENDIF #
			</span>
		</h1>
		
		<meta itemprop="url" content="{U_LINK}">
		<meta itemprop="description" content="${escape(DESCRIPTION)}" />
		<meta itemprop="discussionUrl" content="{U_COMMENTS}">
		<meta itemprop="interactionCount" content="{NUMBER_COMMENTS} UserComments">
		
	</header>
	<div class="content">
		# IF NOT C_APPROVED #
			# INCLUDE NOT_APPROVED_MESSAGE #
		# ENDIF #
		<div class="options infos">
			<div class="center">
				# IF C_HAS_PARTNER_PICTURE #
					<img src="{PARTNER_PICTURE}" alt="" itemprop="image" />
					<div class="spacer">&nbsp;</div>
				# ENDIF #
				# IF C_APPROVED #
					<a href="{U_VISIT}" class="basic-button">
						<i class="fa fa-globe"></i> {@visit}
					</a>
					# IF IS_USER_CONNECTED #
					<a href="{U_DEADLINK}" class="basic-button alt" title="${LangLoader::get_message('deadlink', 'common')}">
						<i class="fa fa-unlink"></i>
					</a>
					# ENDIF #
				# ENDIF #
			</div>
			<h6>{@link_infos}</h6>
			<span class="text-strong">{@visits_number} : </span><span>{NUMBER_VIEWS}</span><br/>
			<span class="text-strong">${LangLoader::get_message('category', 'categories-common')} : </span><span><a itemprop="about" class="small" href="{U_CATEGORY}">{CATEGORY_NAME}</a></span><br/>
			# IF C_KEYWORDS #
				<span class="text-strong">${LangLoader::get_message('form.keywords', 'common')} : </span>
				<span>
					# START keywords #
						<a itemprop="keywords" href="{keywords.URL}">{keywords.NAME}</a># IF keywords.C_SEPARATOR #, # ENDIF #
					# END keywords #
				</span><br/>
			# ENDIF #
			# IF C_COMMENTS_ENABLED #
				<span># IF C_COMMENTS # {NUMBER_COMMENTS} # ENDIF # {L_COMMENTS}</span>
			# ENDIF #
			# IF C_APPROVED #
				# IF C_NOTATION_ENABLED #
					<div class="spacer">&nbsp;</div>
					<div class="center">{NOTATION}</div>
				# ENDIF #
			# ENDIF #
		</div>
		
		<div itemprop="text">{CONTENTS}</div>
	</div>
	<aside>
		# INCLUDE COMMENTS #
	</aside>
	<footer></footer>
</article>