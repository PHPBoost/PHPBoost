<?php
/*##################################################
 *                           bread_crumb.class.php
 *                            -------------------
 *   begin                : February 16, 2007
 *   copyright            : (C) 2007 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *   Bread_crumb 2.0
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

class BreadCrumb
{
	##  Méthodes publiques  ##
    function BreadCrumb() { }
    
	//Ajout d'un lien
	function add($text, $target = '')
	{
		if (!empty($text))
		{
			$this->array_links[] = array($text, $target);
			return true;
		}
		else
			return false;
	}
	
	//Inversion de l'ordre des liens
	function reverse()
	{
		$this->array_links = array_reverse($this->array_links);
	}
	
	//Inversion de l'ordre des liens
	function remove_last()
	{
		array_pop($this->array_links);
	}
	
	//Affichage
	function display()
	{
		global $Template, $CONFIG, $LANG;
		
		if (empty($this->array_links))
			$this->add(stripslashes(TITLE), HOST . SCRIPT . SID);
		
		$Template->assign_vars(array(
			'START_PAGE' => get_start_page(),
			'L_INDEX' => $LANG['home']	
		));
		
		foreach ($this->array_links as $key => $array)
		{
			$Template->assign_block_vars('link_bread_crumb', array(
				'URL' => $array[1],
				'TITLE' => $array[0]
			));	
		}
	}
	
	//Suppression des liens existants
	function clean()
	{
		$this->array_links = array();
	}
	
	## Attributs protégés ##
	var $array_links = array();
}

?>