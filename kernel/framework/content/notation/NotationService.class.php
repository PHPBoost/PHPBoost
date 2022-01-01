<?php
/**
 * This class represents the rating system and its parameters
 * @package     Content
 * @subpackage  Notation
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 3.0 - 2010 02 14
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NotationService
{
	private static $js_already_included = false;

	private static $user;
	private static $db_querier;

	public static function __static()
	{
		self::$user = AppContext::get_current_user();
		self::$db_querier = PersistenceContext::get_querier();
	}

	/**
	 * This function required object Notation containing the module_name, id in module and notation_scale.
	 * @param object $notation Notation
	 * @param boolean $average_notes
	 */
	public static function display_static_image(Notation $notation)
	{
		$notation_scale = $notation->get_notation_scale();
		if (!empty($notation_scale))
		{
			$view = new FileTemplate('framework/content/notation/notation.tpl');
			$view->add_lang(LangLoader::get_all_langs());

			$average_notes = $notation->get_average_notes();
			$int = intval($average_notes);
			$decimal = floatval('0.' . TextHelper::substr($average_notes, TextHelper::strpos($average_notes, '.') + 1));

			for ($i = 1; $i <= $notation->get_notation_scale(); $i++)
			{
				$star_full = false;
				$star_half = false;
				$star_empty = false;
				$width = 0;

				if ($int >= $i || ($int+1 == $i && $decimal == 1)) {
					$star_full = true;
					$star_width = 'star-width-100';
				}
				else if ($int+1 == $i && $decimal >= 0.90) {
					$star_full = true;
					$star_width = 'star-width-90';
				}
				else if ($int+1 == $i && $decimal >= 0.75 && $decimal < 0.9) {
					$star_full = true;
					$star_width = 'star-width-75';
				}
				else if ($int+1 == $i && $decimal >= 0.5 && $decimal < 0.75) {
					$star_half = true;
					$star_width = 'star-width-50';
				}
				else if ($int+1 == $i && $decimal >= 0.25 && $decimal < 0.5) {
					$star_half = true;
					$star_width = 'star-width-25';
				}
				else if ($int+1 == $i && $decimal >= 0.1 && $decimal < 0.25) {
					$star_empty = true;
					$star_width = 'star-width-10';
				}
				else {
					$star_empty = true;
					$star_width = 'star-width-0';
				}

				$view->assign_block_vars('star', array(
					'I' => $i,
					'STAR_EMPTY' => $star_empty,
					'STAR_HALF'  => $star_half,
					'STAR_FULL'  => $star_full,
					'STAR_WIDTH' => $star_width
				));
			}

			$count_notes = $notation->get_notes_number();
			$view->put_all(array(
				'C_STATIC_DISPLAY' => true,
				'C_NOTES'          => $count_notes > 0,

				'ID_IN_MODULE'   => $notation->get_id_in_module(),
				'NOTES_NUMBER'   => $notation->get_notes_number(),
				'AVERAGE_NOTES'  => $average_notes,
				'NOTATION_SCALE' => $notation->get_notation_scale(),
			));

			return $view->render();
		}
		else
		{
			throw new Exception('The notation scale is empty');
		}
	}

	/**
	 * This function required object Notation containing the module_name, id_in_module, user_id, note and notation_scale.
	 * @param object $notation Notation
	 */
	public static function display_active_image(Notation $notation)
	{
		$note_post = AppContext::get_request()->get_int('note', 0);
		$id_post = AppContext::get_request()->get_int('id', 0);

		if (!empty($note_post) && !empty($id_post))
		{
			$notation->set_module_name(Environment::get_running_module_name());
			$notation->set_id_in_module($id_post);
			$notation->set_note($note_post);
			self::register_notation($notation);
		}
		else
		{
			$view = new FileTemplate('framework/content/notation/notation.tpl');
			$view->add_lang(LangLoader::get_all_langs());

			$average_notes = $notation->get_average_notes();
			$int = intval($average_notes);
			$decimal = floatval('0.' . TextHelper::substr($average_notes, TextHelper::strpos($average_notes, '.') + 1));

			for ($i = 1; $i <= $notation->get_notation_scale(); $i++)
			{
				$star_full = false;
				$star_half = false;
				$star_empty = false;
				$width = 0;

				if ($int >= $i || ($int+1 == $i && $decimal == 1)) {
					$star_full = true;
					$star_width = 'star-width-100';
				}
				else if ($int+1 == $i && $decimal >= 0.90) {
					$star_full = true;
					$star_width = 'star-width-90';
				}
				else if ($int+1 == $i && $decimal >= 0.75 && $decimal < 0.9) {
					$star_full = true;
					$star_width = 'star-width-75';
				}
				else if ($int+1 == $i && $decimal >= 0.5 && $decimal < 0.75) {
					$star_half = true;
					$star_width = 'star-width-50';
				}
				else if ($int+1 == $i && $decimal >= 0.25 && $decimal < 0.5) {
					$star_half = true;
					$star_width = 'star-width-25';
				}
				else if ($int+1 == $i && $decimal >= 0.1 && $decimal < 0.25) {
					$star_empty = true;
					$star_width = 'star-width-10';
				}
				else {
					$star_empty = true;
					$star_width = 'star-width-0';
				}

				$view->assign_block_vars('star', array(
					'I' => $i,
					'STAR_EMPTY' => $star_empty,
					'STAR_HALF'  => $star_half,
					'STAR_FULL'  => $star_full,
					'STAR_WIDTH' => $star_width
				));
			}

			$count_notes = $notation->get_notes_number();
			$view->put_all(array(
				'C_JS_NOT_ALREADY_INCLUDED' => !self::$js_already_included,
				'C_NOTES' => $count_notes > 0,
				'C_SEVERAL_NOTES' => $count_notes > 1,

				'CURRENT_URL' => REWRITED_SCRIPT,
				'ID_IN_MODULE' => $notation->get_id_in_module(),
				'NOTATION_SCALE' => $notation->get_notation_scale(),
				'NOTES_NUMBER' => $count_notes,
				'AVERAGE_NOTES' => $average_notes,
				'ALREADY_NOTE' => $notation->user_already_noted(),
			));

			self::$js_already_included = true;

			return $view->render();
		}
	}

	/**
	 * This fonction update notation scale by module_name
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

	/**
	 * This fonction delete all notes by id module and id in module element
	 * @param string $module_name
	 * @param string $id_in_module
	 */
	public static function delete_notes_id_in_module($module_name, $id_in_module)
	{
		try {
			$condition = 'WHERE module_name=:module_name AND id_in_module=:id_in_module';
			$parameters = array('module_name' => $module_name, 'id_in_module' => $id_in_module);

			self::$db_querier->delete(DB_TABLE_AVERAGE_NOTES, $condition, $parameters);
			self::$db_querier->delete(DB_TABLE_NOTE, $condition, $parameters);
		} catch (MySQLQuerierException $e) {
		}
	}

	/**
	 * This fonction delete all notes by module
	 * @param string $module_name
	 */
	public static function delete_notes_module($module_name)
	{
		try {
			$condition = 'WHERE module_name=:module_name';
			$parameters = array('module_name' => $module_name);

			self::$db_querier->delete(DB_TABLE_AVERAGE_NOTES, $condition, $parameters);
			self::$db_querier->delete(DB_TABLE_NOTE, $condition, $parameters);
		} catch (MySQLQuerierException $e) {
		}
	}

	/**
	 * This function required object Notation containing the module_name and id_in_module.
	 */
	public static function get_notes_number(Notation $notation)
	{
		try {
			return self::$db_querier->get_column_value(DB_TABLE_AVERAGE_NOTES, 'notes_number', 'WHERE module_name = :module_name AND id_in_module = :id_in_module',
			array('module_name' => $notation->get_module_name(), 'id_in_module' => $notation->get_id_in_module()));
		} catch (RowNotFoundException $e) {
			return 0;
		}
	}

	/**
	 * This function required object Notation containing the module_name and id_in_module.
	 */
	public static function get_average_notes(Notation $notation)
	{
		try {
			return self::$db_querier->get_column_value(DB_TABLE_AVERAGE_NOTES, 'average_notes', 'WHERE module_name = :module_name AND id_in_module = :id_in_module',
			array('module_name' => $notation->get_module_name(), 'id_in_module' => $notation->get_id_in_module()));
		} catch (RowNotFoundException $e) {
			return 0;
		}
	}

	/**
	 * This function required object Notation containing the module_name, id_in_module and user_id.
	 */
	public static function get_informations_note(Notation $notation)
	{
		try {
			return self::$db_querier->select_single_row_query('SELECT average_notes, notes_number, (SELECT COUNT(*) FROM '. DB_TABLE_NOTE .'
			WHERE user_id=:user_id AND module_name=:module_name AND id_in_module=:id_in_module) AS user_already_noted
			FROM ' . DB_TABLE_AVERAGE_NOTES . '
			WHERE module_name = :module_name AND id_in_module = :id_in_module', array(
				'module_name' => $notation->get_module_name(),
				'id_in_module' => $notation->get_id_in_module(),
				'user_id' => $notation->get_user_id()
			));
		} catch (RowNotFoundException $e) {
			return array(
				'average_notes' => 0,
				'notes_number' => 0,
				'user_already_noted' => 0
			);
		}
	}

	private static function register_notation(Notation $notation)
	{
		if (self::$user->check_level(User::MEMBER_LEVEL))
		{
			$note_is_valid = $notation->get_note() >= 0 && $notation->get_note() <= $notation->get_notation_scale() ? true : false;
			$member_already_notation = self::$db_querier->count(DB_TABLE_NOTE, 'WHERE user_id=:user_id AND module_name=:module_name AND id_in_module=:id_in_module', array(
				'module_name' => $notation->get_module_name(),
				'id_in_module' => $notation->get_id_in_module(),
				'user_id' => $notation->get_user_id()
			));

			if (!$member_already_notation && $note_is_valid)
			{
				$properties = array(
					'module_name'  => $notation->get_module_name(),
					'id_in_module' => $notation->get_id_in_module(),
					'user_id'      => $notation->get_user_id(),
					'note'         => $notation->get_note()
				);

				self::$db_querier->insert(DB_TABLE_NOTE, $properties);
				HooksService::execute_hook_action('notation', $notation->get_module_name(), array_merge($properties, array('notation_scale' => $notation->get_notation_scale())));

				$condition = 'WHERE module_name=:module_name AND id_in_module=:id_in_module';
				$parameters = array('module_name' => $notation->get_module_name(), 'id_in_module' => $notation->get_id_in_module());

				$nbr_notes = self::$db_querier->count(DB_TABLE_AVERAGE_NOTES, $condition, $parameters);
				if ($nbr_notes == 0)
				{
					self::$db_querier->insert(DB_TABLE_AVERAGE_NOTES, array(
						'module_name' => $notation->get_module_name(),
						'id_in_module' => $notation->get_id_in_module(),
						'average_notes' => self::calculates_average_notes($notation),
						'notes_number' => 1
					));
				}
				else
				{
					self::$db_querier->update(DB_TABLE_AVERAGE_NOTES, array(
						'average_notes' => self::calculates_average_notes($notation),
						'notes_number' => self::get_notes_number($notation) + 1)
					, $condition, $parameters);
				}
			}
		}
		else
		{
			DispatchManager::redirect(PHPBoostErrors::user_not_authorized());
		}
	}

	private static function calculates_average_notes(Notation $notation)
	{
		try {
			$result = self::$db_querier->select_rows(DB_TABLE_NOTE, array('note'), 'WHERE module_name=:module_name AND id_in_module=:id_in_module',
			array('module_name' => $notation->get_module_name(), 'id_in_module' => $notation->get_id_in_module()));

			$notes = 0;
			while ($row = $result->fetch())
			{
				$notes += $row['note'];
			}
			$result->dispose();

			return (round(($notes / $result->get_rows_count()) / 0.25) * 0.25);
		} catch (RowNotFoundException $e) {
			return 0;
		}
	}
}
?>
