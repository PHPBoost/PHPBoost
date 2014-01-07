<?php
/*##################################################
 *                    Captcha.class.php
 *                            -------------------
 *   begin                : September 04, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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
 * @package {@package}
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
abstract class Captcha implements ExtensionPoint
{
	const EXTENSION_POINT = 'captcha';
	
	private $html_id = 'captcha';
	private $options;

	abstract public function get_name();
		
	abstract public function is_available();
	
	abstract public function is_valid();
	
	abstract public function display();
	
	public function get_error()
	{
		return;
	}
	
	public function set_html_id($html_id) {	$this->html_id = $html_id; }
	public function get_html_id() { return $this->html_id; }
	public function set_options(CaptchaOptions $options) { $this->options = $options; }
	public function get_options() { return $this->options; }
}
?>