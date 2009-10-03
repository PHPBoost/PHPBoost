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

interface DAO
{
    const ORDER_BY_ASC = 0x01;
    const ORDER_BY_DESC = 0x01;
	
	public function delete($object);
	public function save(PropertiesMapInterface $object);

	public function find_by_id($id);
	public function find_by_criteria($criteria, $parameters = array());
	public function find_all($limit = 100, $offset = 0, $order_by = null, $way = self::ORDER_BY_ASC);
}

class ValidationException extends Exception {}

?>