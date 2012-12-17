# IF C_ROOT_AND_EXPLORE #
	<div class="module_position">			
		<div class="module_top_l"></div>		
		<div class="module_top_r">
			# IF C_ADD_FILE #
				<div style="float:right;padding-top:5px;">
					<a href="{U_ADD_FILE}" title="{L_ADD_FILE}">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/french/add.png" alt="{L_ADD_FILE}" />
					</a>
				</div>
			# ENDIF #
			# IF C_ADMIN #
				<div style="float:right;padding-right:5px;">
					<a href="{U_ADMIN_CAT}">
						<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="" />
					</a>
				</div>
			# ENDIF #
		</div>
		<div class="module_top">
			<a href="${relative_url(SyndicationUrlBuilder::rss('download',IDCAT))}" title="Rss"><img style="vertical-align:middle;margin-top:-2px;" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="Rss" title="Rss" /></a>
			{TITLE}
		</div>
		<div class="module_contents">

			# IF C_DESCRIPTION #
				<!-- {DESCRIPTION} -->
			# ENDIF #
			<div class="download_entete">
				<img class="download_entete_img" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/logo.png" alt="" />
				<div class="download_entete_content">
					<p class="download_entete_title">PHPBOOST</p>
					<span class="download_entete_desc">
					Bienvenue sur la page de téléchargement de PHPBoost.
					<br /><br />PHPBoost est un logiciel libre distribué sous licence GNU/GPL.
					<br /><br />PHPBoost 3 innove dans sa façon d'être distribué. En effet Tornade est la première version de PHPBoost à être distribuée de différentes façons pour être à même de s'adapter très rapidement aux besoins de chacun. Aujourd'hui, quatre distributions existent, et vous sont présentées dans la liste ci-dessous. Vous pourrez les télécharger en cliquant sur leur lien associé.</span>
				</div>
			</div>
			<hr style="margin:25px 0px;" />
			
			Cette page vous proposera de télécharger différentes version de PHPBoost, des mises à jours, ou d'acceder à nos sites de test.
			<br /><br />
			<ul class="bb_ul">
				<li class="bb_li">PHPBoost 4.0 est la dernière version du CMS (Stable)</li>
				<li class="bb_li">PHPBoost 3.x est la version du CMS ayant fait ces preuves, mais ne possédant pas les nombreux avantages de la version 4.0</li>
				<li class="bb_li">PHPBoost Archives regroupera toutes les version antérieurs à la 3.0 Uniquement pour les nostalgiques</li>
				<li class="bb_li">Vous trouverez aussi des versions "En cours / Unstable" permettant au developpeurs de tester et de contribuer au projet.</li>
			</ul>
			<hr style="margin:25px auto 25px auto;" />
			
			
			<div class="download_container">
			
				<div class="download_content block_container">
				
					<div class="download_entete_content">
						<p class="download_entete_title">Télécharger PHPBoost 4.0 - Sirocco</p>
						<span class="download_entete_desc">
						C'est la versions conseillée pour tous les nouveaux membres et ceux qui souhaitent bénéficier de toute la puissance de PHPBoost.
						</span>
					</div>
					
					<div class="d_button_container">
						<div class="d_button d_button_blue">
							<a href="{PATH_TO_ROOT}/download/file-229+phpboost-4-0.php" class="d_button_a">
								<img class="d_button_img" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/img_phpboost.png" alt="" />
								<p class="d_button_title">Télécharger PHPBoost 4.0</p>
								<p class="d_button_com">Rev : 4.0.1 | Req : PHP 5.1.2 | .zip </p>
							</a>
						</div>
						<div class="d_button d_button_green">
							<a href="{PATH_TO_ROOT}/download/category-36+mises-jour.php" class="d_button_a">
								<img class="d_button_img" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/img_phpboost.png" alt="" />
								<p class="d_button_title">Mises à jour</p>
								<p class="d_button_com d_button_com_green">Mise à jour et migration</p>
							</a>
						</div>
					</div>
					<div class="d_dev_container">
						<a href="#" class="d_dev">Télécharger la version de développement</a>
					</div>
					
					<hr style="margin:10px auto 0px auto;" />
					
					<div class="d_custom_content">
						<div style="width: 90%;margin:auto;">
							<div style="float:left; width: 47%;padding-right:15px;">
								<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/img_modules.png" class="valign_middle d_custom_img" />
								<h2 class="title d_custom_subtitle" >
									<a href="{PATH_TO_ROOT}/download/category-38+modules.php">Modules compatibles</a>
								</h2>
								<p class="d_custom_exemple">
									Donnez de nouvelles fonctionnalités à votre site.
								</p>
							</div>
							<div style="float:left; width: 47%;padding-left:15px;">
								<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/img_themes.png" class="valign_middle d_custom_img" />
								<h2 class="title d_custom_subtitle" >
									<a href="{PATH_TO_ROOT}/download/category-37+themes.php">Thèmes compatibles</a>
								</h2>
								<p class="d_custom_exemple">
									Trouvez la bonne entitée graphique pour votre site.
								</p>
							</div>
						</div>
						
						<div class="spacer"></div>
					</div>										
				</div>
														
				<div class="download_content block_container">
				
					<div class="download_entete_content">
						<p class="download_entete_title">Télécharger PHPBoost 3.0 - Tornade</p>
						<span class="download_entete_desc">Nous vous conseillons la version 4.0 pour tout nouveau site. 
						</span>
					</div>
					
					<div class="d_button_container">
						<div class="d_button d_button_blue">
							<a href="{PATH_TO_ROOT}/download/file-111+phpboost-3-0-complete.php" class="d_button_a">
								<img class="d_button_img" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/img_phpboost.png" alt="" />
								<p class="d_button_title">Télécharger PHPBoost 3.0</p>
								<p class="d_button_com">Rev : 3.0.11 | Req : PHP 4.0.1 | .zip </p>
							</a>
						</div>
						<div class="d_button d_button_green">
							<a href="{PATH_TO_ROOT}/download/category-39+mises-jour.php" class="d_button_a">
								<img class="d_button_img" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/img_phpboost.png" alt="" />
								<p class="d_button_title">Mises à jour</p>
								<p class="d_button_com d_button_com_green">Mise à jour et migration</p>
							</a>
						</div>
					</div>						
					
					<hr style="margin:25px auto 0px auto;" />
					
					<div class="d_custom_content">
						<div style="width: 90%;margin:auto;">
							<div style="float:left; width: 47%;padding-right:15px;">
								<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/img_modules.png" class="valign_middle d_custom_img" />
								<h2 class="title d_custom_subtitle" >
									<a href="{PATH_TO_ROOT}/download/category-29+modules.php">Modules compatibles</a>
								</h2>
								<p class="d_custom_exemple">
									Donnez de nouvelles fonctionnalités à votre site.
								</p>
							</div>
							<div style="float:left; width: 47%;padding-left:15px;">
								<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/img_themes.png" class="valign_middle d_custom_img" />
								<h2 class="title d_custom_subtitle" >
									<a href="{PATH_TO_ROOT}/download/category-30+themes.php">Thèmes compatibles</a>
								</h2>
								<p class="d_custom_exemple">
									Trouvez la bonne entitée graphique pour votre site.
								</p>
							</div>
						</div>
						
						<div class="spacer"></div>
					</div>										
				</div>
				
				<hr style="margin:20px auto 30px auto;" />
				
				<div style="text-align:center;">	
					<div class="d_button d_button_gray">
						<a href="{PATH_TO_ROOT}/download/download.php?explore=1" class="d_button_a">
							<p class="d_button_title">Parcourir l'arborescence</p>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
# ELSE #
	# IF C_DOWNLOAD_CAT #
	<div class="module_position">			
		<div class="module_top_l"></div>		
		<div class="module_top_r"></div>
		<div class="module_top">
			<a href="${relative_url(SyndicationUrlBuilder::rss('download',IDCAT))}" title="Rss"><img style="vertical-align:middle;margin-top:-2px;" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="Rss" title="Rss" /></a>
			{TITLE}
			# IF C_ADMIN #
			<a href="{U_ADMIN_CAT}">
				<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="" />
			</a>
			# END IF #
		</div>
		<div class="module_contents">
			# IF C_ADD_FILE #
				<div style="text-align:center;">
					<a href="{U_ADD_FILE}" title="{L_ADD_FILE}">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/french/add.png" alt="{L_ADD_FILE}" />
					</a>
				</div>
				<hr style="margin-top:25px; margin-bottom:25px;" />
			# ENDIF #
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
							<a href="{row.list_cats.U_ADMIN_CAT}">
								<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="" />
							</a>
							# ENDIF #
							<div class="text_small">
								{row.list_cats.NUM_FILES}
							</div>
						</div>
					# END row.list_cats #
					<div class="spacer">&nbsp;</div>
				# END row #
				<hr style="margin-bottom:25px;" />
			# ENDIF #
			
			# IF C_FILES #
				
				<div style="float:right;" class="row3" id="form">
					<script type="text/javascript">
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
					<div class="block_container" style="margin-bottom:20px;">
						<div class="block_contents">
							# IF file.C_IMG #
								<div class="float_right">
									<a href="{file.U_DOWNLOAD_LINK}">
										<img src="{file.IMG}" alt="{file.IMG_NAME}" />
									</a>
								</div>
							# ENDIF #
							<p style="margin-bottom:10px">
								<a href="{file.U_DOWNLOAD_LINK}" class="big_link">{file.NAME}</a>
								# IF C_ADMIN #
									<a href="{file.U_ADMIN_EDIT_FILE}">
										<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="" />
									</a>
									<a href="{file.U_ADMIN_DELETE_FILE}" onclick="return confirm('{L_CONFIRM_DELETE_FILE}');">
										<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="" />
									</a>
								# ENDIF #
							</p>
							# IF file.C_DESCRIPTION #
								<p>
								{file.DESCRIPTION}
								</p>
							# ENDIF #
							<div class="text_small">
								{file.DATE}
								<br />
								{file.COUNT_DL}
								<br />
								{file.U_COM_LINK}
								<br />
								{L_NOTE} {file.NOTE}
							</div>
							<div class="spacer"></div>								
						</div>
					</div>						
				# END file #
				<div style="text-align:center;">{PAGINATION}</div>
			# ENDIF #
			
			# IF C_NO_FILE #
				<div class="notice">
					{L_NO_FILE_THIS_CATEGORY}
				</div>
			# ENDIF #
			<div class="spacer"></div>
		</div>
		<div class="module_bottom_l"></div>		
		<div class="module_bottom_r"></div>
		<div class="module_bottom"></div>
	</div>
	# ENDIF #
	
	# IF C_DISPLAY_DOWNLOAD #			
	<div class="module_position">					
		<div class="module_top_l"></div>		
		<div class="module_top_r"></div>
		<div class="module_top">
			<div style="float:left">
				{NAME}
			</div>
			<div style="float:right">
				{U_COM}
				# IF C_EDIT_AUTH #
					<a href="{U_EDIT_FILE}">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" class="valign_middle" alt="{L_EDIT_FILE}" />
					</a>
					<a href="{U_DELETE_FILE}" onclick="return confirm('{L_CONFIRM_DELETE_FILE}');">
						<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" class="valign_middle" alt="{L_DELETE_FILE}" />
					</a>
				# ENDIF #
			</div>
		</div>
		<div class="module_contents">
			<table>
				<tr>
					<td style="text-align:center;padding-right:20px;">
						# IF C_IMG #
							<img src="{U_IMG}" alt="{IMAGE_ALT}" />
							<br /><br />
						# ENDIF #
						<a href="{U_DOWNLOAD_FILE}">
							<img src="{PICTURES_DATA_PATH}/images/download_file.png" alt="" />
						</a>
						<p><a href="{U_DOWNLOAD_FILE}">{L_DOWNLOAD_FILE}</a></p>
						# IF IS_USER_CONNECTED #
						<a href="{U_DEADLINK}">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/notice.png" alt="" />
						</a>
						<p><a href="{U_DEADLINK}">{L_DEADLINK}</a></p>
						# ENDIF #
					</td>
					<td>
						<p class="text_justify">
							{CONTENTS}
						</p>
					</td>
				</tr>
			</table>
			<br />
			<table style="width:430px;margin-right:0;" class="module_table text_small">
				<tr>
					<th colspan="2">
						{L_FILE_INFOS}
					</th>
				</tr>
				<tr>
					<td class="row1" style="padding:3px">
						{L_SIZE}
					</td>
					<td class="row2" style="padding:3px">
						{SIZE}
					</td>
				</tr>
				<tr>
					<td class="row1" style="padding:3px">
						{L_INSERTION_DATE}
					</td>
					<td class="row2" style="padding:3px">
						{CREATION_DATE}
					</td>
				</tr>
				<tr>
					<td class="row1" style="padding:3px">
						{L_RELEASE_DATE}
					</td>
					<td class="row2" style="padding:3px">
						{RELEASE_DATE}
					</td>
				</tr>
				<tr>
					<td class="row1" style="padding:3px">
						{L_DOWNLOADED}
					</td>
					<td class="row2" style="padding:3px">
						{HITS}
					</td>
				</tr>
				<tr>
					<td class="row1" style="padding:3px">
						{L_NOTE} <em><span id="nbrnote{ID_FILE}">({NUM_NOTES})</span></em>
					</td>
					<td class="row2" style="padding:1px">
						{KERNEL_NOTATION}
					</td>
				</tr>
			</table>
		</div>
		<div class="module_bottom_l"></div>		
		<div class="module_bottom_r"></div>
		<div class="module_bottom">
		</div>
	</div>		
	<br /><br />
	{COMMENTS}
	# ENDIF #
# ENDIF #