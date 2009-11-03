<?php
/*##################################################
 *                           BlogControllerBlogList.class.php
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


import('/blog/models/blog');


class BlogControllerBlogList implements Controller
{
	/**
	 * @var View
	 */
	private $view;
	
	/**
	 * @var SiteDisplayResponse
	 */
	private $response;
	
	public function execute(HTTPRequest $request)
	{
		$this->load_env();
		
		$blogs = BlogDAO::instance()->find_all(20, 0, array(array(
		     'column' => 'title',
		     'way' => DAO::ORDER_BY_DESC
	     )));
		$this->view->assign_vars(array(
            'U_CREATE' => Blog::global_action_url(Blog::GLOBAL_ACTION_CREATE)->absolute(),
            'U_LIST' => Blog::global_action_url(Blog::GLOBAL_ACTION_LIST)->absolute()
		));

		foreach ($blogs as $blog)
		{
			$this->view->assign_block_vars('blogs', array(
                'TITLE' => $blog->get_title(),
                'DESCRIPTION' => second_parse($blog->get_description()),
                'U_DETAILS' => $blog->action_url(Blog::ACTION_DETAILS)->absolute(),
                'U_EDIT' => $blog->action_url(Blog::ACTION_EDIT)->absolute(),
                'U_DELETE' => $blog->action_url(Blog::ACTION_DELETE)->absolute(),
                'USER' => $blog->get_login()
			));
		}

		return $this->response;
	}
	
	private function load_env()
	{
		
        
        global $BLOG_LANGS;
        load_module_lang('blog');
        
        $this->view = new View('blog/list.tpl');
        $this->view->add_lang($BLOG_LANGS);
        $this->response = new SiteDisplayResponse($this->view);
        $env = $this->response->get_graphical_environment();

        $env->set_page_title($BLOG_LANGS['blogs_list']);
        
        $module_discovery_service = new ModulesDiscoveryService();
        $module = $module_discovery_service->get_module('blog');
        
        AppContext::get_breadcrumb()->add($module->get_name(),
        Blog::global_action_url(Blog::GLOBAL_ACTION_LIST)->absolute());
	}
}
?>