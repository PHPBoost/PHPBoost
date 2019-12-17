<?php
/**
 * implements the query var replacement method
 * @package     IO
 * @subpackage  DB
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 11 01
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
