<?php
/*##################################################
 *                          DataStore.class.php
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
 * @package {@package}
 * @desc This interface represents a data store. Its different implementations store data in
 * different physical areas, you have to use it according to the data's life span and the efficiency
 * you need.
 * A container can store several pieces of data, each of one has a string identifier you choose. That
 * identifier must be a string with only letters and digits. 
 * @author Benoit Sautel <ben.popeye@phpboost.com>, Loic Rouchon <horn@phpboost.com>
 */
interface DataStore
{
	/**
	 * @desc Returns the data stored in the $id key.
	 * @param string $id The key (must be a string with only letters and digits)
	 * @return mixed The data that is stored
	 * @throws DataStoreException If data cannot be found.
	 */
    function get($id);

    /**
     * @desc Tells whether the container contains the data at the key $id.
     * @param string $id The key
     * @return bool true if the container contains it, false otherwise
     */
    function contains($id);

    /**
     * @desc Stores data.
     * @param string $id The key corresponding to what you store.
     * @param mixed $object The data you want to store
     */
    function store($id, $object);

    /**
     * @desc Deletes the data you had stored.
     * @param string $id The key
     */
    function delete($id);

    /**
     * @desc Clears all data
     */
    function clear();
}
?>