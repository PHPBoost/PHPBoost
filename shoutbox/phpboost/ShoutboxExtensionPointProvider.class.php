<?php
/*##################################################
 *                        shoutboxExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : July 7, 2008
 *   copyright            : (C) 2008 R�gis Viarre
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



class ShoutboxExtensionPointProvider extends ExtensionPointProvider
{
	private $sql_querier;
	
    public function __construct()
    {
        $this->sql_querier = PersistenceContext::get_sql();
        parent::__construct('shoutbox');
    }

	//Actions journali�re.
	function on_changeday()
	{
		global $Cache, $CONFIG_SHOUTBOX;
		
		$Cache->load('shoutbox'); //$CONFIG_SHOUTBOX en global.

		if ($CONFIG_SHOUTBOX['shoutbox_max_msg'] != -1)
		{
			//Suppression des messages en surplus dans la shoutbox.
			$this->sql_querier->query_inject("SELECT @compt := id AS compt
			FROM " . PREFIX . "shoutbox
			ORDER BY id DESC
			" . $this->sql_querier->limit(0, $CONFIG_SHOUTBOX['shoutbox_max_msg']), __LINE__, __FILE__);
			$this->sql_querier->query_inject("DELETE FROM " . PREFIX . "shoutbox WHERE id < @compt", __LINE__, __FILE__);
		}
	}
}

?>