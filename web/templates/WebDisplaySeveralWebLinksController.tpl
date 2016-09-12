<section id="module-web">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('web', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			# IF C_PENDING #{@web.pending}# ELSE #{@module_title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF # # IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF ## ENDIF #
		</h1>
		
		# IF C_CATEGORY_DESCRIPTION #
		<div class="cat-description">
			{CATEGORY_DESCRIPTION}
		</div>
		# ENDIF #
		
	</header>
	
	# IF C_SUB_CATEGORIES #
	<div class="subcat-container">
		# START sub_categories_list #
		<div class="subcat-element" style="width:{CATS_COLUMNS_WIDTH}%;">
			<div class="subcat-content">
				# IF sub_categories_list.C_CATEGORY_IMAGE #<a itemprop="about" href="{sub_categories_list.U_CATEGORY}"><img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="{sub_categories_list.CATEGORY_NAME}" /></a># ENDIF #
				<br />
				<a itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a>
				<br />
				<span class="small">{sub_categories_list.WEBLINKS_NUMBER} # IF sub_categories_list.C_MORE_THAN_ONE_WEBLINK #${TextHelper::lowercase_first(LangLoader::get_message('links', 'common', 'web'))}# ELSE #${TextHelper::lowercase_first(LangLoader::get_message('link', 'common', 'web'))}# ENDIF #</span>
			</div>
		</div>
		# END sub_categories_list #
		<div class="spacer"></div>
	</div>
	# IF C_SUBCATEGORIES_PAGINATION #<span class="center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
	# ELSE #
		# IF NOT C_CATEGORY_DISPLAYED_TABLE #<div class="spacer"></div># ENDIF #
	# ENDIF #
	
	<div class="content">
	# IF C_WEBLINKS #
		# IF C_MORE_THAN_ONE_WEBLINK #
			# INCLUDE SORT_FORM #
			<div class="spacer"></div>
		# ENDIF #
		# IF C_CATEGORY_DISPLAYED_TABLE #
			<table id="table">
				<thead>
					<tr>
						<th>${LangLoader::get_message('form.name', 'common')}</th>
						<th class="col-small">${LangLoader::get_message('form.keywords', 'common')}</th>
						<th class="col-small">{@visits_number}</th>
						# IF C_NOTATION_ENABLED #<th>${LangLoader::get_message('note', 'common')}</th># ENDIF #
						# IF C_COMMENTS_ENABLED #<th class="col-small">${LangLoader::get_message('comments', 'comments-common')}</th># ENDIF #
						# IF C_MODERATE #<th class="col-smaller"></th># ENDIF #
					</tr>
				</thead>
				<tbody>
					# START weblinks #
					<tr>
						<td>
							<a href="{weblinks.U_LINK}" itemprop="name">{weblinks.NAME}</a>
						</td>
						<td>
							# IF weblinks.C_KEYWORDS #
								# START weblinks.keywords #
									<a itemprop="keywords" href="{weblinks.keywords.URL}">{weblinks.keywords.NAME}</a># IF weblinks.keywords.C_SEPARATOR #, # ENDIF #
								# END weblinks.keywords #
							# ELSE #
								${LangLoader::get_message('none', 'common')}
							# ENDIF #
						</td>
						<td>
							{weblinks.NUMBER_VIEWS}
						</td>
						# IF C_NOTATION_ENABLED #
						<td>
							{weblinks.STATIC_NOTATION}
						</td>
						# ENDIF #
						# IF C_COMMENTS_ENABLED #
						<td>
							# IF weblinks.C_COMMENTS # {weblinks.NUMBER_COMMENTS} # ENDIF # {weblinks.L_COMMENTS}
						</td>
						# ENDIF #
						# IF C_MODERATE #
						<td>
							# IF weblinks.C_EDIT #
							<a href="{weblinks.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
							# ENDIF #
							# IF weblinks.C_DELETE #
							<a href="{weblinks.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
							# ENDIF #
						</td>
						# ENDIF #
					</tr>
					# END weblinks #
				</tbody>
			</table>
		# ELSE #
			# START weblinks #
			<article id="article-web-{weblinks.ID}" class="article-web article-several# IF C_CATEGORY_DISPLAYED_SUMMARY # block# IF weblinks.C_IS_PARTNER # web-friends# ENDIF ## IF weblinks.C_IS_PRIVILEGED_PARTNER # web-privileged-friends# ENDIF #" # ENDIF #itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
				<header>
					<h2>
						<span class="actions">
							# IF weblinks.C_EDIT #<a href="{weblinks.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a># ENDIF #
							# IF weblinks.C_DELETE #<a href="{weblinks.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a># ENDIF #
						</span>
						<a href="{weblinks.U_LINK}" itemprop="name">{weblinks.NAME}</a>
					</h2>
					
					<meta itemprop="url" content="{weblinks.U_LINK}">
					<meta itemprop="description" content="${escape(weblinks.DESCRIPTION)}"/>
					# IF C_COMMENTS_ENABLED #
					<meta itemprop="discussionUrl" content="{weblinks.U_COMMENTS}">
					<meta itemprop="interactionCount" content="{weblinks.NUMBER_COMMENTS} UserComments">
					# ENDIF #
				</header>
				
				# IF C_CATEGORY_DISPLAYED_SUMMARY #
					<div class="more">
						<i class="fa fa-eye" title="{weblinks.L_VISITED_TIMES}"></i>
						<span title="{weblinks.L_VISITED_TIMES}">{weblinks.NUMBER_VIEWS}</span>
						# IF C_COMMENTS_ENABLED #
							| <i class="fa fa-comment" title="${LangLoader::get_message('comments', 'comments-common')}"></i>
							# IF weblinks.C_COMMENTS # {weblinks.NUMBER_COMMENTS} # ENDIF # {weblinks.L_COMMENTS}
						# ENDIF #
						# IF weblinks.C_KEYWORDS #
							| <i class="fa fa-tags" title="${LangLoader::get_message('form.keywords', 'common')}"></i> 
							# START weblinks.keywords #
								<a itemprop="keywords" href="{weblinks.keywords.URL}">{weblinks.keywords.NAME}</a>
								# IF weblinks.keywords.C_SEPARATOR #, # ENDIF #
							# END weblinks.keywords #
						# ENDIF #
						# IF C_NOTATION_ENABLED #
							<span class="float-right">{weblinks.STATIC_NOTATION}</span>
						# ENDIF #
						<div class="spacer"></div>
					</div>
					<div class="content">
						{weblinks.DESCRIPTION}# IF weblinks.C_READ_MORE #... <a href="{weblinks.U_LINK}" class="read-more">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #
					</div>
				# ELSE #
					<div class="content">
						<div class="options infos">
							<div class="center">
								# IF weblinks.C_HAS_PARTNER_PICTURE #
									<img src="{weblinks.U_PARTNER_PICTURE}" alt="{weblinks.NAME}" itemprop="image" />
									<div class="spacer"></div>
								# ENDIF #
								# IF weblinks.C_VISIBLE #
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
							# IF NOT C_CATEGORY #<span class="text-strong">${LangLoader::get_message('category', 'categories-common')} : </span><span><a itemprop="about" class="small" href="{weblinks.U_CATEGORY}">{weblinks.CATEGORY_NAME}</a></span><br/># ENDIF #
							# IF weblinks.C_KEYWORDS #
								<span class="text-strong">${LangLoader::get_message('form.keywords', 'common')} : </span>
								<span>
									# START weblinks.keywords #
										<a itemprop="keywords" class="small" href="{weblinks.keywords.URL}">{weblinks.keywords.NAME}</a># IF weblinks.keywords.C_SEPARATOR #, # ENDIF #
									# END weblinks.keywords #
								</span><br/>
							# ENDIF #
							# IF C_COMMENTS_ENABLED #
								<span># IF weblinks.C_COMMENTS # {weblinks.NUMBER_COMMENTS} # ENDIF # {weblinks.L_COMMENTS}</span>
							# ENDIF #
							# IF C_NOTATION_ENABLED #
								<div class="spacer"></div>
								<div class="center">{weblinks.NOTATION}</div>
							# ENDIF #
						</div>
						
						<div itemprop="text">{weblinks.CONTENTS}</div>
					</div>
				# ENDIF #
				
				<footer></footer>
			</article>
			# END weblinks #
		# ENDIF #
	# ELSE #
		# IF NOT C_HIDE_NO_ITEM_MESSAGE #
		<div class="center">
			${LangLoader::get_message('no_item_now', 'common')}
		</div>
		# ENDIF #
	# ENDIF #
	</div>
	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>