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
$lang['authentication.config.curl_extension_disabled'] = 'L\'extension <b>php_curl</b> est désactivée sur ce serveur. Veuillez l\'activez pour utiliser les authentifications des réseaux sociaux.';

$lang['authentication.config.authentication-enabled'] = 'Activer l\'authentification via :name';
$lang['authentication.config.authentication-enabled-explain'] = 'Rendez-vous sur <a href=":identifiers_creation_url">:identifiers_creation_url</a> pour créer vos identifiants.<br/>
Indiquez l\'URL de redirection suivante lors de la configuration :<br/>
<b>:callback_url</b>';
$lang['authentication.config.client-id'] = ':name Id ou Key';
$lang['authentication.config.client-secret'] = ':name Secret';

//Configuration
$lang['admin.order.manage'] = 'Ordre d\'affichage des réseaux sociaux';

//Sign in label
$lang['sign-in-label'] = 'Connexion :name';

?>