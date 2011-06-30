<?php
/*##################################################
 *                              media_contribution.php
 *                            -------------------
 *   begin               	: October 20, 2008
 *   copyright        		: (C) 2007 Geoffrey ROGUELON
 *   email               	: liaght@gmail.com
 *
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
require_once('media_begin.php');

define('TITLE', $MEDIA_LANG['contribution_confirmation']);

$Bread_crumb->add($MEDIA_LANG['media'], url('media.php'));
$Bread_crumb->add($MEDIA_LANG['contribution_confirmation'], url('contribution.php'));

require_once('../kernel/header.php');

$media_template = new FileTemplate('media/contribution.tpl');

$media_template->put_all(array(
	'L_CONTRIBUTION_CONFIRMATION' => $MEDIA_LANG['contribution_confirmation'],
	'L_CONTRIBUTION_SUCCESS' => $MEDIA_LANG['contribution_success'],
	'L_CONTRIBUTION_CONFIRMATION_EXPLAIN' => $MEDIA_LANG['contribution_confirmation_explain']
));

$media_template->display();

require_once('../kernel/footer.php');

?>