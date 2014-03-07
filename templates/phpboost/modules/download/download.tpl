# IF C_ROOT_AND_EXPLORE #
	<section>
		<header>
			<h1>
				<a href="${relative_url(SyndicationUrlBuilder::rss('download',IDCAT))}" class="fa fa-syndication" title="${LangLoader::get_message('syndication', 'main')}"></a>
				{TITLE}
				# IF C_ADMIN #
				<span class="actions">
					<a href="{U_ADMIN_CAT}" title="${LangLoader::get_message('edit', 'main')}" class="fa fa-edit"></a>
				</span>
				# END IF #
			</h1>
		</header>
		<div class="content">
			# IF C_DESCRIPTION #
				<!-- {DESCRIPTION} -->
			# ENDIF #
			<div class="pbt-entete">
				<img class="pbt-entete-img" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/logo.png" alt="" />
				<div class="pbt-content">
					<p class="pbt-title">Télécharger PHPBoost</p>
					<span class="pbt-desc">Bienvenue sur la page de téléchargement de PHPBoost.</span>
				</div>
			</div>
			<hr style="margin:25px 0px;" />
			Vous trouverez sur cette page :
			<br /><br />
			<ul>
				<li>La dernière version stable : PHPBoost 4.0 Sirocco et 4.0 Sirocco PDK (Version destinée aux développeurs)</li>
				<li>PHPBoost 3.0 Tornade</li>
				<li>Mise à jour des versions 3.0 et 4.0</li>
				<li>Migration de PHPBoost 3.0 à PHPBoost 4.0</li>
			</ul>
			<hr style="margin:25px auto 25px auto;" />
			
			<article class="block">
				<header>
					<h1>Télécharger PHPBoost 4.0 - Sirocco</h1>
					<p class="pbt-desc">
						La version stable de PHPBoost. A utiliser pour bénéficier de toutes les dernières fonctionnalités implantées.
					</p>
				</header>
					
				<div class="pbt-button-container">
					<div class="pbt-button pbt-button-blue">
							<a href="{PATH_TO_ROOT}/download/file-229+phpboost-4-0.php" class="pbt-button-a">
								<div class="pbt-custom-img pbt-custom-img-phpboost"></div>
								<p class="pbt-button-title">Télécharger PHPBoost 4.0</p>
								<p class="pbt-button-com">Rev : 4.0.4 | Req : PHP 5.1.2 | .zip </p>
							</a>
						</div>
						<div class="pbt-button pbt-button-green">
							<a href="{PATH_TO_ROOT}/download/category-36+mises-jour.php" class="pbt-button-a">
								<div class="pbt-custom-img pbt-custom-img-phpboost"></div>
								<p class="pbt-button-title">Mises à jour</p>
								<p class="pbt-button-com pbt-button-com_green">Mise à jour et migration</p>
							</a>
						</div>
					</div>
					<div class="pbt-dev_container">
						<a href="{PATH_TO_ROOT}/download/download-232+phpboost-4-0-pdk.php" class="pbt-dev">Télécharger la version pour développeurs (PDK)</a>
					</div>
					
					<hr style="margin:10px auto 0px auto;" />
					
					<div class="pbt-custom-content">
						<div style="width: 90%;margin:auto;">
							<div style="float:left; width: 47%;padding-right:15px;">
								<div class="pbt-custom-img pbt-custom-img-modules"></div>
								<h2 class="title pbt-custom-subtitle" >
									<a href="{PATH_TO_ROOT}/download/category-38+modules.php">Modules compatibles</a>
								</h2>
								<p class="pbt-custom-desc">Donnez de nouvelles fonctionnalités à votre site.</p>
							</div>
							<div style="float:left; width: 47%;padding-left:15px;">
								<div class="pbt-custom-img pbt-custom-img-themes"></div>
								<h2 class="title pbt-custom-subtitle" >
									<a href="{PATH_TO_ROOT}/download/category-37+themes.php">Thèmes compatibles</a>
								</h2>
								<p class="pbt-custom-desc">Trouvez la bonne entité graphique pour votre site.</p>
							</div>
						</div>
						
						<div class="spacer"></div>
					</div>										
				</article>
														
				<article class="block">
				
					<header>
						<h1>Télécharger PHPBoost 3.0 - Tornade</h1>
						<p class="pbt-desc">
							Pour les nostalgiques, ou pour les personnes ayant besoin de réparer une version 3.0 encore en production.
						</p>
					</header>
					
					<div class="pbt-button-container">
						<div class="pbt-button pbt-button-blue">
							<a href="{PATH_TO_ROOT}/download/file-111+phpboost-3-0-complete.php" class="pbt-button-a">
								<div class="pbt-custom-img pbt-custom-img-phpboost"></div>
								<p class="pbt-button-title">Télécharger PHPBoost 3.0</p>
								<p class="pbt-button-com">Rev : 3.0.11 | Req : PHP 4.0.1 | .zip </p>
							</a>
						</div>
						<div class="pbt-button pbt-button-green">
							<a href="{PATH_TO_ROOT}/download/category-39+mises-jour.php" class="pbt-button-a">
								<div class="pbt-custom-img pbt-custom-img-phpboost"></div>
								<p class="pbt-button-title">Mises à jour</p>
								<p class="pbt-button-com pbt-button-com_green">Mise à jour et migration</p>
							</a>
						</div>
					</div>						
					
					<hr style="margin:25px auto 0px auto;" />
					
					<div class="pbt-custom-content">
						<div style="width: 90%;margin:auto;">
							<div style="float:left; width: 47%;padding-right:15px;">
								<div class="pbt-custom-img pbt-custom-img-modules"></div>
								<h2 class="title pbt-custom-subtitle" >
									<a href="{PATH_TO_ROOT}/download/category-29+modules.php">Modules compatibles</a>
								</h2>
								<p class="pbt-custom-desc">
									Donnez de nouvelles fonctionnalités à votre site.
								</p>
							</div>
							<div style="float:left; width: 47%;padding-left:15px;">
								<div class="pbt-custom-img pbt-custom-img-themes"></div>
								<h2 class="title pbt-custom-subtitle" >
									<a href="{PATH_TO_ROOT}/download/category-30+themes.php">Thèmes compatibles</a>
								</h2>
								<p class="pbt-custom-desc">
									Trouvez la bonne entité graphique pour votre site.
								</p>
							</div>
						</div>
						
						<div class="spacer"></div>
					</div>										
				</article>
				
				<hr style="margin:20px auto 30px auto;" />
				
				<div style="text-align:center;">	
					<a href="{PATH_TO_ROOT}/download/download.php?explore=1" class="pbt-button-a">
						<button class="big"><i class="fa fa-folder"></i> Parcourir l'arborescence</button>
					</a>
				</div>
		</div>
		<footer># IF C_PAGINATION #<div class="center"># INCLUDE PAGINATION #</div># ENDIF #</footer>
	</section>
# ELSE #
		# IF C_DOWNLOAD_CAT #
		<section>
			<header>
				<h1>
					<a href="${relative_url(SyndicationUrlBuilder::rss('download',IDCAT))}" class="fa fa-syndication" title="${LangLoader::get_message('syndication', 'main')}"></a>
					{TITLE}
					# IF C_ADMIN #
					<span class="actions">
						<a href="{U_ADMIN_CAT}" title="${LangLoader::get_message('edit', 'main')}" class="fa fa-edit"></a>
					</span>
					# END IF #
				</h1>
			</header>
			<div class="content">
				# IF C_DESCRIPTION #
					{DESCRIPTION}
					<hr style="margin-top:25px;" />
				# ENDIF #
				
				# IF C_SUB_CATS #
					# START row #
						# START row.list_cats #
							<div style="float:left;width:{row.list_cats.WIDTH}%;text-align:center;margin:20px 0px;">
								# IF row.list_cats.C_CAT_IMG #
									<a href="{row.list_cats.U_CAT}" title="{row.list_cats.IMG_NAME}"><img src="{row.list_cats.SRC}" alt="{row.list_cats.IMG_NAME}" /></a>
									<br />
								# ENDIF #
								<a href="{row.list_cats.U_CAT}">{row.list_cats.NAME}</a>
								
								# IF C_ADMIN #
								<a href="{row.list_cats.U_ADMIN_CAT}" title="${LangLoader::get_message('edit', 'main')}" class="fa fa-edit"></a>
								# ENDIF #
								<div class="smaller">
									{row.list_cats.NUM_FILES}
								</div>
							</div>
						# END row.list_cats #
						<div class="spacer">&nbsp;</div>
					# END row #
					<hr style="margin-bottom:25px;" />
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
							<option value="size"{SELECTED_SIZE}>{L_SIZE}</option>
							<option value="date"{SELECTED_DATE}>{L_DATE}</option>
							<option value="hits"{SELECTED_HITS}>{L_POPULARITY}</option>
							<option value="note"{SELECTED_NOTE}>{L_NOTE}</option>
						</select>
						<select name="mode" id="mode" class="nav" onchange="change_order()">
							<option value="asc"{SELECTED_ASC}>{L_ASC}</option>
							<option value="desc"{SELECTED_DESC}>{L_DESC}</option>
						</select>
					</div>
					<div class="spacer">&nbsp;</div>
					
					# START file #
						<article class="block" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
							<header>
								<h1>
									<a href="{file.U_DOWNLOAD_LINK}" itemprop="name">{file.NAME}</a>
									# IF C_ADMIN #
									<span class="actions">
										<a href="{file.U_ADMIN_EDIT_FILE}" title="${LangLoader::get_message('edit', 'main')}" class="fa fa-edit"></a>
										<a href="{file.U_ADMIN_DELETE_FILE}" title="${LangLoader::get_message('delete', 'main')}" class="fa fa-delete" data-confirmation="delete-element"></a>
									</span>
									# ENDIF #
								</h1>
							</header>
							<div class="content">
								# IF file.C_IMG #
									<div class="float-right">
										<a href="{file.U_DOWNLOAD_LINK}">
											<img src="{file.IMG}" alt="{file.IMG_NAME}" itemprop="image" />
										</a>
									</div>
								# ENDIF #
								# IF file.C_DESCRIPTION #
									<p itemprop="text">
									{file.DESCRIPTION}
									</p>
								# ENDIF #
								<div class="smaller">
									<span itemprop="dateCreated"> 
										{file.DATE} 
									<span> 
									<br />
									{file.COUNT_DL}
									<br />
									{file.U_COM_LINK}
									<br />
									{L_NOTE} {file.NOTE}
								</div>
								<div class="spacer"></div>
							</div>
							<footer></footer>
						</article>
					# END file #
				# ENDIF #
				
				# IF C_NO_FILE #
					<div class="notice">{L_NO_FILE_THIS_CATEGORY}</div>
				# ENDIF #
				<div class="spacer"></div>
			</div>
			<footer># IF C_PAGINATION #<div class="center"># INCLUDE PAGINATION #</div># ENDIF #</footer>
		</section>
		# ENDIF #
		
		# IF C_DISPLAY_DOWNLOAD #
		<article itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
			<header>
				<h1 itemprop="name">
					{NAME}
					<span class="actions">
						{U_COM}
						# IF C_EDIT_AUTH #
							<a href="{U_EDIT_FILE}" title="${LangLoader::get_message('edit', 'main')}" class="fa fa-edit"></a>
							<a href="{U_DELETE_FILE}" title="${LangLoader::get_message('delete', 'main')}" class="fa fa-delete" data-confirmation="delete-element"></a>
						# ENDIF #
					</span>
				</h1>
				
			</header>
			<div class="content">
				<div class="options infos">
					<div class="center">
						# IF C_IMG #
							<img src="{U_IMG}" alt="{IMAGE_ALT}" itemprop="image"/>
							<br /><br />
						# ENDIF #
						<a href="{U_DOWNLOAD_FILE}" class="basic-button">
							<i class="fa fa-download"></i> {L_DOWNLOAD_FILE}
						</a>
						# IF IS_USER_CONNECTED #
						<a href="{U_DEADLINK}" class="basic-button alt" title="{L_DEADLINK}">
							<i class="fa fa-unlink"></i>
						</a>
						# ENDIF #
					</div>
					<h6>{L_FILE_INFOS}</h6>
					<span class="text-strong">{L_SIZE} : </span><span>{SIZE}</span><br/>
					<span class="text-strong">{L_INSERTION_DATE} : </span><span itemprop="dateCreated">{CREATION_DATE}</span><br/>
					<span class="text-strong">{L_RELEASE_DATE} : </span><span itemprop="dateModified">{RELEASE_DATE}</span><br/>
					<span class="text-strong">{L_DOWNLOADED} : </span><span>{HITS}</span><br/>
					<div class="center">
						<span class="text-strong">{KERNEL_NOTATION}</span>
					</div>
					
				</div>
				<span itemprop="text">
					{CONTENTS}
				</span>
				<br />
				{COMMENTS}
			</div>
			<footer></footer>
		</article>
		# ENDIF #
# ENDIF #