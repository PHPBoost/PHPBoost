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

class Comments
{
	## Public Methods ##
	//Constructeur.
	function Comments($script, $idprov, $vars, $module_folder = '') 
	{
		$this->module_folder = !empty($module_folder) ? securit($module_folder) : securit($script);
		list($this->script, $this->idprov, $this->vars, $this->path) = array(securit($script), numeric($idprov), $vars, '../' . $this->module_folder . '/');
	}
	
	//Ajoute un commentaire et retourne l'identifiant inséré.
	function Add_com($contents, $login)
	{
		global $Sql, $Member;
		
		$Sql->Query_inject("INSERT INTO ".PREFIX."com (idprov, login, user_id, contents, timestamp, script, path) VALUES('" . $this->idprov . "', '" . $login . "', '" . $Member->Get_attribute('user_id') . "', '" . $contents . "', '" . time() . "', '" . $this->script . "', '.." . securit(str_replace(DIR, '', SCRIPT) . '?' . QUERY_STRING) . "')", __LINE__, __FILE__);
		$idcom = $Sql->Sql_insert_id("SELECT MAX(idcom) FROM ".PREFIX."com");
		
		//Incrémente le nombre de commentaire dans la table du script concerné.
		$Sql->Query_inject("UPDATE ".PREFIX.$this->sql_table." SET nbr_com = nbr_com + 1 WHERE id = '" . $this->idprov . "'", __LINE__, __FILE__);
		
		return $idcom;
	}
	
	//Edition d'un commentaire
	function Update_com($contents, $login)
	{
		global $Sql;
		
		$Sql->Query_inject("UPDATE ".PREFIX."com SET contents = '" . $contents . "', login = '" . $login . "' WHERE idcom = '" . $this->idcom . "' AND idprov = '" . $this->idprov . "' AND script = '" . $this->script . "'", __LINE__, __FILE__);
	}
	
	//Suppression d'un commentaire
	function Del_com()
	{
		global $Sql;
		
		//Sélectionne le message précédent à celui qui va être supprimé.
		$lastid_com = $Sql->Query("SELECT idcom 
		FROM ".PREFIX."com 
		WHERE idcom < '" . $this->idcom . "' AND script = '" . $this->script . "' AND idprov = '" . $this->idprov . "' 
		ORDER BY idcom DESC 
		" . $Sql->Sql_limit(0, 1), __LINE__, __FILE__);
		
		$Sql->Query_inject("DELETE FROM ".PREFIX."com WHERE idcom = '" . $this->idcom . "' AND script = '" . $this->script . "' AND idprov = '" . $this->idprov . "'", __LINE__, __FILE__);				
		$Sql->Query_inject("UPDATE ".PREFIX.$this->sql_table." SET nbr_com= nbr_com - 1 WHERE id = '" . $this->idprov . "'", __LINE__, __FILE__);
		
		return $lastid_com;
	}
	
	//Verrouille les commentaires
	function Lock_com($lock)
	{
		global $Sql;
		
		$Sql->Query_inject("UPDATE ".PREFIX.$this->sql_table." SET lock_com = '" . $lock . "' WHERE id = '" . $this->idprov . "'", __LINE__, __FILE__);
	}
	
	//Vérifie que le système de commentaires est bien chargé.
	function Com_loaded()
	{
		global $Errorh;
		
		if( empty($this->sql_table) ) //Erreur avec le module non prévu pour gérer les commentaires.
			$Errorh->Error_handler('e_unexist_page', E_USER_REDIRECT);
			
		return (!empty($this->script) && !empty($this->idprov) && !empty($this->vars));
	}
	
	//Met à jour l'id du commentaire.
	function Set_arg($idcom, $path = '')
	{
		if( !empty($path) )
			$this->path = $path;
		$this->idcom = max($idcom, 0);
		list($this->sql_table, $this->nbr_com, $this->lock_com) = $this->get_info_module();
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
	var $idcom = 0;
	var $path = '';
	var $vars = '';
	var $module_folder = '';
	var $sql_table = '';
	var $nbr_com = 0;
	var $lock_com = 0;
}

?>