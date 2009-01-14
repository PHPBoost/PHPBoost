<?php
/*##################################################
 *                              online_mini.php
 *                            -------------------
 *   begin                : July 20, 2005
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

function media_mini($position)
{
	global $LANG, $Cache, $Sql, $User, $CONFIG, $MEDIA_CONFIG, $MEDIA_CATS, $MEDIA_LANG;

	//Chargement de la langue du module.
	require_once('media_constant.php');
	require_once('media_cats.class.php');
    load_module_lang('media');
    $Cache->load('media');

    $tpl = new Template('media/media_mini.tpl');
    import('core/menu_service');
    MenuService::assign_positions_conditions($tpl, $position);
	// Building Categories to look in
	$cats = new MediaCats();
	$children_cats = array();
	$cats->build_children_id_list(0, $children_cats, RECURSIVE_EXPLORATION, ADD_THIS_CATEGORY_IN_LIST);

    $i = 0;
    $result = $Sql->query_while("SELECT id, idcat, name	FROM ".PREFIX."media WHERE infos = '" . MEDIA_STATUS_APROBED . "' AND idcat IN (" . implode($children_cats, ','). " ) ORDER BY timestamp DESC" . $Sql->limit(0, NUM_MEDIA), __LINE__, __FILE__);

    while ($row = $Sql->fetch_assoc($result))
    {
    	$tpl->assign_block_vars('last_media', array(
			'U_MEDIA' => url('../media/media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $row['idcat'] . '+' . url_encode_rewrite($row['name']) . '.php'),
			'U_CAT' => url('../media/media.php?cat=' . $row['idcat'], 'media-0-' . $row['idcat'] . '+' . url_encode_rewrite($MEDIA_CATS[$row['idcat']]['name']) . '.php'),
			'MEDIA' => $row['name'],
			'CAT' => $MEDIA_CATS[$row['idcat']]['name']
		));

		$i++;
	}

    $Sql->query_close($result);

    if ($i == 0)
    {
    	$tpl->assign_vars(array('L_NONE_MEDIA' => $MEDIA_LANG['none_mini_media']));
    }

    $tpl->assign_vars(array(
    	'L_MEDIA' => $MEDIA_LANG['xml_media_desc'],
    	'L_IN' => $MEDIA_LANG['in']
    ));

    return $tpl->parse(TEMPLATE_STRING_MODE);
}
?>