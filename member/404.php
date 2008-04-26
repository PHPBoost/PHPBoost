<?php
/*##################################################
 *                                error.php
 *                            -------------------
 *   begin                : August 08 2005
 *   copyright          : (C) 2005 CrowkaiT
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

require_once('../kernel/begin.php'); 
define('TITLE', $LANG['title_error'] . ' 404');
require_once('../kernel/header.php'); 

$Template->Set_filenames(array(
	'error'=> 'error.tpl'
));

$Template->Assign_vars(array(
	'C_ERRORH' => true,
	'ERRORH_IMG' => 'important',
	'ERRORH_CLASS' => 'error_warning',
	'L_ERROR' => $LANG['title_error'] . ' 404',
	'L_ERRORH' => '<strong>' . $LANG['title_error'] . ' 404</strong>' . '<br /><br />' . $LANG['e_unexist_page'],
	'U_BACK' => !empty($_SERVER['HTTP_REFERER']) ? '<a href="' . transid($_SERVER['HTTP_REFERER']) .'">' . $LANG['back'] . '</a>' : '<a href="javascript:history.back(1)">' . $LANG['back'] . '</a>',
));

$Template->Pparse('error');

require_once('../kernel/footer.php'); 

?>