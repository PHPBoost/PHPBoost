<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 21
 * @since       PHPBoost 6.0 - 2020 05 14
*/

class PollConfig extends DefaultRichModuleConfig
{
	const MINI_MODULE_ACTIVATING = 'mini_module_activating';
	const MINI_MODULE_SELECTED_ITEMS = 'mini_module_selected_items';
	const COOKIE_NAME = 'cookie_name';
	const COOKIE_LENGHT = 'cookie_lenght';
	
	const VOTE_AUTHORIZATIONS = 32;
	const DISPLAY_VOTES_RESULT_AUTHORIZATIONS = 64;

	public function get_additional_default_values()
	{
		return array(
			self::MINI_MODULE_ACTIVATING => false,
			self::MINI_MODULE_SELECTED_ITEMS => array(),
			self::COOKIE_NAME => 'poll',
			self::COOKIE_LENGHT => 30, //Cookie duration is 30 days per default
			self::AUTHORIZATIONS => array('r-1' => 97, 'r0' => 103, 'r1' => 109)
		);
	}

	public function get_cookie_lenght_in_seconds()
	{
		return $this->get_property(self::COOKIE_LENGHT) * (3600 * 24);
	}
	
	public function set_mini_module_selected_items(array $array)
	{
		$set_array = array();
		foreach ($array as $value)
		{
			$set_array[] = (string) $value;
		}
		return $this->set_property(self::MINI_MODULE_SELECTED_ITEMS, $set_array);
	}
}
?>
