<?php
/*##################################################
 *                             com.class.php
 *                            -------------------
*   begin                : April 08, 2008
 *   copyright          : (C) 2008 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

define('NOTE_DISPLAY_NOTE', 0x01);
define('NOTE_NODISPLAY_NBRNOTES', 0x02);
define('NOTE_DISPLAY_BLOCK', 0x04);

class Note
{
	## Public Methods ##
	//Constructeur.
	function Note($script, $idprov, $vars, $notation_scale, $module_folder = '', $options = 0) 
	{
		$this->module_folder = !empty($module_folder) ? securit($module_folder) : securit($script);
		$this->options = (int)$options;
		list($this->script, $this->idprov, $this->vars, $this->notation_scale, $this->path) = array(securit($script), numeric($idprov), $vars, $notation_scale, '../' . $this->module_folder . '/');
		$this->sql_table = $this->get_table_module();
	}
	
	//Ajoute une note.
	function Add_note($note)
	{
		global $Sql, $Member;
		
		if( $Member->Check_level(MEMBER_LEVEL) )
		{
			$check_note = ($note >= 0 && $note <= $this->notation_scale) ? true : false; //Validité de la note.			
			$row_note = $Sql->Query_array($this->sql_table, 'users_note', 'nbrnote', 'note', "WHERE id = '" . $this->idprov . "'", __LINE__, __FILE__);
			$user_id = $Member->Get_attribute('user_id');
			$array_users_note = explode('/', $row_note['users_note']);
			if( !in_array($user_id, $array_users_note) && $check_note ) //L'utilisateur n'a pas déjà voté, et la note est valide.
			{
				$note = (($row_note['note'] * $row_note['nbrnote']) + $note)/($row_note['nbrnote'] + 1);
				$users_note = !empty($row_note['users_note']) ? $row_note['users_note'] . '/' . $user_id : $user_id; //On ajoute l'id de l'utilisateur.
				
				$Sql->Query_inject("UPDATE ".PREFIX.$this->sql_table." SET note = '" . $note . "', nbrnote = nbrnote + 1, users_note = '" . $users_note . "' WHERE id = '" . $this->idprov . "'", __LINE__, __FILE__);
				
				return 'get_note = ' . $note . ';get_nbrnote = ' . ($row_note['nbrnote'] + 1) . ';';
			}
			else
				return -1;
		}
		else
			return -2;
	}
	
	//Vérifie que le système de commentaires est bien chargé.
	function Note_loaded()
	{
		global $Errorh;
		
		if( empty($this->sql_table) ) //Erreur avec le module non prévu pour gérer les commentaires.
			$Errorh->Error_handler('e_unexist_page', E_USER_REDIRECT);
		
		return (!empty($this->script) && !empty($this->idprov) && !empty($this->vars));
	}
	
	//Accesseur
	function Get_attribute($varname)
	{
		return $this->$varname;
	}
	
	## Private Methods ##
	//Récupération de la table du module associée aux notes.
	function get_table_module()
	{
		global $Sql, $CONFIG;

		//Récupération des informations sur le module.
		$info_module = load_ini_file('../' . $this->module_folder . '/lang/', $CONFIG['lang']);
		$check_script = false;
		if( isset($info_module['note']) )
		{
			if( $info_module['note'] == $this->script )
			{
				$idprov = $Sql->Query("SELECT id FROM ".PREFIX.$info_module['note']." WHERE id = '" . $this->idprov . "'", __LINE__, __FILE__);
				if( $idprov == $this->idprov )
					$check_script = true;
			}
		}
		
		return $check_script ? $info_module['note'] : 0;
	}
	
	## Private attributes ##
	var $script = '';
	var $idprov = 0;
	var $path = '';
	var $vars = '';
	var $module_folder = '';
	var $options = '';
	var $sql_table = '';
	var $notation_scale = '';
}

?>