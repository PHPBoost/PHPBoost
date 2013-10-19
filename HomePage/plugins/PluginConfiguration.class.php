<?php
/*##################################################
 *                           PluginConfiguration.class.php
 *                            -------------------
 *   begin                : February 21, 2012
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

abstract class PluginConfiguration
{
	private $id;
	
	public function __construct($id)
	{
		$this->id = $id;
	}
	
	public function set_id($id)
	{
		$this->id = $id;
	}
	
	public function get_id()
	{
		return $this->id;
	}
	
	public static function load($id, $class)
	{
		try {
			$serialized = PersistenceContext::get_querier()->get_column_value(HomePageSetup::$home_page_table, 'object', 'WHERE id=:id', array('id' => $id));
			return unserialize($serialized);
		} catch (RowNotFoundException $e) {
			return new $class($id);
		}
	}
}
?>