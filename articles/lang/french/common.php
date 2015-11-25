<?php
/*##################################################
 *                        articles_common.php
 *                            -------------------
 *   begin                : February 27, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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
 #                      French					    #
 ####################################################

//Titles
$lang['articles'] = 'Articles';
$lang['article'] = 'Article';
$lang['module_config_title'] = 'Configuration des articles';
$lang['articles_management'] = 'Gestion des articles';
$lang['articles.add'] = 'Ajouter un article';
$lang['articles.edit'] = 'Modification d\'un article';
$lang['articles.feed_name'] = 'Derniers articles';
$lang['articles.pending_articles'] = 'Articles en attente';
$lang['articles.published_articles'] = 'Articles publiés';
$lang['articles.select_page'] = 'Sélectionnez une page';
$lang['articles.summary'] = 'Sommaire :';
$lang['articles.print.article'] = 'Impression d\'un article';

//Articles configuration
$lang['articles_configuration.display_icon_cats'] = 'Afficher l\'icône des catégories';
$lang['articles_configuration.number_cols_display_cats'] = 'Nombre de colonnes à afficher pour les catégories';
$lang['articles_configuration.number_character_to_cut'] = 'Nombre de caractères pour couper le condensé de l\'article';
$lang['articles_configuration.display_type'] = 'Type d\'affichage des articles';
$lang['articles_configuration.display_type.mosaic'] = 'Mosaïque';
$lang['articles_configuration.display_type.list'] = 'Liste';
$lang['articles_configuration.display_type.block'] = 'Liste sans image';
$lang['articles_configuration.display_descriptions_to_guests'] = 'Afficher le condensé des articles aux visiteurs s\'ils n\'ont pas l\'autorisation de lecture';

//Form
$lang['articles.form.description'] = 'Description (maximum :number caractères)';
$lang['articles.form.description_enabled'] = 'Activer le condensé de l\'article';
$lang['articles.form.description_enabled.description'] = 'ou laissez PHPBoost couper le contenu à :number caractères';
$lang['articles.form.add_page'] = 'Insérer une page';
$lang['articles.form.add_page.title'] = 'Titre de la nouvelle page';

//Sort fields title and mode
$lang['articles.sort_field.views'] = 'Vues';
$lang['admin.articles.sort_field.published'] = 'Publié';

//SEO
$lang['articles.seo.description.root'] = 'Tous les articles du site :site.';
$lang['articles.seo.description.tag'] = 'Tous les articles sur le sujet :subject.';
$lang['articles.seo.description.pending'] = 'Tous les articles en attente.';

//Messages
$lang['articles.message.success.add'] = 'L\'article <b>:title</b> a été ajouté';
$lang['articles.message.success.edit'] = 'L\'article <b>:title</b> a été modifié';
$lang['articles.message.success.delete'] = 'L\'article <b>:title</b> a été supprimé';
?>
