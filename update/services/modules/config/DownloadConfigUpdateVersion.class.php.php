<?php
/*##################################################
 *                       DownloadConfigUpdateVersion.class.php
 *                            -------------------
 *   begin                : January 2, 2016
 *   copyright            : (C) 2016 Julien BRISWALTER
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

class DownloadConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('download');
	}
	
	protected function build_new_config()
	{
		$config = $this->get_old_config();
		
		$download_config = DownloadConfig::load();
		
		$download_config->set_authorizations($this->build_authorizations($config['auth']));
		
		DownloadConfig::save();
		
		return true;
	}
	
	private function build_authorizations($old_auth)
	{
		$new_auth = array();
		
		foreach ($old_auth as $level => $auth)
		{
			switch ($level) {
				case 'r-1':
					$new_auth[$level] = ($auth == 17 ? 33 : $auth);
				break;
				case 'r0':
					$new_auth[$level] = ($auth == 21 ? 53 : ($auth == 23 ? 55 : $auth));
				break;
				case 'r1':
					$new_auth[$level] = ($auth == 29 ? 61 : ($auth == 31 ? 63 : ($auth == 17 ? 33 : $auth)));
				break;
				default:
					$new_auth[$level] = ($auth == 21 ? 53 : ($auth == 23 ? 55 : $auth));
				break;
			}
		}
		
		if (!isset($new_auth['r-1']))
			$new_auth['r-1'] = 33;
		if (!isset($new_auth['r0']))
			$new_auth['r0'] = 53;
		if (!isset($new_auth['r1']))
			$new_auth['r1'] = 61;
		
		return $new_auth;
	}
}
?>