<?php
/*##################################################
 *                           admin_poll_config.php
 *                            -------------------
 *   begin                : June 21, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
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
load_module_lang('poll'); //Chargement de la langue du module.
define('TITLE', $LANG['configuration']);
require_once('../admin/admin_header.php');
$poll_config = PollConfig::load();

$request = AppContext::get_request();

$valid = $request->get_postvalue('valid', false);

if ($valid)
{
	$displayed_in_mini_module_list = explode(',', retrieve(POST, 'displayed_in_mini_module_list', ''));
	
	$poll_config->set_authorizations(Authorizations::build_auth_array_from_form(PollAuthorizationsService::READ_AUTHORIZATIONS, PollAuthorizationsService::WRITE_AUTHORIZATIONS));
	$poll_config->set_displayed_in_mini_module_list($displayed_in_mini_module_list);
	$poll_config->set_cookie_name(retrieve(POST, 'cookie_name', 'poll', TSTRING_UNCHANGE));
	$poll_config->set_cookie_lenght(retrieve(POST, 'cookie_lenght', 30));
	
	if (retrieve(POST, 'display_results_before_polls_end', false))
		$poll_config->display_results_before_polls_end();
	else
		$poll_config->hide_results_before_polls_end();
	
	PollConfig::save();
	
	###### Régénération du cache des sondages #######
	PollMiniMenuCache::invalidate();
	
	AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
}
else
{
	$tpl = new FileTemplate('poll/admin_poll_config.tpl');
	
	$config_authorizations = $poll_config->get_authorizations();
	
	$i = 0;
	//Liste des sondages
	$poll_list = '';
	$result = PersistenceContext::get_querier()->select("SELECT id, question 
	FROM " . PREFIX . "poll
	WHERE archive = 0 AND visible = 1
	ORDER BY timestamp");
	while ($row = $result->fetch())
	{
		$selected = in_array($row['id'], $poll_config->get_displayed_in_mini_module_list()) ? 'selected="selected"' : '';
		$poll_list .= '<option value="' . $row['id'] . '" ' . $selected . ' id="displayed_in_mini_module_list' . $i++ . '">' . stripslashes($row['question']) . '</option>';
	}
	$result->dispose(); 
	
	$tpl->put_all(array(
		'COOKIE_NAME' => $poll_config->get_cookie_name(),
		'COOKIE_LENGHT' => $poll_config->get_cookie_lenght(),
		'C_DISPLAY_RESULTS_BEFORE_POLLS_END' => $poll_config->are_results_displayed_before_polls_end(),
		'POLL_LIST' => $poll_list,
		'NBR_POLL' => $i,
		'READ_AUTHORIZATION' => Authorizations::generate_select(PollAuthorizationsService::READ_AUTHORIZATIONS, $poll_config->get_authorizations()),
		'WRITE_AUTHORIZATION' => Authorizations::generate_select(PollAuthorizationsService::WRITE_AUTHORIZATIONS, $poll_config->get_authorizations()),
		'L_POLL_MANAGEMENT' => $LANG['poll_management'],
		'L_POLL_ADD' => $LANG['poll_add'],
		'L_POLL_CONFIG' => $LANG['poll_config'],
		'L_POLL_CONFIG_MINI' => $LANG['poll_config_mini'],
		'L_POLL_CONFIG_ADVANCED' => $LANG['poll_config_advanced'],
		'L_DISPLAYED_IN_MINI_MODULE_LIST' => $LANG['displayed_in_mini_module_list'],
		'L_DISPLAYED_IN_MINI_MODULE_LIST_EXPLAIN' => $LANG['displayed_in_mini_module_list_explain'],
		'L_AUTHORIZATIONS' => $LANG['admin.authorizations'],
		'L_READ_AUTHORIZATION' => $LANG['admin.authorizations.read'],
		'L_WRITE_AUTHORIZATION' => $LANG['admin.authorizations.write'],
		'L_COOKIE_NAME' => $LANG['cookie_name'],
		'L_COOKIE_LENGHT' => $LANG['cookie_lenght'],
		'L_DISPLAY_RESULTS_BEFORE_POLLS_END' => $LANG['display_results_before_polls_end'],
		'L_SELECT_ALL' => $LANG['select_all'],
		'L_SELECT_NONE' => $LANG['select_none'],
		'L_REQUIRE' => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
		'L_DAYS' => LangLoader::get_message('days', 'date-common'),
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']
	));
	 
	$tpl->display();
}

require_once('../admin/admin_footer.php');

?>