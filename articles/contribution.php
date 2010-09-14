<?php
/*##################################################
 *                        contribution.php
 *                            -------------------
 *   begin                : September 10, 2009
 *   copyright            : (C) 2009 Maurel Nicolas
 *   email                : crunchfamily@free.fr
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
require_once 'articles_begin.php';

if (!$User->check_auth($CONFIG_ARTICLES['global_auth'], AUTH_ARTICLES_CONTRIBUTE))
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

$Bread_crumb->add($ARTICLES_LANG['contribution_confirmation'], url('contribution.php'));

require_once('../kernel/header.php');

//Template
$tpl = new FileTemplate('articles/contribution.tpl');

$tpl->assign_vars(array(
	'L_CONTRIBUTION_CONFIRMATION' => $ARTICLES_LANG['contribution_confirmation'],
	'L_CONTRIBUTION_SUCCESS' => $ARTICLES_LANG['contribution_success'],
	'L_CONTRIBUTION_CONFIRMATION_EXPLAIN' => $ARTICLES_LANG['contribution_confirmation_explain']
));

$tpl->display();

require_once('../kernel/footer.php');

?>