<?php
/*##################################################
 *                           mysql_restriction.class.php
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

mvcimport('mvc/dao/criteria/restriction/sql_restriction');

class MySQLRestriction extends SQLRestriction
{
    public function eq($field, $value)
    {
        return  $field->name() . '=' . self::value($value);
    }
    public function gt($field, $value)
    {
        return  $field->name() . '>' . self::value($value);
    }
    public function lt($field, $value)
    {
        return  $field->name() . '<' . self::value($value);
    }
    public function gt_eq($field, $value)
    {
        return  $field->name() . '>=' . self::value($value);
    }
    public function lt_eq($field, $value)
    {
        return  $field->name() . '<=' . self::value($value);
    }
    public function neq($field, $value)
    {
        return  $field->name() . '!=' . self::value($value);
    }
    public function in($field, $values)
    {
        $nb = count(values);
        for ($i = 0; i < $nb; $i++)
        {
            $values[$i] = self::escape($value[$i]);
        }
        return $field->name() . ' IN (' . implode(',', $values) . ')';
    }
    public function between($field, $field_or_value_min, $field_or_value_max)
    {
        return $field->name() . ' BETWEEN (' . self::value($field_or_value_min) . ', ' . self::value($field_or_value_max) . ')';
    }
    public function like($field, $pattern)
    {
        return $field->name() . ' LIKE ' . self::escape($pattern);
    }
    public function match($field, $text_value)
    {
        return 'MATCH (' . $field->name() . ') AGAINST (' . self::escape($text_value) . ')';
    }

    public function and_criterions($left_restriction, $right_restriction)
    {
        return '(' . $left_restriction . ') AND (' . $right_restriction . ')';
    }
    public function or_criterions($left_restriction, $right_restriction)
    {
        return '(' . $left_restriction . ') OR (' . $right_restriction . ')';
    }
    public function not($restriction)
    {
        return 'NOT (' . $restriction . ')';
    }
    
    protected static function escape($value)
    {
    	return MySQLDAO::escape($value);
    } 
}
?>