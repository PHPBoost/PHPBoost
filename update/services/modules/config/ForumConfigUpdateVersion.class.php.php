<?php
/*##################################################
 *                       ForumConfigUpdateVersion.class.php
 *                            -------------------
 *   begin                : September 3, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
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

class ForumConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('forum');
	}
	
	protected function build_new_config()
	{
		$config = $this->get_old_config();
		
		$forum_config = ForumConfig::load();
		$forum_config->set_forum_name($config['forum_name']);
		$forum_config->set_number_topics_per_page($config['pagination_topic']);
		$forum_config->set_number_messages_per_page($config['pagination_msg']);
		$forum_config->set_read_messages_storage_duration($config['view_time']);
		$forum_config->set_max_topic_number_in_favorite($config['topic_track']);
		
		if (!empty($config['topic_track']))
			$forum_config->enable_edit_mark();
		else
			$forum_config->disable_edit_mark();
		
		if (!empty($config['no_left_column']))
			$forum_config->enable_left_column();
		else
			$forum_config->disable_left_column();
		
		if (!empty($config['no_right_column']))
			$forum_config->enable_right_column();
		else
			$forum_config->disable_right_column();
		
		if (!empty($config['activ_display_msg']))
			$forum_config->display_message_before_topic_title();
		else
			$forum_config->hide_message_before_topic_title();
		
		$forum_config->set_message_before_topic_title($config['display_msg']);
		$forum_config->set_message_when_topic_is_unsolved($config['explain_display_msg']);
		$forum_config->set_message_when_topic_is_solved($config['explain_display_msg_bis']);
		
		if (!empty($config['icon_activ_display_msg']))
			$forum_config->display_message_before_topic_title_icon();
		else
			$forum_config->hide_message_before_topic_title_icon();
		
		if (!empty($config['display_connexion']))
			$forum_config->display_connexion_form();
		else
			$forum_config->hide_connexion_form();
		
		$forum_config->set_authorizations($this->build_authorizations(unserialize($config['auth'])));
		
		ForumConfig::save();
		
		return true;
	}
	
	private function build_authorizations($old_auth)
	{
		$new_auth = array();
		
		foreach ($old_auth as $level => $auth)
		{
			switch ($level) {
				case 'r-1':
					$new_auth[$level] = ($auth == 1 ? 17 : ($auth == 3 ? 49 : 1));
				break;
				case 'r0':
					$new_auth[$level] = ($auth == 1 ? 147 : ($auth == 3 ? 179 : ($auth == 7 ? 243 : 131)));
				break;
				case 'r1':
					$new_auth[$level] = ($auth == 1 ? 155 : ($auth == 3 ? 187 : ($auth == 7 ? 251 : 139)));
				break;
				default:
					$new_auth[$level] = ($auth == 1 ? 147 : ($auth == 3 ? 169 : ($auth == 7 ? 233 : 131)));
				break;
			}
		}
		
		if (!isset($new_auth['r-1']))
			$new_auth['r-1'] = 1;
		if (!isset($new_auth['r0']))
			$new_auth['r0'] = 131;
		if (!isset($new_auth['r1']))
			$new_auth['r1'] = 139;
		
		return $new_auth;
	}
}
?>