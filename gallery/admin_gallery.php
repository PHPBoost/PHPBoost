<?php
/*##################################################
 *                               admin_gallery.php
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

$idcat = !empty($_GET['cat']) ? NumberHelper::numeric($_GET['cat']) : 0;
$idpics = !empty($_GET['id']) ? NumberHelper::numeric($_GET['id']) : 0;
$del = !empty($_GET['del']) ? NumberHelper::numeric($_GET['del']) : 0;
$move = !empty($_GET['move']) ? NumberHelper::numeric($_GET['move']) : 0;

$Gallery = new Gallery();
$config = GalleryConfig::load();

$Cache->load('gallery');

if (!empty($idpics) && isset($_GET['move'])) //Déplacement d'une image.
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf

	$Gallery->Move_pics($idpics, $move);

	//Régénération du cache des photos aléatoires.
	$Cache->Generate_module_file('gallery');

	AppContext::get_response()->redirect('/gallery/admin_gallery.php?cat=' . $move);
}
elseif (!empty($del)) //Suppression d'une image.
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf

	$Gallery->Del_pics($del);

	//Régénération du cache des photos aléatoires.
	$Cache->Generate_module_file('gallery');

	AppContext::get_response()->redirect('/gallery/admin_gallery.php?cat=' . $idcat);
}
else
{
	$Template->set_filenames(array(
		'admin_gallery_management'=> 'gallery/admin_gallery_management.tpl'
	));

	if (!empty($idcat) && !isset($CAT_GALLERY[$idcat]))
		AppContext::get_response()->redirect('/gallery/admin_gallery.php?error=unexist_cat');

	if (!empty($idcat))
	{
		//Création de l'arborescence des catégories.
		$cat_links = '';
		foreach ($CAT_GALLERY as $id => $array_info_cat)
		{
			if ($CAT_GALLERY[$idcat]['id_left'] >= $array_info_cat['id_left'] && $CAT_GALLERY[$idcat]['id_right'] <= $array_info_cat['id_right'] && $array_info_cat['level'] <= $CAT_GALLERY[$idcat]['level'])
				$cat_links .= ' <a href="admin_gallery.php?cat=' . $id . '">' . $array_info_cat['name'] . '</a> &raquo;';
		}

		$clause_cat = " WHERE gc.id_left > '" . $CAT_GALLERY[$idcat]['id_left'] . "' AND id_right < '" . $CAT_GALLERY[$idcat]['id_right'] . "' AND level = '" . $CAT_GALLERY[$idcat]['level'] . "' + 1";
	}
	else
	{
		$cat_links = '';
		$clause_cat = " WHERE level = '0'";
		$CAT_GALLERY[0]['name'] = $LANG['root'];
		$CAT_GALLERY[0]['level'] = -1;
		$CAT_GALLERY[0]['aprob'] = 1;
	}
	
	$nbr_pics = PersistenceContext::get_querier()->count(PREFIX . "gallery", 'WHERE idcat=:idcat', array('idcat' => $idcat));
	$total_cat = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "gallery_cats gc " . $clause_cat);

	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? trim($_GET['error']) : '';
	if ($get_error == 'unexist_cat')
		$Template->put('message_helper', MessageHelper::display($LANG['e_unexist_cat'], MessageHelper::NOTICE));

	//On crée une pagination si le nombre de catégories est trop important.
	$page = AppContext::get_request()->get_getint('p', 1);
	$pagination = new ModulePagination($page, $total_cat, $config->get_pics_number_per_page());
	$pagination->set_url(new Url('/gallery/admin_gallery.php?p=%d'));
	
	if ($pagination->current_page_is_empty() && $page > 1)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	//Colonnes des catégories.
	$nbr_column_cats = ($total_cat > $config->get_columns_number()) ? $config->get_columns_number() : $total_cat;
	$nbr_column_cats = !empty($nbr_column_cats) ? $nbr_column_cats : 1;
	$column_width_cats = floor(100/$nbr_column_cats);

	//Colonnes des images.
	$nbr_column_pics = ($nbr_pics > $config->get_columns_number()) ? $config->get_columns_number() : $nbr_pics;
	$nbr_column_pics = !empty($nbr_column_pics) ? $nbr_column_pics : 1;
	$column_width_pics = floor(100/$nbr_column_pics);

	$Template->put_all(array(
		'C_PAGINATION' => $pagination->has_several_pages(),
		'PAGINATION' => $pagination->display(),
		'COLUMN_WIDTH_CAT' => $column_width_cats,
		'COLUMN_WIDTH_PICS' => $column_width_pics,
		'COLSPAN' => $config->get_columns_number(),
		'CAT_ID' => $idcat,
		'GALLERY' => !empty($idcat) ? $CAT_GALLERY[$idcat]['name'] : $LANG['gallery'],
		'HEIGHT_MAX' => ($config->get_mini_max_height() - 15),
		'ARRAY_JS' => '',
		'NBR_PICS' => 0,
		'MAX_START' => 0,
		'START_THUMB' => 0,
		'END_THUMB' => 0,
		'L_GALLERY_MANAGEMENT' => $LANG['gallery_management'],
		'L_GALLERY_PICS_ADD' => $LANG['gallery_pics_add'],
		'L_GALLERY_CAT_MANAGEMENT' => $LANG['gallery_cats_management'],
		'L_GALLERY_CAT_ADD' => $LANG['gallery_cats_add'],
		'L_GALLERY_CONFIG' => $LANG['gallery_config'],
		'L_CONFIRM_DEL_FILE' => $LANG['confim_del_file'],
		'L_FILE_FORBIDDEN_CHARS' => $LANG['file_forbidden_chars'],
		'L_TOTAL_IMG' => sprintf($LANG['total_img_cat'], $nbr_pics),
		'L_ADD_IMG' => $LANG['add_pic'],
		'L_GALLERY' => $LANG['gallery'],
		'L_CATEGORIES' => ($CAT_GALLERY[$idcat]['level'] >= 0) ? $LANG['sub_album'] : $LANG['album'],
		'L_NAME' => $LANG['name'],
		'L_APROB' => $LANG['aprob'],
		'L_UNAPROB' => $LANG['unaprob'],
		'L_EDIT' => $LANG['edit'],
		'L_MOVETO' => $LANG['moveto'],
		'L_DELETE' => $LANG['delete'],
		'L_SUBMIT' => $LANG['submit'],
		'U_GALLERY_CAT_LINKS' => $cat_links
	));

	##### Catégorie disponibles #####
	if ($total_cat > 0)
	{
		$Template->assign_block_vars('cat', array(
		));

		$i = 0;
		$result = $Sql->query_while ("SELECT gc.id, gc.name, gc.status, (gc.nbr_pics_aprob + gc.nbr_pics_unaprob) AS nbr_pics, gc.nbr_pics_unaprob, g.path
		FROM " . PREFIX . "gallery_cats gc
		LEFT JOIN " . PREFIX . "gallery g ON g.idcat = gc.id
		" . $clause_cat . "
		GROUP BY gc.id
		ORDER BY gc.id_left
		" . $Sql->limit($pagination->get_display_from(), $config->get_pics_number_per_page()));
		while ($row = $Sql->fetch_assoc($result))
		{
			//On genère le tableau pour $config->get_columns_number() colonnes
			$multiple_x = $i / $nbr_column_cats;
			$tr_start = is_int($multiple_x) ? '<tr>' : '';
			$i++;
			$multiple_x = $i / $nbr_column_cats;
			$tr_end = is_int($multiple_x) ? '</tr>' : '';

			//Si la miniature n'existe pas (cache vidé) on regénère la miniature à partir de l'image en taille réelle.
			if (!file_exists('pics/thumbnails/' . $row['path']))
				$Gallery->Resize_pics('pics/' . $row['path']); //Redimensionnement + création miniature

			$Template->assign_block_vars('cat.list', array(
				'C_IMG' => !empty($row['path']),
				'IDCAT' => $row['id'],
				'CAT' => $row['name'],
				'IMG' => '<img src="pics/thumbnails/' . $row['path'] . '" alt="" />',
				'TR_START' => $tr_start,
				'TR_END' => $tr_end,
				'LOCK' => ($row['status'] == 0) ? '<i class="fa fa-lock"></>' : '',
				'L_NBR_PICS' => sprintf($LANG['nbr_pics_info_admin'], $row['nbr_pics'], $row['nbr_pics_unaprob'])
			));
		}
		$result->dispose();

		//Création des cellules du tableau si besoin est.
		while (!is_int($i/$nbr_column_cats))
		{
			$i++;
			$Template->assign_block_vars('cat.end_td', array(
				'TD_END' => '<td style="width:' . $column_width_cats . '%">&nbsp;</td>',
				'TR_END' => (is_int($i/$nbr_column_cats)) ? '</tr>' : ''
			));
		}
	}

	##### Affichage des photos #####
	$Template->assign_block_vars('pics', array(
		'C_PICTURES' => $nbr_pics > 0,
		'C_PICS_MAX' => $nbr_pics == 0 || !empty($idpics),
		'EDIT' => '<a href="admin_gallery_cat.php' . (!empty($idcat) ? '?id=' . $idcat : '') . '" title="' . $LANG['edit'] . '" class="fa fa-edit"></a>',
		'PICS_MAX' => '<img src="show_pics.php?id=' . $idpics . '&amp;cat=' . $idcat . '" alt="" / >'
	));
	
	if ($nbr_pics > 0)
	{
		//On crée une pagination si le nombre de photos est trop important.
		$page = AppContext::get_request()->get_getint('pp', 1);
		$pagination = new ModulePagination($page, $nbr_pics, $config->get_pics_number_per_page());
		$pagination->set_url(new Url('/gallery/admin_gallery.php?cat=' . $idcat . '&amp;pp=%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		$Template->put_all(array(
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display()
		));

		$array_cat_list = array(0 => '<option value="0" %s>' . $LANG['root'] . '</option>');
		$result = $Sql->query_while("SELECT id, level, name
		FROM " . PREFIX . "gallery_cats
		ORDER BY id_left");
		while ($row = $Sql->fetch_assoc($result))
		{
			$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
			$array_cat_list[$row['id']] = '<option value="' . $row['id'] . '" %s>' . $margin . ' ' . $row['name'] . '</option>';
		}
		$result->dispose();

		if (!empty($idpics))
		{
			$result = $Sql->query_while("SELECT g.id, g.idcat, g.name, g.user_id, g.views, g.width, g.height, g.weight, g.timestamp, g.aprob, m.display_name, m.level, m.user_groups
			FROM " . PREFIX . "gallery g
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
			WHERE g.idcat = '" . $idcat . "' AND g.id = '" . $idpics . "'
			" . $Sql->limit(0, 1));
			$info_pics = $Sql->fetch_assoc($result);
			if (!empty($info_pics['id']))
			{
				//Affichage miniatures.
				$id_previous = 0;
				$id_next = 0;
				$nbr_pics_display_before = floor(($nbr_column_pics - 1)/2); //Nombres de photos de chaque côté de la miniature de la photo affichée.
				$nbr_pics_display_after = ($nbr_column_pics - 1) - floor($nbr_pics_display_before);
				list($i, $reach_pics_pos, $pos_pics, $thumbnails_before, $thumbnails_after, $start_thumbnails, $end_thumbnails) = array(0, false, 0, 0, 0, $nbr_pics_display_before, $nbr_pics_display_after);
				$array_pics = array();
				$array_js = 'var array_pics = new Array();';
				$result = $Sql->query_while("SELECT g.id, g.idcat, g.path
				FROM " . PREFIX . "gallery g
				LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
				WHERE g.idcat = '" . $idcat . "'");
				while ($row = $Sql->fetch_assoc($result))
				{
					//Si la miniature n'existe pas (cache vidé) on regénère la miniature à partir de l'image en taille réelle.
					if (!file_exists('pics/thumbnails/' . $row['path']))
						$Gallery->Resize_pics('pics/' . $row['path']); //Redimensionnement + création miniature

					//Affichage de la liste des miniatures sous l'image.
					$array_pics[] = '<td style="text-align:center;height:' . ($config->get_mini_max_height() + 16) . 'px"><span id="thumb' . $i . '"><a href="admin_gallery.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'] . '#pics_max' . '"><img src="pics/thumbnails/' . $row['path'] . '" alt="" / ></a></span></td>';

					if ($row['id'] == $idpics)
					{
						$reach_pics_pos = true;
						$pos_pics = $i;
					}
					else
					{

						if (!$reach_pics_pos)
						{
							$thumbnails_before++;
							$id_previous = $row['id'];
						}
						else
						{
							$thumbnails_after++;
							if (empty($id_next))
								$id_next = $row['id'];
						}
					}

					$array_js .= 'array_pics[' . $i . '] = new Array();' . "\n";
					$array_js .= 'array_pics[' . $i . '][\'link\'] = \'.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'] . '#pics_max' . "';\n";
					$array_js .= 'array_pics[' . $i . '][\'path\'] = \'' . $row['path'] . "';\n";
					$i++;
				}
				$result->dispose();

				if ($thumbnails_before < $nbr_pics_display_before)
					$end_thumbnails += $nbr_pics_display_before - $thumbnails_before;
				if ($thumbnails_after < $nbr_pics_display_after)
					$start_thumbnails += $nbr_pics_display_after - $thumbnails_after;

				$Template->put_all(array(
					'ARRAY_JS' => $array_js,
					'NBR_PICS' => ($i - 1),
					'MAX_START' => ($i - 1) - $nbr_column_pics,
					'START_THUMB' => (($pos_pics - $start_thumbnails) > 0) ? ($pos_pics - $start_thumbnails) : 0,
					'END_THUMB' => ($pos_pics + $end_thumbnails),
					'L_INFORMATIONS' => $LANG['informations'],
					'L_NAME' => $LANG['name'],
					'L_POSTOR' => $LANG['postor'],
					'L_VIEWS' => $LANG['views'],
					'L_ADD_ON' => $LANG['add_on'],
					'L_DIMENSION' => $LANG['dimension'],
					'L_SIZE' => $LANG['size'],
					'L_EDIT' => $LANG['edit'],
					'L_APROB' => $LANG['aprob'],
					'L_UNAPROB' => $LANG['unaprob'],
					'L_THUMBNAILS' => $LANG['thumbnails']
				));

				//Liste des catégories.
				$cat_list = '';
				foreach ($array_cat_list as $key_cat => $option_value)
					$cat_list .= ($key_cat == $info_pics['idcat']) ? sprintf($option_value, 'selected="selected"') : sprintf($option_value, '');
				
				$group_color = User::get_group_color($info_pics['groups'], $info_pics['level']);
				
				//Affichage de l'image et de ses informations.
				$Template->assign_block_vars('pics.pics_max', array(
					'C_APPROVED' => $info_pics['aprob'],
					'C_PREVIOUS' => ($pos_pics > 0),
					'C_NEXT' => ($pos_pics < ($i - 1)),
					'C_LEFT_THUMBNAILS' => ($pos_pics - $start_thumbnails),
					'C_RIGHT_THUMBNAILS' => (($pos_pics - $start_thumbnails) <= ($i - 1) - $nbr_column_pics),
					'ID' => $info_pics['id'],
					'IMG' => '<img src="show_pics.php?id=' . $idpics . '&amp;cat=' . $idcat . '" alt="" / >',
					'NAME' => '<span id="fi_' . $info_pics['id'] . '">' . stripslashes($info_pics['name']) . '</span> <span id="fi' . $info_pics['id'] . '"></span>',
					'POSTOR' => '<a class="' . UserService::get_level_class($info_pics['level']) . '"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . ' href="'. UserUrlBuilder::profile($info_pics['user_id'])->rel() .'">' . $info_pics['login'] . '</a>',
					'DATE' => gmdate_format('date_format_short', $info_pics['timestamp']),
					'VIEWS' => ($info_pics['views'] + 1),
					'DIMENSION' => $info_pics['width'] . ' x ' . $info_pics['height'],
					'SIZE' => NumberHelper::round($info_pics['weight']/1024, 1),
					'COLSPAN' => ($config->get_columns_number() + 2),
					'CAT' => $cat_list,
					'RENAME' => addslashes($info_pics['name']),
					'RENAME_CUT' => addslashes($info_pics['name']),
					'U_DEL' => 'php?del=' . $info_pics['id'] . '&amp;cat=' . $idcat . '&amp;token=' . AppContext::get_session()->get_token(),
					'U_MOVE' => '.php?id=' . $info_pics['id'] . '&amp;token=' . AppContext::get_session()->get_token() . '&amp;move=\' + this.options[this.selectedIndex].value',
					'U_PREVIOUS' => '<a href="admin_gallery.php?cat=' . $idcat . '&amp;id=' . $id_previous . '#pics_max" class="fa fa-arrow-left fa-2x"></a> <a href="admin_gallery.php?cat=' . $idcat . '&amp;id=' . $id_previous . '#pics_max">' . $LANG['previous'] . '</a>',
					'U_NEXT' => '<a href="admin_gallery.php?cat=' . $idcat . '&amp;id=' . $id_next . '#pics_max">' . $LANG['next'] . '</a> <a href="admin_gallery.php?cat=' . $idcat . '&amp;id=' . $id_next . '#pics_max" class="fa fa-arrow-right fa-2x"></a>'
				));

				//Affichage de la liste des miniatures sous l'image.
				$i = 0;
				foreach ($array_pics as $pics)
				{
					if ($i >= ($pos_pics - $start_thumbnails) && $i <= ($pos_pics + $end_thumbnails))
					{
						$Template->assign_block_vars('pics.pics_max.list_preview_pics', array(
							'PICS' => $pics
						));
					}
					$i++;
				}
			}
		}
		else
		{
			$j = 0;
			$result = $Sql->query_while("SELECT g.id, g.idcat, g.name, g.path, g.timestamp, g.aprob, g.width, g.height, m.display_name, m.user_id, m.level, m.user_groups
			FROM " . PREFIX . "gallery g
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
			WHERE g.idcat = '" . $idcat . "'
			ORDER BY g.timestamp
			" . $Sql->limit($pagination->get_display_from(), $config->get_pics_number_per_page()));
			while ($row = $Sql->fetch_assoc($result))
			{
				//Si la miniature n'existe pas (cache vidé) on regénère la miniature à partir de l'image en taille réelle.
				if (!file_exists('pics/thumbnails/' . $row['path']))
					$Gallery->Resize_pics('pics/' . $row['path']); //Redimensionnement + création miniature

				$name_cut = (strlen(TextHelper::html_entity_decode($row['name'])) > 22) ? TextHelper::htmlentities(substr(TextHelper::html_entity_decode($row['name']), 0, 22)) . '...' : $row['name'];

				//On reccourci le nom s'il est trop long pour éviter de déformer l'administration.
				$name = TextHelper::html_entity_decode($row['name']);
				$name = strlen($name) > 20 ? substr($name, 0, 20) . '...' : $name;

				//On genère le tableau pour x colonnes
				$tr_start = is_int($j / $nbr_column_pics) ? '<tr>' : '';
				$j++;
				$tr_end = is_int($j / $nbr_column_pics) ? '</tr>' : '';

				//Affichage de l'image en grand.
				if ($config->get_pics_enlargement_mode() == GalleryConfig::FULL_SCREEN) //Ouverture en popup plein écran.
					$display_link = HOST . DIR . '/gallery/show_pics' . url('.php?id=' . $row['id'] . '&amp;cat=' . $row['idcat']);
				elseif ($config->get_pics_enlargement_mode() == GalleryConfig::POPUP) //Ouverture en popup simple.
					$display_link = 'javascript:display_pics_popup(\'' . HOST . DIR . '/gallery/show_pics' . url('.php?id=' . $row['id'] . '&amp;cat=' . $row['idcat']) . '\', \'' . $row['width'] . '\', \'' . $row['height'] . '\')';
				elseif ($config->get_pics_enlargement_mode() == GalleryConfig::RESIZE) //Ouverture en agrandissement simple.
					$display_link = 'javascript:display_pics(' . $row['id'] . ', \'' . HOST . DIR . '/gallery/show_pics' . url('.php?id=' . $row['id'] . '&amp;cat=' . $row['idcat']) . '\', 0)';
				else //Ouverture nouvelle page.
					$display_link = 'admin_gallery.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'] . '#pics_max';

				//Liste des catégories.
				$cat_list = '';
				foreach ($array_cat_list as $key_cat => $option_value)
					$cat_list .= ($key_cat == $row['idcat']) ? sprintf($option_value, 'selected="selected"') : sprintf($option_value, '');
				
				$group_color = User::get_group_color($row['groups'], $row['level']);
				
				$Template->assign_block_vars('pics.list', array(
					'C_APPROVED' => $row['aprob'],
					'ID' => $row['id'],
					'IMG' => '<img src="pics/thumbnails/' . $row['path'] . '" alt="' . $name . '" />',
					'PATH' => $row['path'],
					'NAME' => stripslashes($name_cut),
					'TITLE' => stripslashes($row['name']),
					'RENAME_FILE' => '<span id="fihref' . $row['id'] . '"><a href="javascript:display_rename_file(\'' . $row['id'] . '\', \'' . addslashes($row['name']) . '\', \'' . addslashes($name_cut) . '\');" title="' . $LANG['edit'] . '" class="fa fa-edit"></a></span>',
					'TR_START' => $tr_start,
					'TR_END' => $tr_end,
					'CAT' => $cat_list,
					'U_DISPLAY' => $display_link,
					'U_POSTOR' => $LANG['by'] . ' <a class="' . UserService::get_level_class($row['level']) . '"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . ' href="'. UserUrlBuilder::profile($row['user_id'])->rel() .'">' . $row['login'] . '</a>',
				));
			}
			$result->dispose();

			//Création des cellules du tableau si besoin est.
			while (!is_int($j/$nbr_column_pics))
			{
				$j++;
				$Template->assign_block_vars('pics.end_td_pics', array(
					'TD_END' => '<td style="width:' . $column_width_pics . '%">&nbsp;</td>',
					'TR_END' => (is_int($j/$nbr_column_pics)) ? '</tr>' : ''
				));
			}
		}
	}

	$Template->pparse('admin_gallery_management');
}

require_once('../admin/admin_footer.php');

?>