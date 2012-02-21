<?php
/*##################################################
 *		                    FaqSearchable.class.php
 *                            -------------------
 *   begin                : February 21, 2012
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

class FaqSearchable extends AbstractSearchableExtensionPoint
{
	private $sql_querier;

	public function __construct()
	{
		$this->sql_querier = PersistenceContext::get_sql();
		parent::__construct(false, true);
	}
	
	/**
	 * @desc Returns the SQL query which will return the result of the researched keywords
	 * @param $args string[] parameters of the research
	 * @return string The SQL query corresponding to the research.
	 */
	public function get_search_request($args)
    {
        global $Cache;
		$Cache->load('faq');

        $weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
        $Cats = new FaqCats();
        $auth_cats = array();
        $Cats->build_children_id_list(0, $auth_cats);

        $auth_cats = !empty($auth_cats) ? " AND f.idcat IN (" . implode($auth_cats, ',') . ") " : '';

        $request = "SELECT " . $args['id_search'] . " AS id_search,
            f.id AS id_content,
            f.question AS title,
            ( 2 * FT_SEARCH_RELEVANCE(f.question, '" . $args['search'] . "') + FT_SEARCH_RELEVANCE(f.answer, '" . $args['search'] . "') ) / 3 * " . $weight . " AS relevance, "
            . $this->sql_querier->concat("'../faq/faq.php?id='","f.idcat","'&amp;question='","f.id","'#q'","f.id") . " AS link
            FROM " . PREFIX . "faq f
            WHERE ( FT_SEARCH(f.question, '" . $args['search'] . "') OR FT_SEARCH(f.answer, '" . $args['search'] . "') )" . $auth_cats
            . " ORDER BY relevance DESC " . $this->sql_querier->limit(0, FAQ_MAX_SEARCH_RESULTS);

        return $request;
    }


    /**
     * @desc Return the array containing the result's data list
     * @param &string[][] $args The array containing the result's id list
     * @return string[] The array containing the result's data list
     */
    public function compute_search_results($args)
    {
        $results_data = array();

        $results =& $args['results'];
        $nb_results = count($results);

        $ids = array();
        for ($i = 0; $i < $nb_results; $i++)
            $ids[] = $results[$i]['id_content'];

        $request = "SELECT idcat, id, question, answer
            FROM " . PREFIX . "faq
            WHERE id IN (" . implode(',', $ids) . ")";

        $request_results = $this->sql_querier->query_while ($request, __LINE__, __FILE__);
        while ($row = $this->sql_querier->fetch_assoc($request_results))
        {
            $results_data[] = $row;
        }
        $this->sql_querier->query_close($request_results);

        return $results_data;
    }

    /**
     *  @desc Return the string to print the result
     *  @param &string[] $result_data the result's data
     *  @return string[] The string to print the result of a search element
     */
    function parse_search_result($result_data)
    {
        $tpl = new FileTemplate('faq/search_result.tpl');

        $tpl->put_all(array(
            'U_QUESTION' => PATH_TO_ROOT . '/faq/faq.php?id=' . $result_data['idcat'] . '&amp;question=' . $result_data['id'] . '#q' . $result_data['id'],
            'QUESTION' => $result_data['question'],
            'ANSWER' => FormatingHelper::second_parse($result_data['answer'])
        ));

        return $tpl->render();
    }
}
?>