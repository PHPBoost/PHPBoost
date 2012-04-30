<?php
/*##################################################
 *                                ArticlesConfig.class.php
 *                            -------------------
 *   begin                : March 6, 2012
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

class ArticlesConfig extends AbstractConfigData
{
	const NBR_ARTICLES_MAX = 'nbr_articles_max';
	const NBR_CAT_MAX = 'nbr_cat_max';
	const NBR_COLUMNS = 'nbr_columns';
	const NOTE_MAX = 'note_max';
	const AUTHORIZATIONS = 'authorisations';
	
	public function get_nbr_articles_max()
	{
		return $this->get_property(self::NBR_ARTICLES_MAX);
	}

	public function set_nbr_articles_max($value) 
	{
		$this->set_property(self::NBR_ARTICLES_MAX, $value);
	}

	public function get_nbr_cat_max()
	{
		return $this->get_property(self::NBR_CAT_MAX);
	}

	public function set_nbr_cat_max($value) 
	{
		$this->set_property(self::NBR_CAT_MAX, $value);
	}

	public function get_nbr_columns()
	{
		return $this->get_property(self::NBR_COLUMNS);
	}

	public function set_nbr_columns($value) 
	{
		$this->set_property(self::NBR_COLUMNS, $value);
	}	
	
	public function get_note_max()
	{
		return $this->get_property(self::NOTE_MAX);
	}

	public function set_note_max($value) 
	{
		$this->set_property(self::NOTE_MAX, $value);
	}
	
	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(Array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}
	
	public function get_default_values()
	{
		return array(
			self::NBR_ARTICLES_MAX => 10,
			self::NBR_CAT_MAX => 10,
			self::NBR_COLUMNS => 3,
			self::NOTE_MAX => 5,
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 3, 'r1' => 7)
		);
	}

	/**
	 * Returns the configuration.
	 * @return ArticlesConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'articles', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('articles', self::load(), 'config');
	}
}
?>