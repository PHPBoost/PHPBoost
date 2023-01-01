<?php
/**
 * Describes a simple way to interact with a table
 * @package     MVC
 * @subpackage  Model
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 02
*/

interface DAO
{
	const FIND_ALL = 0;
	const WHERE_ALL = 'WHERE 1';

    /**
     * Saves <code>$object</code> in the table.
     * If the object does not exist, it is created, else,  just updated
     * @param PropertiesMapInterface $object the object to save
     */
    function save(PropertiesMapInterface $object);

    /**
     * Update all fields in the table to the given value if row match the where clause.
     * @param mixed[string] $fields keys are the fields names to update, values, their new value
     * @param string $where the part of the query that came just after the from
     * @param string[string] $parameters the query vars to inject into the <code>$where</code>
     */
    function update(array $fields, $where = DAO::WHERE_ALL, array $parameters = array());

    /**
     * Deletes <code>$object</code> from the table.
     * If the object does not exist, nothing is done
     * @param PropertiesMapInterface $object the object to delete
     */
    function delete(PropertiesMapInterface $object);

    /**
     * Deletes all object matching the where clause from the table.
     * @param string $where the part of the query that came just after the from
     * @param string[string] $parameters the query vars to inject into the <code>$where</code>
     */
    function delete_all($where = DAO::WHERE_ALL, array $parameters = array());

    /**
     * Count the number of object in the table matching the <code>$where</code> clause
     * @param string $where the part of the query that came just after the from
     * @param string[string] $parameters the query vars to inject into the <code>$where</code>
     */
    function count($where = DAO::WHERE_ALL, array $parameters = array());

	/**
	 * retrieves the object with the <code>$id</code> primary identifier
	 * @param mixed $id the object primary identifier (shoudl always be an integer)
	 * @return PropertiesMapInterface the objet retrieved from the table
	 * @throws ObjectNotFoundException if no objects with this id are found in the table
	 */
	function find_by_id($id);

	/**
	 * retrieves the <code>$limit</code> first objects from the <code>$offset</code> one.
	 * If <code>$order_by</code> is specified, objects will be sorted in the <code>$way</code> way
	 * @param int $limit the maximum number of objets to retrieve
	 * @param int $offset the offset from which retrieves objects
	 * @param string[mixed][] $order_by the column(s) on which sort will be done.
	 * This parameter is an array with nested associative subarrays for each column:
	 * <code>array(array('column' => $column1, 'way' => $way1), ...)</code>
	 * <code>$column1</code> must be a column name or an alias and <code>$way</code> could be
	 * ascending (<code>SQLQuerier::ORDER_BY_ASC</code>) or descending (<code>SQLQuerier::ORDER_BY_DESC</code>)
	 * @return QueryResultMapper the objects list
	 */
	function find_all($limit = 100, $offset = 0, $order_by = array());

	/**
	 * retrieves all the objects in the table matching the <code>$criteria</code>
	 * @param string $criteria the part of the query that came just after the from
	 * @param string[string] $parameters the query vars to inject into the <code>$criteria</code>
	 * @return QueryResultMapper the objects list
	 */
	function find_by_criteria($criteria, $parameters = array());
}
?>
