
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
						Bienvenue sur la page de téléchargement de PHPBoost 3.0 Tornade, la dernière version en date du CMS sortie le 28 juillet 2009.
						<BR /><BR />PHPBoost est un logiciel libre distribué sous licence GNU/GPL.
						<BR /><BR />PHPBoost 3 innove dans sa façon d'être distribué. En effet Tornade est la première version de PHPBoost à être distribuée de différentes façons pour être à même de s'adapter très rapidement aux besoins de chacun. Aujourd'hui, quatre distributions existent, et vous sont présentées dans la liste ci-dessous. Vous pourrez les télécharger en cliquant sur leur lien associé.</span>
					</div>
				</div>
				<hr style="margin:25px 0px;" />
				
				Cette page vous proposera de télécharger différentes version de PHPBoost, des mises à jours, ou d'acceder à nos sites de test.
				<BR /><BR />
				<ul class="bb_ul">
					<li class="bb_li">PHPBoost 4.0 est la dernière version du CMS (Stable)</li>
					<li class="bb_li">PHPBoost 3.x est la version du CMS ayant fait ces preuves, mais ne possédant pas les nombreux avantages de la version 4.0</li>
					<li class="bb_li">PHPBoost Archives regroupera toutes les version antérieurs à la 3.0 Uniquement pour les nostalgiques</li>
					<li class="bb_li">Vous trouverez aussi des versions "En cours / Unstable" permettant au developpeurs de tester et de contribuer au projet.</li>
				</ul>
				<hr style="margin-top:25px;" />
				
				
				<div class="download_container">
				
					<div class="download_content download_V4 block_container">
					
						<div class="download_entete_content">
							<p class="download_entete_title">Télécharger PHPBoost V4.x </p>
							<span class="download_entete_desc">
							C'est la versions conseiller pour tous les nouveaux membres et ceux qui souhaitent bénéficier de toute la puissance de PHPBoost.
							</span>
						</div>
						
						<div class="d_button d_button_ddl">
							<a href="#" class="d_button_a">
								<img class="d_button_img" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/logo.png" alt="" />
								<p class="d_button_title">Télécharger PHPBoost V4</p>
								<p class="d_button_com">Rév. 4.0.01 | Req. PHP 4.2 | Ver. Full .Zip </p>
							</a>
						</div>
						
						<div class="d_button d_button_try">
							<a href="#" class="d_button_a">
								<img class="d_button_img" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/logo.png" alt="" />
								<p class="d_button_title">Essayer PHPBoost V4</p>
								<p class="d_button_com d_button_com_try">Site de Test</p>
							</a>
						</div>
						
						<div class="d_upgrade_container">
							<h2 class="title" >Mettre à jour votre CMS PHPBoost V4</h2>
							<span class="download_entete_desc">Vous souhaitez mettre à jour votre site avec la dernière mise à jour de PHPBoost V4 ?</span>
							<ul class="bb_ul">
								<li class="bb_li"><a href="#">Mise à jour de la version N-1 à la version N</a></li>
								<li class="bb_li"><a href="#">Mise à jour de la version N-2 à la version N-1</a></li>
								<li class="bb_li"><a href="#">Autres mise à jour</a></li>
							</ul>
						</div>
						
						<div class="d_other_container">
							<h2 class="title" >Télecharger une version spéciale</h2>
							<span class="download_entete_desc">Si vous ne souhaitez pas la version Full !</span>
							<ul class="bb_ul">
								<li class="bb_li"><a href="#">Version Publication</a></li>
								<li class="bb_li"><a href="#">Version Communauté</a></li>
								<li class="bb_li"><a href="#">Version Pikatchu</a></li>
							</ul>
						</div>
						
					</div>
					
					<div class="download_content download_V3 block_container">
						<div class="download_entete_content">
							<p class="download_entete_title">Télécharger PHPBoost Dev (Unstable)</p>
							<span class="download_entete_desc">
							Il s'agit d'une version non finalisée, en cours developpement.
							</span>
						</div>
						
						<div class="d_button d_button_ddl">
							<a href="#" class="d_button_a">
								<img class="d_button_img" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/logo.png" alt="" />
								<p class="d_button_title">Télécharger PHPBoost Dev</p>
								<p class="d_button_com">Rév. 4.1 | Req. PHP 4.2 | Ver. Full .Zip </p>
							</a>
						</div>
						
						<div class="d_button d_button_try">
							<a href="#" class="d_button_a">
								<img class="d_button_img" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/logo.png" alt="" />
								<p class="d_button_title">Essayer PHPBoost Dev</p>
								<p class="d_button_com d_button_com_try">Site de dev</p>
							</a>
						</div>
					</div>
										
					<div class="download_content download_V3 block_container">
						<div class="download_entete_content">
							<p class="download_entete_title">Télécharger PHPBoost V3.x </p>
							<span class="download_entete_desc">Dernière révision stable de la V3</span>
						</div>
						
						<div class="d_button d_button_ddl">
							<a href="#" class="d_button_a">
								<img class="d_button_img" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/logo.png" alt="" />
								<p class="d_button_title">Télécharger PHPBoost V3</p>
								<p class="d_button_com">Rév. 3.11.01 | Req. PHP 4 | Ver. Full .Zip </p>
							</a>
						</div>
						
						<div class="d_button d_button_try">
							<a href="#" class="d_button_a">
								<img class="d_button_img" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/logo.png" alt="" />
								<p class="d_button_title">Essayer PHPBoost V3</p>
								<p class="d_button_com d_button_com_try">Site de Test</p>
							</a>
						</div>
						<div class="d_ohter_revisions">
							<a href="#">Autres versions/révisions</a>
						</div>
						
						<div class="d_upgrade_container">
							<h2 class="title" >Mettre à jour votre CMS PHPBoost V3</h2>
							<span class="download_entete_desc">Vous souhaitez mettre à jour votre site avec la dernière mise à jour de PHPBoost V3 ?</span>
							<ul class="bb_ul">
								<li class="bb_li"><a href="#">Mise à jour de la version N-1 à la version N</a></li>
								<li class="bb_li"><a href="#">Autres mise à jour</a></li>
							</ul>
						</div>
						
					</div>
					
					<div class="download_content download_Older block_container">
						<div class="download_entete_content">
							<p class="download_entete_title">Télécharger PHPBoost VX</p>
							<span class="download_entete_desc">Parcourir les répertoires à la recherche de votre version</span>
						</div>
						
						<div class="d_button d_button_sea">
							<a href="#" class="d_button_a">
								<p class="d_button_title">Parcourir l'arborescence</p>
								<p class="d_button_com">Toutes les révisions </p>
							</a>
						</div>
					</div>
				</div>