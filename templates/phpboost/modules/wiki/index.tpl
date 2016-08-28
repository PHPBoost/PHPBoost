		<section id="module-wiki">
			<header>
				<h1>
					<a href="${relative_url(SyndicationUrlBuilder::rss('wiki'))}" title="${LangLoader::get_message('syndication', 'common')}" class="fa fa-syndication"></a>
					{TITLE}
				</h1>
			</header>
			<div class="content">
				# INCLUDE wiki_tools #
				<div class="pbt-wiki-header">
					<img class="pbt-header-img" src="{PATH_TO_ROOT}/templates/{THEME}/theme/images/logo.png" alt="" />
					<div class="pbt-header-content">
						<p class="pbt-header-title">Bienvenue dans la documentation de PHPBoost.</p>
						<span class="pbt-header-desc">
						<br />Que vous soyez un utilisateur débutant ou confirmé, nous espérons que vous trouverez sur ces pages toutes les informations dont vous avez besoin. Dans le cas contraire, utilisez le <a href="{PATH_TO_ROOT}/forum/">Forum du projet</a> pour votre demande d'information.</span>
					</div>
				</div>
				
				<hr class="pbt-wiki-section-start"/>

				<section class="block pbt-wiki-about">
					<header>
						<h2>A propos de PHPBoost</h2>
					</header>
					<div class="content">
						<ul class="pbt-wiki-section-list pbt-content-about">
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/presentation-de-phpboost">Présentation de PHPBoost</a>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/download/">Télécharger PHPBoost</a>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/installation-de-phpboost">Installation de PHPBoost</a>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/migrer-son-site-vers-une-nouvelle-version">Comment migrer vers une nouvelle version</a>
							</li>
						</ul>
						
						<ul class="pbt-wiki-section-list pbt-content-about">
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/forum/">Forum</a>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/bugtracker/">Rapporter un bug</a>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/structure-d-un-theme">Structure d'un thème</a>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/creer-un-module">Créer un module</a>
							</li>
						</ul>
					</div>
					<footer></footer>
				</section>

				<section class="pbt-wiki-section">
					<header>
						<h2>
							<a href="{PATH_TO_ROOT}/wiki/presentation-de-phpboost">Présentation de PHPBoost</a>
							<span class="more">Tout savoir sur le projet</span>
						</h2>
					</header>
					<div class="content">
						<p class="more">Les articles</p>
						<ul class="pbt-wiki-section-list">
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/presentation-de-phpboost">Présentation de PHPBoost</a>
								<span class="more"> - Tout savoir sur le projet</span>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/phpboost-4-1">PHPBoost 4.1</a>
								<span class="more"> - Les nouveautés de la version 4.1 de PHPBoost</span>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/phpboost-4-0">PHPBoost 4.0</a>
								<span class="more"> - Les nouveautés de la version 4.0 de PHPBoost</span>
							</li>
						</ul>
						
						<p class="more">Les articles<p>
						<ul class="pbt-wiki-section-list">
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/liste-des-modules-supportes-et-contenus-dans-votre-distribution">Liste des modules supportés et contenus dans votre distribution</a>
							</li>
						</ul>
					</div>
					<footer></footer>
				</section>
				
				<section class="pbt-wiki-section">
					<header>
						<h2>
							<a href="{PATH_TO_ROOT}/wiki/installation-et-migration-de-phpboost">Installation et migration de PHPBoost</a>
							<span class="more">Installer et migrer PHPBoost très facilement</span>
						</h2>
					</header>
					<div class="content">
						<p class="more">Les catégories</p>
						<ul class="pbt-wiki-section-list">
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/installation-de-phpboost">Installation de PHPBoost</a>
								<span class="more"> - Obtenir une documentation détaillée sur l'installation de PHPBoost</span>
							</li>
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/changement-d-hebergement-ou-de-serveur">Changement d'hébergement ou de serveur</a>
								<span class="more"> - Changer de serveur sans aucun problème</span>
							</li>
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/migrer-son-site-vers-une-nouvelle-version">Migrer son site vers une nouvelle version</a>
								<span class="more"> - Passer aux nouvelles versions majeures pour obtenir plus de fonctionnalités</span>
							</li>
						</ul>
					</div>
					<footer></footer>
				</section>		
			
				<section class="pbt-wiki-section">
					<header>
						<h2>
							<a href="{PATH_TO_ROOT}/wiki/utilisation-de-phpboost">Utilisation de PHPBoost</a>
							<span class="more">Bien commencer avec PHPBoost</span>
						</h2>
					</header>
					<div class="content">
						<p class="more">Les catégories</p>
						<ul class="pbt-wiki-section-list">
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/panneau-d-administration">Panneau d'administration</a>
								<span class="more"> - Documentation relative à l'administration et à son utilisation</span>
							</li>
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/panneau-de-contribution">Panneau de contribution</a>
								<span class="more"> - Laisser vos membres intéragir avec votre site</span>
							</li>
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/panneau-de-moderation">Panneau de modération</a>
								<span class="more"> - Tout savoir sur la modération de PHPBoost</span>
							</li>
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/modules">Modules</a>
								<span class="more"> - Documentation des modules officiels de PHPBoost</span>
							</li>
						</ul>
						
						<p class="more">Les articles<p>
						<ul class="pbt-wiki-section-list">
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/utiliser-phpboost-en-ligne-de-commande">Utiliser PHPBoost en ligne de commande</a>
							</li>
						</ul>
					</div>
					<footer></footer>
				</section>	
				
				<section class="pbt-wiki-section">
					<header>
						<h2>
							<a href="{PATH_TO_ROOT}/wiki/design-et-interface">Design et Interface</a>
							<span class="more">Personnaliser l'aspect de votre site</span>
						</h2>
					</header>
					<div class="content">
						<p class="more">Les catégories</p>
						<ul class="pbt-wiki-section-list">
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/structure-d-un-theme">Structure un thème</a>
								<span class="more"> - De A à Z</span>
							</li>
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/personnaliser-un-theme">Personnaliser un thème</a>
								<span class="more"> - Modifier un thème existant</span>
							</li>
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/migrer-un-theme-vers-une-nouvelle-version">Migrer un thème vers une nouvelle version</a>
								<span class="more"> - Faire évoluer votre thème</span>
							</li>
						</ul>
					</div>
					<footer></footer>
				</section>	
				
				<section class="pbt-wiki-section">
					<header>
						<h2>
							<a href="{PATH_TO_ROOT}/wiki/developper-avec-phpboost">Développer avec PHPBoost</a>
							<span class="more">Créer des modules et du contenu dynamique</span>
						</h2>
					</header>
					<div class="content">
						<p class="more">Les catégories</p>
						<ul class="pbt-wiki-section-list">
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/creer-un-module">Créer un module</a>
								<span class="more"> - Tout savoir sur la création d'un module</span>
							</li>
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/creer-un-menu">Créer un menu</a>
								<span class="more"> - Tout savoir sur la création d'un menu</span>
							</li>
							<li>
								<i class="fa fa-folder-open"></i>
								<a href="{PATH_TO_ROOT}/wiki/migrer-un-module-vers-une-nouvelle-version">Migrer un module vers une nouvelle version</a>
								<span class="more"> - Faire évoluer votre module</span>
							</li>
						</ul>
						
						<p class="more">Les articles</p>
						<ul class="pbt-wiki-section-list">
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/conventions-de-developpement">Conventions de développement</a>
								<span class="more"> - Styles de développement adoptés sur le projet PHPBoost</span>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/debogage-dans-phpboost">Débogage dans PHPBoost</a>
								<span class="more"> - Les outils mis à disposition pour déboger votre code</span>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/constantes-chargees-avec-l-environnement-phpboost">Constantes chargées avec l'environnement PHPBoost</a>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/utilisateur-courant">Utilisateur courant</a>
								<span class="more"> - Obtenir des renseignements et son niveau d'autorisation sur l'utilisateur courant</span>
							</li>
							<li>
								<a href="{PATH_TO_ROOT}/wiki/developper-avec-phpboost">
									<span class="more more-articles">[+ d'articles]</span>
								</a>
							</li>
						</ul>
					</div>
					<footer></footer>
				</section>

				<section class="pbt-wiki-section">
					<header>
						<h2>
							<a href="{PATH_TO_ROOT}/wiki/tutoriels">Les tutoriels</a>
							<span class="more">Apprenez et partagez vos astuces</span>
						</h2>
					</header>
					<div class="content">
						<p class="more">Les articles</p>
						<ul class="pbt-wiki-section-list">
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/configurer-les-connexions-externes">Configurer les connexions externes</a>
								<span class="more"> - Connexion avec le login Facebook ou Google+</span>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/integrer-les-boutons-des-reseaux-sociaux">Intégrer les boutons des réseaux sociaux</a>
								<span class="more"> - Ajouter les liens de "like" et de partage de vos réseaux sociaux préférés</span>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/ajouter-des-images-sur-les-categories-du-forum">Ajouter des images sur les catégories du forum</a>
								<span class="more"> - Personnaliser votre forum</span>
							</li>
							<li>
								<i class="fa fa-file-text"></i>
								<a href="{PATH_TO_ROOT}/wiki/homecustom-ajout-d-elements-recuperes-dans-la-base-de-donnee">Ajouter des élements récupérés dans la base de donnée sur votre page d'accueil</a>
								<span class="more"> - Mettre en avant le contenu de votre site</span>
							</li>
							<li>
								<a href="{PATH_TO_ROOT}/wiki/tutoriels">
									<span class="more more-articles">[+ d'articles]</span>
								</a>
							</li>
						</ul>
					</div>
					<footer></footer>
				</section>

				<hr class="pbt-wiki-section-end" />
				
				# IF IS_MODERATOR #
					<div class="center">
						<a href="{PATH_TO_ROOT}/wiki/{U_EXPLORER}" class="pbt-button-a">
							<button class="big">
								<i class="fa fa-folder-open"></i> {L_EXPLORER}
							</button>
						</a>
					</div>
					
					<br />
					# START last_articles #
					<hr class="last-articles" />
					<br />
					<table>
						<thead>
							<tr>
								<th colspan="2">
									# IF last_articles.C_ARTICLES #<a href="${relative_url(SyndicationUrlBuilder::rss('wiki'))}" class="fa fa-syndication" title="${LangLoader::get_message('syndication', 'common')}"></a> # ENDIF #<strong><em>{last_articles.L_ARTICLES}</em></strong>
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								# START last_articles.list #
								{last_articles.list.TR}
									<td class="left" style="width:50%">
										<i class="fa fa-file-text"></i> <a href="{PATH_TO_ROOT}/wiki/{last_articles.list.U_ARTICLE}">{last_articles.list.ARTICLE}</a>
									</td>
								# END last_articles.list #
								{L_NO_ARTICLE}
							</tr>
						</tbody>
					</table>
					# END last_articles #
				# ENDIF #
			</div>
			<footer></footer>
		</section>