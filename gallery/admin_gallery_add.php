<?php
/*##################################################
 *                               admin_gallery_add.php
 *                            -------------------
 *   begin                : August 17, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../admin/admin_begin.php');
load_module_lang('gallery'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$Cache->load('gallery');
$Gallery = new Gallery();

$idcat = !empty($_GET['cat']) ? NumberHelper::numeric($_GET['cat']) : 0;
$idcat_post = !empty($_POST['idcat_post']) ? NumberHelper::numeric($_POST['idcat_post']) : 0;
$add_pic = !empty($_GET['add']) ? NumberHelper::numeric($_GET['add']) : 0;
$nbr_pics_post = !empty($_POST['nbr_pics']) ? NumberHelper::numeric($_POST['nbr_pics']) : 0;

if (isset($_FILES['gallery']) && isset($_POST['idcat_post'])) //Upload
{
	$dir = 'pics/';
	$Upload = new Upload($dir);
	$Upload->disableContentCheck();

	$idpic = 0;
	$Upload->file('gallery', '`([a-z0-9()_-])+\.(jpg|jpeg|gif|png)+$`i', Upload::UNIQ_NAME, $CONFIG_GALLERY['weight_max']);
	if ($Upload->get_error() != '') //Erreur, on arrête ici
		AppContext::get_response()->redirect('/gallery/admin_gallery_add.php?error=' . $Upload->get_error() . '#message_helper');
	else
	{
		$path = $dir . $Upload->get_filename();
		$error = $Upload->check_img($CONFIG_GALLERY['width_max'], $CONFIG_GALLERY['height_max'], Upload::DELETE_ON_ERROR);
		if (!empty($error)) //Erreur, on arrête ici
			AppContext::get_response()->redirect('/gallery/admin_gallery_add.php?error=' . $error . '#message_helper');
		else
		{
			//Enregistrement de l'image dans la bdd.
			$Gallery->Resize_pics($path);
			if ($Gallery->get_error() != '')
				AppContext::get_response()->redirect('/gallery/admin_gallery_add.php?error=' . $Gallery->get_error() . '#message_helper');

			$name = !empty($_POST['name']) ? TextHelper::strprotect($_POST['name']) : '';
			$idpic = $Gallery->Add_pics($idcat_post, $name, $Upload->get_filename(), $User->get_id());
			if ($Gallery->get_error() != '')
				AppContext::get_response()->redirect('/gallery/admin_gallery_add.php?error=' . $Gallery->get_error() . '#message_helper');

			//Régénération du cache des photos aléatoires.
			$Cache->Generate_module_file('gallery');
		}
	}

	AppContext::get_response()->redirect('/gallery/admin_gallery_add.php?add=' . $idpic);
}
elseif (!empty($_POST['valid']) && !empty($nbr_pics_post)) //Ajout massif d'images par ftp.
{
	for ($i = 1; $i <= $nbr_pics_post; $i++)
	{
		$activ = !empty($_POST[$i . 'activ']) ? trim($_POST[$i . 'activ']) : '';
		$uniq = !empty($_POST[$i . 'uniq']) ? TextHelper::strprotect($_POST[$i . 'uniq']) : '';
		if ($activ && !empty($uniq)) //Sélectionné.
		{
			$name = !empty($_POST[$i . 'name']) ? TextHelper::strprotect($_POST[$i . 'name']) : 0;
			$cat = !empty($_POST[$i . 'cat']) ? NumberHelper::numeric($_POST[$i . 'cat']) : 0;
			$del = !empty($_POST[$i . 'del']) ? NumberHelper::numeric($_POST[$i . 'del']) : 0;

			if ($del)
				delete_file('pics/' . $uniq);
			else
				$Gallery->Add_pics($cat, $name, $uniq, $User->get_id());
		}
	}

	//Régénération du cache des photos aléatoires.
	$Cache->Generate_module_file('gallery');

	AppContext::get_response()->redirect('/gallery/admin_gallery_add.php');
}
else
{
	$Template->set_filenames(array(
		'admin_gallery_add'=> 'gallery/admin_gallery_add.tpl'
	));

	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? trim($_GET['error']) : '';
	$array_error = array('e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_max_dimension', 'e_upload_error', 'e_upload_php_code', 'e_upload_failed_unwritable', 'e_upload_already_exist', 'e_unlink_disabled', 'e_unsupported_format', 'e_unabled_create_pics', 'e_error_resize', 'e_no_graphic_support', 'e_unabled_incrust_logo', 'delete_thumbnails');
	if (in_array($get_error, $array_error))
		$Template->put('message_helper', MessageHelper::display($LANG[$get_error], E_USER_WARNING));

	//Création de la liste des catégories.
	$cat_list = '<option value="0" selected="selected">' . $LANG['root'] . '</option>';
	$cat_list_unselect = '<option value="0" selected="selected">' . $LANG['root'] . '</option>';
	$result = $Sql->query_while("SELECT id, level, name
	FROM " . PREFIX . "gallery_cats
	ORDER BY id_left", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
		$selected = ($row['id'] == $idcat) ? ' selected="selected"' : '';
		$cat_list .= '<option value="' . $row['id'] . '"' . $selected . '>' . $margin . ' ' . $row['name'] . '</option>';
		$cat_list_unselect .= '<option value="' . $row['id'] . '">' . $margin . ' ' . $row['name'] . '</option>';
	}
	$Sql->query_close($result);

	//Aficchage de la photo uploadée.
	if (!empty($add_pic))
	{
		$CAT_GALLERY[0]['name'] = $LANG['root'];
		$imageup = $Sql->query_array(PREFIX . "gallery", "idcat", "name", "path", "WHERE id = '" . $add_pic . "'", __LINE__, __FILE__);
		$Template->assign_block_vars('image_up', array(
			'NAME' => $imageup['name'],
			'IMG' => '<a href="admin_gallery.php?cat=' . $imageup['idcat'] . '&amp;id=' . $add_pic . '#pics_max"><img src="pics/' . $imageup['path'] . '" alt="" /></a>',
			'L_SUCCESS_UPLOAD' => $LANG['success_upload_img'],
			'U_CAT' => '<a href="admin_gallery.php?cat=' . $imageup['idcat'] . '">' . $CAT_GALLERY[$imageup['idcat']]['name'] . '</a>'
		));
	}

	$Template->put_all(array(
		'WIDTH_MAX' => $CONFIG_GALLERY['width_max'],
		'HEIGHT_MAX' => $CONFIG_GALLERY['height_max'],
		'WEIGHT_MAX' => $CONFIG_GALLERY['weight_max'],
		'AUTH_EXTENSION' => 'JPEG, GIF, PNG',
		'CATEGORIES' => $cat_list,
		'IMG_HEIGHT_MAX' => $CONFIG_GALLERY['height']+10,
		'L_GALLERY_MANAGEMENT' => $LANG['gallery_management'],
		'L_GALLERY_PICS_ADD' => $LANG['gallery_pics_add'],
		'L_GALLERY_CAT_MANAGEMENT' => $LANG['gallery_cats_management'],
		'L_GALLERY_CAT_ADD' => $LANG['gallery_cats_add'],
		'L_GALLERY_CONFIG' => $LANG['gallery_config'],
		'L_ADD_IMG' => $LANG['add_pic'],
		'L_WEIGHT_MAX' => $LANG['weight_max'],
		'L_HEIGHT_MAX' => $LANG['height_max'],
		'L_WIDTH_MAX' => $LANG['width_max'],
		'L_UPLOAD_IMG' => $LANG['upload_pics'],
		'L_AUTH_EXTENSION' => $LANG['auth_extension'],
		'L_CATEGORY' => $LANG['category'],
		'L_IMG_DISPO_GALLERY' => $LANG['img_dispo'],
		'L_REQUIRE' => $LANG['require'],
		'L_SELECT_IMG_ADD' => $LANG['select_img_add'],
		'L_NAME' => $LANG['name'],
		'L_UNIT_PX' => $LANG['unit_pixels'],
		'L_UNIT_KO' => $LANG['unit_kilobytes'],
		'L_SELECT' => $LANG['select'],
		'L_CAT' => $LANG['categorie'],
		'L_DELETE' => $LANG['delete'],
		'L_SUBMIT' => $LANG['submit']
	));

	//Affichage photos
	$dir = 'pics/';
	if (is_dir($dir)) //Si le dossier existe
	{


		$array_pics = array();
		$image_folder_path = new Folder('./pics/');
		foreach ($image_folder_path->get_files('`.*\.(png|jpg|bmp|gif|jpeg|tiff)$`i') as $image)
			$array_pics[] = $image->get_name();

		if (is_array($array_pics))
		{
			$result = $Sql->query_while("SELECT path
			FROM " . PREFIX . "gallery", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				//On recherche les clées correspondante à celles trouvée dans la bdd.
				$key = array_search($row['path'], $array_pics);
				if ($key !== false)
					unset($array_pics[$key]); //On supprime ces clées du tableau.
			}
			$Sql->query_close($result);

			//Colonnes des images.
			$nbr_pics = count($array_pics);
			$nbr_column_pics = ($nbr_pics > $CONFIG_GALLERY['nbr_column']) ? $CONFIG_GALLERY['nbr_column'] : $nbr_pics;
			$nbr_column_pics = !empty($nbr_column_pics) ? $nbr_column_pics : 1;
			$column_width_pics = floor(100/$nbr_column_pics);

			$Template->put_all(array(
				'NBR_PICS' => $nbr_pics,
				'COLUMN_WIDTH_PICS' => $column_width_pics
			));

			$j = 0;
			foreach ($array_pics as  $key => $pics)
			{
				$height = 150;
				$width = 150;
				if (function_exists('getimagesize')) //On verifie l'existence de la fonction getimagesize.
				{
					// On recupère la hauteur et la largeur de l'image.
					list($width_source, $height_source) = @getimagesize($rep . $pics);

					$height_max = 150;
					$width_max = 150;

					if (($width_source > $width_max) || ($height_source > $height_max))
					{
						if ($width_source > $height_source)
						{
							$ratio = $width_source / $height_source;
							$width = $width_max;
							$height = ceil($width / $ratio);
						}
						else
						{
							$ratio = $height_source / $width_source;
							$height = $height_max;
							$width = ceil($height / $ratio);
						}
					}
					else
					{
						$width = $width_source;
						$height = $height_source;
					}
				}

				//On genère le tableau pour x colonnes
				$tr_start = is_int($j / $nbr_column_pics) ? '<tr>' : '';
				$j++;
				$tr_end = is_int($j / $nbr_column_pics) ? '</tr>' : '';

				//On raccourci le nom du fichier pour ne pas déformer l'administration.
				$name = strlen($pics) > 20 ? substr($pics, 0, 20) . '...' : $pics;

				//Si la miniature n'existe pas (cache vidé) on regénère la miniature à partir de l'image en taille réelle.
				if (!file_exists('pics/thumbnails/' . $pics) && file_exists('pics/' . $pics))
					$Gallery->Resize_pics('pics/' . $pics); //Redimensionnement + création miniature

				$Template->assign_block_vars('list', array(
					'ID' => $j,
					'THUMNAILS' => '<img src="pics/thumbnails/' .  $pics . '" alt="" />',
					'NAME' => $pics,
					'Upload::UNIQ_NAME' => $pics,
					'TR_START' => $tr_start,
					'TR_END' => $tr_end,
					'CATEGORIES' => $cat_list_unselect
				));
			}

			//Création des cellules du tableau si besoin est.
			while (!is_int($j/$nbr_column_pics))
			{
				$j++;
				$Template->assign_block_vars('end_td_pics', array(
					'TD_END' => '<td class="row1" style="width:' . $column_width_pics . '%;padding:0">&nbsp;</td>',
					'TR_END' => (is_int($j/$nbr_column_pics)) ? '</tr>' : ''
				));
			}
		}
	}

	if ($j == 0)
	{
		$Template->assign_block_vars('no_img', array(
			'L_NO_IMG' => $LANG['no_pics']
		));
	}

	$Template->pparse('admin_gallery_add');
}

require_once('../admin/admin_footer.php');

?>