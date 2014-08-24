<section>
	<header>
		<h1>
			# IF C_PENDING #{@web.pending}# ELSE #{@module_title} - {CATEGORY_NAME}# ENDIF #
		</h1>
		# IF C_CATEGORY_DESCRIPTION #
			# IF NOT C_ROOT_CATEGORY #
			<div class="spacer">&nbsp;</div>
			{CATEGORY_DESCRIPTION}
			<div class="spacer">&nbsp;</div>
			<hr />
			# ENDIF #
		# ENDIF #
		# IF C_SUB_CATEGORIES #
		<div class="cat">
			<div class="subcat">
				<div class="spacer">&nbsp;</div>
				# IF C_MORE_THAN_ONE_SUB_CATEGORY #${LangLoader::get_message('sub_categories', 'categories-common')}# ELSE #${LangLoader::get_message('sub_category', 'categories-common')}# ENDIF # :
				<div class="spacer">&nbsp;</div>
				# START sub_categories_list #
				<div class="sub_category" style="width:{CATS_COLUMNS_WIDTH}%;">
				<a itemprop="about" href="{sub_categories_list.U_CATEGORY}"><img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="" /></a><br />
				<a itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME} ({sub_categories_list.WEBLINKS_NUMBER})</a>
				<br />
				<span class="small">{sub_categories_list.CATEGORY_DESCRIPTION}</span>
				</div>
				# END sub_categories_list #
			</div>
		</div>
		<div class="spacer">&nbsp;</div>
		<hr />
		# ENDIF #
	</header>
	<div class="content">
	# IF C_WEBLINKS #
		# START weblinks #
		<article # IF C_CATEGORY_DISPLAYED_SUMMARY #class="block" # ENDIF #itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
			<header>
				<h1>
					<a href="{weblinks.U_LINK}" itemprop="name">{weblinks.NAME}</a>
					<span class="actions">
						# IF weblinks.C_EDIT #
							<a href="{weblinks.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}" class="fa fa-edit"></a>
						# ENDIF #
						# IF weblinks.C_DELETE #
							<a href="{weblinks.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" class="fa fa-delete" data-confirmation="delete-element"></a>
						# ENDIF #
					</span>
				</h1>
				# IF C_CATEGORY_DISPLAYED_SUMMARY #
				<div class="spacer">&nbsp;</div>
				<div class="more">
					<i class="fa fa-eye" title="{weblinks.L_VISITED_TIMES}"></i>&nbsp;<span title="{weblinks.L_VISITED_TIMES}">{weblinks.NUMBER_VIEWS}</span>
					# IF C_COMMENTS_ENABLED #
						&nbsp;|&nbsp;<i class="fa fa-comment" title="${LangLoader::get_message('comments', 'comments-common')}"></i># IF weblinks.C_COMMENTS # {weblinks.NUMBER_COMMENTS} # ENDIF # {weblinks.L_COMMENTS}
					# ENDIF #
					# IF weblinks.C_KEYWORDS #
						&nbsp;|&nbsp;<i title=""${LangLoader::get_message('form.keywords', 'common')}" class="fa fa-tags"></i> 
						# START weblinks.keywords #
							<a itemprop="keywords" href="{weblinks.keywords.URL}">{weblinks.keywords.NAME}</a># IF weblinks.keywords.C_SEPARATOR #, # ENDIF #
						# END weblinks.keywords #
					# ENDIF #
					# IF C_NOTATION_ENABLED #
						<span class="float-right">{weblinks.STATIC_NOTATION}</span>
					# ENDIF #
				</div>
				# ENDIF #
				<meta itemprop="url" content="{weblinks.U_LINK}">
				<meta itemprop="description" content="${escape(weblinks.DESCRIPTION)}"/>
				<meta itemprop="discussionUrl" content="{weblinks.U_COMMENTS}">
				<meta itemprop="interactionCount" content="{weblinks.NUMBER_COMMENTS} UserComments">
				
			</header>
			
			# IF NOT C_CATEGORY_DISPLAYED_SUMMARY #
			<div class="content">
				# IF NOT weblinks.C_APPROVED #
					# INCLUDE NOT_APPROVED_MESSAGE #
				# ENDIF #
				<div class="options infos">
					<div class="center">
						# IF weblinks.C_HAS_PARTNER_PICTURE #
							<img src="{weblinks.PARTNER_PICTURE}" alt="" itemprop="image" />
							<div class="spacer">&nbsp;</div>
						# ENDIF #
						# IF weblinks.C_APPROVED #
							<a href="{weblinks.U_VISIT}" class="basic-button">
								<i class="fa fa-globe"></i> {@visit}
							</a>
							# IF IS_USER_CONNECTED #
							<a href="{weblinks.U_DEADLINK}" class="basic-button alt" title="${LangLoader::get_message('deadlink', 'common')}">
								<i class="fa fa-unlink"></i>
							</a>
							# ENDIF #
						# ENDIF #
					</div>
					<h6>{@link_infos}</h6>
					<span class="text-strong">{@visits_number} : </span><span>{weblinks.NUMBER_VIEWS}</span><br/>
					<span class="text-strong">${LangLoader::get_message('category', 'categories-common')} : </span><span><a itemprop="about" class="small" href="{weblinks.U_CATEGORY}">{weblinks.CATEGORY_NAME}</a></span><br/>
					# IF weblinks.C_KEYWORDS #
						<span>
							<i title="${LangLoader::get_message('form.keywords', 'common')}" class="fa fa-tags"></i> 
							# START weblinks.keywords #
								<a itemprop="keywords" href="{weblinks.keywords.URL}">{weblinks.keywords.NAME}</a># IF weblinks.keywords.C_SEPARATOR #, # ENDIF #
							# END weblinks.keywords #
						</span><br/>
					# ENDIF #
					# IF C_COMMENTS_ENABLED #
						<span><i class="fa fa-comment" title="${LangLoader::get_message('comments', 'comments-common')}"></i># IF weblinks.C_COMMENTS # {weblinks.NUMBER_COMMENTS} # ENDIF # {weblinks.L_COMMENTS}</span>
					# ENDIF #
					# IF weblinks.C_APPROVED #
						# IF C_NOTATION_ENABLED #
							<div class="spacer">&nbsp;</div>
							<div class="center">{weblinks.NOTATION}</div>
						# ENDIF #
					# ENDIF #
				</div>
				
				<div itemprop="text">{weblinks.CONTENTS}</div>
			</div>
			# ENDIF #
			
			<footer></footer>
		</article>
		# END weblinks #
	# ELSE #
		# IF NOT C_ROOT_CATEGORY #
		<div class="center">
			${LangLoader::get_message('no_item_now', 'common')}
		</div>
		# ENDIF #
	# ENDIF #
	</div>
	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>