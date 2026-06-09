<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2012 05 30
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                      French                      #
####################################################

    //Variables générales
$lang['update.title']                  = 'Mise à jour de PHPBoost';
$lang['update.steps.list']             = 'Liste des étapes';
$lang['update.step.list.introduction'] = 'Préambule';
$lang['update.step.list.license']      = 'Licence';
$lang['update.step.list.server']       = 'Configuration du serveur';
$lang['update.step.list.database']     = 'Configuration base de données';
$lang['update.step.list.website']      = 'Configuration du site';
$lang['update.step.list.execute']      = 'Mise à jour';
$lang['update.step.list.end']          = 'Fin de la mise à jour';
$lang['update.language.change']        = 'Changer de langue';
$lang['update.change']                 = 'Changer';

$lang['update.step.previous'] = 'Étape précédente';
$lang['update.step.next']     = 'Étape suivante';

$lang['update.phpboost.link']   = 'Lien vers le site officiel de PHPBoost CMS';
$lang['update.phpboost.rights'] = '';
$lang['update.phpboost.logo']   = 'Logo PHPBoost';

//Introduction
$lang['update.step.introduction.title']   = 'Mise à jour de PHPBoost ' . GeneralConfig::load()->get_phpboost_major_version() . ' vers PHPBoost ' . UpdateServices::NEW_KERNEL_VERSION . ' ' . Environment::get_phpboost_name_version();
$lang['update.step.introduction.message'] = 'Bienvenue dans l\'assistant de mise à jour';
$lang['update.step.introduction.clue']    = '
        <p>Merci d\'avoir accordé votre confiance à PHPBoost pour créer votre site web.</p>
        <p>Pour mettre à jour PHPBoost vous devez disposer d\'un minimum d\'informations concernant votre hébergement qui devraient être fournies par votre hébergeur. La mise à jour est entièrement automatisée, elle ne devrait prendre que quelques minutes. Cliquez sur la flèche ci-dessous pour démarrer le processus de mise à jour.</p>
';

$lang['update.step.introduction.maintenance_notice'] = '<div class="message-helper bgc notice">Votre site va automatiquement être placé en maintenance. Pensez à désactiver la maintenance lorsque vous aurez vérifié que tout fonctionne correctement.</div>';
$lang['update.step.introduction.team_signature']     = '<p>Cordialement, l\'équipe PHPBoost</p>';

//Configuration du serveur
$lang['update.step.server.title']       = 'Vérification de la configuration du serveur';
$lang['update.step.server.description'] = '
        <p>Avant de commencer la mise à jour de PHPBoost, la configuration de votre serveur va être vérifiée afin d\'établir sa compatibilité avec PHPBoost.</p>
        <p>En cas de problème n\'hésitez pas à poser vos questions sur le <a href="https://www.phpboost.com/forum/">forum de support</a>.</p>
        <div class="message-helper bgc notice">Veillez à ce que chaque condition obligatoire soit vérifiée sans quoi vous risquez d\'avoir des problèmes en utilisant le logiciel.</div>
';
$lang['update.php.version']            = 'Version de PHP';
$lang['update.php.version.check']      = 'PHP supérieur à :min_php_version';
$lang['update.php.version.check.clue'] = '<span style="font-weight:bold;color:red;">Obligatoire :</span> Pour faire fonctionner PHPBoost, votre serveur doit être équipé d\'une version supérieure ou égale à PHP :min_php_version. Sans cela il vous sera impossible de le faire fonctionner correctement, contactez votre hébergeur ou migrez vers un serveur plus récent.';

$lang['update.php.extensions']                      = 'Extensions';
$lang['update.php.extensions.check']                = 'L\'activation de ces extensions permet d\'apporter des fonctionnalités supplémentaires et sont indispensables au bon fonctionnement de PHPBoost.';
$lang['update.php.extensions.check.gd']             = 'Extension GD';
$lang['update.php.extensions.check.gd.clue']        = 'Extension utilisée pour générer des images. Utile par exemple pour la protection anti robots ou les diagrammes des statistiques du site. Certains modules peuvent également s\'en servir.';
$lang['update.php.extensions.check.curl']           = 'Extension Curl';
$lang['update.php.extensions.check.curl.clue']      = 'Extension utilisée pour récupération d\'éléments distants. Nécessaire pour faire fonctionner l\'authentification externe par exemple.';
$lang['update.php.extensions.check.zip']            = 'Extension Zip';
$lang['update.php.extensions.check.zip.clue']       = 'Extension utilisée pour gestion de fichiers compressés.';
$lang['update.php.extensions.check.mbstring']       = 'Extension MBstring';
$lang['update.php.extensions.check.mbstring.clue']  = 'Extension utilisée pour la gestion des caractères UTF-8. Obligatoire pour avoir un site fonctionnel.';
$lang['update.php.extensions.check.mbstring.error'] = 'L\'extension php <b>mbstring</b> n\'est pas activée. Veuillez l\'activer ou contactez votre hébergeur avant de pouvoir poursuivre l\'installation.';

$lang['update.server.url.rewriting'] = 'URL Rewriting';
$lang['update.server.url.rewriting.clue'] = 'Optionnel<br />Réécriture des adresses des pages qui les rend plus lisibles et plus propices au référencement sur les moteurs de recherche';

$lang['update.folders.chmod']         = 'Autorisations des dossiers';
$lang['update.folders.chmod.check']   = '<span style="font-weight:bold;color:red;">Obligatoire :</span> PHPBoost nécessite que certains dossiers soient inscriptibles. Si votre serveur le permet, leurs autorisations sont changées de façon automatique. Cependant certains serveurs empêchent la modification automatique des autorisations, il faut donc faire la manipulation manuellement, pour cela voir la <a href="https://www.phpboost.com/wiki/changer-le-chmod-d-un-dossier" aria-label="Documentation PHPBoost : Changer le chmod">documentation PHPBoost</a> ou contactez votre hébergeur.';
$lang['update.folders.chmod.refresh'] = 'Revérifier les dossiers';
$lang['update.folder.exists']         = 'Existant';
$lang['update.folder.non.existent']   = 'Inexistant';
$lang['update.folder.is.writable']    = 'Inscriptible';
$lang['update.folder.not.writable']   = 'Non inscriptible';
$lang['update.folders.chmod.error']   = 'Les répertoires ne sont pas tous existants et/ou inscriptibles. Merci de le faire à la main pour pouvoir continuer.';

//Base de données
$lang['update.step.database.config']      = 'Configuration base de données';
$lang['update.db.parameters.config']      = 'Paramètres de connexion à la base de données';
$lang['update.db.parameters.config.clue'] = '<p>Cette étape permet de générer le fichier de configuration qui retiendra les identifiants de connexion à votre base de données. Si vous ne connaissez pas les informations ci-dessous, contactez votre hébergeur qui vous les transmettra.</p>';
$lang['update.dbms.parameters']           = 'Paramètres d\'accès au <acronym aria-label="Système de Gestion de Base de Données">SGBD</acronym>';
$lang['update.dbms.host']                 = 'Nom de l\'hôte';
$lang['update.dbms.host.clue']            = 'URL du serveur qui gère la base de données, <em>localhost</em> la plupart du temps.';
$lang['update.dbms.port']                 = 'Port du serveur';
$lang['update.dbms.port.clue']            = 'Port du serveur qui gère la base de données, <em>3306</em> la plupart du temps.';
$lang['update.dbms.login']                = 'Identifiant';
$lang['update.dbms.login.clue']           = 'Fourni par l\'hébergeur';
$lang['update.dbms.password']             = 'Mot de passe';
$lang['update.dbms.password.clue']        = 'Fourni par l\'hébergeur';

$lang['update.schema.properties']        = 'Propriétés de la base de données';
$lang['update.schema']                   = 'Nom de la base de données';
$lang['update.schema.clue']              = 'Fourni par l\'hébergeur. Si la base de données n\'existe pas, PHPBoost essayera de la créer.';
$lang['update.schema.table.prefix']      = 'Préfixe des tables';
$lang['update.schema.table.prefix.clue'] = 'Par défaut <em>phpboost_</em>. A changer si vous avez installé plusieurs fois PHPBoost dans la même base de données.';

$lang['update.db.config.check']        = 'Essayer';
$lang['update.db.connection.success']  = 'La connexion à la base de données a été effectuée avec succès. Vous pouvez poursuivre l\'installation';
$lang['update.db.connection.error']    = 'Impossible de se connecter à la base de données. Merci de vérifier vos paramètres.';
$lang['update.db.creation.error']      = 'La base de données que vous avez indiquée n\'existe pas.';
$lang['update.db.unknown.error']       = 'Une erreur inconnue a été rencontrée.';
$lang['update.db.required.host']       = 'Vous devez renseigner le nom de l\'hôte !';
$lang['update.db.required.port']       = 'Vous devez renseigner le port !';
$lang['update.db.required.login']      = 'Vous devez renseigner l\'identifiant de connexion !';
$lang['update.db.required.schema']     = 'Vous devez renseigner le nom de la base de données !';
$lang['update.db.unexisting.database'] = 'La base de donnée n\'existe pas. Veuillez vérifier vos paramètres.';

$lang['phpboost.not.installed']      = 'Installation inexistante';
$lang['phpboost.not.installed.clue'] = '
        <p>La base de données sur laquelle vous souhaitez mettre à jour PHPBoost ne contient pas d\'installation.</p>
        <p> Veuillez vérifier que vous avez bien saisi le bon préfixe et la bonne base de données.</p>
';

//Execute update
$lang['update.step.execute.title']              = 'Exécuter la mise à jour';
$lang['update.step.execute.update.in.progress'] = 'Mise à jour en cours';
$lang['update.step.execute.message']            = 'Mise à jour du site';

$lang['update.step.execute.clue'] = '
        Cette étape va convertir votre site PHPBoost ' . GeneralConfig::load()->get_phpboost_major_version() . ' vers PHPBoost ' . UpdateServices::NEW_KERNEL_VERSION . '.
        <br /><br />Attention cette étape est irréversible, veuillez par précaution sauvegarder votre base de données au préalable !
';
$lang['update.step.execute.incompatible.modules'] = '
        Les modules suivants vont être désactivés car ils ne sont pas compatibles avec PHPBoost ' . UpdateServices::NEW_KERNEL_VERSION . ' : :modules.
        <br />Pour les réactiver, téléchargez leur version compatible sur le site <a href="https://www.phpboost.com/download">PHPBoost</a> si elle existe et rendez-vous sur la page mise à jour de module de votre site pour les mettre à jour (ils seront alors réactivés automatiquement).
';
$lang['update.step.execute.incompatible.module'] = '
        Le module :modules va être désactivé car il n\'est pas compatible avec PHPBoost ' . UpdateServices::NEW_KERNEL_VERSION . '.
        <br />Pour le réactiver, téléchargez sa version compatible sur le site <a href="https://www.phpboost.com/download">PHPBoost</a> si elle existe et rendez-vous sur la page mise à jour de module de votre site pour le mettre à jour (il sera alors réactivé automatiquement).
';
$lang['update.step.execute.incompatible.module.default'] = '<br /><br />Le module <b>:old_default</b> placé en démarrage du site va être remplacé par le module <b>:new_default</b>. Quand vous aurez installé la nouvelle version compatible du module <b>:old_default</b> pensez à reconfigurer la page de démarrage de votre site dans la configuration générale.';
$lang['update.step.execute.incompatible.themes'] = '
        Les thèmes suivants vont être désactivés car ils ne sont pas compatibles avec PHPBoost ' . UpdateServices::NEW_KERNEL_VERSION . ' : :themes.
        <br />Pour les réactiver, téléchargez leur version compatible sur le site <a href="https://www.phpboost.com/download">PHPBoost</a> si elle existe, mettez à jour les thèmes sur le FTP de votre site dans le dossier templates puis rendez-vous sur la page de gestion des thèmes de votre site pour les réactiver.
';
$lang['update.step.execute.incompatible.theme'] = '
        Le thème :themes va être désactivé car il n\'est pas compatible avec PHPBoost ' . UpdateServices::NEW_KERNEL_VERSION . '.
        <br />Pour le réactiver, téléchargez sa version compatible sur le site <a href="https://www.phpboost.com/download">PHPBoost</a> si elle existe, mettez à jour le thème sur le FTP de votre site dans le dossier templates puis rendez-vous sur la page de gestion des thèmes de votre site pour le réactiver.
';
$lang['update.step.execute.incompatible.theme.default' ] = '<br /><br />Le thème <b>:old_default</b> par défaut du site va être remplacé par le thème <b>:new_default</b>. Quand vous aurez installé la nouvelle version compatible du thème <b>:old_default</b> pensez à reconfigurer le thème par défaut de votre site dans la configuration générale (si c\'était le seul thème désinstallez ensuite le thème <b>:new_default</b> pour que les utilisateurs du site aient bien ce thème actif).';
$lang['update.step.execute.incompatible.langs'] = '
        Les langues suivantes vont être désactivées car elles ne sont pas compatibles avec PHPBoost ' . UpdateServices::NEW_KERNEL_VERSION . ' : :langs.
        <br />Pour les réactiver, téléchargez leur version compatible sur le site <a href="https://www.phpboost.com/download">PHPBoost</a> si elle existe, mettez à jour les langues sur le FTP de votre site dans le dossier lang puis rendez-vous sur la page de gestion des langues de votre site pour les réactiver.
';
$lang['update.step.execute.incompatible.lang'] = '
        La langue :langs va être désactivée car elle n\'est pas compatible avec PHPBoost ' . UpdateServices::NEW_KERNEL_VERSION . '.
        <br />Pour la réactiver, téléchargez sa version compatible sur le site <a href="https://www.phpboost.com/download">PHPBoost</a> si elle existe, mettez à jour la langue sur le FTP de votre site  dans le dossier lang puis rendez-vous sur la page de gestion des langues de votre site pour la réactiver.
';
$lang['update.step.execute.incompatible.lang.default'] = '<br /><br />La langue <b>:old_default</b> par défaut du site va être remplacée par la langue <b>:new_default</b>. Quand vous aurez installé la nouvelle version compatible de la langue <b>:old_default</b> pensez à reconfigurer la langue par défaut de votre site dans la configuration générale (si c\'était la seule langue désinstallez ensuite la langue <b>:new_default</b> pour que les utilisateurs du site aient bien cette active).';

//Finish update
$lang['update.tab.congrats'] = 'Félicitaions';
$lang['update.tab.thanks']   = 'Remerciements';
$lang['update.tab.projects'] = 'Projets';
$lang['update.tab.credits']  = 'Crédits';

$lang['update.tab.content.congrats'] = '
    <div>
        <h2>PHPBoost est désormais mis à jour !</h2>
        <div class="fielset-inset">
            <p class="message-helper bgc success">La mise à jour de PHPBoost s\'est déroulée avec succès. L\'équipe PHPBoost vous remercie de lui avoir fait confiance et est heureuse de vous compter parmi ses utilisateurs.</p>
            <p class="message-helper bgc warning">Merci de récupérer le <a href="' . GeneralConfig::load()->get_complete_site_url() . '/cache/update.log" download>fichier de log</a> de votre migration, il pourra vous être demandé sur le forum PHPBoost en cas de demande de support sur votre migration.</p>
            <p>Nous vous conseillons de vous tenir au courant de l\'évolution de PHPBoost via le site de la communauté francophone, <a href="https://www.phpboost.com">www.phpboost.com</a>. Vous serez automatiquement averti dans le panneau d\'administration de l\'arrivée de nouvelles mises à jour. Il est fortement conseillé de tenir votre système à jour afin de profiter des dernières nouveautés et de corriger les éventuelles failles ou erreurs.</p>
            <p class="message-helper bgc warning">Par mesure de sécurité nous vous conseillons fortement de supprimer le dossier <b>update</b> et tout ce qu\'il contient, des personnes mal intentionnées pourraient relancer le script de mise à jour et écraser certaines de vos données ! Une option vous sera proposée une fois connecté sur le site pour effectuer cette suppression.</p>
            <p>N\'oubliez pas la <a href="https://www.phpboost.com/wiki/">documentation</a> qui vous guidera dans l\'utilisation de PHPBoost ainsi que la <a href="https://www.phpboost.com/faq/"><acronym aria-label="Foire Aux Questions">FAQ</acronym></a> qui répond aux questions les plus fréquentes.</p>
            <p>En cas de problème, rendez-vous sur le <a href="https://www.phpboost.com/forum/">forum du support de PHPBoost</a>.</p>
        </div>
    </div>
';

$lang['update.tab.content.thanks'] = '
    <div>
        <h2>Remerciements</h2>
        <div class="fielset-inset">
            <h2>Membres de la communauté</h2>
            <p>Merci à tous les membres de la communauté qui nous encouragent au quotidien et contribuent à la qualité du logiciel que ce soit en suggérant des nouvelles fonctionnalités ou en signalant des dysfonctionnements, ce qui permet d\'aboutir entre autres à un CMS stable et efficace.</p>
            <p>Merci aux membres des équipes de PHPBoost et particulièrement à <strong>mipel</strong> de l\'équipe communication, <strong>mipel</strong>, <strong>olivierb</strong> et <strong>xela</strong> pour la documentation, <strong>babsolune</strong> et <strong>xela</strong> pour l\(aide au développement, <strong>ElenWii</strong> et <strong>babsolune</strong> pour les graphismes, <strong>mipel</strong> et <strong>olivierb</strong> pour la modération de la communauté et <strong>janus57</strong> pour l\'appui aux développements et à l\'aide de la communauté sur le forum.</p>
        </div>
    </div>
';

$lang['update.tab.content.projects'] = '
    <div>
        <h2>Projets</h2>
        <div class="fielset-inset">
            <p>PHPBoost utilise différents outils afin d\'élargir ses fonctionnalités sans augmenter trop le temps de développement. Ces outils sont tous libres, distribués sous la licence GNU/GPL pour la plupart.</p>
            <ul>
                <li><a href="https://notepad-plus-plus.org/fr">Notepad++</a>, <a href="https://atom.io/">Atom</a>, <a href="https://fr.netbeans.org/">NetBeans</a> et <a href="https://sublimetext.com">Sublime Text</a> : Editeurs de texte puissants utilisés pour le développement de PHPBoost.</li>
                <li><a href="https://github.com/daanforever/phpmathpublisher">PHPMathPublisher</a> : Ensemble de fonctions permettant de mettre en forme des formules mathématiques à partir d\'une syntaxe proche de celle du <a href="https://fr.wikipedia.org/wiki/LaTeX">LaTeX</a>.</li>
                <li><a href="https://www.tiny.cloud/">TinyMCE</a> : Editeur <abbr aria-label="What You See Is What You Get">WYSIWYG</abbr> permettant la mise en page à la volée.</li>
                <li><a href="https://github.com/PrismJS/prism">Prism</a> : Colorateur de code source dans de nombreux langages informatiques.</li>
                <li><a href="https://jquery.com">jQuery</a> : Framework Javascript et <abbr aria-label="Asynchronous Javascript And XML">AJAX</abbr></li>
                <li><a href="https://fontawesome.com/?from=io">Font Awesome</a> : librairie d\'icônes</li>
            </ul>
        </div>
    </div>
';

$lang['update.tab.content.credits'] = '
    <div>
        <h2>Crédits</h2>
        <div class="fielset-inset">
            <ul>
                <li><strong>Régis VIARRE</strong> <em>(alias CrowkaiT)</em>, fondateur du projet PHPBoost et développeur retraité</li>
                <li><strong>Benoît SAUTEL</strong> <em>(alias ben.popeye)</em>, développeur retraité</li>
                <li><strong>Loic ROUCHON</strong> <em>(alias horn)</em>, développeur retraité</li>
                <li><strong>Kevin MASSY</strong> <em>(alias ReidLos)</em>, développeur retraité</li>
                <li><strong>Julien BRISWALTER</strong> <em>(alias j1.seth)</em>, développeur</li>
            </ul>
        </div
    </div>
';

$lang['update.donate'] = '
    <div>
        <h2>Faire un don</h2>
        <div class="fielset-inset">
            Si vous souhaitez supporter PHPBoost financièrement, vous pouvez nous faire un don via paypal 

            <div class="align-center">
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="7EFHMABH75HPE">
                    <input type="image" src="https://resources.phpboost.com/documentation/paypal/button_french.png" border="0" name="submit" alt="PHPBoost - PayPal">
                </form>
            </div>
        </div>
    </div>
';

$lang['update.site.index']  = 'Aller à l\'accueil du site';
$lang['update.admin.index'] = 'Aller dans le panneau d\'administration'
?>
