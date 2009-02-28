<?php
/*##################################################
 *                              articles_french.php
 *                            -------------------
 *   begin                : November 21, 2006
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *  
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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
#                                                          French                                                                        #
####################################################

//Admin
$LANG['written_by'] = 'Ecrit par';
$LANG['explain_page'] = 'Insérer une nouvelle page';
$LANG['page_prompt'] = 'Titre de la nouvelle page';
$LANG['summary'] = 'Sommaire';
$LANG['select_page'] = 'Sélectionnez une page';
$LANG['articles_management'] = 'Gestion des articles';
$LANG['articles_add'] = 'Ajouter un article';
$LANG['articles_config'] = 'Configuration des articles';
$LANG['articles_cats_add'] = 'Ajouter une catégorie';
$LANG['edit_article'] = 'Editer l\'article';
$LANG['cat_edit'] = 'Editer la catégorie';
$LANG['nbr_articles_max'] = 'Nombre maximum d\'articles affichés';
$LANG['articles_date'] = 'Date de l\'article <span class="text_small">(jj/mm/aa)</span> <br />
<span class="text_small">(Laisser vide pour mettre la date d\'aujourd\'hui)';
$LANG['icon_cat'] = 'Icône de la catégorie';
$LANG['icon_cat_explain'] = 'A placer dans le répertoire /articles';
$LANG['parent_category'] = 'Catégorie parente';
$LANG['explain_article'] = 'La catégorie que vous désirez supprimer contient <strong>1</strong> article, voulez-vous la conserver en la transférant dans une autre catégorie, ou bien la supprimer?';
$LANG['explain_articles'] = 'La catégorie que vous désirez supprimer contient <strong>%d</strong> articles, voulez-vous les conserver en les transférants dans une autre catégorie, ou bien tout supprimer?';
$LANG['explain_subcat'] = 'La catégorie que vous désirez supprimer contient <strong>1</strong> sous-catégorie, voulez-vous la conserver en la transférant dans une autre catégorie, ou bien la supprimer ainsi que son contenu?';
$LANG['explain_subcats'] = 'La catégorie que vous désirez supprimer contient <strong>%d</strong> sous-catégories, voulez-vous les conserver en les transférants dans une autre catégorie, ou bien les supprimer ainsi que leur contenu?';
$LANG['keep_articles'] = 'Conserver le(s) article(s)';
$LANG['keep_subcat'] = 'Conserver la/les catégorie(s)';
$LANG['move_articles_to'] = 'Déplacer le(s) article(s) vers';
$LANG['move_subcat_to'] = 'Déplacer la/les sous-catégorie(s) vers';
$LANG['cat_target'] = 'Catégorie cible';
$LANG['del_all'] = 'Suppression complète';
$LANG['del_articles_contents'] = 'Supprimer la catégorie "<strong>%s</strong>", ses <strong>sous-catégories</strong> et <strong>tout</strong> son contenu <span class="text_small">(Définitif)</span>';
$LANG['article_icon'] = 'Icône de l\'article';
$LANG['article_icon_explain'] = 'A placer dans le repertoire /articles';
$LANG['explain_articles_count'] = 'Recompter le nombre d\'articles par catégories';
$LANG['recount'] = 'Recompter';

//Erreurs
$LANG['e_unexist_articles'] = 'L\'article que vous avez demandé n\'existe pas';

//Titres
$LANG['title_articles'] = 'Articles';

//Articles
$LANG['articles'] = 'Articles';
$LANG['alert_delete_article'] = 'Supprimer cet article ?';
$LANG['propose_article'] = 'Proposer un article';
$LANG['none_article'] = 'Aucun article dans cette catégorie';
$LANG['xml_articles_desc'] = 'Derniers articles';
$LANG['no_note'] = 'Aucune note';
$LANG['actual_note'] = 'Note actuelle';
$LANG['vote'] = 'Voter';
$LANG['nbr_articles_info'] = '%d article(s) dans la catégorie';
$LANG['sub_categories'] = 'Sous catégories';

//Ajout article.
$MAIL['new_article_website'] = 'Nouvel article sur votre site web';
$MAIL['new_article'] = 'Un nouvel article a été ajouté sur votre site web ' . HOST . ', 
il devra être approuvé avant d\'être visible sur le site par tout le monde.

Titre de l\'article: %s
Contenu: %s...[suite]
Posté par: %s

Rendez-vous dans le panneau gestion des articles de l\'administration, pour l\'approuver.
' . HOST . DIR . '/admin/admin_articles_gestion.php';

$LANG['read_feed'] = 'Lire l\'article';
$LANG['posted_on'] = 'Le';
?>