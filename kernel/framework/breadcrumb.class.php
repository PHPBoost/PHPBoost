<?php
/*##################################################
 *                                bread_crumb.class.php
 *                            -------------------
 *   begin                : February 16, 2007
 *   copyright          : (C) 2007 Sautel Benoit
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

class Bread_crumb
{
	##  Méthodes publiques  ##
	//Ajout d'un lien
	function Add_link($text, $target)
	{
		if( !empty($text) )
		{
			$this->array_links[] = array(stripslashes($text), $target);
			return true;
		}
		else
			return false;
	}
	
	//Inversion de l'ordre des liens
	function Reverse_links()
	{
		$this->array_links = array_reverse($this->array_links);
	}
	
	//Inversion de l'ordre des liens
	function Remove_last_link()
	{
		array_pop($this->array_links);
	}
	
	//Affichage
	function Display_bread_crumb()
	{
		global $Template, $CONFIG, $LANG;
		
		if( empty($this->array_links) )
			$this->Add_link(stripslashes(TITLE), HOST . SCRIPT . SID);
		
		$Template->Assign_vars(array(
			'START_PAGE' => get_start_page(),
			'L_INDEX' => $LANG['index']	
		));
		
		foreach($this->array_links as $key => $array)
		{
			$Template->Assign_block_vars('link_bread_crumb', array(
				'URL' => $array[1],
				'TITLE' => $array[0]
			));	
		}
	}
	
	//Suppression des liens existants
	function Clean_links()
	{
		$this->array_links = array();
	}
	
	## Attributs protégés ##
	var $array_links = array();
}

?>