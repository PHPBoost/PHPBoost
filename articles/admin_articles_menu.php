<?php
/*##################################################
 *                               admin_articles_menu.php
 *                            -------------------
 *   begin               	: August 28, 2009
 *   copyright           	: (C) 2009 Nicolas MAUREL
 *   email               	: crunchfamily@free.fr
 *
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

if (defined('PHPBOOST') !== true) exit;

$tpl_menu = new Template('articles/admin_articles_menu.tpl');

$tpl_menu->Assign_vars(array(
	'L_ARTICLES_MANAGEMENT' => $ARTICLES_LANG['articles_management'],
	'L_ADD_ARTICLES' => $ARTICLES_LANG['add_articles'],
	'L_CONFIG_ARTICLES' => $ARTICLES_LANG['configuration_articles'],
	'L_CAT_ARTICLES' => $ARTICLES_LANG['category_articles'],
	'L_ADD_CAT' => $ARTICLES_LANG['add_category']
));

$admin_menu = $tpl_menu->parse(TEMPLATE_STRING_MODE);

?>