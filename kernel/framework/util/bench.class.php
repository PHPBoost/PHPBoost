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

//Classe gérant le multi-bench, permet de calculer très précisemment le temps d'execution d'un ou plusieurs morceau de code.
class Bench
{
	## Public Methods ##
	//Lancement du bench.
	function Start_bench($name)
	{
		$this->bench_part[$name] = $this->get_microtime();
	}

	//Calcul et retourne le temps écoulé.
	function End_bench($name)
	{
		$this->bench[$name] = isset($this->bench[$name]) ? $this->bench[$name] : 0;
		$this->bench[$name] += ($this->get_microtime() - $this->bench_part[$name]);
	}
	
	//Calcul et retourne le temps écoulé.
	function Display_bench($name)
	{
		return number_round($this->bench[$name], $this->number_format);
	}
	
	
	## Private Methods ##
	//Récupère l'heure en microsecondes 
	function get_microtime() 
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
	
	## Private Attributes ##
	var $bench = array(); //Array contenant le total du temps d'execution des différents bench en cours.
	var $bench_part = array(); //Array contenant les différentes parties du bench en cours.
	var $number_format = 3; //Nombre de décimales après la virgule, affichées dans le résultat.
}

?>
