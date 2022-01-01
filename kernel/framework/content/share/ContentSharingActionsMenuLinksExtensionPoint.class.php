<?php
/**
 * @package     Content
 * @subpackage  Share
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 02 04
 * @since       PHPBoost 5.1 - 2018 01 30
*/

class ContentSharingActionsMenuLinksExtensionPoint implements ExtensionPoint
{
	const EXTENSION_POINT = 'content_sharing_actions_menu_links';

	private $content_sharing_actions_menu_links = array();

	public function __construct($content_sharing_actions_menu_links)
	{
		$this->content_sharing_actions_menu_links = $content_sharing_actions_menu_links;
	}

	public function get_content_sharing_actions_menu_links()
	{
		$this->content_sharing_actions_menu_links;
	}
}
?>
