<?php
/*##################################################
 *                              media_mini.php
 *                            -------------------
 *   begin               	: October 20, 2008
 *   copyright        	: (C) 2007 Geoffrey ROGUELON
 *   email               	: liaght@gmail.com
 *
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

function media_mini()
{
	global $LANG, $Cache, $Sql, $User, $CONFIG, $MEDIA_CONFIG, $MEDIA_CATS, $MEDIA_MINI, $MEDIA_LANG;

	//Chargement de la langue du module.
	require_once('media_constant.php');
    load_module_lang('media');
	$Cache->load('media');

    $tpl = new Template('media/media_mini.tpl');

	if (!empty($MEDIA_MINI))
	{
		foreach ($MEDIA_MINI as $last_files)	
		{
			$tpl->assign_block_vars('last_media', array(
				'U_MEDIA' => '../media/' . url('media.php?id=' . $last_files['id'], 'media-' . $last_files['id'] . '-' . $last_files['idcat'] . '+' . url_encode_rewrite($last_files['name']) . '.php'),
				'U_CAT' => url('../media/media.php?cat=' . $last_files['idcat'], 'media-0-' . $last_files['idcat'] . '+' . url_encode_rewrite($MEDIA_CATS[$last_files['idcat']]['name']) . '.php'),
				'MEDIA' => $last_files['name'],
				'CAT' => $MEDIA_CATS[$last_files['idcat']]['name']
			));
		}
	}
	else
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