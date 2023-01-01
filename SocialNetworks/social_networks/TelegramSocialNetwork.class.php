<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 09 20
 * @since       PHPBoost 5.1 - 2018 09 19
*/

class TelegramSocialNetwork extends AbstractSocialNetwork
{
	const SOCIAL_NETWORK_ID = 'telegram';

	public function get_name()
	{
		return 'Telegram';
	}

	public function get_icon_name()
	{
		return self::SOCIAL_NETWORK_ID . '-plane';
	}

	public function get_mobile_content_sharing_url()
	{
		return 'tg://msg?url=' . (rawurlencode(HOST . REWRITED_SCRIPT)) . (defined('TITLE') ? '&text=' . rawurlencode(TITLE) : '');
	}

	public function is_mobile_only()
	{
		return true;
	}
}
?>
