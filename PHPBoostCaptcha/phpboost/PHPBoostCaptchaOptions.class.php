<?php 
/*##################################################
 *                            PHPBoostCaptchaOptions.class.php
 *                            -------------------
 *   begin                : February 27, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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

class PHPBoostCaptchaOptions implements CaptchaOptions
{
	private $width = 160;
	private $height = 50;
	private $font = '../../kernel/data/fonts/impact.ttf';
	private $difficulty;
	
	public function __construct()
	{
		$this->difficulty = PHPBoostCaptchaConfig::load()->get_difficulty();
	}
	
	public function set_difficulty($difficulty)	{$this->difficulty = max(0, $difficulty);}
	public function set_width($width) {$this->width = $width;}
	public function set_height($height)	{$this->height = $height;}
	public function set_font($font) {$this->font = $font;}

	public function get_width() {return $this->width;}
	public function get_height() {return $this->height;}
	public function get_font() {return $this->font;}
	public function get_difficulty() {return $this->difficulty;}
}
?>