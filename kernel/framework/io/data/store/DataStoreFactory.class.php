<?php
/*##################################################
 *                       DataStoreFatory.class.php
 *                            -------------------
 *   begin                : December 09, 2009
 *   copyright            : (C) 2009 Benoit Sautel, Loic Rouchon
 *   email                : ben.popeye@phpboost.com, horn@phpboost.com
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
 * @package io
 * @subpackage data/store
 * @desc This factory returns you the data store that are the best for your requirements.
 * @author Benoit Sautel <ben.popeye@phpboost.com>, Loic Rouchon <horn@phpboost.com>
 */
class DataStoreFactory
{
	private static $apc_enabled = null;

	/**
	 * @desc Returns an efficient data store whose life span can be not infinie.
	 * @param string $id Identifier of the data store.
	 * @return DataStore The best data store you can use with the current configuration
	 */
	public static function get_ram_store($id)
	{
		if (self::is_apc_enabled())
		{
			return new APCDataStore($id);
		}
		return new RAMDataStore();
	}

	/**
	 * @desc Returns an infinite-life span data store that can be not very efficient.
	 * @param string $id Ifentifier of the data store.
	 * @return DataStore The best data store you can use with the current configuration
	 */
	public static function get_filesystem_store($id)
	{
		if (self::is_apc_enabled())
		{
			return new APCDataStore($id);
		}
		return new FileSystemDataStore($id);
	}

	private static function is_apc_enabled()
	{
		if (self::$apc_enabled === null)
		{
			if (function_exists('apc_cache_info'))
			{
				self::$apc_enabled = true;
			}
			else
			{
				self::$apc_enabled = false;
			}
		}
		return self::$apc_enabled;
	}
}
?>