<?php
/*##################################################
 *                                install.php
 *                            -------------------
 *   begin                : May 30, 2010
 *   copyright            : (C) 2010 Kevin MASSY
 *   email                : soldier.weasel@gmail.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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
    'step.previous' => 'Etape précédente',
    'step.next' => 'Etape suivante',
    'yes' => 'Oui',
    'no' => 'Non',
	'generatedBy' => 'Généré par %s',
	'poweredBy' => 'Boosté par',
	'phpboost.rights' => '',

//Introduction
	'step.introduction.title' => 'Préambule',
	'step.introduction.message' => 'Bienvenue dans l\'assistant de mise à jour de PHPBoost',
    'step.introduction.explanation' => '<p>Merci d\'avoir accordé votre confiance à PHPBoost pour créer votre site web.</p>
<p>Pour mettre à jour PHPBoost vous devez disposer d\'un minimum d\'informations concernant votre hébergement qui devraient être fournies par votre hébergeur. La mise à jour est entièrement automatisée, elle ne devrait prendre que quelques minutes. Cliquez sur la flèche ci-dessous pour démarrer le processus de mise à jour.</p>
<p>Cordialement, l\'équipe PHPBoost</p>',

//Configuration du serveur
	'step.server.title' => 'Vérification de la configuration du serveur',
	'step.server.explanation' => '<p>Avant de commencer la mise à jour de PHPBoost, la configuration de votre serveur va être vérifiée afin d\'établir sa compatibilité avec PHPBoost.</p>
<div class="notice">Veillez à ce que chaque condition obligatoire soit vérifiée sans quoi vous risquez d\'avoir des problèmes en utilisant le logiciel.</div>
<p>En cas de problème n\'hésitez pas à poser vos questions sur le <a href="http://www.phpboost.com/forum/index.php">forum de support</a>.</p>',
	'php.version' => 'Version de PHP',
	'php.version.check' => 'PHP supérieur à :min_php_version',
	'php.version.check.explanation' => '<span style="font-weight:bold;color:red;">Obligatoire :</span> Pour faire fonctionner PHPBoost, votre serveur doit être équipé d\'une version supérieure ou égale à PHP :min_php_version. Sans cela il vous sera impossible de le faire fonctionner correctement, contactez votre hébergeur ou migrez vers un serveur plus récent.',
	'php.extensions' => 'Extensions',
	'php.extensions.check' => 'Optionnel : L\'activation de ces extensions permet d\'apporter des fonctionnalités supplémentaires mais n\'est en aucun cas indispensable.',
	'php.extensions.check.gdLibrary' => 'Librairie GD',
	'php.extensions.check.gdLibrary.explanation' => 'Librairie utilisée pour générer des images. Utile par exemple pour la protection anti robots, ou les diagrammes des statistiques du site. Certains modules peuvent également s\'en servir.',
	'server.urlRewriting' => 'URL Rewriting',
	'server.urlRewriting.explanation' => 'Réécriture des adresses des pages qui les rend plus lisibles et plus propices au référencement sur les moteurs de recherche',
	'folders.chmod' => 'Autorisations des dossiers',
	'folders.chmod.check' => '<span style="font-weight:bold;color:red;">Obligatoire :</span> PHPBoost nécessite que certains dossiers soient inscriptibles. Si votre serveur le permet, leurs autorisations sont changées de façon automatique. Cependant certains serveurs empêchent la modification automatique des autorisations, il faut donc faire la manipulation manuellement, pour cela voir la <a href="http://www.phpboost.com/wiki/changer-le-chmod-d-un-dossier" title="Documentation PHPBoost : Changer le chmod">documentation PHPBoost</a> ou contactez votre hébergeur.',
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
	'dbms.paramters' => 'Paramètres d\'accès au <acronym title="Système de Gestion de Base de Données">SGBD</acronym>',
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
	'schema.tablePrefix' => 'Préfixe des tables',
	'schema.tablePrefix.explanation' => 'Par défaut <em>phpboost_</em>. A changer si vous avez installé plusieurs fois PHPBoost dans la même base de données.',
	'db.config.check' => 'Essayer',
	'db.connection.success' => 'La connexion à la base de données a été effectuée avec succès. Vous pouvez poursuivre l\'installation',
	'db.connection.error' => 'Impossible de se connecter à la base de données. Merci de vérifier vos paramètres.',
	'db.creation.error' => 'La base de données que vous avez indiquée n\'existe pas et le système n\'a pas l\'autorisation de la créer.',
	'db.unknown.error' => 'Une erreur inconnue a été rencontrée.',
	'db.required.host' => 'Vous devez renseigner le nom de l\'hôte !',
	'db.required.port' => 'Vous devez renseigner le port !',
	'db.required.login' => 'Vous devez renseigner l\'identifiant de connexion !',
	'db.required.schema' => 'Vous devez renseigner le nom de la base de données !',
	'db.unexisting_database' => 'La base de donnée n\'existe pas. Veuillez vérifier vos paramètres.',
	'phpboost.notInstalled' => 'Installation inexistante',
	'phpboost.notInstalled.explanation' => '<p>La base de données sur laquelle vous souhaitez mettre à jour PHPBoost ne contient pas d\'installation.</p>
	<p> Veuillez vérifier que vous avez bien saisi le bon prefix et la bonne base de données.</p>',

//Execute update
	'step.execute.title' => 'Exécuter la mise à jour',
	'step.execute.message' => 'Mise à jour du site',
	'step.execute.explanation' => 'Cette étape va convertir votre site PHPBoost 3.0 vers PHPBoost 4.0.
	<br /><br />
	Attention cette étape est irréversible, veuillez par précaution sauvegarder votre base de données au préalable !',
	
	'finish.message' => '<fieldset>
                            <legend>PHPBoost est désormais mis à jour!</legend>
                            <p class="success">La mise à jour de PHPBoost s\'est déroulée avec succès. L\'équipe PHPBoost vous remercie de lui avoir fait confiance et est heureuse de vous compter parmi ses utilisateurs.</p>
                            <p>Nous vous conseillons de vous tenir au courant de l\'évolution de PHPBoost via le site de la communauté francophone, <a href="http://www.phpboost.com">www.phpboost.com</a>. Vous serez automatiquement averti dans le panneau d\'administration de l\'arrivée de nouvelles mises à jour. Il est fortement conseillé de tenir votre système à jour afin de profiter des dernières nouveautés et de corriger les éventuelles failles ou erreurs.</p>
                            <p class="warning">Par mesure de sécurité nous vous conseillons fortement de supprimer le dossier install et tout ce qu\'il contient, des personnes mal intentionnées pourraient relancer le script d\'installation et écraser certaines de vos données !</p>
                            <p>N\'oubliez pas la <a href="http://www.phpboost.com/wiki/wiki.php">documentation</a> qui vous guidera dans l\'utilisation de PHPBoost ainsi que la <a href="http://www.phpboost.com/faq/faq.php"><acronym title="Foire Aux Questions">FAQ</acronym></a> qui répond aux questions les plus fréquentes.</p>
                            <p>En cas de problème, rendez-vous sur le <a href="http://www.phpboost.com/forum/index.php">forum du support de PHPBoost</a>.</p>
                        </fieldset>
                        <fieldset>
                            <legend>Remerciements</legend>
                            <h2>Membres de la communauté</h2>
                            <p>Merci à tous les membres de la communauté qui nous encouragent au quotidien et contribuent à la qualité du logiciel que ce soit en suggérant des nouvelles fonctionnalités ou en signalant des dysfonctionnements, ce qui permet d\'aboutir entre autres à une version 4.0 stable et efficace.</p>
                            <p>Merci aux membres des équipes de PHPBoost et particulièrement à <strong>soupaloignon</strong> de l\'équipe communication, <strong>Ptithom</strong>, <strong>aiglobulles</strong>, <strong>55 Escape</strong> et <strong>Micman</strong> pour la documentation, <strong>Schyzo</strong> pour les graphismes, <strong>DaaX</strong>, <strong>Alain91</strong> et <strong>julienseth78</strong> de l\'équipe de développement de modules et <strong>benflovideo</strong> pour la modération de la communauté.</p>
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
                                <li><strong>Régis VIARRE</strong> <em>(alias CrowkaiT)</em>, fondateur du projet PHPBoost et développeur retraité</li>
                                <li><strong>Benoît SAUTEL</strong> <em>(alias ben.popeye)</em>, développeur retraité</li>
                                <li><strong>Loic ROUCHON</strong> <em>(alias horn)</em>, développeur retraité</li>
                                <li><strong>Kevin MASSY</strong> <em>(alias ReidLos)</em>, développeur</li>
                            </ul>
                        </fieldset>',
	'site.index' => 'Aller à l\'accueil du site',
	'admin.index' => 'Aller dans le panneau d\'administration'
);
?>
