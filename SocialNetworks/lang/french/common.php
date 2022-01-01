<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 05 24
 * @since       PHPBoost 5.1 - 2018 01 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     French                       #
####################################################

$lang['sn.module.title'] = 'Réseaux Sociaux';

// Authentication Configuration
$lang['sn.authentication.curl.extension.disabled'] = 'L\'extension <b>php_curl</b> est désactivée sur ce serveur. Veuillez l\'activer pour utiliser les authentifications des réseaux sociaux.';

$lang['sn.enable.authentication']               = 'Activer l\'authentification via :name';
$lang['sn.enable.authentication.clue']          = 'Rendez vous sur <a href=":identifiers_creation_url">:identifiers_creation_url</a> pour créer vos identifiants.';
$lang['sn.enable.authentication.key.only.clue'] = 'Rendez vous sur <a href=":identifiers_creation_url">:identifiers_creation_url</a> pour créer votre identifiant.';
$lang['sn.enable.authentication.callback.url.clue'] = '
    <br/>Indiquez l\'URL de redirection suivante lors de la configuration :
    <br/><b>:callback_url</b>
';
$lang['sn.authentication.client.id']            = ':name Id ou Key';
$lang['sn.authentication.client.secret']        = ':name Secret';
$lang['sn.authentication.no.identifier.needed'] = 'Aucun identifiant n\'est nécessaire pour ce réseau social';

// Configuration
$lang['sn.order.management']        = 'Ordre d\'affichage des réseaux sociaux';
$lang['sn.visible.on.mobile.only']  = 'Visible sur périphérique mobile uniquement';
$lang['sn.visible.on.desktop.only'] = 'Visible sur ordinateur uniquement';
$lang['sn.display.share.link']      = 'Afficher le lien de partage';
$lang['sn.hide.share.link']         = 'Cacher le lien de partage';
$lang['sn.no.sharing.content.url']  = 'Ce réseau social n\'a pas de lien de partage, il n\'apparaîtra pas dans la liste des liens de partage mais uniquement dans la liste des modes de connexion si celle-ci est activée.';

$lang['sn.menu.position']            = 'Positionnement du menu';
$lang['sn.menu.mini.module.message'] = 'Pour afficher les liens de partage dans un mini-module sur toutes les pages, activez le mini-module dédié dans la <a href="' . PATH_TO_ROOT . '/admin/menus/menus.php#module-mini-social-networks">gestion des Menus</a>.';
$lang['sn.menu.content.message']     = 'Pour afficher les liens de partage sur les pages de contenu uniquement, activez l\'option <b>Afficher les liens de partage sur les pages de contenu</b> dans la <a href="' . AdminContentUrlBuilder::content_configuration()->rel() . '#AdminContentConfigController_sharing_config">gestion du Contenu</a>.';

// Sign in label
$lang['sn.sign.in.label'] = 'Connexion :name';

?>
