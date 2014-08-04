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
	    global $Cache, $LANG,  $Sql;

	    $config_shoutbox = ShoutboxConfig::load();
	
	    //Mini Shoutbox non activée si sur la page archive shoutbox.
	    if (strpos(SCRIPT, '/shoutbox/shoutbox.php') === false && ShoutboxAuthorizationsService::check_authorizations()->read())
	    {
	    	load_module_lang('shoutbox');
	
	    	###########################Insertion##############################
	    	$shoutbox = retrieve(POST, 'shoutbox', false);
	    	if ($shoutbox)
	    	{
	    		//Membre en lecture seule?
	    		if (AppContext::get_current_user()->get_attribute('user_readonly') > time())
	    		{
	    			$error_controller = PHPBoostErrors::user_in_read_only();
	                DispatchManager::redirect($error_controller);
	    		}
	
	    		$shout_pseudo = substr(retrieve(POST, 'shout_pseudo', $LANG['guest']), 0, 25); //Pseudo posté.
	    		$shout_contents = retrieve(POST, 'shout_contents', '', TSTRING_UNCHANGE);
	    		if (!empty($shout_pseudo) && !empty($shout_contents))
	    		{
	    			//Accès pour poster.
	    			if (ShoutboxAuthorizationsService::check_authorizations()->write())
	    			{
	    				//Mod anti-flood, autorisé aux membres qui bénificie de l'autorisation de flooder.
	    				$check_time = (AppContext::get_current_user()->get_id() !== -1 && ContentManagementConfig::load()->is_anti_flood_enabled()) ? $Sql->query("SELECT MAX(timestamp) as timestamp FROM " . PREFIX . "shoutbox WHERE user_id = '" . AppContext::get_current_user()->get_id() . "'") : '';
	    				if (!empty($check_time) && !AppContext::get_current_user()->check_max_value(AUTH_FLOOD))
	    				{
	    					if ($check_time >= (time() - ContentManagementConfig::load()->get_anti_flood_duration()))
	    						AppContext::get_response()->redirect('/shoutbox/shoutbox.php' . url('?error=flood', '', '&'));
	    				}
	
	    				//Vérifie que le message ne contient pas du flood de lien.
	    				$shout_contents = FormatingHelper::strparse($shout_contents, $config_shoutbox->get_forbidden_formatting_tags());
	    				if (!TextHelper::check_nbr_links($shout_pseudo, 0)) //Nombre de liens max dans le pseudo.
	    					AppContext::get_response()->redirect('/shoutbox/shoutbox.php' . url('?error=lp_flood', '', '&'));
	    				if (!TextHelper::check_nbr_links($shout_contents, $config_shoutbox->get_max_links_number_per_message(), true)) //Nombre de liens max dans le message.
	    					AppContext::get_response()->redirect('/shoutbox/shoutbox.php' . url('?error=l_flood', '', '&'));
	
	    				$Sql->query_inject("INSERT INTO " . PREFIX . "shoutbox (login, user_id, level, contents, timestamp) VALUES ('" . $shout_pseudo . "', '" . AppContext::get_current_user()->get_id() . "', '" . AppContext::get_current_user()->get_attribute('level') . "', '" . $shout_contents . "', '" . time() . "')");
	
	    				AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
	    			}
	    			else //utilisateur non autorisé!
	    				AppContext::get_response()->redirect('/shoutbox/shoutbox.php' . url('?error=auth', '', '&'));
	    		}
	    	}
	
	    	###########################Affichage##############################
	    	$tpl = new FileTemplate('shoutbox/shoutbox_mini.tpl');
	
	       MenuService::assign_positions_conditions($tpl, $this->get_block());
	
	    	//Pseudo du membre connecté.
	    	if (AppContext::get_current_user()->get_id() !== -1)
	    		$tpl->put_all(array(
	    			'SHOUTBOX_PSEUDO' => AppContext::get_current_user()->get_attribute('login'),
	    			'C_HIDDEN_SHOUT' => true
	    		));
	    	else
	    		$tpl->put_all(array(
	    			'SHOUTBOX_PSEUDO' => $LANG['guest'],
	    			'C_VISIBLE_SHOUT' => true
	    		));
	
			$tpl->put_all(array(
	    		'SHOUT_REFRESH_DELAY' => $config_shoutbox->get_refresh_delay(),
	    		'L_ALERT_TEXT' => $LANG['require_text'],
	    		'L_ALERT_UNAUTH_POST' => $LANG['e_unauthorized'],
	    		'L_ALERT_FLOOD' => $LANG['e_flood'],
	    		'L_ALERT_LINK_FLOOD' => sprintf($LANG['e_l_flood'], $config_shoutbox->get_max_links_number_per_message()),
	    		'L_ALERT_LINK_PSEUDO' => $LANG['e_link_pseudo'],
	    		'L_ALERT_INCOMPLETE' => $LANG['e_incomplete'],
	    		'L_ALERT_READONLY' => $LANG['e_readonly'],
				'L_DELETE_MSG' => $LANG['alert_delete_msg'],
	    		'L_SHOUTBOX' => $LANG['title_shoutbox'],
	    		'L_MESSAGE' => $LANG['message'],
	    		'L_PSEUDO' => $LANG['pseudo'],
	    		'L_SUBMIT' => $LANG['submit'],
	    		'L_REFRESH' => $LANG['refresh'],
	    		'L_ARCHIVES' => $LANG['archives']
	    	));
	
	    	$array_class = array('member', 'modo', 'admin');
	    	$result = $Sql->query_while("SELECT s.id, s.login, s.user_id, s.level, s.contents, s.timestamp, m.login as mlogin, m.user_groups
	    	FROM " . PREFIX . "shoutbox s
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = s.user_id
	    	ORDER BY s.timestamp DESC
	    	" . $Sql->limit(0, 25));
	    	while ($row = $Sql->fetch_assoc($result))
	    	{
	    		$row['user_id'] = (int)$row['user_id'];
	    		if (ShoutboxAuthorizationsService::check_authorizations()->moderation() || ($row['user_id'] === AppContext::get_current_user()->get_id() && AppContext::get_current_user()->get_id() !== -1))
	    			$del_message = '<a href="javascript:Confirm_del_shout(' . $row['id'] . ');" title="' . $LANG['delete'] . '" class="small"><i class="fa fa-remove"></i></a>';
	    		else
	    			$del_message = '';
	
	    		if ($row['user_id'] !== -1)
	    		{
	    			$group_color = User::get_group_color($row['user_groups'], $row['level']);
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
	    	$Sql->query_close($result);
	
	    	return $tpl->render();
	    }
	    return '';
    }
}
?>