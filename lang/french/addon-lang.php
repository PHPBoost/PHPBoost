<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 06 01
 * @since       PHPBoost 3.0 - 2012 04 12
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      xela <xela@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     French                       #
####################################################

// Common
$lang['addon.multiple.select']     = 'Gestion multiple';
$lang['addon.multiple.install']    = 'Installer la sélection';
$lang['addon.multiple.uninstall']  = 'Désinstaller la sélection';
$lang['addon.multiple.delete']     = 'Supprimer la sélection';
$lang['addon.multiple.enable']     = 'Activer la sélection';
$lang['addon.multiple.disable']    = 'Désactiver la sélection';
$lang['addon.multiple.upgrade']    = 'Mettre à jour la sélection';
$lang['addon.install']             = 'Installer';
$lang['addon.uninstall']           = 'Désinstaller';
$lang['addon.compatible']          = 'Compatible';
$lang['addon.not.compatible']      = 'Incompatible';
$lang['addon.compatibility']       = 'Compatibilité';
$lang['addon.authorizations']      = 'Autorisations';
$lang['addon.authorizations.save'] = 'Sauvegarder les autorisations';
$lang['addon.upload.clue']         = 'L\'archive uploadée doit être au format <span class="text-strong">zip ou gzip</span> et ne doit pas dépasser <span class="text-strong">:max_size</span>. En cas de dépassement, déposez le dossier extrait de l\'archive :addon de votre site sur votre FTP.';

$lang['addon.add.tab.github']  = 'Depuis GitHub';
$lang['addon.add.tab.website'] = 'Depuis un site internet';
$lang['addon.add.tab.server']  = 'Disponible sur ce serveur';
$lang['addon.add.tab.archive'] = 'Depuis un fichier compressé';
// Langs
$lang['addon.langs.directory']       = 'dans le dossier <span class ="text-strong pinned question">lang</span>';
$lang['addon.langs']                 = 'Langues';
$lang['addon.langs.management']      = 'Gestion des langues';
$lang['addon.langs.add']             = 'Ajouter une langue';
$lang['addon.langs.delete']          = 'Suppression d\'une langue';
$lang['addon.langs.delete.multiple'] = 'Suppression des langues sélectionnées';
$lang['addon.langs.installed']       = 'Langues installées';
$lang['addon.langs.available']       = 'Langues disponibles';
$lang['addon.langs.default']         = 'Langue par défaut';
$lang['addon.langs.default.auth']    = 'La langue par défaut est visible par tout le monde';
$lang['addon.langs.select.all']      = 'Selectionner toutes les langues';
    // Warnings
$lang['addon.langs.warning.delete']  = 'Une langue doit être activée, désactivée ou supprimée uniquement depuis cette page.<br />En aucun cas il ne faut intervenir sur le FTP et/ou dans la base de données.';
$lang['addon.langs.warning.install'] = 'Les langues ajoutées sont automatiquement activées. Pensez à les désactiver si besoin.';
$lang['addon.langs.not.lang']        = 'Ce dossier n\'est pas un dossier de langue.';
    // Upload
$lang['addon.langs.upload']      = 'Uploader une langue';
    // Drop
$lang['addon.langs.drop']          = 'Supprimer tous les fichiers de la langue';
$lang['addon.langs.drop.multiple'] = 'Supprimer tous les fichiers des langues';
$lang['addon.langs.default.clue']  = 'La langue par défaut ne peut pas être désinstallée, désactivée ou réservée';

// Modules
$lang['addon.modules.directory']       = 'à la <span class ="text-strong pinned question">racine</span>';
$lang['addon.modules']                 = 'Modules';
$lang['addon.modules.management']      = 'Gestion des modules';
$lang['addon.modules.update']          = 'Mettre à jour un module';
$lang['addon.modules.delete']          = 'Suppression d\'un module';
$lang['addon.modules.delete.multiple'] = 'Suppression de modules';
$lang['addon.modules.installed']       = 'Modules installés';
$lang['addon.modules.select.all']      = 'Sélectionner tous les modules';
$lang['addon.modules.no.icon']         = 'Aucune icône';
    // add
$lang['addon.modules.add']             = 'Ajouter un module';
    // Warnings
$lang['addon.modules.warning.delete']  = 'Un module doit être activé, désactivé ou supprimé uniquement depuis cette page. <br />En aucun cas il ne faut intervenir sur le FTP et/ou dans la base de données.';
$lang['addon.modules.warning.install'] = 'Les modules ajoutés sont automatiquement activés. Pensez à les désactiver si besoin.';
$lang['addon.modules.warning.update']  = 'Les modules mis à jour sont automatiquement activés. Pensez à les désactiver si besoin.';
$lang['addon.modules.not.module']      = 'Ce dossier n\'est pas un dossier de module.';
    // Upload
$lang['addon.modules.upload']      = 'Uploader un module';
    // Module
$lang['addon.modules.php.version']   = 'Version PHP';
$lang['addon.modules.documentation'] = 'Documentation';
    // Messages helper
$lang['addon.modules.already.installed'] = 'Ce module est déjà installé';
$lang['addon.modules.not.upgradable']    = 'Ce module ne peut pas être mis à jour';
$lang['addon.modules.not.installed']     = 'Ce module n\'est pas installé !';
$lang['addon.modules.available.updates'] = 'Mises à jour disponibles';
$lang['addon.modules.config.conflict']   = 'Conflit avec la configuration du module, installation impossible!';
$lang['addon.modules.not.compatible']    = 'Ce module n\'est pas compatible avec la version actuelle de PHPBoost vérifiez la disponibilité d\'une nouvelle version sur <a class="offload" href="https://www.phpboost.com/download">le site de PHPBoost</a>.';
    // Drop
$lang['addon.modules.drop']          = 'Supprimer tous les fichiers du module';
$lang['addon.modules.drop.multiple'] = 'Supprimer tous les fichiers des modules';
    // Upgrade
$lang['addon.modules.upgrade']     = 'Mettre à jour';
$lang['addon.modules.upgrade.all'] = 'Mettre à jour les modules sélectionnés';
    // Management
$lang['addon.module.disable']           = '<strong>Désactiver</strong><br />Les données et les fichiers ne seront pas supprimés';
$lang['addon.module.uninstall']         = '<strong>Désinstaller</strong><br />Les données seront supprimées mais pas les fichiers';
$lang['addon.module.delete']            = '<strong>Supprimer</strong><br />Les données et les fichiers seront supprimés';
$lang['addon.module.warning.uninstall'] = 'Le module <strong>:module</strong> a été désinstallé avec succès';
$lang['addon.module.warning.delete']    = 'Le module <strong>:module</strong> a été supprimé avec succès';
    // Configuration
$lang['addon.sub.directory']         = 'Sous-répertoire';
$lang['addon.github.configuration']  = 'Configuration des dépôts GitHub';
$lang['addon.github.token']          = 'Token GitHub API';
$lang['addon.github.token.clue']     = 'Lien vers le site Github api.github.com pour créer un token gratuit';
$lang['addon.modules.repos.add']     = 'Ajouter un repo GitHub pour les modules';
$lang['addon.themes.repos.add']      = 'Ajouter un repo GitHub pour les thèmes';
$lang['addon.langs.repos.add']       = 'Ajouter un repo GitHub pour les langues';
$lang['addon.repos.owner']           = 'Propriétaire du dépot';
$lang['addon.repos.repository']      = 'Nom du dépot';
$lang['addon.servers.configuration'] = 'Configuration des sites de téléchargement';
$lang['addon.servers.configuration.clue'] = '
    Le site doit contenir les sous-dossiers
    <span class="pinned bgc administrator">/' . GeneralConfig::load()->get_phpboost_major_version() . '/modules/</span>
    <span class="pinned bgc administrator">/' . GeneralConfig::load()->get_phpboost_major_version() . '/templates/</span>
    <span class="pinned bgc administrator">/' . GeneralConfig::load()->get_phpboost_major_version() . '/langs/</span>
';
$lang['addon.caches.configuration'] = 'Mise en cache des ressources';
$lang['addon.caches.configuration.clue'] = 'Cliquez sur le bouton ci-dessous pour mettre à jour les listes des extensions disponibles';
$lang['addon.servers.add']     = 'Ajouter un site de téléchargement';
$lang['addon.servers.website'] = 'Nom du site';
$lang['addon.servers.url']     = 'URL du site';

// Themes
$lang['addon.themes.directory']       = 'dans le dossier <span class ="text-strong pinned question">templates</span>';
$lang['addon.themes']                 = 'Thèmes';
$lang['addon.themes.management']      = 'Gestion des thèmes';
$lang['addon.themes.add']             = 'Ajouter un thème';
$lang['addon.themes.delete']          = 'Suppression d\'un thème';
$lang['addon.themes.delete.multiple'] = 'Suppression des thèmes sélectionnés';
$lang['addon.themes.installed']       = 'Thèmes installés';
$lang['addon.themes.available']       = 'Thèmes disponibles';
$lang['addon.themes.default']         = 'Thème par défaut';
$lang['addon.themes.default.clue']    = 'Le thème par défaut ne peut pas être désinstallé, désactivé ou réservé';
$lang['addon.themes.default.auth']    = 'Le thème par défaut est visible par tout le monde';
$lang['addon.themes.select.all']      = 'Sélectionner tous les thèmes';
    // Theme
$lang['addon.themes.html.version']      = 'Version HTML';
$lang['addon.themes.css.version']       = 'Version CSS';
$lang['addon.themes.main.color']        = 'Couleurs dominantes';
$lang['addon.themes.variable.width']    = 'Extensible';
$lang['addon.themes.width']             = 'Largeur';
$lang['addon.themes.parent.theme']      = 'Thème parent';
$lang['addon.themes.bot.informed']      = 'Non précisé';
$lang['addon.themes.view.real.preview'] = 'Voir en taille réelle';
    // Warnings
$lang['addon.themes.warning.delete']             = 'Un thème doit être activé, désactivé ou supprimé uniquement depuis cette page.<br />En aucun cas il ne faut intervenir sur le FTP et/ou dans la base de données.';
$lang['addon.themes.warning.install']            = 'Les thèmes ajoutés sont automatiquement activés. Pensez à les désactiver si besoin.';
$lang['addon.themes.warning.default']            = 'Le thème par défaut ne peut pas être désinstallé';
$lang['addon.themes.warning.version']            = 'La version PHPBoost de ce thème n\'est pas compatible avec la version PHPBoost du site';
$lang['addon.themes.parent.theme.not.installed'] = 'Le thème parent (<b>:id_parent</b>) de ce thème n\'est pas installé, veuillez l\'installer avant de pouvoir installer celui-ci';
$lang['addon.themes.default.parent.theme']       = 'Le thème <b>:name</b> est le parent du thème par défaut du site (<b>:default_theme</b>), il ne peut pas être désinstallé';
$lang['addon.themes.warning.childs.list']        = 'Les thèmes <b>:themes_names</b>, enfants du thème <b>:name</b> vont également être désinstallés';
$lang['addon.themes.warning.child']              = 'Le thème <b>:theme_name</b>, enfant du thème <b>:name</b> va également être désinstallé';
$lang['addon.themes.not.theme']                  = 'Ce dossier n\'est pas un dossier de thème.';
    // Upload
$lang['addon.themes.upload'] = 'Uploader un thème';
    // Drop
$lang['addon.themes.drop']          = 'Supprimer tous les fichiers du thème';
$lang['addon.themes.drop.multiple'] = 'Supprimer tous les fichiers des thèmes';

// ---- Sources distantes d'extension (GitHub / Site) ----

$lang['addon.refresh.cache'] = 'Rafraîchir le cache';
$lang['addon.add.source']    = 'Ajouter une source';
// GitHub
$lang['addon.github.running.repo']   = 'Dépôt courant';
$lang['addon.github.choose.repo']    = 'Choisir un dépôt';
$lang['addon.github.custom.repo']    = 'Utiliser un dépôt personnalisé';
$lang['addon.github.load.repo']      = 'Charger ce dépôt';
$lang['addon.github.view.repo']      = 'Voir sur GitHub';
$lang['addon.github.no.addon.found'] = 'Aucune extension compatible trouvée dans ce dépôt.';
$lang['addon.github.token.missing']  = 'Le token GitHub est manquant. <a href="' . AdminConfigUrlBuilder::addons_config()->rel() . '" class="offload">Configuration</a>';
$lang['addon.github.bad.token']      = 'Le token GitHub est invalide ou le fichier JSON est manquant. <a href="' . AdminConfigUrlBuilder::addons_config()->rel() . '" class="offload">Configuration</a>';

// Website
$lang['addon.website.running.server'] = 'Serveur courant';
$lang['addon.website.choose.server']  = 'Choisir un serveur de téléchargement';
$lang['addon.website.custom.server']  = 'Utiliser un serveur personnalisé';
$lang['addon.website.load']           = 'Charger ce serveur';
$lang['addon.website.no.addon.found'] = 'Aucune extension compatible trouvée sur ce serveur.';

// Common
$lang['addon.loading']               = 'Chargement&hellip;';
$lang['addon.installing']            = 'Installation&hellip;';
$lang['addon.already.installed']     = 'Déjà installé';
$lang['addon.source.error']          = 'Impossible d\'atteindre la source. Vérifiez l\'adresse ou votre connexion.';
$lang['addon.sub.directory']         = 'Sous-dossier';
$lang['addon.sub.directory.optional']   = 'Optionnel';
$lang['addon.themes.already.installed'] = 'Ce thème est déjà installé.';
$lang['addon.langs.already.installed']  = 'Cette langue est déjà installée.';
$lang['addon.warning.download.error']   = 'Le téléchargement a échoué. Vérifiez l\'URL ou votre connexion.';
?>
