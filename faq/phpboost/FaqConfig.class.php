<?php
/*##################################################
 *		                  FaqConfig.class.php
 *                            -------------------
 *   begin                : September 25, 2011
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

/**
 * This class contains the configuration of the faq module.
 * @author Patrick Dubeau <daaxwizeman@gmail.com>
 *
 */
class FaqConfig extends AbstractConfigData
{
	const AUTHORIZATION = 'authorization';
	
	public function get_faq_name()
	{
		return $this->get_property('faq_name');
	}
	
	public function set_faq_name($name) 
	{
		$this->set_property('faq_name', $name);
	}
	
	public function get_number_columns()
	{
		return $this->get_property('num_cols');
	}
	
	public function set_number_columns($num_cols) 
	{
		$this->set_property('num_cols', $num_cols);
	}
	
	public function get_display_mode()
	{
		return $this->get_property('display_mode');
	}
	
	public function set_display_mode($mode) 
	{
		$this->set_property('display_mode', $mode);
	}
	
	public function get_authorization()
	{
		return $this->get_property(self::AUTHORIZATION);
	}
	
	public function set_authorization(Array $array)
	{
		$this->set_property(self::AUTHORIZATION, $array);
	}
	
	//Config de la catégorie racine
	public function get_root_cat_display_mode()
	{
		return $this->get_property('root_cat_display_mode');
	}
	
	public function set_root_cat_display_mode($num_mode) 
	{
		$this->set_property('root_cat_display_mode', $num_mode);
	}
	
	public function get_root_cat_description()
	{
		return $this->get_property('root_cat_description');
	}
	
	public function set_root_cat_description($desc) 
	{
		$this->set_property('root_cat_description', $desc);
	}
	
	public function get_default_values()
	{
		return array(
			self::AUTHORIZATION => array('r-1' => 1, 'r0' => 1),
			'faq_name' => 'FAQ PHPBoost',
			'num_cols' => 4,
			'display_mode' => 'inline',
			'root_cat_display_mode' => 0,
			'root_cat_description' => 'Bienvenue dans la FAQ !<br /><br /> 2 catégories et quelques questions ont été créées pour vous montrer comment fonctionne ce module. Voici quelques conseils pour bien débuter sur ce module.
			<br /><br /> 
			<ul class="bb_ul"><li class="bb_li">Pour configurer votre module, rendez vous dans l\'<a href="/faq/admin_faq.php">administration du module</a> 
			</li><li class="bb_li">Pour créer des catégories, <a href="/faq/admin_faq_cats.php?new=1">cliquez ici</a> 
			</li><li class="bb_li">Pour créer des questions, rendez vous dans la catégorie souhaitée et cliquez sur \'Gérer la catégorie\' puis \'ajout\'</li></ul>
			<br /><br />Pour personnaliser l\'accueil de ce module, <a href="/faq/management.php">cliquez ici</a>
			<br />Pour en savoir plus, n\'hésitez pas à consulter la documentation du module sur le site de PHPBoost.'
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return FaqConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'module-faq', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('module-faq', self::load(), 'config');
	}
}
?>