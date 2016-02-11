<?php
/*##################################################
 *                      AdminBugtrackerDeleteDefaultParameterController.class.php
 *                            -------------------
 *   begin                : October 21, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class AdminBugtrackerDeleteDefaultParameterController extends AdminModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();
		
		$config = BugtrackerConfig::load();
		
		$parameter = $request->get_string('parameter', '');
		
		if (in_array($parameter, array('type', 'category', 'severity', 'priority', 'version')))
		{
			switch ($parameter)
			{
				case 'type':
					$config->set_default_type(0);
					break;
				case 'category':
					$config->set_default_category(0);
					break;
				case 'severity':
					$config->set_default_severity(0);
					break;
				case 'priority':
					$config->set_default_priority(0);
					break;
				case 'version':
					$config->set_default_version(0);
					break;
			}
			
			BugtrackerConfig::save();
			AppContext::get_response()->redirect(BugtrackerUrlBuilder::configuration());
		}
		else
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), LangLoader::get_message('error.e_unexist_parameter', 'common', 'bugtracker'));
			$controller->set_response_classname(UserErrorController::ADMIN_RESPONSE);
			DispatchManager::redirect($controller);
		}
	}
}
?>
