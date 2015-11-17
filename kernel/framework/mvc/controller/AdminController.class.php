<?php
/*##################################################
 *                          AbstractController.class.php
 *                            -------------------
 *   begin                : December 14 2009
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
 * @desc This class defines the minimalist controler pattern
 */
abstract class AdminController extends AbstractController
{
	public final function get_right_controller_regarding_authorizations()
	{
		if (!AppContext::get_current_user()->is_admin())
		{
			return new UserLoginController(UserLoginController::ADMIN_LOGIN, substr(REWRITED_SCRIPT, strlen(GeneralConfig::load()->get_site_path())));
		}
		return $this;
	}
}
?>