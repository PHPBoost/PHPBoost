<?php
/*##################################################
 *                           SQLFragment.class.php
 *                            -------------------
 *   begin                : February 27, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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
 */
class SQLFragment
{
	private $query;
	private $parameters;
	
	public function __construct($query = '', array $parameters = array())
	{
		$this->query = $query;
		$this->parameters = $parameters;
	}
	
	/**
	 * @return string
	 */
	public function get_query()
	{
		return $this->query;
	}
	
	/**
	 * @return mixed[string]
	 */
	public function get_parameters()
    {
        return $this->parameters;
    }
    
    /**
     * @desc Adds the fragment parameters to the <code>$parameters</code> map
     * @param mixed[string] $parameters the parameters that will be filled
     */
    public function add_parameters_to_map(array & $parameters)
    {
    	foreach ($this->parameters as $parameter_name => $parameter_value)
    	{
    		$parameters[$parameter_name] = $parameter_value;
    	}
    }
}
?>