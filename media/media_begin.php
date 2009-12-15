<?php
/*##################################################
 *                              media_begin.php
 *                            -------------------
 *   begin               	: October 20, 2008
 *   copyright        		: (C) 2007 Geoffrey ROGUELON
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

if (defined('PHPBOOST') !== true)
{
	exit;
}

$Cache->load('media');
load_module_lang('media');

require_once('media_constant.php');

define('FEED_URL', '/syndication.php?m=media');

function bread_crumb($id)
{
	global $Bread_crumb, $MEDIA_CATS;

	$id_parent = $MEDIA_CATS[$id]['id_parent'];
	$Bread_crumb->add($MEDIA_CATS[$id]['name'], url('media.php?cat=' . $id, 'media-0-' . $id . '+' . url_encode_rewrite($MEDIA_CATS[$id]['name']) . '.php'));

	while ($id_parent >= 0)
	{
		$Bread_crumb->add($MEDIA_CATS[$id_parent]['name'], url('media.php?cat=' . $id_parent, 'media-0-' . $id_parent . '+' . url_encode_rewrite($MEDIA_CATS[$id_parent]['name']) . '.php'));
		$id_parent = $MEDIA_CATS[$id_parent]['id_parent'];
	}

	$Bread_crumb->reverse();
}

?>