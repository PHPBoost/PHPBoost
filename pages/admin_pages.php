<?php
/*##################################################
 *                               admin_pages.php
 *                            -------------------
 *   begin                : August 09, 2007
 *   copyright            : (C) 2007 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../admin/admin_begin.php');
load_module_lang('pages');
define('TITLE', $LANG['administration'] . ' : ' . $LANG['pages']);
require_once('../admin/admin_header.php');

include_once('pages_begin.php');
include_once('pages_functions.php');

if (!empty($_POST['update']))  //Mise à jour
{
	$count_hits = !empty($_POST['count_hits']) ? 1 : 0;
	$activ_com = !empty($_POST['activ_com']) ? 1 : 0;
	
	//Génération du tableau des droits.
	$array_auth_all = Authorizations::build_auth_array_from_form(READ_PAGE, EDIT_PAGE, READ_COM);
	
	$_PAGES_CONFIG['auth'] = serialize($array_auth_all);
	$_PAGES_CONFIG['count_hits'] = $count_hits;
	$_PAGES_CONFIG['activ_com'] = $activ_com;

	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($_PAGES_CONFIG)) . "' WHERE name = 'pages'", __LINE__, __FILE__);
	//Régénération du cache
	$Cache->Generate_module_file('pages');
	
	AppContext::get_response()->redirect(HOST . SCRIPT);
}

$Template->set_filenames(array(
	'pages_config'=> 'pages/admin_pages.tpl'
));

$array_auth = isset($_PAGES_CONFIG['auth']) ? $_PAGES_CONFIG['auth'] : array();

$Template->assign_vars(array(
	'HITS_CHECKED' => $_PAGES_CONFIG['count_hits'] == 1 ? 'checked="checked"' : '',
	'COM_CHECKED' => $_PAGES_CONFIG['activ_com'] == 1 ? 'checked="checked"' : '',
	'SELECT_READ_PAGE' => Authorizations::generate_select(READ_PAGE, $array_auth),
	'SELECT_EDIT_PAGE' => Authorizations::generate_select(EDIT_PAGE, $array_auth),
	'SELECT_READ_COM' => Authorizations::generate_select(READ_COM, $array_auth),
	'L_READ_COM' => $LANG['pages_auth_read_com'],
	'L_EDIT_PAGE' => $LANG['pages_auth_edit'],
	'L_READ_PAGE' => $LANG['pages_auth_read'],
	'L_SELECT_NONE' => $LANG['select_none'],
	'L_SELECT_ALL' => $LANG['select_all'],
	'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
	'L_AUTH' => $LANG['pages_auth'],
	'L_COUNT_HITS_EXPLAIN' => $LANG['pages_count_hits_explain'],
	'L_COUNT_HITS' => $LANG['pages_count_hits'],
	'L_PAGES' => $LANG['pages'],
	'L_UPDATE' => $LANG['update'],
	'L_RESET' => $LANG['reset'],
	'L_ACTIV_COM' => $LANG['pages_activ_com'],
	'L_PAGES_CONGIG' => $LANG['pages_config'],
	'L_PAGES_MANAGEMENT' => $LANG['pages_management'],
));
	
	
$Template->pparse('pages_config');

require_once('../admin/admin_footer.php');

?>