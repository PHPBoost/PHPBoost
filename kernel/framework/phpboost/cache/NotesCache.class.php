<?php
/*##################################################
 *                      	 NotesCache.class.php
 *                            -------------------
 *   begin                : September 24, 2011
 *   copyright            : (C) 2011 Kévin MASSY
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
 */
class NotesCache implements CacheData
{
	private $notes = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->notes = array();

		$result = PersistenceContext::get_querier()->select("
			SELECT *
			FROM " . DB_TABLE_NOTE . "
		");
		
		while ($row = $result->fetch())
		{
			$this->notes[$row['id']] = array(
				'id' => $row['id'],
				'module_name' => $row['module_name'],
				'id_in_module' => $row['id_in_module'],
				'user_id' => $row['user_id'],
				'note' => $row['note']
			);
		}
	}

	public function get_notes()
	{
		return $this->notes;
	}
	
	public function note_exists($id)
	{
		return array_key_exists($id, $this->notes);
	}

	public function note_exists_by_module($module_id, $id_in_module)
	{
		$notes = $this->get_notes_by_module($module_id, $id_in_module);
		return !empty($notes);
	}
	
	public function get_note($id)
	{
		if ($this->note_exists($id))
		{
			return $this->notes[$id];
		}
		return null;
	}

	public function get_notes_by_module($module_id, $id_in_module)
	{
		$notes = array();
		foreach ($this->notes as $id_notes => $informations)
		{
			if ($informations['module_name'] == $module_id && $informations['id_in_module'] == $id_in_module)
			{
				$notes[$id_notes] = $informations;
			}
		}
		return $notes;
	}
	
	public function get_notes_by_module_and_user($module_id, $id_in_module, $user_id)
	{
		$notes = array();
		foreach ($this->notes as $id_notes => $informations)
		{
			if ($informations['module_name'] == $module_id && $informations['id_in_module'] == $id_in_module && $informations['user_id'] == $user_id)
			{
				$notes[$id_notes] = $informations;
			}
		}
		return $notes;
	}
	
	public function get_count_notes_by_module($module_id, $id_in_module)
	{
		return count($this->get_notes_by_module($module_id, $id_in_module));
	}
	
	public function get_count_notes()
	{
		return count($this->notes);
	}
	
	/**
	 * Loads and returns the comments cached data.
	 * @return CommentsCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'kernel', 'notes');
	}
	
	/**
	 * Invalidates the current comments cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('kernel', 'notes');
	}
}
?>