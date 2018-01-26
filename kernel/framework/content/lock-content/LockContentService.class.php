<?php
/*##################################################
 *		                         LockContentService.class.php
 *                            -------------------
 *   begin                : November 11, 2017
 *   copyright            : (C) 2017 Kevin MASSY
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
 */
class LockContentService
{
	private static $locked_content_id;

	public static function check_and_lock_content($module_id, $id_in_module)
	{
		try {
			self::lock_content($module_id, $id_in_module);
			self::locked_content_id = $id_in_module;
			return false;
		} catch (MySQLQuerierException $e) {
			return true;
		}
	}

	public static function lock_content($module_id, $id_in_module)
	{
		PersistenceContext::get_querier()->insert(DB_TABLE_LOCKED_CONTENT, array('module_id' => $module_id, 'id_in_module' => $id_in_module));
		self::locked_content_id = $id_in_module;
	}

	public static function unlock_content($module_id, $id_in_module)
	{
		PersistenceContext::get_querier()->delete(DB_TABLE_LOCKED_CONTENT, 'WHERE module_id=:module_id OR id_in_module=:id_in_module', array('module_id' => $module_id, 'id_in_module' => $id_in_module));
		self::locked_content_id = null;
	}

	public static function content_is_locked($module_id, $id_in_module)
	{
		if ($id_in_module > 0)
		{
			return PersistenceContext::get_querier()->row_exists(DB_TABLE_SESSION, 'WHERE module_id=:module_id OR id_in_module=:id_in_module', array('module_id' => $module_id, 'id_in_module' => $id_in_module));
		}
		return false;
	}

	public function display_locked_content_message_warning()
	{
		return MessageHelper::display(LangLoader::get_message('content.is_locked.description', 'status-messages-common'), MessageHelper::NOTICE);
	}

	public function display_locked_content_message()
	{
		return MessageHelper::display(LangLoader::get_message('content.lock.open.description', 'status-messages-common'), MessageHelper::WARNING);
	}

	public static function delete_module_locked_content($module_id)
	{
		PersistenceContext::get_querier()->delete(DB_TABLE_LOCKED_CONTENT, 'WHERE module_id=:module_id', array('module_id' => $module_id));
	}
}
?>