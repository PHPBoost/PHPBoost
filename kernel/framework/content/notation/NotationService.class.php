<?php
/*##################################################
 *                              NotationService.class.php
 *                            -------------------
 *   begin                : February 14, 2010
 *   copyright            : (C) 2010 Kevin MASSY
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
 * @author Kevin MASSY <soldier.weasel@gmail.com>
 * @package {@package}
 */
class NotationService
{
	private static $user;
	private static $db_querier;
	private static $lang;
	
	public static function __static()
	{
		self::$user = AppContext::get_current_user();
		self::$db_querier = PersistenceContext::get_querier();
		self::$lang = LangLoader::get('main');
	}
        
	/*
	 * @desc This function required object Notation containing the module_name, id in module and notation_scale.
	 * @param object $notation Notation
	 * @param boolean $average_notes
	 */
	public static function display_static_image(Notation $notation, $average_notes = false)
	{
		$notation_scale = $notation->get_notation_scale();
		if (!empty($notation_scale))
		{
			if ($average_notes === false)
			{
				$average_notes = self::get_average_notes($notation);
			}
			
			if ($average_notes > 0)
			{
				$template = new StringTemplate('
				# START notation #
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{notation.PICTURE}" alt="" class="valign_middle" />
				# END notation #');
				
				for ($i = 1; $i <= $notation_scale; $i++)
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
			throw new NotationScaleIsEmptyException();
		}
	}
	
	/*
	 * @desc This function required object Notation containing the module_name, id_in_module, user_id, note and notation_scale.
	 * @param object $notation Notation
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
			$template = new FileTemplate('framework/content/notation/notation.tpl');
			
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

			$count_notes = NotationDAO::get_count_notes_by_id_in_module($notation);
			$template->put_all(array(
				'C_VOTES' => $count_notes > 0 ? true : false,
				'C_MORE_1_VOTES' => $count_notes > 1 ? true : false,
				'CURRENT_URL' => REWRITED_SCRIPT,
				'ID_IN_MODULE' => $notation->get_id_in_module(),
				'NOTATION_SCALE' => $notation->get_notation_scale(),
				'NUMBER_PIXEL' => $notation->get_notation_scale() * 16,
				'NUMBER_VOTES' => $count_notes,
				'AVERAGE_NOTES' => $average_notes,
				'ALREADY_VOTE' => NotationDAO::get_member_already_notation($notation),
				'L_NO_NOTE' => self::$lang['no_note'],
				'L_AUTH_ERROR' => LangLoader::get_message('e_auth', 'errors'),
				'L_ALREADY_VOTE' => self::$lang['already_vote'],
				'L_NOTES' => self::$lang['notes'],
				'L_NOTE' => self::$lang['note'],
				'L_VALID_NOTE' => self::$lang['valid_note']
			));

			return $template->render();
		}
	}
	
	/*
	 * @desc This fonction update notation scale by module_name
	 * @param string $module_name 
	 * @param string $old_notation_scale
	 * @param string $new_notation_scale
	 */
	public static function update_notation_scale($module_name, $old_notation_scale, $new_notation_scale)
	{
		if ($old_notation_scale !== $new_notation_scale)
		{
			$coefficient = $new_notation_scale / $old_notation_scale;
			self::$db_querier->inject("UPDATE " . DB_TABLE_AVERAGE_NOTES . " SET average_notes = average_notes * " . $coefficient . " WHERE module_name = '". $module_name . "'");
			self::$db_querier->inject("UPDATE " . DB_TABLE_NOTE . " SET note = note * " . $coefficient . " WHERE module_name = '". $module_name . "'");
		}
	}
	
	/*
	 * @desc This fonction delete all notes by id module and id in module element
	 * @param string $module_name 
	 * @param string $id_in_module
	 */
	public static function delete_notes_id_in_module($module_name, $id_in_module)
	{
		try {
			NotationDAO::delete_average_notes_by_id_in_module($module_name, $id_in_module);
			NotationDAO::delete_notes_by_id_in_module($module_name, $id_in_module);
		} catch (Exception $e) {
		}
	}
	
	/*
	 * @desc This fonction delete all notes by module
	 * @param string $module_name 
	 * @param string $id_in_module
	 */
	public static function delete_notes_module($module_name)
	{
		try {
			NotationDAO::delete_all_notes_by_module($module_name);
			NotationDAO::delete_all_average_notes_by_module($module_name);
		} catch (Exception $e) {
		}
	}
	
	/*
	 * This function required object Notation containing the module_name and id_in_module.
	 */
	public static function get_former_number_notes(Notation $notation)
	{
		try {
			return self::$db_querier->get_column_value(DB_TABLE_AVERAGE_NOTES, 'number_notes', 'WHERE module_name = :module_name AND id_in_module = :id_in_module', 
			array('module_name' => $notation->get_module_name(), 'id_in_module' => $notation->get_id_in_module()));
		} catch (RowNotFoundException $e) {
		}
	}
	
	/*
	 * This function required object Notation containing the module_name and id_in_module.
	 */
	public static function get_average_notes(Notation $notation)
	{
		try {
			return self::$db_querier->get_column_value(DB_TABLE_AVERAGE_NOTES, 'average_notes', 'WHERE module_name = :module_name AND id_in_module = :id_in_module', 
			array('module_name' => $notation->get_module_name(), 'id_in_module' => $notation->get_id_in_module()));
		} catch (RowNotFoundException $e) {
			return '0';
		}
	}
	
	private static function register_notation(Notation $notation)
	{
		if (self::$user->check_level(User::MEMBER_LEVEL))
		{
			$note_is_valid = $notation->get_note() >= 0 && $notation->get_note() <= $notation->get_notation_scale() ? true : false;
			$member_already_notation = NotationDAO::get_member_already_notation($notation);
			
			if (!$member_already_notation && $note_is_valid)
			{
				NotationDAO::insert_note($notation);
				$nbr_notes = NotationDAO::get_count_average_notes_by_id_in_module($notation);
				if ($nbr_notes == 0)
				{
					NotationDAO::insert_average_notes($notation);
				}
				else
				{
					NotationDAO::update_average_notes($notation);
				}
			}
			else
			{
				throw new Exception('Already post note');
			}
		}
		else
		{
			DispatchManager::redirect(PHPBoostErrors::user_not_authorized());
		}
	}
}
?>