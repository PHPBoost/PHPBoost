<?php
/*##################################################
 *		                   ArticlesConfig.class.php
 *                            -------------------
 *   begin                : October 15, 2011
 *   copyright            : (C) 2011 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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
	const AUTHORIZATION = 'authorization';
	
	public function get_authorization()
	{
		return $this->get_property(self::AUTHORIZATION);
	}
	
	public function set_authorization(Array $array)
	{
		$this->set_property(self::AUTHORIZATION, $array);
	}
	
	public function get_max_nbr_articles_max()
	{
		return $this->get_property('nbr_articles_max');
	}
	
	public function set_max_nbr_articles_max($nbr_articles) 
	{
		$this->set_property('nbr_articles_max', $nbr_articles);
	}
	
	public function get_max_nbr_category_max()
	{
		return $this->get_property('nbr_cat_max');
	}
	
	public function set_max_nbr_vategory_max($nbr_category) 
	{
		$this->set_property('nbr_cat_max', $nbr_category);
	}
	
	public function get_number_columns()
	{
		return $this->get_property('nbr_column');
	}
	
	public function set_number_columns($nbr_cols) 
	{
		$this->set_property('nbr_column', $nbr_cols);
	}
	
	public function get_note_max()
	{
		return $this->get_property('note_max');
	}
	
	public function set_note_max($note) 
	{
		$this->set_property('note_max', $note);
	}
	
	public function get_default_category_template()
	{
		return $this->get_property('tpl_cat');
	}
	
	public function set_default_category_template($tpl) 
	{
		$this->set_property('tpl_cat', $tpl);
	}
	
	public function get_number_displayed_articles_mini()
	{
		return $this->get_property('nbr_articles');
	}
	
	public function set_number_displayed_articles_mini($nbr_articles) 
	{
		$this->set_property('nbr_articles', $nbr_articles);
	}
	
	public function get_sorting_type_mini()
	{
		return $this->get_property('mini_type');
	}
	
	public function set_sorting_type_mini($type) 
	{
		$this->set_property('mini_type', $type);
	}
	
	public function get_default_values()
	{
		return array(
			self::AUTHORIZATION => array('r-1' => 1, 'r0' => 3, 'r1' => 7),
			'nbr_articles_max' => 10,
			'nbr_cat_max' => 10,
			'nbr_column' => 3,
			'note_max' => 5,
			'tpl_cat' => 'articles_cat.tpl',
			'nbr_articles' => 5,
			'mini_type' => 'view'
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return ArticlesConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'module', 'articles-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('module', self::load(), 'articles-config');
	}
}
?>