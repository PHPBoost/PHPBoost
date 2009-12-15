<?php
/***************************************************************************
 *                                fatal.php
 *                            -------------------
 *   begin                : April 12, 2007
 *   copyright            : (C) 2007 CrowkaiT
 *   email                : crowkait@phpboost.com
 *
 *
 *
 ***************************************************************************
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
***************************************************************************/

require_once('../kernel/begin.php');
require_once('../kernel/header.php');

$tpl = new Template('member/csrf-attack.tpl');
$tpl->assign_vars(array(
    'L_ATTACK_EXPLAIN' => $LANG['csrf_attack'],
    'L_PREVIOUS' => $LANG['previous'],
));
$tpl->parse();

require_once('../kernel/footer.php');

?>