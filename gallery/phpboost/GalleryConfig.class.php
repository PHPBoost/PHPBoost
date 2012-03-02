<?php
/*##################################################
 *		                  GalleryConfig.class.php
 *                            -------------------
 *   begin                : March 2, 2012
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

class GalleryConfig extends AbstractConfigData
{
	const WIDTH = 'width';
	const HEIGHT = 'height';
	const WIDTH_MAX = 'width_max';
	const HEIGHT_MAX = 'height_max';
	const WEIGHT_MAX = 'weight_max';
	const QUALITY = 'quality';
	const TRANS = 'trans';
	const LOGO = 'logo';
	const ACTIV_LOGO = 'activ_logo';
	const D_WIDTH = 'd_width';
	const D_HEIGHT = 'd_height';
	const NBR_COLUMNS = 'nbr_columns';
	const NBR_PICS_MAX = 'nbr_pics_max';
	const NOTE_MAX = 'note_max';
	const ACTIV_TITLE = 'activ_title';
	const ACTIV_COM = 'activ_com';
	const ACTIV_NOTE = 'activ_note';
	const DISPLAY_NBR_NOTE = 'display_nbrnote';
	const ACTIV_VIEW = 'activ_view';
	const ACTIV_USER = 'activ_user';
	const LIMIT_MEMBER = 'limit_member';
	const LIMIT_MODO = 'limit_modo';
	const DISPLAY_PICS = 'display_pics';
	const SCROLL_TYPE = 'scroll_type';
	const NBR_PICS_MINI = 'nbr_pics_mini';
	const SPEED_MINI_PICS = 'speed_mini_pics';
	const AUTHORIZATION = 'auth_root';

	public function get_width()
	{
		return $this->get_property(self::WIDTH);
	}
	
	public function set_width($value) 
	{
		$this->set_property(self::WIDTH, $value);
	}
	
	public function get_height()
	{
		return $this->get_property(self::HEIGHT);
	}
	
	public function set_height($value) 
	{
		$this->set_property(self::HEIGHT, $value);
	}
	
	public function get_width_max()
	{
		return $this->get_property(self::WIDTH_MAX);
	}
	
	public function set_width_max($value) 
	{
		$this->set_property(self::WIDTH_MAX, $value);
	}
	
	public function get_height_max()
	{
		return $this->get_property(self::HEIGHT_MAX);
	}
	
	public function set_height_max($value) 
	{
		$this->set_property(self::HEIGHT_MAX, $value);
	}
	
	public function get_weight_max()
	{
		return $this->get_property(self::WEIGHT_MAX);
	}
	
	public function set_weight_max($value) 
	{
		$this->set_property(self::WEIGHT_MAX, $value);
	}
	
	public function get_quality()
	{
		return $this->get_property(self::QUALITY);
	}
	
	public function set_quality($value) 
	{
		$this->set_property(self::QUALITY, $value);
	}
	
	public function get_trans()
	{
		return $this->get_property(self::TRANS);
	}
	
	public function set_trans($value) 
	{
		$this->set_property(self::TRANS, $value);
	}
	
	public function get_logo()
	{
		return $this->get_property(self::LOGO);
	}
	
	public function set_logo($value) 
	{
		$this->set_property(self::LOGO, $value);
	}
	
	public function get_activ_logo()
	{
		return $this->get_property(self::ACTIV_LOGO);
	}
	
	public function set_activ_logo($value) 
	{
		$this->set_property(self::ACTIV_LOGO, $value);
	}
	
	public function get_d_width()
	{
		return $this->get_property(self::D_WIDTH);
	}
	
	public function set_d_width($value) 
	{
		$this->set_property(self::D_WIDTH, $value);
	}
	
	public function get_d_height()
	{
		return $this->get_property(self::D_HEIGHT);
	}
	
	public function set_d_height($value) 
	{
		$this->set_property(self::D_HEIGHT, $value);
	}
	
	public function get_nbr_columns()
	{
		return $this->get_property(self::NBR_COLUMNS);
	}
	
	public function set_nbr_columns($value) 
	{
		$this->set_property(self::NBR_COLUMNS, $value);
	}
	
	public function get_nbr_pics_max()
	{
		return $this->get_property(self::NBR_PICS_MAX);
	}
	
	public function set_nbr_pics_max($value) 
	{
		$this->set_property(self::NBR_PICS_MAX, $value);
	}
	
	public function get_note_max()
	{
		return $this->get_property(self::NOTE_MAX);
	}
	
	public function set_note_max($value) 
	{
		$this->set_property(self::NOTE_MAX, $value);
	}
	
	public function get_activ_title()
	{
		return $this->get_property(self::ACTIV_TITLE);
	}
	
	public function set_activ_title($value) 
	{
		$this->set_property(self::ACTIV_TITLE, $value);
	}
	
	public function get_activ_com()
	{
		return $this->get_property(self::ACTIV_COM);
	}
	
	public function set_activ_com($value) 
	{
		$this->set_property(self::ACTIV_COM, $value);
	}
	
	public function get_activ_note()
	{
		return $this->get_property(self::ACTIV_NOTE);
	}
	
	public function set_activ_note($value) 
	{
		$this->set_property(self::ACTIV_NOTE, $value);
	}
	
	public function get_display_nbr_note()
	{
		return $this->get_property(self::DISPLAY_NBR_NOTE);
	}
	
	public function set_display_nbr_note($value) 
	{
		$this->set_property(self::DISPLAY_NBR_NOTE, $value);
	}
	
	public function get_activ_view()
	{
		return $this->get_property(self::ACTIV_VIEW);
	}
	
	public function set_activ_view($value) 
	{
		$this->set_property(self::ACTIV_VIEW, $value);
	}
	
	public function get_activ_user()
	{
		return $this->get_property(self::ACTIV_USER);
	}
	
	public function set_activ_user($value) 
	{
		$this->set_property(self::ACTIV_USER, $value);
	}
	
	public function get_limit_member()
	{
		return $this->get_property(self::LIMIT_MEMBER);
	}
	
	public function set_limit_member($value) 
	{
		$this->set_property(self::LIMIT_MEMBER, $value);
	}
	
	public function get_limit_modo()
	{
		return $this->get_property(self::LIMIT_MODO);
	}
	
	public function set_limit_modo($value) 
	{
		$this->set_property(self::LIMIT_MODO, $value);
	}
	
	public function get_display_pics()
	{
		return $this->get_property(self::DISPLAY_PICS);
	}
	
	public function set_display_pics($value) 
	{
		$this->set_property(self::DISPLAY_PICS, $value);
	}
	
	public function get_scroll_type()
	{
		return $this->get_property(self::SCROLL_TYPE);
	}
	
	public function set_scroll_type($value) 
	{
		$this->set_property(self::SCROLL_TYPE, $value);
	}
	
	public function get_nbr_pics_mini()
	{
		return $this->get_property(self::NBR_PICS_MINI);
	}
	
	public function set_nbr_pics_mini($value) 
	{
		$this->set_property(self::NBR_PICS_MINI, $value);
	}
	
	public function get_speed_mini_pics()
	{
		return $this->get_property(self::SPEED_MINI_PICS);
	}
	
	public function set_speed_mini_pics($value) 
	{
		$this->set_property(self::SPEED_MINI_PICS, $value);
	}
	
	public function get_authorization()
	{
		return $this->get_property(self::AUTHORIZATION);
	}
	
	public function set_authorization(Array $array)
	{
		$this->set_property(self::AUTHORIZATION, $array);
	}
	
	public function get_default_values()
	{
		return array(
			self::WIDTH => 150,
			self::HEIGHT => 150,
			self::WIDTH_MAX => 800,
			self::HEIGHT_MAX => 600,
			self::WEIGHT_MAX => 1024,
			self::QUALITY => 80,
			self::TRANS => 40,
			self::LOGO => 'logo.jpg',
			self::ACTIV_LOGO => 1,
			self::D_WIDTH => 5,
			self::D_HEIGHT => 5,
			self::NBR_COLUMNS => 4,
			self::NBR_PICS_MAX => 16,
			self::NOTE_MAX => 5,
			self::ACTIV_TITLE => 1,
			self::ACTIV_COM => 1,
			self::ACTIV_NOTE => 1,
			self::DISPLAY_NBR_NOTE => 1,
			self::ACTIV_VIEW => 1,
			self::ACTIV_USER => 1,
			self::LIMIT_MEMBER => 10,
			self::LIMIT_MODO => 25,
			self::DISPLAY_PICS => 3,
			self::SCROLL_TYPE => 1,
			self::NBR_PICS_MINI => 6,
			self::SPEED_MINI_PICS => 6,
			self::AUTHORIZATION => array('r-1' => 1, 'r0' => 3, 'r1' => 7, 'r2' => 7)
		);
	}

	/**
	 * Returns the configuration.
	 * @return GalleryConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'module-gallery', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('module-Gallery', self::load(), 'config');
	}
}
?>