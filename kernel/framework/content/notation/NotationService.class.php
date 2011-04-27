<?php
/*##################################################
 *                              NotationService.class.php
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
class NotationService
{
	private static $user;
	private static $db_querier;
	private static $lang;
	
	public static function __static()
	{
		self::$user = AppContext::get_user();
		self::$db_querier = PersistenceContext::get_querier();
		self::$lang = LangLoader::get('main');
	}
	
	/*
	 * This function required object Notation containing the module_name, id in module and notation_scale.
	 */
	public static function display_static_image(Notation $notation)
	{
		if ($notation->get_notation_scale() > 0)
		{
			$nbr_notes = self::get_count_average_notes_by_id_in_module($notation);
			if ($nbr_notes > 0)
			{
				$template = new StringTemplate('
				# START notation #
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{notation.PICTURE}" alt="" class="valign_middle" />
				# END notation #');
				
				$average_notes = self::get_average_notes($notation);
				for ($i = 1; $i <= $notation->get_notation_scale(); $i++)
				{
					$star_img = 'stars.png';
					if ($average_notes < $i)
					{
						$decimal = $i - $average_notes;
						if ($decimal >= 1)
							$star_img = 'stars0.png';
						elseif ($decimal >= 0.75)
							$star_img = 'stars1.png';
						elseif ($decimal >= 0.50)
							$star_img = 'stars2.png';
						else
							$star_img = 'stars3.png';
					}
					
					$template->assign_block_vars('notation', array(
						'PICTURE' => $star_img
					));
				}
				return $template->render();
			}
			else
			{
				return self::$lang['no_note'];
			}
		}
		else
		{
			throw new Exception('Not display, notation scale is null');
		}
	}
	
	/*
	 * This function required object Notation containing the module_name, id_in_module, user_id, note and notation_scale.
	 */
	public static function display_active_image(Notation $notation)
	{
		$html_id = $notation->get_module_name() . '_' . $notation->get_id_in_module();
		
		$note_post = AppContext::get_request()->get_int('note', 0);
		
		if (!empty($note_post))
		{
			$notation->set_note($note_post);
			self::register_notation($notation);
		}
		else
		{
			$template = new FileTemplate('framework/notation.tpl');
			
			$average_notes = self::get_average_notes($notation);

			for ($i = 1; $i <= $notation->get_notation_scale(); $i++)
			{
				$star_img = 'stars.png';
				if ($average_notes < $i)
				{
					$decimal = $i - $average_notes;
					if ($decimal >= 1)
						$star_img = 'stars0.png';
					elseif ($decimal >= 0.75)
						$star_img = 'stars1.png';
					elseif ($decimal >= 0.50)
						$star_img = 'stars2.png';
					else
						$star_img = 'stars3.png';
				}
				
				$template->assign_block_vars('notation', array(
					'I' => $i,
					'PICTURE' => $star_img
				));
				
				$template->assign_block_vars('notation_no_js', array(
					'I' => $i
				));
			}

			$template->put_all(array(
				'C_VOTES' => self::get_count_notes_by_id_in_module($notation) > 0 ? true : false,
				'CURRENT_URL' => REWRITED_SCRIPT,
				'ID_IN_MODULE' => $notation->get_id_in_module(),
				'NOTATION_SCALE' => $notation->get_notation_scale(),
				'NUMBER_PIXEL' => $notation->get_notation_scale() * 16,
				'NUMBER_VOTES' => self::get_count_notes_by_id_in_module($notation),
				'AVERAGE_NOTES' => $average_notes,
				'ALREADY_VOTE' => self::get_member_already_notation($notation),
				'L_NO_NOTE' => self::$lang['no_note'],
				'L_AUTH_ERROR' => LangLoader::get_message('e_auth', 'errors'),
				'L_ALREADY_VOTE' => self::$lang['already_vote'],
				'L_NOTES' => self::get_count_notes_by_id_in_module($notation) > 1 ? self::$lang['notes'] : self::$lang['note'],
				'L_NOTE' => self::$lang['note'],
				'L_VALID_NOTE' => self::$lang['valid_note']
			));

			return $template->render();
		}
	}
	
	/*
	 * This function required object Notation containing the module_name and id_in_module.
	 */
	public static function delete_notes_id_in_module(Notation $notation)
	{
		$nbr_notes = self::get_count_average_notes_by_id_in_module($notation);
		if ($nbr_notes > 0)
		{
			self::delete_average_notes_by_id_in_module($notation);
			self::delete_notes_by_id_in_module($notation);
		}
	}
	
	/*
	 * This function required object Notation containing the module_name.
	 */
	public static function delete_notes_module(Notation $notation)
	{
		$nbr_notes = self::get_count_average_notes_by_module($notation);
		if ($nbr_notes > 0)
		{
			self::delete_all_notes_by_module($notation);
			self::delete_all_average_notes_by_module($notation);
		}
	}
	
	/*
	 * This function required object Notation containing the module_name and id_in_module.
	 */
	public static function get_former_number_notes(Notation $notation)
	{
		$nbr_notes = self::get_count_average_notes_by_id_in_module($notation);
		if ($nbr_notes > 0)
		{
			$row = self::$db_querier->select_single_row(DB_TABLE_AVERAGE_NOTES, array('number_notes'), "WHERE module_name = '" . $notation->get_module_name() . "' AND id_in_module = '". $notation->get_id_in_module() ."'");
			return (int)$row['number_notes'];
		}
	}
	
	/*
	 * This function required object Notation containing the module_name and id_in_module.
	 */
	public static function get_average_notes(Notation $notation)
	{
		$nbr_notes = self::get_count_average_notes_by_id_in_module($notation);
		if ($nbr_notes > 0)
		{
			$row = self::$db_querier->select_single_row(DB_TABLE_AVERAGE_NOTES, array('average_notes'), "WHERE module_name = '" . $notation->get_module_name() . "' AND id_in_module = '". $notation->get_id_in_module() ."'");
			return $row['average_notes'];
		}
		return 0;
	}
	
	private static function register_notation(Notation $notation)
	{
		if (self::$user->check_level(MEMBER_LEVEL))
		{
			$note_is_valid = $notation->get_note() >= 0 && $notation->get_note() <= $notation->get_notation_scale() ? true : false;
			$member_already_notation = self::get_member_already_notation($notation);
			
			if (!$member_already_notation && $note_is_valid)
			{
				self::insert_note($notation);
				$nbr_notes = self::get_count_average_notes_by_id_in_module($notation);
				if ($nbr_notes > 0)
				{
					self::update_average_notes($notation);
				}
				else
				{
					self::insert_average_notes($notation);
				}
			}
			else
			{
				throw new Exception('Already post note');
			}
		}
		else
		{
			throw new Exception('Not level member');
		}
	}
	
	private static function insert_note(Notation $notation)
	{
		self::$db_querier->inject(
			"INSERT INTO " . DB_TABLE_NOTE . " (module_name, id_in_module, user_id, note)
			VALUES (:module_name, :id_in_module, :user_id, :note)", array(
                'module_name' => $notation->get_module_name(),
				'id_in_module' => $notation->get_id_in_module(),
				'user_id' => $notation->get_user_id(),
				'note' => $notation->get_note(),				
		));
	}
	
	private static function insert_average_notes(Notation $notation)
	{
		self::$db_querier->inject(
			"INSERT INTO " . DB_TABLE_AVERAGE_NOTES . " (module_name, id_in_module, average_notes, number_notes)
			VALUES (:module_name, :id_in_module, :average_notes, :number_notes)", array(
                'module_name' => $notation->get_module_name(),
				'id_in_module' => $notation->get_id_in_module(),
				'average_notes' => self::calculates_average_notes($notation),
				'number_notes' => 1
		));
	}
	
	private static function update_average_notes(Notation $notation)
	{
		$former_nbr_notes = self::get_former_number_notes($notation);
		self::$db_querier->inject(
			"UPDATE " . DB_TABLE_AVERAGE_NOTES . " SET 
			module_name = :module_name, id_in_module = :id_in_module, average_notes = :average_notes, number_notes = :number_notes"
			, array(
                'module_name' => $notation->get_module_name(),
				'id_in_module' => $notation->get_id_in_module(),
				'average_notes' => self::calculates_average_notes($notation),
				'number_notes' => $former_nbr_notes + 1
		));
	}

	private static function calculates_average_notes(Notation $notation)
	{
		$nbr_notes = self::get_count_notes_by_id_in_module($notation);
		if ($nbr_notes > 0)
		{
			$result = self::$db_querier->select("SELECT note
				FROM " . DB_TABLE_NOTE . "
				WHERE module_name = '" . $notation->get_module_name() . "' 
				AND id_in_module = '". $notation->get_id_in_module() ."'
			");
			
			$notes = 0;
			while ($row = $result->fetch())
			{
				$notes += $row['note'];
			}

			return (round(($notes / $nbr_notes) / 0.25) * 0.25);
		}
	}

	private static function get_count_notes_by_id_in_module(Notation $notation)
	{
		return self::$db_querier->count(DB_TABLE_NOTE, "WHERE module_name = '" . $notation->get_module_name() . "' AND id_in_module = '". $notation->get_id_in_module() ."' ");
	}
	
	private static function get_count_average_notes_by_id_in_module(Notation $notation)
	{
		return self::$db_querier->count(DB_TABLE_AVERAGE_NOTES, "WHERE module_name = '" . $notation->get_module_name() . "' AND id_in_module = '". $notation->get_id_in_module() ."' ");
	}
	
	private static function get_count_average_notes_by_module(Notation $notation)
	{
		return self::$db_querier->count(DB_TABLE_AVERAGE_NOTES, "WHERE module_name = '" . $notation->get_module_name() . "'");
	}
	
	private static function get_member_already_notation(Notation $notation)
	{
		return self::$db_querier->count(DB_TABLE_NOTE, "WHERE user_id = '" . $notation->get_user_id() . "' AND module_name = '" . $notation->get_module_name() . "' AND id_in_module = '". $notation->get_id_in_module() ."' ") > 0 ? true : false;
	}
	
	private static function delete_average_notes_by_id_in_module(Notation $notation)
	{
		self::$db_querier->inject("DELETE FROM " . DB_TABLE_AVERAGE_NOTES . " WHERE module_name = '" . $notation->get_module_name() . "' AND id_in_module = '". $notation->get_id_in_module() ."' ");
	}
	
	private static function delete_notes_by_id_in_module(Notation $notation)
	{
		self::$db_querier->inject("DELETE FROM " . DB_TABLE_NOTE . " WHERE module_name = '" . $notation->get_module_name() . "' AND id_in_module = '". $notation->get_id_in_module() ."' ");
	}
	
	private static function delete_all_average_notes_by_module(Notation $notation)
	{
		self::$db_querier->inject("DELETE FROM " . DB_TABLE_AVERAGE_NOTES . " WHERE module_name = '" . $notation->get_module_name() . "'");
	}
	
	private static function delete_all_notes_by_module(Notation $notation)
	{
		self::$db_querier->inject("DELETE FROM " . DB_TABLE_NOTE . " WHERE module_name = '" . $notation->get_module_name() . "'");
	}
}

?>