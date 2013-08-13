<?php
/*##################################################
 *                          AjaxUserAutoCompleteController.class.php
 *                            -------------------
 *   begin                : November 15, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class AjaxUserAutoCompleteController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$tpl = new StringTemplate('<ul> # IF C_RESULTS ## START results # <li><a class="{results.USER_LEVEL_CLASS}" # IF results.C_USER_GROUP_COLOR # style="color:{results.USER_GROUP_COLOR}" # ENDIF #>{results.NAME}</a></li> # END results ## ELSE # <li>{L_NO_RESULT}</li># ENDIF # </ul>');
 
		$result = PersistenceContext::get_querier()->select("SELECT user_id, login, level, user_groups FROM " . DB_TABLE_MEMBER . " WHERE login LIKE '" . $request->get_value('value', '') . "%'",
			array(), SelectQueryResult::FETCH_ASSOC);
 
		$nb_results = 0;
		
		while($row = $result->fetch())
		{
			$user_group_color = User::get_group_color($row['user_groups'], $row['level']);
			
			$tpl->assign_block_vars('results', array(
				'C_USER_GROUP_COLOR' => !empty($user_group_color),
				'NAME' => $row['login'],
				'USER_LEVEL_CLASS' => UserService::get_level_class($row['level']),
				'USER_GROUP_COLOR' => $user_group_color
			));
			
			$nb_results++;
		}
		
		$tpl->put_all(array(
			'C_RESULTS' => $nb_results,
			'L_NO_RESULT' => LangLoader::get_message('no_result', 'main')
		));
 
		return new SiteNodisplayResponse($tpl);
	}
}
?>