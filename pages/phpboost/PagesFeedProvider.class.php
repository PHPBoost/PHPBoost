<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Alain091 <alain091@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2015 06 29
 * @since       PHPBoost 3.0 - 2011 08 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class PagesFeedProvider implements FeedProvider
{
	function get_feeds_list()
	{
		global $LANG;

		require_once(PATH_TO_ROOT.'/pages/pages_functions.php');

		$cats_tree = new FeedsCat('pages', 0, $LANG['root']);
		$categories = PagesCategoriesCache::load()->get_categories();
		build_pages_cat_children($cats_tree, array_values($categories));
		$feeds = new FeedsList();
		$feeds->add_feed($cats_tree, Feed::DEFAULT_FEED_NAME);
		return $feeds;
	}

	function get_feed_data_struct($idcat = 0, $name = '')
	{
		global $LANG;

		$querier = PersistenceContext::get_querier();
		$pages_config = PagesConfig::load();

		if (!defined('READ_PAGE'))
			require_once(PATH_TO_ROOT.'/pages/pages_defines.php');
		load_module_lang('pages');

		$data = new FeedData();

		$data->set_title($LANG['pages_rss_desc']);
		$data->set_date(new Date());
		$data->set_link(SyndicationUrlBuilder::rss('pages', $idcat));
		$data->set_host(HOST);
		$data->set_desc($LANG['pages_rss_desc']);
		$data->set_lang($LANG['xml_lang']);
		$data->set_auth_bit(READ_PAGE);

		$where_clause = !empty($idcat) ? ' WHERE p.id_cat = :idcat'  : '';
		$results = $querier->select('SELECT p.*
			FROM ' . PREFIX . 'pages p ' .
				$where_clause . '
			ORDER BY p.timestamp DESC LIMIT :limit OFFSET 0', array(
				'idcat' => $idcat,
				'limit' => 10));

		// Generation of the feed's items
		foreach ($results as $row)
		{
			$item = new FeedItem();

			$link = new Url(PagesUrlBuilder::get_link_item($row['encoded_title']));

			$item->set_title(stripslashes($row['title']));
			$item->set_link($link);
			$item->set_guid($link);
			$item->set_desc(preg_replace('`\[page\](.+)\[/page\]`uU', '<br /><strong>$1</strong><hr />', FormatingHelper::second_parse($row['contents'])));
			$item->set_date(new Date($row['timestamp'], Timezone::SERVER_TIMEZONE));
			$item->set_auth(empty($row['auth']) ? $pages_config->get_authorizations() : TextHelper::unserialize($row['auth']));

			$data->add_item($item);
		}
		$results->dispose();

		return $data;
	}
}
?>
