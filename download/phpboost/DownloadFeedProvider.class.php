<?php
/*##################################################
 *                          DownloadFeedProvider.class.php
 *                            -------------------
 *   begin                : February 07, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

class DownloadFeedProvider implements FeedProvider
{
	function get_feeds_list()
	{
		$dl_cats = new DownloadCats();
		return $dl_cats->get_feeds_list();
	}

	function get_feed_data_struct($idcat = 0, $name = '')
	{
		$querier = PersistenceContext::get_querier();
		require_once(PATH_TO_ROOT . '/download/download_auth.php');

		global $Cache, $LANG, $DOWNLOAD_LANG, $CONFIG_DOWNLOAD, $DOWNLOAD_CATS;
		load_module_lang('download');
		$Cache->load('download');
		$data = new FeedData();

		// Meta-informations generation
		$data->set_title($DOWNLOAD_LANG['xml_download_desc']);
		$data->set_date(new Date());
		$data->set_link(new Url('/syndication.php?m=download&amp;cat=' . $idcat));
		$data->set_host(HOST);
		$data->set_desc($DOWNLOAD_LANG['xml_download_desc']);
		$data->set_lang($LANG['xml_lang']);
		$data->set_auth_bit(DOWNLOAD_READ_CAT_AUTH_BIT);


		// Building Categories to look in
		$cats = new DownloadCats();
		$children_cats = array();
		$cats->build_children_id_list($idcat, $children_cats, RECURSIVE_EXPLORATION, ADD_THIS_CATEGORY_IN_LIST);

		$req = 'SELECT id, idcat, title, contents, timestamp, image
        FROM ' . PREFIX . 'download
        WHERE visible = 1 AND idcat IN :children
        ORDER BY timestamp DESC LIMIT :limit OFFSET 0';
		$results = $querier->select($req, array(
			'children' => $children_cats,
			'limit' => 2 * $CONFIG_DOWNLOAD['nbr_file_max']));

		foreach ($results as $row)
		{
			$item = new FeedItem();

			$link = new Url('/download/download' . url('.php?id=' . $row['id'], '-' . $row['id'] .  '+' . Url::encode_rewrite($row['title']) . '.php'));
			// Adding item's informations
			$item->set_title($row['title']);
			$item->set_link($link);
			$item->set_guid($link);
			$item->set_desc(FormatingHelper::second_parse($row['contents']));
			$item->set_date(new Date(DATE_TIMESTAMP, Timezone::SERVER_TIMEZONE, $row['timestamp']));
			$item->set_image_url($row['image']);
			$item->set_auth($cats->compute_heritated_auth($row['idcat'], DOWNLOAD_READ_CAT_AUTH_BIT, Authorizations::AUTH_PARENT_PRIORITY));

			// Adding the item to the list
			$data->add_item($item);
		}
		$results->dispose();

		return $data;
	}
}
?>
