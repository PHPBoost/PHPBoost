<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 02 20
 * @since       PHPBoost 6.0 - 2025 02 20
*/

class BlueskySocialNetwork extends AbstractSocialNetwork
{
	const SOCIAL_NETWORK_ID = 'bluesky';

	public function get_name()
	{
		return 'Bluesky';
	}

	public function get_content_sharing_url()
	{
		return 'https://bsky.app/intent/compose?text=' . (rawurlencode(HOST . REWRITED_SCRIPT)) . (defined('TITLE') ? '&n=' . rawurlencode(TITLE) : '');
	}
}
?>
