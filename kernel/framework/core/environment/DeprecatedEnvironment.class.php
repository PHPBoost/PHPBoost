<?php
/*##################################################
 *                          DeprecatedEnvironment.class.php
 *                            -------------------
 *   begin                : December 28, 2009
 *   copyright            : (C) 2009 Loïc Rouchon
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
 * @package core
 * @subpackage environment
 * This class manages all the deprecated services that old PHPBoost modules need to run.
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 *
 */
class DeprecatedEnvironment
{
	public static function check_page_auth()
	{
		$running_module_name = Environment::get_running_module_name();
		if (!in_array($running_module_name, array('member', 'admin', 'kernel')))
		{
			if (ModulesManager::is_module_installed($running_module_name))
			{
				$module = ModulesManager::get_module($running_module_name);
				if (!$module->is_activated())
				{
					DispatchManager::redirect(PHPBoostErrors::module_not_activated());
				}
				else if(!$module->check_auth())
				{
					DispatchManager::redirect(PHPBoostErrors::user_not_authorized());
				}
			}
			else
			{
				DispatchManager::redirect(PHPBoostErrors::module_not_installed());
			}
		}
	}
}
?>