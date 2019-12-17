<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 3.0 - 2012 10 21
*/

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
