<?php
/*##################################################
 *		                   NewsConfig.class.php
 *                            -------------------
 *   begin                : February 13, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class NewsConfig extends AbstractConfigData
{
	const NUMBER_NEWS_PER_PAGE = 'number_news_per_page';
	const NUMBER_COLUMNS_DISPLAY_NEWS = 'number_columns_display_news';
	
	const COMMENTS_ENABLED = 'comments_enabled';
	const NEWS_SUGGESTIONS_ENABLED = 'news_suggestions_enabled';
	
	const EDITO_ENABLED = 'edito_enabled';
	const EDITO_TITLE = 'edito_title';
	const EDITO_CONTENTS = 'edito_contents';
	
	const DISPLAY_TYPE = 'display_type';
	const DISPLAY_BLOCK = 'block';
	const DISPLAY_LIST = 'list';
	
	const AUTHORIZATIONS = 'authorizations';
	
	public function get_number_news_per_page()
	{
		return $this->get_property(self::NUMBER_NEWS_PER_PAGE);
	}

	public function set_number_news_per_page($number_news_per_page)
	{
		$this->set_property(self::NUMBER_NEWS_PER_PAGE, $number_news_per_page);
	}
	
	public function get_number_columns_display_news()
	{
		return $this->get_property(self::NUMBER_COLUMNS_DISPLAY_NEWS);
	}

	public function set_number_columns_display_news($number_columns_display_news)
	{
		$this->set_property(self::NUMBER_COLUMNS_DISPLAY_NEWS, $number_columns_display_news);
	}

	public function get_comments_enabled()
	{
		return $this->get_property(self::COMMENTS_ENABLED);
	}

	public function set_comments_enabled($comments_enabled)
	{
		$this->set_property(self::COMMENTS_ENABLED, $comments_enabled);
	}
	
	public function get_news_suggestions_enabled()
	{
		return $this->get_property(self::NEWS_SUGGESTIONS_ENABLED);
	}

	public function set_news_suggestions_enabled($news_suggestions_enabled)
	{
		$this->set_property(self::NEWS_SUGGESTIONS_ENABLED, $news_suggestions_enabled);
	}
	
	public function get_display_type()
	{
		return $this->get_property(self::DISPLAY_TYPE);
	}

	public function set_display_type($display_type)
	{
		$this->set_property(self::DISPLAY_TYPE, $display_type);
	}
	
	public function get_edito_enabled()
	{
		return $this->get_property(self::EDITO_ENABLED);
	}

	public function set_edito_enabled($edito_enabled)
	{
		$this->set_property(self::EDITO_ENABLED, $edito_enabled);
	}
	
	public function get_edito_title()
	{
		return $this->get_property(self::EDITO_TITLE);
	}

	public function set_edito_title($edito_title)
	{
		$this->set_property(self::EDITO_TITLE, $edito_title);
	}
	
	public function get_edito_contents()
	{
		return $this->get_property(self::EDITO_CONTENTS);
	}

	public function set_edito_contents($edito_contents)
	{
		$this->set_property(self::EDITO_CONTENTS, $edito_contents);
	}
		
	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(Array $authorizations)
	{
		$this->set_property(self::AUTHORIZATIONS, $authorizations);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::NUMBER_NEWS_PER_PAGE => 10,
			self::NUMBER_COLUMNS_DISPLAY_NEWS => 1,
			self::COMMENTS_ENABLED => true,
			self::NEWS_SUGGESTIONS_ENABLED => true,
			self::EDITO_ENABLED => true,
			self::EDITO_TITLE => 'titre',
			self::EDITO_CONTENTS => 'contenu',
			self::DISPLAY_TYPE => self::DISPLAY_BLOCK,
			self::AUTHORIZATIONS => array()
		);
	}

	/**
	 * Returns the configuration.
	 * @return NewsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'news', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('news', self::load(), 'config');
	}
}
?>