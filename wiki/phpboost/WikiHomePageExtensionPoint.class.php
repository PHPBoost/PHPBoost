<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 09 19
 * @since       PHPBoost 3.0 - 2012 01 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WikiHomePageExtensionPoint implements HomePageExtensionPoint
{
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), $this->get_view());
	}

	private function get_title()
	{
		return LangLoader::get_message('wiki.module.title', 'common', 'wiki');
	}

	private function get_view()
	{
		$lang = LangLoader::get_all_langs('wiki');

		global $Bread_crumb, $encoded_title, $id_article, $article_infos, $id_cat;

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

		$view = new FileTemplate('wiki/index.tpl');
		$view->add_lang($lang);

		// If it's active, display last items
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

			$view->assign_block_vars('last_articles', array(
				'C_ITEMS' => $result->get_rows_count() > 0,
			));

			$i = 0;
			while ($row = $result->fetch())
			{
				$view->assign_block_vars('last_articles.list', array(
					'ITEM_TITLE' => stripslashes($row['title']),
					'U_ITEM'     => url('wiki.php?title=' . $row['encoded_title'], $row['encoded_title'])
				));
				$i++;
			}
			$result->dispose();
		}

		// If it's active, display categories at the root
		if ($config->are_categories_displayed_on_index())
		{
			$view->assign_block_vars('cat_list', array(
				'C_CATEGORIES' => WikiCategoriesCache::load()->get_number_categories() > 0
			));
			$i = 0;
			foreach (WikiCategoriesCache::load()->get_categories() as $id => $cat)
			{
				if ($cat['id_parent'] == 0)
				{
					$view->assign_block_vars('cat_list.list', array(
						'CATEGORY_NAME' => stripslashes($cat['title']),
						'U_CATEGORY'    => url('wiki.php?title=' . $cat['encoded_title'], $cat['encoded_title'])
					));
					$i++;
				}
			}
		}

		$view->put_all(array(
			'C_HAS_ROOT_DESCRIPTION' => !empty($config->get_index_text()),
			'TITLE'      	   => $config->get_wiki_name() ? $config->get_wiki_name() : $lang['wiki.module.title'],
			'ROOT_DESCRIPTION' => FormatingHelper::second_parse(wiki_no_rewrite($config->get_index_text())),
			'U_EXPLORER' 	   => url('explorer.php'),
		));

		$page_type = 'index';
		include(PATH_TO_ROOT . '/wiki/wiki_tools.php');
		$view->put('WIKI_TOOLS', $tools_view);

		return new StringTemplate($view->render());
	}
}
?>
