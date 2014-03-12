<?php
/*##################################################
 *                     PagesHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : February 09, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class PagesHomePageExtensionPoint implements HomePageExtensionPoint
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
		
		load_module_lang('pages');
		
		return $LANG['pages'];
	}
	
	private function get_view()
	{
		global $User, $Cache, $Bread_crumb, $_PAGES_CATS, $PAGES_LANG, $LANG, $Session, $pages;
		
		$pages_config = PagesConfig::load();
		
		//Configuration des authorisations
		$config_authorizations = $pages_config->get_authorizations();
		
		require_once(PATH_TO_ROOT . '/pages/pages_begin.php');

		$tpl = new FileTemplate('pages/index.tpl');
        
		$num_pages = $this->sql_querier->query("SELECT COUNT(*) FROM " . PREFIX . "pages WHERE redirect = '0'", __LINE__, __FILE__);
		$num_coms = CommentsService::get_number_and_lang_comments('pages', $pages['id']);
		
		$tpl->put_all(array(
			'NUM_PAGES' => sprintf($LANG['pages_num_pages'], $num_pages),
			'NUM_COMS' => sprintf($LANG['pages_num_coms'], $num_coms, ($num_pages > 0 ? $num_coms / $num_pages : 0)),
			'L_EXPLAIN_PAGES' => $LANG['pages_explain'],
			'L_STATS' => $LANG['pages_stats']
		));
		
		//Liste des dossiers de la racine
		$root = '';
		if (!empty($_PAGES_CATS)) {
			foreach ($_PAGES_CATS as $key => $value)
			{
				if ($value['id_parent'] == 0)
				{
					//Autorisation particulière ?
					$special_auth = !empty($value['auth']);
					//Vérification de l'autorisation d'éditer la page
					if (($special_auth && $User->check_auth($value['auth'], READ_PAGE)) || (!$special_auth && $User->check_auth($config_authorizations, READ_PAGE)))
					{
						$root .= '<li><a href="javascript:open_cat(' . $key . '); show_cat_contents(' . $value['id_parent'] . ', 0);"><i class="fa fa-folder"></i>' . $value['name'] . '</a></li>';
					}
				}
			}
		}
		//Liste des fichiers de la racine
		$result = $this->sql_querier->query_while("SELECT title, id, encoded_title, auth
			FROM " . PREFIX . "pages
			WHERE id_cat = 0 AND is_cat = 0
			ORDER BY is_cat DESC, title ASC", __LINE__, __FILE__);
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			//Autorisation particulière ?
			$special_auth = !empty($row['auth']);
			$array_auth = unserialize($row['auth']);
			//Vérification de l'autorisation d'éditer la page
			if (($special_auth && $User->check_auth($array_auth, READ_PAGE)) || (!$special_auth && $User->check_auth($config_authorizations, READ_PAGE)))
			{
				$root .= '<li><a href="' . PagesUrlBuilder::get_link_item($row['encoded_title']) . '"><i class="fa fa-file"></i>' . $row['title'] . '</a></li>';
			}
		}
		$this->sql_querier->query_close($result);

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
		$result = $this->sql_querier->query_while("SELECT c.id, p.title, p.encoded_title
		FROM " . PREFIX . "pages_cats c
		LEFT JOIN " . PREFIX . "pages p ON p.id = c.id_page
		WHERE c.id_parent = 0
		ORDER BY p.title ASC", __LINE__, __FILE__);
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			$sub_cats_number = $this->sql_querier->query("SELECT COUNT(*) FROM " . PREFIX . "pages_cats WHERE id_parent = '" . $row['id'] . "'", __LINE__, __FILE__);
			if ($sub_cats_number > 0)
			{	
				$tpl->assign_block_vars('list', array(
					'DIRECTORY' => '<li class="sub"><a class="parent" href="javascript:show_cat_contents(' . $row['id'] . ', 0);"><i class="fa fa-plus-square-o" id="img2_' . $row['id'] . '"></i><i class="fa fa-folder" id ="img_' . $row['id'] . '"></i></a><a id="class_' . $row['id'] . '" href="javascript:open_cat(' . $row['id'] . ');">' . $row['title'] . '</a><span id="cat_' . $row['id'] . '"></li>'
				));
			}
			else
			{
				$tpl->assign_block_vars('list', array(
					'DIRECTORY' => '<li class="sub"><a id="class_' . $row['id'] . '" href="javascript:open_cat(' . $row['id'] . ');"><i class="fa fa-folder"></i>' . $row['title'] . '</a><span id="cat_' . $row['id'] . '"></span></li>'
				));
			}
		}
		$this->sql_querier->query_close($result);

		return $tpl;
	}
}
?>