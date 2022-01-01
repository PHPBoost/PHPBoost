<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 19
 * @since       PHPBoost 6.0 - 2020 04 29
*/

class UserAccountsConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('kernel', false, 'kernel-user-accounts');
	}

	protected function build_new_config()
	{
		$old_config = $this->get_old_config();

		$config = UserAccountsConfig::load();
		$config->set_default_avatar_name($old_config->is_default_avatar_enabled() ? (in_array($old_config->get_default_avatar_name(), array('no_avatar.png', FormFieldThumbnail::DEFAULT_VALUE)) ? FormFieldThumbnail::DEFAULT_VALUE : Url::to_rel('/templates/' . AppContext::get_current_user()->get_theme() . '/images/' . $old_config->get_default_avatar_name())) : '');
		$this->save_new_config('kernel-user-accounts', $config);

		return true;
	}
}
?>
