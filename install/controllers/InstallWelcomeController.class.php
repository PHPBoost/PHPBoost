<?php
/*##################################################
 *                         InstallWelcomeController.class.php
 *                            -------------------
 *   begin                : June 13 2010
 *   copyright            : (C) 2010 Loic Rouchon
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

class InstallWelcomeController extends AbstractController
{
	private $lang;

	public function execute(HTTPRequest $request)
	{
        $this->lang = LangLoader::get('install', 'install');
		$view = new FileTemplate('install/welcome.tpl');
        $view->assign_vars(array('DISTRIBUTION' => 'coucou'));
        $view->assign_vars(array('DISTRIBUTION_DESCRIPTION' => 'coucou'));
		return $this->create_response($view);
	}

	/**
	 * @param Template $view
	 * @return InstallDisplayResponse
	 */
	private function create_response(Template $view)
	{
        $page_title = $this->lang['installation.title'];
        $step_title = $this->lang['step.welcome.title'];
        $step_explanation = $this->lang['step.welcome.explanation'];
		$response = new InstallDisplayResponse($page_title, $view, 0, $step_title, $step_explanation);
		$this->add_navigation($response);
		return $response;
	}

	/**
	 * @param InstallDisplayResponse $response
	 */
	private function add_navigation(InstallDisplayResponse $response)
    {
    	$name = $this->lang['step.license.title'];
    	$response->set_next_step($name, InstallUrlBuilder::license()->relative());
    }
}
?>