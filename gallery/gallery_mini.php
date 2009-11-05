<?php
/*##################################################
 *                              gallery_mini.php
 *                            -------------------
 *   begin                : August 03, 2005
 *   copyright          : (C) 2005 Viarre Régis
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

if (defined('PHPBOOST') !== true) exit;

function gallery_mini($position, $block)
{
    global $Cache, $User, $CAT_GALLERY, $CONFIG_GALLERY, $LANG, $_array_random_pics, $Sql;
    $tpl = new Template('gallery/gallery_mini.tpl');

    MenuService::assign_positions_conditions($tpl, $block);

    //Chargement de la langue du module.
    load_module_lang('gallery');
    $Cache->load('gallery'); //Requête des configuration générales (gallery), $CONFIG_ALBUM variable globale.

    //Affichage des miniatures disponibles
    $i = 0;
    $array_pics_mini = 'var array_pics_mini = new Array();' . "\n";
    list($nbr_pics, $sum_height, $sum_width, $scoll_mode, $height_max, $width_max) = array(0, 0, 0, 0, 142, 142);
    if (isset($_array_random_pics) && $_array_random_pics !== array())
    {
    	if (!defined('READ_CAT_GALLERY'))
    		define('READ_CAT_GALLERY', 0x01);

    	$gallery_mini = array();
    	shuffle($_array_random_pics); //On mélange les éléments du tableau.

    	//Autorisations de la racine.
    	$CAT_GALLERY[0]['auth'] = $CONFIG_GALLERY['auth_root'];
    	//Vérification des autorisations.
    	$break = 0;
    	foreach ($_array_random_pics as $array_pics_info)
    	{
    		if ($User->check_auth($CAT_GALLERY[$array_pics_info['idcat']]['auth'], READ_CAT_GALLERY))
    		{
    			$gallery_mini[] = $array_pics_info;
    			$break++;
    		}
    		if ($break == $CONFIG_GALLERY['nbr_pics_mini'])
    			break;
    	}

    	//Aucune photo ne correspond, on fait une requête pour vérifier.
    	if (count($gallery_mini) == 0)
    	{
			$_array_random_pics = array();
			$result = $Sql->query_while("SELECT g.id, g.name, g.path, g.width, g.height, g.idcat, gc.auth
    		FROM " . PREFIX . "gallery g
    		LEFT JOIN " . PREFIX . "gallery_cats gc on gc.id = g.idcat
    		WHERE g.aprob = 1 AND gc.aprob = 1
    		ORDER BY RAND()
    		" . $Sql->limit(0, $CONFIG_GALLERY['nbr_pics_mini']), __LINE__, __FILE__);
    		while($row = $Sql->fetch_assoc($result))
			{
				$_array_random_pics[] = $row;
			}

    		//Vérification des autorisations.
    		$break = 0;
    		foreach ($_array_random_pics as $key => $array_pics_info)
    		{
				if ($User->check_auth($CAT_GALLERY[$array_pics_info['idcat']]['auth'], READ_CAT_GALLERY))
    			{
    				$gallery_mini[] = $array_pics_info;
    				$break++;
    			}
    			if ($break == $CONFIG_GALLERY['nbr_pics_mini'])
    				break;
    		}
    	}

    	switch ($CONFIG_GALLERY['scroll_type'])
    	{
			case 0:
        	$tpl->assign_vars(array(
    			'C_FADE' => true
    		));
    		break;
    		case 1:
    		$tpl->assign_vars(array(
    			'C_VERTICAL_SCROLL' => true
    		));
    		break;
    		case 2:
    		$tpl->assign_vars(array(
    			'C_HORIZONTAL_SCROLL' => true
    		));
    		break;
			case 3:
			$tpl->assign_vars(array(
    			'C_STATIC' => true
    		));
			break;
    	}

    	$Gallery = new Gallery();

    	foreach ($gallery_mini as $key => $row)
    	{
    		//Si la miniature n'existe pas (cache vidé) on regénère la miniature à partir de l'image en taille réelle.
    		if (!is_file(PATH_TO_ROOT . '/gallery/pics/thumbnails/' . $row['path']))
    			$Gallery->Resize_pics(PATH_TO_ROOT . '/gallery/pics/' . $row['path']); //Redimensionnement + création miniature

    		// On recupère la hauteur et la largeur de l'image.
    		if ($row['width'] == 0 || $row['height'] == 0)
    			list($row['width'], $row['height']) = @getimagesize(PATH_TO_ROOT . '/gallery/pics/thumbnails/' . $row['path']);
    		if ($row['width'] == 0 || $row['height'] == 0)
    			list($row['width'], $row['height']) = array(142, 142);

    		$tpl->assign_block_vars('pics_mini', array(
    			'ID' => $i,
    			'PICS' => TPL_PATH_TO_ROOT . '/gallery/pics/thumbnails/' . $row['path'],
    			'NAME' => strprotect($row['name'],HTML_PROTECT,ADDSLASHES_FORCE),
    			'HEIGHT' => $row['height'],
    			'WIDTH' => $row['width'],
    			'U_PICS' => TPL_PATH_TO_ROOT . '/gallery/gallery' . url('.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'], '-' . $row['idcat'] . '-' . $row['id'] . '.php')
    		));

    		$sum_height += $row['height'] + 5;
    		$sum_width += $row['width'] + 5;
    		$i++;

			if ($CONFIG_GALLERY['scroll_type'] == 3)
				break;
    	}
    }

    $tpl->assign_vars(array(
    	'SID' => SID,
    	'ARRAY_PICS' => $array_pics_mini,
    	'HEIGHT_DIV' => $CONFIG_GALLERY['height'],
    	'SUM_HEIGHT' => $sum_height + 10,
    	'HIDDEN_HEIGHT' => $CONFIG_GALLERY['height'] + 10,
    	'WIDTH_DIV' => $CONFIG_GALLERY['width'],
    	'SUM_WIDTH' => $sum_width + 30,
    	'HIDDEN_WIDTH' => ($CONFIG_GALLERY['width'] * 3) + 30,
    	'SCROLL_DELAY' => 0.2 * (11 - $CONFIG_GALLERY['speed_mini_pics']),
    	'L_RANDOM_PICS' => $LANG['random_img'],
    	'L_NO_RANDOM_PICS' => ($i == 0) ? '<br /><span class="text_small"><em>' . $LANG['no_random_img']  . '</em></span><br />' : '',
    	'L_GALLERY' => $LANG['gallery']
    ));
    return $tpl->parse(Template::TEMPLATE_PARSER_STRING);
}
?>