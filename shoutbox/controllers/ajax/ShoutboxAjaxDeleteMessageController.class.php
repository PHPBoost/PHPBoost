<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 4.1 - 2014 12 01
*/

class ShoutboxAjaxDeleteMessageController extends AbstractController
{
	private $shoutbox_message;

	public function execute(HTTPRequestCustom $request)
	{
		$this->get_shoutbox_message($request);

		if ($this->shoutbox_message !== null && $this->check_authorizations())
		{
			$this->delete_message();
			$code = $this->shoutbox_message->get_id();
		}
		else
			$code = -1;

		return new JSONResponse(array('code' => $code));
	}

	private function delete_message()
	{
		AppContext::get_session()->csrf_post_protect();

		ShoutboxService::delete('WHERE id=:id', array('id' => $this->shoutbox_message->get_id()));
	}

	private function get_shoutbox_message(HTTPRequestCustom $request)
	{
		$id = $request->get_int('id', 0);

		if (!empty($id))
		{
			try {
				$this->shoutbox_message = ShoutboxService::get_message('WHERE id=:id', array('id' => $id));
			} catch (RowNotFoundException $e) {
			}
		}
	}

	private function check_authorizations()
	{
		return $this->shoutbox_message->is_authorized_to_delete() && !AppContext::get_current_user()->is_readonly();
	}
}
?>
