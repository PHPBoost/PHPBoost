<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 02
 * @since       PHPBoost 1.6 - 2007 08 18
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

####################################################
#                     English                      #
####################################################

//Généralités
$LANG['pages'] = 'Pages';

$LANG['page_hits'] = 'This page has been seen %d times';

//Administration
$LANG['pages_count_hits_activated'] = 'Hits count';
$LANG['pages_count_hits_explain'] = 'Can be changed for each page.';
$LANG['pages_auth_read'] = 'Read page';
$LANG['pages_auth_edit'] = 'Edit page';
$LANG['pages_auth_read_com'] = 'Read and write comments';
$LANG['pages_auth'] = 'Permissions';
$LANG['select_all'] = 'Select all';
$LANG['select_none'] = 'Unselect all';
$LANG['ranks'] = 'Ranks';
$LANG['groups'] = 'Groups';
$LANG['pages_config'] = 'Configuration';
$LANG['pages_management'] = 'Pages management';
$LANG['pages_manage'] = 'Manage pages';

//Création / édition d'une page
$LANG['pages_edition'] = 'Editing a page';
$LANG['pages_creation'] = 'Creating a page';
$LANG['pages_edit_page'] = 'Edition of the page <em>%s</em>';
$LANG['page_title'] = 'Page title';
$LANG['page_contents'] = 'Page contents';
$LANG['pages_edit'] = 'Edit this page';
$LANG['pages_delete'] = 'Delete this page';
$LANG['pages_create'] = 'Create a page';
$LANG['pages_comments_activated'] = 'Activate comments';
$LANG['pages_display_print_link'] = 'Display print link';
$LANG['pages_own_auth'] = 'Apply individual permissions to this page';
$LANG['pages_is_cat'] = 'This page is a category';
$LANG['pages_parent_cat'] = 'Parent category';
$LANG['pages_page_path'] = 'Path';
$LANG['pages_properties'] = 'Properties';
$LANG['pages_no_selected_cat'] = 'No selected category';
$LANG['explain_select_multiple'] = 'Press Ctrl then click in the list to select several options';
$LANG['pages_previewing'] = 'Preview';
$LANG['pages_contents_part'] = 'Page contents';
$LANG['pages_delete_success'] = 'The page has been deleted successfully.';
$LANG['pages_delete_failure'] = 'The page hasn\'t been deleted. An error occured.';
$LANG['pages_confirm_delete'] = 'Are you sure you want to delete this page ?';

//Divers
$LANG['pages_links_list'] = 'Tools';
$LANG['pages_com'] = 'Comments';
$LANG['pages_explorer'] = 'Explorer';
$LANG['pages_explorer_seo'] = 'Explorer to navigate the tree of different pages of the site.';
$LANG['pages_root'] = 'Root';
$LANG['pages_cats_tree'] = 'Categories tree';
$LANG['pages_display_coms'] = 'Comments (%d)';
$LANG['pages_post_com'] = 'Post a comment';
$LANG['pages_page_com'] = 'Comments of the page %s';
$LANG['pages_page_com_seo'] = 'All comments of the page %s';

//Accueil
$LANG['pages_explain'] = 'You are in the "pages" module control panel. Here you can manage your whole pages.<br /><br />
<p>You will edit your page with the chosen editor in your personal profile. You can insert some HTML code using the BBCode tag <span style="font-family:courier new;">[html]HTML code[/html]</span></p>
<p>You cannot insert some PHP code in you pages for security reason.</p>
<p>To create links between different pages of this module, you have to use the <em>link</em> tag which doesn\'t appear in the BBCode toolbar, but the syntax is for instance : <span style="font-family:courier new;">[link=title-of-the-page]Link up to page[/link]</span>.</p>
<p>This tag only exist on pages and wiki modules.</p>
<div class="message-helper bgc warning">For security reason, insert PHP code is forbidden in "pages" module.</div>';
$LANG['pages_redirections'] = 'Redirections';
$LANG['pages_num_pages'] = '%d existing page(s)';
$LANG['pages_num_coms'] = '%d comments on the whole pages, which corresponds to %1.1f commentary by page';
$LANG['pages_stats'] = 'Statistics';
$LANG['pages_tools'] = 'Tools';

//Redirections et renommer
$LANG['pages_rename'] = 'Rename';
$LANG['pages_redirection_management'] = 'Redirection management';
$LANG['pages_redirection_manage'] = 'Manage redirection';
$LANG['pages_rename_page'] = 'Rename the page <em>%s</em>';
$LANG['pages_new_title'] = 'New title of this page';
$LANG['pages_create_redirection'] = 'Create a redirection from the previous title to the current title?';
$LANG['pages_explain_rename'] = 'You are just going to rename the page. You have to know that every link pointing up to that page will be broken. That\'s why you have the possibility of creating a redirection from the previous title to the new one, which won\'t break those links.';
$LANG['pages_confirm_delete_redirection'] = addslashes('Are you sure you want to delete this redirection ?');
$LANG['pages_delete_redirection'] = 'Delete this redirection';
$LANG['pages_redirected_from'] = 'Redirected from <em>%s</em>';
$LANG['pages_redirection_title'] = 'Redirection title';
$LANG['pages_redirection_target'] = 'Redirection target';
$LANG['pages_redirection_actions'] = 'Actions';
$LANG['pages_manage_redirection'] = 'Manage every redirection pointing to this page';
$LANG['pages_no_redirection'] = 'No existing redirection';
$LANG['pages_create_redirection'] = 'Create a redirection to this article';
$LANG['pages_creation_redirection'] = 'Creating a redirection';
$LANG['pages_creation_redirection_title'] = 'Creating a redirection to %s';
$LANG['pages_redirection_title'] = 'Redirection name';
$LANG['pages_remove_this_cat'] = 'Deleting the category : <em>%s</em>';
$LANG['pages_remove_all_contents'] = 'Delete its whole content';
$LANG['pages_move_all_contents'] = 'Move its whole content into the folowing folder :';
$LANG['pages_future_cat'] = 'Category in which you want to move those elements';
$LANG['pages_change_cat'] = 'Change category';
$LANG['pages_delete_cat'] = 'Deleting a category';
$LANG['pages_confirm_remove_cat'] = 'Are you sure you want to delete this category?';

//Errors
$LANG['page_alert_title'] = 'You have to enter a title';
$LANG['page_alert_contents'] = 'You have to enter a contents for your page';
$LANG['pages_already_exists'] = 'The title you have chosen already exists. You must choose another one, because titles must be uniques.';
$LANG['pages_cat_contains_cat'] = 'The category you have selected to put this category is contained by herself or one of its own, which is not possible. Please select another category.';
$LANG['pages_notice_previewing'] = 'You are previewing the contents you have entered. No edition has been done into the database. You must submit your page if you want it to take effect.';

$LANG['pages_rss_desc'] = 'News pages';

?>
