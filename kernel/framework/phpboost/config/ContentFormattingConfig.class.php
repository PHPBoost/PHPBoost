<?php
/*##################################################
 *		                   ContentFormattingConfig.class.php
 *                            -------------------
 *   begin                : July 7, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU ContentFormatting Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU ContentFormatting Public License for more details.
 *
 * You should have received a copy of the GNU ContentFormatting Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Benoit Sautel <ben.popeye@phpboost.com>
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