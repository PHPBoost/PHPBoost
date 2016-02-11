<?php
/*##################################################
 *                          BugtrackerAjaxCheckStatusChangedController.class.php
 *                            -------------------
 *   begin                : February 21, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
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

class BugtrackerAjaxCheckStatusChangedController extends AbstractController
{
	private $view;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_view($request);
		return new SiteNodisplayResponse($this->view);
	}
	
	private function build_view(HTTPRequestCustom $request)
	{
		$id = $request->get_value('id', 0);
		$status = $request->get_value('status', '');
		$old_status = $request->get_value('old_status', '');
		
		$this->view->put('RESULT', (int)(!empty($id) && $old_status == $status));
	}
	
	private function init()
	{
		$this->view = new StringTemplate('{RESULT}');
	}
}
?>
