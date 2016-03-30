<script>
<!--
	$(document).ready(function(){
		
		/* Tableaux contenant les numeros associes a chaque type de categories */
		var updates 		= [36, 39, 41, 45];
		var prev_releases 	= [27, 35, 40];
		var last_release	= [44];
		var tools			= [26];
		var modules 		= [29, 34, 38, 43, 47];
		var templates 		= [30, 37, 42, 46];

		/* Images que l on souhaite afficher en grand */
		var img_modules  = "{PATH_TO_ROOT}/templates/{THEME}/theme/images/modules.jpg";
		var img_releases = "{PATH_TO_ROOT}/templates/{THEME}/theme/images/modules.jpg";
		var img_updates  = "{PATH_TO_ROOT}/templates/{THEME}/theme/images/modules.jpg";
		var img_default  = "{PATH_TO_ROOT}/templates/{THEME}/theme/images/transparent.gif";

		/* On parcours toutes les elements avec la class article-download */
		$( ".article-download" ).each(function(){ 
			
			var cats_id = 0;

			/* On recupere l id de l element pere */
			var article_id 	= parseInt( $(this).attr('id').replace("article-download-", "") );

			/* On recupere le numero de categorie parmis les classes */
			$.each( $(this).attr('class').split(" "), function( index, value ) {
				if ( value.search("article-download-cats") == 0 )
				cats_id = parseInt( value.replace("article-download-cats-", "") );
			});	

			$(this).removeClass("article-download-cats-" + cats_id);

			/* On applique les modifications en fonction du type de categories rencontre. */
			if ( $.inArray(cats_id, updates) != -1 )
			{
				$(this).addClass("article-download-cats-updates");
				$("#pbt-img-" + article_id ).addClass("pbt-img-updates");
				$("#pbt-img-" + article_id ).attr("src", img_updates);
				$("#pbt-icon-" + article_id ).addClass("pbt-icon-updates");
				$("#pbt-logo-" + article_id ).addClass("pbt-logo-updates");
				$("#pbt-logo-" + article_id ).attr("src", img_default);
			}

			if (( $.inArray(cats_id, prev_releases) != -1 ) || ( $.inArray(cats_id, last_release) != -1 ) || ( $.inArray(cats_id, tools) != -1 ))
			{
				$(this).addClass("article-download-cats-release");
				$("#pbt-img-" + article_id ).addClass("pbt-img-releases");
				$("#pbt-img-" + article_id ).attr("src", img_releases);
				$("#pbt-icon-" + article_id ).addClass("pbt-icon-releases");
				$("#pbt-logo-" + article_id ).addClass("pbt-logo-releases");
				$("#pbt-logo-" + article_id ).attr("src", img_default);
			}

			if ( $.inArray(cats_id, modules) != -1 )
			{
				$(this).addClass("article-download-cats-modules");
				$("#pbt-img-" + article_id ).addClass("pbt-img-modules");
				$("#pbt-img-" + article_id ).attr("src", img_modules);
				$("#pbt-icon-" + article_id ).addClass("pbt-icon-modules");
				$("#pbt-logo-" + article_id ).addClass("pbt-logo-modules");
			}

			if ( $.inArray(cats_id, templates) != -1 )
			{
				$(this).addClass("article-download-templates");
				$("#pbt-img-" + article_id ).addClass("pbt-img-templates");
				$("#pbt-icon-" + article_id ).addClass("pbt-icon-templates");
				$("#pbt-logo-" + article_id ).remove();
			}

		});
	
		/* On parcours toutes les elements avec la class download-subcat-element */
		$( ".download-subcat-element" ).each(function(){ 
			
			/* On recupere l id de l element pere */
			var subcat_id = parseInt( $(this).attr('id').replace("download-subcat-", "") );

			$("#subcat-img-" + subcat_id).attr("src", img_default);

			/* On applique les modifications en fonction du type de categories rencontre. */
			if ( $.inArray(subcat_id, updates) != -1 ) 			{ $(this).addClass("download-subcat-updates"); 			}
			if ( $.inArray(subcat_id, prev_releases) != -1 ) 	{ $(this).addClass("download-subcat-prev-releases"); 	}
			if ( $.inArray(subcat_id, last_release) != -1 ) 	{ $(this).addClass("download-subcat-last-release"); 	}
			if ( $.inArray(subcat_id, tools) != -1 ) 			{ $(this).addClass("download-subcat-tools"); 			}
			if ( $.inArray(subcat_id, modules) != -1 ) 			{ $(this).addClass("download-subcat-modules"); 			}
			if ( $.inArray(subcat_id, templates) != -1 ) 		{ $(this).addClass("download-subcat-templates"); 		}

		});
	});
-->
</script>

# IF C_ROOT_CATEGORY #
	${resources('PHPBoostOfficial/common')}
	<script>
	<!--
	function toggle_root_cat_display() {
		if (jQuery('.content').is(":visible"))
		{
			jQuery('.content').hide(400);
			# IF C_CATEGORY_DESCRIPTION #jQuery('.cat-description').show(200);# ENDIF #
			jQuery('.root-categories-container').show(200);
			jQuery('#display-tree-txt').html(${escapejs(LangLoader::get_message('download.display_root_cat', 'common', 'PHPBoostOfficial'))});
		} else {
			jQuery('.root-categories-container').hide(400);
			# IF C_CATEGORY_DESCRIPTION #jQuery('.cat-description').hide(400);# ENDIF #
			jQuery('.content').show(200);
			jQuery('#display-tree-txt').html(${escapejs(LangLoader::get_message('download.display_tree', 'common', 'PHPBoostOfficial'))});
		}
		jQuery('#display-tree').parent().toggleClass("pbt-button-cat");
	};
	-->
	</script>
	
	<section id="module-download-pbt">
		<header>
			<h1>
				<a href="${relative_url(SyndicationUrlBuilder::rss('download', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
				${LangLoader::get_message('module_title', 'common', 'download')} # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF #
			</h1>
			# IF C_CATEGORY_DESCRIPTION #
				<div class="cat-description" style="display: none;">
					{CATEGORY_DESCRIPTION}
				</div>
			# ENDIF #
		</header>
		
		<div class="root-categories-container" style="display: none;">
			# IF C_SUB_CATEGORIES #
			<div class="subcat-container">
				# START sub_categories_list #
				<div id="download-subcat-{sub_categories_list.CATEGORY_ID}" class="subcat-element download-subcat-element">
					<a itemprop="about" href="{sub_categories_list.U_CATEGORY}" class="subcat-content">
						<span class="subcat-img-container">
							<img itemprop="thumbnailUrl" src="# IF sub_categories_list.C_CATEGORY_IMAGE #{sub_categories_list.CATEGORY_IMAGE}# ELSE #{PATH_TO_ROOT}/templates/{THEME}/theme/images/transparent.gif# ENDIF #" alt="{sub_categories_list.CATEGORY_NAME}" class="subcat-img" />
						</span>
						<span class="subcat-title">{sub_categories_list.CATEGORY_NAME}</span>
						<span class="subcat-more">{sub_categories_list.DOWNLOADFILES_NUMBER} # IF sub_categories_list.C_MORE_THAN_ONE_DOWNLOADFILE #${TextHelper::lowercase_first(LangLoader::get_message('files', 'common', 'download'))}# ELSE #${TextHelper::lowercase_first(LangLoader::get_message('file', 'common', 'download'))}# ENDIF #</span>
					</a>
				</div>
				# END sub_categories_list #
				<div class="spacer"></div>
			</div>
			# IF C_SUBCATEGORIES_PAGINATION #<span class="center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
			# ELSE #
				# IF NOT C_CATEGORY_DISPLAYED_TABLE #<div class="spacer"></div># ENDIF #
			# ENDIF #
		</div>
		
		<div class="content">
			# IF C_DESCRIPTION #
				<!-- {DESCRIPTION} -->
			# ENDIF #
			<div class="pbt-header">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/logo.png" alt="pbt-header-img" class="pbt-header-img" />
				<div class="pbt-content">
					<p class="pbt-title">{@download.header.title}</p>
					<span class="pbt-desc">{@download.header.description}</span>
				</div>
				<div class="spacer"></div>
			</div>

			<div class="pbt-explain">
				{@download.page_content.title} :
				<br /><br />
				<ul>
					<li>{@download.page_content.last_stable_version}</li>
					<li>{@download.page_content.previous_version}</li>
					<li>{@download.page_content.updates}</li>
					<li>{@download.page_content.updates_scripts}</li>
				</ul>
			</div>

			<article class="pbt-block">
				<header>
					<h2>{@download} {@download.last_major_version_number} - {@download.last_version_name}</h2>
					<p class="pbt-desc">
						{@download.last_version.description}
					</p>
				</header>
				
				<div class="pbt-content">

					<article id="pbt-rev-last" class="article-download article-several article-download-release article-download-pbt-rev" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
						<header>
							<h3>
								<a href="{@download.last_version_download_link}" itemprop="name">{@download} {@download.phpboost_last_major_version_number}</a>
							</h3>
							<meta itemprop="url" content="{@download.last_version_download_link}">
							<meta itemprop="description" content="${escape(@download.phpboost_last_major_version_number)}"/>
						</header>
						
						<div class="content">
							<a href="{@download.last_version_download_link}" itemprop="name">
								<div class="pbt-element-img-container">
									<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/modules.jpg" alt="pbt-img-releases" id="pbt-img-001" class="pbt-img pbt-img-releases" itemprop="image" />
									<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/transparent.gif" alt="pbt-icon-releases" id="pbt-icon-001" class="pbt-icon pbt-icon-releases" />
									<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/logo.png" alt="pbt-logo-releases" id="pbt-logo-001" class="pbt-logo pbt-logo-releases" />
								</div>
								<div class="pbt-element-info-container">
									<p class="pbt-info-title">{@download.phpboost_last_complete_major_version_number}</p>
									<p class="pbt-info-desc">{@download.last_minimal_php_version} | .zip</p>
									<p class="pbt-info-author">${LangLoader::get_message('by', 'common')} PHPBoost</p>
								</div>
							</a>
						</div>
							
						<footer></footer>
					</article>
					
					<a href="{@download.last_version_download_link}" itemprop="name" class="download-pdk-last-link hidden-large-screens">{@download.pdk_version_txt}</a>

					<article id="pdk-last" class="article-download article-several article-download-release article-download-pdk hidden-small-screens" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
						<header>
							<h3>
								<a href="{@download.last_version_pdk_link}" itemprop="name">{@download} {@download.last_major_version_number}</a>
							</h3>
							<meta itemprop="url" content="{@download.last_version_pdk_link}">
							<meta itemprop="description" content="${escape(@download.last_complete_version_number)}"/>
						</header>
						
						<div class="content">
							<a href="{@download.last_version_pdk_link}" itemprop="name">
								<div class="pbt-element-img-container">
									<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/modules.jpg" alt="pbt-img-releases" id="pbt-img-002" class="pbt-img pbt-img-releases" itemprop="image" />
									<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/transparent.gif" alt="pbt-icon-releases" id="pbt-icon-002" class="pbt-icon pbt-icon-releases" />
									<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/transparent.gif" alt="pbt-logo-pdk" id="pbt-logo-002" class="pbt-logo pbt-logo-pdk" />
								</div>
								<div class="pbt-element-info-container">
									<p class="pbt-info-title">{@download.last_version_pdk}</p>
									<p class="pbt-info-desc">{@download.pdk_version}</p>
									<p class="pbt-info-author">${LangLoader::get_message('by', 'common')} PHPBoost</p>
								</div>
							</a>
						</div>
							
						<footer></footer>
					</article>
				</div>

				<div class="pbt-content">
					<div class="subcat-container">
					
						<div id="subcat-001" class="subcat-element download-subcat-element download-subcat-updates" >
							<a itemprop="about" href="{@download.last_version_updates_cat_link}" class="subcat-content">
								<span class="subcat-img-container">
									<img itemprop="thumbnailUrl" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/transparent.gif" alt="{@download.updates}" id="subcat-img-001" class="subcat-img" />
								</span>
								<span class="subcat-title">{@download.updates}</span>
								<span class="subcat-more">{@download.updates.description}</span>
							</a>
						</div>
					
						<div id="subcat-002" class="subcat-element download-subcat-element download-subcat-modules" >
							<a itemprop="about" href="{@download.last_version_modules_cat_link}" class="subcat-content">
								<span class="subcat-img-container">
									<img itemprop="thumbnailUrl" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/transparent.gif" alt="{@download.compatible_modules}" id="subcat-img-002" class="subcat-img" />
								</span>
								<span class="subcat-title">{@download.compatible_modules}</span>
								<span class="subcat-more">{@download.compatible_modules.description}</span>
							</a>
						</div>

						<div id="subcat-003" class="subcat-element download-subcat-element download-subcat-templates" >
							<a itemprop="about" href="{@download.last_version_themes_cat_link}" class="subcat-content">
								<span class="subcat-img-container">
									<img itemprop="thumbnailUrl" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/transparent.gif" alt="{@download.compatible_themes}" id="subcat-img-003" class="subcat-img" />
								</span>
								<span class="subcat-title">{@download.compatible_themes}</span>
								<span class="subcat-more">{@download.compatible_themes.description}</span>
							</a>
						</div>

					</div>
					
				</div>
												
			</article>

			<hr style="margin:60px auto 60px auto;" />

			<article class="pbt-block">
				<header>
					<h2>{@download} {@download.previous_major_version_number} - {@download.previous_version_name}</h2>
					<p class="pbt-desc">
						{@download.previous_version.description}
					</p>
				</header>

				<div class="pbt-content">

					<article id="pbt-rev-previous" class="article-download article-several article-download-release article-download-pbt-rev" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
						<header>
							<h3>
								<a href="{@download.previous_version_download_link}" itemprop="name">{@download} {@download.previous_major_version_number}</a>
							</h3>
							<meta itemprop="url" content="{@download.previous_version_download_link}">
							<meta itemprop="description" content="${escape(@download.previous_version_download_link)}"/>
						</header>
						
						<div class="content">
							<a href="{@download.previous_version_download_link}" itemprop="name">
								<div class="pbt-element-img-container">
									<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/modules.jpg" alt="pbt-img-releases" id="pbt-img-011" class="pbt-img pbt-img-releases" itemprop="image" />
									<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/transparent.gif" alt="pbt-icon-releases" id="pbt-icon-011" class="pbt-icon pbt-icon-releases" />
									<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/logo.png" alt="pbt-logo-releases" id="pbt-logo-011" class="pbt-logo pbt-logo-releases" />
								</div>
								<div class="pbt-element-info-container">
									<p class="pbt-info-title">{@download.phpboost_previous_complete_major_version_number}</p>
									<p class="pbt-info-desc">PHP {@download.previous_minimal_php_version} | .zip</p>
									<p class="pbt-info-author">${LangLoader::get_message('by', 'common')} PHPBoost</p>
								</div>
							</a>
						</div>
							
						<footer></footer>
					</article>
					
					<a href="{@download.previous_version_pdk_link}" itemprop="name" class="download-pdk-last-link hidden-large-screens">{@download.pdk_version_txt}</a>

					<article id="pdk-preview" class="article-download article-several article-download-release article-download-pdk hidden-small-screens" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
						<header>
							<h3>
								<a href="{@download.previous_version_pdk_link}" itemprop="name">{@download} {@download.pdk_version}</a>
							</h3>
							<meta itemprop="url" content="{@download.previous_version_pdk_link}">
							<meta itemprop="description" content="${escape(@download.previous_version_pdk_link)}"/>
						</header>
						
						<div class="content">
							<a href="{@download.previous_version_pdk_link}" itemprop="name">
								<div class="pbt-element-img-container">
									<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/modules.jpg" alt="pbt-img-releases" id="pbt-img-012" class="pbt-img pbt-img-releases" itemprop="image" />
									<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/transparent.gif" alt="pbt-icon-releases" id="pbt-icon-012" class="pbt-icon pbt-icon-releases" />
									<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/transparent.gif" alt="pbt-logo-pdk" id="pbt-logo-012" class="pbt-logo pbt-logo-pdk" />
								</div>
								<div class="pbt-element-info-container">
									<p class="pbt-info-title">{@download.previous_version_pdk}</p>
									<p class="pbt-info-desc">{@download.pdk_version}</p>
									<p class="pbt-info-author">${LangLoader::get_message('by', 'common')} PHPBoost</p>
								</div>
							</a>
						</div>
							
						<footer></footer>
					</article>

				</div>

				<div class="pbt-content">
					<div class="subcat-container">
					
						<div id="subcat-004" class="subcat-element download-subcat-element download-subcat-updates" >
							<a itemprop="about" href="{@download.previous_version_updates_cat_link}" class="subcat-content">
								<span class="subcat-img-container">
									<img itemprop="thumbnailUrl" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/transparent.gif" alt="{@download.updates}" id="subcat-img-011" class="subcat-img" />
								</span>
								<span class="subcat-title">{@download.updates}</span>
								<span class="subcat-more">{@download.updates.description}</span>
							</a>
						</div>
					
						<div id="subcat-005" class="subcat-element download-subcat-element download-subcat-modules" >
							<a itemprop="about" href="{@download.previous_version_modules_cat_link}" class="subcat-content">
								<span class="subcat-img-container">
									<img itemprop="thumbnailUrl" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/transparent.gif" alt="{@download.compatible_modules}" id="subcat-img-012" class="subcat-img" />
								</span>
								<span class="subcat-title">{@download.compatible_modules}</span>
								<span class="subcat-more">{@download.compatible_modules.description}</span>
							</a>
						</div>
					
						<div id="subcat-006" class="subcat-element download-subcat-element download-subcat-templates" >
							<a itemprop="about" href="{@download.previous_version_themes_cat_link}" class="subcat-content">
								<span class="subcat-img-container">
									<img itemprop="thumbnailUrl" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/transparent.gif" alt="{@download.compatible_themes}" id="subcat-img-013" class="subcat-img" />
								</span>
								<span class="subcat-title">{@download.compatible_themes}</span>
								<span class="subcat-more">{@download.compatible_themes.description}</span>
							</a>
						</div>

					</div>
					
				</div>
			</article>
		</div>
		
		<hr style="margin:20px auto 30px auto;" />
			
		<div class="pbt-button pbt-button-gray center">
			<a id="display-tree" href="" onclick="toggle_root_cat_display();return false;" class="pbt-button-a">
				<i class="fa fa-folder"></i>
				<span id="display-tree-txt" >{@download.display_tree}</span>
			</a>
		</div>
		<footer># IF C_PAGINATION #<div class="center"># INCLUDE PAGINATION #</div># ENDIF #</footer>
	</section>
# ELSE #
<section id="module-download-several">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('download', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			# IF C_PENDING #{@download.pending}# ELSE #{@module_title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF # # IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF ## ENDIF #
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
		<div id="download-subcat-{sub_categories_list.CATEGORY_ID}" class="subcat-element download-subcat-element">
			<a itemprop="about" href="{sub_categories_list.U_CATEGORY}" class="subcat-content">
				<span class="subcat-img-container">
					<img itemprop="thumbnailUrl" src="# IF sub_categories_list.C_CATEGORY_IMAGE #{sub_categories_list.CATEGORY_IMAGE}# ELSE #{PATH_TO_ROOT}/templates/{THEME}/theme/images/transparent.gif# ENDIF #" alt="{sub_categories_list.CATEGORY_NAME}" id="subcat-img-{sub_categories_list.CATEGORY_ID}" class="subcat-img" />
				</span>
				<span class="subcat-title">{sub_categories_list.CATEGORY_NAME}</span>
				<span class="subcat-more">{sub_categories_list.DOWNLOADFILES_NUMBER} # IF sub_categories_list.C_MORE_THAN_ONE_DOWNLOADFILE #${TextHelper::lowercase_first(LangLoader::get_message('files', 'common', 'download'))}# ELSE #${TextHelper::lowercase_first(LangLoader::get_message('file', 'common', 'download'))}# ENDIF #</span>
			</a>
		</div>
		# END sub_categories_list #
		<div class="spacer"></div>
	</div>
	# IF C_SUBCATEGORIES_PAGINATION #<span class="center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
	# ELSE #
		# IF NOT C_CATEGORY_DISPLAYED_TABLE #<div class="spacer"></div># ENDIF #
	# ENDIF #
	
	<div class="content">
	# IF C_FILES #
		# IF C_MORE_THAN_ONE_FILE #
			# INCLUDE SORT_FORM #
			<div class="spacer"></div>
		# ENDIF #

		# START downloadfiles #
		<article id="article-download-{downloadfiles.ID}" class="article-download article-several article-download-cats-{downloadfiles.CATEGORY_ID}" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
			<header>
				<h2>
					<a href="{downloadfiles.U_LINK}" itemprop="name">{downloadfiles.NAME}</a>
				</h2>
					
				<meta itemprop="url" content="{downloadfiles.U_LINK}">
				<meta itemprop="description" content="${escape(downloadfiles.DESCRIPTION)}"/>
			</header>
			
			<div class="content">
				<span class="actions">
					# IF downloadfiles.C_EDIT #<a href="{downloadfiles.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a># ENDIF #
					# IF downloadfiles.C_DELETE #<a href="{downloadfiles.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a># ENDIF #
				</span>
				<a href="{downloadfiles.U_LINK}" itemprop="name">
					<div class="pbt-element-img-container">
						<img src="{downloadfiles.U_PICTURE}" alt="pbt-img" id="pbt-img-{downloadfiles.ID}" class="pbt-img" itemprop="image" />
						<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/transparent.gif" alt="pbt-icon" id="pbt-icon-{downloadfiles.ID}" class="pbt-icon" />
						<img src="{downloadfiles.U_PICTURE}" alt="pbt-logo" id="pbt-logo-{downloadfiles.ID}" class="pbt-logo" />
					</div>
					<div class="pbt-element-info-container">
						<p class="pbt-info-title">{downloadfiles.NAME}</p>
						<p class="pbt-info-desc">{downloadfiles.DESCRIPTION}</p>
						# IF C_AUTHOR_DISPLAYED #
							<p class="pbt-info-author">${LangLoader::get_message('by', 'common')} 
							# IF downloadfiles.C_CUSTOM_AUTHOR_DISPLAY_NAME #
								{downloadfiles.CUSTOM_AUTHOR_DISPLAY_NAME}
							# ELSE #
								{downloadfiles.PSEUDO}
							# ENDIF #
							</p>
						# ENDIF #
					</div>
				</a>
			</div>
				
			<footer></footer>
		</article>
		# END downloadfiles #
		<div class="spacer"></div>
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
# ENDIF #
