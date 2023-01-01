<?php
/**
 * @package     PHPBoost
 * @subpackage  Langs
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 25
 * @since       PHPBoost 3.0 - 2012 01 19
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class LangsManager
{
	private static $error = null;

	public static function get_installed_langs_map()
	{
		return LangsConfig::load()->get_langs();
	}

	/**
	 * @return Lang[string] the Lang map (name => lang) of the installed langs (activated or not)
	 * sorted by name
	 */
	public static function get_installed_langs_map_sorted_by_localized_name()
	{
		$langs = self::get_installed_langs_map();
		try {
			uasort($langs, array(__CLASS__, 'callback_sort_langs_by_name'));
		} catch (IOException $ex) {
		}
		return $langs;
	}

	public static function get_activated_langs_map()
	{
		$activated_langs = array();
		foreach (LangsConfig::load()->get_langs() as $lang) {
			if ($lang->is_activated()) {
				$activated_langs[$lang->get_id()] = $lang;
			}
		}
		return $activated_langs;
	}

	/**
	 * @return Lang[string] the Langs map (name => lang) of the installed langs (and activated)
	 * sorted by name
	 */
	public static function get_activated_langs_map_sorted_by_localized_name()
	{
		$langs = self::get_activated_langs_map();
		try {
			uasort($langs, array(__CLASS__, 'callback_sort_langs_by_name'));
		} catch (IOException $ex) {
		}
		return $langs;
	}

	public static function get_activated_and_authorized_langs_map()
	{
		$langs = array();
		foreach (LangsConfig::load()->get_langs() as $lang) {
			if ($lang->is_activated() && $lang->check_auth()) {
				$langs[$lang->get_id()] = $lang;
			}
		}
		return $langs;
	}

	/**
	 * @return Lang[string] the Langs map (name => lang) of the installed langs (and activated and authorized)
	 * sorted by name
	 */
	public static function get_activated_and_authorized_langs_map_sorted_by_localized_name()
	{
		$langs = self::get_activated_and_authorized_langs_map();
		try {
			uasort($langs, array(__CLASS__, 'callback_sort_langs_by_name'));
		} catch (IOException $ex) {
		}
		return $langs;
	}

	public static function callback_sort_langs_by_name(Lang $lang1, Lang $lang2)
	{
		if (TextHelper::strtolower($lang1->get_configuration()->get_name()) > TextHelper::strtolower($lang2->get_configuration()->get_name()))
		{
			return 1;
		}
		return -1;
	}

	public static function get_default_lang()
	{
		return UserAccountsConfig::load()->get_default_lang();
	}

	public static function get_lang($id)
	{
		return LangsConfig::load()->get_lang($id);
	}

	public static function get_lang_existed($id)
	{
		if (LangsConfig::load()->get_lang($id) !== null)
		{
			return true;
		}
		return false;
	}

	public static function install($id, $authorizations = array(), $enable = true)
	{
		if (!empty($id) && !self::get_lang_existed($id))
		{
			$lang = new Lang($id, $authorizations, $enable);
			$configuration = $lang->get_configuration();

			$phpboost_version = GeneralConfig::load()->get_phpboost_major_version();
			if (version_compare($phpboost_version, $configuration->get_compatibility(), '>'))
			{
				self::$error = LangLoader::get_message('warning.misfit.phpboost', 'warning-lang');
			}
			else
			{
				LangsConfig::load()->add_lang($lang);
				LangsConfig::save();
			}
		}
		else if (self::get_lang_existed($id))
		{
			self::$error = LangLoader::get_message('warning.element.already.exists', 'warning-lang');
		}
		else
		{
			self::$error = LangLoader::get_message('warning.process.error', 'warning-lang');
		}
	}

	public static function uninstall($id, $drop_files = false)
	{
		if (!empty($id) && self::get_lang_existed($id))
		{
			$default_lang = self::get_default_lang();
			if (self::get_lang($id)->get_id() !== $default_lang)
			{
				PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, array('locale' => $default_lang),
					'WHERE locale=:old_locale', array('old_locale' => $id
				));

				LangsConfig::load()->remove_lang_by_id($id);
				LangsConfig::save();

				if ($drop_files)
				{
					$folder = new Folder(PATH_TO_ROOT . '/lang/' . $id);
					$folder->delete();
				}
			}
		}
	}

	public static function change_visibility($id, $visibility)
	{
		if (!empty($id) && self::get_lang_existed($id))
		{
			$lang = self::get_lang($id);
			$lang->set_activated($visibility);
			LangsConfig::load()->update($lang);
			LangsConfig::save();
		}
	}

	public static function change_authorizations($id, Array $authorizations)
	{
		if (!empty($id) && self::get_lang_existed($id))
		{
			$lang = self::get_lang($id);
			$lang->set_authorizations($authorizations);
			LangsConfig::load()->update($lang);
			LangsConfig::save();
		}
	}

	public static function change_informations($id, $visibility, Array $authorizations = array())
	{
		if (!empty($id) && self::get_lang_existed($id))
		{
			$lang = self::get_lang($id);
			$lang->set_activated($visibility);

			if (!empty($authorizations))
			{
				$lang->set_authorizations($authorizations);
			}

			LangsConfig::load()->update($lang);
			LangsConfig::save();
		}
	}

	public static function get_error()
	{
		return self::$error;
	}
}
?>
