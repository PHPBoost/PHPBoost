<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 12 20
 * @since       PHPBoost 5.1 - 2018 01 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

####################################################
#                     French                       #
####################################################

$lang['module_name'] = 'Réseaux Sociaux';
$lang['module_config_title'] = 'Configuration du module Réseaux sociaux';

//Authentication Configuration
$lang['authentication.config.curl_extension_disabled'] = 'L\'extension <b>php_curl</b> est désactivée sur ce serveur. Veuillez l\'activer pour utiliser les authentifications des réseaux sociaux.';

$lang['authentication.config.authentication-enabled'] = 'Activer l\'authentification via :name';
$lang['authentication.config.authentication-enabled-explain'] = 'Rendez-vous sur <a href=":identifiers_creation_url">:identifiers_creation_url</a> pour créer vos identifiants.';
$lang['authentication.config.authentication-enabled-explain.key-only'] = 'Rendez-vous sur <a href=":identifiers_creation_url">:identifiers_creation_url</a> pour créer votre identifiant.';
$lang['authentication.config.authentication-enabled-explain.callback-url'] = '<br/>
Indiquez l\'URL de redirection suivante lors de la configuration :<br/>
<b>:callback_url</b>';
$lang['authentication.config.client-id'] = ':name Id ou Key';
$lang['authentication.config.client-secret'] = ':name Secret';
$lang['authentication.config.no-identifier-needed'] = 'Aucun identifiant n\'est nécessaire pour ce réseau social';

//Configuration
$lang['admin.order.manage'] = 'Ordre d\'affichage des réseaux sociaux';
$lang['admin.visible_on_mobile_only'] = 'Visible sur périphérique mobile uniquement';
$lang['admin.visible_on_desktop_only'] = 'Visible sur ordinateur uniquement';
$lang['admin.display_share_link'] = 'Afficher le lien de partage';
$lang['admin.hide_share_link'] = 'Cacher le lien de partage';
$lang['admin.no_sharing_content_url'] = 'Ce réseau social n\'a pas de lien de partage, il n\'apparaîtra pas dans la liste des liens de partage mais uniquement dans la liste des modes de connexion si celle-ci est activée.';
$lang['admin.menu.position'] = 'Positionnement du menu';
$lang['admin.menu.mini_module_message'] = 'Pour afficher les liens de partage dans un mini-module sur toutes les pages, activez le mini-module dédié dans la <a href="' . PATH_TO_ROOT . '/admin/menus/menus.php">gestion des Menus</a>.';
$lang['admin.menu.content_message'] = 'Pour afficher les liens de partage sur les pages de contenu uniquement, activez l\'option <b>Afficher les liens de partage sur les pages de contenu</b> dans la <a href="' . AdminContentUrlBuilder::content_configuration()->rel() . '">gestion du Contenu</a>.';


//Sign in label
$lang['sign-in-label'] = 'Connexion :name';

?>
