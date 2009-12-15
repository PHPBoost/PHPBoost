<?php

/**
 *                      module_interface.class.php
 *                            -------------------
 *   begin                : January 15, 2008
 *   copyright            : (C) 2008 Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
 *   
 *
 *###################################################
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
 *###################################################
 */

define('MODULE_NOT_AVAILABLE', 1);
define('ACCES_DENIED', 2);
define('MODULE_NOT_YET_IMPLEMENTED', 4);
define('FUNCTIONNALITY_NOT_IMPLEMENTED', 8);
define('MODULE_ATTRIBUTE_DOES_NOT_EXIST', 16);

/**
 * @author Loïc Rouchon <loic.rouchon@phpboost.com>
 * @desc This Class allow you to call methods on a ModuleInterface extended class
 * that you're not sure of the method's availality. It also provides a set of
 * generic methods that you could use to integrate your module with others, or
 * allow your module to share services.
 * @package modules
 */
class ModuleInterface
{
	protected $sql_querier;

	/**
     * @access protected
     * @var string the module identifier
     */
    protected $id;
    /**
     * @access protected
     * @var string the module full name
     */
    protected $name;
    /**
     * @access protected
     * @var mixed module's informations contained in ini files
     */
    protected $infos;
    /**
     * @access protected
     * @var string[] list of the functionalities provided
     */
    protected $functionalities;
    /**
     * @access protected
     * @var int error flag
     */
    protected $errors;
    /**
     * @access protected
     * @var mixed[string] the attributes dictionary
     */
    protected $attributes;

    protected $enabled = false;
    
    /**
     * @desc ModuleInterface constructor
     * @param string $moduleId the module id. It's the name of the folder in witch the module is
     * @param int $error allow you to instanciate your module with an error code
     */
    public function __construct($moduleId = '', $error = 0)
    {
        global $CONFIG, $MODULES;
        $this->id = $moduleId;
        $this->name = $this->id;
        $this->attributes =array();
        $this->infos = array();
        $this->functionalities = array();
        $this->enabled = !empty($MODULES[strtolower($this->get_id())]) && ($MODULES[strtolower($this->get_id())]['activ'] == '1');

        // Get the config.ini informations
        $this->infos = load_ini_file(PATH_TO_ROOT . '/' . $this->id . '/lang/', get_ulang());
        if (isset($this->infos['name']))
        {
        	$this->name = $this->infos['name'];
        }

        if ($error == 0)
        {
            $class = ucfirst($moduleId).'Interface';
            // Get modules methods
            $module_methods = get_class_methods($class); // PHP4 returns it in lower case
            // generics module Methods from ModuleInterface
            $generics_methods = get_class_methods('ModuleInterface'); // PHP4 returns it in lower case
            $generics_methods[] = $class;
            
            $methods_diff = array_diff($module_methods, $generics_methods);
            
            // keep only public methods from the functionalities list
            foreach ($methods_diff as $method)
            {
                if (substr($method, 0, 1) != '_')
                {
                	$this->functionalities[] = $method;
                }
            }
            $this->functionalities[] = 'none';
        }
        $this->errors = $error;
        
        $this->sql_querier = AppContext::get_sql();
    }

    /**
     * @return string Return the id of the module
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * @return bool Return the true if the module is enabled
     */
    public function is_enabled()
    {
        return $this->enabled;
    }
    
    /**
     * @return string Return the name of the module
     */
    public function get_name()
    {
        return $this->name;
    }

    /**
     * @return mixed[] All informations that you could find in the .ini file of the module,  his functionalities and his name
     */
    public function get_infos()
    {
        return array(
            'name' => $this->name,
            'infos' => $this->infos,
            'functionalities' => $this->functionalities,
        );
    }

    /**
     * @param $attribute the attribute identifier in the dictionary
     * @return mixed The value of the attribute identified by the string $attribute
     *  in the intern dictionary if existing. Else, the MODULE_ATTRIBUTE_DOES_NOT_EXIST flag is raised and it
     *  returns -1
     */
    public function get_attribute($attribute)

    {
        $this->_clear_error(MODULE_ATTRIBUTE_DOES_NOT_EXIST);
        if ( isset($this->attributes[$attribute]) )
            return $this->attributes[$attribute];
        
        $this->_set_error(MODULE_ATTRIBUTE_DOES_NOT_EXIST);
        return -1;
    }

    /**
     * @desc Set the $value of the attribute identified by the string $attribute.
     * @param string $attribute the attribute identifier
     * @param mixed $value the value to set
     */
    public function set_attribute($attribute, $value)
    {
        $this->attributes[$attribute] = $value;
    }

    /**
     * @desc Delete the attribute and free its memory.
     * @param string $attribute the attribute identifier
     */
    public function unset_attribute($attribute)
    {
        unset($this->attributes[$attribute]);
    }

    /**
     * @desc Returns the last error. If called with no arguments, returns true if an error has occured
     *  otherwise, false. If the method got an argument,
     * @param int $error to check a specific error, 0 otherwise
     * @return returns true if the specified $error has occured otherwise, false.
     */
    public function got_error($error = 0)
    {
        if ( $error == 0 )
            return $this->errors != 0;
        else
            return ($this->errors & $error) != 0;
    }

    /**
     * @return int Returns the current errors flags
     */
    public function get_errors()
    {
        return $this->errors;
    }

    /**
     * @desc Check the existance of the functionality and if exists call it.
     *  If she's not available, the FUNCTIONNALITY_NOT_IMPLEMENTED flag is raised.
     * @param string $functionality the name of the method you want to call
     * @param mixed $args the args you want to pass to the $functionality method
     * @return mixed the $functionality returns or if non-existing, false
     */
    public function functionality($functionality, $args = null)
    {
        $this->_clear_error(FUNCTIONNALITY_NOT_IMPLEMENTED);
        if ($this->has_functionality($functionality))
            return $this->$functionality($args);
        $this->_set_error(FUNCTIONNALITY_NOT_IMPLEMENTED);
        return false;
    }

    /**
     * @desc Check the availability of the functionality (hook)
     * @param string $functionality the name of the method you want to check the availability
     * @return bool true if the functionality exists, false otherwise
     */
    public function has_functionality($functionality)
    {
        return in_array(strtolower($functionality), $this->functionalities);
    }

    /**
     * @desc Check the availability of the functionalities (hook)
     * @param string[] $functionalities the names of the methods you want to check the availability
     * @return bool true if all functionalities exist, false otherwise
     */
    public function has_functionalities($functionalities)
    {
        $nbFunctionnalities = count($functionalities);
        for ( $i = 0; $i < $nbFunctionnalities; $i++ )
            $functionalities[$i] = strtolower($functionalities[$i]);
        return $functionalities === array_intersect($functionalities, $this->functionalities);
    }


    /**
     * @desc Set the flag error.
     * @param int $error the error flag to raised
     */
    public function set_error($error = 0)
    {
        $this->errors |= $error;
    }
    
    /**
     * @desc Clear the $error error flag
     * @param int $error the error flag to clear
     */
    private function _clear_error($error)
    {
        $this->errors &= (~$error);
    }
}

?>
