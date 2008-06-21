<?php

/*##################################################
*                      module_interface.class.php
*                            -------------------
*   begin                : January 15, 2008
*   copyright            :(C) 2008 Loïc Rouchon
*   email                : horn@phpboost.com
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

define('MODULE_NOT_AVAILABLE', 1);
define('ACCES_DENIED', 2);
define('MODULE_NOT_YET_IMPLEMENTED', 4);
define('FUNCTIONNALITY_NOT_IMPLEMENTED', 8);
define('MODULE_ATTRIBUTE_DOES_NOT_EXIST', 16);

class ModuleInterface
{
    //-------------------------------------------------------------- CONSTRUCTOR
    function ModuleInterface($moduleId = '', $error = 0)
    /**
     *  Module constructor
     */
    {
        global $CONFIG;
        $this->id = $moduleId;
        $this->name = $this->id;
        $this->attributes =array();
        $this->infos = array();
        $this->functionnalities = array();
        if( $error == 0 )
        {
            // Get the config.ini informations
            $this->infos = load_ini_file(PATH_TO_ROOT . '/'.$this->id.'/lang/', $CONFIG['lang']);
            if ( isset($this->infos['name']) )
                $this->name = $this->infos['name'];
            
            // Get modules methods
            $methods = get_class_methods(ucfirst($moduleId).'Interface'); // PHP4 returns it in lower case
            // generics module Methods from ModuleInterface
            $moduleMethods = get_class_methods('ModuleInterface'); // PHP4 returns it in lower case
            
            // Delete all generics private methods
            foreach($methods as $key => $function)
            {
                if( !(in_array($function, $moduleMethods) || $function == $moduleId.'interface') )
                    array_push($this->functionnalities, strtolower($methods[$key]));
            }
        }
        $this->errors = $error;
    }
    
    //----------------------------------------------------------- PUBLIC METHODS
    function get_id()
    /**
     *  Return the name of the module
     */
    {
        return $this->id;
    }
    
    function get_name()
    /**
     *  Return the name of the module
     */
    {
        return $this->name;
    }
    
    function get_infos()
    /**
     *  Return all informations that you could find in the .ini file of the module,
     *  his functionnalities and his name
     */
    {
        return array('name' => $this->name,
            'infos' => $this->infos,
            'functionnalities' => $this->functionnalities,
        );
    }

    function get_attribute($attribute)
    /**
     *  Return the value of the attribute identified by the string <$attribute>
     *  if existing. Else set the <MODULE_ATTRIBUTE_DOES_NOT_EXIST> flag and
     *  return <-1>
     */
    {
        $this->_clear_error(MODULE_ATTRIBUTE_DOES_NOT_EXIST);
        if ( isset($this->attributes[$attribute]) )
            return $this->attributes[$attribute];

        // else
        $this->_set_error(MODULE_ATTRIBUTE_DOES_NOT_EXIST);
        return -1;
    }
    
    function set_attribute($attribute, $value)
    /**
     *  Set the value of the attribute identified by the string <$attribute>.
     */
    {
        $this->attributes[$attribute] = $value;
    }
    
    function unset_attribute($attribute)
    /**
     *  Delete the attribute and free the memory of it.
     */
    {
        unset($this->attributes[$attribute]);
    }
    
    function got_error($error = 0)
    /**
     *  If called with no arguments, return <true> if an error has occured
     *  otherwise, <false>.
     *  If the method got an argument, return <true> if the specified <$error>
     *  has occured otherwise, <false>.
     */
    {
        if ( $error == 0 )
            return $this->errors != 0;
        else
            return ($this->errors & $error) != 0;
    }
    
    function get_errors()
    /**
     *  Return the errors flags
     */
    {
        return $this->errors;
    }
    
    function functionnality($functionnality, $args = null)
    /**
     *  Test the existance of the functionnality and if exist call it.
     *  If she's not available, the FUNCTIONNALITY_NOT_IMPLEMENTED flag is set.
     */
    {
        $this->_clear_error(FUNCTIONNALITY_NOT_IMPLEMENTED);
        if( $this->has_functionnality($functionnality) )
            return $this->$functionnality($args);
        else
            $this->_set_error(FUNCTIONNALITY_NOT_IMPLEMENTED);
    }
    
    function has_functionnality($functionnality)
    /**
     *  Test the availability of the functionnality
     */
    {
        return in_array(strtolower($functionnality), $this->functionnalities);
    }
    
    function has_functionnalities($functionnalities)
    /**
     *  Test the availability of all the functionnalities
     */
    {
        $nbFunctionnalities = count($functionnalities);
        for ( $i = 0; $i < $nbFunctionnalities; $i++ )
            $functionnalities[$i] = strtolower($functionnalities[$i]);
        return $functionnalities === array_intersect($functionnalities, $this->functionnalities);
    }
    
    //------------------------------------------------------------------ PRIVATE
	/**
	 *  For compatibility reasons with PHP4, the private, protected and public
	 *  keywords are not used.
	 *
	 *  So please, even if it's possible, do NOT call these methods.
	 *
     *  At your own risk!
	 */
	//-------------------------------------------------------- PROTECTED METHODS
	function _set_error($error = 0)
	/**
	*  Set the flag error.
	*/
	{
		$this->errors |= $error;
	}
	
	function _clear_error($error)
	/**
	*  Clean the functionnality flag
	*/
	{
		$this->errors &= (~$error);
	}
	
	//----------------------------------------------------- PROTECTED ATTRIBUTES
    var $id;
    var $name;
	var $infos;
	var $functionnalities;
	var $errors;
    var $attributes;
}

?>
