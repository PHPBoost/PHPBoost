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

class InstallWelcomeController extends InstallController
{
	public function execute(HTTPRequest $request)
	{
        parent::load_lang($request);
		$view = new FileTemplate('install/welcome.tpl');
		$this->add_navigation($view);
		return $this->create_response($view);
	}

	/**
	 * @param Template $view
	 * @return InstallDisplayResponse
	 */
	private function create_response(Template $view)
	{
        $step_title = $this->lang['step.welcome.title'];
		$response = new InstallDisplayResponse(0, $step_title, $view);
		return $response;
	}

	private function add_navigation(Template $view)
    {
        $form = new HTMLForm('preambleForm', InstallUrlBuilder::license()->absolute());
        $form->add_button(new InstallNavigationBar());
        $view->add_subtemplate('LICENSE_FORM', $form->display());
    }
}
?>