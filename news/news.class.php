<?php
/*##################################################
 *                              news.class.php
 *                            -------------------
 *   begin                : August 11, 2009
 *   copyright            : (C) 2009 Roguelon Geoffrey
 *   email                : liaght@gmail.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

class news
{
	// Genrerate the bread crumb from a category.
	function bread_crumb($id = 0)
	{
		global $Bread_crumb, $User, $NEWS_LANG, $NEWS_CAT;
		
		require_once('news_cats.class.php');
		$news_categories = new NewsCats();

		while ($id > 0)
		{
			if ($User->check_auth($news_categories->auth($id), AUTH_NEWS_READ))
			$Bread_crumb->add($NEWS_CAT[$id]['name'], url('news.php?cat=' . $id, 'news-' . $id . '+' . url_encode_rewrite($NEWS_CAT[$id]['name']) . '.php'));
			$id = $NEWS_CAT[$id]['id_parent'];
		}

		$Bread_crumb->add($NEWS_LANG['news'], url('news.php'));

		$Bread_crumb->reverse();
	}
}
?>