<?php
/*##################################################
 *                              Notation.class.php
 *                            -------------------
 *   begin                : February 14, 2010
 *   copyright            : (C) 2010 Kevin MASSY
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @desc This class represents the rating system and its parameters
 * @package {@package}
 */
class Notation
{
	private $id;
	private $module_name;
	private $id_in_module;
	private $user_id;
	private $note;
	private $notation_scale;
	
	private $average_notes;
	private $number_notes;
	private $user_already_noted;
	
	private $infos;
	
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
	
	public function set_id_in_module($id_in_module)
	{
		$this->id_in_module = $id_in_module;
	}
	
	public function get_id_in_module()
	{
		return $this->id_in_module;
	}
	
	public function set_user_id($user_id)
	{
		$this->user_id = $user_id;
	}
	
	public function get_user_id()
	{
		return !empty($this->user_id) ? $this->user_id : AppContext::get_current_user()->get_id();
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
	
	public function set_average_notes($average_notes)
	{
		$this->average_notes = (float)$average_notes;
	}
	
	public function get_average_notes()
	{
		if ($this->average_notes === null)
		{
			$this->init_database_infos();
			return $this->infos['average_notes'];
		}
		return $this->average_notes;
	}
	
	public function set_number_notes($number_notes)
	{
		$this->number_notes = (int)$number_notes;
	}
	
	public function get_number_notes()
	{
		if ($this->number_notes === null)
		{
			$this->init_database_infos();
			return $this->infos['number_notes'];
		}
		return $this->number_notes;
	}
	
	public function set_user_already_noted($user_already_noted)
	{
		$this->user_already_noted = (bool)$user_already_noted;
	}
	
	public function user_already_noted()
	{
		if ($this->user_already_noted === null)
		{
			$this->init_database_infos();
			return $this->infos['user_already_noted'];
		}
		return $this->user_already_noted;
	}
	
	private function init_database_infos()
	{
		if ($this->infos === null)
		{
			$this->infos = NotationService::get_informations_note($this);
		}
		return $this->infos;
	}
}
?>