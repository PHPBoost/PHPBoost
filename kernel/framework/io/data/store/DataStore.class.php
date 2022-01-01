<?php
/**
 * This interface represents a data store. Its different implementations store data in
 * different physical areas, you have to use it according to the data's life span and the efficiency
 * you need.
 * A container can store several pieces of data, each of one has a string identifier you choose. That
 * identifier must be a string with only letters and digits.
 * @package     IO
 * @subpackage  Data\store
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 12 09
*/

interface DataStore
{
	/**
	 * Returns the data stored in the $id key.
	 * @param string $id The key (must be a string with only letters and digits)
	 * @return mixed The data that is stored
	 * @throws DataStoreException If data cannot be found.
	 */
    function get($id);

    /**
     * Tells whether the container contains the data at the key $id.
     * @param string $id The key
     * @return bool true if the container contains it, false otherwise
     */
    function contains($id);

    /**
     * Stores data.
     * @param string $id The key corresponding to what you store.
     * @param mixed $object The data you want to store
     */
    function store($id, $object);

    /**
     * Deletes the data you had stored.
     * @param string $id The key
     */
    function delete($id);

    /**
     * Clears all data
     */
    function clear();
}
?>
