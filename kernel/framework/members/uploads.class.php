<?php
/*##################################################
 *                             uploads.class.php
 *                            -------------------
 *   begin                : April 18, 2007
 *   copyright            : (C) 2007 Viarre Régis
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

define('EMPTY_FOLDER', true);
define('ADMIN_NO_CHECK', true);

/**
 * @package members
 *
 */

class Uploads
{
	## Public Attributes ##
	var $error = ''; //Gestion des erreurs
	
	
	## Public Methods ##	
	//Ajout d'un dossier virtuel
	function Add_folder($id_parent, $user_id, $name)
	{
		global $Sql;
		
		$check_folder = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_UPLOAD_CAT . " WHERE name = '" . $name . "' AND id_parent = '" . $id_parent . "' AND user_id = '" . $user_id . "'", __LINE__, __FILE__);
		if (!empty($check_folder) || preg_match('`/|\.|\\\|"|<|>|\||\?`', stripslashes($name)))
			return 0;
			
		$Sql->query_inject("INSERT INTO " . DB_TABLE_UPLOAD_CAT . " (id_parent, user_id, name) VALUES ('" . $id_parent . "', '" . $user_id . "', '" . $name . "')", __LINE__, __FILE__);
	
		return $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "upload_cat");
	}	
	
	//Suppression recursive des dossiers et fichiers du membre.
	function Empty_folder_member($user_id)
	{
		global $Sql;
		
		//Suppression des fichiers.
		$result = $Sql->Query_while("SELECT path
		FROM " . DB_TABLE_UPLOAD . " 
		WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
		while( $row = $Sql->fetch_assoc($result) )
			delete_file(PATH_TO_ROOT . '/upload/' . $row['path']);
		
		//Suppression des entrées dans la base de données
		$Sql->Query_inject("DELETE FROM " . DB_TABLE_UPLOAD_CAT . " WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);			
		$Sql->Query_inject("DELETE FROM " . DB_TABLE_UPLOAD . " WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);			
	}
	
	//Suppression recursive du dossier et de son contenu.
	function Del_folder($id_folder)
	{
		global $Sql;
		
		//Suppression des fichiers.
		$result = $Sql->query_while("SELECT path
		FROM " . DB_TABLE_UPLOAD . " 
		WHERE idcat = '" . $id_folder . "'", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
			delete_file(PATH_TO_ROOT . '/upload/' . $row['path']);
		
		//Suppression des entrées dans la base de données
		$Sql->query_inject("DELETE FROM " . DB_TABLE_UPLOAD_CAT . " WHERE id = '" . $id_folder . "'", __LINE__, __FILE__);
			
		$Sql->query_inject("DELETE FROM " . DB_TABLE_UPLOAD . " WHERE idcat = '" . $id_folder . "'", __LINE__, __FILE__);			
		$result = $Sql->query_while("SELECT id 
		FROM " . DB_TABLE_UPLOAD_CAT . " 
		WHERE id_parent = '" . $id_folder . "'", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			if (!empty($row['id']))
				$this->del_folder($row['id'], false);
		}
	}
	
	//Suppression d'un fichier
	function Del_file($id_file, $user_id, $admin = false)
	{	
		global $Sql;
		
		if ($admin) //Administration, on ne vérifie pas l'appartenance.
		{
			$name = $Sql->query("SELECT path FROM " . DB_TABLE_UPLOAD . " WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
			$Sql->query_inject("DELETE FROM " . DB_TABLE_UPLOAD . " WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
			delete_file(PATH_TO_ROOT . '/upload/' . $name);
			return '';
		}		
		else
		{
			$check_id_auth = $Sql->query("SELECT user_id FROM " . DB_TABLE_UPLOAD . " WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
			//Suppression d'un fichier.
			if ($check_id_auth == $user_id)
			{
				$name = $Sql->query("SELECT path FROM " . DB_TABLE_UPLOAD . " WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
				$Sql->query_inject("DELETE FROM " . DB_TABLE_UPLOAD . " WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
				delete_file(PATH_TO_ROOT . '/upload/' . $name);
				return '';
			}
			return 'e_auth';
		}
	}
	
	//Renomme un dossier virtuel
	function Rename_folder($id_folder, $name, $previous_name, $user_id, $admin = false)
	{
		global $Sql;
		
		//Vérification de l'unicité du nom du dossier.
		$info_folder = $Sql->query_array(PREFIX . "upload_cat", "id_parent", "user_id", "WHERE id = '" . $id_folder . "'", __LINE__, __FILE__);
		$check_folder = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_UPLOAD_CAT . " WHERE id_parent = '" . $info_folder['id_parent'] . "' AND name = '" . $name . "' AND id <> '" . $id_folder . "' AND user_id = '" . $user_id . "'", __LINE__, __FILE__);
		if ($check_folder > 0 || preg_match('`/|\.|\\\|"|<|>|\||\?`', stripslashes($name)))
			return '';
		
		if ($admin) //Administration, on ne vérifie pas l'appartenance.
		{
			$Sql->query_inject("UPDATE " . DB_TABLE_UPLOAD_CAT . " SET name = '" . $name . "' WHERE id = '" . $id_folder . "'", __LINE__, __FILE__);
			return stripslashes((strlen(html_entity_decode($name)) > 22) ? htmlentities(substr(html_entity_decode($name), 0, 22)) . '...' : $name);
		}
		else
		{
			if ($user_id == $info_folder['user_id'])
			{
				$Sql->query_inject("UPDATE " . DB_TABLE_UPLOAD_CAT . " SET name = '" . $name . "' WHERE id = '" . $id_folder . "'", __LINE__, __FILE__);
				return stripslashes((strlen(html_entity_decode($name)) > 22) ? htmlentities(substr(html_entity_decode($name), 0, 22)) . '...' : $name);
			}
		}
		return stripslashes((strlen(html_entity_decode($previous_name)) > 22) ? htmlentities(substr(html_entity_decode($previous_name), 0, 22)) . '...' : $previous_name);
	}
	
	//Renomme un fichier virtuel
	function Rename_file($id_file, $name, $previous_name, $user_id, $admin = false)
	{
		global $Sql;
		
		//Vérification de l'unicité du nom du fichier.
		$info_cat = $Sql->query_array(PREFIX . "upload", "idcat", "user_id", "WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
		$check_file = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_UPLOAD . " WHERE idcat = '" . $info_cat['idcat'] . "' AND name = '" . $name . "' AND id <> '" . $id_file . "' AND user_id = '" . $user_id . "'", __LINE__, __FILE__);
		if ($check_file > 0 || preg_match('`/|\\\|"|<|>|\||\?`', stripslashes($name)))
			return '/';
			
		if ($admin) //Administration, on ne vérifie pas l'appartenance.
		{
			$Sql->query_inject("UPDATE " . DB_TABLE_UPLOAD . " SET name = '" . $name . "' WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
			return stripslashes((strlen(html_entity_decode($name)) > 22) ? htmlentities(substr(html_entity_decode($name), 0, 22)) . '...' : $name);
		}
		else
		{
			if ($user_id == $info_cat['user_id'])
			{
				$Sql->query_inject("UPDATE " . DB_TABLE_UPLOAD . " SET name = '" . $name . "' WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
				return stripslashes((strlen(html_entity_decode($name)) > 22) ? htmlentities(substr(html_entity_decode($name), 0, 22)) . '...' : $name);
			}
		}
		return stripslashes((strlen(html_entity_decode($previous_name)) > 22) ? htmlentities(substr(html_entity_decode($previous_name), 0, 22)) . '...' : $previous_name);
	}
		
	//Déplacement dun dossier.
	function Move_folder($move, $to, $user_id, $admin = false)
	{		
		global $Sql;
		
		if ($admin) //Administration, on ne vérifie pas l'appartenance.
		{
			//Changement de propriètaire du fichier.
			$change_user_id = $Sql->query("SELECT user_id FROM " . DB_TABLE_UPLOAD_CAT . " WHERE id = '" . $to . "'", __LINE__, __FILE__);
			if (empty($change_user_id))
				$change_user_id = -1;
			if ($to != $move)
				$Sql->query_inject("UPDATE " . DB_TABLE_UPLOAD_CAT . " SET id_parent = '" . $to . "', user_id = '" . $change_user_id . "' WHERE id = '" . $move . "'", __LINE__, __FILE__);
			return '';
		}
		else
		{
			if ($to == 0) //Déplacement dossier racine du membre.
			{	
				$get_mbr_folder = $Sql->query("SELECT id FROM " . DB_TABLE_UPLOAD_CAT . " WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);	
				$Sql->query_inject("UPDATE " . DB_TABLE_UPLOAD_CAT . " SET id_parent = '" . $get_mbr_folder . "' WHERE id = '" . $move . "' AND user_id = '" . $user_id . "'", __LINE__, __FILE__);
				return '';
			}
			
			//Vérification de l'appartenance du dossier de destination.
			$check_user_id_move = $Sql->query("SELECT user_id FROM " . DB_TABLE_UPLOAD_CAT . " WHERE id = '" . $move . "'", __LINE__, __FILE__);
			$check_user_id_to = $Sql->query("SELECT user_id FROM " . DB_TABLE_UPLOAD_CAT . " WHERE id = '" . $to . "'", __LINE__, __FILE__);
			if ($user_id == $check_user_id_move && $user_id == $check_user_id_to)
			{
				$Sql->query_inject("UPDATE " . DB_TABLE_UPLOAD_CAT . " SET id_parent = '" . $to . "' WHERE id = '" . $move . "' AND user_id = '" . $user_id . "'", __LINE__, __FILE__);
				return '';
			}
			else
				return 'e_auth';
		}
	}
	
	//Déplacement dun fichier.
	function Move_file($move, $to, $user_id, $admin = false)
	{
		global $Sql;
		
		if ($admin) //Administration, on ne vérifie pas l'appartenance.
		{
			//Changement de propriètaire du fichier.
			$change_user_id = $Sql->query("SELECT user_id FROM " . DB_TABLE_UPLOAD_CAT . " WHERE id = '" . $to . "'", __LINE__, __FILE__);	
			if (empty($change_user_id))
				$change_user_id = -1;
			$Sql->query_inject("UPDATE " . DB_TABLE_UPLOAD . " SET idcat = '" . $to . "', user_id = '" . $change_user_id . "' WHERE id = '" . $move . "'", __LINE__, __FILE__);
			return '';
		}
		else
		{
			if ($to == 0) //Déplacement dossier racine du membre.
			{	
				$get_mbr_folder = $Sql->query("SELECT id FROM " . DB_TABLE_UPLOAD_CAT . " WHERE user_id = '" . $user_id . "' AND id_parent = 0", __LINE__, __FILE__);	
				$Sql->query_inject("UPDATE " . DB_TABLE_UPLOAD . " SET idcat = '" . $get_mbr_folder . "' WHERE id = '" . $move . "' AND user_id = '" . $user_id . "'", __LINE__, __FILE__);
				return '';
			}	

			//Vérification de l'appartenance du dossier de destination.
			$check_user_id_move = $Sql->query("SELECT user_id FROM " . DB_TABLE_UPLOAD . " WHERE id = '" . $move . "'", __LINE__, __FILE__);
			$check_user_id_to = $Sql->query("SELECT user_id FROM " . DB_TABLE_UPLOAD_CAT . " WHERE id = '" . $to . "'", __LINE__, __FILE__);
			if ($user_id == $check_user_id_move && $user_id == $check_user_id_to)
			{
				$Sql->query_inject("UPDATE " . DB_TABLE_UPLOAD . " SET idcat = '" . $to . "' WHERE id = '" . $move . "' AND user_id = '" . $user_id . "'", __LINE__, __FILE__);
				return '';
			}
			else
				return 'e_auth';
		}
	}
	
	//Fonction qui détermine toutes les sous-catégories d'une catégorie (récursive)
	function Find_subfolder($array_folders, $id_cat, &$array_child_folder)
	{
		//On parcourt les catégories et on déterminer les catégories filles
		foreach ($array_folders as $key => $value)
		{
			if ($value == $id_cat)
			{
				$array_child_folder[] = $key;
				//On rappelle la fonction pour la catégorie fille
				$this->Find_subfolder($array_folders, $key, $array_child_folder);
			}
		}
	}
	
	//Récupération du répertoire courant (administration).
	function get_admin_url($id_folder, $pwd, $member_link = '')
	{		
		global $LANG, $Sql;
		
		$parent_folder = $Sql->query_array(PREFIX . "upload_cat", "id_parent", "name", "user_id", "WHERE id = '" . $id_folder . "'", __LINE__, __FILE__);
		if (!empty($parent_folder['id_parent']))
		{	
			$pwd .= $this->get_admin_url($parent_folder['id_parent'], $pwd, $member_link);	
			return $pwd . '/<a href="admin_files.php?f=' . $id_folder . '">' . $parent_folder['name'] . '</a>';
		}
		else
			return ($parent_folder['user_id'] == '-1') ? $pwd . '/<a href="admin_files.php?f=' . $id_folder . '">' . $parent_folder['name'] . '</a>' : $pwd . '/' . $member_link . '<a href="admin_files.php?f=' . $id_folder . '">' . $parent_folder['name'] . '</a>';
	}
	
	//Récupération du répertoire courant.
	function get_url($id_folder, $pwd, $popup)
	{		
		global $LANG, $Sql;
		
		$parent_folder = $Sql->query_array(PREFIX . "upload_cat", "id_parent", "name", "WHERE id = '" . $id_folder . "' AND user_id <> -1", __LINE__, __FILE__);
		if (!empty($parent_folder['id_parent']))
		{	
			$pwd .= $this->get_url($parent_folder['id_parent'], $pwd, $popup);	
			return $pwd . '/<a href="' . url('upload.php?f=' . $id_folder . $popup) . '">' . $parent_folder['name'] . '</a>';
		}
		else
			return $pwd . '/<a href="' . url('upload.php?f=' . $id_folder . $popup) . '">' . $parent_folder['name'] . '</a>';
	}
	
	//Récupération de la taille totale utilisée par un membre.
	function Member_memory_used($user_id)
	{
		global $Sql;
		
		return $Sql->query("SELECT SUM(size) FROM " . DB_TABLE_UPLOAD . " WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
	}

	//Conversion mimetype -> image.
	function get_img_mimetype($type)
	{
		global $LANG;
		
		$filetype = sprintf($LANG['file_type'], strtoupper($type));
		switch ($type)
		{
			//Images
			case 'jpg':			
			case 'png':
			case 'gif':
			case 'bmp':
			case 'svg':
			$img = $type . '.png';
			$filetype = sprintf($LANG['image_type'], strtoupper($type));
			break;
			//Archives
			case 'rar':
			case 'gz':
			case 'zip':
			$img = 'zip.png';
			$filetype = sprintf($LANG['zip_type'], strtoupper($type));
			break;
			//Pdf
			case 'pdf':
			$img = 'pdf.png';
			$filetype = $LANG['adobe_pdf'];
			break;
			//Son
			case 'wav':
			case 'mp3':
			$img = 'audio.png';
			$filetype = sprintf($LANG['audio_type'], strtoupper($type));
			break;
			//Sripts
			case 'html':
			$img = 'html.png';
			break;
			case 'js':
			case 'php':
			$img = 'script.png';
			break;
			//Vidéos
			case 'wmv':
			case 'avi':
			$img = 'video.png';
			break;
			//Executables
			case 'exe':
			$img = 'exec.png';
			break;
			default:
			$img = 'text.png';
			$filetype = sprintf($LANG['document_type'], strtoupper($type));
		}	
		
		return array('img' => $img, 'filetype' => $filetype);
	}	
	
	
	## Private Attributes ##
	var $base_directory; //Répertoire de destination des fichiers.
	var $extension = array(); //Extension des fichiers.
	var $filename = array(); //Nom des fichiers.
}

?>