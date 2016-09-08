<?php
/*##################################################
 *                             common.php
 *                            -------------------
 *   begin                : November 28, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 #						French						#
 ####################################################

$lang['module_title'] = 'Discussion';
$lang['module_config_title'] = 'Configuration du module discussion';

$lang['archives'] = 'Archives';
$lang['shoutbox.add'] = 'Ajouter un message';
$lang['shoutbox.edit'] = 'Edition d\'un message';

//Config
$lang['config.items_number_per_page'] = 'Nombre d\'éléments affichés par page dans les archives';
$lang['config.max_messages_number_enabled'] = 'Limiter le nombre de messages dans les archives';
$lang['config.max_messages_number'] = 'Nombre de messages maximum à conserver';
$lang['config.max_links_number_per_message_enabled'] = 'Limiter le nombre de liens dans les messages';
$lang['config.max_links_number_per_message'] = 'Nombre de liens maximum dans un message';
$lang['config.no_write_authorization_message_displayed'] = 'Afficher un message pour les utilisateurs qui n\'ont pas l\'autorisation d\'ajouter un message';
$lang['config.shoutbox_menu'] = 'Menu discussion';
$lang['config.automatic_refresh_enabled'] = 'Activer le rafraichissement automatique de la discussion';
$lang['config.refresh_delay'] = 'Délai de rafraichissement';
$lang['config.refresh_delay.explain'] = 'En minutes';
$lang['config.date_displayed'] = 'Afficher la date';
$lang['config.shout_max_messages_number_enabled'] = 'Limiter le nombre de messages affichés';
$lang['config.shout_max_messages_number'] = 'Nombre de messages maximum à afficher';
$lang['config.shout_bbcode_enabled'] = 'Afficher une mini barre BBcode avant le bouton Envoyer';
$lang['config.validation_onkeypress_enter_enabled'] = 'Envoyer le message en appuyant sur la touche Entrée';

//Messages
$lang['shoutbox.message.success.delete'] = 'Le message a été supprimé';

//Errors
$lang['error.message.delete'] = 'Erreur lors de la suppression du message';
$lang['error.post.unauthorized'] = 'Vous n\'êtes pas autorisé à ajouter un message !';
?>
