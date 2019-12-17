<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 12 24
 * @since       PHPBoost 1.6 - 2007 10 14
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

####################################################
#                     English                      #
####################################################

//Généralités
$LANG['wiki'] = 'Wiki';
$LANG['wiki_article_hits'] = 'This page has been seen %d times';
$LANG['wiki_history'] = 'History';
$LANG['wiki_history_all'] = 'Wiki history';
$LANG['wiki_history_article'] = 'History of article %s';
$LANG['wiki_history_seo'] = 'All history of article %s';
$LANG['wiki_contribution_tools'] = 'Contribute';
$LANG['wiki_other_tools'] = 'Tools';
$LANG['wiki_author'] = 'Author';
$LANG['wiki_empty_index'] = 'The wiki is empty. If you have an administrator account you can create pages and edit the wiki index into the administration panel.';
$LANG['wiki_previewing'] = 'Previewing';
$LANG['wiki_table_of_contents'] = 'Table of contents';
$LANG['wiki_read_feed'] = 'Read the article';

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
$LANG['wiki_warning_updated_article'] = 'This article has been updated, you are now consulting an old release of this article!';
$LANG['wiki_article_cat'] = 'Category of the article';
$LANG['wiki_current_cat'] = 'Current category';
$LANG['wiki_contribuate'] = 'Contribute to the wiki';
$LANG['wiki_edit_article'] = 'Edition of the article <em>%s</em>';
$LANG['wiki_edit_cat'] = 'Edition of the category <em>%s</em>';
$LANG['wiki_move'] = 'Move';
$LANG['wiki_rename'] = 'Rename';
$LANG['wiki_no_cat'] = 'No existing category';
$LANG['wiki_no_sub_cat'] = 'No existing subcategory';
$LANG['wiki_no_article'] = 'No existing article';
$LANG['wiki_no_sub_article'] = 'No existing subarticle';
$LANG['wiki_no_selected_cat'] = 'No category selected';
$LANG['wiki_do_not_select_any_cat'] = 'Root';
$LANG['wiki_please_enter_a_link_name'] = 'Please enter a link name';
$LANG['wiki_insert_a_link'] = 'Insert a link into the article';
$LANG['wiki_insert_link'] = 'Insert a link';
$LANG['wiki_title_link'] = 'Article title';
$LANG['wiki_no_js_insert_link'] = 'If you want to insert a link to an article you can use the link tag : [link=a]b[/link] where a is the title of the article in which you want to create a link (enough special characters) and b represents the name of the link.';
$LANG['wiki_explain_paragraph'] = 'Insert a paragraph of level %d';
$LANG['wiki_help_tags'] = 'Know more about wiki specific tags';
$LANG['wiki_help_url'] = 'https://www.phpboost.com/wiki/';
$LANG['wiki_paragraph_name'] = 'Please enter a paragraph name';
$LANG['wiki_paragraph_name_example'] = 'Paragraph title';

//Restrictions d'accès
$LANG['wiki_member_restriction'] = 'This article is protected, only members can edit it.';
$LANG['wiki_modo_restriction'] = 'This article is protected, only moderators can edit it.';
$LANG['wiki_admin_restriction'] = 'This article is protected, only administrators can edit it.';
$LANG['wiki_edition_restriction'] = 'Edition permissions';
$LANG['wiki_no_restriction'] = 'No restrictions';
$LANG['wiki_auth_management'] = 'Permissions management';
$LANG['wiki_auth_management_article'] = 'Permissions management of the article <em>%s</em>';
$LANG['explain_select_multiple'] = 'Press Ctrl then click into the list to choose some options.';
$LANG['select_all'] = 'Select all';
$LANG['select_none'] = 'Unselect all';
$LANG['ranks'] = 'Ranks';
$LANG['groups'] = 'Groups';
$LANG['wiki_explain_restore_default_auth'] = 'Don\'t take into account any particular restriction to this article; permissions of this article will be global permissions.';
$LANG['wiki_restore_default_auth'] = 'Default permissions';

//Catégories
$LANG['wiki_last_articles_list'] = 'Last updated articles :';
$LANG['wiki_cats_list'] = 'List of main categories';
$LANG['wiki_articles_of_this_cat'] = 'Articles of this category';
$LANG['wiki_subcats'] = 'Categories contained by this category :';
$LANG['wiki_subarticles'] = 'Articles contained by this category :';

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
$LANG['wiki_status_explain'] = 'Here you can select the status of this article. Several different status permit you to order your articles and to show a particular point for each article.<br />You can assign as well defined status to you articles or personalized one. To use a defined status let the personalized field empty.';
$LANG['wiki_current_status'] = 'Current status';
$LANG['wiki_article_init'] = 'Initialization';
$LANG['wiki_change_reason_label'] = 'Reason for the change (optional 100 car max)';
$LANG['wiki_change_reason'] = 'Reason for the change';

$LANG['wiki_status_list'] = array(
	array('Quality article', '<span class="message-helper bgc notice">This article is very good.</span>'),
	array('Unachieved article', '<span class="message-helper bgc question">This article lacks sources. <br />Your knowlegde is welcome to complete it.</span>'),
	array('Article in transformation', '<span class="message-helper bgc notice">This article is not complete, you can use your knowledge to complete it.</span>'),
	array('Article to remake', '<span class="message-helper bgc warning">This article must be writen again. Its content is not reliable.</span>'),
	array('Article discussion', '<span class="message-helper bgc error">This article was a subject of discussion and its content seems to be incorrect. You can eventually read discussions and maybe use your knowledge to complete it.</span>')
);

//Déplacement de l'article
$LANG['wiki_moving_article'] = 'Moving of an article';
$LANG['wiki_moving_this_article'] = 'Moving of the article: %s';
$LANG['wiki_change_cat'] = 'Change category';
$LANG['wiki_cat_contains_cat'] = 'You have attempted to move this category in its sub-category or in itself, that\'s impossible!';

//Renommer l'article
$LANG['wiki_renaming_article'] = 'Rename an article';
$LANG['wiki_renaming_this_article'] = 'Rename the article: %s';
$LANG['wiki_new_article_title'] = 'New title of the article';
$LANG['wiki_explain_renaming'] = 'You will rename this article. Be careful, all links to this article will be broken. But you can ask to put a redirection for this article, so the links will not be broken.';
$LANG['wiki_create_redirection_after_renaming'] = 'Create an automatic redirection from the old article to the new one';
$LANG['wiki_title_already_exists'] = 'The title you want to choose already exists. Please choose another one';

//Redirection
$LANG['wiki_redirecting_from'] = 'Redirect from %s';
$LANG['wiki_remove_redirection'] = 'Delete the redirection';
$LANG['wiki_redirections'] = 'Redirections';
$LANG['wiki_redirections_management'] = 'Redirections management';
$LANG['wiki_edit_redirection'] = 'Edit an redirection';
$LANG['wiki_redirections_to_this_article'] = 'Redirections to the article: <em>%s</em>';
$LANG['wiki_redirection_name'] = 'Title of the redirection';
$LANG['wiki_redirection_delete'] = 'Delete the redirection';
$LANG['wiki_alert_delete_redirection'] = 'Are you sure you want to delete this redirection?';
$LANG['wiki_no_redirection'] = 'They have no redirection to this page';
$LANG['wiki_create_redirection'] = 'Create a redirection to this page';
$LANG['wiki_create_redirection_to_this'] = 'Create a redirection to the article: <em>%s</em>';

//Recherche
$LANG['wiki_empty_search'] = 'No article was found.';
$LANG['wiki_search_where'] = 'Where?';
$LANG['wiki_search_where_title'] = 'Title';
$LANG['wiki_search_where_contents'] = 'Content';
$LANG['wiki_search_where_all'] = 'Title &amp; content';

//Discussion
$LANG['wiki_article_com'] = 'Discussion for the article %s';
$LANG['wiki_article_com_article'] = 'Discussion';
$LANG['wiki_article_com_seo'] = 'All discussions for article %s';

//Suppression
$LANG['wiki_confirm_delete_archive'] = 'Are you sure you want to delete this version of the article?';
$LANG['wiki_remove_cat'] = 'Delete a category';
$LANG['wiki_remove_this_cat'] = 'Delete the category: <em>%s</em>';
$LANG['wiki_explain_remove_cat'] = 'You want to delete this category. You can delete all its content, or move its content somewhere else.The article will be delete anyway.';
$LANG['wiki_remove_all_contents'] = 'Delete all its content (irreversible actions)';
$LANG['wiki_move_all_contents'] = 'Move all the content in this folder:';
$LANG['wiki_future_cat'] = 'Category in which you want to move these element:';
$LANG['wiki_alert_removing_cat'] = 'Are you sure you want to delete this category? (definitive)';
$LANG['wiki_confirm_remove_article'] = 'Are you sure you want to delete this article?';
$LANG['wiki_not_a_cat'] = 'You have not selected a valid category!';

//RSS
$LANG['wiki_rss'] = 'RSS Flow';
$LANG['wiki_rss_cat'] = 'Last articles of the category: %s';
$LANG['wiki_rss_last_articles'] = '%s: last articles';

//Favoris
$LANG['wiki_favorites'] = 'Favorites';
$LANG['wiki_favorites_seo'] = 'List of favorite wiki articles.';
$LANG['wiki_unwatch_this_topic'] = 'Don\'t track this topic anymore';
$LANG['wiki_unwatch'] = 'Don\'t track anymore';
$LANG['wiki_watch'] = 'Track this topic';
$LANG['wiki_followed_articles'] = 'Favorites';
$LANG['wiki_already_favorite'] = 'The topic you want to put in your favorites is already in it';
$LANG['wiki_article_is_not_a_favorite'] = 'The topic you want to delete of your favorites is not in your favorites';
$LANG['wiki_no_favorite'] = 'No topic in favorites';
$LANG['wiki_confirm_unwatch_this_topic'] = 'Are you sure you want to delete this article from your favorites?';

//Administration
$LANG['authorizations'] = 'Authorizations';
$LANG['wiki_groups_config'] = 'Groups management';
$LANG['explain_wiki_groups'] = 'You can configure here everything concerning authorizations. You can attribute authorizations to a level and specials persmissions to a group.';
$LANG['wiki_auth_read'] = 'Read articles';
$LANG['wiki_auth_create_article'] = 'Create an article';
$LANG['wiki_auth_create_cat'] = 'Create a category';
$LANG['wiki_auth_restore_archive'] = 'Restore an archive';
$LANG['wiki_auth_delete_archive'] = 'Delete an archive';
$LANG['wiki_auth_edit'] = 'Edit an article';
$LANG['wiki_auth_delete'] = 'Delete an article';
$LANG['wiki_auth_rename'] = 'Rename an article';
$LANG['wiki_auth_redirect'] = 'Manage redirection to an article';
$LANG['wiki_auth_move'] = 'Move an article';
$LANG['wiki_auth_status'] = 'Edit an article status';
$LANG['wiki_auth_com'] = 'Comment an article';
$LANG['wiki_auth_restriction'] = 'Edit restrictions level of an article';
$LANG['wiki_auth_restriction_explain'] = 'It is advised to keep it for moderators only';
$LANG['wiki_config'] = 'Wiki configuration';
$LANG['wiki_groups_config'] = 'Permissions management in the wiki';
$LANG['wiki_management'] = 'Wiki management';
$LANG['wiki_config_whole'] = 'General configuration';
$LANG['wiki_index'] = 'Wiki home';
$LANG['wiki_count_hits'] = 'Count the number of time that articles has been seen';
$LANG['wiki_name'] = 'Wiki name';
$LANG['wiki_display_cats'] = 'Show principal categories list in home';
$LANG['wiki_no_display'] = 'Don\'t show';
$LANG['wiki_display'] = 'Show';
$LANG['wiki_last_articles'] = 'Last articles number to show in home';
$LANG['wiki_last_articles_explain'] = '0 to deactivate';
$LANG['wiki_desc'] = 'Home text';

//explorateur du wiki
$LANG['wiki_explorer'] = 'Wiki explorer';
$LANG['wiki_explorer_seo'] = 'Explorer to navigate the tree of different pages of the wiki.';
$LANG['wiki_root'] = 'Wiki root';
$LANG['wiki_contents'] = 'Content';
$LANG['wiki_cats_tree'] = 'Category tree';
$LANG['wiki_explorer_short'] = 'Explorer';

?>
