<?php
/*##################################################
 *                             files.class.php
 *                            -------------------
 *   begin                : April 18, 2007
 *   copyright          : (C) 2007 Viarre Régis
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

class Files
{
	var $base_directory; //Répertoire de destination des fichiers.
	var $extension = array(); //Extension des fichiers.
	var $filename = array(); //Nom des fichiers.
	var $error = ''; //Gestion des erreurs
	
	//Constructeur
	function Files()
	{
	}

	//Ajout d'un dossier virtuel
	function add_folder($id_parent, $user_id, $name)
	{
		global $sql;
		
		$check_folder = $sql->query("SELECT COUNT(*) FROM ".PREFIX."upload_cat WHERE name = '" . $name . "' AND id_parent = '" . $id_parent . "'", __LINE__, __FILE__);
		if( !empty($check_folder) || preg_match('`/|\.|\\\|"|<|>|\||\?`', stripslashes($name)) )
			return 0;
			
		$sql->query_inject("INSERT INTO ".PREFIX."upload_cat (id_parent, user_id, name) VALUES ('" . $id_parent . "', '" . $user_id . "', '" . $name . "')", __LINE__, __FILE__);
	
		return $sql->sql_insert_id("SELECT MAX(id) FROM ".PREFIX."upload_cat");
	}	
	
	//Suppression recursive du dossier et de son contenu.
	function del_folder($id_folder, $empty_folder = false)
	{
		global $sql;
		static $i = 0;
		
		//Suppression des fichiers.
		$result = $sql->query_while("SELECT path
		FROM ".PREFIX."upload 
		WHERE idcat = '" . $id_folder . "'", __LINE__, __FILE__);
		while( $row = $sql->sql_fetch_assoc($result) )
		{
			delete_file('../upload/' . $row['path']);
		}
		
		//Suppression des entrées dans la base de données
		if( $empty_folder && $i == 0 ) //Non suppression du dossier racine.
			$i++;
		else
			$sql->query_inject("DELETE FROM ".PREFIX."upload_cat WHERE id = '" . $id_folder . "'", __LINE__, __FILE__);
			
		$sql->query_inject("DELETE FROM ".PREFIX."upload WHERE idcat = '" . $id_folder . "'", __LINE__, __FILE__);			
		$result = $sql->query_while("SELECT id 
		FROM ".PREFIX."upload_cat 
		WHERE id_parent = '" . $id_folder . "'", __LINE__, __FILE__);
		while( $row = $sql->sql_fetch_assoc($result) )
		{
			if( !empty($row['id']) )
				$this->del_folder($row['id'], false);
		}
	}
	
	//Suppression d'un fichier
	function del_file($id_file, $user_id, $admin = false)
	{	
		global $sql;
		
		if( $admin ) //Administration, on ne vérifie pas l'appartenance.
		{
			$name = $sql->query("SELECT path FROM ".PREFIX."upload WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
			$sql->query_inject("DELETE FROM ".PREFIX."upload WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
			delete_file('../upload/' . $name);
			return '';
		}		
		else
		{
			$check_id_auth = $sql->query("SELECT user_id FROM ".PREFIX."upload WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
			//Suppression d'un fichier.
			if( $check_id_auth == $user_id )
			{
				$name = $sql->query("SELECT path FROM ".PREFIX."upload WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
				$sql->query_inject("DELETE FROM ".PREFIX."upload WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
				delete_file('../upload/' . $name);
				return '';
			}
			return 'e_auth';
		}
	}
	
	//Renomme un dossier virtuel
	function rename_folder($id_folder, $name, $previous_name, $user_id, $admin = false)
	{
		global $sql;
		
		//Vérification de l'unicité du nom du dossier.
		$info_folder = $sql->query_array("upload_cat", "id_parent", "user_id", "WHERE id = '" . $id_folder . "'", __LINE__, __FILE__);
		$check_folder = $sql->query("SELECT COUNT(*) FROM ".PREFIX."upload_cat WHERE id_parent = '" . $info_folder['id_parent'] . "' AND name = '" . $name . "' AND id <> '" . $id_folder . "' AND user_id = '" . $user_id . "'", __LINE__, __FILE__);
		if( $check_folder > 0 || preg_match('`/|\.|\\\|"|<|>|\||\?`', stripslashes($name)) )
			return '';
		
		if( $admin ) //Administration, on ne vérifie pas l'appartenance.
		{
			$sql->query_inject("UPDATE ".PREFIX."upload_cat SET name = '" . $name . "' WHERE id = '" . $id_folder . "'", __LINE__, __FILE__);
			return stripslashes((strlen(html_entity_decode($name)) > 22) ? htmlentities(substr(html_entity_decode($name), 0, 22)) . '...' : $name);
		}
		else
		{
			if( $user_id == $info_folder['user_id'] )
			{
				$sql->query_inject("UPDATE ".PREFIX."upload_cat SET name = '" . $name . "' WHERE id = '" . $id_folder . "'", __LINE__, __FILE__);
				return stripslashes((strlen(html_entity_decode($name)) > 22) ? htmlentities(substr(html_entity_decode($name), 0, 22)) . '...' : $name);
			}
		}
		return stripslashes((strlen(html_entity_decode($previous_name)) > 22) ? htmlentities(substr(html_entity_decode($previous_name), 0, 22)) . '...' : $previous_name);
	}
	
	//Renomme un fichier virtuel
	function rename_file($id_file, $name, $previous_name, $user_id, $admin = false)
	{
		global $sql;
		
		//Vérification de l'unicité du nom du fichier.
		$info_cat = $sql->query_array("upload", "idcat", "user_id", "WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
		$check_file = $sql->query("SELECT COUNT(*) FROM ".PREFIX."upload WHERE idcat = '" . $info_cat['idcat'] . "' AND name = '" . $name . "' AND id <> '" . $id_file . "' AND user_id = '" . $user_id . "'", __LINE__, __FILE__);
		if( $check_file > 0 || preg_match('`/|\\\|"|<|>|\||\?`', stripslashes($name)) )
			return '/';
			
		if( $admin ) //Administration, on ne vérifie pas l'appartenance.
		{
			$sql->query_inject("UPDATE ".PREFIX."upload SET name = '" . $name . "' WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
			return stripslashes((strlen(html_entity_decode($name)) > 22) ? htmlentities(substr(html_entity_decode($name), 0, 22)) . '...' : $name);
		}
		else
		{
			if( $user_id == $info_cat['user_id'] )
			{
				$sql->query_inject("UPDATE ".PREFIX."upload SET name = '" . $name . "' WHERE id = '" . $id_file . "'", __LINE__, __FILE__);
				return stripslashes((strlen(html_entity_decode($name)) > 22) ? htmlentities(substr(html_entity_decode($name), 0, 22)) . '...' : $name);
			}
		}
		return stripslashes((strlen(html_entity_decode($previous_name)) > 22) ? htmlentities(substr(html_entity_decode($previous_name), 0, 22)) . '...' : $previous_name);
	}
		
	//Déplacement dun dossier.
	function move_folder($move, $to, $user_id, $admin = false)
	{		
		global $sql;
		
		if( $admin ) //Administration, on ne vérifie pas l'appartenance.
		{
			//Changement de propriètaire du fichier.
			$change_user_id = $sql->query("SELECT user_id FROM ".PREFIX."upload_cat WHERE id = '" . $to . "'", __LINE__, __FILE__);
			$sql->query_inject("UPDATE ".PREFIX."upload_cat SET id_parent = '" . $to . "', user_id = '" . $change_user_id . "' WHERE id = '" . $move . "'", __LINE__, __FILE__);
			return '';
		}
		else
		{
			if( $to == 0 ) //Déplacement dossier racine du membre.
			{	
				$get_mbr_folder = $sql->query("SELECT id FROM ".PREFIX."upload_cat WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);	
				$sql->query_inject("UPDATE ".PREFIX."upload_cat SET id_parent = '" . $get_mbr_folder . "' WHERE id = '" . $move . "' AND user_id = '" . $user_id . "'", __LINE__, __FILE__);
				return '';
			}
			
			//Vérification de l'appartenance du dossier de destination.
			$check_user_id_move = $sql->query("SELECT user_id FROM ".PREFIX."upload_cat WHERE id = '" . $move . "'", __LINE__, __FILE__);
			$check_user_id_to = $sql->query("SELECT user_id FROM ".PREFIX."upload_cat WHERE id = '" . $to . "'", __LINE__, __FILE__);
			if( $user_id == $check_user_id_move && $user_id == $check_user_id_to )
			{
				$sql->query_inject("UPDATE ".PREFIX."upload_cat SET id_parent = '" . $to . "' WHERE id = '" . $move . "' AND user_id = '" . $user_id . "'", __LINE__, __FILE__);
				return '';
			}
			else
				return 'e_auth';
		}
	}
	
	//Déplacement dun fichier.
	function move_file($move, $to, $user_id, $admin = false)
	{
		global $sql;
		
		if( $admin ) //Administration, on ne vérifie pas l'appartenance.
		{
			//Changement de propriètaire du fichier.
			$change_user_id = $sql->query("SELECT user_id FROM ".PREFIX."upload_cat WHERE id = '" . $to . "'", __LINE__, __FILE__);	
			$sql->query_inject("UPDATE ".PREFIX."upload SET idcat = '" . $to . "', user_id = '" . $change_user_id . "' WHERE id = '" . $move . "'", __LINE__, __FILE__);
			return '';
		}
		else
		{
			if( $to == 0 ) //Déplacement dossier racine du membre.
			{	
				$get_mbr_folder = $sql->query("SELECT id FROM ".PREFIX."upload_cat WHERE user_id = '" . $user_id . "' AND id_parent = 0", __LINE__, __FILE__);	
				$sql->query_inject("UPDATE ".PREFIX."upload SET idcat = '" . $get_mbr_folder . "' WHERE id = '" . $move . "' AND user_id = '" . $user_id . "'", __LINE__, __FILE__);
				return '';
			}	

			//Vérification de l'appartenance du dossier de destination.
			$check_user_id_move = $sql->query("SELECT user_id FROM ".PREFIX."upload WHERE id = '" . $move . "'", __LINE__, __FILE__);
			$check_user_id_to = $sql->query("SELECT user_id FROM ".PREFIX."upload_cat WHERE id = '" . $to . "'", __LINE__, __FILE__);
			if( $user_id == $check_user_id_move && $user_id == $check_user_id_to )
			{
				$sql->query_inject("UPDATE ".PREFIX."upload SET idcat = '" . $to . "' WHERE id = '" . $move . "' AND user_id = '" . $user_id . "'", __LINE__, __FILE__);
				return '';
			}
			else
				return 'e_auth';
		}
	}
	
	//Fonction qui détermine toutes les sous-catégories d'une catégorie (récursive)
	function find_subfolder($array_folders, $id_cat, &$array_child_folder)
	{
		//On parcourt les catégories et on déterminer les catégories filles
		foreach($array_folders as $key => $value)
		{
			if( $value == $id_cat )
			{
				$array_child_folder[] = $key;
				//On rappelle la fonction pour la catégorie fille
				$this->find_subfolder($array_folders, $key, $array_child_folder);
			}
		}
	}
	
	//Récupération du répertoire courant (administration).
	function get_admin_url($id_folder, $pwd, $member_link = '')
	{		
		global $LANG, $sql;
		
		$parent_folder = $sql->query_array("upload_cat", "id_parent", "name", "user_id", "WHERE id = '" . $id_folder . "'", __LINE__, __FILE__);
		if( !empty($parent_folder['id_parent']) )
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
		global $LANG, $sql;
		
		$parent_folder = $sql->query_array("upload_cat", "id_parent", "name", "WHERE id = '" . $id_folder . "' AND user_id <> -1", __LINE__, __FILE__);
		if( !empty($parent_folder['id_parent']) )
		{	
			$pwd .= $this->get_url($parent_folder['id_parent'], $pwd, $popup);	
			return $pwd . '/<a href="' . transid('upload.php?f=' . $id_folder . $popup) . '">' . $parent_folder['name'] . '</a>';
		}
		else
			return $pwd . '/<a href="' . transid('upload.php?f=' . $id_folder . $popup) . '">' . $parent_folder['name'] . '</a>';
	}
	
	//Récupération de la taille totale utilisée par un membre.
	function member_memory_used($user_id)
	{
		global $sql;
		
		return $sql->query("SELECT SUM(size) FROM ".PREFIX."upload WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
	}

	//Conversion mimetype -> image.
	function get_img_mimetype($type)
	{
		global $LANG;
		
		$filetype = sprintf($LANG['file_type'], strtoupper($type));
		switch($type)
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
}

?>