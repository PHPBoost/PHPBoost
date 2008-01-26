<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
	<head>
		<title>{L_TITLE}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="description" content="PHPBoost" />
		<link type="text/css" href="templates/update.css" title="phpboost" rel="stylesheet" />
		<link rel="shortcut" href="../favicon.ico" />
	</head>
	<body>
		<script type="text/javascript">
		<!--
			var speed_progress = 20;
			var timeout = null;
			var max_percent = 0;
			var info_progress_tmp = '';
			var step = {NUM_STEP};
			
			function progress_bar(percent_progress, info_progress, restart_progress)
			{
				bar_progress = (percent_progress * 55) / 100;
				
				if (arguments.length == 5)
				{
					result_id = arguments[3];
					result_msg = arguments[4];
				}
				else
				{
					result_id = "";
					result_msg = "";
				}	
				
				// Déclaration et initialisation d'une variable statique
			    if( typeof this.percent_begin == 'undefined' || restart_progress == 1 ) 
				{	
					this.percent_begin = 0;
					max_percent = 0;
					if( document.getElementById('progress_bar') )
						document.getElementById('progress_bar').innerHTML = '';
				}
			
				if( this.percent_begin <= bar_progress )
				{
					if( document.getElementById('progress_bar') )
						document.getElementById('progress_bar').innerHTML += '<img src="templates/images/loading.png" alt="" />';
					if( document.getElementById('progress_percent') )
						document.getElementById('progress_percent').innerHTML = Math.round((this.percent_begin * 100) / 55);
					if( document.getElementById('progress_info') )
					{	
						if( percent_progress > max_percent )
						{	
							max_percent = percent_progress;
							info_progress_tmp = info_progress;
						}
						document.getElementById('progress_info').innerHTML = info_progress_tmp;
					
					}
					//Message de fin
					if( this.percent_begin == 55 && result_id != "" && result_msg != "" )
						document.getElementById(result_id).innerHTML = result_msg;
					timeout = setTimeout('progress_bar(' + percent_progress + ', "' + info_progress + '", 0, "' + result_id + '", "' + result_msg.replace(/"/g, "\\\"") + '")', speed_progress);
				}
				else
					this.percent_begin = this.percent_begin - 1;
				this.percent_begin++;
			}
		-->
		</script>
		<div id="header">
			<img src="templates/images/header_boost.jpg" alt="PHPBoost" />
		</div>

		<div id="sub_header">
			<div id="sub_header_left">
			</div>
			<div id="sub_header_right">
			</div>
		</div>
		<div id="left_menu">
			<table class="table_left">
				<tr>
					<td class="row_top">
						{L_STEPS_LIST}
					</td>
				</tr>
				# START link_menu #
					{link_menu.ROW}
				# END link_menu #
			</table>
			
			<br /><br />
			
			<table class="table_left">
				<tr>
					<td class="row_top">
						{L_INSTALL_PROGRESS}
					</td>
				</tr>
				<tr>
					<td class="row_next">
						<div style="margin:auto;width:235px">
							<div style="text-align:center;">{L_STEP}</div>
							<div style="float:left;height:12px;border:1px solid black;background:white;width:192px;padding:2px;padding-left:3px;padding-right:1px;">
								{PROGRESS_BAR_PICS}
							</div>&nbsp;{PROGRESS_LEVEL}%
						</div>
					</td>
				</tr>						
			</table>
			
			<br /><br />
			
			<table class="table_left">
				<tr>
					<td class="row_top">
						{L_APPENDICES}
					</td>
				</tr>
				<tr>
					<td class="row_next">
						<img src="templates/images/help.png" alt="{L_DOCUMENTATION}" class="valign_middle" />
						&nbsp;&nbsp;
						<a href="{U_DOCUMENTATION}">{L_DOCUMENTATION}</a>
					</td>
				</tr>
				<tr>
					<td class="row_next">
						<img src="templates/images/intro.png" alt="{L_RESTART_INSTALL}" class="valign_middle" />
						&nbsp;&nbsp;
						<a href="{U_RESTART}" onclick="return confirm('{L_CONFIRM_RESTART}');">{L_RESTART_INSTALL}</a>
					</td>
				</tr>					
			</table>
		</div>
		
		<div id="main">
			<table class="table_contents">
				<tr> 
					<th colspan="2">
						{L_STEP}
					</th>
				</tr>
				
				<tr> 					
					# START intro #
					<td class="row_contents">						
						<span style="float:left;padding:8px;padding-top:0px">
							<img src="templates/images/phpboost.png" alt="Logo PHPBoost" />
						</span>
						Vous êtes sur le point de mettre à jour PHPBoost de la version 1.6.0 à la 2.0.
						<br />
						L'installation se fera en plusieurs parties, en un premier temps vous mettrez à jour le noyau de PHPBoost (la partie fixe) et ensuite chaque module un par un.
						<fieldset class="submit_case">
							<a href="{L_NEXT_STEP}" title="{L_START_INSTALL}" ><img src="templates/images/right.png" alt="{L_START_INSTALL}" /></a>
						</fieldset>		
					</td>
					# END intro #
					
					# START kernel_update #
					<td class="row_contents">						
						Cette étape concerne la mise à jour du noyau, c'est à dire l'importation dans la nouvelle structure des anciennes données principales dans la nouvelle structure. Les mises à jour concernant chaque module se feront ultérieurement.
						<br />
						<div class="warning">
							Les messages privés ne seront pas conservés.
							<br />
							Certaines de vos configurations seront perdues, pensez à noter la configuration actuelle.
						</div>
						# START error #
							<br />
							<div class="error">
								{kernel_update.error.ERROR}
							</div>
						# END error #
						<form action="{TARGET}" method="post">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}" ><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" name="submit" value="submit" />
							</fieldset>		
						</form>
					</td>
					# END kernel_update #
					
					# START articles_update #
					<td class="row_contents">						
						Vous allez ici mettre à jour la table articles. Vos anciens articles et catégories seront importés.
						<br />
						<fieldset>
							<legend>Nouveautés</legend>							    
								<ul>
									<li>Nouveauté: Gestion totale de la parution de l'article, date de début/fin d'affichage, intervalle d'affichage.</li>
									<li>Nouveauté: Gestion des sous-catégories infinies.</li>
									<li>Possibilité d'afficher les catégories sur plusieurs colonnes (configurable).</li>
								</ul>
						</fieldset>
						<br />
						<div class="question">
							Si vous ne souhaitez pas mettre à jour ce module vous pouvez ignorer cette étape en cliquant sur le bouton associé : <img src="templates/images/stop.png" alt="" class="valign_middle" >
						</div>
						<form action="{TARGET}" method="post">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;
								<a href="{U_NEXT_PAGE}" title="{L_IGNORE}"><img src="templates/images/stop.png" alt="{L_IGNORE}" class="valign_middle" /></a>&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" name="submit" value="submit" />							
							</fieldset>		
						</form>
					</td>
					# END articles_update #
								
					# START calendar_update #
					<td class="row_contents">						
						Tous les évennements du calendrier seront importés.
						<br />
						<fieldset>
							<legend>Nouveautés</legend>							    
								<ul>
									<li>Mini calendrier de saisie</li>
									<li>Corrections, améliorations et intégration du mini-calendrier en popup pour la saisie des dates.</li>
								</ul>
						</fieldset>
						<br />
						<div class="question">
							Si vous ne souhaitez pas mettre à jour ce module vous pouvez ignorer cette étape en cliquant sur le bouton associé : <img src="templates/images/stop.png" alt="" class="valign_middle" >
						</div>
						<form action="{TARGET}" method="post">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;
								<a href="{U_NEXT_PAGE}" title="{L_IGNORE}"><img src="templates/images/stop.png" alt="{L_IGNORE}" class="valign_middle" /></a>&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" name="submit" value="submit" />							
							</fieldset>		
						</form>
					</td>
					# END calendar_update #
					
					
					# START forum_update #
					<td class="row_contents">						
						Tous les événements du calendrier seront importés.
						<br />
						<fieldset>
							<legend>Nouveautés</legend>							    
								<ul>
									<li>Nouveauté: Création de sous-forums illimité.
									</li><li>Nouveauté: Gestion complète des mutligroupes, gestion des droits très fine (lecture, écriture, édition) pour chaque catégorie et pour chaque groupes. Autorisations globales des groupes sur le forum (flood, marqueurs d'édition, etc..).
									</li><li>Nouveauté: Suppression des messages instantanée sur le forum (sans rechargement de la page grâce à Ajax).
									</li><li>Nouveauté: Intégration du module de gestion des médias, ajout d'images sur le forum automatisé par attachement de l'image au message.
									</li><li>Nouveauté: Possibilité de choisir d'être prévenu (ou non) lors d'un nouveau message par messages privés ou par mails (si déconnecté du site), pour chaque sujets suivis du forum. Ajout d'une option de suppression des sujets suivis.
									</li><li>Nouveauté: Possibilité d'afficher les derniers messages lu, afin de faciliter leur suivi.
									</li><li>Nouveauté: Possibilité de masquer les menus de gauche et droite.
									</li><li>Possibilité de prédéfinir un texte inséré devant le topic, ajouté automatiquement à l'édition (ex: [Résolu] Nom du topic).
									</li><li>Possibilité de choisir le contenu du message envoyé lors de l'avertissement/mise en lectures seule d'un membre.
									</li><li>Nouvelle page de statistiques, ajout de la moyenne de sujets/messages par jour et du nombre de sujets/messages total et de la journée.
									</li><li>Possibilité de mettre à jour les données en cache (recompte le nombre de topics et de messages pour chaque catégories).</li>
								</ul>
						</fieldset>
						<br />
						<div class="question">
							Si vous ne souhaitez pas mettre à jour ce module vous pouvez ignorer cette étape en cliquant sur le bouton associé : <img src="templates/images/stop.png" alt="" class="valign_middle" >
						</div>
						<form action="{TARGET}" method="post">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;
								<a href="{U_NEXT_PAGE}" title="{L_IGNORE}"><img src="templates/images/stop.png" alt="{L_IGNORE}" class="valign_middle" /></a>&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" name="submit" value="submit" />							
							</fieldset>		
						</form>
					</td>
					# END forum_update #

					# START gallery_update #
					<td class="row_contents">						
						Toutes les images de votre galerie seront importés
						<br />
						<fieldset>
							<legend>Nouveautés</legend>							    
								<ul>
									<li>Nouveauté: Refonte complète du module, avec gestion des sous-albums infinis.
									<li>Nouveauté: Nouveau mode d'affichage des images (plein écran, agrandissement, etc...).</li>
									<li>Nouveauté: Interface de visualisation avec défilement des miniatures.</li>
									<li>Menu défilant avec affichage de plusieurs photos (configurable) dans ordre aléatoire (mini galerie).</li>
								</ul>
						</fieldset>
						<br />
						# START error #
							<div class="error">
								{gallery_update.error.ERROR}
							</div>
						# END error #
						<div class="question">
							Si vous ne souhaitez pas mettre à jour ce module vous pouvez ignorer cette étape en cliquant sur le bouton associé : <img src="templates/images/stop.png" alt="" class="valign_middle" >
						</div>
						<form action="{TARGET}" method="post">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;
								<a href="{U_NEXT_PAGE}" title="{L_IGNORE}"><img src="templates/images/stop.png" alt="{L_IGNORE}" class="valign_middle" /></a>&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" name="submit" value="submit" />							
							</fieldset>		
						</form>
					</td>
					# END gallery_update #

					# START guestbook_update #
					<td class="row_contents">						
						Les anciens messages du livre d'or vont être copiés vers la nouvelle version.
						<br />
						<fieldset>
							<legend>Nouveautés</legend>							    
								<ul>
									<li>Configuration du livre d'or dans l'administration, rang pour pouvoir poster, balises interdites...</li>
								</ul>
						</fieldset>
						<br />
						<div class="question">
							Si vous ne souhaitez pas mettre à jour ce module vous pouvez ignorer cette étape en cliquant sur le bouton associé : <img src="templates/images/stop.png" alt="" class="valign_middle" >
						</div>
						</div>
						<form action="{TARGET}" method="post">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;
								<a href="{U_NEXT_PAGE}" title="{L_IGNORE}"><img src="templates/images/stop.png" alt="{L_IGNORE}" class="valign_middle" /></a>&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" name="submit" value="submit" />							
							</fieldset>		
						</form>
					</td>
					# END guestbook_update #
					
					# START news_update #
					<td class="row_contents">						
						Toutes les news seront récupérées.
						<br />
						<fieldset>
							<legend>Nouveautés</legend>							    
								<ul>
									<li>Nouveauté: Gestion totale de la parution des news, date de début/fin d'affichage, intervalle d'affichage.</li>
									<li>Nouveauté: Gestion des catégories pour les news, avec description et icône associé à la news (désactivable), lien permettant l'affichage des news par catégories.</li>
									<li>Nouveauté: Possibilité de tronquer l'affichage de la news, un lien permet de lire la suite.</li>
									<li>Nouveauté: Gestion du système de média intégré aux news, permet l'ajout simplifié des images.</li>
									<li>Nouveauté: Possibilité de changer la date de parution de la news (classement des news possible).</li>
									<li>Nouveauté: Possibilité d'afficher les news sur plusieurs colonnes (configurable).</li>
									<li>Ajout du titre de la news dans l'url rewriting.</li>
								</ul>
						</fieldset>
						<br />
						<div class="warning">
							Toutes les news seront approuvées.
						</div>
						<br />
						<div class="question">
							Si vous ne souhaitez pas mettre à jour ce module vous pouvez ignorer cette étape en cliquant sur le bouton associé : <img src="templates/images/stop.png" alt="" class="valign_middle" >
						</div>
						<form action="{TARGET}" method="post">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;
								<a href="{U_NEXT_PAGE}" title="{L_IGNORE}"><img src="templates/images/stop.png" alt="{L_IGNORE}" class="valign_middle" /></a>&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" name="submit" value="submit" />							
							</fieldset>		
						</form>
					</td>
					# END news_update #
					
					# START pages_update #
					<td class="row_contents">						
						Toutes les news seront récupérées.
						<br />
						<fieldset>
							<legend>Nouveautés</legend>							    
								<ul>
									<li>Gestion des catégories infinies</li>
									<li>Langage hybride : HTML ou BBCode</li>
									<li>Commentaires en option pour chaque page</li>
									<li>Possibilité de créer des redirections d'une page vers une autre</li>
									<li>Optimisation en ce qui concerne le référéncement de vos pages</li>
								</ul>
						</fieldset>
						<div class="warning">
							La syntaxe des pages ayant changé on ne peut pas garantir l'exactitude de l'importation des pages. Il est donc vivement conseillé de sauvegarder vos pages (par votre client ftp télécharger le contenu du dossier page) car à la fin du traitement elles seront supprimées.
							<br />
							Veillez aussi à reprendre les autorisations pour chaque page, elles ne seront pas conservées.
						</div>
						<br />
						<div class="question">
							Si vous ne souhaitez pas mettre à jour ce module vous pouvez ignorer cette étape en cliquant sur le bouton associé : <img src="templates/images/stop.png" alt="" class="valign_middle" >
						</div>
						<form action="{TARGET}" method="post">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;
								<a href="{U_NEXT_PAGE}" title="{L_IGNORE}"><img src="templates/images/stop.png" alt="{L_IGNORE}" class="valign_middle" /></a>&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" name="submit" value="submit" />							
							</fieldset>		
						</form>
					</td>
					# END pages_update #

					# START shoutbox_update #
					<td class="row_contents">						
						Importation des messages de la shoutbox.
						<br />
						<fieldset>
							<legend>Nouveautés</legend>							    
								<ul>
									<li>Délestage automatique des messages, configurable et désactivable.</li>
									<li>Configuration de la shoutbox dans l'administration, rang pour pouvoir poster, balises interdites...</li>
								</ul>
						</fieldset>
						<br />
						<div class="question">
							Si vous ne souhaitez pas mettre à jour ce module vous pouvez ignorer cette étape en cliquant sur le bouton associé : <img src="templates/images/stop.png" alt="" class="valign_middle" >
						</div>
						<form action="{TARGET}" method="post">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;
								<a href="{U_NEXT_PAGE}" title="{L_IGNORE}"><img src="templates/images/stop.png" alt="{L_IGNORE}" class="valign_middle" /></a>&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" name="submit" value="submit" />							
							</fieldset>		
						</form>
					</td>
					# END shoutbox_update #
					
					# START web_update #
					<td class="row_contents">						
						Importe les liens web.
						<br />
						<fieldset>
							<legend>Nouveautés</legend>							    
								<ul>
									<li>Liens en dur (adresse directe sur le bouton) avec compteur.</li>
								</ul>
						</fieldset>
						<br />
						<div class="question">
							Si vous ne souhaitez pas mettre à jour ce module vous pouvez ignorer cette étape en cliquant sur le bouton associé : <img src="templates/images/stop.png" alt="" class="valign_middle" >
						</div>
						<form action="{TARGET}" method="post">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;
								<a href="{U_NEXT_PAGE}" title="{L_IGNORE}"><img src="templates/images/stop.png" alt="{L_IGNORE}" class="valign_middle" /></a>&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" name="submit" value="submit" />							
							</fieldset>		
						</form>
					</td>
					# END web_update #
					
					# START download_update #
					<td class="row_contents">						
						Importation des fichiers en téléchargement.
						<br />
						<fieldset>
							<legend>Nouveautés</legend>							    
								<ul>
									<li>Nouveauté: Gestion totale de la parution du téléchargement, date de début/fin d'affichage, intervalle d'affichage.</li>
									<li>Possibilité d'afficher les catégories sur plusieurs colonnes (configurable).</li>
									<li>Force le téléchargement des fichiers.</li>
									<li>Mise en cache des catégories.</li>
								</ul>
						</fieldset>
						<br />
						<div class="question">
							Si vous ne souhaitez pas mettre à jour ce module vous pouvez ignorer cette étape en cliquant sur le bouton associé : <img src="templates/images/stop.png" alt="" class="valign_middle" >
						</div>
						<form action="{TARGET}" method="post">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;
								<a href="{U_NEXT_PAGE}" title="{L_IGNORE}"><img src="templates/images/stop.png" alt="{L_IGNORE}" class="valign_middle" /></a>&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" name="submit" value="submit" />							
							</fieldset>		
						</form>
					</td>
					# END download_update #
					
					# START cache #
					<td class="row_contents">						
						Finalisation de l'installation (régénération du cache, mise en place des menus).
						<br />
						<form action="{TARGET}" method="post">
							<fieldset class="submit_case">
								<a href="{U_PREVIOUS_PAGE}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;
								<input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" name="submit" value="submit" />							
							</fieldset>		
						</form>
					</td>
					# END cache #
					
					# START end #
					<td class="row_contents">						
						<fieldset>
							<legend>Mise à jour terminée</legend>
							<div class="success">
								Votre portail PHPBoost est désormais à jour. Vos données ont été importées, mais certaines de vos configurations n'ont pas pu être reconduites. Nous vous prions de vérifier chacune d'elles afin d'utiliser PHPBoost à votre sauce.
							</div>
							<br />
							Merci de nous faire confiance depuis un certain temps et de continuer. Bonne continuation sur PHPBoost.
						</fieldset>
						<fieldset>
							<legend>Rejoindre votre site</legend>
							<div class="warning">
								Il est important de supprimer le dossier update de votre site, cela pourrait vous poser des problèmes de sécurité.
							</div>
							<div style="text-align:center;">
								<a href="../news/news.php"><img src="templates/images/go-home.png" alt="Go home" /></a>
								<br />
								<a href="../news/news.php">Rejoindre le site</a>
							</div>
						</fieldset>
					</td>
					# END end #
				</tr>
			</table>		
		</div>
		<div id="footer">
			<span class="text_small">{L_GENERATED_BY}</span>
		</div>
	</body>
</html>