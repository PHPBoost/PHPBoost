<?php
/*##################################################
 *                       UserError404Controller.class.php
 *                            -------------------
 *   begin                : October 07, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class UserError404Controller extends UserErrorController
{
	public function __construct()
	{
		$error = LangLoader::get_message('error', 'status-messages-common');
		$unexist_page = LangLoader::get_message('error.page.unexist', 'status-messages-common');
		$message = '<strong>404.</strong> ' . $unexist_page;
		parent::__construct($error. ' 404', $message, self::WARNING);
	}
	
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->no_session_location();
		AppContext::get_response()->set_status_code(404);
		AdminError404Service::register_404();
		return parent::execute($request);
	}

	protected function create_view()
	{
		$this->view = new FileTemplate('user/UserError404Controller.tpl');
	}
}
?>