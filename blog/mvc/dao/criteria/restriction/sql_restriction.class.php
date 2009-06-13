<?php
/*##################################################
 *                           sql_restriction.class.php
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

mvcimport('mvc/dao/criteria/restriction/irestriction', INTERFACE_IMPORT);
mvcimport('mvc/dao/criteria/restriction/irestriction', INTERFACE_IMPORT);

abstract class SQLRestriction implements IRestriction
{	
	public function value($field_or_value)
    {
        if ($field_or_value instanceof ModelField)
        {
            return $field_or_value->name();
        }
        else
        {
            return MySQLDAO::escape($field_or_value);
        }
    }
}
?>