<?php
/*##################################################
 *                     WebHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : February 06, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class WebHomePageExtensionPoint implements HomePageExtensionPoint
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
		
		load_module_lang('web');
		
		return $LANG['title_web'];
	}
	
	private function get_view()
	{
		global $Cache, $Bread_crumb, $idwebcat, $Session, $User, $WEB_CAT, $LANG, $WEB_LANG;
		
		require_once(PATH_TO_ROOT . '/web/web_begin.php'); 
		
		$tpl = new FileTemplate('web/web.tpl');
		
		$web_config = WebConfig::load();
		
		$total_link = $this->sql_querier->query("SELECT COUNT(*) FROM " . PREFIX . "web_cat wc
		LEFT JOIN " . PREFIX . "web w ON w.idcat = wc.id
		WHERE w.aprob = 1 AND wc.aprob = 1 AND wc.secure <= '" . $User->get_attribute('level') . "'", __LINE__, __FILE__);
		$total_cat = $this->sql_querier->query("SELECT COUNT(*) as compt FROM " . PREFIX . "web_cat WHERE aprob = 1 AND secure <= '" . $User->get_attribute('level') . "'", __LINE__, __FILE__);
		
		//On créé une pagination si le nombre de catégories est trop important.
		 
		$Pagination = new DeprecatedPagination();
		
		$nbr_column_config = $web_config->get_number_columns();
		$nbr_column = ($total_cat > $nbr_column_config) ? $nbr_column_config : $total_cat;
        $nbr_column = !empty($nbr_column) ? $nbr_column : 1;
		
		$tpl->put_all(array(
			'C_WEB_CAT' => true,
			'C_IS_ADMIN' => $User->check_level(User::ADMIN_LEVEL),
			'PAGINATION' => $Pagination->display('web' . url('.php?p=%d', '-0-0-%d.php'), $total_cat, 'p', $web_config->get_max_nbr_category(), 3),
			'TOTAL_FILE' => $total_link,
			'L_CATEGORIES' => $LANG['categories'],
			'L_EDIT' => $LANG['edit'],
			'L_PROPOSE_LINK' => $LANG['propose_link'],
			'L_HOW_LINK' => $LANG['how_link'],
			'U_WEB_ADD' => url('.php?web=true'),
			'L_WEB' => $LANG['title_web']
		));
		
		//Catégorie disponibles	
		$column_width = floor(100/$nbr_column);
		$result = $this->sql_querier->query_while(
		"SELECT aw.id, aw.name, aw.contents, aw.icon, COUNT(w.id) as count
		FROM " . PREFIX . "web_cat aw
		LEFT JOIN " . PREFIX . "web w ON w.idcat = aw.id AND w.aprob = 1
		WHERE aw.aprob = 1 AND aw.secure <= '" . $User->get_attribute('level') . "'
		GROUP BY aw.id
		ORDER BY aw.class
		" . $this->sql_querier->limit($Pagination->get_first_msg($web_config->get_max_nbr_category(), 'p'), $web_config->get_max_nbr_category()), __LINE__, __FILE__);
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			$tpl->assign_block_vars('cat_list', array(
				'WIDTH' => $column_width,
				'TOTAL' => $row['count'],
				'CAT' => $row['name'],
				'CONTENTS' => $row['contents'],	
				'U_IMG_CAT' => !empty($row['icon']) ? '<a href="' . PATH_TO_ROOT . '/web/web' . url('.php?cat=' . $row['id'], '-' . $row['id'] . '.php') . '"><img src="' . ($row['icon'] == 'web.png' || $row['icon'] == 'web_mini.png' ? PATH_TO_ROOT . '/web/' . $row['icon'] : $row['icon']) . '" alt="" /></a><br />' : '',
				'U_WEB_CAT' => url('.php?cat=' . $row['id'], '-' . $row['id'] . '.php')
			));
		}
		$this->sql_querier->query_close($result);
		return $tpl;
	}
}
?>