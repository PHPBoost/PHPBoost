<?php
class DownloadSearchable extends AbstractSearchableExtensionPoint
{
	private $sql_querier;

	public function __construct()
	{
		$this->sql_querier = PersistenceContext::get_sql();
		parent::__construct(false, true);
	}
	
	public function get_search_request($args)
    /**
     *  Renvoie la requête de recherche
     */
    {
        global $Cache;
        $weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		$Cache->load('download');

        $cats = new DownloadCats();
        $auth_cats = array();
        $cats->build_children_id_list(0, $auth_cats);

        $auth_cats = !empty($auth_cats) ? " AND d.idcat IN (" . implode($auth_cats, ',') . ") " : '';

        $request = "SELECT " . $args['id_search'] . " AS id_search,
            d.id AS id_content,
            d.title AS title,
            ( 3 * FT_SEARCH(d.title, '" . $args['search'] . "') +
            2 * FT_SEARCH_RELEVANCE(d.short_contents, '" . $args['search'] . "') +
            FT_SEARCH_RELEVANCE(d.contents, '" . $args['search'] . "') ) / 6 * " . $weight . " AS relevance, "
            . $this->sql_querier->concat("'" . PATH_TO_ROOT . "/download/download.php?id='","d.id") . " AS link
            FROM " . PREFIX . "download d
            WHERE ( FT_SEARCH(d.title, '" . $args['search'] . "') OR
            FT_SEARCH(d.short_contents, '" . $args['search'] . "') OR
            FT_SEARCH(d.contents, '" . $args['search'] . "') )" . $auth_cats
            . " ORDER BY relevance DESC " . $this->sql_querier->limit(0, DOWNLOAD_MAX_SEARCH_RESULTS);

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

        $request = "SELECT d.id, d.idcat, d.title, d.short_contents, d.url, d.image, d.count, d.timestamp, d.approved, d.visible, notes.average_notes, com.number_comments
            FROM " . PREFIX . "download d
            LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON d.id = com.id_in_module AND com.module_id = 'download'
            LEFT JOIN " . DB_TABLE_AVERAGE_NOTES . " notes ON d.id = notes.id_in_module AND module_name = 'download'
            WHERE d.id IN (" . implode(',', $ids) . ") AND d.visible = 1 AND d.approved = 1";

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
    public function parse_search_result($result_data)
    {
        global $Cache, $LANG, $DOWNLOAD_LANG;
        $Cache->load('download');

        load_module_lang('download'); //Chargement de la langue du module.
        $tpl = new FileTemplate('download/download_generic_results.tpl');


        $date = new Date(DATE_TIMESTAMP, TIMEZONE_USER, $result_data['timestamp']);
         //Notes
         
        $notation = new Notation();
		$notation->set_module_name('download');
		$notation->set_id_in_module($result_data['id']);
		$notation->set_notation_scale(DownloadConfig::load()->get_notation_scale());
		$notation->set_average_notes($result_data['average_notes']);
		
        $tpl->put_all(array(
            'L_ADDED_ON' => sprintf($DOWNLOAD_LANG['add_on_date'], $date->format(DATE_FORMAT_TINY, TIMEZONE_USER)),
            'U_LINK' => url(PATH_TO_ROOT . '/download/download.php?id=' . $result_data['id']),
            'U_IMG' => $result_data['image'],
            'E_TITLE' => TextHelper::strprotect($result_data['title']),
            'TITLE' => $result_data['title'],
            'SHORT_DESCRIPTION' => FormatingHelper::second_parse($result_data['short_contents']),
            'L_NB_DOWNLOADS' => $DOWNLOAD_LANG['downloaded'] . ' ' . sprintf($DOWNLOAD_LANG['n_times'], $result_data['count']),
            'L_NB_COMMENTS' => $result_data['number_comments'] > 1 ? sprintf($DOWNLOAD_LANG['num_com'], $result_data['number_comments']) : sprintf($DOWNLOAD_LANG['num_coms'], $result_data['number_comments']),
            'L_MARK' => $result_data['average_notes'] > 0 ? NotationService::display_static_image($notation) : ('<em>' . $LANG['no_note'] . '</em>')
        ));

        return $tpl->render();
    }
}
?>