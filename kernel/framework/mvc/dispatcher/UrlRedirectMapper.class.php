<?php
/*##################################################
 *                           UrlRedirectMapper.class.php
 *                            -------------------
 *   begin                : November 06 2010
 *   copyright            : (C) 2010 Loic Rouchon
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
 * @desc Redirect to the an url
 * @package {@package}
 */
class UrlRedirectMapper extends AbstractUrlMapper
{
	private $redirect_url;

	/**
	 * @desc build a new UrlDispatcherItem
	 * @param string $redirect_url the url on which the redirection will be done
	 * @param string $capture_regex the regular expression matching the url
	 * and capturing the controller method parameters. By default, match the empty url <code>/</code>
	 * @throws NoSuchControllerException
	 */
	public function __construct($redirect_url, $capture_regex = '`^/?$`')
	{
		$this->redirect_url = $redirect_url;
		parent::__construct($capture_regex);
	}

	/**
	 * @desc Call the controller method if the url match and if the method exists
	 */
	public function call()
	{
		AppContext::get_response()->redirect($this->redirect_url);
	}
}
?>