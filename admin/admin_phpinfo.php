<?php
/*##################################################
 *                               admin_phpinfo.php
 *                            -------------------
 *   begin                : November 06, 2007
 *   copyright            : (C) 2007 Viarre Régis
 *   email                : crowkait@gmail.com
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

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

ob_start();

$template = new FileTemplate('admin/admin_phpinfo.tpl');

phpinfo();
$phpinfo = ob_get_contents();
$phpinfo = preg_replace('`^.*<body>`is', '', $phpinfo);
$phpinfo = str_replace(array('class="e"', 'class="v"', 'class="h"', '<i>', '</i>', '<hr />', '<img border="0"', '<table border="0" cellpadding="3" width="600">', '</body></html>'), 
array('class="row1"', 'class="row2"', 'class="row3"', '<em class="em">', '</em>', '', '<img style="float:right;"', '<table class="module_table">', ''), $phpinfo);
ob_end_clean();

ob_start();

$template->put_all(array(
    'PHPINFO' => $phpinfo,
	'L_SYSTEM_REPORT' => $LANG['system_report'],
	'L_SERVER' => $LANG['server'],
    'L_PHPINFO' => $LANG['phpinfo']
));

$template->display();

require_once('../admin/admin_footer.php');

?>