<?php
/*##################################################
 *                                NewsConfig.class.php
 *                            -------------------
 *   begin                : March 5, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class NewsConfig extends AbstractConfigData
{
	const NEWS_BLOCK_ACTIVATED = 'news_block_activated';
	const COMMENTS_ACTIVATED = 'comments_activated';
	const ICON_ACTIVATED = 'icon_activated';
	const EDITO_ACTIVATED = 'edito_activated';
	const PAGINATION_ACTIVATED = 'pagination_activated';
	const DISPLAY_DATE = 'display_date';
	const DISPLAY_AUTHOR = 'display_author';
	const NEWS_PAGINATION = 'news_pagination';
	const ARCHIVES_PAGINATION = 'archives_pagination';
	const NBR_COLUMNS = 'nbr_columns';
	const NBR_VISIBLE_NEWS= 'nbr_visible_news';
	const AUTHORIZATIONS = 'authorizations';
	const EDITO_TITLE = 'edito_title';
	const EDITO_CONTENT = 'edito_content';
	
	public function get_news_block_activated()
	{
		return $this->get_property(self::NEWS_BLOCK_ACTIVATED);
	}

	public function set_news_block_activated($value) 
	{
		$this->set_property(self::NEWS_BLOCK_ACTIVATED, $value);
	}

	public function get_comments_activated()
	{
		return $this->get_property(self::COMMENTS_ACTIVATED);
	}

	public function set_comments_activated($value) 
	{
		$this->set_property(self::COMMENTS_ACTIVATED, $value);
	}

	public function get_icon_activated()
	{
		return $this->get_property(self::ICON_ACTIVATED);
	}

	public function set_icon_activated($value) 
	{
		$this->set_property(self::ICON_ACTIVATED, $value);
	}
	
	public function get_edito_activated()
	{
		return $this->get_property(self::EDITO_ACTIVATED);
	}

	public function set_edito_activated($value) 
	{
		$this->set_property(self::EDITO_ACTIVATED, $value);
	}	
	
	public function get_pagination_activated()
	{
		return $this->get_property(self::PAGINATION_ACTIVATED);
	}

	public function set_pagination_activated($value) 
	{
		$this->set_property(self::PAGINATION_ACTIVATED, $value);
	}
	
	public function get_display_date()
	{
		return $this->get_property(self::DISPLAY_DATE);
	}

	public function set_display_date($value) 
	{
		$this->set_property(self::DISPLAY_DATE, $value);
	}

	public function get_display_author()
	{
		return $this->get_property(self::DISPLAY_AUTHOR);
	}

	public function set_display_author($value) 
	{
		$this->set_property(self::DISPLAY_AUTHOR, $value);
	}
	
	public function get_news_pagination()
	{
		return $this->get_property(self::NEWS_PAGINATION);
	}

	public function set_news_pagination($value) 
	{
		$this->set_property(self::NEWS_PAGINATION, $value);
	}	
	
	public function get_archives_pagination()
	{
		return $this->get_property(self::ARCHIVES_PAGINATION);
	}

	public function set_archives_pagination($value) 
	{
		$this->set_property(self::ARCHIVES_PAGINATION, $value);
	}	
	
	public function get_nbr_columns()
	{
		return $this->get_property(self::NBR_COLUMNS);
	}

	public function set_nbr_columns($value) 
	{
		$this->set_property(self::NBR_COLUMNS, $value);
	}	
	
	public function get_nbr_visible_news()
	{
		return $this->get_property(self::NBR_VISIBLE_NEWS);
	}

	public function set_nbr_visible_news($value) 
	{
		$this->set_property(self::NBR_VISIBLE_NEWS, $value);
	}
	
	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(Array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}
	
	public function get_edito_title()
	{
		return $this->get_property(self::EDITO_TITLE);
	}

	public function set_edito_title($value)
	{
		$this->set_property(self::EDITO_TITLE, $value);
	}
	
	public function get_edito_content()
	{
		return $this->get_property(self::EDITO_CONTENT);
	}

	public function set_edito_content($value)
	{
		$this->set_property(self::EDITO_CONTENT, $value);
	}
	
	public function get_default_values()
	{
		return array(
			self::NEWS_BLOCK_ACTIVATED => true,
			self::COMMENTS_ACTIVATED => true,
			self::ICON_ACTIVATED => true,
			self::EDITO_ACTIVATED => true,
			self::PAGINATION_ACTIVATED => true,
			self::DISPLAY_DATE => true,
			self::DISPLAY_AUTHOR => true,
			self::NEWS_PAGINATION => 6,
			self::ARCHIVES_PAGINATION => 15,
			self::NBR_COLUMNS => 1,
			self::NBR_VISIBLE_NEWS => 1,
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 3, 'r1' => 15),
			self::EDITO_TITLE => LangLoader::get_message('news.config.edito_title', 'news_config', 'news'),
			self::EDITO_CONTENT => LangLoader::get_message('news.config.edito_content', 'news_config', 'news')
		);
	}

	/**
	 * Returns the configuration.
	 * @return NewsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'module', 'news-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('module', self::load(), 'news-config');
	}
}
?>