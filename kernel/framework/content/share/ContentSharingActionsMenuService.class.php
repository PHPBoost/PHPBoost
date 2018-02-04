<?php
/*##################################################
 *		                        ContentSharingActionsMenuService.class.php
 *                            -------------------
 *   begin                : January 30, 2018
 *   copyright            : (C) 2018 Kévin MASSY
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

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
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

	public static function display($tpl = null)
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
}
?>