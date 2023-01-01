<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 18
 * @since       PHPBoost 4.1 - 2014 12 01
*/

class ShoutboxAjaxDeleteMessageController extends AbstractController
{
	private $item;

	public function execute(HTTPRequestCustom $request)
	{
		$this->get_item($request);

		if ($this->item !== null && $this->check_authorizations())
		{
			$this->delete_message();
			$code = $this->item->get_id();
		}
		else
			$code = -1;

		return new JSONResponse(array('code' => $code));
	}

	private function delete_message()
	{
		AppContext::get_session()->csrf_post_protect();

		ShoutboxService::delete($this->item->get_id());
		
		HooksService::execute_hook_action('delete', 'shoutbox', $this->item->get_properties());
	}

	private function get_item(HTTPRequestCustom $request)
	{
		$id = $request->get_int('id', 0);

		try {
			$this->item = ShoutboxService::get_item($id);
		} catch (RowNotFoundException $e) {
		}
	}

	private function check_authorizations()
	{
		return $this->item->is_authorized_to_delete() && !AppContext::get_current_user()->is_readonly();
	}
}
?>
