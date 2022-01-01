<?php
/**
 * @package     PHPBoost
 * @subpackage  Config
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 07 07
*/

class ContentFormattingConfig extends AbstractConfigData
{
	const DEFAULT_EDITOR = 'default_editor';
	const FORBIDDEN_TAGS = 'forbidden_tags';
	const HTML_TAG_AUTH = 'html_tag_auth';

	public function get_default_editor()
	{
		return $this->get_property(self::DEFAULT_EDITOR);
	}

	public function set_default_editor($editor)
	{
		$this->set_property(self::DEFAULT_EDITOR, $editor);
	}

	public function get_forbidden_tags()
	{
		return $this->get_property(self::FORBIDDEN_TAGS);
	}

	public function set_forbidden_tags(array $forbidden_tags)
	{
		$this->set_property(self::FORBIDDEN_TAGS, $forbidden_tags);
	}

	public function get_html_tag_auth()
	{
		return $this->get_property(self::HTML_TAG_AUTH);
	}

	public function set_html_tag_auth(array $auth)
	{
		$this->set_property(self::HTML_TAG_AUTH, $auth);
	}

	protected function get_default_values()
	{
		return array(
			self::DEFAULT_EDITOR => 'BBCode',
			self::FORBIDDEN_TAGS => array(),
			self::HTML_TAG_AUTH => array()
		);
	}

	/**
	 * Returns the configuration.
	 * @return ContentFormattingConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'content-formatting');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'content-formatting');
	}
}
?>
