<?php
/**
 * @package     PHPBoost
 * @subpackage  Config
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 18
 * @since       PHPBoost 3.0 - 2012 01 20
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class LangsConfig extends AbstractConfigData
{
	private static string $langs_property = 'langs';

	public function get_default_values(): array
	{
		return [
			self::$langs_property => []
		];
	}

	public function get_langs(): array
	{
		return $this->get_property(self::$langs_property);
	}

    public function set_langs(array $langs): void
    {
        $this->set_property(self::$langs_property, $langs);
    }

	public function get_lang(string $id): ?Lang
	{
		$langs = $this->get_property(self::$langs_property);
		if (array_key_exists($id, $langs))
		{
			return $langs[$id];
		}
		return null;
	}

    public function add_lang(Lang $lang): void
    {
        $langs = $this->get_property(self::$langs_property);
        $langs[$lang->get_id()] = $lang;
        $this->set_property(self::$langs_property, $langs);
    }

    public function remove_lang(Lang $lang): void
    {
        $langs = $this->get_property(self::$langs_property);
        unset($langs[$lang->get_id()]);
        $this->set_property(self::$langs_property, $langs);
    }

    public function remove_lang_by_id(string $id): void
    {
        $langs = $this->get_property(self::$langs_property);
        unset($langs[$id]);
        $this->set_property(self::$langs_property, $langs);
    }

	public function update(Lang $lang): void
	{
		$langs = $this->get_property(self::$langs_property);
        $langs[$lang->get_id()] = $lang;

        $this->set_property(self::$langs_property, $langs);
	}

	/**
	 * Loads and returns the langs cached data.
	 * @return LangsConfig The cached data
	 */
	public static function load(): LangsConfig
	{
		return ConfigManager::load(self::class, 'kernel', 'langs');
	}

	/**
	 * Invalidates the current langs cached data.
	 */
	public static function save(): void
	{
		ConfigManager::save('kernel', self::load(), 'langs');
	}
}
?>
