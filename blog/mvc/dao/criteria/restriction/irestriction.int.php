<?php
/*##################################################
 *                           irestriction.int.php
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

interface IRestriction
{
    public function eq($field, $value);
    public function gt($field, $value);
    public function lt($field, $value);
    public function gt_eq($field, $value);
    public function lt_eq($field, $value);
    public function neq($field, $value);
    public function in($field, $values);
    public function between($field, $min_value, $max_value);
    public function like($field, $pattern);
    public function match($field, $text_value);

    public function and_criterions($left_restriction, $right_restriction);
    public function or_criterions($left_restriction, $right_restriction);

    public function not($restriction);
}
?>