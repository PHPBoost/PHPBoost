<?php
/*##################################################
 *                             xmlhttprequest_cats.php
 *                            -------------------
 *   begin               	: October 20, 2008
 *   copyright        	: (C) 2007 Geoffrey ROGUELON
 *   email               	: liaght@gmail.com
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

define('NO_SESSION_LOCATION', true);

require_once('../kernel/begin.php');
require_once('../kernel/header_no_display.php');

if ($User->check_level(ADMIN_LEVEL)) //Admin
{
	$media_categories = new MediaCats();
	$id_up = retrieve(GET, 'id_up', 0);
	$id_down = retrieve(GET, 'id_down', 0);
	$id_show = retrieve(GET, 'show', 0);
	$id_hide = retrieve(GET, 'hide', 0);
	$cat_to_del = retrieve(GET, 'del', 0);
	$result = false;

	if ($id_up > 0)
	{
		$result = $media_categories->move($id_up, MOVE_CATEGORY_UP);
	}
	elseif ($id_down > 0)
	{
		$result = $media_categories->move($id_down, MOVE_CATEGORY_DOWN);
	}
	elseif ($id_show > 0)
	{
		$result = $media_categories->change_visibility($id_show, CAT_VISIBLE, LOAD_CACHE);
	}
	elseif ($id_hide > 0)
	{
		$result = $media_categories->change_visibility($id_hide, CAT_UNVISIBLE, LOAD_CACHE);
	}

	//Operation was successfully
	if ($result)
	{
		$cat_config = array(
			'xmlhttprequest_file' => 'xmlhttprequest_cats.php',
			'administration_file_name' => 'admin_media_cats.php',
			'url' => array(
				'unrewrited' => 'media.php?id=%d',
				'rewrited' => 'media-%d+%s.php'
			)
		);

		$media_categories->set_display_config($cat_config);
		$Cache->load('media', RELOAD_CACHE);

		echo $media_categories->build_administration_interface(AJAX_MODE);
	}
}

require_once('../kernel/footer_no_display.php');

?>
