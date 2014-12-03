<?php
/*##################################################
 *                          ShoutboxAjaxDeleteMessageController.class.php
 *                            -------------------
 *   begin                : December 01, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class ShoutboxAjaxDeleteMessageController extends AbstractController
{
	private $shoutbox_message;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->get_shoutbox_message($request);
		
		if ($this->shoutbox_message !== null && $this->check_authorizations())
		{
			$this->delete_message();
			$return = $this->shoutbox_message->get_id();
		}
		else
			$return = -1;
		
		return new SiteNodisplayResponse(new StringTemplate($return));
	}
	
	private function delete_message()
	{
		AppContext::get_session()->csrf_post_protect();
		
		ShoutboxService::delete('WHERE id=:id', array('id' => $this->shoutbox_message->get_id()));
		
		ShoutboxMessagesCache::invalidate();
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
		if (!$this->shoutbox_message->is_authorized_to_delete() || AppContext::get_current_user()->is_readonly())
			return false;
		else
			return true;
	}
}
?>
