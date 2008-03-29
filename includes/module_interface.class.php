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

class ModuleInterface
{
    //-------------------------------------------------------------- CONSTRUCTOR
    function ModuleInterface($moduleName = '', $error = 0)
    /**
     *  Module constructor
     */
    {
        global $CONFIG;
        $this->name = $moduleName;
        $this->attributes =array();
        $this->infos = array();
        $this->functionnalities = array();
        if( $error == 0 )
        {
            // Get the config.ini informations
            $this->infos = load_ini_file('../' . $this->name . '/lang/', $CONFIG['lang']);
            
            // Get modules methods
            $methods = get_class_methods(ucfirst($moduleName).'Interface'); // PHP4 returns it in lower case
            // generics module Methods from ModuleInterface
            $moduleMethods = get_class_methods('ModuleInterface'); // PHP4 returns it in lower case
            
            // Delete all generics private methods
            foreach($methods as $key => $function)
            {
                if( !(in_array($function, $moduleMethods) || $function == $moduleName.'interface') )
                    array_push($this->functionnalities, strtolower($methods[$key]));
            }
        }
        $this->errors = $error;
    }
    
	//----------------------------------------------------------- PUBLIC METHODS
    function GetName()
    /**
     *  Return the name of the module
     */
    {
        return $this->name;
    }

    function GetInfo()
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

    function GetAttribute($attribute)
    /**
     *  Return the value of the attribute identified by the string <$attribute>
     */
    {
        return $this->attributes[$attribute];
    }
    
    function SetAttribute($attribute, $value)
    /**
     *  Set the value of the attribute identified by the string <$attribute>
     */
    {
        $this->attributes[$attribute] = $value;
    }
    
    function GetErrors()
    /**
     *  Renvoie un integer contenant des bits d'erreurs.
     */
    {
        return $this->errors;
    }
    
    function Functionnality($functionnality, $args = null)
    /**
     *  Test the existance of the functionnality and if exist call it.
     *  If she's not available, the FUNCTIONNALITY_NOT_IMPLEMENTED flag is set.
     */
    {
        $this->clearFunctionnalityError();
        if( $this->HasFunctionnality($functionnality) )
            return $this->$functionnality($args);
        else
            $this->setError(FUNCTIONNALITY_NOT_IMPLEMENTED);
    }
    
    function HasFunctionnality($functionnality)
    /**
     *  Test the availability of the functionnality
     */
    {
        return in_array(strtolower($functionnality), $this->functionnalities);
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
	function setError($error = 0)
	/**
	*  Set the flag error.
	*/
	{
		$this->errors |= $error;
	}
	
	function clearFunctionnalityError()
	/**
	*  Clean the functionnality flag
	*/
	{
		$this->errors &= (~FUNCTIONNALITY_NOT_IMPLEMENTED);
	}
	
	//----------------------------------------------------- PROTECTED ATTRIBUTES
	var $name;
	var $infos;
	var $functionnalities;
	var $errors;
    var $attributes;
}

?>
