<?php
/*##################################################
 *   		FaqFeedProvider.class.php
 *   		-------------------------
 *   begin                : August 07, 2011
 *   copyright            : (C) 2011 Alain091
 *   email                : alain091@gmail.com
 *
 *
 *###################################################
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
 *###################################################
 */

class FaqFeedProvider implements FeedProvider
{
	function get_feeds_list()
	{
		$faq_cats = new FaqCats();
		return $faq_cats->get_feeds_list();
	}

	function get_feed_data_struct($idcat = 0, $name = '')
	{
		global $Cache, $LANG, $FAQ_CATS, $FAQ_LANG, $User;
		
		$querier = PersistenceContext::get_querier();
		if (empty($FAQ_LANG))
			require_once PATH_TO_ROOT.'/faq/faq_begin.php';

		$data = new FeedData();

		$data->set_title($FAQ_LANG['xml_faq_desc']);
		$data->set_date(new Date());
		$data->set_link(SyndicationUrlBuilder::rss('faq', $idcat));
		$data->set_host(HOST);
		$data->set_desc($FAQ_LANG['xml_faq_desc']);
		$data->set_lang($LANG['xml_lang']);
		$data->set_auth_bit(FaqAuthorizationsService::READ_AUTHORIZATIONS);

		$cat_clause = !empty($idcat) ? ' AND f.idcat = :idcat' : '';
		$results = $querier->select('SELECT f.*, fc.auth
			FROM ' . PREFIX . 'faq f
			LEFT JOIN ' . PREFIX . 'faq_cats fc ON fc.id = f.idcat
			WHERE (fc.visible = 1 OR f.idcat = 0) ' .
				$cat_clause . '
			ORDER BY f.timestamp DESC LIMIT :limit OFFSET 0', array(
				'idcat' => $idcat,
				'limit' => 10));
				
		if (empty($results))
			return $data;

		// Generation of the feed's items
		foreach ($results as $row)
		{
			$item = new FeedItem();

			$link = FaqUrlBuilder::get_link_question($row['idcat'],$row['id']);

			$item->set_title($row['question']);
			$item->set_link($link);
			$item->set_guid($link);
			$item->set_desc(preg_replace('`\[page\](.+)\[/page\]`U', '<br /><strong>$1</strong><hr />', FormatingHelper::second_parse($row['answer'])));
			$item->set_date(new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['timestamp']));
			$item->set_auth($row['idcat'] == 0 ? $faq_config->get_authorizations() : unserialize($row['auth']));

			$data->add_item($item);
		}
		$results->dispose();

		return $data;
	}
}
?>