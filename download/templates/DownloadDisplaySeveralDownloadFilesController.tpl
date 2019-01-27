<section id="module-download">
	<header>
		<div class="cat-actions">
			<a href="${relative_url(SyndicationUrlBuilder::rss('download', ID_CAT))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication" aria-hidden="true" title="${LangLoader::get_message('syndication', 'common')}"></i></a>
			# IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit small" aria-hidden="true" title="${LangLoader::get_message('edit', 'common')}"></i></a># ENDIF ## ENDIF #
		</div>
		<h1>
			# IF C_PENDING #{@download.pending}# ELSE #{@module_title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF #
		</h1>
	</header>
	# IF C_CATEGORY_DESCRIPTION #
		<div class="cat-description">
			{CATEGORY_DESCRIPTION}
		</div>
	# ENDIF #

	# IF C_SUB_CATEGORIES #
	<div class="subcat-container elements-container# IF C_SEVERAL_CATS_COLUMNS # columns-{NUMBER_CATS_COLUMNS}# ENDIF #">
		# START sub_categories_list #
		<div class="subcat-element block">
			<div class="subcat-content">
				# IF sub_categories_list.C_CATEGORY_IMAGE #
					<a class="subcat-thumbnail" itemprop="about" href="{sub_categories_list.U_CATEGORY}" title="{sub_categories_list.CATEGORY_NAME}">
						<img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="{sub_categories_list.CATEGORY_NAME}" />
					</a>
				# ENDIF #
				<a class="subcat-title" itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a>
				<span class="subcat-options">{sub_categories_list.DOWNLOADFILES_NUMBER} # IF sub_categories_list.C_MORE_THAN_ONE_DOWNLOADFILE #${TextHelper::lcfirst(LangLoader::get_message('files', 'common', 'download'))}# ELSE #${TextHelper::lcfirst(LangLoader::get_message('file', 'common', 'download'))}# ENDIF #</span>
			</div>
		</div>
		# END sub_categories_list #
		<div class="spacer"></div>
	</div>
	# IF C_SUBCATEGORIES_PAGINATION #<span class="center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
	# ELSE #
		# IF NOT C_CATEGORY_DISPLAYED_TABLE #<div class="spacer"></div># ENDIF #
	# ENDIF #

	<div class="elements-container">
	# IF C_FILES #
		# IF C_MORE_THAN_ONE_FILE #
			# INCLUDE SORT_FORM #
			<div class="spacer"></div>
		# ENDIF #
		# IF C_CATEGORY_DISPLAYED_TABLE #
			<table id="table">
				<thead>
					<tr>
						<th>${LangLoader::get_message('form.name', 'common')}</th>
						<th class="col-small">${LangLoader::get_message('form.keywords', 'common')}</th>
						<th class="col-small">${LangLoader::get_message('form.date.creation', 'common')}</th>
						<th class="col-small">{@downloads_number}</th>
						# IF C_NB_VIEW_ENABLED #<th>{@download.number.view}</th># ENDIF #
						# IF C_NOTATION_ENABLED #<th>${LangLoader::get_message('note', 'common')}</th># ENDIF #
						# IF C_COMMENTS_ENABLED #<th class="col-small">${LangLoader::get_message('comments', 'comments-common')}</th># ENDIF #
						# IF C_MODERATION #<th class="col-smaller"></th># ENDIF #
					</tr>
				</thead>
				<tbody>
					# START downloadfiles #
					<tr>
						<td>
							<a href="{downloadfiles.U_LINK}" itemprop="name"# IF downloadfiles.C_NEW_CONTENT # class="new-content"# ENDIF #>{downloadfiles.NAME}</a>
						</td>
						<td>
							# IF downloadfiles.C_KEYWORDS #
								# START downloadfiles.keywords #
									<a itemprop="keywords" href="{downloadfiles.keywords.URL}">{downloadfiles.keywords.NAME}</a># IF downloadfiles.keywords.C_SEPARATOR #, # ENDIF #
								# END downloadfiles.keywords #
							# ELSE #
								${LangLoader::get_message('none', 'common')}
							# ENDIF #
						</td>
						<td>
							<time datetime="# IF NOT downloadfiles.C_DIFFERED #{downloadfiles.DATE_ISO8601}# ELSE #{downloadfiles.DIFFERED_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT downloadfiles.C_DIFFERED #{downloadfiles.DATE}# ELSE #{downloadfiles.DIFFERED_START_DATE}# ENDIF #</time>
						</td>
						<td>
							{downloadfiles.NUMBER_DOWNLOADS}
						</td>
						# IF C_NB_VIEW_ENABLED #
						<td>
							{downloadfiles.NUMBER_VIEW}
						</td>
						# ENDIF #
						# IF C_NOTATION_ENABLED #
						<td>
							{downloadfiles.STATIC_NOTATION}
						</td>
						# ENDIF #
						# IF C_COMMENTS_ENABLED #
						<td>
							# IF downloadfiles.C_COMMENTS # {downloadfiles.NUMBER_COMMENTS} # ENDIF # {downloadfiles.L_COMMENTS}
						</td>
						# ENDIF #
						# IF C_MODERATION #
						<td>
							# IF downloadfiles.C_EDIT #
								<a href="{downloadfiles.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true" title="${LangLoader::get_message('edit', 'common')}"></i></a>
							# ENDIF #
							# IF downloadfiles.C_DELETE #
								<a href="{downloadfiles.U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete" aria-hidden="true" title="${LangLoader::get_message('delete', 'common')}"></i></a>
							# ENDIF #
						</td>
						# ENDIF #
					</tr>
					# END downloadfiles #
				</tbody>
			</table>
		# ELSE #
			# START downloadfiles #
			<article id="article-download-{downloadfiles.ID}" class="article-download article-several# IF C_CATEGORY_DISPLAYED_SUMMARY # block# ENDIF ## IF downloadfiles.C_NEW_CONTENT # new-content# ENDIF #" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
				<header>
					<h2><a href="{downloadfiles.U_LINK}" itemprop="name">{downloadfiles.NAME}</a></h2>
				</header>
				<div class="actions">
					# IF downloadfiles.C_EDIT #<a href="{downloadfiles.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true" title="${LangLoader::get_message('edit', 'common')}"></i></a># ENDIF #
					# IF downloadfiles.C_DELETE #<a href="{downloadfiles.U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete" aria-hidden="true" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"></i></a># ENDIF #
				</div>

				<meta itemprop="url" content="{downloadfiles.U_LINK}">
				<meta itemprop="description" content="${escape(downloadfiles.DESCRIPTION)}"/>
				# IF C_COMMENTS_ENABLED #
					<meta itemprop="discussionUrl" content="{downloadfiles.U_COMMENTS}">
					<meta itemprop="interactionCount" content="{downloadfiles.NUMBER_COMMENTS} UserComments">
				# ENDIF #

				# IF C_CATEGORY_DISPLAYED_SUMMARY #
					<div class="more">
						<i class="fa fa-download" title="{downloadfiles.L_DOWNLOADED_TIMES}" aria-hidden="true"></i>
						<span title="{downloadfiles.L_DOWNLOADED_TIMES}">{downloadfiles.NUMBER_DOWNLOADS}</span>
						# IF C_NB_VIEW_ENABLED # | <span title="{downloadfiles.NUMBER_VIEW} {@download.view}"><i class="fa fa-eye" aria-hidden="true"></i> {downloadfiles.NUMBER_VIEW}</span># ENDIF #
						# IF C_COMMENTS_ENABLED #
							| <i class="fa fa-comments-o" aria-hidden="true" title="${LangLoader::get_message('comments', 'comments-common')}"></i>
							# IF downloadfiles.C_COMMENTS # {downloadfiles.NUMBER_COMMENTS} # ENDIF # {downloadfiles.L_COMMENTS}
						# ENDIF #
						# IF downloadfiles.C_KEYWORDS #
							| <i class="fa fa-tags" aria-hidden="true" title="${LangLoader::get_message('form.keywords', 'common')}"></i>
							# START downloadfiles.keywords #
								<a itemprop="keywords" aria-hidden="true" href="{downloadfiles.keywords.URL}">{downloadfiles.keywords.NAME}</a>
								# IF downloadfiles.keywords.C_SEPARATOR #, # ENDIF #
							# END downloadfiles.keywords #
						# ENDIF #
						# IF C_NOTATION_ENABLED #
							<span class="float-right">{downloadfiles.STATIC_NOTATION}</span>
						# ENDIF #
						<div class="spacer"></div>
					</div>
					<div class="content">
						# IF downloadfiles.C_PICTURE #
						<a href="{downloadfiles.U_LINK}" class="thumbnail-item">
							<img src="{downloadfiles.U_PICTURE}" alt="{downloadfiles.NAME}" title="{downloadfiles.NAME}" itemprop="image" />
						</a>
						# ENDIF #
						{downloadfiles.DESCRIPTION}# IF downloadfiles.C_READ_MORE #... <a href="{downloadfiles.U_LINK}" class="read-more">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #
						<div class="spacer"></div>
					</div>
				# ELSE #
					<div class="content">
						<div class="options infos">
							<div class="center">
								# IF downloadfiles.C_PICTURE #
									<img src="{downloadfiles.U_PICTURE}" alt="{downloadfiles.NAME}" title="{downloadfiles.NAME}" itemprop="image" />
									<div class="spacer"></div>
								# ENDIF #
								# IF downloadfiles.C_VISIBLE #
									<a href="{downloadfiles.U_DOWNLOAD}" class="basic-button">
										<i class="fa fa-download" aria-hidden="true"></i> {@download}
									</a>
									# IF IS_USER_CONNECTED #
									<a href="{downloadfiles.U_DEADLINK}" class="basic-button alt" aria-label="${LangLoader::get_message('deadlink', 'common')}">
										<i class="fa fa-unlink" aria-hidden="true" title="${LangLoader::get_message('deadlink', 'common')}"></i>
									</a>
									# ENDIF #
								# ENDIF #
							</div>
							<h6>{@file_infos}</h6>
							# IF downloadfiles.C_SOFTWARE_VERSION #<span class="text-strong">{@software_version} : </span><span>{downloadfiles.SOFTWARE_VERSION}</span><br/># ENDIF #
							<span class="infos-options"><span class="text-strong">${LangLoader::get_message('size', 'common')} : </span># IF downloadfiles.C_SIZE #{downloadfiles.SIZE}# ELSE #${LangLoader::get_message('unknown_size', 'common')}# ENDIF #</span>
							<span class="infos-options"><span class="text-strong">${LangLoader::get_message('form.date.creation', 'common')} : </span><time datetime="# IF NOT downloadfiles.C_DIFFERED #{downloadfiles.DATE_ISO8601}# ELSE #{downloadfiles.DIFFERED_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT downloadfiles.C_DIFFERED #{downloadfiles.DATE}# ELSE #{downloadfiles.DIFFERED_START_DATE}# ENDIF #</time></span>
							# IF downloadfiles.C_UPDATED_DATE #<span class="infos-options"><span class="text-strong">${LangLoader::get_message('form.date.update', 'common')} : </span><time datetime="{downloadfiles.UPDATED_DATE_ISO8601}" itemprop="dateModified">{downloadfiles.UPDATED_DATE}</time></span># ENDIF #
							<span class="infos-options"><span class="text-strong">{@downloads_number} : </span>{downloadfiles.NUMBER_DOWNLOADS}</span>
							# IF C_NB_VIEW_ENABLED #<span class="infos-options" title="{downloadfiles.NUMBER_VIEW} {@download.view}"><span class="text-strong">{@download.number.view} : </span>{downloadfiles.NUMBER_VIEW}</span># ENDIF #
							# IF NOT C_CATEGORY #<span class="infos-options"><span class="text-strong">${LangLoader::get_message('category', 'categories-common')} : </span><a itemprop="about" href="{downloadfiles.U_CATEGORY}">{downloadfiles.CATEGORY_NAME}</a></span># ENDIF #
							# IF downloadfiles.C_KEYWORDS #
								<span class="infos-options">
									<span class="text-strong">${LangLoader::get_message('form.keywords', 'common')} : </span>
									# START downloadfiles.keywords #
										<a itemprop="keywords" href="{downloadfiles.keywords.URL}">{downloadfiles.keywords.NAME}</a># IF downloadfiles.keywords.C_SEPARATOR #, # ENDIF #
									# END downloadfiles.keywords #
								</span>
							# ENDIF #
							# IF C_AUTHOR_DISPLAYED #
								<span class="infos-options">
									<span class="text-strong">${LangLoader::get_message('author', 'common')} : </span>
									# IF downloadfiles.C_AUTHOR_CUSTOM_NAME #
										{downloadfiles.AUTHOR_CUSTOM_NAME}
									# ELSE #
										# IF downloadfiles.C_AUTHOR_EXIST #<a itemprop="author" rel="author" class="{downloadfiles.USER_LEVEL_CLASS}" href="{downloadfiles.U_AUTHOR_PROFILE}" # IF downloadfiles.C_USER_GROUP_COLOR # style="color:{downloadfiles.USER_GROUP_COLOR}" # ENDIF #>{downloadfiles.PSEUDO}</a># ELSE #{downloadfiles.PSEUDO}# ENDIF #
									# ENDIF #
								</span>
							# ENDIF #
							# IF C_COMMENTS_ENABLED #
								<span class="infos-options"># IF downloadfiles.C_COMMENTS # {downloadfiles.NUMBER_COMMENTS} # ENDIF # {downloadfiles.L_COMMENTS}</span>
							# ENDIF #
							# IF downloadfiles.C_VISIBLE #
								# IF C_NOTATION_ENABLED #
									<div class="center">{downloadfiles.NOTATION}</div>
								# ENDIF #
							# ENDIF #
						</div>

						<div itemprop="text">{downloadfiles.CONTENTS}</div>
					</div>
				# ENDIF #

				<footer></footer>
			</article>
			# END downloadfiles #
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
