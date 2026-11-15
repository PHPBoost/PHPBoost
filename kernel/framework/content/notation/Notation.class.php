<?php
/**
 * This class represents the rating system and its parameters
 * @package     Content
 * @subpackage  Notation
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11 
 * @since       PHPBoost 3.0 - 2010 02 14
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class Notation
{
	private mixed $id = null;
	private string $module_name = '';
	private int $id_in_module = 0;
	private int $user_id = 0;
	private float $note = 0;
	private string $notation_scale = '';
	private mixed $average_notes = 0;
	private mixed $notes_number = null;
	private bool $user_already_noted = false;

	private mixed $infos = null;

	public function set_id(string $id): void
	{
		$this->id = $id;
	}

	public function get_id(): mixed
	{
		return $this->id;
	}

	public function set_module_name(string $module): void
	{
		$this->module_name = $module;
	}

	public function get_module_name(): string
	{
		return $this->module_name;
	}

	public function set_id_in_module(int $id_in_module): void
	{
		$this->id_in_module = $id_in_module;
	}

	public function get_id_in_module(): int
	{
		return $this->id_in_module;
	}

	public function set_user_id(int $user_id): void
	{
		$this->user_id = $user_id;
	}

	public function get_user_id(): int
	{
		return !empty($this->user_id) ? $this->user_id : AppContext::get_current_user()->get_id();
	}

	public function set_note(float $note): void
	{
		$this->note = $note;
	}

	public function get_note(): float
	{
		return $this->note;
	}

	public function set_notation_scale(string $notation_scale): void
	{
		$this->notation_scale = $notation_scale;
	}

	public function get_notation_scale(): string
	{
		if (!empty($this->notation_scale))
		{
			return $this->notation_scale;
		}
		return ContentManagementConfig::load()->get_notation_scale();
	}

	public function set_average_notes(mixed $average_notes): void
	{
		$this->average_notes = (float)$average_notes;
	}

	public function get_average_notes(): mixed
	{
		if ($this->average_notes === null)
		{
			$this->init_database_infos();
			return $this->infos['average_notes'];
		}
		return $this->average_notes;
	}

	public function set_notes_number(mixed $notes_number): void
	{
		$this->notes_number = (int)$notes_number;
	}

	public function get_notes_number(): mixed
	{
		if ($this->notes_number === null)
		{
			$this->init_database_infos();
			return $this->infos['notes_number'];
		}
		return $this->notes_number;
	}

	public function set_user_already_noted(bool $user_already_noted): void
	{
		$this->user_already_noted = (bool)$user_already_noted;
	}

	public function user_already_noted(): bool
	{
		if ($this->user_already_noted === null)
		{
			$this->init_database_infos();
			return $this->infos['user_already_noted'];
		}
		return $this->user_already_noted;
	}

	private function init_database_infos(): mixed
	{
		if ($this->infos === null)
		{
			$this->infos = NotationService::get_informations_note($this);
		}
		return $this->infos;
	}
}
?>
