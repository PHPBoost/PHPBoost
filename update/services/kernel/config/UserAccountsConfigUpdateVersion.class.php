<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 04 29
 * @since       PHPBoost 5.3 - 2020 04 29
*/

class UserAccountsConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('kernel-user-accounts', false);
	}

	protected function build_new_config()
	{
		$old_config = $this->get_old_config();

		$config = UserAccountsConfig::load();
		$config->set_default_avatar_name($old_config->is_default_avatar_enabled() ? ($old_config->get_default_avatar_name() == 'no_avatar.png' ? FormFieldThumbnail::DEFAULT_VALUE : '/templates/' . AppContext::get_current_user()->get_theme() . '/images/' . $old_config->get_default_avatar_name()) : '');
		$this->save_new_config('kernel-user-accounts', $config);

		return true;
	}
}
?>
