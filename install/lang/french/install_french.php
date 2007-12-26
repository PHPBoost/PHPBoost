<?php
/*##################################################
 *                                install.php
 *                            -------------------
 *   begin                : August 23, 2007
 *   copyright          : (C) 2007 	SAUTEL Benoit
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
#                                                           French                                                                               #
####################################################

$LANG = array();
$LANG['page_title'] = 'Installation de PHPBoost';
$LANG['steps_list'] = 'Liste des étapes';
$LANG['introduction'] = 'Préambule';
$LANG['config_server'] = 'Configuration du serveur';
$LANG['database_config'] = 'Configuration de la base de données';
$LANG['advanced_config'] = 'Configuration du site';
$LANG['administrator_account_creation'] = 'Compte administrateur';
$LANG['modules_installation'] = 'Installation des modules';
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
$LANG['intro_explain'] = 'Merci d\'avoir accordé votre confiance à PHPBoost pour créer votre site web.<br /><br />
Pour installer PHPBoost vous devez disposer d\'un minimum d\'informations concernant votre hébergement qui devraient être fournies par votre hébergeur. L\'installation est entièrement automatisée, elle ne devrait prendre que quelques minutes. Cliquez sur la flèche ci-dessous pour démarrer le processus d\'installation.<br /><br />
Cordialement l\'équipe PHPBoost';
$LANG['start_install'] = 'Commencer l\'installation';

//licence
$LANG['license'] = 'Licence';
$LANG['require_license_agreement'] = 'Vous devez accepter les termes de la licence GPL pour installer PHPBoost.';
$LANG['license_agreement'] = 'Acceptation des termes de la licence';
$LANG['please_agree_license'] = 'J\'ai pris connaissance et j\'accepte les termes de la licence.';
$LANG['alert_agree_license'] = 'Vous devez accepter la licence en cochant le formulaire associé pour pouvoir continuer !';

//Configuration du serveur
$LANG['config_server_explain'] = 'Vérification du serveur<br />
Avant de commencer les étapes d\'installation de PHPBoost, la configuration de votre serveur va être vérifiée afin d\'établir sa compatibilité avec PHPBoost. Veillez à ce que chaque condition obligatoire soit vérifiée sans quoi vous ne pourrez pas installer PHPBoost.<br />
En cas de problème n\'hésitez pas à poser vos questions sur le forum <a href="http://www.phpboost.com/forum/index.php">PHPBoost</a>.';
$LANG['php_version'] = 'Version de PHP';
$LANG['check_php_version'] = 'PHP supérieur à 4.1.0';
$LANG['check_php_version_explain'] = '<span style="font-weight:bold;color:red;">Obligatoire :</span> Pour faire fonctionner PHPBoost, votre serveur doit être équipé d\'une version supérieure ou égale à PHP 4.1.0. Sans cela il vous sera impossible de le faire fonctionner correctement, contactez votre hébergeur ou migrez vers un serveur plus récent.';
$LANG['extensions'] = 'Extensions';
$LANG['check_extensions'] = 'Optionnel : L\'activation de ces extensions permet d\'apporter des fonctionnalités supplémentaires mais n\'est en aucun cas indispensable.';
$LANG['gd_library'] = 'Librairie GD';
$LANG['gd_library_explain'] = 'Code de vérification et certains modules concernant les images';
$LANG['url_rewriting'] = 'URL Rewriting';
$LANG['url_rewriting_explain'] = 'Réécriture des adresses des pages';
$LANG['auth_dir'] = 'Autorisations des dossiers';
$LANG['check_auth_dir'] = '<span style="font-weight:bold;color:red;">Obligatoire :</span> PHPBoost nécessite que certains dossiers soient autorisés en écriture, si votre serveur le permet, leurs autorisations sont changées de façon automatique. Cependant certains serveurs empêchent la modification automatique des autorisations, il faut donc faire la manipulation manuellement, pour cela voir la <a href="http://www.phpboost.com/wiki/changer-le-chmod-d-un-dossier" title="Documentation PHPBoost : Changer le chmod">documentation PHPBoost</a> ou contactez votre hébégeur.';
$LANG['refresh_chmod'] = 'Revérifier les dossiers';
$LANG['existing'] = 'Existant';
$LANG['unexisting'] = 'Inexistant';
$LANG['writable'] = 'Inscriptible';
$LANG['unwritable'] = 'Non inscriptible';
$LANG['unknown'] = 'Indéterminable';

//Base de données
$LANG['db_explain'] = 'Cette étape permet de générer le fichier de configuration qui retiendra les identifiants de connexion à votre base de données. Les tables permettant de faire fonctionner PHPBoost seront automatiquement créées lors de cette étape. Si vous ne connaissez pas les informations ci-dessous, contactez votre hébérgeur qui vous les transmettra.';
$LANG['dbms'] = 'Système de gestion de base de données';
$LANG['choose_dbms'] = 'Choisir le système';
$LANG['choose_dbms_explain'] = 'MySQL par défaut sur la plupart des serveurs';
$LANG['db_informations'] = 'Paramètres de la base de données';
$LANG['db_host_name'] = 'Nom de l\'hôte';
$LANG['db_host_name_explain'] = 'Adresse du serveur qui gère la base de données, localhost la plupart du temps';
$LANG['db_login'] = 'Identifiant';
$LANG['db_login_explain'] = 'Fourni par l\'hébergeur';
$LANG['db_password'] = 'Mot de passe';
$LANG['db_password_explain'] = 'Fourni par l\'hébergeur';
$LANG['db_name'] = 'Nom de la base de données';
$LANG['db_name_explain'] = 'Fourni par l\'hébergeur';
$LANG['db_prefix'] = 'Prefixe des tables';
$LANG['db_prefix_explain'] = 'Par défaut <em>phpboost_</em>';
$LANG['test_db_config'] = 'Essayer';
$LANG['result'] = 'Résultats';
$LANG['empty_field'] = 'Le champ %s est vide';
$LANG['field_dbms'] = 'système de gestion de base de données';
$LANG['field_host'] = 'hôte';
$LANG['field_login'] = 'identifiant';
$LANG['field_password'] = 'mot de passe';
$LANG['field_database'] = 'nom de la base de données';
$LANG['db_error_dbms'] = 'Le système de gestion de base de données que vous avez choisi n\'existe pas';
$LANG['db_error_connexion'] = 'Impossible de se connecter à la base de données. Merci de vérifier vos paramètres.';
$LANG['db_error_selection'] = 'Impossible de sélectionner la base de données. Merci de vérifier son existence.';
$LANG['db_success'] = 'La connexion à la base de données a été effectuée avec succès. Vous pouvez poursuivre l\'installation';
$LANG['require_hostname'] = 'Vous devez renseigner le nom de l\'hôte !';
$LANG['require_login'] = 'Vous devez renseigner l\'identifiant de connexion !';
$LANG['require_db_name'] = 'Vous devez renseigner le nom de la base de données !';
$LANG['db_result'] = 'Résultats du test';

//configuraton du site
$LANG['config_site_explain'] = 'Configuration du site<br />
La configuration de base du site va être créée dans cette étape afin de permettre à PHPBoost de fonctionner. Sachez cependant que toutes les données que vous allez rentrer seront ultérieurement modifiables dans le panneau d\'administration dans la rubrique configuration du site. Vous pourrez dans ce même panneau renseigner davantage d\'informations facultatives à propos de votre site.';
$LANG['your_site'] = 'Votre site';
$LANG['site_url'] = 'Adresse du site :';
$LANG['site_url_explain'] = 'De la forme http://www.google.fr';
$LANG['site_path'] = 'Chemin de PHPBoost :';
$LANG['site_path_explain'] = 'Vide si votre site est à la racine du serveur, de la forme /dossier sinon';
$LANG['default_language'] = 'Langue du site par défaut';
$LANG['default_theme'] = 'Thème du site par défaut';
$LANG['site_name'] = 'Nom du site';
$LANG['site_description'] = 'Description du site';
$LANG['site_description_explain'] = '(facultatif) Utile pour le référencement dans les moteurs de recherche';
$LANG['site_keywords'] = 'Mots clés du site';
$LANG['site_keywords_explain'] = '(facultatif) A rentrer séparés par des virgules, ils servent au référencement dans les moteurs de recherche';
$LANG['require_site_url'] = 'Vous devez entrer l\'adresse de votre site !';
$LANG['require_site_name'] = 'Vous devez entrer le nom de votre site !';
$LANG['confirm_site_url'] = 'L\'adresse du site que vous avez rentrée ne correspond pas à celle détectée par le serveur. Souhaitez vous vraiment choisir cette adresse ?';
$LANG['confirm_site_path'] = 'Le chemin du site sur le serveur que vous avez rentrée ne correspond pas à celle détectée par le serveur. Souhaitez vous vraiment choisir ce chemin ?';

//administration
$LANG['admin_account_creation_explain'] = 'Création du compte administrateur
<br />Ce compte donne accès au panneau d\'administration par lequel vous configurerez votre site. Vous pourrez modifier les informations concernant ce compte par la suite en consultant votre profil.';
$LANG['admin_account'] = 'Compte administrateur';
$LANG['admin_pseudo'] = 'Pseudo';
$LANG['admin_pseudo_explain'] = 'Minimum 3 caractères';
$LANG['admin_password'] = 'Mot de passe';
$LANG['admin_password_explain'] = 'Minimum 6 caractères';
$LANG['admin_password_repeat'] = 'Répéter le mot de passe';
$LANG['admin_mail'] = 'Courrier électronique';
$LANG['admin_mail_explain'] = 'Doit être valide pour recevoir le code de déverrouillage';
$LANG['admin_lang'] = 'Langue';
$LANG['admin_require_login'] = 'Vous devez entrer un pseudo';
$LANG['admin_login_too_short'] = 'Votre pseudo est trop court (3 caractères minimum)';
$LANG['admin_require_password'] = 'Vous devez entrer un mot de passe';
$LANG['admin_require_password_repeat'] = 'Vous devez confirmer votre mot de passe';
$LANG['admin_require_mail'] = 'Vous devez entrer une adresse email';
$LANG['admin_passwords_error'] = 'Les deux mots de passe que vous avez entrés ne correspondent pas';
$LANG['admin_email_error'] = 'L\'adresse email que vous avez fournie n\'a pas une forme correcte';
$LANG['admin_create_session'] = 'Me connecter à la fin de l\'installation';
$LANG['admin_auto_connection'] = 'Rester connecté systématiquement à chacune de mes visites';
$LANG['admin_error'] = 'Erreur';
$LANG['admin_mail_object'] = 'PHPBoost : message à conserver';
$LANG['admin_mail_unlock_code'] = 'Cher %s,

Tout d\'abord, merci d\'avoir choisi PHPBoost pour réaliser votre site, nous espérons qu\'il repondra au mieux à vos besoins. Pour tout problème n\'hésitez pas à vous rendre sur le forum http://www.phpboost.com/forum/index.php

Voici vos identifiants (ne les perdez pas, ils vous seront utiles pour administrer votre site et ne pourront plus être récupérés).

Identifiant: %s 
Password: %s

A conserver ce code (Il ne vous sera plus délivré) : %s

Ce code permet le déverrouillage de l\'administration en cas de tentative d\'intrusion dans l\'administration par un utilisateur mal intentionné, il vous sera demandé dans le formulaire de connexion directe à l\'administration (%s/admin/admin.php) 

Cordialement l\'équipe PHPBoost.';

//Installation des modules
$LANG['modules_explain'] = 'Installation des modules
<br />Vous pouvez dès maintenant installer des modules qui vous permettront d\'élaborer à votre convenance votre site. Nous vous proposons quelques préselections afin de faciliter l\'installation mais vous pouvez composer votre propre sélection. A noter que vous pourrez par la suite installer et désinstaller n\'importe quel module, cette étape ne vous engage à rien mais vous permet de partir sur une configuration adaptée à vos besoins.';
$LANG['modules_list'] = 'Liste des modules disponibles';
$LANG['modules_preselections'] = 'Présélections de modules proposées';
$LANG['modules_no_module'] = 'Aucun module';
$LANG['modules_all'] = 'Tous les modules disponibles';
$LANG['modules_community'] = 'Portail communautaire';
$LANG['modules_publication'] = 'Site de publication';
$LANG['modules_perso'] = 'Personnalisé';
$LANG['modules_other_options'] = 'Autres options';
$LANG['modules_activ_member_accounts'] = 'Activer l\'inscription des membres';
$LANG['modules_index_module'] = 'Module de démarrage : ';
$LANG['modules_default_index'] = 'Page par défaut';
$LANG['modules_require_javascript'] = 'Vous devez activer le javascript pour pouvoir profiter pleinement des préselections et de la page de démarrage';

//Fin de l'installation
$LANG['end_installation'] = '<fieldset>
							<legend>PHPBoost est désormais installé !</legend>
							<p class="success">
								L\'installation de PHPBoost s\'est déroulée avec succès. L\'équipe PHPBoost vous remercie de lui avoir fait confiance et est heureuse de vous compter parmi ses utilisateurs.
							</p>
							<p>Sur l\'accueil de l\'administration, vous retrouverez les news du site officiel de PHPBoost en temps réel, pensez à y jeter un coup de d\'oeil de temps en temps pour être au courant des nouveautés. Sur cette même page vous serez aussi averti des mises à jour disponibles concernant le noyau ou un de vos modules. Nous vous conseillons de tenir votre version de PHPBoost à jour afin de profiter des dernières fonctionnalités ainsi que de corriger les éventuelles failles ou erreurs.</p>
							<p class="warning">
								Par mesure de sécurité nous vous conseillons fortement de supprimer le dossier install et tout ce qu\'il contient, des personnes mal intentionnées pourraient relancer le script d\'installation et écraser certaines de vos données !</p>
							<p>N\'oubliez pas la <a href="http://www.phpboost.com/wiki/index.php">documentation</a> qui vous guidera dans l\'utilisation de PHPBoost.</p>
							<p>En cas de problème, rendez-vous sur le forum du support de PHPBoost : <a href="http://www.phpboost.com/forum/index.php">forum PHPBoost</a>.</p>
						</fieldset>
						<fieldset>
							<legend>Remerciements</legend>
							Membres
							<br />
							<ul>
								<li>Merci à tous les membres qui nous ont encouragé et qui nous ont signalé tous les bugs qu\'ils ont pu rencontrer, ce qui aura permis à PHPBoost 2.0 d\'être stable.</li>
								<li>Merci en particulier à Ptithom qui a passé beaucoup de temps à tester la version béta et à nous rédiger une documentation complète.</li>
							</ul>
							<br />
							Projets
							<br />
							<ul>
								<li><a href="http://notepad-plus.sourceforge.net">Notepad++</a> : Editeur texte surpuissant utilisé pour la totalité du développement, un immense merci!</li>
								<li><a href="http://tango.freedesktop.org/Tango_Desktop_Project">Tango Desktop Project</a> : Ensemble d\'icônes diverses utilisées sur l\'ensemble de PHPBoost.</li>
								<li><a href="http://www.phpconcept.net/pclzip/">PCLZIP par PHPConcept</a> : Librairie permettant de travailler sur des archives au format Zip.</li>
								<li><a href="http://www.xm1math.net/phpmathpublisher/index_fr.html">PHPMathPublisher</a> : Ensemble de fonctions permettant de mettre en forme des formules mathématiques à partir d\'une syntaxe proche de celle du <a href="http://fr.wikipedia.org/wiki/LaTeX">LaTeX</a>.</li>
							</ul>
							<p style="text-align:center"><img src="images/npp_logo.gif" alt="" /></p>
						</fieldset>
						<fieldset>
							<legend>Crédits</legend>
							<ul>
								<li>Régis VIARRE <em>(alias CrowkaiT)</em>, fondateur du projet PHPBoost et développeur</li>
								<li>Benoît SAUTEL <em>(alias ben.popeye)</em>, développeur</li>
							</ul>
						</fieldset>';
$LANG['site_index'] = 'Aller à l\'accueil du site';
						
//Enregistrement en ligne
$LANG['register_online'] = 'Enregistrement en ligne';
$LANG['register_online_explain'] = 'Il est possible de vous enregistrer automatiquement en ligne. L\'enregistrement en ligne permettra à votre site d\'apparaître automatiquement sur la liste des portails PHPBoost installés du site officiel de PHPBoost (<a href="http://www.phpboost.com/phpboost/list.php">liste des portails installés</a>).
<br />
Vous n\'êtes pas obligé de vous enregistrer, nous vous proposons simplement ce service afin de vous aider à faire connaître votre site.  Si vous installez PHPBoost pour le tester en local ou en ligne ou que vous souhaitez que votre site ne soit pas connu du public vous ne devez pas l\'enregistrer.
<br />
<div class="notice">Attention : vous devez être connecté à Internet pour pouvoir enregistrer votre site en ligne.</div>';
$LANG['register'] = 'M\'enregistrer';
$LANG['register_i_want_to'] = 'Je souhaite m\'enregistrer en ligne et ainsi apparaître sur le site officiel de PHPBoost.';

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
		
?>