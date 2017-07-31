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
		parent::__construct('download-config', false);
	}
	
	protected function build_new_config()
	{
		$old_config = $this->get_old_config();
		
		if ($old_config)
		{
			$config = DownloadConfig::load();
			$config->set_property('authorizations', $this->build_authorizations($old_config->get_property('authorizations')));
			$config->set_property('root_category_description', $old_config->get_property('root_category_description'));
			$this->save_new_config('download-config', $config);
			
			if (!$old_config->get_property('comments_enabled'))
			{
				$comments_config = CommentsConfig::load();
				$unauthorized_modules = $comments_config->get_comments_unauthorized_modules();
				$unauthorized_modules[] = 'download';
				$comments_config->set_comments_unauthorized_modules($unauthorized_modules);
				CommentsConfig::save();
			}
			
			if (!$old_config->get_property('notation_enabled'))
			{
				$content_management_config = ContentManagementConfig::load();
				$unauthorized_modules = $content_management_config->get_notation_unauthorized_modules();
				$unauthorized_modules[] = 'download';
				$content_management_config->set_notation_unauthorized_modules($unauthorized_modules);
				ContentManagementConfig::save();
			}
			
			return true;
		}
		return false;
	}
	
	private function build_authorizations($old_auth)
	{
		$new_auth = array();
		
		foreach ($old_auth as $level => $auth)
		{
			$new_auth[$level] = $auth + 16;
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
