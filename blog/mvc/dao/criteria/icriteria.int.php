<?php
/*##################################################
 *                           icriteria.int.php
 *                            -------------------
 *   begin                : June 13 2009
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

interface ICriteria
{
	public function create_restriction();
    public function add($restriction);

    public function add_external_field($external_field);
    public function add_external_table($table_name);
    
    public function count();

    public function update(&$fields_and_values);
    public function delete();
    public function results_list($max_results = -1, $offset = 0, $order_by = null, $way = ICriteria::ASC);
    
    const ASC = 'asc';
    const DESC = 'desc';
}
?>