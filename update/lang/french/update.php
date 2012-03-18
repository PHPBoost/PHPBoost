<?php
/*##################################################
 *                                install.php
 *                            -------------------
 *   begin                : May 30, 2010
 *   copyright            : (C) 2010 Kévin MASSY
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
	'phpboost.alreadyInstalled.alert' => 'Il existe déjà une installation de PHPBoost sur cette base de données avec ce préfixe. Si vous continuez, ces tables seront supprimées et vous perdrez certainement des données.',
	'db.required.host' => 'Vous devez renseigner le nom de l\'hôte !',
	'db.required.port' => 'Vous devez renseigner le port !',
	'db.required.login' => 'Vous devez renseigner l\'identifiant de connexion !',
	'db.required.schema' => 'Vous devez renseigner le nom de la base de données !',
	'phpboost.alreadyInstalled' => 'Installation existante',
	'phpboost.alreadyInstalled.explanation' => '<p>La base de données sur laquelle vous souhaitez installer PHPBoost contient déjà une installation de PHPBoost.</p>
<p>Si vous effectuez l\'installation sur cette base de données avec cette configuration, vous écraserez les données présentes actuellement. Si vous voulez installer deux fois PHPBoost sur la même base de données, utilisez des préfixes différents.</p>',
	'phpboost.alreadyInstalled.overwrite' => 'Je souhaite écraser l\'installation de PHPBoost déjà existante',
	'phpboost.alreadyInstalled.overwrite.confirm' => 'Vous devez confirmer l\'écrasement de la précédente installation',

//Execute update
	'step.execute.title' => 'Exécuter la mise à jour',
	'step.execute.message' => 'Mise à jour du site',
	'step.execute.explanation' => 'Cette étape va convertir votre site PHPBoost 3.0 vers PHPBoost 4.0.
	<br /><br />
	Attention cette étape est irréversible, veuillez par précaution sauvegarder votre base de données au préalable !',

//configuraton du site
    'step.websiteConfig.title' => 'Configuration du serveur',
	'websiteConfig' => 'Configuration du site',
	'websiteConfig.explanation' => '<p>La configuration de base du site va être créée dans cette étape afin de permettre à PHPBoost de fonctionner. Sachez cependant que toutes les données que vous allez rentrer seront ultérieurement modifiables dans le panneau d\'administration dans la rubrique configuration du site. Vous pourrez dans ce même panneau renseigner davantage d\'informations facultatives à propos de votre site.</p>',
	'website.yours' => 'Votre site',
	'website.host' => 'Adresse du site :',
	'website.host.explanation' => 'De la forme http://www.phpboost.com',
	'website.path' => 'Chemin de PHPBoost :',
	'website.path.explanation' => 'Vide si votre site est à la racine du serveur, de la forme /dossier sinon',
	'website.name' => 'Nom du site',
    'website.description' => 'Description du site',
    'website.description.explanation' => '(facultatif) Utile pour le référencement dans les moteurs de recherche',
    'website.metaKeywords' => 'Mots clés du site',
    'website.metaKeywords.explanation' => '(facultatif) A rentrer séparés par des virgules, ils servent au référencement dans les moteurs de recherche',
	'website.timezone' => 'Fuseau horaire du site',
	'website.timezone.explanation' => 'La valeur par défaut est celle correspondant à la localisation de votre serveur. Pour la France, il s\'agit de GMT + 1. Vous pourrez changer cette valeur par la suite dans le panneau d\'administration.',
	'website.host.required' => 'Vous devez entrer l\'adresse de votre site !',
	'website.name.required' => 'Vous devez entrer le nom de votre site !',
	'website.host.warning' => 'L\'adresse du site que vous avez rentrée ne correspond pas à celle détectée par le serveur. Souhaitez vous vraiment choisir cette adresse ?',
	'website.path.warning' => 'Le chemin du site sur le serveur que vous avez rentrée ne correspond pas à celle détectée par le serveur. Souhaitez vous vraiment choisir ce chemin ?',
);
?>
