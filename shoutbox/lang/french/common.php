<?php
/*##################################################
 *                             common.php
 *                            -------------------
 *   begin                : November 28, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
$lang['config.refresh_delay'] = 'Délai de rafraichissement automatique de la discussion';
$lang['config.refresh_delay.explain'] = 'En minutes. Mettre 0 pour désactiver.';
$lang['config.date_displayed'] = 'Afficher la date';
$lang['config.max_messages_number'] = 'Nombre de messages maximum à conserver';
$lang['config.max_messages_number.explain'] = 'Supprimés tous les jours, mettre -1 pour désactiver';
$lang['config.max_links_number_per_message'] = 'Nombre de liens maximum dans le message';
$lang['config.max_links_number_per_message.explain'] = 'Mettre -1 pour illimité';

$lang['error.message.delete'] = 'Erreur lors de la suppression du message';
?>