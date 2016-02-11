<?php
/*##################################################
 *                       UserError403Controller.class.php
 *                            -------------------
 *   begin                : October 19, 2012
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

class UserError403Controller extends UserErrorController
{
	public function __construct()
	{
		$error = LangLoader::get_message('error', 'status-messages-common');
		$unexist_page = LangLoader::get_message('error.page.forbidden', 'status-messages-common');
		$message = '<strong>403.</strong> ' . $unexist_page;
		parent::__construct($error. ' 403', $message, self::WARNING);
	}
	
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_response()->set_status_code(403);
		return parent::execute($request);
	}

	protected function create_view()
	{
		$columns_disabled = ThemesManager::get_theme(AppContext::get_current_user()->get_theme())->get_columns_disabled();
		$columns_disabled->set_disable_right_columns(true);
		$columns_disabled->set_disable_left_columns(true);
		$columns_disabled->set_disable_top_central(true);
		$columns_disabled->set_disable_bottom_central(true);
		$this->view = new FileTemplate('user/UserError403Controller.tpl');
	}
}
?>