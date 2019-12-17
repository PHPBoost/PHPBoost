<?php
/**
 * @package     Content
 * @subpackage  Share
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 06 19
 * @since       PHPBoost 5.1 - 2018 01 30
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class ContentSharingActionsMenuService
{
	private static $tpl;

	public static function get_content_sharing_actions_links()
	{
		$content_sharing_actions_menu_links = array();
		$extension_point = AppContext::get_extension_provider_service()->get_extension_point(ContentSharingActionsMenuLinksExtensionPoint::EXTENSION_POINT);

		foreach ($extension_point as $id => $provider)
		{
			foreach ($provider as $link)
			{
				$content_sharing_actions_menu_links[] = $link;
			}
		}
		return $content_sharing_actions_menu_links;
	}

	public static function display_sharing_elements($tpl = null)
	{
		if ($tpl instanceof Template)
		{
			self::$tpl = $tpl;
		}
		else if (!empty($tpl))
		{
			self::$tpl = new FileTemplate($tpl);
		}
		else
		{
			self::$tpl = new FileTemplate('framework/content/share/ContentSharingActionsMenu.tpl');
		}

		$content_sharing_actions_menu_links = self::get_content_sharing_actions_links();
		foreach ($content_sharing_actions_menu_links as $link)
		{
			self::$tpl->assign_block_vars('element', array(
				'ELEMENT' => $link->export()->render()
			));
		}

		return self::$tpl->render();
	}

	public static function display($tpl = null)
	{
		return ContentManagementConfig::load()->is_content_sharing_enabled() ? self::display_sharing_elements($tpl) : '';
	}
}
?>
