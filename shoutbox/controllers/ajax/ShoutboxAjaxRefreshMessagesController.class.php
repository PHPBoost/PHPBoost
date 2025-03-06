<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 16
 * @since       PHPBoost 4.1 - 2014 12 02
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ShoutboxAjaxRefreshMessagesController extends AbstractController
{
	private $view;

	public function execute(HTTPRequestCustom $request)
	{
		$this->build_view();
		return new JSONResponse(array('messages' => $this->view->render()));
	}

	private function build_view()
	{
		$this->view = new FileTemplate('shoutbox/ShoutboxAjaxMessagesBoxController.tpl');
		$this->view->add_lang(LangLoader::get_all_langs());

		$config = ShoutboxConfig::load();

		$this->view->put('C_DISPLAY_DATE', $config->is_date_displayed());

		$result = PersistenceContext::get_querier()->select('SELECT *
		FROM ' . ShoutboxSetup::$shoutbox_table . ' s
		LEFT JOIN ' . DB_TABLE_MEMBER . ' m ON m.user_id = s.user_id
		ORDER BY s.timestamp DESC
		' . ($config->is_shout_max_messages_number_enabled() ? 'LIMIT ' . $config->get_shout_max_messages_number() : ''));

		while ($row = $result->fetch())
		{
			$shoutbox_message = new ShoutboxItem();
			$shoutbox_message->set_properties($row);

			$this->view->assign_block_vars('messages', $shoutbox_message->get_template_vars());
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
