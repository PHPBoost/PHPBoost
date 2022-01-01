<?php
/**
 * @package     PHPBoost
 * @subpackage  Config
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2012 01 20
*/

class LangsConfig extends AbstractConfigData
{
	private static $langs_property = 'langs';

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::$langs_property => array()
		);
	}

	public function get_langs()
	{
		return $this->get_property(self::$langs_property);
	}

	public function get_lang($id)
	{
		$langs = $this->get_property(self::$langs_property);
		if (array_key_exists($id, $langs))
		{
			return $langs[$id];
		}
		return null;
	}

    public function set_langs(array $langs)
    {
        $this->set_property(self::$langs_property, $langs);
    }

    public function add_lang(Lang $lang)
    {
        $langs = $this->get_property(self::$langs_property);
        $langs[$lang->get_id()] = $lang;
        $this->set_property(self::$langs_property, $langs);
    }

    public function remove_lang(Lang $lang)
    {
        $langs = $this->get_property(self::$langs_property);
        unset($langs[$lang->get_id()]);
        $this->set_property(self::$langs_property, $langs);
    }

    public function remove_lang_by_id($id)
    {
        $langs = $this->get_property(self::$langs_property);
        unset($langs[$id]);
        $this->set_property(self::$langs_property, $langs);
    }

	public function update(Lang $lang)
	{
		$langs = $this->get_property(self::$langs_property);
        $langs[$lang->get_id()] = $lang;

        $this->set_property(self::$langs_property, $langs);
	}

	/**
	 * Loads and returns the langs cached data.
	 * @return LangsConfig The cached data
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'langs');
	}

	/**
	 * Invalidates the current langs cached data.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'langs');
	}
}
?>
