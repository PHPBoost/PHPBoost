<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 09 20
 * @since       PHPBoost 5.1 - 2018 05 02
*/

class ViadeoSocialNetwork extends AbstractSocialNetwork
{
	const SOCIAL_NETWORK_ID = 'viadeo';

	public function get_name()
	{
		return 'Viadeo';
	}

	public function get_content_sharing_url()
	{
		return 'https://partners.viadeo.com/share?url=' . (rawurlencode(HOST . REWRITED_SCRIPT));
	}
}
?>
