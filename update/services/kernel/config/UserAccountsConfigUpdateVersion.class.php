<?php
/*##################################################
 *                       UserAccountsConfigUpdateVersion.class.php
 *                            -------------------
 *   begin                : February 29, 2012
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

class UserAccountsConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('member');
	}
	
	protected function build_new_config()
	{
		$config = $this->get_old_config();
		
		$user_account_config = UserAccountsConfig::load();
		$user_account_config->set_registration_enabled($config['activ_register']);
		$user_account_config->set_member_accounts_validation_method(($config['activ_mbr']+1));
		$user_account_config->set_registration_captcha_enabled($config['verif_code']);
		$user_account_config->set_registration_captcha_difficulty($config['verif_code_difficulty']);
		$user_account_config->set_avatar_upload_enabled($config['activ_up_avatar']);
		$user_account_config->set_unactivated_accounts_timeout($config['delay_unactiv_max']);
		$user_account_config->set_default_avatar_name_enabled($config['activ_avatar']);
		$user_account_config->set_default_avatar_name($config['avatar_url']);
		$user_account_config->set_max_avatar_width($config['width_max']);
		$user_account_config->set_max_avatar_height($config['height_max']);
		$user_account_config->set_max_avatar_weight($config['weight_max']);
		$user_account_config->set_welcome_message($config['msg_mbr']);
		$user_account_config->set_registration_agreement($config['msg_register']);
		UserAccountsConfig::save();
		
		return true;
	}
}