<?php
/*##################################################
 *                              guestbookExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : July 7, 2008
 *   copyright            : (C) 2008 Régis Viarre
 *   email                : crowkait@phpboost.com
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

if (defined('PHPBOOST') !== true) exit;

class GuestbookExtensionPointProvider extends ExtensionPointProvider
{
	private $sql_querier;

    public function __construct()
    {
        $this->sql_querier = PersistenceContext::get_sql();
        parent::__construct('guestbook');
    }

	//Récupération du cache.
	function get_cache()
	{
		$guestbook_code = "\n\n" . 'global $_guestbook_rand_msg;' . "\n";
		$guestbook_code .= "\n" . '$_guestbook_rand_msg = array();' . "\n";
		$result = $this->sql_querier->query_while("SELECT g.id, g.login, g.user_id, g.timestamp, m.login as mlogin, g.contents
		FROM " . PREFIX . "guestbook g
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
		ORDER BY g.timestamp DESC
		" . $this->sql_querier->limit(0, 10), __LINE__, __FILE__);
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			$guestbook_code .= '$_guestbook_rand_msg[] = array(\'id\' => ' . var_export($row['id'], true) . ', \'contents\' => ' . var_export(nl2br(TextHelper::substr_html(strip_tags(FormatingHelper::second_parse($row['contents'])), 0, 150)), true) . ', \'user_id\' => ' . var_export($row['user_id'], true) . ', \'login\' => ' . var_export($row['login'], true) . ');' . "\n";
		}
		$this->sql_querier->query_close($result);

		return $guestbook_code;
	}
}

?>