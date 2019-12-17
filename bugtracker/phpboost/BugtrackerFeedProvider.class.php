<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2014 01 21
*/

class BugtrackerFeedProvider implements FeedProvider
{
	public function get_feeds_list()
	{
		$list = new FeedsList();

		//unsolved bugs list
		$cats_tree = new FeedsCat('bugtracker', 0, LangLoader::get_message('titles.unsolved', 'common', 'bugtracker'));

		//solved bugs list
		$sub_tree = new FeedsCat('bugtracker', 1, LangLoader::get_message('titles.solved', 'common', 'bugtracker'));
		$cats_tree->add_child($sub_tree);
		$list->add_feed($cats_tree, Feed::DEFAULT_FEED_NAME);

		return $list;
	}

	public function get_feed_data_struct($idcat = 0, $name = '')
	{
		$querier = PersistenceContext::get_querier();
		$lang = LangLoader::get('common', 'bugtracker');

		//Configuration load
		$config = BugtrackerConfig::load();
		$types = $config->get_types();
		$categories = $config->get_categories();
		$severities = $config->get_severities();
		$priorities = $config->get_priorities();
		$versions = $config->get_versions_detected();

		$site_name = GeneralConfig::load()->get_site_name();
		$feed_module_name = $idcat == 1 ? $lang['titles.solved'] : $lang['titles.unsolved'];

		$data = new FeedData();
		$data->set_title($feed_module_name . ' - ' . $site_name);
		$data->set_date(new Date());
		$data->set_link(SyndicationUrlBuilder::rss('bugtracker', $idcat));
		$data->set_host(HOST);
		$data->set_desc($feed_module_name . ' - ' . $site_name);
		$data->set_lang(LangLoader::get_message('xml_lang', 'main'));
		$data->set_auth_bit(BugtrackerAuthorizationsService::READ_AUTHORIZATIONS);

		$results = $querier->select("SELECT bugtracker.*, author.*
		FROM " . BugtrackerSetup::$bugtracker_table . " bugtracker
		LEFT JOIN " . DB_TABLE_MEMBER . " author ON author.user_id = bugtracker.author_id
		WHERE " . ($idcat == 1 ? "(status = '" . Bug::FIXED . "' OR status = '" . Bug::REJECTED . "')" : "status <> '" . Bug::FIXED . "' AND status <> '" . Bug::REJECTED . "'") . "
		ORDER BY " . ($idcat == 1 ? "fix_date" : "submit_date") . " DESC");

		foreach ($results as $row)
		{
			$bug = new Bug();
			$bug->set_properties($row);

			$link = BugtrackerUrlBuilder::detail($bug->get_id() . '-' . $bug->get_rewrited_title());

			$description = FormatingHelper::second_parse($bug->get_contents());
			$description .= '<br /><br />' . $lang['labels.fields.reproductible'] . ' : ' . ($bug->is_reproductible() ? LangLoader::get_message('yes', 'common') : LangLoader::get_message('no', 'common'));

			if ($bug->is_reproductible())
				$description .= '<br />' . FormatingHelper::second_parse($bug->get_reproduction_method()) . '<br />';

			if ($types)
				$description .= '<br />' . $lang['labels.fields.type'] . ' : ' . (isset($types[$bug->get_type()]) ? stripslashes($types[$bug->get_type()]) : $lang['notice.none']);
			if ($categories)
				$description .= '<br />' . $lang['labels.fields.category'] . ' : ' . (isset($categories[$bug->get_category()]) ? stripslashes($categories[$bug->get_category()]) : $lang['notice.none_e']);
			if ($severities)
				$description .= '<br />' . $lang['labels.fields.severity'] . ' : ' . (isset($severities[$bug->get_severity()]) ? stripslashes($severities[$bug->get_severity()]['name']) : $lang['notice.none']);
			if ($priorities)
				$description .= '<br />' . $lang['labels.fields.priority'] . ' : ' . (isset($priorities[$bug->get_priority()]) ? stripslashes($priorities[$bug->get_priority()]) : $lang['notice.none_e']);
			if ($versions)
				$description .= '<br />' . $lang['labels.fields.version'] . ' : ' . (isset($versions[$bug->get_detected_in()]) ? stripslashes($versions[$bug->get_detected_in()]['name']) : $lang['notice.not_defined']);

			$item = new FeedItem();
			$item->set_title($bug->get_title());
			$item->set_link($link);
			$item->set_guid($link);
			$item->set_desc($description);
			$item->set_date($bug->get_submit_date());
			$item->set_auth(BugtrackerAuthorizationsService::READ_AUTHORIZATIONS);
			$data->add_item($item);
		}
		$results->dispose();

		return $data;
	}
}
?>
