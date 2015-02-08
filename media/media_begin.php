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

load_module_lang('media');

$id_media = retrieve(GET, 'id', 0);
$id_cat = retrieve(GET, 'cat', 0);

require_once('media_constant.php');

function bread_crumb($id)
{
	global $Bread_crumb;
	$Bread_crumb->add(LangLoader::get_message('module_title', 'common', 'media'), MediaUrlBuilder::home());
	
	$categories = array_reverse(MediaService::get_categories_manager()->get_parents($id, true));
	foreach ($categories as $category)
	{
		if ($category->get_id() != Category::ROOT_CATEGORY)
			$Bread_crumb->add($category->get_name(), url('media.php?cat=' . $category->get_id(), 'media-0-' . $category->get_id() . '+' . $category->get_rewrited_name() . '.php'));
	}
}

?>