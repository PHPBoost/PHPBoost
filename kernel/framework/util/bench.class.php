<?php
/*##################################################
*                                bench.class.php
*                            -------------------
*   begin                : March 14, 2006
*   copyright          : (C) 2005 Viarre Régis
*   email                : crowkait@phpboost.com
*
*   Function 2.0.0 
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

class Bench
{
    ## Public Methods ##
    function Bench($name) { $this->start = $this->_get_microtime(); }
    
    //Calcul et retourne le temps écoulé.
    function stop($name) { $this->duration = $this->_get_microtime() - $this->start; }
    
    //Calcul et retourne le temps écoulé.
    function display($name) { return number_round($this->duration, $this->number_format); }
    
    ## Private Methods ##
    //Récupère l'heure en microsecondes 
    function _get_microtime() 
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
    
    ## Private Attributes ##
    var $start = 0;
    var $duration = 0;
    var $number_format = 3; //Nombre de décimales après la virgule, affichées dans le résultat.
}

?>
