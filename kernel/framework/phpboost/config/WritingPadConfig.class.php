<?php
/*##################################################
 *		                  WritingPadConfig.class.php
 *                            -------------------
 *   begin                : October 17, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * This class contains the content of the writing pad which is on the home page 
 * of the administration panel.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
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