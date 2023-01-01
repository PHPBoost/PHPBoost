<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 09 20
 * @since       PHPBoost 5.1 - 2018 04 19
*/

class BloggerSocialNetwork extends AbstractSocialNetwork
{
	const SOCIAL_NETWORK_ID = 'blogger';

	public function get_name()
	{
		return 'Blogger';
	}

	public function get_icon_name()
	{
		return self::SOCIAL_NETWORK_ID . '-b';
	}

	public function get_content_sharing_url()
	{
		return 'https://www.blogger.com/blog-this.g?u=' . (rawurlencode(HOST . REWRITED_SCRIPT)) . (defined('TITLE') ? '&n=' . rawurlencode(TITLE) : '');
	}
}
?>
