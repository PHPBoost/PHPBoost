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
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

include_once('pages_begin.php');
include_once('pages_functions.php');

$request = AppContext::get_request();

$update = $request->get_postvalue('update', false);

if ($update)  //Mise à jour
{
	$pages_config->set_authorizations(Authorizations::build_auth_array_from_form(READ_PAGE, EDIT_PAGE, READ_COM));
	$pages_config->set_count_hits_activated(retrieve(POST, 'count_hits', false));
	$pages_config->set_comments_activated(retrieve(POST, 'comments_activated', false));

	PagesConfig::save();
	
	###### Régénération du cache #######
	PagesCategoriesCache::invalidate();
	
	AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
}

$tpl = new FileTemplate('pages/admin_pages.tpl');

//Configuration des authorisations
$config_authorizations = $pages_config->get_authorizations();

$tpl->put_all(array(
	'HITS_CHECKED' => ($pages_config->get_count_hits_activated() == true) ? 'checked="checked"' : '',
	'COM_CHECKED' => ($pages_config->get_comments_activated() == true) ? 'checked="checked"' : '',
	'SELECT_READ_PAGE' => Authorizations::generate_select(READ_PAGE, $config_authorizations),
	'SELECT_EDIT_PAGE' => Authorizations::generate_select(EDIT_PAGE, $config_authorizations),
	'SELECT_READ_COM' => Authorizations::generate_select(READ_COM, $config_authorizations),
	'L_READ_COM' => $LANG['pages_auth_read_com'],
	'L_EDIT_PAGE' => $LANG['pages_auth_edit'],
	'L_READ_PAGE' => $LANG['pages_auth_read'],
	'L_SELECT_NONE' => $LANG['select_none'],
	'L_SELECT_ALL' => $LANG['select_all'],
	'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
	'L_AUTH' => $LANG['pages_auth'],
	'L_COUNT_HITS_EXPLAIN' => $LANG['pages_count_hits_explain'],
	'L_COUNT_HITS' => $LANG['pages_count_hits_activated'],
	'L_PAGES' => $LANG['pages'],
	'L_UPDATE' => $LANG['update'],
	'L_RESET' => $LANG['reset'],
	'L_COMMENTS_ACTIVATED' => $LANG['pages_comments_activated'],
	'L_PAGES_CONGIG' => $LANG['pages_config'],
	'L_PAGES_MANAGEMENT' => $LANG['pages_management'],
));

$tpl->display();

require_once('../admin/admin_footer.php');

?>