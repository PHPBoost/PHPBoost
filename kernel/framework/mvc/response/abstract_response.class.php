<?php
/*##################################################
 *                           abstract_response.class.php
 *                            -------------------
 *   begin                : October 18 2009
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

import('mvc/response/response');

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc the response
 * @package mvc
 * @subpackage response
 */
abstract class AbstractResponse implements Response
{
	/**
	 * @var View
	 */
	private $view;

	/**
	 * @var GraphicalEnvironment
	 */
	private $graphical_environment;

	protected function __construct($graphical_environment, $view)
	{
		$this->graphical_environment = $graphical_environment;
		$this->view = $view;
	}

	public function get_graphical_environment()
	{
		return $this->graphical_environment;
	}

	public function send()
	{
		$this->graphical_environment->display_header();
		$this->view->parse();
		$this->graphical_environment->display_footer();
	}
}
?>