<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 20
 * @since       PHPBoost 4.0 - 2014 05 09
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class QuestionCaptchaConfig extends AbstractConfigData
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
		$items = array();

		$lang = LangLoader::get('install', 'QuestionCaptcha');

		$item = new QuestionCaptchaItem();
		$item->set_label($lang['item.1.label']);
		$item->set_answers(explode(';', $lang['item.1.answers']));

		$items[1] = $item->get_properties();

		$item = new QuestionCaptchaItem();
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
		return array(
			self::ITEMS => self::init_items_array()
		);
	}

	/**
	 * Returns the configuration.
	 * @return QuestionCaptchaConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'question-captcha', 'config');
	}

	/**
	 * Saves the configuration in the database.
	 */
	public static function save()
	{
		ConfigManager::save('question-captcha', self::load(), 'config');
	}
}
?>
