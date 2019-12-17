<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 24
 * @since       PHPBoost 3.0 - 2010 02 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class WikiFeedProvider implements FeedProvider
{
	public function get_feeds_list()
	{
		global $LANG;

		require_once(PATH_TO_ROOT.'/wiki/wiki_functions.php');

		$cats_tree = new FeedsCat('wiki', 0, $LANG['root']);
		$categories = WikiCategoriesCache::load()->get_categories();
		build_wiki_cat_children($cats_tree, array_values($categories));
		$feeds = new FeedsList();
		$feeds->add_feed($cats_tree, Feed::DEFAULT_FEED_NAME);
		return $feeds;
	}

	public function get_feed_data_struct($idcat = 0, $name = '')
	{
		$querier = PersistenceContext::get_querier();
		global $LANG;

		load_module_lang('wiki');

		$categories = WikiCategoriesCache::load()->get_categories();
		$config = WikiConfig::load();
		$parameters = array('limit' => 20);
		if (($idcat > 0) && array_key_exists($idcat, $categories))//Catégorie
		{
			$desc = sprintf($LANG['wiki_rss_cat'], stripslashes($categories[$idcat]['title']));
			$where = 'AND a.id_cat = :idcat';
			$parameters['idcat'] = $idcat;
		}
		else //Sinon derniers messages
		{
			$desc = sprintf($LANG['wiki_rss_last_articles'], ($config->get_wiki_name() ? $config->get_wiki_name() : $LANG['wiki']));
			$where = '';
		}

		$data = new FeedData();

		$data->set_title($config->get_wiki_name() ? $config->get_wiki_name() : $LANG['wiki']);
		$data->set_date(new Date());
		$data->set_link(SyndicationUrlBuilder::rss('wiki', $idcat));
		$data->set_host(HOST);
		$data->set_desc($desc);
		$data->set_lang($LANG['xml_lang']);

		// Last news
		$results = $querier->select('SELECT a.title, a.encoded_title, c.content, c.timestamp
            FROM ' . PREFIX . 'wiki_articles a
            LEFT JOIN ' . PREFIX . 'wiki_contents c ON c.id_contents = a.id_contents
            WHERE a.redirect = 0 ' . $where . '
            ORDER BY c.timestamp DESC LIMIT :limit OFFSET 0', $parameters);

		// Generation of the feed's items
		foreach ($results as $row)
		{
			$item = new FeedItem();

			$item->set_title(stripslashes($row['title']));
			$link = new Url('/wiki/' . url('wiki.php?title=' . $row['encoded_title'], $row['encoded_title']));
			$item->set_link($link);
			$item->set_guid($link);
			$item->set_desc(FormatingHelper::second_parse($row['content']));
			$item->set_date(new Date($row['timestamp'], Timezone::SERVER_TIMEZONE));

			$data->add_item($item);
		}
		$results->dispose();

		return $data;
	}
}
?>
