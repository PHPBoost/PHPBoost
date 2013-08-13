<?php
/*##################################################
 *                       CommentsConfigUpdateVersion.class.php
 *                            -------------------
 *   begin                : February 28, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class CommentsConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('com');
	}
	
	protected function build_new_config()
	{
		$config = $this->get_old_config();
		
		$comments_configuration = CommentsConfig::load();
		$comments_configuration->set_display_captcha($config['com_verif_code']);
		$comments_configuration->set_captcha_difficulty($config['com_verif_code_difficulty']);
	 	$comments_configuration->set_forbidden_tags($config['forbidden_tags']);
		$comments_configuration->set_max_links_comment($config['max_link']);
		CommentsConfig::save();
        
		return true;
	}
}