<?php
/*##################################################
 *                           AdminMenuDisplayResponse.class.php
 *                            -------------------
 *   begin                : October 18 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc the response
 * @package {@package}
 */
class AdminMenuDisplayResponse extends AbstractResponse
{
	/**
	 * @var Template
	 */
	private $full_view;
	private $links = array();

	public function __construct(View $view)
	{
		$env = new AdminDisplayGraphicalEnvironment();
		$this->full_view = new FileTemplate('admin/AdminMenuDisplayResponse.tpl');
		$this->full_view->put('content', $view);
		$this->display_kernel_message($this->full_view);
		parent::__construct($env , $this->full_view);
	}

	public function set_title($title)
	{
		$this->full_view->put_all(array('TITLE' => $title));
	}

	public function add_link($name, $url, $img)
	{
		$this->links[] = array(
		    'LINK' => $name,
		    'U_LINK' => Url::to_rel($url),
		    'U_IMG' => Url::to_rel($img)
		);
	}

	public function send()
	{
		$this->full_view->put('links', $this->links);
		parent::send();
	}
	
	protected function display_kernel_message(View $template)
	{
		$request = AppContext::get_request();
		if ($request->has_cookieparameter('message'))
		{
			$message = $request->get_cookie('message');
			$message_type = $request->has_cookieparameter('message_type') ? $request->get_cookie('message_type') : MessageHelper::SUCCESS;
			$message_duration = $request->has_cookieparameter('message_duration') ? $request->get_cookie('message_duration') : 5;
			
			if (!empty($message))
				$template->put('KERNEL_MESSAGE', MessageHelper::display($message, $message_type, $message_duration));
			
			$response = AppContext::get_response();
			$response->delete_cookie('message');
			$response->delete_cookie('message_type');
			$response->delete_cookie('message_duration');
		}
	}
}
?>