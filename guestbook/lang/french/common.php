<?php
/*##################################################
 *                             common.php
 *                            -------------------
 *   begin                : November 30, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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
# French                                           #
####################################################

//Module title
$lang['module_title'] = 'Livre d\'or';

//Title
$lang['guestbook.titles.more_contents'] = '[Suite...]';
$lang['guestbook.titles.no_message'] = 'Aucun message';
$lang['guestbook.delete_message_confirm'] = 'Etes-vous sûr de vouloir supprimer ce message ?';
$lang['guestbook.add'] = 'Signer le livre d\'or';
$lang['guestbook.edit'] = 'Edition d\'un message';

//Admin
$lang['admin.config'] = 'Configuration';
$lang['admin.config.items_per_page'] = 'Nombre de messages par page';
$lang['admin.config.enable_captcha'] = 'Activer la protection anti-spam';
$lang['admin.config.captcha_difficulty'] = 'Difficulté de l\'anti spam';
$lang['admin.config.forbidden-tags'] = 'Formats interdits';
$lang['admin.config.max_links'] = 'Nombre de liens maximum dans le message';
$lang['admin.config.max_links_explain'] = 'Mettre -1 pour illimité';
$lang['admin.authorizations'] = 'Autorisations';
$lang['admin.authorizations.read']  = 'Autorisation d\'afficher le livre d\'or';
$lang['admin.authorizations.write']  = 'Autorisation d\'écriture';
$lang['admin.authorizations.moderation']  = 'Autorisation de supprimer ou modifier un message';
$lang['admin.config.success'] = 'La configuration a été modifiée';
$lang['admin.config.error.require_items_per_page'] = 'Veuillez remplir le champ \"Nombre de messages par page\"';
$lang['admin.config.error.number-required'] = 'La valeur saisie doit être un nombre';

?>
