<?php
/*##################################################
 *                       MediaConfigUpdateVersion.class.php
 *                            -------------------
 *   begin                : September 3, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class MediaConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('media');
	}
	
	protected function build_new_config()
	{
		$config = $this->get_old_config();
		$config['root'] = unserialize($config['root']);
		
		$media_config = MediaConfig::load();
		$media_config->set_items_number_per_page($config['pagin']);
		$media_config->set_categories_number_per_page($config['nbr_column']);
		$media_config->set_notation_scale($config['note_max']);
		$media_config->set_max_video_width($config['width']);
		$media_config->set_max_video_height($config['height']);
		$media_config->set_root_category_description($config['root']['desc']);
		$media_config->set_root_category_content_type($config['root']['mime_type']);
		$media_config->set_authorizations($this->build_authorizations(unserialize($config['root']['auth'])));
		
		MediaConfig::save();
		
		return true;
	}
	
	private function build_authorizations($old_auth)
	{
		$new_auth = array();
		
		foreach ($old_auth as $level => $auth)
		{
			$new_auth[$level] = ($auth == 7 ? 13 : ($auth == 3 ? 5 : $auth));
		}
		
		if (!isset($new_auth['r-1']))
			$new_auth['r-1'] = 1;
		if (!isset($new_auth['r0']))
			$new_auth['r0'] = 5;
		if (!isset($new_auth['r1']))
			$new_auth['r1'] = 13;
		
		return $new_auth;
	}
}
?>