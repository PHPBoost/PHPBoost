<?php
/**
 * @package     Content
 * @subpackage  Share
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 10
 * @since       PHPBoost 5.1 - 2018 01 30
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ContentSharingActionsMenuLinksExtensionPoint implements ExtensionPoint
{
	const EXTENSION_POINT = 'content_sharing_actions_menu_links';

	private array $content_sharing_actions_menu_links = [];

	public function __construct(array $content_sharing_actions_menu_links)
	{
		$this->content_sharing_actions_menu_links = $content_sharing_actions_menu_links;
	}

	public function get_content_sharing_actions_menu_links(): void
	{
		$this->content_sharing_actions_menu_links;
	}
}
?>
