<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 12
 * @since       PHPBoost 4.1 - 2014 08 24
*/

class SocialNetworksModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__RIGHT;
	}

	public function get_menu_id()
	{
		return 'module-mini-social-networks';
	}

	public function get_menu_title()
	{
		return LangLoader::get_message('common.share', 'common-lang');
	}

	public function get_formated_title()
	{
		return LangLoader::get_message('sn.module.title', 'common', 'SocialNetworks');
	}

	public function get_menu_content()
	{
		$tpl = new FileTemplate('SocialNetworks/SocialNetworksModuleMiniMenu.tpl');

		$tpl->put('NETWORK', ContentSharingActionsMenuService::display_sharing_elements());

		return $tpl->render();
	}
}
?>
