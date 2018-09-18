<?php
/*##################################################
 *                           AbstractResponse.class.php
 *                            -------------------
 *   begin                : October 18 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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



/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc the response
 * @package {@package}
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
			
			$view->put('MESSAGE', MessageHelper::display(StringVars::replace_vars(LangLoader::get_message('content.is_locked.description', 'status-messages-common'), array('user_display_name' => $user_display_name ? $user_display_name : LangLoader::get_message('content.is_locked.another_user')), MessageHelper::NOTICE)));
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