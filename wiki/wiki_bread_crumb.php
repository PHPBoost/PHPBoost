<?php
/*##################################################
 *                              wiki_bread_crumb.php
 *                            -------------------
 *   begin                : May 5, 2007
 *   copyright            : (C) 2007 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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

if (defined('PHPBOOST') !== true)	exit;

require_once(PATH_TO_ROOT .'/wiki/wiki_auth.php');
$config = WikiConfig::load();
$categories = WikiCategoriesCache::load()->get_categories();

switch ($bread_crumb_key)
{
	case 'wiki':
		if (!empty($id_contents))
			$Bread_crumb->add($LANG['wiki_history'], '');
		if (!empty($article_infos['title']))
		{
			if ($article_infos['is_cat'] == 0)
				$Bread_crumb->add(stripslashes($article_infos['title']), url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title']));
			$id_cat = (int)$article_infos['id_cat'];
		}
		if (!empty($id_cat)  && is_array($categories)) //Catégories infinies
		{
			$id = $id_cat; //Premier id
			do
			{
				$Bread_crumb->add(stripslashes($categories[$id]['title']), url('wiki.php?title=' . $categories[$id]['encoded_title'], $categories[$id]['encoded_title']));
				$id = (int)$categories[$id]['id_parent'];
			}	
			while ($id > 0);
		}
		$Bread_crumb->add(($config->get_wiki_name() ? $config->get_wiki_name() : $LANG['wiki']), url('wiki.php'));
		$Bread_crumb->reverse();
		break;
	case 'wiki_history':
		$Bread_crumb->add(($config->get_wiki_name() ? $config->get_wiki_name() : $LANG['wiki']),url('wiki.php'));
		$Bread_crumb->add($LANG['wiki_history'], url('history.php'));
			if (!empty($id_article))
				$Bread_crumb->add(stripslashes($article_infos['title']), url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title']));
		break;
	case 'wiki_history_article':
		$Bread_crumb->add($LANG['wiki_history'], url('history.php?id=' . $id_article));
		$Bread_crumb->add(stripslashes($article_infos['title']), url('wiki.php?title=' . $article_infos['encoded_title']), $article_infos['encoded_title']);

		$id_cat = (int)$article_infos['id_cat'];
		if (!empty($id_cat)  && is_array($categories)) //Catégories infinies
		{
			$id = $id_cat; //Premier id
			do
			{
				$Bread_crumb->add(stripslashes($categories[$id]['title']), url('wiki.php?title=' . $categories[$id]['encoded_title'], $categories[$id]['encoded_title']));
				$id = (int)$categories[$id]['id_parent'];
			}
			while ($id > 0);
		}
		$Bread_crumb->add(($config->get_wiki_name() ? $config->get_wiki_name() : $LANG['wiki']), url('wiki.php'));
		$Bread_crumb->reverse();
		break;
	case 'wiki_post':
		$Bread_crumb->add(($config->get_wiki_name() ? $config->get_wiki_name() : $LANG['wiki']), url('wiki.php'));
		$Bread_crumb->add($LANG['wiki_contribuate'], '');
		break;
	case 'wiki_property':
		if ($id_auth > 0)
			$Bread_crumb->add($LANG['wiki_auth_management'], url('property.php?auth=' . $article_infos['id']));
		elseif ($wiki_status > 0)
			$Bread_crumb->add($LANG['wiki_status_management'], url('property.php?status=' . $article_infos['id']));
		elseif ($move > 0)
			$Bread_crumb->add($LANG['wiki_moving_article'], url('property.php?move=' . $move));
		elseif ($rename > 0)
			$Bread_crumb->add($LANG['wiki_renaming_article'], url('property.php?rename=' . $rename));
		elseif ($redirect > 0)
			$Bread_crumb->add($LANG['wiki_redirections'], url('property.php?redirect=' . $redirect));
		elseif ($create_redirection > 0)
			$Bread_crumb->add($LANG['wiki_create_redirection'], url('property.php?create_redirection=' . $create_redirection));
		elseif (AppContext::get_request()->has_getparameter('i') && $idcom > 0)
			$Bread_crumb->add($LANG['wiki_article_com'], url('property.php?com=' . $idcom . '&amp;i=0'));
		elseif ($del_article > 0)
			$Bread_crumb->add($LANG['wiki_remove_cat'], url('property.php?del=' . $del_article));
			
		if (isset($article_infos) && $article_infos['is_cat'] == 0)
			$Bread_crumb->add(stripslashes($article_infos['title']), url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title']));
			
		$id_cat = !empty($article_infos['id_cat']) ? (int)$article_infos['id_cat'] : 0;
		if ($id_cat > 0 && is_array($categories)) //Catégories infinies
		{
			$id = $id_cat;
			do
			{
				$Bread_crumb->add(stripslashes($categories[$id]['title']), url('wiki.php?title=' . $categories[$id]['encoded_title'], $categories[$id]['encoded_title']));
				$id = (int)$categories[$id]['id_parent'];
			}
			while ($id > 0);
		}
		$Bread_crumb->add(($config->get_wiki_name() ? $config->get_wiki_name() : $LANG['wiki']), url('wiki.php'));
		$Bread_crumb->reverse();
		break;
	case 'wiki_favorites':
		$Bread_crumb->add(($config->get_wiki_name() ? $config->get_wiki_name() : $LANG['wiki']), url('wiki.php'));
		$Bread_crumb->add($LANG['wiki_favorites'], url('favorites.php'));
		break;
	case 'wiki_explorer':
		$Bread_crumb->add(($config->get_wiki_name() ? $config->get_wiki_name() : $LANG['wiki']), url('wiki.php'));
		$Bread_crumb->add($LANG['wiki_explorer'], url('explorer.php'));
		break;
	default:
		$Bread_crumb->add(($config->get_wiki_name() ? $config->get_wiki_name() : $LANG['wiki']), url('wiki.php'));
		break;
}
	
?>