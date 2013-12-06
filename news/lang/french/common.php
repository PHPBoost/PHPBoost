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

$lang['news'] = 'News';
$lang['news.add'] = 'Ajouter une news';
$lang['news.edit'] = 'Modifier une news';
$lang['news.pending'] = 'News en attente';
$lang['news.manage'] = 'Gérer les news';
$lang['news.management'] = 'Gestion des news';

$lang['news.message.no_items'] = 'Aucune news n\'est disponible pour le moment';

$lang['news.seo.description.root'] = 'Toutes les news du site :site.';
$lang['news.seo.description.tag'] = 'Toutes les news sur le sujet :subject.';
$lang['news.seo.description.pending'] = 'Toutes les news en attente.';

$lang['news.display.written_by'] = 'Posté par';
$lang['news.display.posted_on'] = 'le';
$lang['news.display.in_category'] = 'dans la catégorie';

$lang['news.form.name'] = 'Nom de la news';
$lang['news.form.rewrited_name'] = 'Nom de votre news dans l\'url';
$lang['news.form.rewrited_name.description'] = 'Contient uniquement des lettres minuscules, des chiffres et des traits d\'union.';
$lang['news.form.rewrited_name.personalize'] = 'Personnaliser le nom de la news dans l\'url';
$lang['news.form.short_contents'] = 'Condensé de la news';
$lang['news.form.short_contents.enabled'] = 'Personnaliser le condensé de la news';
$lang['news.form.short_contents.enabled.description'] = 'Ou laisser PHPBoost couper la news à :number caractères.';
$lang['news.form.approved.not'] = 'Gardée en brouillon';
$lang['news.form.approved.now'] = 'Publiée';
$lang['news.form.approved.date'] = 'Publiée en différé';
$lang['news.form.top_list'] = 'Placer la news en tête de liste';
$lang['news.form.keywords.description'] = 'Vous permet d\'ajouter des mots clés à votre news';
$lang['news.form.picture'] = 'Image de la news';
$lang['news.form.contribution.explain'] = 'Vous n\'êtes pas autorisé à créer une news, cependant vous pouvez en proposer une.';

//Administration
$lang['admin.config.number_news_per_page'] = 'Nombre de news par page';
$lang['admin.config.number_columns_display_news'] = 'Nombre de colonnes pour afficher les news';
$lang['admin.config.display_condensed'] = 'Afficher le condensé de la news et non la news entière';
$lang['admin.config.number_character_to_cut'] = 'Nombre de caractères pour couper la news';
$lang['admin.config.comments_enabled'] = 'Activer les commentaires';
$lang['admin.config.news_suggestions_enabled'] = 'Activer l\'affichage des suggestions';
$lang['admin.config.display_type'] = 'Type d\'affichage des news';
$lang['admin.config.display_type.block'] = 'Affichage en bloc';
$lang['admin.config.display_type.list'] = 'Affichage en liste';

//Feed name
$lang['feed.name'] = 'Actualités';
$lang['syndication'] = 'Flux RSS';
?>