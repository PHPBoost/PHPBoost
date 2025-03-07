<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2025 01 10
 * @since       PHPBoost 6.0 - 2020 05 14
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class PollConfig extends DefaultRichModuleConfig
{
	const MINI_MODULE_SELECTED_ITEMS = 'mini_module_selected_items';
	const COOKIE_NAME = 'cookie_name';
	const COOKIE_LENGHT = 'cookie_lenght';
	
	const VOTE_AUTHORIZATIONS = 64;
	const DISPLAY_VOTES_RESULT_AUTHORIZATIONS = 128;

	public function get_additional_default_values()
	{
		return [
			self::MINI_MODULE_SELECTED_ITEMS => [$this->get_default_mini_module_selected_item_id()],
			self::COOKIE_NAME => 'poll',
			self::COOKIE_LENGHT => 30, //Cookie duration is 30 days per default
			self::AUTHORIZATIONS => ['r-1' => 129, 'r0' => 197, 'r1' => 213]
        ];
	}

	public function get_cookie_lenght_in_seconds()
	{
		return $this->get_property(self::COOKIE_LENGHT) * (3600 * 24);
	}
	
	public function set_mini_module_selected_items(array $array)
	{
		$set_array = [];
		foreach ($array as $value)
		{
			$set_array[] = (string) $value;
		}
		return $this->set_property(self::MINI_MODULE_SELECTED_ITEMS, $set_array);
	}
	
	public function get_default_mini_module_selected_item_id()
	{
		$selected_id = '';
		$install_lang = LangLoader::get('install', self::$module_id);
		$install_item_title = isset($install_lang['items']) && isset($install_lang['items'][0]) && isset($install_lang['items'][0]['item.title']) ? $install_lang['items'][0]['item.title'] : '';

		$db_querier  = PersistenceContext::get_querier();

		if ($install_item_title)
		{
			try 
			{
				$item = $db_querier->select_single_row(PREFIX . self::$module_id, ['id', 'title'], 'WHERE title =:title', ['title' => $install_item_title]);
				$selected_id = $item['id'];
			}
			catch (RowNotFoundException $e) {}
		}

		return (string)$selected_id;
	}
}
?>
