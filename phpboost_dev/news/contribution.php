<?php
/*##################################################
 *                        contribution.php
 *                            -------------------
 *   begin                : August 23, 2009
 *   copyright            : (C) 2009 Roguelon Geoffrey
 *   email                : liaght@gmail.com
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

require_once('../kernel/begin.php');

// Définition des constantes des autorisations
require_once 'news_begin.php';


if (!$User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_CONTRIBUTE))
	$Errorh->handler('e_auth', E_USER_REDIRECT);

define('TITLE', $NEWS_LANG['contribution_confirmation']);

$Bread_crumb->add($NEWS_LANG['news'], url('news.php'));
$Bread_crumb->add($NEWS_LANG['contribution_confirmation'], url('contribution.php'));

require_once('../kernel/header.php');

//Template
$template = new FileTemplate('news/contribution.tpl');

$template->assign_vars(array(
	'L_CONTRIBUTION_CONFIRMATION' => $NEWS_LANG['contribution_confirmation'],
	'L_CONTRIBUTION_SUCCESS' => $NEWS_LANG['contribution_success'],
	'L_CONTRIBUTION_CONFIRMATION_EXPLAIN' => $NEWS_LANG['contribution_confirmation_explain']
));

$template->display();

require_once('../kernel/footer.php');

?>