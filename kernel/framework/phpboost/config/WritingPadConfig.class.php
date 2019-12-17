<?php
/**
 * This class contains the content of the writing pad which is on the home page
 * of the administration panel.
 * @package     PHPBoost
 * @subpackage  Config
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 17
*/

class WritingPadConfig extends AbstractConfigData
{
	/**
	 * Sets the content of the writing pad
	 * @param string $content The content
	 */
	public function set_content($content)
	{
		$this->set_property('content', $content);
	}

	/**
	 * Returns the content of the writing pad
	 * @return string its content
	 */
	public function get_content()
	{
		try
		{
			return $this->get_property('content');
		}
		catch(PropertyNotFoundException $ex)
		{
			return '';
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			'content' => LangLoader::get_message('writing_pad_explain', 'admin')
		);
	}

	/**
	 * Returs the configuration.
	 * @return WritingPadConfig The configuration
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'writing-pad');
	}

	/**
	 * Saves the configuration
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'writing-pad');
	}
}
?>
