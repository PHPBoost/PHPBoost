<?php
/*##################################################
 *                              news_english.php
 *                            -------------------
 *   begin                : June 20, 2005
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
#                                                           English                                                                             #
####################################################

//Admin
$LANG['confirm_del_news'] = 'Delete this piece of news?';
$LANG['add_news'] = 'Add an item of news';
$LANG['configuration_news'] = 'News configuration';
$LANG['category_news'] = 'News category';
$LANG['img_management'] = 'Picture management';
$LANG['preview_image'] = 'Picture preview';
$LANG['preview_image_explain'] = 'Default: on right';
$LANG['img_link'] = 'Picture URL';
$LANG['img_desc'] = 'Picture description';
$LANG['news_management'] = 'News management';
$LANG['edit_news'] = 'Edit news';
$LANG['edito'] = 'Editorial';
$LANG['edito_where'] = 'Visible message in the top of the home page';
$LANG['config_news'] = 'News configuration';
$LANG['nbr_news_p'] = 'Number of news per page';
$LANG['nbr_news_p_explain'] = 'Default 6';
$LANG['nbr_arch_p'] = 'Number of archives';
$LANG['nbr_arch_p_explain'] = 'Default 15';
$LANG['module_management'] = 'Modules management';
$LANG['activ_pagination'] = 'Activate pagination';
$LANG['activ_pagination_explain'] = 'Else, show a link to archives';
$LANG['activ_edito'] = 'Activate editorial';
$LANG['activ_edito_explain'] = 'Visible message in top of the home page';
$LANG['activ_news_block'] = 'Activate news in block';
$LANG['activ_com_n'] = 'Activate news comments';
$LANG['activ_icon_n'] = 'Show news category icon';
$LANG['display_news_author'] = 'Display news author';
$LANG['display_news_date'] = 'Display news date';
$LANG['extended_news'] = 'Extended news';
$LANG['icon_cat'] = 'Category icon';
$LANG['news_date'] = 'News date';
$LANG['news_date_explain'] = '(dd/mm/yy) Leave empty to set today date';
$LANG['nbr_news_column'] = 'Column number to display news';
$LANG['no_img'] = 'No picture';

//Errors
$LANG['e_unexist_news'] = 'This news doesn\'t exist';

//Title
$LANG['title_news'] = 'News';

//Alerts
$LANG['alert_delete_news'] = 'Delete this piece of news??';

//News
$LANG['news'] = 'News';
$LANG['propose_news'] = 'Suggest an item of news';
$LANG['xml_news_desc'] = 'Track the last news on';
$LANG['add_succes_news'] = 'Item of news sent successfully, please wait approval';
$LANG['add_news'] = 'Add a item of news';
$LANG['last_news'] = 'Last news';
$LANG['extend_contents'] = 'Read the continuation...';
$LANG['no_news_available'] = 'No news currently available';
$LANG['archive'] = 'Archives';
$LANG['display_archive'] = 'Display archives';
$LANG['read_feed'] = 'Read';

//Add news.
$MAIL['new_news_website'] = 'New news on your website';
$MAIL['new_news'] = 'A new item of news was added on your website ' . HOST . ', 
it will have to be approved before being visible on the website by everyone. 

News\'s title: %s
Contents: %s...[next]
Posted by: %s

Click in the administration panel of the news, and approve it.
' . HOST . DIR . '/admin/admin_news_gestion.php';
?>
