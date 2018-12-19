<?php
/*##################################################
 *                       WikiConfigUpdateVersion.class.php
 *                            -------------------
 *   begin                : December 19, 2018
 *   copyright            : (C) 2018 Julien BRISWALTER
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

class WikiConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('wiki-config', false);
	}
	
	protected function build_new_config()
	{
		$old_config = $this->get_old_config();
		
		if ($old_config)
		{
			$config = WikiConfig::load();
			$config->set_property('authorizations', $this->build_authorizations($old_config->get_property('authorizations')));
			$this->save_new_config('wiki-config', $config);
			
			return true;
		}
		return false;
	}
	
	private function build_authorizations($old_auth)
	{
		$new_auth = array();
		
		foreach ($old_auth as $level => $auth)
		{
			if (($auth - 4096) < 0 && in_array($level, array("r-1", "r0", "r1")))
				$new_auth[$level] = $auth + 4096;
		}
		
		if (!isset($new_auth['r-1']))
			$new_auth['r-1'] = 5120;
		if (!isset($new_auth['r0']))
			$new_auth['r0'] = 5395;
		if (!isset($new_auth['r1']))
			$new_auth['r1'] = 8191;
		
		return $new_auth;
	}
}
?>
