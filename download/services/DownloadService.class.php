<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 23
 * @since       PHPBoost 4.0 - 2014 08 24
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DownloadService
{
	private static $db_querier;

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	 /**
	 * @desc Count items number.
	 * @param string $condition (optional) : Restriction to apply to the list of items
	 */
	public static function count($condition = '', $parameters = array())
	{
		return self::$db_querier->count(DownloadSetup::$download_table, $condition, $parameters);
	}

	 /**
	 * @desc Create a new entry in the database table.
	 * @param string[] $downloadfile : new DownloadFile
	 */
	public static function add(DownloadFile $downloadfile)
	{
		$result = self::$db_querier->insert(DownloadSetup::$download_table, $downloadfile->get_properties());

		return $result->get_last_inserted_id();
	}

	 /**
	 * @desc Update an entry.
	 * @param string[] $downloadfile : DownloadFile to update
	 */
	public static function update(DownloadFile $downloadfile)
	{
		self::$db_querier->update(DownloadSetup::$download_table, $downloadfile->get_properties(), 'WHERE id=:id', array('id' => $downloadfile->get_id()));
	}

	 /**
	 * @desc Update the number of downloads of a file.
	 * @param string[] $downloadfile : DownloadFile to update
	 */
	public static function update_downloads_number(DownloadFile $downloadfile)
	{
		self::$db_querier->update(DownloadSetup::$download_table, array('downloads_number' => $downloadfile->get_downloads_number()), 'WHERE id=:id', array('id' => $downloadfile->get_id()));
	}

	public static function update_views_number(DownloadFile $downloadfile)
	{
		self::$db_querier->update(DownloadSetup::$download_table, array('views_number' => $downloadfile->get_views_number()), 'WHERE id=:id', array('id' => $downloadfile->get_id()));
	}

	 /**
	 * @desc Delete an entry.
	 * @param string $condition : Restriction to apply to the list
	 * @param string[] $parameters : Parameters of the condition
	 */
	public static function delete(int $id)
	{
		if (AppContext::get_current_user()->is_readonly())
        {
            $controller = PHPBoostErrors::user_in_read_only();
            DispatchManager::redirect($controller);
        }
			self::$db_querier->delete(DownloadSetup::$download_table, 'WHERE id=:id', array('id' => $id));

			self::$db_querier->delete(DB_TABLE_EVENTS, 'WHERE module=:module AND id_in_module=:id', array('module' => 'download', 'id' => $id));

			CommentsService::delete_comments_topic_module('download', $id);
			KeywordsService::get_keywords_manager()->delete_relations($id);
			NotationService::delete_notes_id_in_module('download', $id);
	}

	 /**
	 * @desc Return the properties of a downloadfile.
	 * @param string $condition : Restriction to apply to the list
	 * @param string[] $parameters : Parameters of the condition
	 */
	public static function get_downloadfile($condition, array $parameters)
	{
		$row = self::$db_querier->select_single_row_query('SELECT download.*, member.*, notes.average_notes, notes.number_notes, note.note
		FROM ' . DownloadSetup::$download_table . ' download
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = download.author_user_id
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = download.id AND notes.module_name = \'download\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = download.id AND note.module_name = \'download\' AND note.user_id = ' . AppContext::get_current_user()->get_id() . '
		' . $condition, $parameters);

		$downloadfile = new DownloadFile();
		$downloadfile->set_properties($row);
		return $downloadfile;
	}

	public static function clear_cache()
	{
		Feed::clear_cache('download');
		KeywordsCache::invalidate();
		DownloadCache::invalidate();
        CategoriesService::get_categories_manager()->regenerate_cache();
	}
}
?>
