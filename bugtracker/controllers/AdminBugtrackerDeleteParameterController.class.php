<?php
/*##################################################
 *                      AdminBugtrackerDeleteParameterController.class.php
 *                            -------------------
 *   begin                : October 22, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class AdminBugtrackerDeleteParameterController extends AdminController
{
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();
		
		$this->check_authorizations();
		
		//Get the parameter to delete
		$parameter = $request->get_string('parameter', '');
		//Get the id of the parameter to delete
		$id = $request->get_int('id', '');
		
		if (in_array($parameter, array('type', 'category', 'version')) && !empty($id))
		{
			$config = BugtrackerConfig::load();
			
			$types = $config->get_types();
			$categories = $config->get_categories();
			$severities = $config->get_severities();
			$priorities = $config->get_priorities();
			$versions = $config->get_versions();
			
			switch ($parameter)
			{
				case 'type':
					if (isset($types[$id]))
					{
						//Delete the type in the list of types
						unset($types[$id]);
						$config->set_types($types);
						if ($config->get_default_type() == $id)
							$config->set_default_type(0);
						
						//Delete the type for the bugs of this type
						BugtrackerService::update_parameter(array('type' => 0), 'WHERE type=:id', array('id' => $id));
						
						//Delete history lines containing this type
						BugtrackerService::delete_history("WHERE updated_field='type' AND (old_value=:id OR new_value=:id)", array('id' => $id));
					}
					else
					{
						//Error : unexist type
						$controller = new UserErrorController(LangLoader::get_message('error', 'errors-common'), LangLoader::get_message('bugs.error.e_unexist_type', 'common', 'bugtracker'));
						$controller->set_response_classname(UserErrorController::ADMIN_RESPONSE);
						DispatchManager::redirect($controller);
					}
					break;
				case 'category':
					if (isset($categories[$id]))
					{
						//Delete the category in the list of categories
						unset($categories[$id]);
						$config->set_categories($categories);
						if ($config->get_default_category() == $id)
							$config->set_default_category(0);
						
						//Delete the category for the bugs of this category
						BugtrackerService::update_parameter(array('category' => 0), 'WHERE category=:id', array('id' => $id));
						
						//Delete history lines containing this category
						BugtrackerService::delete_history("WHERE updated_field='category' AND (old_value=:id OR new_value=:id)", array('id' => $id));
					}
					else
					{
						//Error : unexist category
						$controller = new UserErrorController(LangLoader::get_message('error', 'errors-common'), LangLoader::get_message('bugs.error.e_unexist_category', 'common', 'bugtracker'));
						$controller->set_response_classname(UserErrorController::ADMIN_RESPONSE);
						DispatchManager::redirect($controller);
					}
					break;
				case 'version':
					if (isset($versions[$id]))
					{
						//Delete the version in the list of versions
						unset($versions[$id]);
						$config->set_versions($versions);
						if ($config->get_default_version() == $id)
							$config->set_default_version(0);
						
						//Delete the version for the bugs of this version
						BugtrackerService::update_parameter(array('detected_in' => 0), 'WHERE detected_in=:id', array('id' => $id));
						BugtrackerService::update_parameter(array('fixed_in' => 0), 'WHERE fixed_in=:id', array('id' => $id));
						
						//Delete history lines containing this version
						BugtrackerService::delete_history("WHERE updated_field='detected_in' AND (old_value=:id OR new_value=:id)", array('id' => $id));
						BugtrackerService::delete_history("WHERE updated_field='fixed_in' AND (old_value=:id OR new_value=:id)", array('id' => $id));
					}
					else
					{
						//Error : unexist version
						$controller = new UserErrorController(LangLoader::get_message('error', 'errors-common'), LangLoader::get_message('bugs.error.e_unexist_version', 'common', 'bugtracker'));
						$controller->set_response_classname(UserErrorController::ADMIN_RESPONSE);
						DispatchManager::redirect($controller);
					}
					break;
			}
			
			BugtrackerConfig::save();
			AppContext::get_response()->redirect(BugtrackerUrlBuilder::configuration_success('config_modified'));
		}
		else
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'errors-common'), LangLoader::get_message('bugs.error.e_unexist_parameter', 'common', 'bugtracker'));
			$controller->set_response_classname(UserErrorController::ADMIN_RESPONSE);
			DispatchManager::redirect($controller);
		}
	}
	
	private function check_authorizations()
	{
		if (!BugtrackerAuthorizationsService::check_authorizations()->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
