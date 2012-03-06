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
	const TYPE = 'type';
	const ACTIV_COM = 'activ_com';
	const ACTIV_ICON = 'activ_icon';
	const ACTIV_EDITO = 'activ_edito';
	const ACTIV_PAGIN = 'activ_pagin';
	const DISPLAY_DATE = 'display_date';
	const DISPLAY_AUTHOR = 'display_author';
	const PAGINATION_NEWS = 'pagination_news';
	const PAGINATION_ARCH = 'pagination_arch';
	const NBR_COLUMNS = 'nbr_columns';
	const NBR_NEWS= 'nbr_news';
	const AUTHORIZATION = 'global_auth';
	const EDITO_TITLE = 'edito_title';
	const EDITO = 'edito';
	
	public function get_type()
	{
		return $this->get_property(self::TYPE);
	}

	public function set_type($value) 
	{
		$this->set_property(self::TYPE, $value);
	}

	public function get_activ_com()
	{
		return $this->get_property(self::ACTIV_COM);
	}

	public function set_activ_com($value) 
	{
		$this->set_property(self::ACTIV_COM, $value);
	}

	public function get_activ_icon()
	{
		return $this->get_property(self::ACTIV_ICON);
	}

	public function set_activ_icon($value) 
	{
		$this->set_property(self::ACTIV_ICON, $value);
	}
	
	public function get_activ_edito()
	{
		return $this->get_property(self::ACTIV_EDITO);
	}

	public function set_activ_edito($value) 
	{
		$this->set_property(self::ACTIV_EDITO, $value);
	}	
	
	public function get_activ_pagin()
	{
		return $this->get_property(self::ACTIV_PAGIN);
	}

	public function set_activ_pagin($value) 
	{
		$this->set_property(self::ACTIV_PAGIN, $value);
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
	
	public function get_pagination_news()
	{
		return $this->get_property(self::PAGINATION_NEWS);
	}

	public function set_pagination_news($value) 
	{
		$this->set_property(self::PAGINATION_NEWS, $value);
	}	
	
	public function get_pagination_arch()
	{
		return $this->get_property(self::PAGINATION_ARCH);
	}

	public function set_pagination_arch($value) 
	{
		$this->set_property(self::PAGINATION_ARCH, $value);
	}	
	
	public function get_nbr_columns()
	{
		return $this->get_property(self::NBR_COLUMNS);
	}

	public function set_nbr_columns($value) 
	{
		$this->set_property(self::NBR_COLUMNS, $value);
	}	
	
	public function get_nbr_news()
	{
		return $this->get_property(self::NBR_NEWS);
	}

	public function set_nbr_news($value) 
	{
		$this->set_property(self::NBR_NEWS, $value);
	}
	
	public function get_authorization()
	{
		return $this->get_property(self::AUTHORIZATION);
	}

	public function set_authorization(Array $array)
	{
		$this->set_property(self::AUTHORIZATION, $array);
	}
	
	public function get_edito_title()
	{
		return $this->get_property(self::EDITO_TITLE);
	}

	public function set_edito_title($value)
	{
		$this->set_property(self::EDITO_TITLE, $value);
	}
	
	public function get_edito()
	{
			return $this->get_property(self::EDITO);
	}

	public function set_edito($value)
	{
			$this->set_property(self::EDITO, $value);
	}
	
	public function get_default_values()
	{
		return array(
			self::TYPE => 1,
			self::ACTIV_COM => 1,
			self::ACTIV_ICON => 1,
			self::ACTIV_EDITO => 1,
			self::ACTIV_PAGIN => 1,
			self::DISPLAY_DATE => 1,
			self::DISPLAY_AUTHOR => 1,
			self::PAGINATION_NEWS => 6,
			self::PAGINATION_ARCH => 15,
			self::NBR_COLUMNS => 1,
			self::NBR_NEWS => 1,
			self::AUTHORIZATION => array('r-1' => 1, 'r0' => 3, 'r1' => 15),
			self::EDITO_TITLE => 'Bienvenue sur votre site!',
			self::EDITO => 'Vous désirez un site dynamique capable de s\'adapter à vos besoins ? PHPBoost est fait pour vous !<br /><br />Vous pourrez à travers une administration intuitive personnaliser entièrement votre site sans connaissances particulières. En effet ce logiciel a été conçu avec la volonté de le rendre utilisable simplement par le plus grand nombre. Prenez le temps de découvrir toutes les fonctionnalités qui vous sont offertes. En cas de problème une communauté grandissante sera toujours là pour vous épauler !<br /><br />Bienvenue sur votre site !'
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