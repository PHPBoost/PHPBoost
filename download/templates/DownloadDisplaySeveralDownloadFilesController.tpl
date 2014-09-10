<section>
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('download', ID_CAT))}" class="fa fa-syndication" title="${LangLoader::get_message('syndication', 'common')}"></a>
			# IF C_PENDING #{@download.pending}# ELSE #{@module_title} - {CATEGORY_NAME}# ENDIF #
		</h1>
		# IF C_CATEGORY_DESCRIPTION #
			<div class="spacer">&nbsp;</div>
			{CATEGORY_DESCRIPTION}
		# ENDIF #
		# IF C_SUB_CATEGORIES #
		<div class="spacer">&nbsp;</div>
		<hr />
		<div class="spacer">&nbsp;</div>
		<div class="cat">
			<div class="subcat">
				# START sub_categories_list #
				<div class="sub-category" style="width:{CATS_COLUMNS_WIDTH}%;">
					<a itemprop="about" href="{sub_categories_list.U_CATEGORY}"><img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="" /></a><br />
					<a itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME} ({sub_categories_list.WEBLINKS_NUMBER})</a><br />
				</div>
				# END sub_categories_list #
			</div>
		</div>
		<div class="spacer">&nbsp;</div>
		<hr />
		# ELSE #
			# IF NOT C_CATEGORY_DISPLAYED_TABLE #<div class="spacer">&nbsp;</div># ENDIF #
		# ENDIF #
	</header>
	<div class="content">
	# IF C_FILES #
		# IF C_MORE_THAN_ONE_FILE #
			# INCLUDE SORT_FORM #
			<div class="spacer">&nbsp;</div>
		# ENDIF #
		# IF C_CATEGORY_DISPLAYED_TABLE #
			<table>
				<thead>
					<tr>
						<th>${LangLoader::get_message('form.name', 'common')}</th>
						<th>${LangLoader::get_message('form.keywords', 'common')}</th>
						<th>${LangLoader::get_message('form.date.creation', 'common')}</th>
						<th>{@downloads_number}</th>
						# IF C_NOTATION_ENABLED #<th>${LangLoader::get_message('note', 'common')}</th># ENDIF #
						# IF C_COMMENTS_ENABLED #<th>${LangLoader::get_message('comments', 'comments-common')}</th># ENDIF #
					</tr>
				</thead>
				# IF C_PAGINATION #
				<tfoot>
					<tr>
						<th colspan="{TABLE_COLSPAN}">
							# INCLUDE PAGINATION #
						</th>
					</tr>
				</tfoot>
				# ENDIF #
				<tbody>
					# START downloadfiles #
					<tr>
						<td>
							<a href="{downloadfiles.U_LINK}" itemprop="name">{downloadfiles.NAME}</a>
							<span class="float-right">
								# IF downloadfiles.C_EDIT #
									<a href="{downloadfiles.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}" class="fa fa-edit"></a>
								# ENDIF #
								# IF downloadfiles.C_DELETE #
									<a href="{downloadfiles.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" class="fa fa-delete" data-confirmation="delete-element"></a>
								# ENDIF #
							</span>
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
							<time datetime="{downloadfiles.DATE_ISO8601}" itemprop="datePublished">{downloadfiles.DATE}</time>
						</td>
						<td>
							{downloadfiles.NUMBER_DOWNLOADS}
						</td>
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
					</tr>
					# END downloadfiles #
				</tbody>
			</table>
		# ELSE #
			# START downloadfiles #
			<article # IF C_CATEGORY_DISPLAYED_SUMMARY #class="block" # ENDIF #itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
				<header>
					<h1>
						<a href="{downloadfiles.U_LINK}" itemprop="name">{downloadfiles.NAME}</a>
						<span class="actions">
							# IF downloadfiles.C_EDIT #
								<a href="{downloadfiles.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}" class="fa fa-edit"></a>
							# ENDIF #
							# IF downloadfiles.C_DELETE #
								<a href="{downloadfiles.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" class="fa fa-delete" data-confirmation="delete-element"></a>
							# ENDIF #
						</span>
					</h1>
					
					<meta itemprop="url" content="{downloadfiles.U_LINK}">
					<meta itemprop="description" content="${escape(downloadfiles.DESCRIPTION)}"/>
					# IF C_COMMENTS_ENABLED #
					<meta itemprop="discussionUrl" content="{downloadfiles.U_COMMENTS}">
					<meta itemprop="interactionCount" content="{downloadfiles.NUMBER_COMMENTS} UserComments">
					# ENDIF #
					
				</header>
				
				<div class="content">
					# IF C_CATEGORY_DISPLAYED_SUMMARY #
						<div class="more">
							<i class="fa fa-download" title="{downloadfiles.L_DOWNLOADED_TIMES}"></i>&nbsp;<span title="{downloadfiles.L_DOWNLOADED_TIMES}">{downloadfiles.NUMBER_DOWNLOADS}</span>
							# IF C_COMMENTS_ENABLED #
								&nbsp;|&nbsp;<i class="fa fa-comment" title="${LangLoader::get_message('comments', 'comments-common')}"></i># IF downloadfiles.C_COMMENTS # {downloadfiles.NUMBER_COMMENTS} # ENDIF # {downloadfiles.L_COMMENTS}
							# ENDIF #
							# IF downloadfiles.C_KEYWORDS #
								&nbsp;|&nbsp;<i title="${LangLoader::get_message('form.keywords', 'common')}" class="fa fa-tags"></i> 
								# START downloadfiles.keywords #
									<a itemprop="keywords" href="{downloadfiles.keywords.URL}">{downloadfiles.keywords.NAME}</a># IF downloadfiles.keywords.C_SEPARATOR #, # ENDIF #
								# END downloadfiles.keywords #
							# ENDIF #
							# IF C_NOTATION_ENABLED #
								<span class="float-right">{downloadfiles.STATIC_NOTATION}</span>
							# ENDIF #
							<div class="spacer">&nbsp;</div>
							<span>{downloadfiles.DESCRIPTION}# IF downloadfiles.C_READ_MORE #... <a href="{downloadfiles.U_LINK}">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #</span>
						</div>
					# ELSE #
						<div class="options infos">
							<div class="center">
								# IF downloadfiles.C_PICTURE #
									<img src="{downloadfiles.U_PICTURE}" alt="" itemprop="image" />
									<div class="spacer">&nbsp;</div>
								# ENDIF #
								# IF downloadfiles.C_VISIBLE #
									<a href="{downloadfiles.U_DOWNLOAD}" class="basic-button">
										<i class="fa fa-download"></i> {@download}
									</a>
									# IF IS_USER_CONNECTED #
									<a href="{downloadfiles.U_DEADLINK}" class="basic-button alt" title="${LangLoader::get_message('deadlink', 'common')}">
										<i class="fa fa-unlink"></i>
									</a>
									# ENDIF #
								# ENDIF #
							</div>
							<h6>{@file_infos}</h6>
							<span class="text-strong">${LangLoader::get_message('size', 'common')} : </span><span># IF downloadfiles.C_SIZE #{downloadfiles.SIZE}# ELSE #${LangLoader::get_message('unknown_size', 'common')}# ENDIF #</span><br/>
							<span class="text-strong">${LangLoader::get_message('form.date.creation', 'common')} : </span><span><time datetime="{downloadfiles.DATE_ISO8601}" itemprop="datePublished">{downloadfiles.DATE}</time></span><br/>
							# IF C_UPDATED_DATE #<span class="text-strong">${LangLoader::get_message('form.date.update', 'common')} : </span><span><time datetime="{downloadfiles.UPDATED_DATE_ISO8601}" itemprop="dateModified">{downloadfiles.UPDATED_DATE}</time></span><br/># ENDIF #
							<span class="text-strong">{@downloads_number} : </span><span>{downloadfiles.NUMBER_DOWNLOADS}</span><br/>
							# IF NOT C_CATEGORY #<span class="text-strong">${LangLoader::get_message('category', 'categories-common')} : </span><span><a itemprop="about" class="small" href="{downloadfiles.U_CATEGORY}">{downloadfiles.CATEGORY_NAME}</a></span><br/># ENDIF #
							# IF downloadfiles.C_KEYWORDS #
								<span class="text-strong">${LangLoader::get_message('form.keywords', 'common')} : </span>
								<span>
									# START downloadfiles.keywords #
										<a itemprop="keywords" class="small" href="{downloadfiles.keywords.URL}">{downloadfiles.keywords.NAME}</a># IF downloadfiles.keywords.C_SEPARATOR #, # ENDIF #
									# END downloadfiles.keywords #
								</span><br/>
							# ENDIF #
							# IF C_AUTHOR_DISPLAYED #
								<span class="text-strong">${LangLoader::get_message('author', 'common')} : </span>
								<span>
									# IF downloadfiles.C_CUSTOM_AUTHOR_DISPLAY_NAME #
										{downloadfiles.CUSTOM_AUTHOR_DISPLAY_NAME}
									# ELSE #
										# IF downloadfiles.C_AUTHOR_EXIST #<a itemprop="author" rel="author" class="small {downloadfiles.USER_LEVEL_CLASS}" href="{downloadfiles.U_AUTHOR_PROFILE}" # IF downloadfiles.C_USER_GROUP_COLOR # style="color:{downloadfiles.USER_GROUP_COLOR}" # ENDIF #>{downloadfiles.PSEUDO}</a># ELSE #{downloadfiles.PSEUDO}# ENDIF #  
									# ENDIF #
								</span><br/>
							# ENDIF #
							# IF C_COMMENTS_ENABLED #
								<span># IF downloadfiles.C_COMMENTS # {downloadfiles.NUMBER_COMMENTS} # ENDIF # {downloadfiles.L_COMMENTS}</span>
							# ENDIF #
							# IF downloadfiles.C_VISIBLE #
								# IF C_NOTATION_ENABLED #
									<div class="spacer">&nbsp;</div>
									<div class="center">{downloadfiles.NOTATION}</div>
								# ENDIF #
							# ENDIF #
						</div>
						
						<div itemprop="text">{downloadfiles.CONTENTS}</div>
					# ENDIF #
				</div>
				
				<footer></footer>
			</article>
			# END downloadfiles #
		# ENDIF #
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