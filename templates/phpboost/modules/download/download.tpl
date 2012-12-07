
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
								<a href="#" class="d_button_a">
									<img class="d_button_img" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/logo.png" alt="" />
									<p class="d_button_title">Télécharger PHPBoost 4.0</p>
									<p class="d_button_com">Rev : 4.0.1 | Req : PHP 5.1.2 | .zip </p>
								</a>
							</div>
							<div class="d_button d_button_green">
								<a href="#" class="d_button_a">
									<img class="d_button_img" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/logo.png" alt="" />
									<p class="d_button_title">Mises à jour</p>
									<p class="d_button_com d_button_com_green">Mise à jour et migration</p>
								</a>
							</div>
						</div>
						<div class="d_dev_container">
							<a href="#" class="d_dev">Télécharger la version de développement </a>
						</div>
						
						
						<hr style="margin:40px auto 0px auto;" />
						
						<div class="d_custom_content">
							<div style="width: 90%;margin:auto;">
								<div style="float:left; width: 47%;padding-right:15px;">
									<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/img_modules.png" class="valign_middle d_custom_img" />
									<h2 class="title d_custom_subtitle" ><a href="#">Les modules Compatibles V4.0</a></h2>
									<p class="d_custom_exemple">
										<a href="#">Calendrier</a>-
										<a href="#">Forum</a>-
										<a href="#">News</a>
									</p>
								</div>
								<div style="float:left; width: 47%;padding-left:15px;">
									<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/img_themes.png" class="valign_middle d_custom_img" />
									<h2 class="title d_custom_subtitle" ><a href="#">Les thèmes Compatibles V4.0</a></h2>
									<p class="d_custom_exemple">
										<a href="#">Loren</a>-
										<a href="#">Pikatchu</a>-
										<a href="#">Sengoten</a>
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
								<a href="#" class="d_button_a">
									<img class="d_button_img" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/logo.png" alt="" />
									<p class="d_button_title">Télécharger PHPBoost 3.0</p>
									<p class="d_button_com">Rev : 3.0.11 | Req : PHP 4.0.1 | .zip </p>
								</a>
							</div>
							<div class="d_button d_button_green">
								<a href="#" class="d_button_a">
									<img class="d_button_img" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/logo.png" alt="" />
									<p class="d_button_title">Mises à jour</p>
									<p class="d_button_com d_button_com_green">Mise à jour et migration</p>
								</a>
							</div>
						</div>						
						
						<hr style="margin:40px auto 0px auto;" />
						
						<div class="d_custom_content">
							<div style="width: 90%;margin:auto;">
								<div style="float:left; width: 47%;padding-right:15px;">
									<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/img_modules.png" class="valign_middle d_custom_img" />
									<h2 class="title d_custom_subtitle" ><a href="#">Les modules Compatibles V3.0</a></h2>
									<p class="d_custom_exemple">
										<a href="#">Calendrier</a>-
										<a href="#">Forum</a>-
										<a href="#">News</a>
									</p>
								</div>
								<div style="float:left; width: 47%;padding-left:15px;">
									<img src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/img_themes.png" class="valign_middle d_custom_img" />
									<h2 class="title d_custom_subtitle" ><a href="#">Les thèmes Compatibles V3.0</a></h2>
									<p class="d_custom_exemple">
										<a href="#">Loren</a>-
										<a href="#">Pikatchu</a>-
										<a href="#">Sengoten</a>
									</p>
								</div>
							</div>
							
							<div class="spacer"></div>
						</div>										
					</div>
					
					<hr style="margin:20px auto 30px auto;" />
					
					<div style="text-align:center;">	
						<div class="d_button d_button_gray">
							<a href="#" class="d_button_a">
								<p class="d_button_title">Parcourir l'arborescence</p>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>