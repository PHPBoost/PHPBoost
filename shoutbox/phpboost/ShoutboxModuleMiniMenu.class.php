<?php
/*##################################################
 *                          ShoutboxModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : October 08, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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

class ShoutboxModuleMiniMenu extends ModuleMiniMenu
{    
	public function get_default_block()
	{
		return self::BLOCK_POSITION__RIGHT;
	}
	
	public function admin_display()
	{
		return '';
	}
	
	public function display($tpl = false)
	{
		//Mini Shoutbox non activée si sur la page archive shoutbox.
		if (!Url::is_current_url('/shoutbox/') && OnlineAuthorizationsService::check_authorizations()->read())
		{
			$tpl = new FileTemplate('shoutbox/ShoutboxModuleMiniMenu.tpl');
			MenuService::assign_positions_conditions($tpl, $this->get_block());
			
			$lang = LangLoader::get('common', 'shoutbox');
			$tpl->add_lang($lang);
			
			$config_shoutbox = ShoutboxConfig::load();
			
			$is_member = AppContext::get_current_user()->check_level(User::MEMBER_LEVEL);
			
			global $LANG;
			
			$tpl->put_all(array(
				'C_MEMBER' => $is_member,
				'C_VISIBLE_SHOUT' => !$is_member,
				'C_HIDDEN_SHOUT' => $is_member,
				'SHOUTBOX_PSEUDO' => $is_member ? AppContext::get_current_user()->get_display_name() : $LANG['guest'],
				'SHOUT_REFRESH_DELAY' => $config_shoutbox->get_refresh_delay(),
				'L_ALERT_TEXT' => $LANG['require_text'],
				'L_ALERT_UNAUTH_POST' => $LANG['e_unauthorized'],
				'L_ALERT_FLOOD' => $LANG['e_flood'],
				'L_ALERT_LINK_FLOOD' => sprintf($LANG['e_l_flood'], $config_shoutbox->get_max_links_number_per_message()),
				'L_ALERT_INCOMPLETE' => $LANG['e_incomplete'],
				'L_ALERT_READONLY' => $LANG['e_readonly'],
				'L_DELETE_MSG' => $LANG['alert_delete_msg'],
				'L_MESSAGE' => $LANG['message'],
				'L_PSEUDO' => $LANG['pseudo'],
				'L_SUBMIT' => $LANG['submit'],
				'L_REFRESH' => $LANG['refresh']
			));
	
			$array_class = array('member', 'modo', 'admin');
			$result = PersistenceContext::get_querier()->select("SELECT s.*, m.display_name as mlogin, m.groups, m.level
			FROM " . PREFIX . "shoutbox s
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = s.user_id
			ORDER BY s.timestamp DESC
			LIMIT 25 OFFSET 0");
			while ($row = $result->fetch())
			{
				$row['user_id'] = (int)$row['user_id'];
				if (ShoutboxAuthorizationsService::check_authorizations()->moderation() || ($row['user_id'] === AppContext::get_current_user()->get_id() && AppContext::get_current_user()->get_id() !== -1))
					$del_message = '<a href="javascript:Confirm_del_shout(' . $row['id'] . ');" title="' . $LANG['delete'] . '" class="small"><i class="fa fa-remove"></i></a>';
				else
					$del_message = '';
	
				if ($row['user_id'] !== -1)
				{
					$group_color = User::get_group_color($row['groups'], $row['level']);
					$style = $group_color ? 'style="color:'.$group_color.'"' : '';
					$row['login'] = $del_message . ' <a '.$style.' class="'. UserService::get_level_class($row['level']) .'" href="' . UserUrlBuilder::profile($row['user_id'])->rel() . '">' . (!empty($row['mlogin']) ? TextHelper::wordwrap_html($row['mlogin'], 16) : $LANG['guest'])  . '</a>';
				}
				else
					$row['login'] = $del_message . ' <span class="small" style="font-style: italic;">' . (!empty($row['login']) ? TextHelper::wordwrap_html($row['login'], 16) : $LANG['guest']) . '</span>';
				
				$date = new Date(DATE_TIMESTAMP, Timezone::SERVER_TIMEZONE, $row['timestamp']);
				$tpl->assign_block_vars('shout', array(
					'IDMSG' => $row['id'],
					'PSEUDO' => $row['login'],
					'DATE' => $date->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
					'CONTENTS' => FormatingHelper::second_parse($row['contents'])
				));
			}
			$result->dispose();
			
			return $tpl->render();
		}
		return '';
	}
}
?>