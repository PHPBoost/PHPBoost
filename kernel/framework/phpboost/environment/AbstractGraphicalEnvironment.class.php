<?php
/*##################################################
 *                    abstract_graphical_environment.class.php
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



abstract class AbstractGraphicalEnvironment implements GraphicalEnvironment
{
	protected $user;

	public function __construct()
	{
		$this->user = AppContext::get_user();
	}

	protected function process_site_maintenance()
	{
		if ($this->is_under_maintenance())
		{
			$maintenance_config = MaintenanceConfig::load();
			if (!$this->user->check_auth($maintenance_config->get_auth(), 1))
			{
				if (SCRIPT !== (DIR . '/member/maintain.php'))
				{
					AppContext::get_response()->redirect('/member/maintain.php');
				}
			}
		}
	}

	protected function is_under_maintenance()
	{
		$maintenance_config = MaintenanceConfig::load();
		return $maintenance_config->is_under_maintenance();
	}
	
	protected static function set_page_localization($page_title)
	{
		AppContext::get_session()->check($page_title);
	}
}

?>