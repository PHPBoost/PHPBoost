<?php
/*##################################################
 *                             guestbook_common.php
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

$lang = array();

//Titre du module
$lang['guestbook.module_title'] = 'Livre d\'or';

//Admin
$lang['guestbook.titles.admin.module_config'] = 'Configuration du module Livre d\'or';
$lang['guestbook.titles.admin.config'] = 'Configuration';
$lang['guestbook.titles.admin.authorizations'] = 'Autorisations';
$lang['guestbook.config.items_per_page'] = 'Nombre de messages par page';
$lang['guestbook.config.enable_captcha'] = 'Activer la protection anti-spam';
$lang['guestbook.config.captcha_difficulty'] = 'Difficulté de l\'anti spam';
$lang['guestbook.config.forbidden-tags'] = 'Types de formatage interdits';
$lang['guestbook.config.max_links'] = 'Nombre de liens maximum dans le message';
$lang['guestbook.config.max_links_explain'] = 'Mettre -1 pour illimité';
$lang['guestbook.config.post_rank']  = 'Autorisation d\'écriture';
$lang['guestbook.config.modo_rank'] = 'Autorisation de supprimer ou modifier un message';

//Titre
$lang['guestbook.titles.more_contents'] = '[Suite...]';
$lang['guestbook.titles.no_message'] = 'Aucun message';

//Erreurs
$lang['guestbook.error.require_items_per_page'] = 'Veuillez remplir le champ \"Nombre de messages par page\"';
$lang['guestbook.error.number-required'] = 'La valeur saisie doit être un nombre';

//Succès
$lang['guestbook.success.config'] = 'La configuration a été modifiée';
?>
