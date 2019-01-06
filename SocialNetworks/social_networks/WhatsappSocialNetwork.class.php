<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 09 20
 * @since   	PHPBoost 5.1 - 2018 09 19
*/

class WhatsappSocialNetwork extends AbstractSocialNetwork
{
	const SOCIAL_NETWORK_ID = 'whatsapp';

	public function get_name()
	{
		return 'Whatsapp';
	}

	public function get_mobile_content_sharing_url()
	{
		return 'whatsapp://send?text=' . (rawurlencode((defined('TITLE') ? TITLE . ' ' : '') . HOST . REWRITED_SCRIPT));
	}

	public function is_mobile_only()
	{
		return true;
	}
}
?>
