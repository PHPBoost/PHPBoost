<?php
/**
 * This class represents the rating system and its parameters
 * @package     Content
 * @subpackage  Notation
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 15
 * @since       PHPBoost 3.0 - 2010 02 14
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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
	private $notes_number;
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
		if (!empty($this->notation_scale))
		{
			return $this->notation_scale;
		}
		return ContentManagementConfig::load()->get_notation_scale();
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

	public function set_notes_number($notes_number)
	{
		$this->notes_number = (int)$notes_number;
	}

	public function get_notes_number()
	{
		if ($this->notes_number === null)
		{
			$this->init_database_infos();
			return $this->infos['notes_number'];
		}
		return $this->notes_number;
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
