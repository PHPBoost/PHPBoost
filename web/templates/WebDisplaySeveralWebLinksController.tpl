<section id="module-web">
	<header>
		<div class="align-right">
			<a href="${relative_url(SyndicationUrlBuilder::rss('web', ID_CAT))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss" aria-hidden="true"></i></a>
			# IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit small" aria-hidden="true"></i></a># ENDIF ## ENDIF #
		</div>
		<h1>
			# IF C_PENDING #{@web.pending}# ELSE #{@module_title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF #
		</h1>
	</header>
	# IF C_CATEGORY_DESCRIPTION #
	<div class="cat-description">
		{CATEGORY_DESCRIPTION}
	</div>
	# ENDIF #

	# IF C_SUB_CATEGORIES #
	<div class="subcat-container elements-container# IF C_SEVERAL_COLUMNS # columns-{COLUMNS_NUMBER}# ENDIF #">
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
	# IF C_SUBCATEGORIES_PAGINATION #<span class="align-center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
	# ELSE #
		# IF NOT C_CATEGORY_DISPLAYED_TABLE #<div class="spacer"></div># ENDIF #
	# ENDIF #


	# IF C_WEBLINKS #
		# IF C_MORE_THAN_ONE_WEBLINK #
		# INCLUDE SORT_FORM #
		<div class="spacer"></div>
		# ENDIF #
	<div class="elements-container# IF C_CATEGORY_DISPLAYED_SUMMARY # columns-{COLUMNS_NUMBER}# ENDIF #">
		# IF C_CATEGORY_DISPLAYED_TABLE #
			<table class="table">
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
							# IF weblinks.C_COMMENTS # {weblinks.COMENTS_NUMBER} # ENDIF # {weblinks.L_COMMENTS}
						</td>
						# ENDIF #
						# IF C_MODERATE #
						<td>
							# IF weblinks.C_EDIT #
							<a href="{weblinks.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a>
							# ENDIF #
							# IF weblinks.C_DELETE #
							<a href="{weblinks.U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
							# ENDIF #
						</td>
						# ENDIF #
					</tr>
					# END weblinks #
				</tbody>
			</table>
		# ELSE #
		# START weblinks #
		<article id="article-web-{weblinks.ID}" class="article-web several-items# IF C_CATEGORY_DISPLAYED_SUMMARY # block# ENDIF ## IF weblinks.C_IS_PARTNER # content-friends# ENDIF ## IF weblinks.C_IS_PRIVILEGED_PARTNER # content-privileged-friends# ENDIF ## IF weblinks.C_NEW_CONTENT # new-content# ENDIF#" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
			<header>
				<span class="controls">
					# IF weblinks.C_EDIT #<a href="{weblinks.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a># ENDIF #
					# IF weblinks.C_DELETE #<a href="{weblinks.U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="fa fa-trash-alt" aria-hidden="true"></i></a># ENDIF #
				</span>
				<h2><a href="{weblinks.U_LINK}" itemprop="name">{weblinks.NAME}</a></h2>
				<meta itemprop="url" content="{weblinks.U_LINK}">
				<meta itemprop="description" content="${escape(weblinks.DESCRIPTION)}"/>
				# IF C_COMMENTS_ENABLED #
				<meta itemprop="discussionUrl" content="{weblinks.U_COMMENTS}">
				<meta itemprop="interactionCount" content="{weblinks.COMMENTS_NUMBER} UserComments">
				# ENDIF #
			</header>

			# IF C_CATEGORY_DISPLAYED_SUMMARY #
			<div class="more">
				<span><i class="fa fa-eye" aria-hidden="true"></i> {weblinks.NUMBER_VIEWS}</span>
				# IF C_COMMENTS_ENABLED #
					| <i class="fa fa-comments" aria-hidden="true"></i>
					# IF weblinks.C_COMMENTS # {weblinks.COMMENTS_NUMBER} # ENDIF # {weblinks.L_COMMENTS}
				# ENDIF #
				# IF weblinks.C_KEYWORDS #
					| <i class="fa fa-tags" aria-hidden="true"></i>
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
				<a href="{weblinks.U_LINK}" class="item-thumbnail">
					<img src="{weblinks.U_PICTURE}" alt="{weblinks.NAME}" itemprop="image" />
				</a>
				# ENDIF #
				{weblinks.DESCRIPTION}# IF weblinks.C_READ_MORE #... <a href="{weblinks.U_LINK}" class="read-more">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #
				<div class="spacer"></div>
			</div>
			# ELSE #
			<div class="content">
				<div class="options infos">
					<div class="align-center">
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
							<a href="{weblinks.U_VISIT}" class="button alt-button">
								<i class="fa fa-globe" aria-hidden="true"></i> {@visit}
							</a>
							# IF IS_USER_CONNECTED #
							<a href="{weblinks.U_DEADLINK}" data-confirmation="${LangLoader::get_message('deadlink.confirmation', 'common')}" class="button alt-button" aria-label="${LangLoader::get_message('deadlink', 'common')}">
								<i class="fa fa-unlink" aria-hidden="true"></i>
							</a>
							# ENDIF #
						# ELSE #
							# IF C_PENDING #
							<a href="{weblinks.U_VISIT}" class="button alt-button">
								<i class="fa fa-globe" aria-hidden="true"></i> {@visit}
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
					<span class="infos-options"># IF weblinks.C_COMMENTS # {weblinks.COMMENTS_NUMBER} # ENDIF # {weblinks.L_COMMENTS}</span>
					# ENDIF #
					# IF C_NOTATION_ENABLED #
					<div class="spacer"></div>
					<div class="align-center">{weblinks.NOTATION}</div>
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
		<div class="align-center">
			${LangLoader::get_message('no_item_now', 'common')}
		</div>
		# ENDIF #
	</div>
	# ENDIF #

	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>
