<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 29
 * @since       PHPBoost 1.2 - 2005 08 16
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

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
				imagesavealpha($thumbnail, true);

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
		// Make the background transparent
		imagecolortransparent($source, imagecolorallocate($source, 0, 0, 0));
		imagealphablending($source, false); // turn off the alpha blending to keep the alpha channel
		imagesavealpha($source, true);

		$path_mini = str_replace('pics', 'pics/thumbnails', $path);
		if (function_exists('imagegif') && $ext === 'gif')
			imagegif ($thumbnail, $path_mini);
		elseif (function_exists('imagejpeg') && ($ext === 'jpg' || $ext === 'jpeg'))
			imagejpeg($thumbnail, $path_mini, GalleryConfig::load()->get_quality());
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
		global $LANG;
		$config = GalleryConfig::load();

		if ($config->is_logo_enabled() && is_file($config->get_logo())) //Incrustation du logo.
		{
			list($width_s, $height_s, $weight_s, $ext_s) = $this->Arg_pics($config->get_logo());
			list($width, $height, $weight, $ext) = $this->Arg_pics($path);

			if ($width_s <= $width && $height_s <= $height)
			{
				switch ($ext_s) //Création de l'image suivant l'extension.
				{
					case 'jpg':
					case 'jpeg':
						$source = @imagecreatefromjpeg($config->get_logo());
						break;
					case 'gif':
						$source = @imagecreatefromgif ($config->get_logo());
						break;
					case 'png':
						$source = @imagecreatefrompng($config->get_logo());
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

					// On veut placer le logo en bas à droite, on calcule les coordonnées où on doit placer le logo sur la photo
					$destination_x = $width - $width_s - $config->get_logo_horizontal_distance();
					$destination_y =  $height - $height_s - $config->get_logo_vertical_distance();

					// Création d'une nouvelle image
       				$image_with_logo = imagecreatetruecolor($width, $height);

					//Sauvegarde des informations de transparences
					imagesavealpha($image_with_logo, true);

					//Création d'un background transparent
					$trans_background = imagecolorallocatealpha($image_with_logo, 0, 0, 0, 127);

					//On ajoute le background sur l'image final
					imagefill($image_with_logo, 0, 0, $trans_background);

					//On ajoute l'image souhaité sur l'image final
					imagecopy($image_with_logo, $destination, 0, 0, 0, 0, $width, $height);

					//On ajoute le logo sur l'image final
					//Si le logo est au format png ou gif, la gestion de transparence est faite dans le logo lui meme. Sinon elle est fait selon la configuration du module.
					if ($ext_s == 'png' || $ext_s == 'gif')
					{
						if (@imagecopy($image_with_logo, $source, $destination_x, $destination_y, 0, 0, $width_s, $height_s) === false)
							$this->error = 'e_unabled_incrust_logo';
					}
					else
					{
						if (@imagecopymerge($image_with_logo, $source, $destination_x, $destination_y, 0, 0, $width_s, $height_s, (100 - $config->get_logo_transparency())) === false)
							$this->error = 'e_unabled_incrust_logo';
					}

					switch ($ext) //Création de l'image suivant l'extension.
					{
						case 'jpg':
						case 'jpeg':
							imagejpeg($image_with_logo);
							break;
						case 'gif':
							imagegif ($image_with_logo);
							break;
						case 'png':
							imagepng($image_with_logo);
							break;
						default:
							$this->error = 'e_unabled_create_pics';
					}

				}
			}
			else
				readfile($path); //On affiche simplement.
		}
		else
			readfile($path); //On affiche simplement.
	}

	//Insertion base de donnée
	public function Add_pics($id_category, $name, $path, $user_id)
	{
		list($width, $height, $weight, $ext) = $this->Arg_pics('pics/' . $path);
		$result = PersistenceContext::get_querier()->insert(GallerySetup::$gallery_table, array('id_category' => $id_category, 'name' => $name, 'path' => $path, 'width' => $width, 'height' => $height, 'weight' => $weight, 'user_id' => $user_id, 'aprob' => 1, 'views' => 0, 'timestamp' => time()));
		return $result->get_last_inserted_id();
	}

	//Supprime une image
	public function Del_pics($id_pics)
	{
		try {
			$info_pics = PersistenceContext::get_querier()->select_single_row(GallerySetup::$gallery_table, array('path', 'id_category', 'aprob'), "WHERE id = :id", array('id' => $id_pics));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		if (!empty($info_pics['path']))
		{
			PersistenceContext::get_querier()->delete(PREFIX . 'gallery', 'WHERE id=:id', array('id' => $id_pics));

			//Suppression physique.
			$file = new File(PATH_TO_ROOT . '/gallery/pics/' . $info_pics['path']);
			$file->delete();

			$file = new File(PATH_TO_ROOT . '/gallery/pics/thumbnails/' . $info_pics['path']);
			$file->delete();

			NotationService::delete_notes_id_in_module('gallery', $id_pics);

			CommentsService::delete_comments_topic_module('gallery', $id_pics);
		}
	}

	//Renomme une image.
	public function Rename_pics($id_pics, $name, $previous_name)
	{
		PersistenceContext::get_querier()->update(GallerySetup::$gallery_table, array('name' => $name), 'WHERE id = :id', array('id' => $id_pics));
		return stripslashes((TextHelper::strlen(TextHelper::html_entity_decode($name)) > 22) ? TextHelper::htmlspecialchars(TextHelper::substr(TextHelper::html_entity_decode($name), 0, 22)) . PATH_TO_ROOT . '.' : $name);
	}

	//Approuve une image.
	public function Aprob_pics($id_pics)
	{
		try {
			$aprob = PersistenceContext::get_querier()->get_column_value(GallerySetup::$gallery_table, 'aprob', "WHERE id = :id", array('id' => $id_pics));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		if ($aprob)
		{
			PersistenceContext::get_querier()->update(GallerySetup::$gallery_table, array('aprob' => 0), 'WHERE id = :id', array('id' => $id_pics));
		}
		else
		{
			PersistenceContext::get_querier()->update(GallerySetup::$gallery_table, array('aprob' => 1), 'WHERE id = :id', array('id' => $id_pics));
		}

		return $aprob;
	}

	//Déplacement d'une image.
	public function Move_pics($id_pics, $id_move)
	{
		PersistenceContext::get_querier()->update(GallerySetup::$gallery_table, array('id_category' => $id_move), 'WHERE id = :id', array('id' => $id_pics));
	}

	//Vérifie si le membre peut uploader une image
	public function Auth_upload_pics($user_id, $level)
	{
		$config = GalleryConfig::load();

		switch ($level)
		{
			case 2:
			$pics_quota = 10000;
			break;
			case 1:
			$pics_quota = $config->get_moderator_max_pics_number();
			break;
			default:
			$pics_quota = $config->get_member_max_pics_number();
		}

		if ($this->get_nbr_upload_pics($user_id) >= $pics_quota)
			return false;

		return true;
	}

	//Arguments de l'image, hauteur, largeur, extension.
	public function Arg_pics($path)
	{
		global $LANG;

		//Vérification du chargement de la librairie GD.
		if (!@extension_loaded('gd'))
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'),
                $LANG['e_no_gd'], UserErrorController::FATAL);
            DispatchManager::redirect($controller);
		}

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
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $LANG['e_no_getimagesize'], UserErrorController::FATAL);
			DispatchManager::redirect($controller);
		}
	}

	//Compte le nombre d'images uploadée par un membre.
	public function get_nbr_upload_pics($user_id)
	{
		return PersistenceContext::get_querier()->count(GallerySetup::$gallery_table, 'WHERE user_id=:user_id', array('user_id' => $user_id));
	}

	//Calcul des dimensions avec respect des proportions.
	public function get_resize_properties($width_s, $height_s, $width_max = 0, $height_max = 0)
	{
		$config = GalleryConfig::load();

		$width_max = ($width_max == 0) ? $config->get_mini_max_width() : $width_max;
		$height_max = ($height_max == 0) ? $config->get_mini_max_height() : $height_max;
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

	//Vidange des miniatures du FTP et de la bdd => régénérée plus tard lors des affichages..
	public function Clear_cache()
	{
		//On recupère les dossier des thèmes contenu dans le dossier images/smiley.
		$thumb_folder_path = new Folder('./pics/thumbnails/');
		foreach ($thumb_folder_path->get_files('`\.(png|jpg|jpeg|bmp|gif)$`iu') as $thumbs)
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
		global $LANG;
		$config = GalleryConfig::load();

		$width = ($width == 0) ? $config->get_mini_max_width() : $width;
		$height = ($height == 0) ? $config->get_mini_max_height() : $height;

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
