<?php
/*##################################################
 *                    AbstractGraphicalEnvironment.class.php
 *                            -------------------
 *   begin                : October 06, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * @package {@package}
 * @desc
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
abstract class AbstractGraphicalEnvironment implements GraphicalEnvironment
{
	protected function process_site_maintenance()
	{
		$maintenance_config = MaintenanceConfig::load();
		if ($maintenance_config->is_under_maintenance())
		{
			if (!$maintenance_config->is_authorized_in_maintenance())
			{
				// Redirect if user not authorized the site for maintenance
				if (AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
				{
					$session = AppContext::get_session();
					Session::delete($session);

					AppContext::get_response()->redirect(UserUrlBuilder::connect());
				}
				
				$maintain_url = UserUrlBuilder::maintain();
				if (!Url::is_current_url($maintain_url->relative()))
				{
					AppContext::get_response()->redirect($maintain_url);
				}
			}
		}
	}
	
	protected static function set_page_localization($page_title)
	{
		AppContext::get_session()->update_location($page_title);
	}
}
?>