<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 08 24
 * @since       PHPBoost 3.0 - 2012 01 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class WikiHomePageExtensionPoint implements HomePageExtensionPoint
{
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), $this->get_view());
	}

	private function get_title()
	{
		global $LANG;

		load_module_lang('wiki');

		return $LANG['wiki'];
	}

	private function get_view()
	{
		global $Bread_crumb, $LANG, $encoded_title, $id_article, $article_infos, $id_cat;

		load_module_lang('wiki');
		include_once(PATH_TO_ROOT . '/wiki/wiki_functions.php');
		$bread_crumb_key = 'wiki';
		require_once(PATH_TO_ROOT . '/wiki/wiki_bread_crumb.php');
		require_once(PATH_TO_ROOT . '/wiki/wiki_auth.php');

		$config = WikiConfig::load();

		if (!AppContext::get_current_user()->check_auth($config->get_authorizations(), WIKI_READ))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}

		$tpl = new FileTemplate('wiki/index.tpl');

		if ($config->get_number_articles_on_index() > 1)
		{
			$result = PersistenceContext::get_querier()->select("SELECT a.title, a.encoded_title, a.id
			FROM " . PREFIX . "wiki_articles a
			LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id_contents
			WHERE a.redirect = 0
			ORDER BY c.timestamp DESC
			LIMIT :number_articles_on_index OFFSET 0", array(
				'number_articles_on_index' => $config->get_number_articles_on_index()
			));

			$tpl->assign_block_vars('last_articles', array(
				'C_ARTICLES' => $result->get_rows_count(),
				'L_ARTICLES' => $LANG['wiki_last_articles_list'],
			));

			$i = 0;
			while ($row = $result->fetch())
			{
				$tpl->assign_block_vars('last_articles.list', array(
					'ARTICLE' => stripslashes($row['title']),
					'U_ARTICLE' => url('wiki.php?title=' . $row['encoded_title'], $row['encoded_title'])
				));
				$i++;
			}
			$result->dispose();

			if ($i == 0)
			{
				$tpl->put_all(array(
					'L_NO_ARTICLE' => $LANG['wiki_no_article'],
				));
			}
		}
		//Affichage de toutes les catégories si c'est activé
		if ($config->are_categories_displayed_on_index())
		{
			$tpl->assign_block_vars('cat_list', array(
			'L_CATS' => $LANG['wiki_cats_list']
			));
			$i = 0;
			foreach (WikiCategoriesCache::load()->get_categories() as $id => $cat)
			{
				//Si c'est une catégorie mère
				if ($cat['id_parent'] == 0)
				{
					$tpl->assign_block_vars('cat_list.list', array(
						'CAT' => stripslashes($cat['title']),
						'U_CAT' => url('wiki.php?title=' . $cat['encoded_title'], $cat['encoded_title'])
					));
					$i++;
				}
			}
			if ($i == 0)
			$tpl->put_all(array(
				'L_NO_CAT' => $LANG['wiki_no_cat'],
			));
		}

		$tpl->put_all(array(
			'TITLE' => $config->get_wiki_name() ? $config->get_wiki_name() : $LANG['wiki'],
			'INDEX_TEXT' => $config->get_index_text() ? FormatingHelper::second_parse(wiki_no_rewrite($config->get_index_text())) : $LANG['wiki_empty_index'],
			'L_EXPLORER' => $LANG['wiki_explorer'],
			'U_EXPLORER' => url('explorer.php'),
		));

		$page_type = 'index';
		include(PATH_TO_ROOT . '/wiki/wiki_tools.php');
		$tpl->put('wiki_tools', $tools_tpl);

		return new StringTemplate($tpl->render());
	}
}
?>
