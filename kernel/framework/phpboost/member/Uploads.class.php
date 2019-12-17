<?php
/**
 * @package     PHPBoost
 * @subpackage  Member
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 22
 * @since       PHPBoost 1.6 - 2007 04 18
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class Uploads
{
	const EMPTY_FOLDER = true;
	const ADMIN_NO_CHECK = true;

	private static $db_querier;

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	//Ajout d'un dossier virtuel
	public static function Add_folder($id_parent, $user_id, $name)
	{
		$check_folder = self::$db_querier->count(DB_TABLE_UPLOAD_CAT, 'WHERE name = :name AND id_parent = :id_parent AND user_id = :user_id', array('name' => $name, 'id_parent' => $id_parent, 'user_id' => $user_id));
		if (!empty($check_folder) || preg_match('`/|\.|\\\|"|<|>|\||\?`u', stripslashes($name)))
			return 0;

		$result = self::$db_querier->insert(DB_TABLE_UPLOAD_CAT, array('id_parent' => $id_parent, 'user_id' => $user_id, 'name' => $name));

		return $result->get_last_inserted_id();
	}

	//Suppression recursive des dossiers et fichiers du membre.
	public static function Empty_folder_member($user_id)
	{
		//Suppression des fichiers.
		$result = self::$db_querier->select("SELECT path
		FROM " . DB_TABLE_UPLOAD . "
		WHERE user_id = :user_id", array(
			'user_id' => $user_id
		));
		while($row = $result->fetch())
		{
			$file = new File(PATH_TO_ROOT . '/upload/' . $row['path']);
			$file->delete();
		}
		$result->dispose();

		//Suppression des entrées dans la base de données
		self::$db_querier->delete(DB_TABLE_UPLOAD_CAT, 'WHERE user_id = :user_id', array('user_id' => $user_id));
		self::$db_querier->delete(DB_TABLE_UPLOAD, 'WHERE user_id = :user_id', array('user_id' => $user_id));
	}

	//Suppression recursive du dossier et de son contenu.
	public static function Del_folder($id_folder)
	{
		//Suppression des fichiers.
		$result = self::$db_querier->select("SELECT path
		FROM " . DB_TABLE_UPLOAD . "
		WHERE idcat = :idcat", array(
			'idcat' => $id_folder
		));
		while ($row = $result->fetch())
		{
			$file = new File(PATH_TO_ROOT . '/upload/' . $row['path']);
			$file->delete();
		}
		$result->dispose();

		//Suppression des entrées dans la base de données
		self::$db_querier->delete(DB_TABLE_UPLOAD_CAT, 'WHERE id = :id', array('id' => $id_folder));

		self::$db_querier->delete(DB_TABLE_UPLOAD, 'WHERE idcat = :idcat', array('idcat' => $id_folder));

		$result = self::$db_querier->select("SELECT id
		FROM " . DB_TABLE_UPLOAD_CAT . "
		WHERE id_parent = :id", array(
			'id' => $id_folder
		));
		while ($row = $result->fetch())
		{
			if (!empty($row['id']))
				self::Del_folder($row['id']);
		}
		$result->dispose();
	}

	//Suppression d'un fichier
	public static function Del_file($id_file, $user_id, $admin = false)
	{
		if ($admin) //Administration, on ne vérifie pas l'appartenance.
		{
			$name = self::$db_querier->get_column_value(DB_TABLE_UPLOAD, 'path', 'WHERE id = :id', array('id' => $id_file));
			self::$db_querier->delete(DB_TABLE_UPLOAD, 'WHERE id = :id', array('id' => $id_file));

			$file = new File(PATH_TO_ROOT . '/upload/' . $name);
			$file->delete();

			return '';
		}
		else
		{
			$check_id_auth = self::$db_querier->get_column_value(DB_TABLE_UPLOAD, 'user_id', 'WHERE id = :id', array('id' => $id_file));
			//Suppression d'un fichier.
			if ($check_id_auth == $user_id)
			{
				$name = self::$db_querier->get_column_value(DB_TABLE_UPLOAD, 'path', 'WHERE id = :id', array('id' => $id_file));
				self::$db_querier->delete(DB_TABLE_UPLOAD, 'WHERE id = :id', array('id' => $id_file));

				$file = new File(PATH_TO_ROOT . '/upload/' . $name);
				$file->delete();

				return '';
			}
			return 'e_auth';
		}
	}

	//Renomme un dossier virtuel
	public static function Rename_folder($id_folder, $name, $previous_name, $user_id, $admin = false)
	{
		$info_folder = array(
			'id_parent' => '',
			'user_id' => ''
		);
		try {
			$info_folder = self::$db_querier->select_single_row(PREFIX . "upload_cat", array("id_parent", "user_id"), 'WHERE id=:id', array('id' => $id_folder));
		} catch (RowNotFoundException $e) {}

		//Vérification de l'unicité du nom du dossier.
		$check_folder = self::$db_querier->count(DB_TABLE_UPLOAD_CAT, 'WHERE id_parent = :id_parent AND name = :name AND id <> :id AND user_id = :user_id', array('id_parent' => $info_folder['id_parent'], 'name' => $name, 'id' => $id_folder, 'user_id' => $user_id));
		if ($check_folder > 0 || preg_match('`/|\.|\\\|"|<|>|\||\?`u', stripslashes($name)))
			return '';

		if ($admin) //Administration, on ne vérifie pas l'appartenance.
		{
			self::$db_querier->update(DB_TABLE_UPLOAD_CAT, array('name' => $name), 'WHERE id = :id', array('id' => $id_folder));
			return stripslashes((TextHelper::strlen(TextHelper::html_entity_decode($name)) > 22) ? TextHelper::htmlspecialchars(TextHelper::substr(TextHelper::html_entity_decode($name), 0, 22)) . '...' : $name);
		}
		else
		{
			if ($user_id == $info_folder['user_id'])
			{
				self::$db_querier->update(DB_TABLE_UPLOAD_CAT, array('name' => $name), 'WHERE id = :id', array('id' => $id_folder));
				return stripslashes((TextHelper::strlen(TextHelper::html_entity_decode($name)) > 22) ? TextHelper::htmlspecialchars(TextHelper::substr(TextHelper::html_entity_decode($name), 0, 22)) . '...' : $name);
			}
		}
		return stripslashes((TextHelper::strlen(TextHelper::html_entity_decode($previous_name)) > 22) ? TextHelper::htmlspecialchars(TextHelper::substr(TextHelper::html_entity_decode($previous_name), 0, 22)) . '...' : $previous_name);
	}

	//Renomme un fichier virtuel
	public static function Rename_file($id_file, $name, $previous_name, $user_id, $admin = false)
	{
		$info_cat = array(
			'idcat' => '',
			'user_id' => ''
		);
		try {
			$info_cat = self::$db_querier->select_single_row(PREFIX . "upload", array("idcat", "user_id"), 'WHERE id=:id', array('id' => $id_file));
		} catch (RowNotFoundException $e) {}

		//Vérification de l'unicité du nom du fichier.
		$check_file = self::$db_querier->count(DB_TABLE_UPLOAD, 'WHERE idcat = :idcat AND name = :name AND id <> :id AND user_id = :user_id', array('idcat' => $info_cat['idcat'], 'name' => $name, 'id' => $id_file, 'user_id' => $user_id));
		if ($check_file > 0 || preg_match('`/|\\\|"|<|>|\||\?`u', stripslashes($name)))
			return '/';

		if ($admin) //Administration, on ne vérifie pas l'appartenance.
		{
			self::$db_querier->update(DB_TABLE_UPLOAD, array('name' => $name), 'WHERE id = :id', array('id' => $id_file));
			return stripslashes((TextHelper::strlen(TextHelper::html_entity_decode($name)) > 22) ? TextHelper::htmlspecialchars(TextHelper::substr(TextHelper::html_entity_decode($name), 0, 22)) . '...' : $name);
		}
		else
		{
			if ($user_id == $info_cat['user_id'])
			{
				self::$db_querier->update(DB_TABLE_UPLOAD, array('name' => $name), 'WHERE id = :id', array('id' => $id_file));
				return stripslashes((TextHelper::strlen(TextHelper::html_entity_decode($name)) > 22) ? TextHelper::htmlspecialchars(TextHelper::substr(TextHelper::html_entity_decode($name), 0, 22)) . '...' : $name);
			}
		}
		return stripslashes((TextHelper::strlen(TextHelper::html_entity_decode($previous_name)) > 22) ? TextHelper::htmlspecialchars(TextHelper::substr(TextHelper::html_entity_decode($previous_name), 0, 22)) . '...' : $previous_name);
	}

	//Déplacement dun dossier.
	public static function Move_folder($move, $to, $user_id, $admin = false)
	{
		if ($admin) //Administration, on ne vérifie pas l'appartenance.
		{
			//Changement de propriètaire du fichier.
			$change_user_id = self::$db_querier->get_column_value(DB_TABLE_UPLOAD_CAT, 'user_id', 'WHERE id = :id', array('id' => $to));
			if (empty($change_user_id))
				$change_user_id = -1;
			if ($to != $move)
				self::$db_querier->update(DB_TABLE_UPLOAD_CAT, array('id_parent' => $to, 'user_id' => $change_user_id), 'WHERE id = :id', array('id' => $move));
			return '';
		}
		else
		{
			if ($to == 0) //Déplacement dossier racine du membre.
			{
				$get_mbr_folder = self::$db_querier->get_column_value(DB_TABLE_UPLOAD_CAT, 'id', 'WHERE user_id = :user_id', array('user_id' => $user_id));
				self::$db_querier->update(DB_TABLE_UPLOAD_CAT, array('id_parent' => $get_mbr_folder), 'WHERE id = :id AND user_id = :user_id', array('id' => $move, 'user_id' => $user_id));
				return '';
			}

			//Vérification de l'appartenance du dossier de destination.
			$check_user_id_move = self::$db_querier->get_column_value(DB_TABLE_UPLOAD_CAT, 'user_id', 'WHERE id = :id', array('id' => $move));
			$check_user_id_to = self::$db_querier->get_column_value(DB_TABLE_UPLOAD_CAT, 'user_id', 'WHERE id = :id', array('id' => $to));
			if ($user_id == $check_user_id_move && $user_id == $check_user_id_to)
			{
				self::$db_querier->update(DB_TABLE_UPLOAD_CAT, array('id_parent' => $to), 'WHERE id = :id AND user_id = :user_id', array('id' => $move, 'user_id' => $user_id));
				return '';
			}
			else
				return 'e_auth';
		}
	}

	//Déplacement dun fichier.
	public static function Move_file($move, $to, $user_id, $admin = false)
	{
		if ($admin) //Administration, on ne vérifie pas l'appartenance.
		{
			//Changement de propriètaire du fichier.
			$change_user_id = self::$db_querier->get_column_value(DB_TABLE_UPLOAD_CAT, 'user_id', 'WHERE id = :id', array('id' => $to));
			if (empty($change_user_id))
				$change_user_id = -1;
			self::$db_querier->update(DB_TABLE_UPLOAD, array('idcat' => $to, 'user_id' => $change_user_id), 'WHERE id = :id', array('id' => $move));
			return '';
		}
		else
		{
			if ($to == 0) //Déplacement dossier racine du membre.
			{
				$get_mbr_folder = self::$db_querier->get_column_value(DB_TABLE_UPLOAD_CAT, 'id', 'WHERE user_id = :user_id AND id_parent = 0', array('user_id' => $user_id));
				self::$db_querier->update(DB_TABLE_UPLOAD, array('idcat' => $get_mbr_folder), 'WHERE id = :id AND user_id = :user_id', array('id' => $move, 'user_id' => $user_id));
				return '';
			}

			//Vérification de l'appartenance du dossier de destination.
			$check_user_id_move = self::$db_querier->get_column_value(DB_TABLE_UPLOAD, 'user_id', 'WHERE id = :id', array('id' => $move));
			$check_user_id_to = self::$db_querier->get_column_value(DB_TABLE_UPLOAD_CAT, 'user_id', 'WHERE id = :id', array('id' => $to));
			if ($user_id == $check_user_id_move && $user_id == $check_user_id_to)
			{
				self::$db_querier->update(DB_TABLE_UPLOAD, array('idcat' => $to), 'WHERE id = :id AND user_id = :user_id', array('id' => $move, 'user_id' => $user_id));
				return '';
			}
			else
				return 'e_auth';
		}
	}

	//Fonction qui détermine toutes les sous-catégories d'une catégorie (récursive)
	public static function Find_subfolder($array_folders, $id_cat, &$array_child_folder)
	{
		//On parcourt les catégories et on déterminer les catégories filles
		foreach ($array_folders as $key => $value)
		{
			if ($value == $id_cat)
			{
				$array_child_folder[] = $key;
				//On rappelle la fonction pour la catégorie fille
				self::Find_subfolder($array_folders, $key, $array_child_folder);
			}
		}
	}

	//Récupération du répertoire courant (administration).
	public static function get_admin_url($id_folder, $pwd, $member_link = '')
	{
		$parent_folder = array(
			'id_parent' => '',
			'name' => '',
			'user_id' => ''
		);
		try {
			$parent_folder = self::$db_querier->select_single_row(PREFIX . "upload_cat", array("id_parent", "name", "user_id"), 'WHERE id=:id', array('id' => $id_folder));
		} catch (RowNotFoundException $e) {}

		if (!empty($parent_folder['id_parent']))
		{
			$pwd .= self::get_admin_url($parent_folder['id_parent'], $pwd, $member_link);
			return $pwd . '/<a href="admin_files.php?f=' . $id_folder . '">' . $parent_folder['name'] . '</a>';
		}
		else
			return ($parent_folder['user_id'] == '-1') ? $pwd . '/<a href="admin_files.php?f=' . $id_folder . '">' . $parent_folder['name'] . '</a>' : $pwd . '/' . $member_link . '<a href="admin_files.php?f=' . $id_folder . '">' . $parent_folder['name'] . '</a>';
	}

	//Récupération du répertoire courant.
	public static function get_url($id_folder, $pwd, $popup)
	{
		$parent_folder = array(
			'id_parent' => '',
			'name' => ''
		);
		try {
			$parent_folder = self::$db_querier->select_single_row(PREFIX . "upload_cat", array("id_parent", "name", "user_id"), 'WHERE id=:id', array('id' => $id_folder));
		} catch (RowNotFoundException $e) {}

		if (!empty($parent_folder['id_parent']))
		{
			$pwd .= self::get_url($parent_folder['id_parent'], $pwd, $popup);
			return $pwd . '/<a href="' . url('upload.php?f=' . $id_folder . $popup) . '">' . $parent_folder['name'] . '</a>';
		}
		else
			return $pwd . '/<a href="' . url('upload.php?f=' . $id_folder . $popup) . '">' . $parent_folder['name'] . '</a>';
	}

	//Récupération de la taille totale utilisée par un membre.
	public static function Member_memory_used($user_id)
	{
		return self::$db_querier->get_column_value(DB_TABLE_UPLOAD, 'SUM(size)', 'WHERE user_id = :user_id', array('user_id' => $user_id));
	}

	//Conversion mimetype -> image.
	public static function get_img_mimetype($type)
	{
		$filetype = sprintf(LangLoader::get_message('file_type', 'main'), TextHelper::strtoupper($type));
		switch ($type)
		{
			// Images
			case 'jpg':
			case 'jpeg':
			case 'png':
			case 'gif':
			case 'bmp':
			case 'svg':
			case 'nef':
			case 'raw':
			case 'ico':
			case 'tif':
			$img = 'far fa-file-image';
			$filetype = sprintf(LangLoader::get_message('image_type', 'main'), TextHelper::strtoupper($type));
			break;
			// Archives
			case 'rar':
			case 'gz':
			case 'zip':
			case '7z':
				$img = 'far fa-file-archive';
				$filetype = sprintf(LangLoader::get_message('zip_type', 'main'), TextHelper::strtoupper($type));
				break;
			// Pdf
			case 'pdf':
				$img = 'far fa-file-pdf';
				$filetype = LangLoader::get_message('adobe_pdf', 'main');
				break;
			// Sound
			case 'wav':
			case 'midi':
			case 'ogg':
			case 'mp3':
				$img = 'far fa-file-audio';
				$filetype = sprintf(LangLoader::get_message('audio_type', 'main'), TextHelper::strtoupper($type));
				break;
			// Sripts
			case 'html':
			case 'tpl':
			case 'css':
			case 'js':
			case 'php':
			case 'swf':
				$img = 'far fa-file-code';
				break;
			// Video
			case 'wmv':
			case 'avi':
			case 'mp4':
			case 'mkv':
			case 'mpg':
			case 'flv':
			case 'mpeg':
			case 'mov':
				$img = 'far fa-file-video';
				break;
			// Executables
			case 'exe':
				$img = 'fa fa-cog';
				break;
			// Text
			case 'txt':
			case 'csv':
				$img = 'far fa-file-alt';
				break;
			// Office
			case 'xls':
			case 'xlsx':
			case 'xlsm':
			case 'gsheet':
			case 'ods':
				$img = 'far fa-file-exel';
				break;
			case 'doc':
			case 'docx':
			case 'gdoc':
			case 'odt':
				$img = 'far fa-file-word';
				break;
			case 'ppt':
			case 'pptx':
			case 'gslides':
			case 'odp':
				$img = 'far fa-file-powerpoint';
				break;
			// 3D files
			case 'blend':
			case '3ds':
			case 'obj':
			case 'stl':
			case 'fbx':
			case 'dae':
			case 'c4d':
				$img = 'fa fa-cubes';
				break;
			//Default
			default:
				$img = 'fa fa-file-upload';
				$filetype = sprintf(LangLoader::get_message('document_type', 'main'), TextHelper::strtoupper($type));
		}

		return array('img' => $img, 'filetype' => $filetype);
	}
}
?>
