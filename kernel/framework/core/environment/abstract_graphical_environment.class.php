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
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

import('core/environment/graphical_environment');

abstract class AbstractGraphicalEnvironment implements GraphicalEnvironment
{
	protected $user;

	public function __construct()
	{
		$this->user = EnvironmentServices::get_user();
	}

	protected function process_site_maintenance()
	{
		global $CONFIG, $Template;

		if ($this->is_under_maintenance())
		{
			if (!$this->user->check_level(ADMIN_LEVEL) &&
			!$this->user->check_auth($CONFIG['maintain_auth'], AUTH_MAINTAIN))
			{
				if (SCRIPT !== (DIR . '/member/maintain.php'))
				{
					redirect('/member/maintain.php');
				}
			}
		}
	}

	protected function is_under_maintenance()
	{
		global $CONFIG;
		return $CONFIG['maintain'] == -1 || $CONFIG['maintain'] > time();
	}
}

?>