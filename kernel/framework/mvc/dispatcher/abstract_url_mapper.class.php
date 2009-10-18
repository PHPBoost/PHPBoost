<?php
/*##################################################
 *                           abstract_url_mapper.class.php
 *                            -------------------
 *   begin                : October 17 2009
 *   copyright            : (C) 2009 Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

import('mvc/dispatcher/url_mapper');

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc Call the controller method matching an url
 */
abstract class AbstractUrlMapper implements UrlMapper
{
	/**
	 * @var string
	 */
	private $capture_regex;
	
	/**
	 * @var string[]
	 */
	private $captured_parameters = array();

	public function __construct($capture_regex)
	{
		$this->capture_regex = $capture_regex;
	}

	public function match(&$url)
	{
		$match = preg_match($this->capture_regex, $url, $this->captured_parameters);
		if ($match)
		{
			if (count($this->captured_parameters) == 2)
			{
				$this->url = $this->captured_parameters[1];
			}
			else
			{
				throw new MalformedUrlControllerMapperRegex($this->controller_name,
				$this->capture_regex, $url);
			}
		}
		return $match;
	}
	
	protected function get_captured_parameters()
	{
		return $this->captured_parameters;
	}
}
?>