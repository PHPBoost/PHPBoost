<?php
/*##################################################
 *                           dao.class.php
 *                            -------------------
 *   begin                : October 2, 2009
 *   copyright            : (C) 2009 Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc Describes a simple way to interact with a datastore
 */
interface DAO
{
	const ORDER_BY_ASC = 'ASC';
	const ORDER_BY_DESC = 'DESC';

    /**
     * @desc Saves <code>$object</code> in the datastore.
     * If the object does not exist, it is created, else,  just updated
     * @param PropertiesMapInterface $object the object to save
     */
    public function save(PropertiesMapInterface $object);
    
	/**
     * @desc Deletes <code>$object</code> from the datastore.
     * If the object does not exist, nothing is done
	 * @param PropertiesMapInterface $object the object to delete
	 */
	public function delete(PropertiesMapInterface $object);

	/**
	 * @desc retrieves the object with the <code>$id</code> primary identifier
	 * @param mixed $id the object primary identifier (shoudl always be an integer)
	 * @return PropertiesMapInterface the objet retrieved from the datastore
	 * @throws ObjectNotFoundException if no objects with this id are found in the datastore
	 */
	public function find_by_id($id);
	
	/**
	 * @desc retrieves the <code>$limit</code> first objects from the <code>$offset</code> one.
	 * If <code>$order_by</code> is specified, objects will be sorted in the <code>$way</code> way
	 * @param int $limit the maximum number of objets to retrieve
	 * @param int $offset the offset from which retrieves objects
	 * @param string[mixed][] $order_by the column(s) on which sort will be done.
	 * This parameter is an array with nested associative subarrays for each column:
	 * <code>array(array('column' => $column1, 'way' => $way1), ...)</code>
	 * <code>$column1</code> must be a column name or an alias and <code>$way</code> could be
	 * ascending (<code>DAO::ORDER_BY_ASC</code>) or descending (<code>DAO::ORDER_BY_DESC</code>)
	 * @return QueryResultMapper the objects list
	 */
	public function find_all($limit = 100, $offset = 0, $order_by = array());
	
	/**
	 * @desc retrieves all the objects in the datastore matching the <code>$criteria</code>
	 * @param string $criteria the part of the query that came just after the from
	 * @param string[string] $parameters the query vars to inject into the <code>$criteria</code>
	 * @return QueryResultMapper the objects list
	 */
	public function find_by_criteria($criteria, $parameters = array());
}

?>