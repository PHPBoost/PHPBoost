<?php
/*##################################################
 *                             com.class.php
 *                            -------------------
 *   begin                : March 08, 2008
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

class Note
{
	## Public Methods ##
	//Constructeur.
	function Note($script, $idprov, $vars, $module_folder = '') 
	{
		$path = !empty($path) ? $path : '%d';
		$this->module_folder = !empty($module_folder) ? securit($module_folder) : securit($script);
		list($this->script, $this->idprov, $this->vars, $this->path) = array(securit($script), numeric($idprov), $vars, '../' . $this->module_folder . '/');
	}
	
	//Ajoute une note.
	function Add_note($note, $notation_scale)
	{
		global $Sql, $Member;
		
		//Echelle de notation.
		$check_note = ( ($note >= 0) && ($note <= $CONFIG_WEB['note_max']) ) ? true : false;				
		$users_note = $Sql->Query("SELECT users_note FROM ".PREFIX."web WHERE idcat = '" . $idcat . "' AND id = '" . $get_note . "'", __LINE__, __FILE__);
		$user_id = $Member->Get_attribute('user_id');
		
		$array_users_note = explode('/', $users_note);
		if( !in_array($user_id, $array_users_note) && !empty($user_id) && $check_note )
		{
			$row_note = $Sql->Query_array($this->sql_table, 'users_note', 'nbrnote', 'note', "WHERE id = '" . $get_note . "'", __LINE__, __FILE__);
			$note = (($row_note['note'] * $row_note['nbrnote']) + $note)/($row_note['nbrnote'] + 1);
			$row_note['nbrnote']++;
			
			$users_note = !empty($row_note['users_note']) ? $row_note['users_note'] . '/' . $user_id : $user_id; //On ajoute l'id de l'utilisateur.
			
			$Sql->Query_inject("UPDATE ".PREFIX.$this->sql_table." SET note = '" . $note . "', nbrnote = '" . $row_note['nbrnote'] . "', users_note = '" . $users_note . "' WHERE id = '" . $get_note . "' AND idcat = '" . $idcat . "'", __LINE__, __FILE__);
			
			return true;
		}		
		
		return false;
	}
	
	//Met à jour l'id du commentaire.
	function Set_arg($idcom, $path = '')
	{
		if( !empty($path) )
			$this->path = $path;
		$this->sql_table = $this->get_info_module();
	}
	
	//Récupération des attributs de l'objet.
	function Get_arg()
	{
		return array($this->script, $this->idprov, $this->vars);
	}
	
	//Vérifie que le système de commentaires est bien chargé.
	function Note_loaded()
	{
		return (!empty($this->script) && !empty($this->idprov) && !empty($this->vars));
	}
	
	//Accesseur
	function Get_attribute($varname)
	{
		return $this->$varname;
	}
	
	## Private Methods ##
	//Récupération de la table du module associée aux commentaires.
	function get_info_module()
	{
		global $Sql, $CONFIG;

		//Récupération des informations sur le module.
		$info_module = load_ini_file('../' . $this->module_folder . '/lang/', $CONFIG['lang']);
		$check_script = false;
		if( isset($info_module['com']) )
		{
			if( $info_module['com'] == $this->script )
			{
				$info_sql_module = $Sql->Query_array(securit($info_module['com']), "id", "nbr_com", "lock_com", "WHERE id = '" . $this->idprov . "'", __LINE__, __FILE__);
				if( $info_sql_module['id'] == $this->idprov )
					$check_script = true;
			}
		}
		return $check_script ? array(securit($info_module['com']), $info_sql_module['nbr_com'], (bool)$info_sql_module['lock_com']) : array('', 0, 0);
	}
	
	## Private attributes ##
	var $script = '';
	var $idprov = 0;
	var $path = '';
	var $vars = '';
	var $module_folder = '';
	var $sql_table = '';
}

?>