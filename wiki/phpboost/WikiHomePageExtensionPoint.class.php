<?php
/*##################################################
 *                     WikiHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : January 27, 2012
 *   copyright            : (C) 2012 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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
	private $sql_querier;

    public function __construct()
    {
        $this->sql_querier = PersistenceContext::get_sql();
	}
	
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
		global $User, $Template, $Cache, $Bread_crumb, $_WIKI_CONFIG, $_WIKI_CATS, $LANG;

		load_module_lang('wiki');
		include_once(PATH_TO_ROOT . '/wiki/wiki_functions.php');
		$bread_crumb_key = 'wiki';
		require_once(PATH_TO_ROOT . '/wiki/wiki_bread_crumb.php');

		$Template->set_filenames(array(
			'wiki'=> 'wiki/wiki.tpl',
			'index'=> 'wiki/index.tpl'
		));

		if ($_WIKI_CONFIG['last_articles'] > 1)
		{
			$result = $this->sql_querier->query_while("SELECT a.title, a.encoded_title, a.id
			FROM " . PREFIX . "wiki_articles a
			LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id_contents
			WHERE a.redirect = 0
			ORDER BY c.timestamp DESC
			LIMIT 0, " . $_WIKI_CONFIG['last_articles'], __LINE__, __FILE__);
			$articles_number = $this->sql_querier->num_rows($result, "SELECT COUNT(*) FROM " . PREFIX . "wiki_articles WHERE encoded_title = '" . $encoded_title . "'", __LINE__, __FILE__);

			$Template->assign_block_vars('last_articles', array(
				'L_ARTICLES' => $LANG['wiki_last_articles_list'],
				'RSS' => $articles_number > 0 ? '<a href="' . SyndicationUrlBuilder::rss('wiki')->rel() .'"><img src="../templates/' . get_utheme() . '/images/rss.png" alt="RSS" /></a>' : ''
			));

			$i = 0;
			while ($row = $this->sql_querier->fetch_assoc($result))
			{
				$Template->assign_block_vars('last_articles.list', array(
					'ARTICLE' => $row['title'],
					'TR' => ($i > 0 && ($i%2 == 0)) ? '</tr><tr>' : '',
					'U_ARTICLE' => url('wiki.php?title=' . $row['encoded_title'], $row['encoded_title'])
				));
				$i++;
			}

			if ($articles_number == 0)
			{
				$Template->put_all(array(
					'L_NO_ARTICLE' => '<td style="text-align:center;" class="row2">' . $LANG['wiki_no_article'] . '</td>',
				));
			}
		}
		//Affichage de toutes les catégories si c'est activé
		if ($_WIKI_CONFIG['display_cats'] != 0)
		{
			$Template->assign_block_vars('cat_list', array(
			'L_CATS' => $LANG['wiki_cats_list']
			));
			$i = 0;
			foreach ($_WIKI_CATS as $id => $infos)
			{
				//Si c'est une catégorie mère
				if ($infos['id_parent'] == 0)
				{
					$Template->assign_block_vars('cat_list.list', array(
						'CAT' => $infos['name'],
						'U_CAT' => url('wiki.php?title=' . Url::encode_rewrite($infos['name']), Url::encode_rewrite($infos['name']))
					));
					$i++;
				}
			}
			if ($i == 0)
			$Template->put_all(array(
				'L_NO_CAT' => $LANG['wiki_no_cat'],
			));
		}

		$Template->put_all(array(
			'TITLE' => !empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki'],
			'INDEX_TEXT' => !empty($_WIKI_CONFIG['index_text']) ? FormatingHelper::second_parse(wiki_no_rewrite($_WIKI_CONFIG['index_text'])) : $LANG['wiki_empty_index'],
			'L_EXPLORER' => $LANG['wiki_explorer'],
			'U_EXPLORER' => url('explorer.php'),
		));

		$page_type = 'index';
		include(PATH_TO_ROOT . '/wiki/wiki_tools.php');

		return new StringTemplate($Template->pparse('index'));
	}
}
?>