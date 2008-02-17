<?php
/*##################################################
 *                                speed_bar.class.php
 *                            -------------------
 *   begin                : February 16, 2007
 *   copyright          : (C) 2007 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *   Speed_bar 2.0
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

class Speed_bar
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
	function Display_speed_bar()
	{
		global $template, $CONFIG, $LANG;
		
		if( empty($this->array_links) )
			$this->Add_link(stripslashes(TITLE), HOST . SCRIPT . SID);
		
		$template->set_filenames(array(
			'speed_bar' => '../templates/' . $CONFIG['theme'] . '/speed_bar.tpl'
		));
			
		$template->assign_vars(array(
			'START_PAGE' => get_start_page(),
			'L_INDEX' => $LANG['index']	
		));
		
		foreach($this->array_links as $key => $array)
		{
			$template->assign_block_vars('link_speed_bar', array(
				'URL' => $array[1],
				'TITLE' => $array[0]
			));	
		}

		$template->pparse('speed_bar');
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