<?php
/*##################################################
 *                               gallery.class.php
 *                            -------------------
 *   begin                : August 16, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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

class Gallery
{	
	private $error = ''; //Gestion des erreurs.
	
	//Constructeur
	public function __construct() 
	{
	}
	
	//Redimensionnement
	public function Resize_pics($path, $width_max = 0, $height_max = 0)
	{
		global $LANG;
			
		if (file_exists($path))
		{	
			list($width_s, $height_s, $weight, $ext) = $this->Arg_pics($path);
			//Calcul des dimensions avec respect des proportions.
			list($width, $height) = $this->get_resize_properties($width_s, $height_s, $width_max, $height_max);
			
			$source = false;
			switch ($ext) //Création de l'image suivant l'extension.
			{
				case 'jpg':
				case 'jpeg':
					$source = @imagecreatefromjpeg($path);
					break;
				case 'gif':
					$source = @imagecreatefromgif ($path);
					break;
				case 'png':
					$source = @imagecreatefrompng($path);
					break;
				default: 
					$this->error = 'e_unsupported_format';
					$source = false;
			}
			
			if (!$source)
			{
				$path_mini = str_replace('pics', 'pics/thumbnails', $path);
				$this->_create_pics_error($path_mini, $width, $height);	
				$this->error = 'e_unabled_create_pics';
			}
			else
			{
				//Préparation de l'image redimensionnée.
				if (!function_exists('imagecreatetruecolor'))
				{	
					$thumbnail = @imagecreate($width, $height);
					if ($thumbnail === false)				
						$this->error = 'e_unabled_create_pics';
				}
				else
				{	
					$thumbnail = @imagecreatetruecolor($width, $height);
					if ($thumbnail === false)				
						$this->error = 'e_unabled_create_pics';
				}
				
				// Make the background transparent
				imagecolortransparent($thumbnail, imagecolorallocate($thumbnail, 0, 0, 0));
				imagealphablending($thumbnail, false);
				
				//Redimensionnement.
				if (!function_exists('imagecopyresampled'))
				{	
					if (@imagecopyresized($thumbnail, $source, 0, 0, 0, 0, $width, $height, $width_s, $height_s) === false)				
						$this->error = 'e_error_resize';
				}
				else
				{	
					if (@imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, $width, $height, $width_s, $height_s) === false)				
						$this->error = 'e_error_resize';
				}
			}
			
			//Création de l'image.
			if (empty($this->error))
				$this->create_pics($thumbnail, $source, $path, $ext);
		}
		else
		{
			$path_mini = str_replace('pics', 'pics/thumbnails', $path);
			$this->_create_pics_error($path_mini, $width_max, $height_max);	
			$this->error = 'e_unabled_create_pics';
		}
	}
	
	//Création de l'image.
	public function Create_pics($thumbnail, $source, $path, $ext)
	{
		global $CONFIG_GALLERY;
		
		// Make the background transparent
		imagecolortransparent($source, imagecolorallocate($source, 0, 0, 0));	
		imagealphablending($source, false); // turn off the alpha blending to keep the alpha channel
		
		$path_mini = str_replace('pics', 'pics/thumbnails', $path);
		if (function_exists('imagegif') && $ext === 'gif') 
			imagegif ($thumbnail, $path_mini);
		elseif (function_exists('imagejpeg') && ($ext === 'jpg' || $ext === 'jpeg')) 
			imagejpeg($thumbnail, $path_mini, $CONFIG_GALLERY['quality']);
		elseif (function_exists('imagepng')  && $ext === 'png') 
			imagepng($thumbnail, $path_mini);
		else 
			$this->error = 'e_no_graphic_support';

		switch ($ext) //Création de l'image suivant l'extension.
		{
			case 'jpg':
			case 'jpeg':
				@imagejpeg($source, $path);
				break;
			case 'gif':
				@imagegif ($source, $path);
				break;
			case 'png':
				@imagepng($source, $path);
				break;
			default: 
				$this->error = 'e_no_graphic_support';
		}
	}

	//Incrustation du logo (possible en transparent si jpg).
	public function Incrust_pics($path)
	{
		global $CONFIG_GALLERY, $LANG;
		
		if ($CONFIG_GALLERY['activ_logo'] == '1' && is_file($CONFIG_GALLERY['logo'])) //Incrustation du logo.
		{
			list($width_s, $height_s, $weight_s, $ext_s) = $this->Arg_pics($CONFIG_GALLERY['logo']);
			list($width, $height, $weight, $ext) = $this->Arg_pics($path);
			
			if ($width_s <= $width && $height_s <= $height)
			{
				switch ($ext_s) //Création de l'image suivant l'extension.
				{
					case 'jpg':
					case 'jpeg':
						$source = @imagecreatefromjpeg($CONFIG_GALLERY['logo']);
						break;
					case 'gif':
						$source = @imagecreatefromgif ($CONFIG_GALLERY['logo']);
						break;
					case 'png':
						$source = @imagecreatefrompng($CONFIG_GALLERY['logo']);
						break;
					default: 
						$this->error = 'e_unsupported_format';
						$source = false;
				}
				
				if (!$source)
				{
					$path_mini = str_replace('pics', 'pics/thumbnails', $path);
					list($width_mini, $height_mini, $weight_mini, $ext_mini) = $this->Arg_pics($path_mini);
					$this->_create_pics_error($path_mini, $width_mini, $height_mini);	
					$this->error = 'e_unabled_create_pics';
				}
				else
				{
					switch ($ext) //Création de l'image suivant l'extension.
					{
						case 'jpg':
						case 'jpeg':
							$destination = @imagecreatefromjpeg($path);
							break;
						case 'gif':
							$destination = @imagecreatefromgif ($path);
							break;
						case 'png':
							$destination = @imagecreatefrompng($path);
							break;
						default: 
							$this->error = 'e_unsupported_format';
					}
					
					if (function_exists('imagecopymerge'))
					{
						// On veut placer le logo en bas à droite, on calcule les coordonnées où on doit placer le logo sur la photo
						$destination_x = $width - $width_s - $CONFIG_GALLERY['d_width'];
						$destination_y =  $height - $height_s - $CONFIG_GALLERY['d_height'];
						
						if (@imagecopymerge($destination, $source, $destination_x, $destination_y, 0, 0, $width_s, $height_s, (100 - $CONFIG_GALLERY['trans'])) === false)
							$this->error = 'e_unabled_incrust_logo';
							
						switch ($ext) //Création de l'image suivant l'extension.
						{
							case 'jpg':
							case 'jpeg':
								imagejpeg($destination);
								break;
							case 'gif':
								imagegif ($destination);
								break;
							case 'png':
								imagepng($destination);
								break;
							default: 
								$this->error = 'e_unabled_create_pics';
						}
					}
					else
						$this->error = 'e_unabled_incrust_logo';
				}
			}
			else
				readfile($path); //On affiche simplement.
		}
		else
			readfile($path); //On affiche simplement.
	}
	
	//Insertion base de donnée
	public function Add_pics($idcat, $name, $path, $user_id)
	{
		global $CAT_GALLERY, $Sql;
		
		$CAT_GALLERY[0]['id_left'] = 0;
		$CAT_GALLERY[0]['id_right'] = 0;
		
		//Parent de la catégorie cible
		$list_parent_cats_to = '';
		$result = $Sql->query_while("SELECT id 
		FROM " . PREFIX . "gallery_cats 
		WHERE id_left <= '" . $CAT_GALLERY[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_GALLERY[$idcat]['id_right'] . "'", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$list_parent_cats_to .= $row['id'] . ', ';
		}
		$Sql->query_close($result);
		$list_parent_cats_to = trim($list_parent_cats_to, ', ');
		
		if (empty($list_parent_cats_to))
			$clause_parent_cats_to = " id = '" . $idcat . "'";
		else
			$clause_parent_cats_to = " id IN (" . $list_parent_cats_to . ")";
		
		$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET nbr_pics_aprob = nbr_pics_aprob + 1 WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);		
		
		list($width, $height, $weight, $ext) = $this->Arg_pics('pics/' . $path);	
		$Sql->query_inject("INSERT INTO " . PREFIX . "gallery (idcat, name, path, width, height, weight, user_id, aprob, views, timestamp, users_note, nbrnote, note, nbr_com) VALUES('" . $idcat . "', '" .strprotect($name,HTML_PROTECT,ADDSLASHES_FORCE). "', '" . $path . "', '" . $width . "', '" . $height . "', '" . $weight ."', '" . $user_id . "', 1, 0, '" . time() . "', '', 0, 0, 0)", __LINE__, __FILE__);
		
		return $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "gallery");
	}
	
	//Supprime une image
	public function Del_pics($id_pics)
	{
		global $CAT_GALLERY, $Sql;
		
		$CAT_GALLERY[0]['id_left'] = 0;
		$CAT_GALLERY[0]['id_right'] = 0;
		
		$info_pics = $Sql->query_array(PREFIX . "gallery", "path", "idcat", "aprob", "WHERE id = '" . $id_pics . "'", __LINE__, __FILE__);
		if (!empty($info_pics['path']))
		{
			$Sql->query_inject("DELETE FROM " . PREFIX . "gallery WHERE id = '" . $id_pics . "'", __LINE__, __FILE__);	
		
			//Parent de la catégorie cible
			$list_parent_cats_to = '';
			$result = $Sql->query_while("SELECT id 
			FROM " . PREFIX . "gallery_cats 
			WHERE id_left <= '" . $CAT_GALLERY[$info_pics['idcat']]['id_left'] . "' AND id_right >= '" . $CAT_GALLERY[$info_pics['idcat']]['id_right'] . "'", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				$list_parent_cats_to .= $row['id'] . ', ';
			}
			$Sql->query_close($result);
			$list_parent_cats_to = trim($list_parent_cats_to, ', ');
			
			if (empty($list_parent_cats_to))
				$clause_parent_cats_to = " id = '" . $info_pics['idcat'] . "'";
			else
				$clause_parent_cats_to = " id IN (" . $list_parent_cats_to . ")";
				
			if ($info_pics['aprob'])
				$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET nbr_pics_aprob = nbr_pics_aprob - 1 WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);
			else
				$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET nbr_pics_unaprob = nbr_pics_unaprob - 1 WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);
		}
		
		//Suppression physique.
		delete_file('pics/' . $info_pics['path']);
		delete_file('pics/thumbnails/' . $info_pics['path']);
	}
	
	//Renomme une image.
	public function Rename_pics($id_pics, $name, $previous_name)
	{
		global $Sql;
		
		$Sql->query_inject("UPDATE " . PREFIX . "gallery SET name = '" . strprotect($name,HTML_PROTECT,ADDSLASHES_FORCE). "' WHERE id = '" . $id_pics . "'", __LINE__, __FILE__);
		return stripslashes((strlen(html_entity_decode($name)) > 22) ? htmlentities(substr(html_entity_decode($name), 0, 22)) . PATH_TO_ROOT . '.' : $name);
	}
	
	//Approuve une image.
	public function Aprob_pics($id_pics)
	{
		global $CAT_GALLERY, $Sql;
		
		$CAT_GALLERY[0]['id_left'] = 0;
		$CAT_GALLERY[0]['id_right'] = 0;
		
		$idcat = $Sql->query("SELECT idcat FROM " . PREFIX . "gallery WHERE id = '" . $id_pics . "'", __LINE__, __FILE__);
		//Parent de la catégorie cible
		$list_parent_cats_to = '';
		$result = $Sql->query_while("SELECT id 
		FROM " . PREFIX . "gallery_cats 
		WHERE id_left <= '" . $CAT_GALLERY[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_GALLERY[$idcat]['id_right'] . "'", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$list_parent_cats_to .= $row['id'] . ', ';
		}
		$Sql->query_close($result);
		$list_parent_cats_to = trim($list_parent_cats_to, ', ');
		
		if (empty($list_parent_cats_to))
			$clause_parent_cats_to = " id = '" . $idcat . "'";
		else
			$clause_parent_cats_to = " id IN (" . $list_parent_cats_to . ")";
			
		$aprob = $Sql->query("SELECT aprob FROM " . PREFIX . "gallery WHERE id = '" . $id_pics . "'", __LINE__, __FILE__);
		if ($aprob)
		{	
			$Sql->query_inject("UPDATE " . PREFIX . "gallery SET aprob = 0 WHERE id = '" . $id_pics . "'", __LINE__, __FILE__);
			$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET nbr_pics_unaprob = nbr_pics_unaprob + 1, nbr_pics_aprob = nbr_pics_aprob - 1 WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);
		}
		else
		{
			$Sql->query_inject("UPDATE " . PREFIX . "gallery SET aprob = 1 WHERE id = '" . $id_pics . "'", __LINE__, __FILE__);
			$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET nbr_pics_unaprob = nbr_pics_unaprob - 1, nbr_pics_aprob = nbr_pics_aprob + 1 WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);
		}
		
		return $aprob;
	}
	
	//Déplacement d'une image.
	public function Move_pics($id_pics, $id_move)
	{
		global $CAT_GALLERY, $Sql;
		
		//Racine.
		$CAT_GALLERY[0]['id_left'] = 0;
		$CAT_GALLERY[0]['id_right'] = 0;
		
		$idcat = $Sql->query("SELECT idcat FROM " . PREFIX . "gallery WHERE id = '" . $id_pics . "'", __LINE__, __FILE__);
		//Parent de la catégorie parente
		$list_parent_cats = '';
		$result = $Sql->query_while("SELECT id 
		FROM " . PREFIX . "gallery_cats 
		WHERE id_left <= '" . $CAT_GALLERY[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_GALLERY[$idcat]['id_right'] . "'", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$list_parent_cats .= $row['id'] . ', ';
		}
		$Sql->query_close($result);
		$list_parent_cats = trim($list_parent_cats, ', ');
		
		if (empty($list_parent_cats))
			$clause_parent_cats = " id = '" . $idcat . "'";
		else
			$clause_parent_cats = " id IN (" . $list_parent_cats . ")";
		
		//Parent de la catégorie cible
		$list_parent_cats_to = '';
		$result = $Sql->query_while("SELECT id 
		FROM " . PREFIX . "gallery_cats 
		WHERE id_left <= '" . $CAT_GALLERY[$id_move]['id_left'] . "' AND id_right >= '" . $CAT_GALLERY[$id_move]['id_right'] . "'", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$list_parent_cats_to .= $row['id'] . ', ';
		}
		$Sql->query_close($result);
		$list_parent_cats_to = trim($list_parent_cats_to, ', ');
	
		if (empty($list_parent_cats_to))
			$clause_parent_cats_to = " id = '" . $id_move . "'";
		else
			$clause_parent_cats_to = " id IN (" . $list_parent_cats_to . ")";
			
		$aprob = $Sql->query("SELECT aprob FROM " . PREFIX . "gallery WHERE id = '" . $id_pics . "'", __LINE__, __FILE__);
		
		if ($aprob)
		{	
			$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET nbr_pics_aprob = nbr_pics_aprob - 1 WHERE " . $clause_parent_cats, __LINE__, __FILE__);
			$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET nbr_pics_aprob = nbr_pics_aprob + 1 WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);
		}
		else
		{
			$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET nbr_pics_unaprob = nbr_pics_unaprob - 1 WHERE " . $clause_parent_cats, __LINE__, __FILE__);
			$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET nbr_pics_unaprob = nbr_pics_unaprob + 1 WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);
		}
		
		$Sql->query_inject("UPDATE " . PREFIX . "gallery SET idcat = '" . $id_move . "' WHERE id = '" . $id_pics . "'", __LINE__, __FILE__);
	}
	
	//Vérifie si le membre peut uploader une image
	public function Auth_upload_pics($user_id, $level)
	{
		global $CONFIG_GALLERY;
		
		switch ($level)
		{
			case 2:
			$pics_quota = 10000;
			break;
			case 1:
			$pics_quota = $CONFIG_GALLERY['limit_modo'];
			break;
			default:
			$pics_quota = $CONFIG_GALLERY['limit_member'];
		}

		if ($this->get_nbr_upload_pics($user_id) >= $pics_quota)
			return false;
			
		return true;
	}
	
	//Arguments de l'image, hauteur, largeur, extension.
	public function Arg_pics($path)
	{
		global $Errorh, $LANG;
		
		//Vérification du chargement de la librairie GD.
		if (!@extension_loaded('gd')) 
			$Errorh->handler($LANG['e_no_gd'], E_USER_ERROR, __LINE__, __FILE__);
		
		if (function_exists('getimagesize')) 
		{
			list($width, $height, $type) = @getimagesize($path);
			$weight = @filesize($path);
			$weight = !empty($weight) ? $weight : 0;			
			
			//On prepare les valeurs de remplacement, pour détérminer le type de l'image.
			$array_type = array( 1 => 'gif', 2 => 'jpg', 3 => 'png', 4 => 'jpeg');
			if (isset($array_type[$type]))
				return array($width, $height, $weight, $array_type[$type]);
			else
				$this->error = 'e_unsupported_format';
		}
		else
			$Errorh->handler($LANG['e_no_getimagesize'], E_USER_ERROR, __LINE__, __FILE__);
	}
		
	//Compte le nombre d'images uploadée par un membre.
	public function get_nbr_upload_pics($user_id)
	{
		global $Sql;
		
		return $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "gallery WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
	}
	
	//Calcul des dimensions avec respect des proportions.
	public function get_resize_properties($width_s, $height_s, $width_max = 0, $height_max = 0)
	{
		global $CONFIG_GALLERY;
		
		$width_max = ($width_max == 0) ? $CONFIG_GALLERY['width'] : $width_max;
		$height_max = ($height_max == 0) ? $CONFIG_GALLERY['height'] : $height_max;
		if ($width_s > $width_max || $height_s > $height_max) 
		{
			if ($width_s > $height_s)
			{
				$ratio = $width_s / $height_s;
				$width = $width_max;
				$height = ceil($width / $ratio);
			}
			else
			{
				$ratio = $height_s / $width_s;
				$height = $height_max;
				$width = ceil($height / $ratio);
			}
		}
		else
		{
			$width = $width_s;
			$height = $height_s;
		}
		
		return array($width, $height);
	}
	
	//Header image.
	public function Send_header($ext)
	{
		global $LANG;
		
		switch ($ext)
		{
			case 'png':
				$header = header('Content-type: image/png');
				break;
			case 'gif':
				$header = header('Content-type: image/gif');
				break;
			case 'jpg':
			case 'jpeg':
				$header = header('Content-type: image/jpeg');
				break;
			default:
				$header = '';
				$this->error = $LANG['e_unable_display_pics'];
		}
		return $header;
	}
	
	//Recompte le nombre d'images de chaque catégories
	public function Count_cat_pics()
	{
		global $CAT_GALLERY, $Sql;
		
		$CAT_GALLERY[0]['id_left'] = 0;
		$CAT_GALLERY[0]['id_right'] = 0;
		
		$info_cat = array();
		$result = $Sql->query_while ("SELECT idcat, COUNT(*) as nbr_pics_aprob 
		FROM " . PREFIX . "gallery 
		WHERE aprob = 1 AND idcat > 0
		GROUP BY idcat", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
			$info_cat[$row['idcat']]['aprob'] = $row['nbr_pics_aprob'];
		$Sql->query_close($result);
		
		$result = $Sql->query_while ("SELECT idcat, COUNT(*) as nbr_pics_unaprob 
		FROM " . PREFIX . "gallery 
		WHERE aprob = 0 AND idcat > 0
		GROUP BY idcat", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
			$info_cat[$row['idcat']]['unaprob'] = $row['nbr_pics_unaprob'];
		$Sql->query_close($result);
		
		$result = $Sql->query_while("SELECT id, id_left, id_right
		FROM " . PREFIX . "gallery_cats", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{			
			$nbr_pics_aprob = 0;
			$nbr_pics_unaprob = 0;
			foreach ($info_cat as $key => $value)
			{			
				if ($CAT_GALLERY[$key]['id_left'] >= $row['id_left'] && $CAT_GALLERY[$key]['id_right'] <= $row['id_right'])
				{	
					$nbr_pics_aprob += isset($info_cat[$key]['aprob']) ? $info_cat[$key]['aprob'] : 0;
					$nbr_pics_unaprob += isset($info_cat[$key]['unaprob']) ? $info_cat[$key]['unaprob'] : 0; 
				}
			}
			$Sql->query_inject("UPDATE " . PREFIX . "gallery_cats SET nbr_pics_aprob = '" . $nbr_pics_aprob . "', nbr_pics_unaprob = '" . $nbr_pics_unaprob . "' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);	
		}
		$Sql->query_close($result);
	}
	
	//Vidange des miniatures du FTP et de la bdd => régénérée plus tard lors des affichages..
	public function Clear_cache()
	{
		//On recupère les dossier des thèmes contenu dans le dossier images/smiley.
		
		$thumb_folder_path = new Folder('./pics/thumbnails/');
		foreach ($thumb_folder_path->get_files('`\.(png|jpg|jpeg|bmp|gif)$`i') as $thumbs)
			$this->delete_file('./pics/thumbnails/' . $thumbs->get_name());
	}
	
	//Suppression d'une image.
	public function delete_file($path)
	{
		if (function_exists('unlink'))
			return @unlink($path); //On supprime le fichier.
		else //Fonction désactivée.	
		{	
			$this->error = 'e_delete_thumbnails';
			return false;
		}		
	}
	
	//Création de l'image d'erreur
	private function _create_pics_error($path, $width, $height)
	{
		global $CONFIG_GALLERY, $LANG; 
		
		$width = ($width == 0) ? $CONFIG_GALLERY['width'] : $width;
		$height = ($height == 0) ? $CONFIG_GALLERY['height'] : $height;
			
		$font = PATH_TO_ROOT . '/kernel/data/fonts/impact.ttf';		
		$font_size = 12;

		$thumbnail = @imagecreate($width, $height);
		if ($thumbnail === false)				
			$this->error = 'e_unabled_create_pics';
		$background = @imagecolorallocate($thumbnail, 255, 255, 255);
		$text_color = @imagecolorallocate($thumbnail, 0, 0, 0);

		//Centrage du texte.	
		$array_size_ttf = imagettfbbox($font_size, 0, $font, $LANG['e_error_img']);
		$text_width = abs($array_size_ttf[2] - $array_size_ttf[0]);
		$text_height = abs($array_size_ttf[7] - $array_size_ttf[1]);
		$text_x = ($width/2) - ($text_width/2);
		$text_y = ($height/2) + ($text_height/2);

		//Ecriture du code.
		imagettftext($thumbnail, $font_size, 0, $text_x, $text_y, $text_color, $font, $LANG['e_error_img']);
		@imagejpeg($thumbnail, $path, 75);
	}
	
	public function get_error() { return $this->error; }
}
?>