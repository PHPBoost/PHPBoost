<?php
/*##################################################
 *                           string_var.class.php
 *                            -------------------
 *   begin                : October 15, 2009
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
 * @desc implements the string var replacement method
 *
 */
class StringVars
{
	private $parameters;
	private $strict;
	
    public static function replace_vars($string, array $parameters, $strict = false)
    {
    	if (empty($parameters))
    	{
    		return $string;
    	}
    	$string_var = new StringVars($strict);
    	return $string_var->replace($string, $parameters);
    }
    
    public function __construct($strict = false)
    {
    	$this->strict = $strict;
    }
    
    public function replace($string, array $parameters)
    {
    	$this->parameters = $parameters;
        return preg_replace_callback('`:([a-z][\w_]+)`i', array($this, 'replace_var'), $string);
    }
    
    private function replace_var($captures)
    {
        $varname =& $captures[1];
        if (array_key_exists($varname, $this->parameters))
        {
            return $this->set_var($this->parameters[$varname]);
        }
        if ($this->strict)
        {
        	throw new RemainingStringVarException($varname);
        }
        else
        {
        	return ':' . $varname;
        }
    }
    
    protected function set_var($parameter)
    {
        return $parameter;
    }
}
?>