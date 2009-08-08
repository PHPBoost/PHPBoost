<?php
/*##################################################
 *                      abstract_blog_controller.class.php
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

import('io/template');
import('mvc/controller');
import('modules/modules_discovery_service');

class AbstractBlogController extends AbstractController
{
	protected function init_env($view = null, $items = null)
	{
		global $Bread_crumb;
		if ($view !== null)
		{
			$view->add_lang($this->lang);
		}
		$Bread_crumb->add($this->interface->get_name(), Blog::global_action_url(Blog::GLOBAL_ACTION_LIST)->absolute());
		if ($items !== null)
		{
			foreach ($items as $item => $target)
			{
				$Bread_crumb->add($item, $target);
			}
		}
		require_once PATH_TO_ROOT . '/kernel/header.php';
		$this->initialized = true;
	}

	public function init()
	{
		global $BLOG_LANGS;
		load_module_lang('blog');

		$this->lang = $BLOG_LANGS;
		$this->module_discovery_service = new ModulesDiscoveryService();
		$this->interface = $this->module_discovery_service->get_module('blog');
	}

	public function destroy()
	{
		if ($this->initialized)
		{
			require_once PATH_TO_ROOT . '/kernel/footer.php';
		}
	}

	protected $lang;
	protected $interface;
	protected $module_discovery_service;
	private $initialized = false;
}
?>