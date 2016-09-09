<?php
/*##################################################
 *                           JSONResponse.class.php
 *                            -------------------
 *   begin                : October 31 2010
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
 * @desc the response
 * @package {@package}
 */
class JSONResponse implements Response
{
	private $json;

	public function __construct(array $json_object)
	{
		$session = AppContext::get_session();
		$session->no_session_location();
		$session->update_location('');

		$this->json = json_encode($json_object);
	}

	public function send()
	{
		$response = AppContext::get_response();
		$response->set_header('Content-type', 'application/json; charset=UTF-8');
		echo $this->json;
	}
}

?>