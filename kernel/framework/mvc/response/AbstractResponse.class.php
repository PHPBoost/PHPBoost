<?php
/**
 * @package     MVC
 * @subpackage  Response
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 25
 * @since       PHPBoost 3.0 - 2009 10 18
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractResponse implements Response
{
	/**
	 * @var View
	 */
	private $view;

	/**
	 * @var GraphicalEnvironment
	 */
	private $graphical_environment;

	protected function __construct(GraphicalEnvironment $graphical_environment, View $view, $location_id = '')
	{
		$this->graphical_environment = $graphical_environment;

		if (!empty($location_id) && AppContext::get_session()->location_id_already_exists($location_id))
		{
			$user_display_name = UserService::display_user_profile_link(AppContext::get_session()->get_user_on_location_id($location_id));

			$view = new StringTemplate('# INCLUDE MESSAGE #');

			$view->put('MESSAGE', MessageHelper::display(StringVars::replace_vars(LangLoader::get_message('warning.locked.content.description', 'warning-lang'), array('user_display_name' => $user_display_name ? $user_display_name : LangLoader::get_message('warning.locked.content.another.user', 'warning-lang'))), MessageHelper::NOTICE));
		}

		$this->view = $view;
	}

	public function get_graphical_environment()
	{
		return $this->graphical_environment;
	}

	public function send()
	{
		$this->graphical_environment->display($this->view->render());
	}
}
?>
