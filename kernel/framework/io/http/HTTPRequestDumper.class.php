<?php
/*##################################################
 *                           HTTPRequest.class.php
 *                            -------------------
 *   begin                : October 17 2010
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
 * @desc Dump the submitted HTTP request
 * @package {@package}
 */
class HTTPRequestDumper
{
	private $request;
	private $output;
	private $is_parameter_row_odd = true;

	/**
	 * @desc Returns a formatted dump of the submitted HTTP request
	 */
	public function dump(HTTPRequest $request)
	{
		$this->request = $request;
		$this->output = '<table cellspacing="0" cellpadding="3 5px"><caption class="header">HTTP Request</caption>';
		$this->dump_get();
		$this->dump_post();
		$this->dump_cookie();
		$this->dump_server();
		$this->output .= '</table>';
		return $this->output;
	}

	private function dump_get()
	{
		$this->dump_var('GET', $_GET);
	}

	private function dump_post()
	{
		$this->dump_var('POST', $_POST);
	}

	private function dump_cookie()
	{
		$this->dump_var('COOKIE', $_COOKIE);
	}

	private function dump_server()
	{
		$this->dump_var('SERVER', $_SERVER);
	}

	private function dump_var($title, $parameters)
	{
		if (!empty($parameters))
		{
			$this->add_section($title);
			foreach ($parameters as $key => $value)
			{
				$this->add_parameter($key, $value);
			}
		}
	}

	private function add_section($title)
	{
		$this->output .= '<tr class="section">' .
			'<th colspan="2" style="text-align:left;padding:0 10px;">' . $title . '</th></tr>';
		$this->is_parameter_row_odd = true;
	}

	private function add_parameter($key, $value)
	{
		$row_class = '';
		if ($this->is_parameter_row_odd)
		{
			$row_class = 'parameterOddRow';
		}
		else
		{
			$row_class = 'parameterEvenRow';
		}
		$this->output .= '<tr class="parameterRow ' . $row_class. '">' .
			'<td class="parameterName">' . $key . '</td>' .
			'<td class="parameterValue">' . htmlspecialchars($value) . '</td>' .
		'</tr>' ;
		$this->is_parameter_row_odd = !$this->is_parameter_row_odd;
	}
}
?>