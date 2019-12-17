<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 05 05
 * @since       PHPBoost 3.0 - 2012 02 09
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class PagesHomePageExtensionPoint implements HomePageExtensionPoint
{
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), $this->get_view());
	}

	private function get_title()
	{
		global $LANG;

		load_module_lang('pages');

		return $LANG['pages'];
	}

	private function get_view()
	{
		global $Bread_crumb, $LANG, $pages;

		$pages_config = PagesConfig::load();

		//Configuration des authorisations
		$config_authorizations = $pages_config->get_authorizations();

		require_once(PATH_TO_ROOT . '/pages/pages_begin.php');

		$tpl = new FileTemplate('pages/index.tpl');

		$num_pages = PersistenceContext::get_querier()->count(PREFIX . "pages", 'WHERE redirect = 0');
		$num_coms = (int)CommentsService::get_number_and_lang_comments('pages', $pages['id']);

		$tpl->put_all(array(
			'NUM_PAGES' => sprintf($LANG['pages_num_pages'], $num_pages),
			'NUM_COMS' => sprintf($LANG['pages_num_coms'], $num_coms, ($num_pages > 0 ? $num_coms / $num_pages : 0)),
			'L_EXPLAIN_PAGES' => $LANG['pages_explain'],
			'L_STATS' => $LANG['pages_stats']
		));

		//Liste des dossiers de la racine
		$root = '';
		foreach (PagesCategoriesCache::load()->get_categories() as $key => $cat)
		{
			if ($cat['id_parent'] == 0)
			{
				//Autorisation particulière ?
				$special_auth = !empty($cat['auth']);
				//Vérification de l'autorisation d'éditer la page
				if (($special_auth && AppContext::get_current_user()->check_auth($cat['auth'], READ_PAGE)) || (!$special_auth && AppContext::get_current_user()->check_auth($config_authorizations, READ_PAGE)))
				{
					$root .= '<li><a href="javascript:open_cat(' . $key . '); show_pages_cat_contents(' . $cat['id_parent'] . ', 0);"><i class="fa fa-folder"></i>' . stripslashes($cat['title']) . '</a></li>';
				}
			}
		}
		//Liste des fichiers de la racine
		$result = PersistenceContext::get_querier()->select("SELECT title, id, encoded_title, auth
			FROM " . PREFIX . "pages
			WHERE id_cat = 0 AND is_cat = 0
			ORDER BY is_cat DESC, title ASC");
		while ($row = $result->fetch())
		{
			//Autorisation particulière ?
			$special_auth = !empty($row['auth']);
			$array_auth = TextHelper::unserialize($row['auth']);
			//Vérification de l'autorisation d'éditer la page
			if (($special_auth && AppContext::get_current_user()->check_auth($array_auth, READ_PAGE)) || (!$special_auth && AppContext::get_current_user()->check_auth($config_authorizations, READ_PAGE)))
			{
				$root .= '<li><a href="' . PagesUrlBuilder::get_link_item($row['encoded_title']) . '"><i class="fa fa-file"></i>' . stripslashes($row['title']) . '</a></li>';
			}
		}
		$result->dispose();

		$tpl->put_all(array(
			'TITLE' => $LANG['pages'],
			'L_ROOT' => $LANG['pages_root'],
			'ROOT_CONTENTS' => $root,
			'L_CATS' => $LANG['pages_cats_tree'],
			'L_EXPLORER' => $LANG['pages_explorer'],
			'SELECTED_CAT' => 0,
			'CAT_0' => 'selected',
			'CAT_LIST' => ''
		));

		$contents = '';
		$result = PersistenceContext::get_querier()->select("SELECT c.id, p.title, p.encoded_title
		FROM " . PREFIX . "pages_cats c
		LEFT JOIN " . PREFIX . "pages p ON p.id = c.id_page
		WHERE c.id_parent = 0
		ORDER BY p.title ASC");
		while ($row = $result->fetch())
		{
			$sub_cats_number = PersistenceContext::get_querier()->count(PREFIX . "pages_cats", 'WHERE id_parent=:id_parent', array('id_parent' => $row['id']));
			$tpl->assign_block_vars('list', array(
				'ID' => $row['id'],
				'TITLE' => stripslashes($row['title']),
				'C_SUB_CAT' => $sub_cats_number > 0
			));
		}
		$result->dispose();

		return $tpl;
	}
}
?>
