<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 30
 * @since       PHPBoost 1.6 - 2007 05 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

if (defined('PHPBOOST') !== true)	exit;

require_once(PATH_TO_ROOT .'/wiki/wiki_auth.php');
$config = WikiConfig::load();
$categories = WikiCategoriesCache::load()->get_categories();

switch ($bread_crumb_key)
{
	case 'wiki':
		if (!empty($id_contents))
			$Bread_crumb->add($lang['wiki.history'], '');
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
		$Bread_crumb->add(($config->get_wiki_name() ? $config->get_wiki_name() : $lang['wiki.module.title']), url('wiki.php'));
		$Bread_crumb->reverse();
		break;
	case 'wiki_history':
		$Bread_crumb->add(($config->get_wiki_name() ? $config->get_wiki_name() : $lang['wiki.module.title']),url('wiki.php'));
		$Bread_crumb->add($lang['wiki.history'], url('history.php'));
			if (!empty($id_article))
				$Bread_crumb->add(stripslashes($article_infos['title']), url('wiki.php?title=' . $article_infos['encoded_title'], $article_infos['encoded_title']));
		break;
	case 'wiki_history_article':
		$Bread_crumb->add($lang['wiki.history'], url('history.php?id=' . $id_article));
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
		$Bread_crumb->add(($config->get_wiki_name() ? $config->get_wiki_name() : $lang['wiki.module.title']), url('wiki.php'));
		$Bread_crumb->reverse();
		break;
	case 'wiki_post':
		$Bread_crumb->add(($config->get_wiki_name() ? $config->get_wiki_name() : $lang['wiki.module.title']), url('wiki.php'));
		$Bread_crumb->add($lang['wiki.contribution'], '');
		break;
	case 'wiki_property':
		if ($id_auth > 0)
			$Bread_crumb->add($lang['wiki.authorizations.management'], url('property.php?auth=' . $article_infos['id']));
		elseif ($wiki_status > 0)
			$Bread_crumb->add($lang['wiki.status.management'], url('property.php?status=' . $article_infos['id']));
		elseif ($move > 0)
			$Bread_crumb->add($lang['wiki.moving.management'], url('property.php?move=' . $move));
		elseif ($rename > 0)
			$Bread_crumb->add($lang['wiki.renaming.management'], url('property.php?rename=' . $rename));
		elseif ($redirect > 0)
			$Bread_crumb->add($lang['wiki.redirections'], url('property.php?redirect=' . $redirect));
		elseif ($create_redirection > 0)
			$Bread_crumb->add($lang['wiki.create.redirection'], url('property.php?create_redirection=' . $create_redirection));
		elseif (AppContext::get_request()->has_getparameter('i') && $idcom > 0)
			$Bread_crumb->add($lang['wiki.comments'], url('property.php?com=' . $idcom . '&amp;i=0'));
		elseif ($del_article > 0)
			$Bread_crumb->add($lang['wiki.remove.category'], url('property.php?del=' . $del_article));

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
		$Bread_crumb->add(($config->get_wiki_name() ? $config->get_wiki_name() : $lang['wiki.module.title']), url('wiki.php'));
		$Bread_crumb->reverse();
		break;
	case 'wiki_favorites':
		$Bread_crumb->add(($config->get_wiki_name() ? $config->get_wiki_name() : $lang['wiki.module.title']), url('wiki.php'));
		$Bread_crumb->add($lang['wiki.tracked.items'], url('favorites.php'));
		break;
	case 'wiki_explorer':
		$Bread_crumb->add(($config->get_wiki_name() ? $config->get_wiki_name() : $lang['wiki.module.title']), url('wiki.php'));
		$Bread_crumb->add($lang['wiki.explorer'], url('explorer.php'));
		break;
	default:
		$Bread_crumb->add(($config->get_wiki_name() ? $config->get_wiki_name() : $lang['wiki.module.title']), url('wiki.php'));
		break;
}

?>
