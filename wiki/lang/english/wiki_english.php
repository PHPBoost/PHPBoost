<?php
/*##################################################
 *                              wiki_english.php
 *                            -------------------
 *   begin                : October 14, 2007
 *   copyright          : (C) 2007 Benoît Sautel
 *   email                : ben.popeye@phpboost.com
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
#                                                           English                                                                             #
####################################################

//Généralités
$LANG['wiki'] = 'Wiki';
$LANG['wiki_article_hits'] = 'This page has been seen %d times';
$LANG['wiki_history'] = 'History';
$LANG['wiki_contribution_tools'] = 'Contribuate';
$LANG['wiki_other_tools'] = 'Tools';
$LANG['wiki_author'] = 'Author';
$LANG['wiki_empty_index'] = 'The wiki is empty. If you have an administrator account you can create page and edit the wiki index into the administration panel.';
$LANG['wiki_previewing'] = 'Previewing';
$LANG['wiki_table_of_contents'] = 'Table of contents';

//Actions
$LANG['wiki_random_page'] = 'Random page';
$LANG['wiki_restriction_level'] = 'Permission level';
$LANG['wiki_article_status'] = 'Article status';

//Poster
$LANG['wiki_contents'] = 'Article contents';
$LANG['wiki_article_title'] = 'Article title';
$LANG['wiki_create_article'] = 'Create an article';
$LANG['wiki_add_article'] = 'Add an article';
$LANG['wiki_add_cat'] = 'Add a category';
$LANG['wiki_article_cat'] = 'Category of this article';
$LANG['wiki_create_cat'] = 'Create a category';
$LANG['wiki_update_index'] = 'Edit wiki index';
$LANG['wiki_warning_updated_article'] = '<span class="warning"><strong>Warning :</strong> This article has been updated, you are now consulting an old release of this article !</span>';
$LANG['wiki_article_cat'] = 'Category of the article';
$LANG['wiki_current_cat'] = 'Current category';
$LANG['wiki_contribuate'] = 'Contribuate to the wiki';
$LANG['wiki_edit_article'] = 'Edition of the article <em>%s</em>';
$LANG['wiki_edit_cat'] = 'Edition of the category <em>%s</em>';
$LANG['wiki_move'] = 'Move';
$LANG['wiki_rename'] = 'Rename';
$LANG['wiki_no_cat'] = 'No existing category';
$LANG['wiki_no_sub_cat'] = 'No existing subcategory';
$LANG['wiki_no_article'] = 'No existing article';
$LANG['wiki_no_sub_article'] = 'No existing subarticle';
$LANG['wiki_no_selected_cat'] = 'No selected cat';
$LANG['wiki_do_not_select_any_cat'] = 'Root';
$LANG['wiki_please_enter_a_link_name'] = 'Please enter a link name';
$LANG['wiki_insert_a_link'] = 'Insert a link into the article';
$LANG['wiki_insert_link'] = 'Insert a link';
$LANG['wiki_title_link'] = 'Article title';
$LANG['wiki_no_js_insert_link'] = 'If you want to insert a link up to an article you can use link tag : [link=a]b[/link] where a is the title of the article up to which you want to create a link (enough special characters) and b represents the name of the link.';
$LANG['wiki_explain_paragraph'] = 'Insert a paragraph of level %d';
$LANG['wiki_help_tags'] = 'Know more about wiki specific tags';
$LANG['wiki_help_url'] = 'http://www.phpboost.com/wiki/';
$LANG['wiki_paragraph_name'] = 'Please enter a paragraph name';
$LANG['wiki_paragraph_name_example'] = 'Paragraph title';

//Restrictions d'accès
$LANG['wiki_member_restriction'] = 'This article is protected, only members car edit it.';
$LANG['wiki_modo_restriction'] = 'This article is protected, only moderators can edit it.';
$LANG['wiki_admin_restriction'] = 'This article is protected, only administrators can edit it.';
$LANG['wiki_edition_restriction'] = 'Edition permissions';
$LANG['wiki_no_restriction'] = 'No restriction';
$LANG['wiki_auth_management'] = 'Permissions management';
$LANG['wiki_auth_management_article'] = 'Permission management of the article <em>%s</em>';
$LANG['explain_select_multiple'] = 'Press Ctrl then click into the list to choose several options.';
$LANG['select_all'] = 'Select all';
$LANG['select_none'] = 'Unselect all';
$LANG['ranks'] = 'Ranks';
$LANG['groups'] = 'Groups';
$LANG['wiki_explain_restore_default_auth'] = 'Don\'t take into consideration any particular restriction to this article ; permissions of this article will be global permissions.';
$LANG['wiki_restore_default_auth'] = 'Default permissions';

//Catégories
$LANG['wiki_last_articles_list'] = 'Last updated articles :';
$LANG['wiki_cats_list'] = 'List of main categories';
$LANG['wiki_articles_of_this_cat'] = 'Articles of this category';
$LANG['wiki_subcats'] = 'Categories contained by this category :';

//Archives
$LANG['wiki_version_list'] = 'Releases';
$LANG['wiki_article_does_not_exist'] = 'The article you want to read doesn\'t exist, if you want to create it you can do it on this page.';
$LANG['wiki_cat_does_not_exist'] = 'Error : the category you want to read doesn\'t exist. <a href="wiki.php">Go back to wiki index.</a>';
$LANG['wiki_consult_article'] = 'Read';
$LANG['wiki_restore_version'] = 'Restore';
$LANG['wiki_possible_actions'] = 'Possible actions';
$LANG['wiki_no_possible_action'] = 'No possible action';
$LANG['wiki_current_version'] = 'Current version';

//Statut de l'article
$LANG['wiki_status_management'] = 'Articles status management';
$LANG['wiki_status_management_article'] = 'Status management of the article <em>%s</em>';
$LANG['wiki_defined_status'] = 'Defined status';
$LANG['wiki_undefined_status'] = 'Personalized status';
$LANG['wiki_no_status'] = 'No status';
$LANG['wiki_status_explain'] = 'You can here select the status of this article. Several different status permit you to order your articles and show a particular point of each article.
<br />
You can assign as well defined status to you articles than personalized one. To use a defined status let the personalized field empty.';
$LANG['wiki_current_status'] = 'Current status';

$LANG['wiki_status_list'] = array(
	array('Quality article', '<span class="notice">This article is very good.</span>'),
	array('Unachieved article', '<span class="question">This article lacks sources. <br />Your knowlegde is welcome to complete it.</span>'),
	array('Article in transformation', '<span class="notice">This article is not complete, you can use your knowledge to complete it.</span>'),
	array('Article à refaire', '<span class="warning">Cet article est à refaire, son contenu n\'est pas très fiable.</span>'),
	array('Article remis en cause', '<span class="error">Cet article a été discuté et son contenu ne paraît pas correct. Vous pouvez éventuellement consulter les discussions à ce propos et peut-être y apporter vos connaissances.</span>')
);

//Déplacement de l'article
$LANG['wiki_moving_article'] = 'Déplacement d\'un article';
$LANG['wiki_moving_this_article'] = 'Déplacement de l\'article: %s';
$LANG['wiki_change_cat'] = 'Changer de catégorie';
$LANG['wiki_cat_contains_cat'] = 'Vous souhaitez placer cette catégorie dans une de ses catégories filles ou dans elle-même, ce qui est impossible!';

//Renommer l'article
$LANG['wiki_renaming_article'] = 'Renommer un article';
$LANG['wiki_renaming_this_article'] = 'Renommer l\'article: %s';
$LANG['wiki_new_article_title'] = 'Nouveau titre de l\'article';
$LANG['wiki_explain_renaming'] = 'Vous êtes sur le point de renommer un article. Attention, vous devez savoir que tous les liens menant à cet article seront rompus. Cependant vous pouvez demander à laisser une redirection vers le nouvel article, ce qui permettra de ne pas briser les liens.';
$LANG['wiki_create_redirection_after_renaming'] = 'Créer une redirection automatique depuis l\'ancien article vers le nouveau';
$LANG['wiki_title_already_exists'] = 'Le titre que vous avez choisi existe déjà. Veuillez en choisir un autre';

//Redirection
$LANG['wiki_redirecting_from'] = 'Redirigé depuis %s';
$LANG['wiki_remove_redirection'] = 'Supprimer la redirection';
$LANG['wiki_redirections'] = 'Redirections';
$LANG['wiki_redirections_management'] = 'Gestion des redirections';
$LANG['wiki_edit_redirection'] = 'Edition d\'une redirection';
$LANG['wiki_redirections_to_this_article'] = 'Redirections menant à l\'article: <em>%s</em>';
$LANG['wiki_redirection_name'] = 'Titre de la redirection';
$LANG['wiki_redirection_delete'] = 'Supprimer la redirection';
$LANG['wiki_alert_delete_redirection'] = 'Etes-vous sur de vouloir supprimer cette redirection?';
$LANG['wiki_no_redirection'] = 'Il n\'y a aucune redirection vers cette page';
$LANG['wiki_create_redirection'] = 'Créer une redirection vers cet article';
$LANG['wiki_create_redirection_to_this'] = 'Créer une redirection vers l\'article <em>%s</em>';

//Recherche
$LANG['wiki_search'] = 'Rechercher';
$LANG['wiki_search_key_words'] = 'Mots clés (4 caractères minimum)';
$LANG['wiki_search_result'] = 'Résultats de la recherche';
$LANG['wiki_search_relevance'] = 'Pertinence du résultat';
$LANG['wiki_empty_search'] = 'Aucun article n\'a été trouvé.';

//Discussion
$LANG['wiki_article_com'] = 'Discussion sur l\'article';
$LANG['wiki_article_com_article'] = 'Discussion';

//Suppression
$LANG['wiki_confirm_delete_archive'] = 'Etes-vous sûr de vouloir supprimer cette version de l\'article?';
$LANG['wiki_remove_cat'] = 'Suppression d\'une catégorie';
$LANG['wiki_remove_this_cat'] = 'Suppression de la catégorie: <em>%s</em>';
$LANG['wiki_explain_remove_cat'] = 'Vous souhaitez supprimer cette catégorie. Vous pouvez supprimer tout son contenu ou transférer son contenu ailleurs. L\'article associé à cette catégorie sera quant à lui obligatoirement supprimé.';
$LANG['wiki_remove_all_contents'] = 'Supprimer tout son contenu (action irréversible)';
$LANG['wiki_move_all_contents'] = 'Déplacer tout son contenu dans le dossier suivant:';
$LANG['wiki_future_cat'] = 'Catégorie dans laquelle vous souhaitez déplacer ses éléments';
$LANG['wiki_alert_removing_cat'] = 'Etes-vous sûr de vouloir supprimer cette catégorie (définitif)';
$LANG['wiki_confirm_remove_article'] = 'Etes-vous sur de vouloir supprimer cet article?';
$LANG['wiki_not_a_cat'] = 'Vous n\'avez pas sélectionné de catégorie valide!';

//RSS
$LANG['wiki_rss'] = 'Flux RSS';
$LANG['wiki_rss_cat'] = 'Derniers articles de la catégorie %s';
$LANG['wiki_rss_last_articles'] = '%s: derniers articles';

//Favoris
$LANG['wiki_favorites'] = 'Favoris';
$LANG['wiki_unwatch_this_topic'] = 'Ne plus suivre ce sujet';
$LANG['wiki_unwatch'] = 'Ne plus suivre';
$LANG['wiki_watch'] = 'Suivre ce sujet';
$LANG['wiki_followed_articles'] = 'Favoris';
$LANG['wiki_already_favorite'] = 'Le sujet que vous désirez mettre en favoris est déjà en favoris';
$LANG['wiki_article_is_not_a_favorite'] = 'L\'article que vous souhaitez supprimer de vos favoris ne figure pas parmi vos favoris';
$LANG['wiki_no_favorite'] = 'Aucun article en favoris';
$LANG['wiki_confirm_unwatch_this_topic'] = 'Etes-vous certain de vouloir supprimer cet article de vos favoris?';

//Administration
$LANG['wiki_groups_config'] = 'Configuration des groupes';
$LANG['explain_wiki_groups'] = 'Vous pouvez paramétrer ici tout ce qui concerne les autorisations. Vous pouvez attribuer des autorisations à un niveau mais aussi des autorisations spéciales à un groupe';
$LANG['wiki_auth_create_article'] = 'Créer un article';
$LANG['wiki_auth_create_cat'] = 'Créer une catégorie';
$LANG['wiki_auth_restore_archive'] = 'Restaurer une archive';
$LANG['wiki_auth_delete_archive'] = 'Supprimer une archive';
$LANG['wiki_auth_edit'] = 'Editer un article';
$LANG['wiki_auth_delete'] = 'Supprimer un article';
$LANG['wiki_auth_rename'] = 'Renommer un article';
$LANG['wiki_auth_redirect'] = 'Gérer les redirections vers un article';
$LANG['wiki_auth_move'] = 'Déplacer un article';
$LANG['wiki_auth_status'] = 'Modifier le statut d\'un article';
$LANG['wiki_auth_com'] = 'Commenter un article';
$LANG['wiki_auth_restriction'] = 'Modifier le niveau de restrictions d\'un article';
$LANG['wiki_auth_restriction_explain'] = 'Il est conseillé de le laisser aux modérateurs uniquement';
$LANG['wiki_config'] = 'Configuration du wiki';
$LANG['wiki_groups_config'] = 'Gestion des autorisations dans le wiki';
$LANG['wiki_management'] = 'Gestion du wiki';
$LANG['wiki_config_whole'] = 'Configuration générale';
$LANG['wiki_index'] = 'Accueil du wiki';
$LANG['wiki_count_hits'] = 'Compter le nombre de fois que sont vus les articles';
$LANG['wiki_name'] = 'Nom du wiki';
$LANG['wiki_display_cats'] = 'Afficher la liste des catégories principales sur l\'accueil';
$LANG['wiki_no_display'] = 'Ne pas afficher';
$LANG['wiki_display'] = 'Afficher';
$LANG['wiki_last_articles'] = 'Nombre des derniers articles à afficher sur l\'accueil';
$LANG['wiki_last_articles_explain'] = '0 pour désactiver';
$LANG['wiki_desc'] = 'Texte de l\'accueil';

//explorateur du wiki
$LANG['wiki_explorer'] = 'Explorateur du wiki';
$LANG['wiki_root'] = 'Racine du wiki';
$LANG['wiki_root_contents'] = 'contenu de la racine';
$LANG['wiki_cats_tree'] = 'Arborescence du wiki';
$LANG['wiki_explorer_short'] = 'Explorateur';

?>