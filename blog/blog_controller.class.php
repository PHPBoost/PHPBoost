<?php
/*##################################################
 *                           controller.class.php
 *                            -------------------
 *   begin                : June 08 2009
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

// TODO change to import('mvc/controller');
mimport('blog/mvc/controller');
mimport('blog/mvc/model');
mimport('blog/model/blog');

class BlogController extends AbstractController
{
	public function init()
	{
		echo '<br />Init()<hr />';
	}

	public function destroy()
	{
		echo '<hr />Destroy()<br />';
	}

	function view()
	{
		echo 'Je suis view<br />';
		try {
			BlogDAO::instance()->find_by_criteria(BlogDAO::instance()->create_criteria());
		} catch (Exception $ex) {
			echo '<hr />' . $ex->getMessage() . '<hr />';
			echo '<pre>';print_r($ex->getTraceAsString());echo '</pre><hr />';
		}
	}

	function view_by_id($id)
	{
		echo 'Je suis view by id avec comme id : ' . $id;
	}

	function special($id)
	{
		echo 'Je suis special : ' . $id . '<br />';
		echo 'param1=' . $_GET['param1'] . '&amp;param2=' . $_GET['param2'];
	}
}
?>