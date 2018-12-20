<?php
/*##################################################
 *		                         common.php
 *                            -------------------
 *   begin                : January 05, 2018
 *   copyright            : (C) 2018 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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