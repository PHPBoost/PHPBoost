<?php
/*##################################################
 *                             web_english.php
 *                            -------------------
 *   begin                :  July 28, 2005
 *   last modified		: October 3rd, 2009 - JMNaylor
 *   copyright            : (C) 2006 CrowkaiT
 *   email                : crowkait@phpboost.com
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
#                                                           English                                                                             #
 ####################################################

//Admin
$LANG['web_add'] = 'Add a weblink';
$LANG['web_management'] = 'Weblink management';
$LANG['web_config'] = 'Weblink configuration';
$LANG['edit_link'] = 'Weblink edition';
$LANG['nbr_web_max'] = 'Maximum weblinks displayed';
$LANG['icon_cat'] = 'Category icon';

//Errors
$LANG['e_unexist_link_web'] = 'This link doesn\'t exist';

//Title
$LANG['title_web'] = 'Weblinks';

//Web
$LANG['link'] = 'Link';
$LANG['propose_link'] = 'Suggest a link';
$LANG['none_link'] = 'No links in this category';
$LANG['how_link'] = 'Link(s) in the database!';
$LANG['no_note'] = 'No note';
$LANG['actual_note'] = 'Current rating';
$LANG['vote'] = 'Rate this link';
$LANG['delete_link'] = 'Delete this link?';

//Add web link.
$MAIL['new_link_website'] = 'New weblink on your website';
$MAIL['new_link'] = 'A new weblink was added to your website ' . HOST . ', 
it will have to be approved before being visible on the site by everyone.

Weblink\'s title: %s
Url: %s
Contents: %s...[next]

Click in the administration panel of the weblink, and approve it.
' . HOST . DIR . '/admin/admin_web_gestion.php';
?>
