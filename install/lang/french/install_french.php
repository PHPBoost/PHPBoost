<?php
/*##################################################
 *                                install.php
 *                            -------------------
 *   begin                : September 28, 2008
 *   copyright            : (C) 2008 	Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *  
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/


####################################################
#                      French                      #
####################################################

$LANG = array();

//Erreur générée par le moteur de template
$LANG['cache_tpl_must_exist_and_be_writable'] = '<h1>Installation de PHPBoost</h1>
<p><strong>Attention</strong> : le dossier cache/tpl n\'existe pas ou n\'est pas inscriptible. Veuillez le créer et/ou changer son CHMOD (mettre 777) pour pouvoir lancer l\'installation.</p>
<p>Une fois ceci fait, actualisez la page pour continuer ou cliquez <a href="">ici</a>.</p>';

//Variables générales
$LANG['page_title'] = 'Installation de PHPBoost';
$LANG['steps_list'] = 'Liste des étapes';
$LANG['introduction'] = 'Préambule';
$LANG['config_server'] = 'Configuration du serveur';
$LANG['database_config'] = 'Configuration base de données';
$LANG['advanced_config'] = 'Configuration du site';
$LANG['administrator_account_creation'] = 'Compte administrateur';
$LANG['end'] = 'Fin de l\'installation';
$LANG['install_progress'] = 'Progression de l\'installation';
$LANG['generated_by'] = 'Généré par %s';
$LANG['previous_step'] = 'Etape précédente';
$LANG['next_step'] = 'Etape suivante';
$LANG['query_loading'] = 'Chargement de la requête au serveur';
$LANG['query_sent'] = 'Requête envoyée au serveur, attente d\'une réponse';
$LANG['query_processing'] = 'Traitement de la requête en cours';
$LANG['query_success'] = 'Traitement terminé';
$LANG['query_failure'] = 'Traitement échoué';

//Introduction
$LANG['intro_title'] = 'Bienvenue dans l\'assistant d\'installation de PHPBoost';
$LANG['intro_explain'] = '<p>Merci d\'avoir accordé votre confiance à PHPBoost pour créer votre site web.</p>
<p>Pour installer PHPBoost vous devez disposer d\'un minimum d\'informations concernant votre hébergement qui devraient être fournies par votre hébergeur. L\'installation est entièrement automatisée, elle ne devrait prendre que quelques minutes. Cliquez sur la flèche ci-dessous pour démarrer le processus d\'installation.</p>
<p>Cordialement, l\'équipe PHPBoost</p>';
$LANG['intro_distribution'] = 'Distribution %s';
$LANG['intro_distribution_intro'] = '<p>Il existe différentes distributions de PHPBoost permettant à l\'utilisateur d\'obtenir automatiquement une configuration appropriée à ses besoins. Une distribution contient des modules ainsi que quelques paramétrages du système (noyau).</p>
<p>PHPBoost va s\'installer selon la configuration de cette distribution, vous pourrez évidemment par la suite modifier sa configuration et ajouter ou supprimer des modules.</p>';
$LANG['start_install'] = 'Commencer l\'installation';

//licence
$LANG['license'] = 'Licence';
$LANG['require_license_agreement'] = '<p>Vous devez accepter les termes de la licence GNU/GPL pour installer PHPBoost.</p><p>Vous trouverez une traduction non officielle de cette licence en français <img src="../images/stats/countries/fr.png" alt="Français" /> <a href="http://www.linux-france.org/article/these/gpl.html">ici</a>.</p>';
$LANG['license_agreement'] = 'Acceptation des termes de la licence';
$LANG['license_terms'] = 'Termes de la licence';
$LANG['please_agree_license'] = 'J\'ai pris connaissance des termes de la licence et je les accepte';
$LANG['alert_agree_license'] = 'Vous devez accepter la licence en cochant le formulaire associé pour pouvoir continuer !';

//Configuration du serveur
$LANG['config_server_title'] = 'Vérification de la configuration du serveur';
$LANG['config_server_explain'] = '<p>Avant de commencer les étapes d\'installation de PHPBoost, la configuration de votre serveur va être vérifiée afin d\'établir sa compatibilité avec PHPBoost.</p>
<div class="notice">Veillez à ce que chaque condition obligatoire soit vérifiée sans quoi vous risquez d\'avoir des problèmes en utilisant le logiciel.</div>
<p>En cas de problème n\'hésitez pas à poser vos questions sur le <a href="http://www.phpboost.com/forum/index.php">forum de support</a>.</p>';
$LANG['php_version'] = 'Version de PHP';
$LANG['check_php_version'] = 'PHP supérieur à 4.1.0';
$LANG['check_php_version_explain'] = '<span style="font-weight:bold;color:red;">Obligatoire :</span> Pour faire fonctionner PHPBoost, votre serveur doit être équipé d\'une version supérieure ou égale à PHP 4.1.0. Sans cela il vous sera impossible de le faire fonctionner correctement, contactez votre hébergeur ou migrez vers un serveur plus récent.';
$LANG['extensions'] = 'Extensions';
$LANG['check_extensions'] = 'Optionnel : L\'activation de ces extensions permet d\'apporter des fonctionnalités supplémentaires mais n\'est en aucun cas indispensable.';
$LANG['gd_library'] = 'Librairie GD';
$LANG['gd_library_explain'] = 'Librairie utilisée pour générer des images. Utile par exemple pour la protection anti robots, ou les diagrammes des statistiques du site. Certains modules peuvent également s\'en servir.';
$LANG['url_rewriting'] = 'URL Rewriting';
$LANG['url_rewriting_explain'] = 'Réécriture des adresses des pages qui les rend plus lisibles et plus propices au référencement sur les moteurs de recherche';
$LANG['auth_dir'] = 'Autorisations des dossiers';
$LANG['check_auth_dir'] = '<span style="font-weight:bold;color:red;">Obligatoire :</span> PHPBoost nécessite que certains dossiers soient inscriptibles. Si votre serveur le permet, leurs autorisations sont changées de façon automatique. Cependant certains serveurs empêchent la modification automatique des autorisations, il faut donc faire la manipulation manuellement, pour cela voir la <a href="http://www.phpboost.com/wiki/changer-le-chmod-d-un-dossier" title="Documentation PHPBoost : Changer le chmod">documentation PHPBoost</a> ou contactez votre hébergeur.';
$LANG['refresh_chmod'] = 'Revérifier les dossiers';
$LANG['existing'] = 'Existant';
$LANG['unexisting'] = 'Inexistant';
$LANG['writable'] = 'Inscriptible';
$LANG['unwritable'] = 'Non inscriptible';
$LANG['unknown'] = 'Indéterminable';
$LANG['config_server_dirs_not_ok'] = 'Les répertoires ne sont pas tous existants et/ou inscriptibles. Merci de le faire à la main pour pouvoir continuer.';

//Base de données
$LANG['db_title'] = 'Paramètres de connexion à la base de données';
$LANG['db_explain'] = '<p>Cette étape permet de générer le fichier de configuration qui retiendra les identifiants de connexion à votre base de données. Les tables permettant de faire fonctionner PHPBoost seront automatiquement créées lors de cette étape. Si vous ne connaissez pas les informations ci-dessous, contactez votre hébergeur qui vous les transmettra.</p>';
$LANG['dbms_paramters'] = 'Paramètres d\'accès au <acronym title="Système de Gestion de Base de Données">SGBD</acronym>';
$LANG['db_host_name'] = 'Nom de l\'hôte';
$LANG['db_host_name_explain'] = 'URL du serveur qui gère la base de données, <em>localhost</em> la plupart du temps.';
$LANG['db_login'] = 'Identifiant';
$LANG['db_login_explain'] = 'Fourni par l\'hébergeur';
$LANG['db_password'] = 'Mot de passe';
$LANG['db_password_explain'] = 'Fourni par l\'hébergeur';
$LANG['db_properties'] = 'Propriétés de la base de données';
$LANG['db_name'] = 'Nom de la base de données';
$LANG['db_name_explain'] = 'Fourni par l\'hébergeur. Si cette base n\'existe pas, PHPBoost essaiera de la créer si la configuration le lui permet.';
$LANG['db_prefix'] = 'Préfixe des tables';
$LANG['db_prefix_explain'] = 'Par défaut <em>phpboost_</em>. A changer si vous souhaitez installer plusieurs fois PHPBoost dans la même base de données.';
$LANG['test_db_config'] = 'Essayer';
$LANG['result'] = 'Résultats';
$LANG['empty_field'] = 'Le champ %s est vide';
$LANG['field_dbms'] = 'système de gestion de base de données';
$LANG['field_host'] = 'hôte';
$LANG['field_login'] = 'identifiant';
$LANG['field_password'] = 'mot de passe';
$LANG['field_database'] = 'nom de la base de données';
$LANG['db_error_connexion'] = 'Impossible de se connecter à la base de données. Merci de vérifier vos paramètres.';
$LANG['db_error_selection_not_creable'] = 'La base de données que vous avez indiquée n\'existe pas et le système n\'a pas l\'autorisation de la créer.';
$LANG['db_error_selection_but_created'] = 'La base de données que vous avez indiquée n\'existait pas mais a pu être créée par le système.';
$LANG['db_error_tables_already_exist'] = 'Il existe déjà une installation de PHPBoost sur cette base de données avec ce préfixe. Si vous continuez, ces tables seront supprimées et vous perdrez certainement des données.';
$LANG['db_success'] = 'La connexion à la base de données a été effectuée avec succès. Vous pouvez poursuivre l\'installation';
$LANG['db_unknown_error'] = 'Une erreur inconnue a été rencontrée.';
$LANG['require_hostname'] = 'Vous devez renseigner le nom de l\'hôte !';
$LANG['require_login'] = 'Vous devez renseigner l\'identifiant de connexion !';
$LANG['require_db_name'] = 'Vous devez renseigner le nom de la base de données !';
$LANG['db_result'] = 'Résultats du test';
$LANG['already_installed'] = 'Installation existante';
$LANG['already_installed_explain'] = '<p>La base de données sur laquelle vous souhaitez installer PHPBoost contient déjà une installation de PHPBoost.</p>
<p>Si vous effectuez l\'installation sur cette base de données avec cette configuration, vous écraserez les données présentes actuellement. Si vous voulez installer deux fois PHPBoost sur la même base de données, utilisez des préfixes différents.</p>';
$LANG['already_installed_overwrite'] = 'Je souhaite écraser l\'installation de PHPBoost déjà existante';

//configuraton du site
$LANG['site_config_title'] = 'Configuration du site';
$LANG['site_config_explain'] = '<p>La configuration de base du site va être créée dans cette étape afin de permettre à PHPBoost de fonctionner. Sachez cependant que toutes les données que vous allez rentrer seront ultérieurement modifiables dans le panneau d\'administration dans la rubrique configuration du site. Vous pourrez dans ce même panneau renseigner davantage d\'informations facultatives à propos de votre site.</p>';
$LANG['your_site'] = 'Votre site';
$LANG['site_url'] = 'Adresse du site :';
$LANG['site_url_explain'] = 'De la forme http://www.phpboost.com';
$LANG['site_path'] = 'Chemin de PHPBoost :';
$LANG['site_path_explain'] = 'Vide si votre site est à la racine du serveur, de la forme /dossier sinon';
$LANG['site_name'] = 'Nom du site';
$LANG['site_timezone'] = 'Fuseau horaire du site';
$LANG['site_timezone_explain'] = 'La valeur par défaut est celle correspondant à la localisation de votre serveur. Pour la France, il s\'agit de GMT + 1. Vous pourrez changer cette valeur par la suite dans le panneau d\'administration.';
$LANG['site_description'] = 'Description du site';
$LANG['site_description_explain'] = '(facultatif) Utile pour le référencement dans les moteurs de recherche';
$LANG['site_keywords'] = 'Mots clés du site';
$LANG['site_keywords_explain'] = '(facultatif) A rentrer séparés par des virgules, ils servent au référencement dans les moteurs de recherche';
$LANG['require_site_url'] = 'Vous devez entrer l\'adresse de votre site !';
$LANG['require_site_name'] = 'Vous devez entrer le nom de votre site !';
$LANG['confirm_site_url'] = 'L\'adresse du site que vous avez rentrée ne correspond pas à celle détectée par le serveur. Souhaitez vous vraiment choisir cette adresse ?';
$LANG['confirm_site_path'] = 'Le chemin du site sur le serveur que vous avez rentrée ne correspond pas à celle détectée par le serveur. Souhaitez vous vraiment choisir ce chemin ?';
$LANG['site_config_maintain_text'] = 'Le site est actuellement en maintenance.';
$LANG['site_config_mail_signature'] = 'Cordialement, l\'équipe du site.';
$LANG['site_config_msg_mbr'] = 'Bienvenue sur le site. Vous êtes membre du site, vous pouvez accéder à tous les espaces nécessitant un compte utilisateur, éditer votre profil et voir vos contributions.';
$LANG['site_config_msg_register'] = 'Vous vous apprêtez à vous enregistrer sur le site. Nous vous demandons d\'être poli et courtois dans vos interventions.<br />
<br />
Merci, l\'équipe du site.';

//administration
$LANG['admin_account_creation'] = 'Création du compte administrateur';
$LANG['admin_account_creation_explain'] = '<p>Ce compte donne accès au panneau d\'administration par lequel vous configurerez votre site. Vous pourrez modifier les informations concernant ce compte par la suite en consultant votre profil.</p>
<p>Par la suite, il sera possible de donner à plusieurs personnes le statut d\'administrateur, ce compte est celui du premier administrateur, sans lequel vous ne pourriez pas gérer le site.</p>';
$LANG['admin_account'] = 'Compte administrateur';
$LANG['admin_pseudo'] = 'Pseudo';
$LANG['admin_pseudo_explain'] = 'Minimum 3 caractères';
$LANG['admin_password'] = 'Mot de passe';
$LANG['admin_password_explain'] = 'Minimum 6 caractères';
$LANG['admin_password_repeat'] = 'Répéter le mot de passe';
$LANG['admin_mail'] = 'Courrier électronique';
$LANG['admin_mail_explain'] = 'Doit être valide pour recevoir le code de déverrouillage';
$LANG['admin_require_login'] = 'Vous devez entrer un pseudo';
$LANG['admin_login_too_short'] = 'Votre pseudo est trop court (3 caractères minimum)';
$LANG['admin_password_too_short'] = 'Votre mot de passe est trop court (6 caractères minimum)';
$LANG['admin_require_password'] = 'Vous devez entrer un mot de passe';
$LANG['admin_require_password_repeat'] = 'Vous devez confirmer votre mot de passe';
$LANG['admin_require_mail'] = 'Vous devez entrer une adresse de courier électronique';
$LANG['admin_passwords_error'] = 'Les deux mots de passe que vous avez entrés ne correspondent pas';
$LANG['admin_email_error'] = 'L\'adresse de courier électronique que vous avez entrée n\'a pas une forme correcte';
$LANG['admin_invalid_email_error'] = 'Mail invalide';
$LANG['admin_create_session'] = 'Me connecter à la fin de l\'installation';
$LANG['admin_auto_connection'] = 'Rester connecté systématiquement à chacune de mes visites';
$LANG['admin_error'] = 'Erreur';
$LANG['admin_mail_object'] = 'Identifiants de votre site créé avec PHPBoost (message à conserver)';
$LANG['admin_mail_unlock_code'] = 'Cher %s,

Tout d\'abord, merci d\'avoir choisi PHPBoost pour réaliser votre site, nous espérons qu\'il répondra au mieux à vos besoins. Pour tout problème n\'hésitez pas à vous rendre sur le forum http://www.phpboost.com/forum/index.php

Voici vos identifiants (ne les perdez pas, ils vous seront utiles pour administrer votre site et ne pourront plus être récupérés).

Identifiant: %s 
Password: %s

A conserver ce code (Il ne vous sera plus délivré) : %s

Ce code permet le déverrouillage de l\'administration en cas de tentative d\'intrusion dans l\'administration par un utilisateur mal intentionné, il vous sera demandé dans le formulaire de connexion directe à l\'administration (%s/admin/admin_index.php) 

Cordialement l\'équipe PHPBoost.';

//Fin de l'installation
$LANG['end_installation'] = '<fieldset>
							<legend>PHPBoost est désormais installé !</legend>
							<p class="success">L\'installation de PHPBoost s\'est déroulée avec succès. L\'équipe PHPBoost vous remercie de lui avoir fait confiance et est heureuse de vous compter parmi ses utilisateurs.</p>
							<p>Nous vous conseillons de vous tenir au courant de l\'évolution de PHPBoost via le site de la communauté francophone, <a href="http://www.phpboost.com">www.phpboost.com</a>. Vous serez automatiquement averti dans le panneau d\'administration de l\'arrivée de nouvelles mises à jour. Il est fortement conseillé de tenir votre système à jour afin de profiter des dernières nouveautés et de corriger les éventuelles failles ou erreurs.</p>
							<p class="warning">Par mesure de sécurité nous vous conseillons fortement de supprimer le dossier install et tout ce qu\'il contient, des personnes mal intentionnées pourraient relancer le script d\'installation et écraser certaines de vos données !</p>
							<p>N\'oubliez pas la <a href="http://www.phpboost.com/wiki/wiki.php">documentation</a> qui vous guidera dans l\'utilisation de PHPBoost ainsi que la <a href="http://www.phpboost.com/faq/faq.php"><acronym title="Foire Aux Questions">FAQ</acronym></a> qui répond aux questions les plus fréquentes.</p>
							<p>En cas de problème, rendez-vous sur le <a href="http://www.phpboost.com/forum/index.php">forum du support de PHPBoost</a>.</p>
						</fieldset>
						<fieldset>
							<legend>Remerciements</legend>
							<h2>Membres de la communauté</h2>
							<p>Merci à tous les membres de la communauté qui nous encouragent au quotidien et contribuent à la qualité du logiciel que ce soit en suggérant des nouvelles fonctionnalités ou en signalant des dysfonctionnements, ce qui permet d\'aboutir entre autres à une version 3.0 stable et efficace.</p>
							<p>Merci aux membres des équipes de PHPBoost et particulièrement à <strong>Ptithom</strong> et <strong>giliam</strong> de l\'équipe rédaction pour la documentation, <strong>KONA</strong>, <strong>Frenchbulldog</strong>, <strong>Grenouille</strong>, <strong>EnimSay</strong>, <strong>swan</strong> pour les graphismes, <strong>Gsgsd</strong>, <strong>Alain91</strong> et <strong>Crunchfamily</strong> de l\'équipe de développement de modules, <strong>Forensic</strong>, <strong>PiJean</strong> et <strong>Beowulf</strong> pour la traduction anglaise et <strong>Shadow</strong> et <strong>Kak Miortvi Pengvin</strong> pour la modération de la communauté.</p>
							<h2>Projets</h2>
							<p>PHPBoost utilise différents outils afin d\'élargir ses fonctionnalités sans augmenter trop le temps de développement. Ces outils sont tous libres, distribués sous la licence GNU/GPL pour la plupart.</p>
							<ul>
								<li><a href="http://notepad-plus.sourceforge.net">Notepad++</a> : Editeur de texte puissant très utilisé pour le développement de PHPBoost.</li>
								<li><a href="http://www.eclipse.org/pdt/">Eclipse <acronym title="PHP Development Tools">PDT</acronym></a> : <acronym title="Integrated Development Environment">IDE</acronym> PHP (outil de développement PHP) basé sur Eclipse.</li>
								<li><a href="http://tango.freedesktop.org/Tango_Desktop_Project">Tango Desktop Project</a> : Ensemble d\'icônes diverses utilisées sur l\'ensemble de PHPBoost.</li>
								<li><a href="http://www.phpconcept.net/pclzip/">PCLZIP</a> : Librairie permettant de travailler sur des archives au format Zip.</li>
								<li><a href="http://www.xm1math.net/phpmathpublisher/index_fr.html">PHPMathPublisher</a> : Ensemble de fonctions permettant de mettre en forme des formules mathématiques à partir d\'une syntaxe proche de celle du <a href="http://fr.wikipedia.org/wiki/LaTeX">LaTeX</a>.</li>
								<li><a href="http://tinymce.moxiecode.com/">TinyMCE</a> : Editeur <acronym title="What You See Is What You Get">WYSIWYG</acronym> permettant la mise en page à la volée.</li>
								<li><a href="http://qbnz.com/highlighter/">GeSHi</a> : Colorateur de code source dans de nombreux langages informatiques.</li>
								<li><a href="http://script.aculo.us/">script.aculo.us</a> : Framework Javascript et <acronym title="Asynchronous Javascript And XML">AJAX</acronym></li>
								<li><a href="http://www.alsacreations.fr/mp3-dewplayer.html">Dewplayer</a> : lecteur audio au format flash</li>
								<li><a href="http://flowplayer.org">Flowplayer</a> : lecteur vidéo au format flash</li>
							</ul>
						</fieldset>
						<fieldset>
							<legend>Crédits</legend>
							<ul>
								<li><strong>Régis VIARRE</strong> <em>(alias CrowkaiT)</em>, fondateur du projet PHPBoost et développeur</li>
								<li><strong>Benoît SAUTEL</strong> <em>(alias ben.popeye)</em>, développeur</li>
								<li><strong>Loïc ROUCHON</strong> <em>(alias horn)</em>, développeur</li>
							</ul>
						</fieldset>';
$LANG['site_index'] = 'Aller à l\'accueil du site';
$LANG['admin_index'] = 'Aller dans le panneau d\'administration';

//Divers
$LANG['yes'] = 'Oui';
$LANG['no'] = 'Non';
$LANG['appendices'] = 'Annexes';
$LANG['documentation'] = 'Documentation';
$LANG['documentation_link'] = 'http://www.phpboost.com/wiki/installer-phpboost';
$LANG['restart_installation'] = 'Recommencer l\'installation';
$LANG['confirm_restart_installation'] = addslashes('Etes-vous certain de vouloir recommencer l\'installation ?');
$LANG['change_lang'] = 'Changer de langue';
$LANG['change'] = 'Changer';

$LANG['powered_by'] = 'Boosté par';
$LANG['phpboost_right'] = '';

?>
