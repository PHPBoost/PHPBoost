<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 06 04
 * @since       PHPBoost 4.0 - 2014 05 09
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class QaptchaConfig extends AbstractConfigData
{
	const ITEMS = 'items';

	public function get_items()
	{
		return $this->get_property(self::ITEMS);
	}

	public function set_items(Array $array)
	{
		$this->set_property(self::ITEMS, $array);
	}

	public function count_items()
	{
		return count($this->get_items());
	}

	private function init_items_array()
	{
		$items = [];

		$lang = LangLoader::get('install', 'qaptcha');

		$item = new QaptchaItem();
		$item->set_label($lang['item.1.label']);
		$item->set_answers(explode(';', $lang['item.1.answers']));

		$items[1] = $item->get_properties();

		$item = new QaptchaItem();
		$item->set_label($lang['item.2.label']);
		$item->set_answers(explode(';', $lang['item.2.answers']));

		$items[2] = $item->get_properties();

		return $items;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return [
			self::ITEMS => self::init_items_array()
		];
	}

	/**
	 * Returns the configuration.
	 * @return QaptchaConfig
	 */
	public static function load()
	{
		return ConfigManager::load(self::class, 'qaptcha', 'config');
	}

	/**
	 * Saves the configuration in the database.
	 */
	public static function save()
	{
		ConfigManager::save('qaptcha', self::load(), 'config');
	}
}
?>
