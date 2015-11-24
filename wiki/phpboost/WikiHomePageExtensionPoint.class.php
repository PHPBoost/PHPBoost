<?php
/*##################################################
 *                     WikiHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : January 27, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
		
		$config = WikiConfig::load();
		
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
					'ARTICLE' => $row['title'],
					'TR' => ($i > 0 && ($i%2 == 0)) ? '</tr><tr>' : '',
					'U_ARTICLE' => url('wiki.php?title=' . $row['encoded_title'], $row['encoded_title'])
				));
				$i++;
			}
			$result->dispose();

			if ($i == 0)
			{
				$tpl->put_all(array(
					'L_NO_ARTICLE' => '<td class="center" colspan="2">' . $LANG['wiki_no_article'] . '</td>',
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