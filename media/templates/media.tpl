		# IF C_CATEGORIES #
			<section id="module-media">
				<header>
					<h1>
						<a href="${relative_url(SyndicationUrlBuilder::rss('media', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
						${LangLoader::get_message('module_title', 'common', 'media')}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF #
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
							<span class="small">{sub_categories_list.MEDIAFILES_NUMBER}</span>
						</div>
					</div>
					# END sub_categories_list #
					<div class="spacer"></div>
				</div>
				# IF C_SUBCATEGORIES_PAGINATION #<span class="center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
				# ELSE #
					<div class="spacer"></div>
				# ENDIF #
				
				<div class="content">
					# IF C_FILES #
						<div class="options" id="form">
							<script>
							<!--
							function change_order()
							{
								window.location = "{TARGET_ON_CHANGE_ORDER}sort=" + document.getElementById("sort").value + "&mode=" + document.getElementById("mode").value;
							}
							-->
							</script>
							{L_ORDER_BY}
							<select name="sort" id="sort" class="nav" onchange="change_order()">
								<option value="alpha"{SELECTED_ALPHA}>{L_ALPHA}</option>
								<option value="date"{SELECTED_DATE}>{L_DATE}</option>
								<option value="nbr"{SELECTED_NBR}>{L_NBR}</option>
								<option value="note"{SELECTED_NOTE}>{L_NOTE}</option>
								<option value="com"{SELECTED_COM}>{L_COM}</option>
							</select>
							<select name="mode" id="mode" class="nav" onchange="change_order()">
								<option value="asc"{SELECTED_ASC}>{L_ASC}</option>
								<option value="desc"{SELECTED_DESC}>{L_DESC}</option>
							</select>
						</div>
						<div class="spacer"></div>
	
						# START file #
							<article id="article-media-{file.ID}" class="article-media article-several block">
								<header>
									<h2>
										<a href="{file.U_MEDIA_LINK}">{file.NAME}</a>
										# IF C_MODO #
										<span class="actions">
											<a href="{file.U_ADMIN_UNVISIBLE_MEDIA}" class="fa fa-eye-slash" title="{L_UNAPROBED}"></a>
											<a href="{file.U_ADMIN_EDIT_MEDIA}" title="${LangLoader::get_message('edit', 'common')}" class="fa fa-edit"></a>
											<a href="{file.U_ADMIN_DELETE_MEDIA}" title="${LangLoader::get_message('delete', 'common')}" class="fa fa-delete" data-confirmation="delete-element"></a>
										</span>
										# ENDIF #
									</h2>
								</header>
								<div class="content">
									# IF file.C_DESCRIPTION #
										<div class="media-desc">
										{file.DESCRIPTION}
										</div>
									# ENDIF #
									<div class="spacer"></div>
									<div class="smaller">
										{L_BY} {file.POSTER}
										<br />
										{file.COUNT}
										<br />
										# IF C_DISPLAY_COMMENTS #
										{file.U_COM_LINK}
										<br />
										# ENDIF #
										# IF C_DISPLAY_NOTATION #
										{L_NOTE} {file.NOTE}
										# ENDIF #
									</div>
								</div>
								<footer></footer>
							</article>
						# END file #
					# ENDIF #
	
					# IF C_DISPLAY_NO_FILE_MSG #
						<div class="notice">${LangLoader::get_message('no_item_now', 'common')}</div>
					# ENDIF #
				</div>
				<footer># IF C_PAGINATION #<span class="center"># INCLUDE PAGINATION #</span># ENDIF #</footer>
			</section>
		# ENDIF #

		# IF C_DISPLAY_MEDIA #
		<section id="module-media">
			<header>
				<h1>
					${LangLoader::get_message('module_title', 'common', 'media')}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF #
				</h1>
			</header>
			<div class="content">
				<article id="article-media-{ID}" class="article-media">
					<header>
						<h2>
							{NAME} 
							<span class="actions">
								# IF C_DISPLAY_COMMENTS #
									<a href="{U_COM}"><i class="fa fa-comments-o"></i> {L_COM}</a>
								# ENDIF #
								# IF C_MODO #
									<a href="{U_UNVISIBLE_MEDIA}" class="fa fa-eye-slash" title="{L_UNAPROBED}"></a>
									<a href="{U_EDIT_MEDIA}" title="${LangLoader::get_message('edit', 'common')}" class="fa fa-edit"></a>
									<a href="{U_DELETE_MEDIA}" title="${LangLoader::get_message('delete', 'common')}" class="fa fa-delete" data-confirmation="delete-element"></a>
								# ENDIF #
							</span>
						</h2>
					</header>
					<div class="content">
					
						<div class="options infos">
							<h6>{L_MEDIA_INFOS}</h6>
								<span class="text-strong">{L_DATE} : </span><span>{DATE}</span><br/>
								<span class="text-strong">{L_BY} : </span><span>{BY}</span><br/>
								<span class="text-strong">{L_VIEWED} : </span><span>{HITS}</span><br/>
							# IF C_DISPLAY_NOTATION #
							<div class="center text-strong">{KERNEL_NOTATION}</div>
							# ENDIF #
						</div>
						
						<div class="media-desc">
							{CONTENTS}
						</div>
						<div class="spacer"></div>
						
						<div class="media-content">
							# INCLUDE media_format #
						</div>
		
						{COMMENTS}
					</div>
					<footer></footer>
				</article>
			</div>
			<footer></footer>
		</section>
		# ENDIF #
