<?php
/*##################################################
 *                                 test.php
 *                            -------------------
 *   begin                : November 29, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/begin.php';

load_module_lang('test');

define('TITLE', $LANG['test_title']);

require_once PATH_TO_ROOT . '/kernel/header.php';

include_once(PATH_TO_ROOT . '/test/util/phpboost_unit_tests.inc.php');

$params = TextHelper::htmlentities(!empty($_REQUEST['params']) ? $_REQUEST['params'] : '');

$tpl = new FileTemplate('test/test.tpl');

$tpl->put_all(array(
	'PARAMS' => $params
));

foreach (list_tu('./kernel') as $tu)
{
    $tpl->assign_block_vars('tests', array(
        'NAME' => str_replace('Test.php', '', $tu),
    	'PATH' => $tu
    ));
}

foreach (list_test_suite('./kernel') as $ts)
{
    $tpl->assign_block_vars('tests_suite', array(
        'NAME' => $ts
    ));
}

$tpl->display();

require_once PATH_TO_ROOT . '/kernel/footer.php';
?>
