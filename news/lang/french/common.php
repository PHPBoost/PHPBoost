<?php
/*##################################################
 *		                         common.php
 *                            -------------------
 *   begin                : February 20, 2013
 *   copyright            : (C) 2013 Kevin MASSY
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

$lang['module_config_title'] = 'Configuration des news';

$lang['news'] = 'News';
$lang['news.add'] = 'Ajouter une news';
$lang['news.edit'] = 'Modifier une news';
$lang['news.pending'] = 'News en attente';
$lang['news.manage'] = 'Gérer les news';
$lang['news.management'] = 'Gestion des news';

$lang['news.seo.description.root'] = 'Toutes les news du site :site.';
$lang['news.seo.description.tag'] = 'Toutes les news sur le sujet :subject.';
$lang['news.seo.description.pending'] = 'Toutes les news en attente.';

$lang['news.form.short_contents'] = 'Condensé de la news';
$lang['news.form.short_contents.description'] = 'Pour que le condensé de la news soit affiché, veuillez activer l\'option dans la configuration du module';
$lang['news.form.short_contents.enabled'] = 'Personnaliser le condensé de la news';
$lang['news.form.short_contents.enabled.description'] = 'Si non coché, la news est automatiquement coupée à :number caractères et le formatage du texte supprimé.';
$lang['news.form.top_list'] = 'Placer la news en tête de liste';
$lang['news.form.contribution.explain'] = 'Vous n\'êtes pas autorisé à créer une news, cependant vous pouvez en proposer une.';

//Administration
$lang['admin.config.number_columns_display_news'] = 'Nombre de colonnes pour afficher les news';
$lang['admin.config.display_condensed'] = 'Afficher le condensé de la news et non la news entière';
$lang['admin.config.display_descriptions_to_guests'] = 'Afficher le condensé des news aux visiteurs s\'ils n\'ont pas l\'autorisation de lecture';
$lang['admin.config.number_character_to_cut'] = 'Nombre de caractères pour couper la news';
$lang['admin.config.news_suggestions_enabled'] = 'Activer l\'affichage des suggestions';
$lang['admin.config.news_number_view_enabled'] = 'Activer l\'affichage du nombre de vues';

//Feed name
$lang['feed.name'] = 'Actualités';

//Messages
$lang['news.message.success.add'] = 'La news <b>:name</b> a été ajoutée';
$lang['news.message.success.edit'] = 'La news <b>:name</b> a été modifiée';
$lang['news.message.success.delete'] = 'La news <b>:name</b> a été supprimée';
?>
