<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 08 02
 * @since       PHPBoost 3.0 - 2012 05 30
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                      French                      #
####################################################

$lang = array(
	//Variables générales
	'update.title' => 'Mise à jour de PHPBoost',
	'steps.list' => 'Liste des étapes',
	'step.list.introduction' => 'Préambule',
	'step.list.license' => 'Licence',
	'step.list.server' => 'Configuration du serveur',
	'step.list.database' => 'Configuration base de données',
	'step.list.website' => 'Configuration du site',
	'step.list.execute' => 'Mise à jour',
	'step.list.end' => 'Fin de la mise à jour',
	'installation.progression' => 'Progression de la mise à jour',
	'language.change' => 'Changer de langue',
	'change' => 'Changer',
	'step.previous' => 'Étape précédente',
	'step.next' => 'Étape suivante',
	'yes' => 'Oui',
	'no' => 'Non',
	'unknown' => 'Inconnu',
	'generatedBy' => 'Généré par %s',
	'poweredBy' => 'Boosté par',
	'phpboost.link' => 'Lien vers le site officiel de PHPBoost CMS',
	'phpboost.rights' => '',
	'phpboost.slogan' => 'Créez votre site internet facilement et gratuitement !',
	'phpboost.logo' => 'Logo PHPBoost',

//Introduction
	'step.introduction.title' => 'Préambule',
	'step.introduction.message' => 'Bienvenue dans l\'assistant de mise à jour de PHPBoost',
	'step.introduction.explanation' => '<p>Merci d\'avoir accordé votre confiance à PHPBoost pour créer votre site web.</p>
<p>Pour mettre à jour PHPBoost vous devez disposer d\'un minimum d\'informations concernant votre hébergement qui devraient être fournies par votre hébergeur. La mise à jour est entièrement automatisée, elle ne devrait prendre que quelques minutes. Cliquez sur la flèche ci-dessous pour démarrer le processus de mise à jour.</p>',
	'step.introduction.maintenance_notice' => '<div class="message-helper bgc notice">Votre site va automatiquement être placé en maintenance. Pensez à désactiver la maintenance lorsque vous aurez vérifié que tout fonctionne correctement.</div>',
	'step.introduction.team_signature' => '<p>Cordialement, l\'équipe PHPBoost</p>',

//Configuration du serveur
	'step.server.title' => 'Vérification de la configuration du serveur',
	'step.server.explanation' => '<p>Avant de commencer la mise à jour de PHPBoost, la configuration de votre serveur va être vérifiée afin d\'établir sa compatibilité avec PHPBoost.</p><p>En cas de problème n\'hésitez pas à poser vos questions sur le <a href="https://www.phpboost.com/forum/">forum de support</a>.</p>
<div class="message-helper bgc notice">Veillez à ce que chaque condition obligatoire soit vérifiée sans quoi vous risquez d\'avoir des problèmes en utilisant le logiciel.</div>',
	'php.version' => 'Version de PHP',
	'php.version.check' => 'PHP supérieur à :min_php_version',
	'php.version.check.explanation' => '<span style="font-weight:bold;color:red;">Obligatoire :</span> Pour faire fonctionner PHPBoost, votre serveur doit être équipé d\'une version supérieure ou égale à PHP :min_php_version. Sans cela il vous sera impossible de le faire fonctionner correctement, contactez votre hébergeur ou migrez vers un serveur plus récent.',
	'php.extensions' => 'Extensions',
	'php.extensions.check' => 'L\'activation de ces extensions permet d\'apporter des fonctionnalités supplémentaires mais n\'est en aucun cas indispensable (sauf la librairie MBstring).',
	'php.extensions.check.gdLibrary' => 'Librairie GD',
	'php.extensions.check.gdLibrary.explanation' => 'Librairie utilisée pour générer des images. Utile par exemple pour la protection anti robots ou les diagrammes des statistiques du site. Certains modules peuvent également s\'en servir.',
	'php.extensions.check.curlLibrary' => 'Librairie Curl',
	'php.extensions.check.curlLibrary.explanation' => 'Librairie utilisée pour récupération d\'éléments distants. Nécessaire pour faire fonctionner l\'authentification externe par exemple.',
	'php.extensions.check.mbstringLibrary' => 'Librairie MBstring',
	'php.extensions.check.mbstringLibrary.explanation' => 'Librairie utilisée pour la gestion des caractères UTF-8. Obligatoire pour avoir un site fonctionnel.',
	'php.extensions.check.mbstringLibrary.error' => 'L\'extension php <b>mbstring</b> n\'est pas activée. Veuillez l\'activer ou contactez votre hébergeur avant de pouvoir poursuivre l\'installation.',
	'server.urlRewriting' => 'URL Rewriting',
	'server.urlRewriting.explanation' => 'Réécriture des adresses des pages qui les rend plus lisibles et plus propices au référencement sur les moteurs de recherche',
	'folders.chmod' => 'Autorisations des dossiers',
	'folders.chmod.check' => '<span style="font-weight:bold;color:red;">Obligatoire :</span> PHPBoost nécessite que certains dossiers soient inscriptibles. Si votre serveur le permet, leurs autorisations sont changées de façon automatique. Cependant certains serveurs empêchent la modification automatique des autorisations, il faut donc faire la manipulation manuellement, pour cela voir la <a href="https://www.phpboost.com/wiki/changer-le-chmod-d-un-dossier" aria-label="Documentation PHPBoost : Changer le chmod">documentation PHPBoost</a> ou contactez votre hébergeur.',
	'folders.chmod.refresh' => 'Revérifier les dossiers',
	'folder.exists' => 'Existant',
	'folder.doesNotExist' => 'Inexistant',
	'folder.isWritable' => 'Inscriptible',
	'folder.isNotWritable' => 'Non inscriptible',
	'folders.chmod.error' => 'Les répertoires ne sont pas tous existants et/ou inscriptibles. Merci de le faire à la main pour pouvoir continuer.',

//Base de données
	'step.dbConfig.title' => 'Configuration base de données',
	'db.parameters.config' => 'Paramètres de connexion à la base de données',
	'db.parameters.config.explanation' => '<p>Cette étape permet de générer le fichier de configuration qui retiendra les identifiants de connexion à votre base de données. Si vous ne connaissez pas les informations ci-dessous, contactez votre hébergeur qui vous les transmettra.</p>',
	'dbms.parameters' => 'Paramètres d\'accès au <acronym aria-label="Système de Gestion de Base de Données">SGBD</acronym>',
	'dbms.host' => 'Nom de l\'hôte',
	'dbms.host.explanation' => 'URL du serveur qui gère la base de données, <em>localhost</em> la plupart du temps.',
	'dbms.port' => 'Port du serveur',
	'dbms.port.explanation' => 'Port du serveur qui gère la base de données, <em>3306</em> la plupart du temps.',
	'dbms.login' => 'Identifiant',
	'dbms.login.explanation' => 'Fourni par l\'hébergeur',
	'dbms.password' => 'Mot de passe',
	'dbms.password.explanation' => 'Fourni par l\'hébergeur',
	'schema.properties' => 'Propriétés de la base de données',
	'schema' => 'Nom de la base de données',
	'schema.explanation' => 'Fourni par l\'hébergeur. Si la base de données n\'existe pas, PHPBoost essayera de la créer.',
	'schema.tablePrefix' => 'Préfixe des tables',
	'schema.tablePrefix.explanation' => 'Par défaut <em>phpboost_</em>. A changer si vous avez installé plusieurs fois PHPBoost dans la même base de données.',
	'db.config.check' => 'Essayer',
	'db.connection.success' => 'La connexion à la base de données a été effectuée avec succès. Vous pouvez poursuivre l\'installation',
	'db.connection.error' => 'Impossible de se connecter à la base de données. Merci de vérifier vos paramètres.',
	'db.creation.error' => 'La base de données que vous avez indiquée n\'existe pas.',
	'db.unknown.error' => 'Une erreur inconnue a été rencontrée.',
	'db.required.host' => 'Vous devez renseigner le nom de l\'hôte !',
	'db.required.port' => 'Vous devez renseigner le port !',
	'db.required.login' => 'Vous devez renseigner l\'identifiant de connexion !',
	'db.required.schema' => 'Vous devez renseigner le nom de la base de données !',
	'db.unexisting_database' => 'La base de donnée n\'existe pas. Veuillez vérifier vos paramètres.',
	'phpboost.notInstalled' => 'Installation inexistante',
	'phpboost.notInstalled.explanation' => '<p>La base de données sur laquelle vous souhaitez mettre à jour PHPBoost ne contient pas d\'installation.</p>
	<p> Veuillez vérifier que vous avez bien saisi le bon préfixe et la bonne base de données.</p>',

//Execute update
	'congratulations' => 'Félicitations !',
	'step.execute.title' => 'Exécuter la mise à jour',
	'step.execute.update_in_progress' => 'Mise à jour en cours',
	'step.execute.message' => 'Mise à jour du site',
	'step.execute.explanation' => 'Cette étape va convertir votre site PHPBoost ' . GeneralConfig::load()->get_phpboost_major_version() . ' vers PHPBoost ' . UpdateServices::NEW_KERNEL_VERSION . '.
	<br /><br />
	Attention cette étape est irréversible, veuillez par précaution sauvegarder votre base de données au préalable !',
	'step.execute.incompatible_modules' => 'Les modules suivants vont être désactivés car ils ne sont pas compatibles avec PHPBoost ' . UpdateServices::NEW_KERNEL_VERSION . ' : :modules.
	<br />Pour les réactiver, téléchargez leur version compatible sur le site <a href="https://www.phpboost.com/download">PHPBoost</a> si elle existe et rendez-vous sur la page mise à jour de module de votre site pour les mettre à jour (ils seront alors réactivés automatiquement).',
	'step.execute.incompatible_module' => 'Le module :modules va être désactivé car il n\'est pas compatible avec PHPBoost ' . UpdateServices::NEW_KERNEL_VERSION . '.
	<br />Pour le réactiver, téléchargez sa version compatible sur le site <a href="https://www.phpboost.com/download">PHPBoost</a> si elle existe et rendez-vous sur la page mise à jour de module de votre site pour le mettre à jour (il sera alors réactivé automatiquement).',
	'step.execute.incompatible_module.default' => '<br /><br />Le module <b>:old_default</b> placé en démarrage du site va être remplacé par le module <b>:new_default</b>. Quand vous aurez installé la nouvelle version compatible du module <b>:old_default</b> pensez à reconfigurer la page de démarrage de votre site dans la configuration générale.',
	'step.execute.incompatible_themes' => 'Les thèmes suivants vont être désactivés car ils ne sont pas compatibles avec PHPBoost ' . UpdateServices::NEW_KERNEL_VERSION . ' : :themes.
	<br />Pour les réactiver, téléchargez leur version compatible sur le site <a href="https://www.phpboost.com/download">PHPBoost</a> si elle existe, mettez à jour les thèmes sur le FTP de votre site dans le dossier templates puis rendez-vous sur la page de gestion des thèmes de votre site pour les réactiver.',
	'step.execute.incompatible_theme' => 'Le thème :themes va être désactivé car il n\'est pas compatible avec PHPBoost ' . UpdateServices::NEW_KERNEL_VERSION . '.
	<br />Pour le réactiver, téléchargez sa version compatible sur le site <a href="https://www.phpboost.com/download">PHPBoost</a> si elle existe, mettez à jour le thème sur le FTP de votre site dans le dossier templates puis rendez-vous sur la page de gestion des thèmes de votre site pour le réactiver.',
	'step.execute.incompatible_theme.default' => '<br /><br />Le thème <b>:old_default</b> par défaut du site va être remplacé par le thème <b>:new_default</b>. Quand vous aurez installé la nouvelle version compatible du thème <b>:old_default</b> pensez à reconfigurer le thème par défaut de votre site dans la configuration générale (si c\'était le seul thème désinstallez ensuite le thème <b>:new_default</b> pour que les utilisateurs du site aient bien ce thème actif).',
	'step.execute.incompatible_langs' => 'Les langues suivantes vont être désactivées car elles ne sont pas compatibles avec PHPBoost ' . UpdateServices::NEW_KERNEL_VERSION . ' : :langs.
	<br />Pour les réactiver, téléchargez leur version compatible sur le site <a href="https://www.phpboost.com/download">PHPBoost</a> si elle existe, mettez à jour les langues sur le FTP de votre site dans le dossier lang puis rendez-vous sur la page de gestion des langues de votre site pour les réactiver.',
	'step.execute.incompatible_lang' => 'La langue :langs va être désactivée car elle n\'est pas compatible avec PHPBoost ' . UpdateServices::NEW_KERNEL_VERSION . '.
	<br />Pour la réactiver, téléchargez sa version compatible sur le site <a href="https://www.phpboost.com/download">PHPBoost</a> si elle existe, mettez à jour la langue sur le FTP de votre site  dans le dossier lang puis rendez-vous sur la page de gestion des langues de votre site pour la réactiver.',
	'step.execute.incompatible_lang.default' => '<br /><br />La langue <b>:old_default</b> par défaut du site va être remplacée par la langue <b>:new_default</b>. Quand vous aurez installé la nouvelle version compatible de la langue <b>:old_default</b> pensez à reconfigurer la langue par défaut de votre site dans la configuration générale (si c\'était la seule langue désinstallez ensuite la langue <b>:new_default</b> pour que les utilisateurs du site aient bien cette active).',

//Finish update
	'finish.message' => '<fieldset>
							<legend>PHPBoost est désormais mis à jour !</legend>
							<div class="fielset-inset">
								<p class="message-helper bgc success">La mise à jour de PHPBoost s\'est déroulée avec succès. L\'équipe PHPBoost vous remercie de lui avoir fait confiance et est heureuse de vous compter parmi ses utilisateurs.</p>
								<p class="message-helper bgc warning">Merci de récupérer le <a href="' . GeneralConfig::load()->get_complete_site_url() . '/update/update_log.txt" download>fichier de log</a> de votre migration, il pourra vous être demandé sur le forum PHPBoost en cas de demande de support sur votre migration.</p>
								' . (class_exists('FacebookSocialNetwork') ? '<p class="message-helper bgc warning">Pour ceux qui utilisent l\'authentification par Facebook, rendez-vous sur la page de configuration de votre application Facebook sur le site <a href="https://developers.facebook.com">Facebook développeurs</a> et modifiez l\'URL de redirection en : <a href="' . UserUrlBuilder::connect(FacebookSocialNetwork::SOCIAL_NETWORK_ID)->absolute() . '">' . UserUrlBuilder::connect(FacebookSocialNetwork::SOCIAL_NETWORK_ID)->absolute() . '</a>.</p>' : '') . '
								<p>Nous vous conseillons de vous tenir au courant de l\'évolution de PHPBoost via le site de la communauté francophone, <a href="https://www.phpboost.com">www.phpboost.com</a>. Vous serez automatiquement averti dans le panneau d\'administration de l\'arrivée de nouvelles mises à jour. Il est fortement conseillé de tenir votre système à jour afin de profiter des dernières nouveautés et de corriger les éventuelles failles ou erreurs.</p>
								<p class="message-helper bgc warning">Par mesure de sécurité nous vous conseillons fortement de supprimer le dossier <b>update</b> et tout ce qu\'il contient, des personnes mal intentionnées pourraient relancer le script de mise à jour et écraser certaines de vos données ! Une option vous sera proposée une fois connecté sur le site pour effectuer cette suppression.</p>
								<p>N\'oubliez pas la <a href="https://www.phpboost.com/wiki/">documentation</a> qui vous guidera dans l\'utilisation de PHPBoost ainsi que la <a href="https://www.phpboost.com/faq/"><acronym aria-label="Foire Aux Questions">FAQ</acronym></a> qui répond aux questions les plus fréquentes.</p>
								<p>En cas de problème, rendez-vous sur le <a href="https://www.phpboost.com/forum/">forum du support de PHPBoost</a>.</p>
							</div>
						</fieldset>
						<fieldset>
							<legend>Remerciements</legend>
							<div class="fielset-inset">
								<h2>Membres de la communauté</h2>
								<p>Merci à tous les membres de la communauté qui nous encouragent au quotidien et contribuent à la qualité du logiciel que ce soit en suggérant des nouvelles fonctionnalités ou en signalant des dysfonctionnements, ce qui permet d\'aboutir entre autres à un CMS stable et efficace.</p>
								<p>Merci aux membres des équipes de PHPBoost et particulièrement à <strong>benflovideo</strong> de l\'équipe communication, <strong>mipel</strong>, <strong>olivierb</strong> et <strong>xela</strong> pour la documentation, <strong>ElenWii</strong> et <strong>babsolune</strong> pour les graphismes, <strong>benflovideo</strong>, <strong>mipel</strong> et <strong>olivierb</strong> pour la modération de la communauté et <strong>janus57</strong> pour l\'appui aux développements et à l\'aide de la communauté sur le forum.</p>
								<h2>Projets</h2>
								<p>PHPBoost utilise différents outils afin d\'élargir ses fonctionnalités sans augmenter trop le temps de développement. Ces outils sont tous libres, distribués sous la licence GNU/GPL pour la plupart.</p>
								<ul>
									<li><a href="https://notepad-plus-plus.org/fr">Notepad++</a> et <a href="http://sublimetext.com">Sublime Text</a> : Editeurs de texte puissants utilisés pour le développement de PHPBoost.</li>
									<li><a href="http://www.phpconcept.net/pclzip/">PCLZIP</a> : Librairie permettant de travailler sur des archives au format Zip.</li>
									<li><a href="http://www.xm1math.net/phpmathpublisher/index_fr.html">PHPMathPublisher</a> : Ensemble de fonctions permettant de mettre en forme des formules mathématiques à partir d\'une syntaxe proche de celle du <a href="http://fr.wikipedia.org/wiki/LaTeX">LaTeX</a>.</li>
									<li><a href="http://www.tinymce.com">TinyMCE</a> : Editeur <acronym aria-label="What You See Is What You Get">WYSIWYG</acronym> permettant la mise en page à la volée.</li>
									<li><a href="http://qbnz.com/highlighter/">GeSHi</a> : Colorateur de code source dans de nombreux langages informatiques.</li>
									<li><a href="http://jquery.com">jQuery</a> : Framework Javascript et <acronym aria-label="Asynchronous Javascript And XML">AJAX</acronym></li>
									<li><a href="http://flowplayer.org">Flowplayer</a> : lecteur vidéo au format flash</li>
									<li><a href="http://fontawesome.io">Font Awesome</a> : librairie d\'icônes</li>
									<li><a href="http://l-lin.github.io/font-awesome-animation/">Font Awesome Animation</a> : Animation pour la librairie Font Awesome</li>
									<li><a href="http://cornel.bopp-art.com/lightcase/">Lightcase.js</a> : Lightbox responsive</li>
									<li><a href="https://github.com/cssmenumaker/jQuery-Plugin-Responsive-Drop-Down">CssMenuMaker</a> : Menus responsive</li>
									<li><a href="http://www.jerrylow.com/basictable/">BasicTable.js</a> : Tables responsive</li>
								</ul>
							</div>
						</fieldset>
						<fieldset>
							<legend>Faire un don</legend>
							<div class="fielset-inset">
								Si vous souhaitez supporter PHPBoost financièrement, vous pouvez nous faire un don via paypal :

								<div class="align-center">
									<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
										<input type="hidden" name="cmd" value="_s-xclick">
										<input type="hidden" name="hosted_button_id" value="7EFHMABH75HPE">
										<input type="image" src="https://resources.phpboost.com/documentation/paypal/button_french.png" border="0" name="submit" alt="PHPBoost - PayPal">
									</form>
								</div>
							</div>
						</fieldset>
						<fieldset>
							<legend>Crédits</legend>
							<div class="fielset-inset">
								<ul>
									<li><strong>Régis VIARRE</strong> <em>(alias CrowkaiT)</em>, fondateur du projet PHPBoost et développeur retraité</li>
									<li><strong>Benoît SAUTEL</strong> <em>(alias ben.popeye)</em>, développeur retraité</li>
									<li><strong>Loic ROUCHON</strong> <em>(alias horn)</em>, développeur retraité</li>
									<li><strong>Kevin MASSY</strong> <em>(alias ReidLos)</em>, développeur</li>
									<li><strong>Julien BRISWALTER</strong> <em>(alias j1.seth)</em>, développeur</li>
								</ul>
							</div
						</fieldset>',
	'site.index' => 'Aller à l\'accueil du site',
	'admin.index' => 'Aller dans le panneau d\'administration'
);
?>
