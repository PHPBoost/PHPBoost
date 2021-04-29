<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 29
 * @since       PHPBoost 3.0 - 2011 04 20
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

####################################################
#                     French                       #
####################################################

// Title
$lang['themes.theme_management'] = 'Gestion des thèmes';
$lang['themes.add_theme'] = 'Ajouter un thème';
$lang['themes.delete_theme'] = 'Suppression d\'un thème';
$lang['themes.delete_theme_multiple'] = 'Suppression des thèmes sélectionnés';
$lang['themes.installed_theme'] = 'Thèmes installés';
$lang['themes.available_themes'] = 'Thèmes disponibles';
$lang['themes.default'] = 'Thème par défaut';
$lang['themes.default_theme_explain'] = 'Le thème par défaut ne peut pas être désinstallé, désactivé ou réservé';
$lang['themes.default_theme_visibility'] = 'Le thème par défaut est visible par tout le monde';

//Theme
$lang['themes.html_version'] = 'Version HTML';
$lang['themes.css_version'] = 'Version CSS';
$lang['themes.main_color'] = 'Couleurs dominantes';
$lang['themes.variable-width'] = 'Extensible';
$lang['themes.width'] = 'Largeur';
$lang['themes.bot_informed'] = 'Non renseigné';
$lang['themes.view_real_preview'] = 'Voir en taille réelle';

//Themes management
$lang['themes.select_all_themes'] = 'Sélectionner tous les thèmes';

//Avertissements
$lang['themes.warning_before_delete'] = '<span class="message-helper bgc warning">Un thème doit être activé, désactivé ou supprimé uniquement depuis cette page.<br />En aucun cas il ne faut intervenir sur le FTP et/ou dans la base de données.</span><span class="message-helper bgc notice">Les thèmes ajoutés sont automatiquement activés. Pensez à les désactiver si besoin.</span>';
$lang['themes.add.warning_before_install'] = '<span class="message-helper bgc notice">Les thèmes ajoutés sont automatiquement activés. Pensez à les désactiver si besoin.</span>';
$lang['themes.default.theme.not.removable'] = 'Le thème par défaut ne peut pas être désinstallé';
$lang['themes.parent.theme.not.installed'] = 'Le thème parent (<b>:id_parent</b>) de ce thème n\'est pas installé, veuillez l\'installer avant de pouvoir installer celui-ci';
$lang['themes.parent.of.default.theme'] = 'Le thème <b>:name</b> est le parent du thème par défaut du site (<b>:default_theme</b>), il ne peut pas être désinstallé';
$lang['themes.theme.childs.list.uninstallation.warning'] = 'Les thèmes <b>:themes_names</b>, enfants du thème <b>:name</b> vont également être désinstallés';
$lang['themes.theme.child.uninstallation.warning'] = 'Le thème <b>:theme_name</b>, enfant du thème <b>:name</b> va également être désinstallé';

//Upload
$lang['themes.upload_theme'] = 'Uploader un thème';
$lang['themes.upload_description'] = 'L\'archive uploadée doit être au format zip ou gzip et ne doit pas dépasser :max_size. En cas de dépassement, déposez le dossier extrait de l\'archive dans le dossier <b>templates</b> de votre site sur votre FTP.';

//Delete theme
$lang['themes.drop_files'] = 'Supprimer tous les fichiers du thème';
$lang['themes.drop_files_multiple'] = 'Supprimer tous les fichiers des thèmes';
?>
