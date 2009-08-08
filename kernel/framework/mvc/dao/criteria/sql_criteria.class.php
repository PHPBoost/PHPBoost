<?php
/*##################################################
 *                           sql_criteria.class.php
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

import('mvc/dao/criteria/icriteria', INTERFACE_IMPORT);

abstract class SQLCriteria implements ICriteria
{
    public function __construct($model, $connection)
    {
        $this->model = $model;
        $this->connection = $connection;
        $this->order_by = $model->primary_key()->name();
        $this->tables[] = $this->model->table();
    }

    public function add($restriction)
    {
        $this->restrictions[] = $restriction;
    }
    
    public function add_external_field($external_field)
    {
    	$this->extra_fields[] = $extra_field;
    }
    public function add_external_table($table_name)
    {
    	if (!in_array($table_name, $this->tables))
    	{
    	   $this->tables[] = $table_name;
    	}
    }

    protected function fields($fields_options = null)
    {
        return '*';
    }

    protected $model;
    protected $restrictions = array();
    protected $offset = 0;
    protected $max_results = 100;
    protected $order_by;
    protected $way = ICriteria::ASC;
    protected $extra_fields = array();
    protected $tables = array();
}
?>