<?php
/*##################################################
 *                           SQLQueryVars.class.php
 *                            -------------------
 *   begin                : November 1, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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
 * @package {@package}
 * @desc implements the query var replacement method
 *
 */
class SQLQueryVars extends StringVars
{
	/**
	 * @var AbstractSQLQuerier
	 */
	private $querier;

	public function __construct(AbstractSQLQuerier $querier)
	{
        $this->querier = $querier;
        $strict = true;
        parent::__construct($strict);
	}

	protected function set_var($parameter)
    {
    	if ($parameter === null)
    	{
    		return 'NULL';
    	}
    	elseif (is_array($parameter))
        {
            $nb_value = count($parameter);
            for ($i = 0; $i < $nb_value; $i++)
            {
                $parameter[$i] = '\'' . $this->querier->escape($parameter[$i]) . '\'';
            }
            return '(' . implode(', ', $parameter) . ')';
        }
        elseif (is_string($parameter))
        {
        	return '\'' . $this->querier->escape($parameter) . '\'';
        }
        else
        {
        	return $parameter;
        }
    }
}
?>