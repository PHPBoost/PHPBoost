<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 05 20
 * @since       PHPBoost 3.0 - 2010 05 30
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     French                       #
####################################################

$lang['install.chmod.cache.not.writable'] = '
	<h1>Installation de PHPBoost</h1>
	<p><strong>Attention</strong> : les dossiers /cache et /cache/tpl n\'existent pas ou ne sont pas inscriptibles. Veuillez les créer et/ou changer leur CHMOD (mettre 755) pour pouvoir lancer l\'installation.</p>
	<p>Une fois ceci fait, actualisez la page pour continuer ou cliquez <a href="#">ici</a>.</p>
';

// Steps menu
$lang['install.change.language']   = 'Changer de langue';

$lang['install.steps.list']        = 'Liste des étapes';
$lang['install.step.introduction'] = 'Préambule';
$lang['install.step.license']      = 'Licence';
$lang['install.step.server']       = 'Configuration du serveur';
$lang['install.step.database']     = 'Configuration base de données';
$lang['install.step.website']      = 'Configuration du site';
$lang['install.step.admin']        = 'Compte administrateur';
$lang['install.step.end']          = 'Fin de l\'installation';

$lang['install.appendices'] = 'Annexes';

$lang['install.restart'] = 'Recommencer l\'installation';
$lang['install.confirm.restart'] = 'Etes-vous certain de vouloir recommencer l\'installation ?';

// General variables
$lang['install.title']              = 'Installation de PHPBoost';
$lang['install.documentation.link'] = 'https://www.phpboost.com/wiki/installer-phpboost';

// Welcome
$lang['install.welcome.title']                    = 'Préambule';
$lang['install.welcome.message']                  = 'Bienvenue dans l\'assistant d\'installation de PHPBoost';
$lang['install.welcome.description']              = '
	<p>Merci d\'avoir accordé votre confiance à PHPBoost pour créer votre site web.</p>
	<p>Pour installer PHPBoost, vous devez disposer d\'un minimum d\'informations concernant votre hébergement qui devraient être fournies par votre hébergeur. L\'installation est entièrement automatisée, elle ne devrait prendre que quelques minutes. Cliquez sur la flèche ci-dessous pour démarrer le processus d\'installation.</p>
	<p>Cordialement, l\'équipe PHPBoost</p>
';
$lang['install.welcome.distribution']             = 'Distribution :distribution';
$lang['install.welcome.distribution.description'] = '<p>Il existe différentes distributions de PHPBoost permettant à l\'utilisateur d\'obtenir automatiquement une configuration appropriée à ses besoins. Une distribution contient des modules ainsi que quelques paramétrages du système (noyau).</p><p>PHPBoost va s\'installer selon la configuration de cette distribution, vous pourrez évidemment par la suite modifier sa configuration et ajouter ou supprimer des modules.</p>';

// License
$lang['install.license.title']             = 'Licence';
$lang['install.license.terms']             = '<p>Termes de la licence GNU/GPL.</p>';
$lang['install.license.agreement']         = 'J\'ai pris connaissance des termes de la licence et je les accepte';
$lang['install.license.warning.agreement'] = 'Vous devez accepter la licence pour pouvoir continuer !';

// Server setup
$lang['install.server.title']       = 'Vérification de la configuration du serveur';
$lang['install.server.description'] = '
	<p>Avant de commencer les étapes d\'installation de PHPBoost, la configuration de votre serveur va être vérifiée afin d\'établir sa compatibilité avec PHPBoost.</p>
	<div class="message-helper bgc notice">Veillez à ce que chaque condition obligatoire soit vérifiée sans quoi vous risquez d\'avoir des problèmes en utilisant le logiciel.</div>
	<p>En cas de problème n\'hésitez pas à poser vos questions sur le <a href="https://www.phpboost.com/forum/">forum de support</a>.</p>
';

$lang['install.php.version']                         = 'Version de PHP';
$lang['install.php.version.check']                   = 'PHP version minimum :min_php_version';
$lang['install.php.version.check.clue']              = 'Votre version PHP est <b>:php_version</b>';
$lang['install.php.version.check.description']       = '<span class="text-strong error">Obligatoire :</span> Pour faire fonctionner PHPBoost, votre serveur doit être équipé d\'une version supérieure ou égale à PHP :min_php_version. Sans cela il vous sera impossible de le faire fonctionner correctement, contactez votre hébergeur ou migrez vers un serveur plus récent.';
$lang['install.php.extensions']                      = 'Extensions';
$lang['install.php.extensions.check']                = 'L\'activation de ces extensions permet d\'apporter des fonctionnalités supplémentaires mais n\'est en aucun cas indispensable (sauf la librairie MBstring).';
$lang['install.php.extensions.check.gd']             = 'Librairie GD';
$lang['install.php.extensions.check.gd.clue']        = 'Librairie utilisée pour générer des images. Utile par exemple pour la protection anti robots, ou les diagrammes des statistiques du site. Certains modules peuvent également s\'en servir.';
$lang['install.php.extensions.check.curl']           = 'Librairie Curl';
$lang['install.php.extensions.check.curl.clue']      = 'Librairie utilisée pour récupération d\'éléments distants. Nécessaire pour faire fonctionner l\'authentification externe par exemple.';
$lang['install.php.extensions.check.mbstring']       = 'Librairie MBstring';
$lang['install.php.extensions.check.mbstring.clue']  = 'Librairie utilisée pour la gestion des caractères UTF-8. Obligatoire pour avoir un site fonctionnel.';
$lang['install.php.extensions.check.mbstring.error'] = 'L\'extension php <b>mbstring</b> n\'est pas activée. Veuillez l\'activer ou contactez votre hébergeur avant de pouvoir poursuivre l\'installation.';
$lang['install.url.rewriting']                       = 'URL Rewriting';
$lang['install.url.rewriting.clue']                  = 'Réécriture des adresses des pages qui les rend plus lisibles et plus propices au référencement sur les moteurs de recherche';

$lang['install.folders.chmod']         = 'Autorisations des dossiers';
$lang['install.folders.chmod.check']   = '<span class="text-strong error">Obligatoire :</span> PHPBoost nécessite que certains dossiers soient inscriptibles. Si votre serveur le permet, leurs autorisations sont changées de façon automatique. Cependant certains serveurs empêchent la modification automatique des autorisations, il faut donc faire la manipulation manuellement, pour cela contactez votre hébergeur.';
$lang['install.folder.existing']       = 'Existant';
$lang['install.folder.non.existent']   = 'Inexistant';
$lang['install.folder.writable']       = 'Inscriptible';
$lang['install.folder.not.writable']   = 'Non inscriptible';
$lang['install.folders.chmod.error']   = 'Les répertoires ne sont pas tous existants et/ou inscriptibles. Merci de le faire à la main pour pouvoir continuer.';
$lang['install.folders.chmod.refresh'] = 'Revérifier les dossiers';

// Database
$lang['install.db.config.title']                  = 'Configuration base de données';
$lang['install.db.parameters.config']             = 'Paramètres de connexion à la base de données';
$lang['install.db.parameters.config.description'] = '<p>Cette étape permet de générer le fichier de configuration qui retiendra les identifiants de connexion à votre base de données. Les tables permettant de faire fonctionner PHPBoost seront automatiquement créées lors de cette étape. Si vous ne connaissez pas les informations ci-dessous, contactez votre hébergeur qui vous les transmettra.</p>';

$lang['install.dbms.parameters']    = 'Paramètres d\'accès au <abbr aria-label ="Système de Gestion de Base de Données">SGBD</abbr>';
$lang['install.dbms.host']          = 'Nom de l\'hôte';
$lang['install.dbms.host.clue']     = 'URL du serveur qui gère la base de données, <em>localhost</em> la plupart du temps.';
$lang['install.dbms.port']          = 'Port du serveur';
$lang['install.dbms.port.clue']     = 'Port du serveur qui gère la base de données, <em>3306</em> la plupart du temps.';
$lang['install.dbms.login']         = 'Identifiant';
$lang['install.dbms.login.clue']    = 'Fourni par l\'hébergeur';
$lang['install.dbms.password']      = 'Mot de passe';
$lang['install.dbms.password.clue'] = 'Fourni par l\'hébergeur';

$lang['install.schema.properties']        = 'Propriétés de la base de données';
$lang['install.schema']                   = 'Nom de la base de données';
$lang['install.schema.clue']              = 'Fourni par l\'hébergeur. Si cette base n\'existe pas, PHPBoost essaiera de la créer si la configuration le lui permet.';
$lang['install.schema.table.prefix']      = 'Préfixe des tables';
$lang['install.schema.table.prefix.clue'] = 'Par défaut <em>phpboost_</em>. A changer si vous souhaitez installer plusieurs fois PHPBoost dans la même base de données.';
$lang['install.db.config.check']          = 'Essayer';
$lang['install.db.connection.success']    = 'La connexion à la base de données a été effectuée avec succès. Vous pouvez poursuivre l\'installation.';
$lang['install.db.connection.error']      = 'Impossible de se connecter à la base de données. Merci de vérifier vos paramètres.';
$lang['install.db.creation.error']        = 'La base de données que vous avez indiquée n\'existe pas et le système n\'a pas l\'autorisation de la créer.';
$lang['install.db.unknown.error']         = 'Une erreur inconnue a été rencontrée.';
$lang['install.db.unknown.error.detail']  = 'Une erreur inconnue a été rencontrée. Vous pouvez récupérer le contenu complet de l\'erreur dans le fichier <em>/cache/error.log</em> sur votre FTP si le support vous le demande.';
$lang['install.db.required.host']         = 'Vous devez renseigner le nom de l\'hôte !';
$lang['install.db.required.port']         = 'Vous devez renseigner le port !';
$lang['install.db.required.login']        = 'Vous devez renseigner l\'identifiant de connexion !';
$lang['install.db.required.schema']       = 'Vous devez renseigner le nom de la base de données !';

$lang['install.phpboost.already.installed']                   = 'Installation existante';
$lang['install.phpboost.already.installed.alert']             = 'Il existe déjà une installation de PHPBoost sur cette base de données avec ce préfixe. Si vous continuez, ces tables seront supprimées et vous perdrez certainement des données.';
$lang['install.phpboost.already.installed.description']       = '
	<p>La base de données sur laquelle vous souhaitez installer PHPBoost contient déjà une installation de PHPBoost.</p>
	<p>Si vous effectuez l\'installation sur cette base de données avec cette configuration, vous écraserez les données présentes actuellement. Si vous voulez installer deux fois PHPBoost sur la même base de données, utilisez des préfixes différents.</p>
';
$lang['install.phpboost.already.installed.overwrite']         = 'Je souhaite écraser l\'installation de PHPBoost déjà existante';
$lang['install.phpboost.already.installed.overwrite.confirm'] = 'Vous devez confirmer l\'écrasement de la précédente installation';

// Website settings
$lang['install.website.config.title']       = 'Configuration du serveur';
$lang['install.website.config']             = 'Configuration du site';
$lang['install.website.config.description'] = '<p>La configuration de base du site va être créée dans cette étape afin de permettre à PHPBoost de fonctionner. Sachez cependant que toutes les données que vous allez rentrer seront ultérieurement modifiables dans le panneau d\'administration, rubrique configuration du site. Vous pourrez dans ce même panneau renseigner davantage d\'informations facultatives à propos de votre site.</p>';

$lang['install.website.yours']            = 'Votre site';
$lang['install.website.host']             = 'Adresse du site';
$lang['install.website.host.clue']        = 'De la forme https://www.phpboost.com';
$lang['install.website.path']             = 'Chemin de PHPBoost';
$lang['install.website.path.clue']        = 'Vide si votre site est à la racine du serveur, de la forme /dossier sinon';
$lang['install.website.name']             = 'Nom du site';
$lang['install.website.slogan']           = 'Slogan du site';
$lang['install.website.description']      = 'Description du site';
$lang['install.website.description.clue'] = '(facultatif) Utile pour le référencement dans les moteurs de recherche';
$lang['install.website.timezone']         = 'Fuseau horaire du site';
$lang['install.website.timezone.clue']    = 'La valeur par défaut est celle correspondant à la localisation de votre serveur. Pour la France, il s\'agit d\'<strong>Europe/Paris</strong>. Vous pourrez changer cette valeur par la suite dans le panneau d\'administration.';
$lang['install.website.host.required']    = 'Vous devez entrer l\'adresse de votre site !';
$lang['install.website.name.required']    = 'Vous devez entrer le nom de votre site !';
$lang['install.website.host.warning']     = 'L\'adresse du site que vous avez rentrée ne correspond pas à celle détectée par le serveur. Souhaitez vous vraiment choisir cette adresse ?';
$lang['install.website.path.warning']     = 'Le chemin du site sur le serveur que vous avez rentrée ne correspond pas à celle détectée par le serveur. Souhaitez vous vraiment choisir ce chemin ?';
$lang['install.website.captcha.config']   = 'Configuration du captcha';

// Admin account
$lang['install.admin.title'] = 'Compte administrateur';
$lang['install.admin.creation'] = 'Création du compte administrateur';
$lang['install.admin.creation.description'] = '
	<p>Ce compte donne accès au panneau d\'administration par lequel vous configurerez votre site. Vous pourrez modifier les informations concernant ce compte en consultant votre profil.</p>
	<p>Par la suite, il sera possible de donner à plusieurs personnes le statut d\'administrateur, ce compte est celui du premier administrateur, sans lequel vous ne pourriez pas gérer le site.</p>
';

$lang['install.admin.login.length'] = 'Votre pseudo est trop court (3 caractères minimum)';
$lang['install.admin.password'] = 'Mot de passe';
$lang['install.admin.password.clue'] = 'Minimum :number caractères';
$lang['install.admin.password.repeat'] = 'Répéter le mot de passe';
$lang['install.admin.password.required'] = 'Vous devez entrer un mot de passe';
$lang['install.admin.password.length'] = 'Votre mot de passe est trop court (:number caractères minimum)';
$lang['install.admin.email'] = 'Courrier électronique';
$lang['install.admin.email.clue'] = 'Doit être valide pour recevoir le code de déverrouillage';
$lang['install.admin.email.required'] = 'Vous devez entrer une adresse de courier électronique';
$lang['install.admin.email.invalid'] = 'L\'adresse de courier électronique que vous avez entrée n\'a pas une forme correcte';
$lang['install.admin.connect.after.install'] = 'Me connecter à la fin de l\'installation';
$lang['install.admin.autoconnect'] = 'Rester connecté systématiquement à chacune de mes visites';

// Services
$lang['install.admin.created.email.object'] = 'Identifiants de votre site créé avec PHPBoost (message à conserver)';
$lang['install.admin.created.email.unlock.code'] = 'Cher %s,

Merci d\'avoir choisi PHPBoost pour réaliser votre site, nous espérons qu\'il répondra au mieux à vos besoins. Pour tout problème n\'hésitez pas à vous rendre sur le forum https://www.phpboost.com/forum/

Voici vos identifiants (ne les perdez pas, ils vous seront utiles pour administrer votre site et ne pourront plus être récupérés).

Identifiant: %s
Si vous perdez votre mot de passe, vous pouvez en générer un nouveau à partir de ce lien %s

Cordialement l\'équipe PHPBoost.';

// End of installation
$lang['install.congratulations'] = 'Félicitations !';
$lang['install.finish.title']    = 'Fin de l\'installation';
$lang['install.finish.message'] = '
	<fieldset>
		<legend>PHPBoost est désormais installé !</legend>
		<div class="fielset-inset">
			<p class="message-helper bgc success">L\'installation de PHPBoost s\'est déroulée avec succès. L\'équipe PHPBoost vous remercie de lui avoir fait confiance et est heureuse de vous compter parmi ses utilisateurs.</p>
			<p>Nous vous conseillons de vous tenir au courant de l\'évolution de PHPBoost via le site de la communauté francophone, <a href="https://www.phpboost.com">www.phpboost.com</a>. Vous serez automatiquement averti dans le panneau d\'administration de l\'arrivée de nouvelles mises à jour. Il est fortement conseillé de tenir votre système à jour afin de profiter des dernières nouveautés et de corriger les éventuelles failles ou erreurs.</p>
			<p class="message-helper bgc warning">Par mesure de sécurité nous vous conseillons fortement de supprimer le dossier <b>install</b> et tout ce qu\'il contient. Des personnes mal intentionnées pourraient relancer le script d\'installation et écraser certaines de vos données ! Une option vous sera proposée une fois connecté sur le site pour effectuer cette suppression.</p>
			<p>N\'oubliez pas de consulter la <a href="https://www.phpboost.com/wiki/">documentation</a> qui vous guidera dans l\'utilisation de PHPBoost ainsi que la <a href="https://www.phpboost.com/faq/"><abbr aria-label="Foire Aux Questions">FAQ</abbr></a> qui répond aux questions les plus fréquentes.</p>
			<p>En cas de problème, rendez-vous sur le <a href="https://www.phpboost.com/forum/">forum du support de PHPBoost</a>.</p>
		</div>
	</fieldset>
	<fieldset>
		<legend>Remerciements</legend>
		<div class="fielset-inset">
			<h2>Membres de la communauté</h2>
			<p>Merci à tous les membres de la communauté qui nous encouragent au quotidien et contribuent à la qualité du logiciel que ce soit en suggérant des nouvelles fonctionnalités ou en signalant des dysfonctionnements, ce qui permet d\'aboutir entre autres à un CMS stable et efficace.</p>
			<p>Merci aux membres des équipes de PHPBoost et particulièrement à <strong>mipel</strong> de l\'équipe communication, <strong>mipel</strong>, <strong>olivierb</strong> et <strong>xela</strong> pour la documentation, <strong>babsolune</strong> et <strong>xela</strong> pour l\'aide au développement, <strong>ElenWii</strong> et <strong>babsolune</strong> pour les graphismes, <strong>mipel</strong> et <strong>olivierb</strong> pour la modération de la communauté et <strong>janus57</strong> pour l\'appui aux développements et à l\'aide de la communauté sur le forum.</p>
			<h2>Projets</h2>
			<p>PHPBoost utilise différents outils afin d\'élargir ses fonctionnalités sans augmenter trop le temps de développement. Ces outils sont tous libres, distribués sous la licence GNU/GPL pour la plupart.</p>
			<ul>
				<li><a href="https://notepad-plus-plus.org/fr">Notepad++</a>, <a href="https://atom.io/">Atom</a>, <a href="https://fr.netbeans.org/">NetBeans</a> et <a href="https://sublimetext.com">Sublime Text</a> : Editeurs de texte puissants utilisés pour le développement de PHPBoost.</li>
				<li><a href="https://github.com/chamilo/pclzip">PCLZIP</a> créé par <a href="https://www.phpconcept.net/">PHPConcept</a> : Librairie permettant de travailler sur des archives au format Zip.</li>
				<li><a href="https://github.com/daanforever/phpmathpublisher">PHPMathPublisher</a> : Ensemble de fonctions permettant de mettre en forme des formules mathématiques à partir d\'une syntaxe proche de celle du <a href="https://fr.wikipedia.org/wiki/LaTeX">LaTeX</a>.</li>
				<li><a href="https://www.tiny.cloud/">TinyMCE</a> : Editeur <abbr aria-label="What You See Is What You Get">WYSIWYG</abbr> permettant la mise en page à la volée.</li>
				<li><a href="http://qbnz.com/highlighter/">GeSHi</a> : Colorateur de code source dans de nombreux langages informatiques.</li>
				<li><a href="https://jquery.com">jQuery</a> : Framework Javascript et <abbr aria-label="Asynchronous Javascript And XML">AJAX</abbr></li>
				<li><a href="https://fontawesome.com/?from=io">Font Awesome</a> : librairie d\'icônes</li>
			</ul>
		</div>
	</fieldset>
	<fieldset>
		<legend>Faire un don</legend>
		<div class="fielset-inset">
			Si vous souhaitez supporter PHPBoost financièrement vous pouvez nous faire un don via paypal :

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
				<li><strong>Kevin MASSY</strong> <em>(alias ReidLos)</em>, développeur retraité</li>
				<li><strong>Julien BRISWALTER</strong> <em>(alias j1.seth)</em>, développeur</li>
			</ul>
		</div>
	</fieldset>
';
$lang['install.site.index']  = 'Aller à l\'accueil du site';
$lang['install.admin.index'] = 'Aller dans le panneau d\'administration';
?>
