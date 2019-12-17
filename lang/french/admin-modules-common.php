<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2019 04 15
 * @since       PHPBoost 3.0 - 2011 09 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

####################################################
#                     French                       #
####################################################

$lang['module'] = 'Module';

//Title
$lang['modules.module_management'] = 'Gestion des modules';
$lang['modules.add_module'] = 'Ajouter un module';
$lang['modules.update_module'] = 'Mettre à jour un module';
$lang['modules.delete_module'] = 'Suppression d\'un module';
$lang['modules.delete_module_multiple'] = 'Suppression de modules';
$lang['modules.installed_modules'] = 'Modules installés';
$lang['modules.available_modules'] = 'Modules disponibles';

//Warnings
$lang['modules.warning_before_delete'] = '<span class="message-helper bgc warning">Un module doit être activé, désactivé ou supprimé uniquement depuis cette page. <br />En aucun cas il ne faut intervenir sur le FTP et/ou dans la base de données.</span><span class="message-helper bgc notice">Les modules ajoutés sont automatiquement activés. Pensez à les désactiver si besoin.</span>';
$lang['modules.add.warning_before_install'] = '<span class="message-helper bgc notice">Les modules ajoutés sont automatiquement activés. Pensez à les désactiver si besoin.</span>';
$lang['modules.update.warning_before_update'] = '<span class="message-helper bgc notice">Les modules mis à jour sont automatiquement activés. Pensez à les désactiver si besoin.</span>';

//Upload
$lang['modules.upload_module'] = 'Uploader un module';
$lang['modules.upload_description'] = 'L\'archive uploadée doit être au format zip ou gzip et ne doit pas dépasser :max_size. En cas de dépassement, déposez le dossier extrait de l\'archive à la <b>racine</b> de votre site sur votre FTP.';

//Module
$lang['modules.php.version'] = 'Version PHP';
$lang['module.documentation'] = 'Documentation';
$lang['module.documentation_of'] = 'Documentation of';

//Module management
$lang['modules.select_all_modules'] = 'Sélectionner tous les modules';

//Messages
$lang['modules.already_installed'] = 'Ce module est déjà installé';
$lang['modules.module_not_upgradable'] = 'Ce module ne peut pas être mis à jour';
$lang['modules.not_installed_module'] = 'Ce module n\'est pas installé !';
$lang['modules.updates_available'] = 'Mises à jour disponibles';
$lang['modules.config_conflict'] = 'Conflit avec la configuration du module, installation impossible!';
$lang['modules.not_compatible'] = 'Ce module n\'est pas compatible avec la version actuelle de PHPBoost vérifiez la disponibilité d\'une nouvelle version sur <a href="https://www.phpboost.com/download">le site de PHPBoost</a>.';

//Delete module
$lang['modules.drop_files'] = 'Supprimer tous les fichiers du module';
$lang['modules.drop_files_multiple'] = 'Supprimer tous les fichiers des modules';

//Update
$lang['modules.upgrade_module'] = 'Mettre à jour';
$lang['modules.upgrade_all_selected_modules'] = 'Mettre à jour les modules sélectionnés';
?>
