<?php
/*##################################################
 *   	FaqExtensionPointProvider.class.php
 *   	-----------------------------------
 *   begin                : April 9, 2008
 *   copyright            : (C) 2008 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

define('FAQ_MAX_SEARCH_RESULTS', 100);

class FaqExtensionPointProvider extends ExtensionPointProvider
{
	private $sql_querier;

    public function __construct()
    {
        $this->sql_querier = PersistenceContext::get_sql();
        parent::__construct('faq');
    }

	//Récupération du cache.
	public function get_cache()
	{
		$string = 'global $FAQ_CATS, $RANDOM_QUESTIONS;' . "\n\n";

		//List of categories and their own properties
		$string .= '$FAQ_CATS = array();' . "\n\n";
		$string .= '$FAQ_CATS[0][\'name\'] = \'\';' . "\n";
		$result = $this->sql_querier->query_while("SELECT id, id_parent, c_order, auth, name, visible, display_mode, image, num_questions, description
		FROM " . PREFIX . "faq_cats
		ORDER BY id_parent, c_order", __LINE__, __FILE__);
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			$string .= '$FAQ_CATS[' . $row['id'] . '] = ' .
				var_export(array(
				'id_parent' => $row['id_parent'],
				'order' => $row['c_order'],
				'name' => $row['name'],
				'desc' => $row['description'],
				'visible' => (bool)$row['visible'],
				'display_mode' => $row['display_mode'],
				'image' => $row['image'],
				'num_questions' => $row['num_questions'],
				'description' => $row['description'],
				'auth' => unserialize($row['auth'])
				),
			true)
			. ';' . "\n";
		}

		//Random questions
		$query = $this->sql_querier->query_while ("SELECT id, question, idcat FROM " . PREFIX . "faq LIMIT 0, 20", __LINE__, __FILE__);
		$questions = array();
		while ($row = $this->sql_querier->fetch_assoc($query))
			$questions[] = array('id' => $row['id'], 'question' => $row['question'], 'idcat' => $row['idcat']);

		$string .= "\n" . '$RANDOM_QUESTIONS = ' . var_export($questions, true) . ';';

		return $string;
	}

	public function home_page()
	{
		return new FaqHomePageExtensionPoint();
	}
	
    public function sitemap()
    {
    	return new FaqSitemapExtensionPoint();
    }
	
	public function feeds()
	{
		return new FaqFeedProvider();
	}
	
	public function menus()
	{
		return new FaqMenusExtensionPoint();
	}
	
	public function search()
	{
		return new FaqSearchable();
	}
}
?>