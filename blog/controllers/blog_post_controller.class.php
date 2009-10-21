<?php
/*##################################################
 *                           blog_post_controller.class.php
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

import('io/template/Template');
import('modules/ModulesDiscoveryService');

import('/blog/controllers/abstract_blog_controller');
import('/blog/models/blog_post');


class BlogPostController extends AbstractBlogController
{
	public function posts() {}
	public function view() {}
	public function create($blog_post = null, $error_message = null, $blog_post_id = -1) {}
	public function create_valid($blog_post_id = -1) {}
	public function edit($blog_post_id) {}
	public function edit_valid($blog_post_id) {}
	
	public function delete($blog_post_id)
	{
		$blog_post = BlogPostDAO::instance()->find_by_id($blog_post_id);
		$redirect_url = $blog_post->action_url(BlogPost::ACTION_LIST)->absolute();
		
		BlogPostDAO::instance()->delete($blog_post_id);
        redirect($redirect_url);
	}
}
?>