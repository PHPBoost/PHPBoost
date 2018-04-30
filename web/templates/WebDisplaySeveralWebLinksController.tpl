<section id="module-web">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('web', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa-pbt fa-syndication"></i></a>
			# IF C_PENDING #{@web.pending}# ELSE #{@module_title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF # # IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit small"></i></a># ENDIF ## ENDIF #
		</h1>

		# IF C_CATEGORY_DESCRIPTION #
		<div class="cat-description">
			{CATEGORY_DESCRIPTION}
		</div>
		# ENDIF #

	</header>

	# IF C_SUB_CATEGORIES #
	<div class="subcat-container elements-container# IF C_SEVERAL_CATS_COLUMNS # columns-{NUMBER_CATS_COLUMNS}# ENDIF #">
		# START sub_categories_list #
		<div class="subcat-element block">
			<div class="subcat-content">
				# IF sub_categories_list.C_CATEGORY_IMAGE #
					<a class="subcat-thumbnail" itemprop="about" href="{sub_categories_list.U_CATEGORY}">
						<img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="{sub_categories_list.CATEGORY_NAME}" />
					</a>
				# ENDIF #
				<a class="subcat-title" itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a>
				<span class="subcat-options">{sub_categories_list.WEBLINKS_NUMBER} # IF sub_categories_list.C_MORE_THAN_ONE_WEBLINK #${TextHelper::lcfirst(LangLoader::get_message('links', 'common', 'web'))}# ELSE #${TextHelper::lcfirst(LangLoader::get_message('link', 'common', 'web'))}# ENDIF #</span>
			</div>
		</div>
		# END sub_categories_list #
		<div class="spacer"></div>
	</div>
	# IF C_SUBCATEGORIES_PAGINATION #<span class="center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
	# ELSE #
		# IF NOT C_CATEGORY_DISPLAYED_TABLE #<div class="spacer"></div># ENDIF #
	# ENDIF #


	# IF C_WEBLINKS #
		# IF C_MORE_THAN_ONE_WEBLINK #
		# INCLUDE SORT_FORM #
		<div class="spacer"></div>
		# ENDIF #
	<div class="content elements-container">
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
							<a href="{weblinks.U_LINK}" itemprop="name"# IF weblinks.C_NEW_CONTENT # class="new-content"# ENDIF#>{weblinks.NAME}</a>
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
							<a href="{weblinks.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit"></i></a>
							# ENDIF #
							# IF weblinks.C_DELETE #
							<a href="{weblinks.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="far fa-delete"></i></a>
							# ENDIF #
						</td>
						# ENDIF #
					</tr>
					# END weblinks #
				</tbody>
			</table>
		# ELSE #
		# START weblinks #
		<article id="article-web-{weblinks.ID}" class="article-web article-several# IF C_CATEGORY_DISPLAYED_SUMMARY # block# ENDIF ## IF weblinks.C_IS_PARTNER # content-friends# ENDIF ## IF weblinks.C_IS_PRIVILEGED_PARTNER # content-privileged-friends# ENDIF ## IF weblinks.C_NEW_CONTENT # new-content# ENDIF#" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
			<header>
				<h2>
					<span class="actions">
						# IF weblinks.C_EDIT #<a href="{weblinks.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit"></i></a># ENDIF #
						# IF weblinks.C_DELETE #<a href="{weblinks.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="far fa-delete"></i></a># ENDIF #
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
				<i class="fas fa-eye" title="{weblinks.L_VISITED_TIMES}"></i>
				<span title="{weblinks.L_VISITED_TIMES}">{weblinks.NUMBER_VIEWS}</span>
				# IF C_COMMENTS_ENABLED #
					| <i class="fas fa-comments" title="${LangLoader::get_message('comments', 'comments-common')}"></i>
					# IF weblinks.C_COMMENTS # {weblinks.NUMBER_COMMENTS} # ENDIF # {weblinks.L_COMMENTS}
				# ENDIF #
				# IF weblinks.C_KEYWORDS #
					| <i class="fas fa-tags" title="${LangLoader::get_message('form.keywords', 'common')}"></i>
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
				# IF weblinks.C_PICTURE #
				<a href="{weblinks.U_LINK}" class="thumbnail-item">
					<img src="{weblinks.U_PICTURE}" alt="{weblinks.NAME}" itemprop="image" />
				</a>
				# ENDIF #
				{weblinks.DESCRIPTION}# IF weblinks.C_READ_MORE #... <a href="{weblinks.U_LINK}" class="read-more">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #
				<div class="spacer"></div>
			</div>
			# ELSE #
			<div class="content">
				<div class="options infos">
					<div class="center">
						# IF weblinks.C_IS_PARTNER #
							# IF weblinks.C_HAS_PARTNER_PICTURE #
								<img src="{weblinks.U_PARTNER_PICTURE}" alt="{weblinks.NAME}" itemprop="image" />
							# ELSE #
								# IF weblinks.C_PICTURE #
									<img src="{weblinks.U_PICTURE}" alt="{weblinks.NAME}" itemprop="image" />
								# ENDIF #
							# ENDIF #
						<div class="spacer"></div>
						# ELSE #
							# IF weblinks.C_PICTURE #
								<img src="{weblinks.U_PICTURE}" alt="{weblinks.NAME}" itemprop="image" />
							# ENDIF #
						<div class="spacer"></div>
						# ENDIF #
						# IF weblinks.C_VISIBLE #
							<a href="{weblinks.U_VISIT}" rel="nofollow" class="basic-button">
								<i class="fas fa-globe"></i> {@visit}
							</a>
							# IF IS_USER_CONNECTED #
							<a href="{weblinks.U_DEADLINK}" class="basic-button alt" title="${LangLoader::get_message('deadlink', 'common')}">
								<i class="fas fa-unlink"></i>
							</a>
							# ENDIF #
						# ENDIF #
					</div>
					<h6>{@link_infos}</h6>
					<span class="infos-options"><span class="text-strong">{@visits_number} : </span>{weblinks.NUMBER_VIEWS}</span>
					# IF NOT C_CATEGORY #<span class="infos-options"><span class="text-strong">${LangLoader::get_message('category', 'categories-common')} : </span><a itemprop="about" href="{weblinks.U_CATEGORY}">{weblinks.CATEGORY_NAME}</a></span># ENDIF #
					# IF weblinks.C_KEYWORDS #
					<span class="text-strong">${LangLoader::get_message('form.keywords', 'common')} : </span>
					<span class="infos-options">
						# START weblinks.keywords #
						<a itemprop="keywords" href="{weblinks.keywords.URL}">{weblinks.keywords.NAME}</a># IF weblinks.keywords.C_SEPARATOR #, # ENDIF #
						# END weblinks.keywords #
					</span>
					# ENDIF #
					# IF C_COMMENTS_ENABLED #
					<span class="infos-options"># IF weblinks.C_COMMENTS # {weblinks.NUMBER_COMMENTS} # ENDIF # {weblinks.L_COMMENTS}</span>
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
	</div>
	# ENDIF #

	# ELSE #
	<div class="content">
		# IF NOT C_HIDE_NO_ITEM_MESSAGE #
		<div class="center">
			${LangLoader::get_message('no_item_now', 'common')}
		</div>
		# ENDIF #
	</div>
	# ENDIF #

	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>
