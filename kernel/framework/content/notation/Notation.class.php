<?php
/*##################################################
 *                              Notation.class.php
 *                            -------------------
 *   begin                : February 14, 2010
 *   copyright            : (C) 2010 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 * @package {@package}
 */
class Notation
{
	private $id;
	private $module_name;
	private $module_id;
	private $user_id;
	private $login;
	private $note;
	private $notation_scale;

	public function set_id($id)
	{
		$this->id = $id;
	}

	public function get_id()
	{
		return $this->id;
	}

	public function set_module_name($module)
	{
		$this->module_name = $module;
	}

	public function get_module_name()
	{
		return $this->module_name;
	}

	public function set_module_id($module_id)
	{
		$this->module_id = $module_id;
	}

	public function get_module_id()
	{
		return $this->module_id;
	}

	public function set_user_id($user_id)
	{
		$this->user_id = $user_id;
	}

	public function get_user_id()
	{
		return !empty($this->user_id) ? $this->user_id : AppContext::get_user()->get_id();
	}

	public function set_login($login)
	{
		$this->login = $login;
	}

	public function get_login()
	{
		return !empty($this->login) ? $this->login : AppContext::get_user()->get_login();
	}

	public function set_note($note)
	{
		$this->note = $note;
	}

	public function get_note()
	{
		return $this->note;
	}

	public function set_notation_scale($notation_scale)
	{
		$this->notation_scale = $notation_scale;
	}

	public function get_notation_scale()
	{
		return $this->notation_scale;
	}

}

?>