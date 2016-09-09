<?php
/*##################################################
 *                          ShoutboxAjaxRefreshMessagesController.class.php
 *                            -------------------
 *   begin                : December 02, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class ShoutboxAjaxRefreshMessagesController extends AbstractController
{
	private $lang;
	private $view;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->build_view();
		return new SiteNodisplayResponse($this->view);
	}
	
	private function build_view()
	{
		$this->lang = LangLoader::get('common', 'shoutbox');
		$this->view = new FileTemplate('shoutbox/ShoutboxAjaxMessagesBoxController.tpl');
		$this->view->add_lang($this->lang);
		
		$config = ShoutboxConfig::load();
		
		$this->view->put('C_DISPLAY_DATE', $config->is_date_displayed());
		
		$result = PersistenceContext::get_querier()->select('SELECT *
		FROM ' . ShoutboxSetup::$shoutbox_table . ' s
		LEFT JOIN ' . DB_TABLE_MEMBER . ' m ON m.user_id = s.user_id
		ORDER BY s.timestamp DESC
		' . ($config->is_shout_max_messages_number_enabled() ? 'LIMIT ' . $config->get_shout_max_messages_number() : ''));
		
		while ($row = $result->fetch())
		{
			$shoutbox_message = new ShoutboxMessage();
			$shoutbox_message->set_properties($row);
			
			$this->view->assign_block_vars('messages', $shoutbox_message->get_array_tpl_vars());
		}
		$result->dispose();
	}
	
	public static function get_view()
	{
		$object = new self();
		$object->build_view();
		return $object->view;
	}
}
?>
