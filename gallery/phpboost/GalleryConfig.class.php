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
	const TRANSPARENCY = 'transparency';
	const LOGO = 'logo';
	const LOGO_ACTIVATED = 'logo_activated';
	const D_WIDTH = 'd_width';
	const D_HEIGHT = 'd_height';
	const NBR_COLUMNS = 'nbr_columns';
	const NBR_PICS_MAX = 'nbr_pics_max';
	const NOTE_MAX = 'note_max';
	const TITLE_ACTIVATED = 'title_activated';
	const COMMENTS_ACTIVATED = 'comments_activated';
	const NOTE_ACTIVATED = 'note_activated';
	const DISPLAY_NBR_NOTE = 'display_nbrnote';
	const VIEW_ACTIVATED = 'view_activated';
	const USER_ACTIVATED = 'user_activated';
	const LIMIT_MEMBER = 'limit_member';
	const LIMIT_MODO = 'limit_modo';
	const DISPLAY_PICS = 'display_pics';
	const SCROLL_TYPE = 'scroll_type';
	const NBR_PICS_MINI = 'nbr_pics_mini';
	const SPEED_MINI_PICS = 'speed_mini_pics';
	const AUTHORIZATIONS = 'authorizations';

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
	
	public function get_transparency()
	{
		return $this->get_property(self::TRANSPARENCY);
	}
	
	public function set_transparency($value) 
	{
		$this->set_property(self::TRANSPARENCY, $value);
	}
	
	public function get_logo()
	{
		return $this->get_property(self::LOGO);
	}
	
	public function set_logo($value) 
	{
		$this->set_property(self::LOGO, $value);
	}
	
	public function get_logo_activated()
	{
		return $this->get_property(self::LOGO_ACTIVATED);
	}
	
	public function set_logo_activated($value) 
	{
		$this->set_property(self::LOGO_ACTIVATED, $value);
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
	
	public function get_title_activated()
	{
		return $this->get_property(self::TITLE_ACTIVATED);
	}
	
	public function set_title_activated($value) 
	{
		$this->set_property(self::TITLE_ACTIVATED, $value);
	}
	
	public function get_comments_activated()
	{
		return $this->get_property(self::COMMENTS_ACTIVATED);
	}
	
	public function set_comments_activated($value) 
	{
		$this->set_property(self::COMMENTS_ACTIVATED, $value);
	}
	
	public function get_note_activated()
	{
		return $this->get_property(self::NOTE_ACTIVATED);
	}
	
	public function set_note_activated($value) 
	{
		$this->set_property(self::NOTE_ACTIVATED, $value);
	}
	
	public function get_display_nbr_note()
	{
		return $this->get_property(self::DISPLAY_NBR_NOTE);
	}
	
	public function set_display_nbr_note($value) 
	{
		$this->set_property(self::DISPLAY_NBR_NOTE, $value);
	}
	
	public function get_view_activated()
	{
		return $this->get_property(self::VIEW_ACTIVATED);
	}
	
	public function set_view_activated($value) 
	{
		$this->set_property(self::VIEW_ACTIVATED, $value);
	}
	
	public function get_user_activated()
	{
		return $this->get_property(self::USER_ACTIVATED);
	}
	
	public function set_user_activated($value) 
	{
		$this->set_property(self::USER_ACTIVATED, $value);
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
			self::WIDTH => 150,
			self::HEIGHT => 150,
			self::WIDTH_MAX => 800,
			self::HEIGHT_MAX => 600,
			self::WEIGHT_MAX => 1024,
			self::QUALITY => 80,
			self::TRANSPARENCY => 40,
			self::LOGO => 'logo.jpg',
			self::LOGO_ACTIVATED => true,
			self::D_WIDTH => 5,
			self::D_HEIGHT => 5,
			self::NBR_COLUMNS => 4,
			self::NBR_PICS_MAX => 16,
			self::NOTE_MAX => 5,
			self::TITLE_ACTIVATED => true,
			self::COMMENTS_ACTIVATED => true,
			self::NOTE_ACTIVATED => true,
			self::DISPLAY_NBR_NOTE => true,
			self::VIEW_ACTIVATED => true,
			self::USER_ACTIVATED => true,
			self::LIMIT_MEMBER => 10,
			self::LIMIT_MODO => 25,
			self::DISPLAY_PICS => 3,
			self::SCROLL_TYPE => 1,
			self::NBR_PICS_MINI => 6,
			self::SPEED_MINI_PICS => 6,
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 3, 'r1' => 7, 'r2' => 7)
		);
	}

	/**
	 * Returns the configuration.
	 * @return GalleryConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'module', 'gallery-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('module', self::load(), 'gallery-config');
	}
}
?>