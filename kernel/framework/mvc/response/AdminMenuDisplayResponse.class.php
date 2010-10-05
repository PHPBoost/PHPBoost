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
	private $full_view;

	public function __construct(Template $view)
	{
		$env = new AdminDisplayGraphicalEnvironment();

		$this->full_view = new FileTemplate('admin/AdminMenuDisplayResponse.tpl');
		$this->full_view->add_subtemplate('content', $view);

		parent::__construct($env , $this->full_view);
	}

	public function set_title($title)
	{
		$this->full_view->assign_vars(array('TITLE' => $title));
	}

	public function add_link($name, $url, $img)
	{
		$this->full_view->assign_block_vars('links', array(
		    'LINK' => $name,
		    'U_LINK' => ltrim(Url::to_rel($url), '/'),
		    'U_IMG' => ltrim($img, '/')
		));
	}
}
?>