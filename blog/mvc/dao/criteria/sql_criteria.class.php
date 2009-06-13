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

mvcimport('mvc/dao/criteria/icriteria');

abstract class SQLCriteria implements ICriteria
{
    public function __construct($model, $connection)
    {
        $this->model = $model;
        $this->connection = $connection;
    }

    public function add($restriction)
    {
        $this->restrictions[] = $restriction;
    }

    public function set_fetch_mode($fetch_attribute, $mode) {}
    public function set_projection($projection) {}
    public function set_max_results($max_results)
    {
        if (is_numeric($max_results))
        {
            $max_results =  numeric($max_results);
            if (is_integer($max_results) && $max_results > 0)
            {
                $this->max_results = $max_results;
                return;
            }
        }
        throw new InvalidArgumentException('ICriteria->set_max_results($max_results): $max_results must be a strictly positive integer');
    }
    public function set_offset($offset)
    {
        if (is_numeric($offset))
        {
            $offset =  numeric($offset);
            if (is_integer($offset) && $offset >= 0)
            {
                $this->offset = $offset;
                return;
            }
        }
        throw new InvalidArgumentException('ICriteria->set_offset($offset): $offset must be a positive integer');
    }

    protected function fields($fields_options = null)
    {
        return '*';
    }

    protected function build_object($row)
    {
        $classname = $this->model->name();
        $object = new $classname();
        foreach ($row as $field_name => $value)
        {
            $setter = $this->model->field($field_name)->setter();
            $object->$setter($value);
        }
        return $object;
    }

    protected $model;
    protected $restrictions = array();
    protected $offset = 0;
    protected $max_results = 100;
}
?>