<?php
/*##################################################
 *                       NewsletterModuleUpdateVersion.class.php
 *                            -------------------
 *   begin                : May 22, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class NewsletterModuleUpdateVersion extends ModuleUpdateVersion
{
	private $querier;
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('newsletter');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function execute()
	{
		$tables = $this->db_utils->list_tables(true);
		
		if (in_array(PREFIX . 'newsletter_streams', $tables))
			$this->update_newsletter_streams_table();
	}
	
	private function update_newsletter_streams_table()
	{
		$columns = $this->db_utils->desc_table(PREFIX . 'newsletter_streams');
		
		if (!isset($columns['special_authorizations']))
			$this->db_utils->add_column(PREFIX . 'newsletter_streams', 'special_authorizations', array('type' => 'boolean', 'notnull' => 1, 'default' => 0));
		
		$result = $this->querier->select_rows(PREFIX . 'newsletter_streams', array('id', 'name', 'auth'));
		while ($row = $result->fetch())
		{
			$this->querier->update(PREFIX . 'newsletter_streams', array(
				'rewrited_name' => Url::encode_rewrite($row['name']),
				'special_authorizations' => (int)!empty($row['auth'])
			), 'WHERE id = :id', array('id' => $row['id']));
		}
		$result->dispose();
	}
}
?>