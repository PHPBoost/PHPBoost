<?php
/*##################################################
 *		                   writing_pad_config.class.php
 *                            -------------------
 *   begin                : October 17, 2009
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

import('io/config/default_config_data');

class WritingPadConfig extends DefaultConfigData
{
	public function set_content($content)
	{
		$this->set_property('content', $content);
	}

	public function get_content()
	{
		try
		{
			return $this->get_property('content');
		}
		catch(PropertyNotFoundException $ex)
		{
			return '';
		}
	}
	
	public function set_default_values()
	{
		global $LANG;
		$this->set_content($LANG['writing_pad_explain']);
	}

	/**
	 *
	 * @return WritingPadConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'writing-pad');
	}

	public static function save(WritingPadConfig $config)
	{
		ConfigManager::save('kernel', $config, 'writing-pad');
	}
}