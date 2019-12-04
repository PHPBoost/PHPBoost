# IF C_CATEGORIES #
	<section id="module-media">
		<header>
			<div class="align-right">
				<a href="${relative_url(SyndicationUrlBuilder::rss('media', ID_CAT))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss" aria-hidden="true"></i></a>
				# IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a># ENDIF #
			</div>
			<h1>
				${LangLoader::get_message('module_title', 'common', 'media')}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
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
							<a class="subcat-thumbnail" itemprop="about" href="{sub_categories_list.U_CATEGORY}">
								<img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="{sub_categories_list.CATEGORY_NAME}" />
							</a>
						# ENDIF #
						<a class="subcat-title" itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a>
						<span class="subcat-options" class="small">{sub_categories_list.MEDIAFILES_NUMBER}</span>
					</div>
				</div>
				# END sub_categories_list #
				<div class="spacer"></div>
			</div>
			# IF C_SUBCATEGORIES_PAGINATION #<span class="align-center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
		# ELSE #
			<div class="spacer"></div>
		# ENDIF #


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
				# IF C_DISPLAY_NOTATION #<option value="note"{SELECTED_NOTE}>{L_NOTE}</option># ENDIF #
				# IF C_DISPLAY_COMMENTS #<option value="com"{SELECTED_COM}>{L_COM}</option># ENDIF #
			</select>
			<select name="mode" id="mode" class="nav" onchange="change_order()">
				<option value="asc"{SELECTED_ASC}>{L_ASC}</option>
				<option value="desc"{SELECTED_DESC}>{L_DESC}</option>
			</select>
		</div>
		<div class="spacer"></div>

		<div class="content elements-container">
			# START file #
			<article id="article-media-{file.ID}" class="article-media several-items# IF file.C_NEW_CONTENT # new-content# ENDIF #">
				<header>
					<h2>
						<a href="{file.U_MEDIA_LINK}">{file.NAME}</a>
					</h2>
				</header>
				# IF C_MODO #
					<span class="controls">
						<a href="{file.U_ADMIN_UNVISIBLE_MEDIA}" aria-label="{L_UNAPROBED}"><i class="fa fa-eye-slash"></i></a>
						<a href="{file.U_ADMIN_EDIT_MEDIA}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
						<a href="{file.U_ADMIN_DELETE_MEDIA}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="fa fa-trash-alt"></i></a>
					</span>
				# ENDIF #

				<div class="more">
						<i class="fa fa-user" aria-hidden="true"></i> {file.AUTHOR} |
						<i class="fa fa-eye" aria-hidden="true"></i> {file.COUNT}
						# IF C_DISPLAY_COMMENTS #
							 | <i class="fa fa-comments" aria-hidden="true"></i> {file.U_COM_LINK}
						# ENDIF #
						# IF C_DISPLAY_NOTATION #
							 | {L_NOTE} {file.NOTE}
						# ENDIF #
				</div>
				<div class="content">
				# IF file.C_HAS_PICTURE #<a href="{file.U_MEDIA_LINK}"><img itemprop="thumbnailUrl" src="{file.PICTURE}" class="item-thumbnail" alt="{file.NAME}" /></a># ENDIF #
				# IF file.C_DESCRIPTION #
					<div itemprop="text">
					{file.DESCRIPTION}
					</div>
				# ENDIF #
				</div>
				<footer></footer>
			</article>
			# END file #
		</div>
		# ENDIF #

		# IF C_DISPLAY_NO_FILE_MSG #
		<div class="content">
			<div class="message-helper bgc notice">${LangLoader::get_message('no_item_now', 'common')}</div>
		</div>
		# ENDIF #

		<footer># IF C_PAGINATION #<span class="align-center"># INCLUDE PAGINATION #</span># ENDIF #</footer>
	</section>
# ENDIF #

# IF C_DISPLAY_MEDIA #
<section id="module-media">
	<header>
		<div class="align-right">
			${LangLoader::get_message('module_title', 'common', 'media')}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a># ENDIF #
		</div>
		<h1>
			{NAME}
		</h1>
	</header>
		<div id="article-media-{ID}" class="article-media# IF C_NEW_CONTENT # new-content# ENDIF #">
			<div class="controls">
				# IF C_DISPLAY_COMMENTS #
					<a href="{U_COM}"><i class="fa fa-comments"></i> {L_COM}</a>
				# ENDIF #
				# IF C_MODO #
					<a href="{U_UNVISIBLE_MEDIA}" aria-label="{L_UNAPROBED}"><i class="fa fa-eye-slash"></i></a>
					<a href="{U_EDIT_MEDIA}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
					<a href="{U_DELETE_MEDIA}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="fa fa-trash-alt"></i></a>
				# ENDIF #
			</div>
			<div class="content">
				<div class="options infos">
					<h6>{L_MEDIA_INFOS}</h6>
						<span class="infos-options"><span class="text-strong">{L_DATE} : </span>{DATE}</span>
						<span class="infos-options"><span class="text-strong">{L_BY} : </span>{BY}</span>
						<span class="infos-options"><span class="text-strong">{L_VIEWED} : </span>{HITS}</span>
					# IF C_DISPLAY_NOTATION #
					<div class="align-center text-strong">{KERNEL_NOTATION}</div>
					# ENDIF #
				</div>

				<div itemprop="text">
					{CONTENTS}
				</div>
				<div class="spacer"></div>
				${ContentSharingActionsMenuService::display()}

				<div class="media-content">
					# INCLUDE media_format #
				</div>

				# IF C_DISPLAY_COMMENTS #
				{COMMENTS}
				# ENDIF #
			</div>
		</div>
	<footer></footer>
</section>
# ENDIF #
