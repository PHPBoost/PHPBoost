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

	private static $container_style = 'border:1px #aaaaaa solid;margin-top:10px;padding-top:0px;';
	private static $header_style = 'text-align:left;font-size:26px;font-weight:bold;background-color:#ffffcc;padding:5px;border-bottom:1px #aaaaaa solid;';
	private static $section_style = 'font-size:18px;background-color:#ffffcc;height:30px;';
	private static $parameter_row_style = '';
	private static $parameter_odd_row_style = 'background-color:#f0f9ff;';
	private static $parameter_even_row_style = 'background-color:#f0fff9;';
	private static $parameter_name_style = 'font-size:14px;font-weight:bold;padding:0 10px;';
	private static $parameter_value_style = 'font-size:14px;';

	/**
	 * @desc Returns a formatted dump of the submitted HTTP request
	 */
	public function dump(HTTPRequest $request)
	{
		$this->request = $request;
		$this->output = '<div style="' . self::$container_style . '">' .
			'<table cellspacing="0" cellpadding="3 5px"><caption style="' . self::$header_style . '">HTTP Request</caption>';
		$this->dump_get();
		$this->dump_post();
		$this->dump_cookie();
		$this->dump_server();
		$this->output .= '</table></div>';
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
		$this->output .= '<tr style="' . self::$section_style . '">' .
			'<th colspan="2" style="text-align:left;padding:0 10px;">' . $title . '</th></tr>';
		$this->is_parameter_row_odd = true;
	}

	private function add_parameter($key, $value)
	{
		$row_style = self::$parameter_row_style;
		if ($this->is_parameter_row_odd)
		{
			$row_style .= self::$parameter_odd_row_style;
		}
		else
		{
			$row_style .= self::$parameter_even_row_style;
		}
		$this->output .= '<tr style="' . $row_style. '">' .
			'<td style="' . self::$parameter_name_style . '">' . $key . '</td>' .
			'<td style="' . self::$parameter_value_style . '">' . htmlspecialchars($value) . '</td>' .
		'</tr>' ;
		$this->is_parameter_row_odd = !$this->is_parameter_row_odd;
	}
}
?>